<?php
  session_start();
  if(!isset($_SESSION['user_id'])){
    header("location: //www.syncjar.com/");
  }
?>
<!DOCTYPE html>

<html lang="en">
    <head>    
        <meta charset="utf-8">
        <title>Sync Jar | dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <link href="//www.syncjar.com/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="//www.syncjar.com/css/editor_style.css">

        <script type="text/javascript" src="//www.syncjar.com/js/jquery.min.js"></script>
        <script type="text/javascript" src="//www.syncjar.com/js/bootstrap.min.js"></script>

        <link href="//www.syncjar.com/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
        <script src="//www.syncjar.com/js/fileinput.min.js" type="text/javascript" ></script>

        <script type="text/javascript">
        <?php
          
        ?>
        var $user_id = <?php
                          echo $_SESSION['user_id'];
                        ?>;
        var $parent = <?php
                          $url = explode("/", $_SERVER["REQUEST_URI"]);
                          include('db.php');
          $db = mysql_select_db("syncjar", $connection);
                          if(isset($url[2])){
          $query = mysql_query("select * from files where data_id='$url[2])'", $connection);
          $details = mysql_fetch_assoc($query);
          $ext = $details['data_extension'];
          $data_name = $details['data_name'];
          echo $details['data_parent'];
                          }
                          else
                          echo "0";
                        ?>;
          var $present = <?php
                          $url = explode("/", $_SERVER["REQUEST_URI"]);
                          if(isset($url[2]))
                          echo $url[2];
                          else
                          echo "0";
                        ?>;

          $(document).on("ready", function() {
            $("#codesupload").fileinput({
                                        uploadUrl: "//www.syncjar.com/upload.php",
                                        uploadExtraData: {
                                          id:<?php
                                                echo $_SESSION['user_id'];
                                              ?>,
                                          parent: $parent,
                                          type:1
                                        }
                                      });

            $("#filesupload").fileinput({
                                        uploadUrl: "//www.syncjar.com/upload.php",
                                        uploadExtraData: {
                                          id:<?php
                                                echo $_SESSION['user_id'];
                                              ?>,
                                          parent:$parent,
                                          type:0
                                        }
                                      });

          });

        </script>

        <!-- code_editor links -->

        <link rel="stylesheet" href="//www.syncjar.com/lib/codemirror.css">
        <link rel="stylesheet" href="//www.syncjar.com/addon/fold/foldgutter.css">
        <link rel="stylesheet" href="//www.syncjar.com/addon/dialog/dialog.css">
        <link rel="stylesheet" href="//www.syncjar.com/theme/monokai.css">
        <link rel="stylesheet" href="//www.syncjar.com/theme/ambiance.css">
        <link rel="stylesheet" href="//www.syncjar.com/addon/scroll/simplescrollbars.css">
        <link rel="stylesheet" href="//www.syncjar.com/addon/display/fullscreen.css">
        <style type="text/css">
          .CodeMirror {border-top: 1px solid #101010; border-bottom: 1px solid #000; line-height: 1.3; height: 100%; width: 100%;}
          .CodeMirror-linenumbers { padding: 0 8px; }

          .pull-right {
            float: right !important;
          }
        </style>

        <script src="//www.syncjar.com/lib/codemirror.js"></script>
        <script src="//www.syncjar.com/addon/search/searchcursor.js"></script>
        <script src="//www.syncjar.com/addon/search/search.js"></script>
        <script src="//www.syncjar.com/addon/dialog/dialog.js"></script>
        <script src="//www.syncjar.com/addon/edit/matchbrackets.js"></script>
        <script src="//www.syncjar.com/addon/edit/closebrackets.js"></script>
        <script src="//www.syncjar.com/addon/comment/comment.js"></script>
        <script src="//www.syncjar.com/addon/wrap/hardwrap.js"></script>
        <script src="//www.syncjar.com/addon/fold/foldcode.js"></script>
        <script src="//www.syncjar.com/addon/fold/brace-fold.js"></script>
        <script src="//www.syncjar.com/mode/javascript/javascript.js"></script>
        <script src="//www.syncjar.com/keymap/sublime.js"></script>
        <script src="//www.syncjar.com/addon/scroll/simplescrollbars.js"></script>
        <script src="//www.syncjar.com/addon/display/fullscreen.js"></script>
        <script src="//www.syncjar.com/addon/selection/active-line.js"></script>
        <script src="//www.syncjar.com/addon/mode/loadmode.js"></script>
        <script src="//www.syncjar.com/mode/meta.js"></script>

        <!-- end of code_editor links -->

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
            <a href="//www.syncjar.com/my-jar" class="navbar-brand"><img src="//www.syncjar.com/images/logo_header.png" height="25px"></a>
          </div>
          <nav class="collapse navbar-collapse" role="navigation">
            <div class="col-lg-4 col-md-2 hidden-sm " style="padding-top: 8px;padding-left: 50px">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                  <button class="btn btn-default" style="padding-top: 6.5px" type="button"><span class="glyphicon glyphicon-search"></span></button>
                </span>
              </div>
            </div>

            <div class="col-lg-1 col-md-1 col-sm-2" style="padding-top: 8px;">
              <button class="btn btn-success"><span class="glyphicon glyphicon-play"></span> RUN</button>
            </div>

            <div class="col-lg-1 col-md-2 col-sm-2" style="padding-top: 8px; display: none;" id="takeover">
              <button class="btn btn-danger" id="takeover_button"><span class="glyphicon glyphicon-pencil"></span> <span id="takeover_text">Take Over</span></button>
            </div>

            <div class="col-lg-2 col-md-3 col-sm-2" style="padding-top: 6px;  font-size: 22px; display: none;" id="lock_by">
              <span class="label label-warning"><span class="glyphicon glyphicon-lock"></span> <span id="lock_name">Lock By Ashwin</span></span>
            </div>

            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php 
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
                    <button class="btn btn-primary">Logout</button>
                </div>
              </li>
            </ul>
          </nav>
        </div>
      </header>
      <?php 

        include "side_menu.php";
        
      ?>

        <div id="main" style="border-left: 1px solid #ebebeb">
          <div class="col-md-12">
              <p class="visible-xs">
              <br><br>
                <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left" id="sidebar-button"></i> Side Menu</button>
              </p>
          </div>
          <div style="color: #fff;background-color: #1b1b1b;">
            <div class="col-lg-2" style="padding: 7px; padding-left: 20px; background-color: #575757;">
              <?php
              if(empty($ext)){
                echo $data_name;
              }else{
                echo $data_name.".".$ext;
              }
              ?><span class="glyphicon glyphicon-remove pull-right"></span>
            </div>
            <a href="#" id="newFileTab">
              <div class="col-lg-1" style="padding: 7px; padding-left: 20px; background-color: #3d3d3d; color: #ababab;">
                <span class="glyphicon glyphicon-plus"></span> New File
              </div>
            </a>
              <div id="jar" style="<?php
              $edit = 1;
                switch ($ext) {
                  case 'php':
                    break;
                  case 'html':
                    break;
                  case 'txt':
                    break;
                  case 'css':
                    break;
                  case 'js':
                    break;
                      
                  default:
                    echo "pointer-events: none;";
                    $edit = 0;
                    break;
                }
              ?>">
              </div>
          </div>
        </div>
      </div>
    
     
      <script type="text/javascript">
                
        $(document).ready(function() {
          $('[data-toggle=offcanvas]').click(function() {
            $('.row-offcanvas').toggleClass('active');
          });
          $('refresh-icon').click(function(){
            window.location.reload();
          });
        });

      </script>
                
      <!--  code_editor javascript code -->

      <script>
        
        var editor = CodeMirror(document.getElementById("jar"), {
          lineNumbers: true,
          styleActiveLine: true,
          keyMap: "sublime",
          autoCloseBrackets: true,
          matchBrackets: true,
          showCursorWhenSelecting: true,
          theme: "ambiance",
          lineWrapping: true,
          scrollbarStyle: "simple",
          extraKeys: {
            "F11": function(cm) {
              cm.setOption("fullScreen", !cm.getOption("fullScreen"));
            },
            "Esc": function(cm) {
              if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
            }
          }
        });

        var $select = 0;
        var $takeover = 1;
        var $takeover_id;
        var $safe = 1;

        getReq();
        <?php
          if($edit)
            echo "setInterval(function() {
                setMode();
            },1000);";
        ?>
        
        function setMode(){
          if($safe){
            $.ajax({
                        url: "//www.syncjar.com/filestatus/" + $present + "/a?a=" + new Date().getTime(), 
                        dataType: 'json',
                        type: 'get',
                        contentType: 'application/x-www-form-urlencoded',
                        success: function( data, textStatus, jQxhr ){
                              $takeover_id = data[0].takeover;
                              if(data[0].user_lock != $user_id && data[0].user_lock != "0" && data[0].status == "0"){
                                $select = 0;
                                console.log("Lock by " + data[0].user_lock + ", File in read mode , Status : " + data[0].status + " takeover : " + data[0].takeover + " takeover user name : " + data[0].takeover_name);
                                editor.setOption("readOnly","nocursor");
                                $("#takeover").css("display","");
                                $("#lock_by").css("display","");
                                $("#lock_name").html("Lock By " + data[0].user_name);
                              }else{
                                if(data[0].takeover != 0 && data[0].takeover != $user_id){
                                    $("#takeover_by").html(data[0].takeover_name);
                                    $('#takeover_modal').modal('show');
                                }
                                  console.log("Lock by " + data[0].user_lock + ", File in write mode , Status : " + data[0].status + " takeover : " +data[0].takeover + " takeover user name : " + data[0].takeover_name);
                                  $select = 1;
                                  $("#takeover").css("display","none");
                                  $("#lock_by").css("display","none");
                                  editor.setOption("readOnly",false);
                                
                              }
                        }
                      });
          }

          if($select == 1){
            saveReq();
          }else{
            getReq();
          }
        }

        function getReq() {
          $.get( "//www.syncjar.com/files/" + $present + "?a=" + new Date().getTime(), function( data ) {
            editor.setValue(data);
            editor.refresh();
          });
          
        }

        function saveReq() {
          if($safe){
            console.log("safe!");
            $.ajax('//www.syncjar.com/save/save?user_lock='+ $user_id +'&data_id='+$present + '&x=' + editor.getCursor().ch + '&y=' + editor.getCursor().line + '&takeover=' + $takeover +'&pseudoParam='+new Date().getTime(),{
                'data': editor.getValue(), //{action:'x',params:['a','b','c']}
                'type': 'POST',
                'processData': false
            });
          }else{
            console.log("not safe!");
          }
        }

        $("#takeover_button").on('click',function(){
          $.ajax({
                      url: '//www.syncjar.com/takeoverreq',
                      dataType: 'text',
                      type: 'post',
                      contentType: 'application/x-www-form-urlencoded',
                      data: {user_id : $user_id, data_id : $present},
                      success: function( data, textStatus, jQxhr ){
                        if(data== "1"){
                          $("#takeover_button").attr('class', 'btn btn-success');
                          $("#takeover_text").html(" Waiting...");
                          setTimeout(function(){
                            $("#takeover_button").attr('class', 'btn btn-danger');
                            $("#takeover_text").html(" Takeover");
                          }, 5000);
                        }else{

                        }
                      },
                      error: function( jqXhr, textStatus, errorThrown ){
                          $('#takeover_error').show();
                      }
                    });
        });

        $("#newFileTab").on('click',function(){
          $('#newfile').modal('show');
        });

      </script>

      <!--  code_editor javascript code -->



      <?php 
      
      include 'footer_models.php';

      ?>