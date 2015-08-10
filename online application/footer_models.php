<!-- modals -->

            <!-- modal for new file-->
              <div class="modal fade" id="newfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel">New File</h4>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 20px;">
                          <h4>Create new file</h4>
                          <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-file"></span></span>
                            <input type="text" class="form-control" id="file_name" placeholder="new code file.." aria-describedby="basic-addon1">
                          </div>
                          <h4 style="color: green; display: none;" id="file_success">File Created!</h4>
                          <h4 style="color: red; display: none;" id="file_error">Error Creating!</h4>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                      <button type="button" class="btn btn-primary" id="create_file">Create</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ajax -->
                <script type="text/javascript">
                  $('#create_file').click(function(){
                    $.ajax({
                      url: '//www.syncjar.com/newfile',
                      dataType: 'text',
                      type: 'post',
                      contentType: 'application/x-www-form-urlencoded',
                      data: {user_id : $user_id, parent : $parent, data_name : $("#file_name").val()},
                      success: function( data, textStatus, jQxhr ){
                          if(data!== "Error Creating!")
                          window.location = "//www.syncjar.com/file/" + data;
                          else
                            $('#file_error').show();
                      },
                      error: function( jqXhr, textStatus, errorThrown ){
                          $('#file_error').show();
                      }
                    });
                  });
                </script>
              <!-- ajax -->
            <!-- end of modal for new file-->

            <!-- modal for new folder-->
              <div class="modal fade" id="newfolder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel">New Folder</h4>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 20px;">
                          <h4>Create new folder</h4>
                          <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-folder-open"></span></span>
                            <input type="text" class="form-control" id="folder_name" placeholder="new code file.." aria-describedby="basic-addon1">
                          </div>
                          <h4 style="color: green; display: none;" id="folder_success">Folder Created!</h4>
                          <h4 style="color: red; display: none;" id="folder_error">Error Creating!</h4>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                      <button type="button" class="btn btn-primary" id="create_folder">Create</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ajax -->
                <script type="text/javascript">
                  $('#create_folder').click(function(){
                    $.ajax({
                      url: '//www.syncjar.com/newfolder',
                      dataType: 'text',
                      type: 'post',
                      contentType: 'application/x-www-form-urlencoded',
                      data: {user_id : $user_id, parent : $parent, data_name : $("#folder_name").val() },
                      success: function( data, textStatus, jQxhr ){
                        if(data!== "Error Creating!"){
                          $('#folder_success').show();
                          window.location.reload();
                        }else
                            $('#folder_error').show();
                      },
                      error: function( jqXhr, textStatus, errorThrown ){
                          $('#folder_error').show();
                      }
                    });
                  });
                </script>
              <!-- ajax -->
            <!-- end of modal for new folder-->

            <!-- modal for file upload-->
              <div class="modal fade" id="fileupload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel">File Upload</h4>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 20px;">
                          <h4>Upload new file</h4>
                          <input id="filesupload" name="files[]" type="file" multiple>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </div>
              </div>
            <!-- modal for file upload-->

            <!-- modal for takeover -->
              <div class="modal fade" id="takeover_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel">TakeOver Request by</h4>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 20px;">
                          <h4><span class="glyphicon glyphicon-user"></span> <span id="takeover_by"></span></h4>
                          <h4 style="color: green; display: none;" id="granted">Granted!</h4>
                          <h4 style="color: red; display: none;" id="revoked">Revoked!</h4>
                          <h4 style="color: red; display: none;" id="takeover_error">Takeover Error!</h4>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal" id="revoke">Revoke</button>
                      <button type="button" class="btn btn-primary" data-dismiss="modal" id="grant">Grant</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ajax -->
                <script type="text/javascript">
                  $('#grant').click(function(){
                    $safe = 0;
                    console.log("safe set to 0");

                    $.ajax({
                      url: '//www.syncjar.com/takeover',
                      dataType: 'text',
                      type: 'post',
                      contentType: 'application/x-www-form-urlencoded',
                      data: {takeover_id : $takeover_id, data_id : $present},
                      success: function( data, textStatus, jQxhr ){
                        if(data== "1"){
                          console.log("safe set to 1");

                          $safe = 1;
                        }else
                            $('#takeover_error').show();
                            $safe = 1;
                      },
                      error: function( jqXhr, textStatus, errorThrown ){
                          $('#takeover_error').show();
                      }
                    });
                  });

                  $('#revoke').click(function(){
                    $safe = 0;
                    console.log("safe set to 0");

                    $.ajax({
                      url: '//www.syncjar.com/revoke',
                      dataType: 'text',
                      type: 'post',
                      contentType: 'application/x-www-form-urlencoded',
                      data: {data_id : $present},
                      success: function( data, textStatus, jQxhr ){
                        if(data== "1"){
                          console.log("safe set to 1");

                          $safe = 1;
                        }else
                            $('#takeover_error').show();
                            $safe = 1;
                      },
                      error: function( jqXhr, textStatus, errorThrown ){
                          $('#takeover_error').show();
                      }
                    });
                  });
                </script>
              <!-- ajax -->
            <!-- end of modal for takeover -->

          <!-- end of modals -->    
            
    </body>
</html>