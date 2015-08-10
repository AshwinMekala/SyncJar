using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;
using System.Net;
using Newtonsoft.Json;
using System.Data.SQLite;
using System.IO;
using System.Data;
using System.Threading;
using System.Collections.Specialized;
using System.Globalization;

namespace syncjar
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        public static string dirPath = Environment.GetFolderPath(Environment.SpecialFolder.Desktop) + System.IO.Path.DirectorySeparatorChar + "Cloud Drive";
        public static string data = "http://www.syncjar.com/data/";
        public static string device_status = "http://www.syncjar.com/devicestatus";
        public static string device_status_change = "http://www.syncjar.com/devicestatuschange";
        static readonly string[] SizeSuffixes = { "bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB" };

        public static string user_id;
        public static string device_id;
        public static string data_path = "";
        static List<FileInfo> files = new List<FileInfo>();  // List that will hold the files and subfiles in CloudDrive
        static List<DirectoryInfo> folders = new List<DirectoryInfo>(); // List that hold direcotries in CloudDrive
        Stack<int> doSync = new Stack<int>();
        private static System.Timers.Timer poolTimer;

        public MainWindow()
        {
            log("In mainwindow");
            InitializeComponent();
            log("InitializeComponent");
            first();
            log("first function completed");
        }
        public void log(string msg)
        {
            StreamWriter file2 = new StreamWriter(Environment.GetFolderPath(Environment.SpecialFolder.Desktop)  + @"\file.txt", true);
            file2.WriteLine(msg);
            file2.Close();
        }
        private void first()
        {
            log("in first function");

            //connection
            SQLiteConnection m_dbConnection = new SQLiteConnection("Data Source=syncjar;Version=3;");
            m_dbConnection.Open();

            log("sql connction open");

            //check if empty
            string sql = "select count(*) from device;";
            using (SQLiteCommand command = new SQLiteCommand(sql, m_dbConnection))
            {
                int RowCount = 0;
                RowCount = Convert.ToInt32(command.ExecuteScalar());
               
                log("check if registred!");

                if (RowCount == 0)
                {
                    log("show login");

                    login.Visibility = Visibility.Visible;
                }
                else
                {
                    login.Visibility = Visibility.Collapsed;

                    sql = "select * from device;";
                    using (SQLiteCommand cmd = new SQLiteCommand(sql, m_dbConnection))
                    {
                        SQLiteDataReader reader = cmd.ExecuteReader();
                        while (reader.Read())
                        {
                            user_name.Content = "Welcome! " + reader["name"];
                            user_id = reader["user_id"].ToString();
                            device_id = reader["device_id"].ToString();
                        }
                    }
                    dashboard.Visibility = Visibility.Visible;
                    logoutBTN.Visibility = Visibility.Visible;

                    //set drive_info
                    sql = "select * from drive_info;";
                    using (SQLiteCommand setInfo = new SQLiteCommand(sql, m_dbConnection))
                    {
                        using (SQLiteDataReader reader = setInfo.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                folders_count.Content = reader["folders"].ToString();
                                files_count.Content = reader["files"].ToString();
                                disc_size.Content = reader["drive_size"].ToString();
                            }
                        }
                    }

                }
            }
            //close connection
            m_dbConnection.Close();
            if (Directory.Exists(dirPath))
            {
                Thread thread1 = new Thread(new ThreadStart(startSync)) { IsBackground = true };
                thread1.Start();
            }

        }

        private void LoginBTN_Click(object sender, RoutedEventArgs e)
        {
            if (CheckForInternetConnection())
            {
                if (!usernameTB.Text.Equals("") && !PasswordBoxPB.Password.Equals(""))
                {
                    string responsebody;
                    login[] result;
                    using (WebClient client = new WebClient())
                    {
                        System.Collections.Specialized.NameValueCollection reqparm = new System.Collections.Specialized.NameValueCollection();
                        reqparm.Add("user_name", usernameTB.Text);
                        reqparm.Add("password", PasswordBoxPB.Password);
                        byte[] responsebytes = client.UploadValues("http://www.syncjar.com/device", "POST", reqparm);
                        responsebody = Encoding.UTF8.GetString(responsebytes);
                        result = JsonConvert.DeserializeObject<login[]>(responsebody);
                    }

                    if (result[0].status == 1)
                    {
                        //check "Cloud Dir" exists or not
                        if (!Directory.Exists(dirPath))
                        {
                            Directory.CreateDirectory(dirPath);
                        }
                        else
                        {
                            System.IO.DirectoryInfo downloadedMessageInfo = new DirectoryInfo(dirPath);

                            foreach (FileInfo file in downloadedMessageInfo.GetFiles())
                            {
                                file.Delete();
                            }
                            foreach (DirectoryInfo dir in downloadedMessageInfo.GetDirectories())
                            {
                                dir.Delete(true);
                            }
                        }

                        //connection
                        SQLiteConnection m_dbConnection = new SQLiteConnection("Data Source=syncjar;Version=3;");
                        m_dbConnection.Open();

                        //flush all devices rows
                        string sql = "DELETE FROM device";
                        SQLiteCommand command = new SQLiteCommand(sql, m_dbConnection);
                        command.ExecuteNonQuery();

                        //flush all Drive Info
                        sql = "DELETE FROM drive_info";
                        command = new SQLiteCommand(sql, m_dbConnection);
                        command.ExecuteNonQuery();

                        //flush all Files
                        sql = "DELETE FROM files";
                        command = new SQLiteCommand(sql, m_dbConnection);
                        command.ExecuteNonQuery();

                        //insert device details
                        string device_id = result[0].device_id.ToString();
                        string user_id = result[0].user_id.ToString();
                        string user_name = result[0].user_name;
                        sql = "insert into device (device_id, user_id, name) values ( @device_id, @user_id, @user_name)";
                        command = new SQLiteCommand(sql, m_dbConnection);
                        command.Parameters.AddWithValue("@device_id", device_id);
                        command.Parameters.AddWithValue("@user_id", user_id);
                        command.Parameters.AddWithValue("@user_name", user_name);
                        command.ExecuteNonQuery();

                        //insert drive info
                        sql = "insert into drive_info (folders, files, drive_size) values ( @folders, @files, @drive_size)";
                        command = new SQLiteCommand(sql, m_dbConnection);
                        command.Parameters.AddWithValue("@folders", "0 Folders");
                        command.Parameters.AddWithValue("@files", "0 Files");
                        command.Parameters.AddWithValue("@drive_size", "0 KB Drive Size");
                        command.ExecuteNonQuery();

                        sql = "select * from device;";
                        using (SQLiteCommand cmd = new SQLiteCommand(sql, m_dbConnection))
                        {
                            SQLiteDataReader reader = cmd.ExecuteReader();
                            while (reader.Read())
                            {
                                MainWindow.device_id = device_id;
                                MainWindow.user_id = user_id;
                            }
                        }

                        //close connection
                        m_dbConnection.Close();

                        onlineSync();
                        first();


                    }
                    else
                        error.Content = "Wrong Password!";
                    error.Visibility = Visibility.Visible;
                }
                else
                    error.Content = "Enter Details!";
                error.Visibility = Visibility.Visible;
            }
            else
            {
                error.Visibility = Visibility.Visible;
                error.Content = "Please Connect to Internet !";
            }
        }

        private void logoutBTN_Click(object sender, RoutedEventArgs e)
        {
            using (SQLiteConnection m_dbConnection = new SQLiteConnection("Data Source=syncjar;Version=3;"))
            {
                m_dbConnection.Open();

                //flush all devices rows
                string sql = "DELETE FROM device";
                SQLiteCommand command = new SQLiteCommand(sql, m_dbConnection);
                command.ExecuteNonQuery();
            }
            DeleteDirectory(dirPath);

            Application.Current.Shutdown();

        }

        void App_Startup(object sender, StartupEventArgs e)
        {
            // Application is running 
            // Process command line args 
            bool startMinimized = false;
            for (int i = 0; i != e.Args.Length; ++i)
            {
                if (e.Args[i] == "/StartMinimized")
                {
                    startMinimized = true;
                }
            }

            // Create main application window, starting minimized if specified
            MainWindow mainWindow = new MainWindow();
            if (startMinimized)
            {
                mainWindow.WindowState = WindowState.Minimized;
            }
            mainWindow.Show();
        }
        void startSync()
        {
            if (CheckForInternetConnection())
                offlineSync();

            FileSystemWatcher watcher = new FileSystemWatcher();
            watcher.Path = dirPath;
            watcher.NotifyFilter = NotifyFilters.LastAccess | NotifyFilters.LastWrite
           | NotifyFilters.FileName | NotifyFilters.DirectoryName;
            watcher.Filter = "*.*";
            watcher.Changed += new FileSystemEventHandler(doSyncMethod);
            watcher.Created += new FileSystemEventHandler(doSyncMethod);
            watcher.Deleted += new FileSystemEventHandler(doSyncMethod);
            watcher.Renamed += new RenamedEventHandler(doSyncMethod);
            watcher.EnableRaisingEvents = true;

            poolTimer = new System.Timers.Timer(5000);
            poolTimer.Elapsed += doSyncPoolTimer;
            poolTimer.Enabled = true;

            sync(watcher, poolTimer);
        }

        private void doSyncPoolTimer(object sender, System.Timers.ElapsedEventArgs e)
        {
            doSync.Push(1);
        }
        public void doSyncMethod(object sender, FileSystemEventArgs e)
        {
            doSync.Push(1);
        }
        private void sync(FileSystemWatcher watcher, System.Timers.Timer poolTimer)
        {
            if (CheckForInternetConnection())
            {
                if (doSync.Count != 0)
                {
                    offlineSync();
                    doSync.Pop();
                }
                else if (onlineChanges())
                {
                    watcher.EnableRaisingEvents = false;
                    poolTimer.Enabled = false;
                    onlineSync();
                    ChangeStatus();
                    watcher.EnableRaisingEvents = true;
                    poolTimer.Enabled = true;
                }

                DateTime now = DateTime.Now;
                this.Dispatcher.Invoke((Action)(() =>
                {
                    last_sync.Content = "Last Sync : " + now;
                }));
            }
            Thread.Sleep(1000);
            sync(watcher, poolTimer);
        }

        private bool onlineChanges()
        {
            string responsebody;
            login[] result;
            using (WebClient client = new WebClient())
            {
                System.Collections.Specialized.NameValueCollection reqparm = new System.Collections.Specialized.NameValueCollection();
                reqparm.Add("device_id", device_id);
                byte[] responsebytes = client.UploadValues(device_status, "POST", reqparm);
                responsebody = Encoding.UTF8.GetString(responsebytes);
                result = JsonConvert.DeserializeObject<login[]>(responsebody);
            }
            if (result[0].status.ToString() == "1")
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        private void ChangeStatus()
        {
            string responsebody;
            login[] result;
            using (WebClient client = new WebClient())
            {
                System.Collections.Specialized.NameValueCollection reqparm = new System.Collections.Specialized.NameValueCollection();
                reqparm.Add("device_id", device_id);
                byte[] responsebytes = client.UploadValues(device_status_change, "POST", reqparm);
                responsebody = Encoding.UTF8.GetString(responsebytes);
                result = JsonConvert.DeserializeObject<login[]>(responsebody);
            }
        }
        void onlineSync()
        {
            WebClient client = new WebClient();
            string reply = client.DownloadString(data + user_id);
            online_action[] result = JsonConvert.DeserializeObject<online_action[]>(reply);


            //connection
            using (SQLiteConnection m_dbConnection = new SQLiteConnection("Data Source=syncjar;Version=3;"))
            {
                m_dbConnection.Open();
                string sql;
                for (int i = 0; i < result.Length; i++)
                {
                    sql = "insert into online_data (data_type, data_id, data_name, data_parent, size) values ( @data_type, @data_id, @data_name, @data_parent, @size)";
                    SQLiteCommand command = new SQLiteCommand(sql, m_dbConnection);
                    command = new SQLiteCommand(sql, m_dbConnection);
                    command.Parameters.AddWithValue("@data_type", result[i].data_type.ToString());
                    command.Parameters.AddWithValue("@data_id", result[i].data_id.ToString());
                    command.Parameters.AddWithValue("@data_name", result[i].data_name.ToString());
                    command.Parameters.AddWithValue("@data_parent", result[i].data_parent.ToString());
                    command.Parameters.AddWithValue("@size", result[i].size.ToString());
                    command.ExecuteNonQuery();

                }


                //folder tree for online data
                folderTree(m_dbConnection, "online_data", "online_fql");
                folderTree(m_dbConnection, "files", "fql");

                onlineDataProcess(m_dbConnection);



                string del = "DELETE FROM fql";
                using (SQLiteCommand command = new SQLiteCommand(del, m_dbConnection))
                {
                    command.ExecuteNonQuery();
                }

                del = "DELETE FROM online_fql";
                using (SQLiteCommand command = new SQLiteCommand(del, m_dbConnection))
                {
                    command.ExecuteNonQuery();
                }

                del = "DELETE FROM online_data";
                using (SQLiteCommand command = new SQLiteCommand(del, m_dbConnection))
                {
                    command.ExecuteNonQuery();
                }

                //update drive size
                sql = "select (select sum(size) from files) size from files;";
                using (SQLiteCommand setInfo = new SQLiteCommand(sql, m_dbConnection))
                {
                    using (SQLiteDataReader reader = setInfo.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            sql = "UPDATE drive_info SET drive_size=@drive_size";
                            SQLiteCommand infoUpdate = new SQLiteCommand(sql, m_dbConnection);
                            infoUpdate.Parameters.AddWithValue("@drive_size", SizeSuffix(Int64.Parse(reader["size"].ToString())) + " Drive Size");
                            infoUpdate.ExecuteNonQuery();

                            this.Dispatcher.Invoke((Action)(() =>
                            {
                                disc_size.Content = SizeSuffix(Int64.Parse(reader["size"].ToString())) + " Drive Size";
                            }));
                        }
                    }
                }

                //update folders
                sql = "select count(*) from files where data_type=@data_type;";
                using (SQLiteCommand command = new SQLiteCommand(sql, m_dbConnection))
                {
                    command.Parameters.AddWithValue("@data_type", "0");
                    int RowCount = 0;
                    RowCount = Convert.ToInt32(command.ExecuteScalar());

                    sql = "UPDATE drive_info SET folders=@folders";
                    SQLiteCommand infoUpdate = new SQLiteCommand(sql, m_dbConnection);
                    infoUpdate.Parameters.AddWithValue("@folders", RowCount + " Folders");
                    infoUpdate.ExecuteNonQuery();

                    this.Dispatcher.Invoke((Action)(() =>
                    {
                        folders_count.Content = RowCount + " Folders";
                    }));

                }

                //update files
                sql = "select count(*) from files where data_type=@data_type;";
                using (SQLiteCommand command = new SQLiteCommand(sql, m_dbConnection))
                {
                    command.Parameters.AddWithValue("@data_type", "1");
                    int RowCount = 0;
                    RowCount = Convert.ToInt32(command.ExecuteScalar());

                    sql = "UPDATE drive_info SET files=@files";
                    SQLiteCommand infoUpdate = new SQLiteCommand(sql, m_dbConnection);
                    infoUpdate.Parameters.AddWithValue("@files", RowCount + " Files");
                    infoUpdate.ExecuteNonQuery();

                    this.Dispatcher.Invoke((Action)(() =>
                    {
                        files_count.Content = RowCount + " Files";
                    }));

                }
            }
        }

        private void onlineDataProcess(SQLiteConnection m_dbConnection)
        {
            //delete data
            string match = "select * from fql ORDER BY data_type DESC;";
            using (SQLiteCommand cmd1 = new SQLiteCommand(match, m_dbConnection))
            {
                using (SQLiteDataReader reader = cmd1.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        string sql = "select count(*) from online_fql where fql=@fql;";
                        using (SQLiteCommand command = new SQLiteCommand(sql, m_dbConnection))
                        {
                            int RowCount = 0;
                            command.Parameters.AddWithValue("@fql", reader["fql"].ToString());
                            RowCount = Convert.ToInt32(command.ExecuteScalar());
                            if (RowCount == 0)
                            {
                                //delete folder
                                if (reader["data_type"].ToString() == "0")
                                {
                                    if (Directory.Exists(dirPath + System.IO.Path.DirectorySeparatorChar + reader["fql"].ToString()))
                                    {
                                        DeleteDirectory(dirPath + System.IO.Path.DirectorySeparatorChar + reader["fql"].ToString());
                                    }
                                } //delete file
                                else
                                {
                                    if (File.Exists(dirPath + System.IO.Path.DirectorySeparatorChar + reader["fql"].ToString()))
                                        File.Delete(dirPath + System.IO.Path.DirectorySeparatorChar + reader["fql"].ToString());
                                }

                                //delete record from files
                                sql = "DELETE FROM files where data_id = @data_id AND data_parent=@data_parent";
                                using (SQLiteCommand deleteData = new SQLiteCommand(sql, m_dbConnection))
                                {
                                    deleteData.Parameters.AddWithValue("@data_id", reader["data_id"].ToString());
                                    deleteData.Parameters.AddWithValue("@data_parent", reader["data_parent"].ToString());
                                    deleteData.ExecuteNonQuery();
                                }
                            }
                        }
                    }
                }
            }

            // create and update
            match = "select * from online_fql ORDER BY data_type ASC;";
            using (SQLiteCommand cmd1 = new SQLiteCommand(match, m_dbConnection))
            {
                using (SQLiteDataReader reader = cmd1.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        string sql = "select count(*) from fql where fql=@fql;";
                        using (SQLiteCommand command = new SQLiteCommand(sql, m_dbConnection))
                        {
                            int RowCount = 0;
                            command.Parameters.AddWithValue("@fql", reader["fql"].ToString());
                            RowCount = Convert.ToInt32(command.ExecuteScalar());
                            if (RowCount == 0)
                            {
                                //create folder
                                if (reader["data_type"].ToString() == "0")
                                {
                                    Directory.CreateDirectory(dirPath + System.IO.Path.DirectorySeparatorChar + reader["fql"].ToString());
                                }
                                else // create file
                                {
                                    using (WebClient dw = new WebClient())
                                    {
                                        dw.DownloadFile("http://www.syncjar.com/download/" + reader["data_id"].ToString(), dirPath + System.IO.Path.DirectorySeparatorChar + reader["fql"].ToString());
                                    }
                                }

                                string fql = reader["fql"].ToString();
                                string[] data_name = fql.Split(System.IO.Path.DirectorySeparatorChar);
                                //insert created data
                                sql = "insert into files (data_type, data_id, data_name, data_parent, size) values ( @data_type, @data_id, @data_name, @data_parent, @size)";
                                SQLiteCommand insertData = new SQLiteCommand(sql, m_dbConnection);
                                insertData = new SQLiteCommand(sql, m_dbConnection);
                                insertData.Parameters.AddWithValue("@data_type", reader["data_type"].ToString());
                                insertData.Parameters.AddWithValue("@data_id", reader["data_id"].ToString());
                                insertData.Parameters.AddWithValue("@data_name", data_name.Last());
                                insertData.Parameters.AddWithValue("@data_parent", reader["data_parent"].ToString());
                                insertData.Parameters.AddWithValue("@size", reader["size"].ToString());
                                insertData.ExecuteNonQuery();
                            }
                        }

                        //update data
                        sql = "select count(*) from fql where fql=@fql AND size!=@size;";
                        using (SQLiteCommand command = new SQLiteCommand(sql, m_dbConnection))
                        {
                            int RowCount = 0;
                            command.Parameters.AddWithValue("@fql", reader["fql"].ToString());
                            command.Parameters.AddWithValue("@size", reader["size"].ToString());
                            RowCount = Convert.ToInt32(command.ExecuteScalar());
                            if (RowCount != 0)
                            {

                                using (WebClient dw = new WebClient())
                                {
                                    dw.DownloadFile("http://www.syncjar.com/download/" + reader["data_id"].ToString(), dirPath + System.IO.Path.DirectorySeparatorChar + reader["fql"].ToString());
                                }

                                sql = "UPDATE files SET size = @size WHERE data_id = @data_id;";
                                using (SQLiteCommand updateData = new SQLiteCommand(sql, m_dbConnection))
                                {
                                    updateData.Parameters.AddWithValue("@data_id", reader["data_id"].ToString());
                                    updateData.Parameters.AddWithValue("@size", reader["size"].ToString());
                                    updateData.ExecuteNonQuery();
                                }
                            }
                        }
                    }
                }
            }
        }


        static private void pathFinder(string data_parent, SQLiteConnection m_dbConnection, string from)
        {
            string sql = "select * from " + from + " where data_id = @data_parent LIMIT 1;";
            using (SQLiteCommand command = new SQLiteCommand(sql, m_dbConnection))
            {
                command.Parameters.AddWithValue("@data_parent", data_parent);
                using (SQLiteDataReader reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        data_path = reader["data_name"].ToString() + System.IO.Path.DirectorySeparatorChar + data_path;
                        if (reader["data_parent"].ToString() != "0")
                            pathFinder(reader["data_parent"].ToString(), m_dbConnection, from);
                    }
                }
            }
        }
        public static void DeleteDirectory(string target_dir)
        {
            string[] files = Directory.GetFiles(target_dir);
            string[] dirs = Directory.GetDirectories(target_dir);

            foreach (string file in files)
            {
                File.SetAttributes(file, FileAttributes.Normal);
                File.Delete(file);
            }

            foreach (string dir in dirs)
            {
                DeleteDirectory(dir);
            }

            Directory.Delete(target_dir, false);
        }

        public void offlineSync()
        {
            DirectoryInfo di = new DirectoryInfo(dirPath);
            using (SQLiteConnection m_dbConnection = new SQLiteConnection("Data Source=syncjar;Version=3;"))
            {
                m_dbConnection.Open();
                getDriveInfo(di, "*");
                driveInfoProcess(m_dbConnection);
                files.Clear();
                folders.Clear();

                //update drive size
                string sql = "select (select sum(size) from files) size from files;";
                using (SQLiteCommand setInfo = new SQLiteCommand(sql, m_dbConnection))
                {
                    setInfo.Parameters.AddWithValue("@info", "info");
                    using (SQLiteDataReader reader = setInfo.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            sql = "UPDATE drive_info SET drive_size=@drive_size";
                            SQLiteCommand infoUpdate = new SQLiteCommand(sql, m_dbConnection);
                            infoUpdate.Parameters.AddWithValue("@drive_size", SizeSuffix(Int64.Parse(reader["size"].ToString())) + " Drive Size");
                            infoUpdate.ExecuteNonQuery();

                            this.Dispatcher.Invoke((Action)(() =>
                            {
                                disc_size.Content = SizeSuffix(Int64.Parse(reader["size"].ToString())) + " Drive Size";
                            }));
                        }
                    }
                }

                //update folders
                sql = "select count(*) from files where data_type=@data_type;";
                using (SQLiteCommand command = new SQLiteCommand(sql, m_dbConnection))
                {
                    command.Parameters.AddWithValue("@data_type", "0");
                    int RowCount = 0;
                    RowCount = Convert.ToInt32(command.ExecuteScalar());

                    sql = "UPDATE drive_info SET folders=@folders";
                    SQLiteCommand infoUpdate = new SQLiteCommand(sql, m_dbConnection);
                    infoUpdate.Parameters.AddWithValue("@folders", RowCount + " Folders");
                    infoUpdate.ExecuteNonQuery();

                    this.Dispatcher.Invoke((Action)(() =>
                    {
                        folders_count.Content = RowCount + " Folders";
                    }));

                }

                //update files
                sql = "select count(*) from files where data_type=@data_type;";
                using (SQLiteCommand command = new SQLiteCommand(sql, m_dbConnection))
                {
                    command.Parameters.AddWithValue("@data_type", "1");
                    int RowCount = 0;
                    RowCount = Convert.ToInt32(command.ExecuteScalar());

                    sql = "UPDATE drive_info SET files=@files";
                    SQLiteCommand infoUpdate = new SQLiteCommand(sql, m_dbConnection);
                    infoUpdate.Parameters.AddWithValue("@files", RowCount + " Files");
                    infoUpdate.ExecuteNonQuery();

                    this.Dispatcher.Invoke((Action)(() =>
                    {
                        files_count.Content = RowCount + " Files";
                    }));
                }
            }
        }

        static void getDriveInfo(DirectoryInfo dir, string searchPattern)
        {

            try
            {
                foreach (FileInfo f in dir.GetFiles(searchPattern))
                {
                    if (f.Name != "Thumbs.db")
                        files.Add(f);
                }
            }
            catch
            {
                return;
            }
            foreach (DirectoryInfo d in dir.GetDirectories())
            {
                folders.Add(d);
                getDriveInfo(d, searchPattern);
            }

        }
        static void driveInfoProcess(SQLiteConnection m_dbConnection)
        {
            folderTree(m_dbConnection, "files", "fql");
            offlineFolderProcess(m_dbConnection);
            offlineFileProcess(m_dbConnection);

            //clear fql table after use
            string del = "DELETE FROM fql";
            using (SQLiteCommand command = new SQLiteCommand(del, m_dbConnection))
            {
                command.ExecuteNonQuery();
            }
        }

        static void folderTree(SQLiteConnection m_dbConnection, string from, string to)
        {
            string sql = "select * from " + from;
            using (SQLiteCommand cmd = new SQLiteCommand(sql, m_dbConnection))
            {
                using (SQLiteDataReader reader = cmd.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        data_path = reader["data_name"].ToString();

                        if (reader["data_parent"].ToString() != "0")
                            pathFinder(reader["data_parent"].ToString(), m_dbConnection, from);

                        // insert to fql table
                        sql = "insert into " + to + " (data_type, data_id, fql, data_parent, size) values (@data_type, @data_id, @fql, @data_parent, @size)";
                        using (SQLiteCommand command = new SQLiteCommand(sql, m_dbConnection))
                        {
                            command.Parameters.AddWithValue("@data_type", reader["data_type"].ToString());
                            command.Parameters.AddWithValue("@data_id", reader["data_id"].ToString());
                            command.Parameters.AddWithValue("@fql", data_path);
                            command.Parameters.AddWithValue("@data_parent", reader["data_parent"].ToString());
                            command.Parameters.AddWithValue("@size", reader["size"].ToString());
                            command.ExecuteNonQuery();
                        }

                    }
                }
            }
        }
        static void offlineFolderProcess(SQLiteConnection m_dbConnection)
        {

            foreach (DirectoryInfo d in folders)
            {
                string fql;
                string[] fnames;
                fql = d.FullName;
                fql = fql.Replace(dirPath + System.IO.Path.DirectorySeparatorChar, string.Empty);
                fnames = fql.Split(System.IO.Path.DirectorySeparatorChar);
                string sql = "select count(*) from fql where fql=@fql AND data_type=0;";
                using (SQLiteCommand command = new SQLiteCommand(sql, m_dbConnection))
                {
                    int RowCount = 0;
                    command.Parameters.AddWithValue("@fql", fql);
                    RowCount = Convert.ToInt32(command.ExecuteScalar());
                    if (RowCount == 0)
                    {
                        if (fnames.Last() == fql)
                        {
                            //it is on root so, data_parent = 0
                            offlineCreateFolder(m_dbConnection, "0", d.Name, d.FullName);
                        }
                        else
                        {
                            fql = fql.Replace(System.IO.Path.DirectorySeparatorChar + fnames.Last(), string.Empty);

                            sql = "select * from fql where fql=@fql AND data_type=0 LIMIT 1;";
                            using (SQLiteCommand cmd = new SQLiteCommand(sql, m_dbConnection))
                            {
                                //take data_id of root
                                cmd.Parameters.AddWithValue("@fql", fql);
                                using (SQLiteDataReader reader = cmd.ExecuteReader())
                                {
                                    while (reader.Read())
                                    {
                                        offlineCreateFolder(m_dbConnection, reader["data_id"].ToString(), d.Name, d.FullName);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            string delete = "select * from fql where data_type=0;";
            using (SQLiteCommand cmd1 = new SQLiteCommand(delete, m_dbConnection))
            {
                using (SQLiteDataReader reader = cmd1.ExecuteReader())
                {
                    Boolean found;
                    while (reader.Read())
                    {
                        found = false;
                        foreach (DirectoryInfo d in folders)
                        {
                            string fql = d.FullName;
                            reader["fql"].ToString();
                            fql = fql.Replace(dirPath + System.IO.Path.DirectorySeparatorChar, string.Empty);
                            if (fql == reader["fql"].ToString())
                            {
                                found = true;
                            }
                        }
                        if (!found)
                        {
                            offlineDeleteFolder(m_dbConnection, reader["data_id"].ToString());
                        }
                    }
                }
            }
        }

        private static void offlineDeleteFolder(SQLiteConnection m_dbConnection, string data_id)
        {
            //sync to onlinestring responsebody;
            string responsebody;
            online_action[] result;
            using (WebClient client = new WebClient())
            {
                System.Collections.Specialized.NameValueCollection reqparm = new System.Collections.Specialized.NameValueCollection();
                reqparm.Add("data_id", data_id);
                reqparm.Add("device_id", device_id);
                reqparm.Add("user_id", user_id);
                byte[] responsebytes = client.UploadValues("http://www.syncjar.com/devicedeletefolder", "POST", reqparm);
                responsebody = Encoding.UTF8.GetString(responsebytes);
                result = JsonConvert.DeserializeObject<online_action[]>(responsebody);
            }

            //sync to local db
            string del = "DELETE FROM files where data_id=@data_id";
            using (SQLiteCommand command = new SQLiteCommand(del, m_dbConnection))
            {
                command.Parameters.AddWithValue("@data_id", data_id);
                command.ExecuteNonQuery();
            }
        }

        static void offlineCreateFolder(SQLiteConnection m_dbConnection, string data_parent, string data_name, string full_name)
        {
            //do create folder code
            string responsebody;
            online_action[] result;
            using (WebClient client = new WebClient())
            {
                System.Collections.Specialized.NameValueCollection reqparm = new System.Collections.Specialized.NameValueCollection();
                reqparm.Add("user_id", user_id);
                reqparm.Add("device_id", device_id);
                reqparm.Add("data_name", data_name);
                reqparm.Add("data_parent", data_parent);
                byte[] responsebytes = client.UploadValues("http://www.syncjar.com/devicenewfolder", "POST", reqparm);
                responsebody = Encoding.UTF8.GetString(responsebytes);
                result = JsonConvert.DeserializeObject<online_action[]>(responsebody);
            }

            //insert to files new folder
            string sql = "insert into files (data_type, data_id, data_name, data_parent, size) values (@data_type, @data_id, @data_name, @data_parent, @size)";
            using (SQLiteCommand folderCreate = new SQLiteCommand(sql, m_dbConnection))
            {
                folderCreate.Parameters.AddWithValue("@data_type", "0");
                folderCreate.Parameters.AddWithValue("@data_id", result[0].data_id.ToString());
                folderCreate.Parameters.AddWithValue("@data_name", data_name);
                folderCreate.Parameters.AddWithValue("@data_parent", data_parent);
                folderCreate.Parameters.AddWithValue("@size", "0");
                folderCreate.ExecuteNonQuery();
            }

            //insert to fql

            full_name = full_name.Replace(dirPath + System.IO.Path.DirectorySeparatorChar, string.Empty);
            sql = "insert into fql (data_type, data_id, fql, data_parent, size) values (@data_type, @data_id, @fql, @data_parent, @size)";
            using (SQLiteCommand folderCreate = new SQLiteCommand(sql, m_dbConnection))
            {
                folderCreate.Parameters.AddWithValue("@data_type", "0");
                folderCreate.Parameters.AddWithValue("@data_id", result[0].data_id.ToString());
                folderCreate.Parameters.AddWithValue("@fql", full_name);
                folderCreate.Parameters.AddWithValue("@data_parent", data_parent);
                folderCreate.Parameters.AddWithValue("@size", "0");
                folderCreate.ExecuteNonQuery();
            }

        }
        private static void offlineFileProcess(SQLiteConnection m_dbConnection)
        {
            string delete = "select * from fql where data_type=1;";
            using (SQLiteCommand cmd1 = new SQLiteCommand(delete, m_dbConnection))
            {
                using (SQLiteDataReader reader = cmd1.ExecuteReader())
                {
                    Boolean found;
                    while (reader.Read())
                    {
                        found = false;
                        foreach (FileInfo f in files)
                        {
                            string fql = f.FullName;
                            reader["fql"].ToString();
                            fql = fql.Replace(dirPath + System.IO.Path.DirectorySeparatorChar, string.Empty);
                            if (fql == reader["fql"].ToString())
                            {
                                found = true;
                                if (f.Length.ToString() != reader["size"].ToString())
                                {
                                    offlineUpdateFile(m_dbConnection, reader["data_id"].ToString(), f.FullName, f.Length.ToString());
                                }
                            }
                        }
                        if (!found)
                        {
                            offlinedeleteFile(m_dbConnection, reader["data_id"].ToString());
                        }
                    }
                }
            }

            foreach (FileInfo f in files)
            {
                string fql;
                string[] fnames;
                fql = f.FullName;
                fql = fql.Replace(dirPath + System.IO.Path.DirectorySeparatorChar, string.Empty);
                fnames = fql.Split(System.IO.Path.DirectorySeparatorChar);
                string sql = "select count(*) from fql where fql=@fql AND data_type=1;";
                using (SQLiteCommand command = new SQLiteCommand(sql, m_dbConnection))
                {
                    int RowCount = 0;
                    command.Parameters.AddWithValue("@fql", fql);
                    RowCount = Convert.ToInt32(command.ExecuteScalar());
                    if (RowCount == 0)
                    {
                        if (fnames.Last() == fql)
                        {
                            //it is on root so, data_parent = 0
                            offlineCreateFile(m_dbConnection, "0", f.Name, f.FullName, f.Length.ToString());
                        }
                        else
                        {
                            //geting parent link
                            fql = fql.Replace(System.IO.Path.DirectorySeparatorChar + fnames.Last(), string.Empty);

                            sql = "select * from fql where fql=@fql AND data_type=0 LIMIT 1;";
                            using (SQLiteCommand cmd = new SQLiteCommand(sql, m_dbConnection))
                            {
                                //take data_id of root
                                cmd.Parameters.AddWithValue("@fql", fql);
                                using (SQLiteDataReader reader = cmd.ExecuteReader())
                                {
                                    while (reader.Read())
                                    {
                                        offlineCreateFile(m_dbConnection, reader["data_id"].ToString(), f.Name, f.FullName, f.Length.ToString());
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        private static void offlineCreateFile(SQLiteConnection m_dbConnection, string data_parent, string data_name, string full_name, string size)
        {
            FileInfo file = new FileInfo(full_name);
            if (File.Exists(full_name))
                if (!IsFileLocked(file))
                {
                    //do create folder code
                    string responsebody;
                    online_action[] result;
                    using (WebClient client = new WebClient())
                    {
                        byte[] responsebytes = client.UploadFile("http://www.syncjar.com/device_new_file.php?user_id=" + user_id + "&data_parent=" + data_parent + "&device_id=" + device_id, "POST", full_name);
                        responsebody = Encoding.UTF8.GetString(responsebytes);
                        result = JsonConvert.DeserializeObject<online_action[]>(responsebody);
                    }

                    //insert to files new folder
                    string sql = "insert into files (data_type, data_id, data_name, data_parent, size) values (@data_type, @data_id, @data_name, @data_parent, @size)";
                    using (SQLiteCommand folderCreate = new SQLiteCommand(sql, m_dbConnection))
                    {
                        folderCreate.Parameters.AddWithValue("@data_type", "1");
                        folderCreate.Parameters.AddWithValue("@data_id", result[0].data_id.ToString());
                        folderCreate.Parameters.AddWithValue("@data_name", data_name);
                        folderCreate.Parameters.AddWithValue("@data_parent", data_parent);
                        folderCreate.Parameters.AddWithValue("@size", size);
                        folderCreate.ExecuteNonQuery();
                    }

                    //insert to fql

                    full_name = full_name.Replace(dirPath + System.IO.Path.DirectorySeparatorChar, string.Empty);
                    sql = "insert into fql (data_type, data_id, fql, data_parent, size) values (@data_type, @data_id, @fql, @data_parent, @size)";
                    using (SQLiteCommand folderCreate = new SQLiteCommand(sql, m_dbConnection))
                    {
                        folderCreate.Parameters.AddWithValue("@data_type", "1");
                        folderCreate.Parameters.AddWithValue("@data_id", result[0].data_id.ToString());
                        folderCreate.Parameters.AddWithValue("@fql", full_name);
                        folderCreate.Parameters.AddWithValue("@data_parent", data_parent);
                        folderCreate.Parameters.AddWithValue("@size", size);
                        folderCreate.ExecuteNonQuery();
                    }
                }
                else
                {
                    offlineCreateFolder(m_dbConnection, data_parent, data_name, full_name);
                }
        }
        private static void offlinedeleteFile(SQLiteConnection m_dbConnection, string data_id)
        {
            //sync to onlinestring responsebody;
            string responsebody;
            using (WebClient client = new WebClient())
            {
                System.Collections.Specialized.NameValueCollection reqparm = new System.Collections.Specialized.NameValueCollection();
                reqparm.Add("data_id", data_id);
                reqparm.Add("user_id", user_id);
                reqparm.Add("device_id", device_id);
                byte[] responsebytes = client.UploadValues("http://www.syncjar.com/devicedeletefile", "POST", reqparm);
                responsebody = Encoding.UTF8.GetString(responsebytes);
            }

            //sync to local db
            string del = "DELETE FROM files where data_id=@data_id";
            using (SQLiteCommand command = new SQLiteCommand(del, m_dbConnection))
            {
                command.Parameters.AddWithValue("@data_id", data_id);
                command.ExecuteNonQuery();
            }
        }
        private static void offlineUpdateFile(SQLiteConnection m_dbConnection, string data_id, string full_name, string size)
        {
            FileInfo file = new FileInfo(full_name);
            if (File.Exists(full_name))
                if (!IsFileLocked(file))
                {
                    //do create folder code
                    string responsebody;
                    using (WebClient client = new WebClient())
                    {
                        byte[] responsebytes = client.UploadFile("http://www.syncjar.com/device_update_file.php?data_id=" + data_id + "&user_id=" + user_id + "&device_id=" + device_id, "POST", full_name);
                        responsebody = Encoding.UTF8.GetString(responsebytes);
                    }

                    //insert to files new folder
                    string sql = "UPDATE files SET size = @size WHERE data_id = @data_id;";
                    using (SQLiteCommand commandSwap = new SQLiteCommand(sql, m_dbConnection))
                    {
                        commandSwap.Parameters.AddWithValue("@data_id", data_id);
                        commandSwap.Parameters.AddWithValue("@size", size);
                        commandSwap.ExecuteNonQuery();
                    }
                }
                else
                {
                    offlineCreateFolder(m_dbConnection, data_id, full_name, size);
                }
        }
        public static bool IsFileLocked(FileInfo file)
        {
            FileStream stream = null;

            try
            {
                stream = file.Open(FileMode.Open, FileAccess.ReadWrite, FileShare.None);
            }
            catch (IOException)
            {
                //the file is unavailable because it is:
                //still being written to
                //or being processed by another thread
                //or does not exist (has already been processed)
                return true;
            }
            finally
            {
                if (stream != null)
                    stream.Close();
            }

            //file is not locked
            return false;
        }
        public static bool CheckForInternetConnection()
        {
            try
            {
                using (var client = new WebClient())
                using (var stream = client.OpenRead("http://www.syncjar.com"))
                {
                    return true;
                }
            }
            catch
            {
                return false;
            }
        }

        static string SizeSuffix(Int64 value)
        {
            if (value < 0) { return "-" + SizeSuffix(-value); }
            if (value == 0) { return "0.0 bytes"; }

            int mag = (int)Math.Log(value, 1024);
            decimal adjustedSize = (decimal)value / (1L << (mag * 10));

            return string.Format("{0:n1} {1}", adjustedSize, SizeSuffixes[mag]);
        }
    }

    public class login
    {
        public int status { get; set; }
        public string user_name { get; set; }
        public int device_id { get; set; }
        public int user_id { get; set; }
    }
    public class online_action
    {
        public int data_type { get; set; }
        public int data_id { get; set; }
        public string data_name { get; set; }
        public int data_parent { get; set; }
        public int size { get; set; }
    }

}