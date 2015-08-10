<!DOCTYPE html>

<html lang="en">
    <head>     
      <meta charset="utf-8">
      <title>Sync Jar | dashboard</title>
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

      <link href="./css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="./css/style.css">

      <script type="text/javascript" src="./js/jquery.min.js"></script>
      <script type="text/javascript" src="./js/bootstrap.min.js"></script>
      
      <link href="./css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
      <script src="./js/fileinput.min.js" type="text/javascript" ></script>

      <script type="text/javascript">
        $(document).on("ready", function() {
          $("#file_upload_1").fileinput({
                                      uploadUrl: "/file.php",
                                      uploadExtraData: {
                                        id:"a1b2c3",
                                        folder:"q1w2e3r4"
                                      }
                                    });

          $("#file_upload_2").fileinput({
                                      uploadUrl: "/file.php",
                                      uploadExtraData: {
                                        id:"a1b2c3",
                                        folder:"q1w2e3r4"
                                      }
                                    });

        });

      </script>
      
    </head>
