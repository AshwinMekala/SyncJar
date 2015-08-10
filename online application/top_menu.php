        <body style="background-color: #f5f5f5">      
          <header class="navbar navbar-bright navbar-fixed-top" role="banner">
            <div class="container-fluid">
              <div class="navbar-header">
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a href="#" class="navbar-brand"><img src="//www.syncjar.com/images/logo_header.png" height="25px"></a>
              </div>
              <nav class="collapse navbar-collapse" role="navigation">
                <div class="col-lg-6 col-md-6 col-sm-6" style="padding-top: 8px;padding-left: 50px">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" style="padding-top: 6.5px" type="button"><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                  </div>
                </div>
                <ul class="nav navbar-nav navbar-right">
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php 
                                    include('db.php');
                                    $db = mysql_select_db("syncjar", $connection);
                                    $user_name = $_SESSION['user_name'];
                                    $query = mysql_query("select * from users where user_name='$user_name'", $connection);
                                    $details = mysql_fetch_assoc($query);
                                    echo $details['name'];
                                    $user_id = $details['user_id'];
                                    ?> &nbsp;<span class="glyphicon glyphicon-user"></span></a>
                    <div class="dropdown-menu" role="menu" style="padding: 10px">
                        <button class="btn btn-primary" >Account Settings</button>
                        <br>
                            <p style="color: #000; margin: 15px"><?php
                                echo $details['email'];
                            ?></p>
                        <a href="//www.syncjar.com/logout"><button class="btn btn-primary">Logout</button></a>
                    </div>
                  </li>
                </ul>
              </nav>
            </div>
            <div id="masthead">  
              <div class="container-fluid">
                <div class="row">
                  <div class="col-xs-7">
                    <ol class="breadcrumb" style="margin-bottom: 0px">
                      <li><a href="#">Home</a></li>
                      <li><a href="#">My Jar</a></li>
                      <li class="active">Data</li>
                    </ol>
                  </div>
                  <div class="col-xs-5">
                    <div class="pull-right" style="padding-top: 10px;" id="refresh-icon">
                      <a href=""><span class="glyphicon glyphicon-refresh"></span></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </header>