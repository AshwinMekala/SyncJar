<!DOCTYPE html>

<html lang="en">
    <head>    
        <meta charset="utf-8">
        <title>Sync Jar | Home</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <link href="//www.syncjar.com/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="//www.syncjar.com/css/editor_style.css">

        <script type="text/javascript" src="//www.syncjar.com/js/jquery.min.js"></script>
        <script type="text/javascript" src="//www.syncjar.com/js/bootstrap.min.js"></script>

        <link href="//www.syncjar.com/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
        <script src="//www.syncjar.com/js/fileinput.min.js" type="text/javascript" ></script>
        <style type="text/css">

        .padding-0 {
              padding: 0;
          }

        </style>

    </head>

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
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="modal" data-target="#login">Login here &nbsp;<span class="glyphicon glyphicon-user"></span></a>
                <div class="dropdown-menu" role="menu" style="padding: 10px">
                    <input type="text" placeholder="user name..">
                    <input type="Password" placeholder="password">
                    
                </div>
              </li>
            </ul>
          </nav>
        </div>
      </header>
      <div class="col-lg-12 padding-0">
        <img src="./images/slider.jpg" class="img-responsive">
      </div>
      <div class="col-lg-12">
      <?php
        if(isset($_GET['error'])){
          echo '<h1 style="text-align: center; font-size: 50px">WRONG PASSWORD</h1>';
        }elseif(isset($_GET['success'])){
          echo '<h1 style="text-align: center; font-size: 50px">SUCCESS SIGNUP!</h1>';
        }else{
          echo '<h1 style="text-align: center; font-size: 50px">...</h1>';
        }

      ?>
      </div>
    </body>
          <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-toggle="modal" data-target="#login" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Login</h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <form action="login" method="POST">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 20px;">
                          <input type="text" class="form-control" name="username" placeholder="user name.." aria-describedby="basic-addon1">
                        <br>
                          <input type="password" class="form-control" name="password" placeholder="password.." aria-describedby="basic-addon1">
                      </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#signup">Sign UP!</button>
                  <button type="submit" class="btn btn-primary" name="submit">Login</button>
                  </form>
                </div>
              </div>
            </div>
          </div>


          <div class="modal fade" id="signup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Sign UP!</h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 20px;">
                    <form action="signup" method="POST">
                      <input type="text" class="form-control" name="name" placeholder="name.." aria-describedby="basic-addon1">
                      <br>
                      <input type="text" class="form-control" name="username" placeholder="user name.." aria-describedby="basic-addon1">
                      <br>
                        <input type="text" class="form-control" name="password" placeholder="password.." aria-describedby="basic-addon1">
                      <br>
                      <input type="text" class="form-control"  name="email" placeholder="email.." aria-describedby="basic-addon1">
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" name="submit" class="btn btn-primary">Sign UP Now!</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
</html>