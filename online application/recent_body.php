    <?php

    include "top_menu.php";
    include "side_menu.php";

    ?>
            <div id="main" style="border-left: 1px solid #ebebeb">
              <div class="col-md-12">
                <p class="visible-xs">
                  <br><br>
                  <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left" id="sidebar-button"></i> Side Menu</button>
                </p>
                <div id="jar">
                <div class="row">
                <br>
                </div>
                <?php
                    $query = mysql_query("select * from files where user_id='$user_id' ORDER BY  data_type ASC, modified DESC", $connection);
                    $pos= 0;
                    while ($tableRow = mysql_fetch_assoc($query)) { 
                      $href = $tableRow['data_type'] ? "file/".$tableRow['data_id'] : "folder/".$tableRow['data_id'];
                        
                          $data_name = $tableRow['data_type'] ? ($tableRow['data_extension']==="0") ? $tableRow['data_name'] : $tableRow['data_name'].'.'.$tableRow['data_extension'] : $tableRow['data_name'];
                          if($tableRow['data_type']){
                              switch ($tableRow['data_extension']) {
                                case 'docx':
                                  $data_type = "//www.syncjar.com/images/docx.png";
                                  break;
                                case 'html':
                                  $data_type = "//www.syncjar.com/images/html.png";
                                  break;
                                case 'php':
                                  $data_type = "//www.syncjar.com/images/php.png";
                                  break;
                                case 'pptx':
                                  $data_type = "//www.syncjar.com/images/pptx.png";
                                  break;
                                case 'rar':
                                  $data_type = "//www.syncjar.com/images/rar.png";
                                  break;
                                case 'txt':
                                  $data_type = "//www.syncjar.com/images/txt.png";
                                  break;
                                case 'xlsx':
                                  $data_type = "//www.syncjar.com/images/xlsx.png";
                                  break;
                                case 'zip':
                                  $data_type = "//www.syncjar.com/images/zip.png";
                                  break;
                                case 'jpg':
                                  $data_type = "//www.syncjar.com/files/".$tableRow['data_id'];
                                  break;
                                case 'png':
                                  $data_type = "//www.syncjar.com/files/".$tableRow['data_id'];
                                  break;
                                case 'pdf':
                                  $data_type = "//www.syncjar.com/images/pdf.png";
                                  break;
                                default:
                                  $data_type = "//www.syncjar.com/images/default.png";
                                  break;
                              }
                            }else {
                              if($tableRow['shared'])
                              $data_type = "//www.syncjar.com/images/".$tableRow['data_type'].$tableRow['shared'].".png";
                              else 
                              $data_type = "//www.syncjar.com/images/".$tableRow['data_type'].".png";
                            }
                          $pos++;
                          if($pos===1){
                            echo '<div class="row">
                                  <a href="//www.syncjar.com/'.$href.'"><div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 nopadding" style="text-align: center;" data-id="'.$tableRow['data_id'].'"><img src="'.$data_type.'" class="img-responsive image-allign-center" data-id="img tag">'.$data_name.'</div></a>';
                          }elseif ($pos===6) {
                            echo '<a href="//www.syncjar.com/'.$href.'"><div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 nopadding" style="text-align: center;" data-id="'.$tableRow['data_id'].'"><img src="'.$data_type.'" class="img-responsive image-allign-center" data-id="img tag">'.$data_name.'</div></a>
                                  </div>';
                            $pos = 0;
                          }else{
                            echo '<a href="//www.syncjar.com/'.$href.'"><div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 nopadding" style="text-align: center;" data-id="'.$tableRow['data_id'].'"><img src="'.$data_type.'" class="img-responsive image-allign-center" data-id="img tag">'.$data_name.'</div></a>';
                          }
                    }
                    if($pos !== 0 && $pos !== 6)
                      echo "</div>";

                ?>

                  <div class="row">
                    <br>
                  </div>
                </div>
              </div>
            </div>
          </div>