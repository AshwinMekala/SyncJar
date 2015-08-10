          <div class="row-offcanvas row-offcanvas-left">
            <div id="sidebar" class="sidebar-offcanvas">
              <div class="col-md-12">
                <ul class="nav nav-pills nav-stacked">
                  <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" style="text-align: center; margin-top: 20px;"><button class="btn btn-default" style="width: 100%;">+ New</button></a>
                              <ul class="dropdown-menu" role="menu">
                                  <li><a data-toggle="modal" data-target="#newfile"><span class="glyphicon glyphicon-file" style="padding-right: 20px;"></span>File</a></li>
                                  <li><a data-toggle="modal" data-target="#newfolder"><span class="glyphicon glyphicon-folder-open" style="padding-right: 20px;"></span>Folder</a></li>
                                  <li><a data-toggle="modal" data-target="#fileupload"><span class="glyphicon glyphicon-open-file" style="padding-right: 20px;"></span>File upload</a></li>
                              </ul>
                  </li>
                  <li><a href="//www.syncjar.com/my-jar" style="<?php if ($url[1]!=="my-jar" || $url[1]==="folder" || $url[1]==="file")echo "color: #484848;"; ?> text-align: center;"><span class="glyphicon glyphicon-folder-close" style="padding-right: 15px;"></span> My Jar</a></li>
                  <li><a href="//www.syncjar.com/shared" style="<?php if ($url[1]!=="shared" || $url[1]==="folder" || $url[1]==="file")echo "color: #484848;"; ?> text-align: center;"><span class="glyphicon glyphicon-cloud-download"  style="padding-right: 15px;"></span> Shared</a></li>
                  <li><a href="//www.syncjar.com/recent" style="<?php if ($url[1]!=="recent" || $url[1]==="folder" || $url[1]==="file")echo "color: #484848;"; ?> text-align: center;"><span class="glyphicon glyphicon-time"  style="padding-right: 15px;"></span> Recent</a></li>
                  <li><a href="//www.syncjar.com/starred" style="<?php if ($url[1]!=="starred" || $url[1]==="folder" || $url[1]==="file")echo "color: #484848;"; ?> text-align: center;"><span class="glyphicon glyphicon-star"  style="padding-right: 15px;"></span> Starred</a></li>
                  <li><div class="hidden-xs" style="position: fixed; bottom: 0px; height: 80px; color: #656565; font-size: 11px; text-align: center; left: 20px">
                    <div class="progress" style="border: 1px solid #ddd; width: 165%;">
                      <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 3em; width: <?php
                          $percent = $details['data_size']/1048576;
                          $percent_friendly = number_format( $percent * 100, 1 ) . '%';
                          echo $percent_friendly;
                        ?>;">
                        <?php
                          echo $percent_friendly;
                        ?>
                      </div>
                    </div>
                      <?php
                        //data est.
                        if($details['data_size']<1048576){
                          $size_in = "KB";
                          $data_size = round($details['data_size']/1048); 
                        }
                        elseif ($details['data_size']>1048576) {
                          $size_in = "MB";
                          $data_size = round($details['data_size']/1048576); 
                        }
                        else{
                          $size_in = "GB";
                          $data_size = round($details['data_size']/1048576);
                        }
                        
                        echo $data_size." ".$size_in;

                        //percent
                        echo ' used ('.$percent_friendly.')';
                      ?>
                       
                      </div>
                  </li>
                </ul>  
              </div>
            </div>