              <option value="0">Select Shared User</option>
              <!-- <option value="1" data-content="<span class='label label-success'>&nbsp;View&nbsp;</span> ashwin_reddy">ashwin_reddy</option>
              <option value="2" data-content="<span class='label label-warning'>&nbsp;&nbsp;Edit&nbsp;&nbsp;</span> demo">demo</option>
              <option value="3" data-content="<span class='label label-danger'>Delete</span> demo">demo</option> -->

              <?php

                $share_raised = $_POST['share_raised'];

                //database connections
                include('db.php');
                $db = mysql_select_db("syncjar", $connection);

                $query = mysql_query("select * from data_shared where data_id='$share_raised'", $connection);
                  while ($tableRow = mysql_fetch_assoc($query)) { 
                    $user_id = $tableRow['user_id'];
                    $user_permit = $tableRow['permit_type'];

                    //data-content
                    if($user_permit == 1){
                      $data_content = "<span class='label label-success'>&nbsp;View&nbsp;</span> ";
                    }
                    elseif($user_permit == 2){
                      $data_content = "<span class='label label-warning'>&nbsp;&nbsp;Edit&nbsp;&nbsp;</span> ";
                    }
                    $userQuery = mysql_query("select * from users where user_id='$user_id'", $connection);
                    $userRow = mysql_fetch_assoc($userQuery);
                    echo '<option value="'.$tableRow['user_id'].'" data-content="'.$data_content.$userRow['user_name'].'">'.$userRow['user_name'].'</option>';
                  }
              ?>
              