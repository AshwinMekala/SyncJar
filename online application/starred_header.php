<!DOCTYPE html>

<html lang="en">
    <head>    
        <meta charset="utf-8">
        <title>Sync Jar | dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <link href="./css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="./css/style.css">
        <link rel="stylesheet" type="text/css" href="./css/bootstrap-select.css">

        <script type="text/javascript" src="./js/jquery.min.js"></script>
        <script type="text/javascript" src="./js/bootstrap.min.js"></script>
        <script type="text/javascript" src="./js/bootstrap-select.js"></script>

        <link href="./css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
        <script src="./js/fileinput.min.js" type="text/javascript" ></script>
    
        <script type="text/javascript">

        $('.selectpicker').selectpicker({
            style: 'btn-info',
            size: 4,
            width: 'auto'
        });

        var $user_id = <?php
                          echo $_SESSION['user_id'];
                        ?>;
        var $parent = <?php
                          $url = explode("/", $_SERVER["REQUEST_URI"]);
                          if(isset($url[2])){
                            echo $url[2];
                            $parent = $url[2];
                          }
                          else{
                            echo "0";
                            $parent = "0";
                          }
                        ?>;

          $(document).on("ready", function() {
            
            $("#filesupload").fileinput({
                                        uploadUrl: "//www.syncjar.com/upload.php",
                                        uploadExtraData: {
                                          id:<?php
                                                echo $_SESSION['user_id'];
                                              ?>,
                                          parent:<?php
                                                    $url = explode("/", $_SERVER["REQUEST_URI"]);
                                                    if(isset($url[2]))
                                                    echo $url[2];
                                                    else
                                                    echo "0";
                                                  ?>
                                        }
                                      });

          });

        </script>

    </head>