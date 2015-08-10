<script type="text/javascript">

var move_raised = 0;
var share_raised = 0;

$(document).ready(function() {
  $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
  });
  $('refresh-icon').click(function(){
    window.location.reload();
  });
  $(function($) {
      $('#jars a').click(function() {
          $('#jars a div').css('background-color', '');
          $(this).children("div").css('background-color', 'white');
          return false;
      }).dblclick(function() {
          window.location = this.href;
          return false;
      });
  });
});

</script>

<!-- right click menu -->

<script type="text/javascript">
      
    $(document).ready(function() {
      $('[data-toggle=offcanvas]').click(function() {
        $('.row-offcanvas').toggleClass('active');
      });
      $('refresh-icon').click(function(){
        window.location.reload();
      });
      $(function($) {
        if(!('ontouchstart' in document.documentElement)){
          $('#jar a').click(function() {
              $('#jar a div').css('background-color', '');
              $(this).children("div").css('background-color', 'white');
              return false;
          }).dblclick(function() {
              window.location = this.href;
              return false;
          });
        }
      });

      (function ($, window) {

          $.fn.contextMenu = function (settings) {

              return this.each(function () {

                  // Open context menu
                  $(this).on("contextmenu", function (e) {
                      //open menu
                      $('#jar a div').css('background-color', '');     
                      $(e.target).parent().css('background-color', 'white');
                      $(settings.menuSelector)
                          .data("invokedOn", $(e.target))

                          .show(function (){
                            var $data_id = $(e.target).parent().attr("data-id");
                            // alert($data_id);
                            $.ajax({
                              url: '//www.syncjar.com/isstar',
                              type: 'POST',
                              dataType: 'html',
                              data: {data_id : $data_id },
                              success: function( data, textStatus, jQxhr ){
                                if(data == 1){
                                  $("#star_icon").attr('class', 'glyphicon glyphicon-star');
                                  $('#is_star').html("Remove Star");
                                }
                                else{
                                  $("#star_icon").attr('class', 'glyphicon glyphicon-star-empty');
                                  $('#is_star').html("Add Star");
                                }
                              },
                              error: function( jqXhr, textStatus, errorThrown ){
                                  $('#move_error').show();
                              }
                            });
                          })
                          .css({
                              position: "absolute",
                              left: getLeftLocation(e),
                              top: getTopLocation(e)
                          })
                          .off('click')
                          .on('click', function (e) {
                              $(this).hide();
                      
                              var $invokedOn = $(this).data("invokedOn");
                              var $selectedMenu = $(e.target);
                              
                              settings.menuSelected.call(this, $invokedOn, $selectedMenu);
                      });
                      
                      return false;
                  });

                  //make sure menu closes on any click
                  $(document).click(function () {
                      $(settings.menuSelector).hide();
                  });
              });

              function getLeftLocation(e) {
                  var mouseWidth = e.pageX;
                  var pageWidth = $(window).width();
                  var menuWidth = $(settings.menuSelector).width();
                  
                  // opening menu would pass the side of the page
                  if (mouseWidth + menuWidth > pageWidth &&
                      menuWidth < mouseWidth) {
                      return mouseWidth - menuWidth;
                  } 
                  return mouseWidth;
              }        
              
              function getTopLocation(e) {
                  var mouseHeight = e.pageY;
                  var pageHeight = $(window).height();
                  var menuHeight = $(settings.menuSelector).height();

                  // opening menu would pass the bottom of the page
                  if (mouseHeight + menuHeight > pageHeight &&
                      menuHeight < mouseHeight) {
                      return mouseHeight - menuHeight;
                  } 
                  return mouseHeight;
              }

          };
      })(jQuery, window);
      $("#jar .row a").contextMenu({
          menuSelector: "#contextMenu",
          menuSelected: function (invokedOn, selectedMenu) {
              var msg = "You selected the menu item '" + selectedMenu.attr("data-id") +
                  "' on the value '" + $(invokedOn).parent().attr("data-id") + "'";
            // alert(msg);
            if(selectedMenu.attr("data-id")==="move"){
              move_raised = $(invokedOn).parent().attr("data-id");
              $('#movedata').modal('toggle');
            }else if(selectedMenu.attr("data-id")==="share"){
              share_raised = $(invokedOn).parent().attr("data-id");
              $('#sharedata').modal('toggle');
              $.ajax({
                url: '//www.syncjar.com/sharedwith',
                type: 'POST',
                dataType: 'html',
                data: {share_raised : share_raised },
                success: function( data, textStatus, jQxhr ){
                    $('#edit_user').html(data);
                    $('.selectpicker').selectpicker('refresh');
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    $('#move_error').show();
                }
              });
            }else if(selectedMenu.attr("data-id")==="star"){
              star_raised = $(invokedOn).parent().attr("data-id");
              $.ajax({
                url: '//www.syncjar.com/star',
                type: 'POST',
                dataType: 'html',
                data: {data_id : star_raised}
              });
            }else if(selectedMenu.attr("data-id")==="rename"){
              rename_raised = $(invokedOn).parent().attr("data-id");
              $('#rename_modal').modal('toggle');
              $.ajax({
                url: '//www.syncjar.com/rename',
                type: 'POST',
                dataType: 'html',
                data: {data_id : rename_raised, data_name : ""},
                success: function( data, textStatus, jQxhr ){
                    $('#rename').val(data);
                }
              });
            }else if(selectedMenu.attr("data-id")==="download"){
              var $download_raised = $(invokedOn).parent().attr("data-id");
              window.open("//www.syncjar.com/download/" + $download_raised);
            }else if(selectedMenu.attr("data-id")==="delete"){
              var $delete_raised = $(invokedOn).parent().attr("data-id");
              $.ajax({
                url: '//www.syncjar.com/delete/' + $delete_raised,
                type: 'POST',
                dataType: 'html',
                success: function( data, textStatus, jQxhr ){
                  window.location.reload();
                }
              });
            }
          }
      });

    });

</script>
      
      
<ul id="contextMenu" class="dropdown-menu" role="menu" style="display:none" >
    <li><a tabindex="-1" href="#"><div data-id="move"><span class="glyphicon glyphicon-resize-horizontal" style="padding-right: 12px" data-id="move"></span>Move to..</div></a></li>
    <li><a tabindex="-1" href="#"><div data-id="share"><span class="glyphicon glyphicon glyphicon-share-alt" style="padding-right: 12px" data-id="share"></span>Share..</div></a></li>
    <li><a tabindex="-1" href="#"><div data-id="star"><span id="star_icon" class="glyphicon glyphicon-star" style="padding-right: 12px" data-id="star"></span><span id="is_star" data-id="star">Add / Edit  star</span></div></a></li>
    <li><a tabindex="-1" href="#"><div data-id="rename"><span class="glyphicon glyphicon-erase" style="padding-right: 12px" data-id="rename"></span>Rename</div></a></li>
    <li class="divider"></li>
    <li><a tabindex="-1" href="#"><div data-id="download"><span class="glyphicon glyphicon-save-file" style="padding-right: 12px" data-id="download"></span>Download</div></a></li>
    <li class="divider"></li>
    <li><a tabindex="-1" href="#"><div data-id="delete"><span class="glyphicon glyphicon-remove" style="padding-right: 12px" data-id="delete"></span>Delete</div></a></li>
</ul>


<!-- end of right click menu -->

<!-- ---------------------------------------------------- -->

<!-- right click models -->

<!-- modal for move -->
  <div class="modal fade" id="movedata" tabindex="-1" role="dialog" aria-labelledby="movedata" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Move to..</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 20px;">
              <h4>My Jar</h4>
              <select class="selectpicker" data-live-search="true" id="move_select">
              <option value="0">My Jar Home</option>
              <?php
                $query = mysql_query("select * from files where user_id='$user_id' AND data_type=0 ORDER BY  data_type ASC", $connection);
                  while ($tableRow = mysql_fetch_assoc($query)) { 
                    echo '<option value="'.$tableRow['data_id'].'">'.$tableRow['data_name'].'</option>';
                  }
              ?>
              </select>
              <h4 style="color: green; display: none;" id="move_success">File Moved!</h4>
              <h4 style="color: red; display: none;" id="move_error">Error Moving!</h4>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="do_move">Move</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ajax -->
    <script type="text/javascript">
      $('#do_move').click(function(){
        $.ajax({
          url: '//www.syncjar.com/move/' + $('#move_select').val() +'/' + move_raised,
          dataType: 'text',
          type: 'post',
          contentType: 'application/x-www-form-urlencoded',
          data: {user_id : $user_id },
          success: function( data, textStatus, jQxhr ){
              if(data!== "File Exists!"){
                $('#move_success').show();
                window.location.reload();
              }
              else
                $('#move_error').show();
          },
          error: function( jqXhr, textStatus, errorThrown ){
              $('#move_error').show();
          }
        });
      });
    </script>
  <!-- ajax -->
<!-- end of modal for move -->

<!-- modal for share-->
  <div class="modal fade" id="sharedata" tabindex="-1" role="dialog" aria-labelledby="sharedata" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Share Settings</h4>
        </div>
        <div class="modal-body">
          <div class="row" id="shared_with_row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 20px;">
              <h4>Shared to..</h4>
              <select class="selectpicker" data-live-search="true" id="edit_user">

              <!-- ajax added data -->
              
              </select>
              <select class="selectpicker" id="edit_permit">
              <option value="0">Edit Permission</option>
              <option value="1" data-icon="glyphicon glyphicon-eye-open"> &nbsp;&nbsp; View File</option>
              <option value="2" data-icon="glyphicon glyphicon-pencil"> &nbsp;&nbsp; Edit File</option>
              <option value="3" data-icon="glyphicon glyphicon-remove"> &nbsp;&nbsp; Remove</option>
              </select>
              <h4><br>Share with..</h4>
              <input type="text" id="add_user" placeholder="enter user name.." class="form-control">
              <br>
              <select class="selectpicker" id="add_permit">
              <option value="0">Permission</option>
              <option value="1" data-icon="glyphicon glyphicon-eye-open"> &nbsp;&nbsp; View File</option>
              <option value="2" data-icon="glyphicon glyphicon-pencil"> &nbsp;&nbsp; Edit File</option>
              </select>
              <h4 style="color: green; display: none;" id="share_success">Success!</h4>
              <h4 style="color: red; display: none;" id="share_error">Enter Valid User Name!</h4>
          </div>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="do_share">Done</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ajax -->
    <script type="text/javascript">

    // $('#do_share').click(function(){
    //   alert($( "#edit_share" ).val() + " : " + $( "#edit_permit" ).val() + " user name : " +$("#add_user").val());
    // });
      $('#do_share').click(function(){
        $.ajax({
          url: '//www.syncjar.com/share/',
          dataType: 'text',
          type: 'post',
          contentType: 'application/x-www-form-urlencoded',
          data: {edit_user : $( "#edit_user" ).val(), edit_permit : $( "#edit_permit" ).val(),add_user : $( "#add_user" ).val(), add_permit : $( "#add_permit" ).val(), data_id : share_raised},
          success: function( data, textStatus, jQxhr ){
              if(data == "Success!"){
                $('#share_error').hide();
                $('#share_success').show();
              }
              else if($( "#edit_user" ).val() !== 0 && $( "#edit_permit" ).val() !== 0 && $( "#add_user" ).val().length !== 0 && $( "#add_permit" ).val() !== 0){
                $('#share_success').hide();
                $('#share_error').show();
              }
          },
          error: function( jqXhr, textStatus, errorThrown ){
              $('#share_error').show();
          }
        });
      });
    </script>
  <!-- ajax -->
<!-- end of modal for share-->

<!-- modal for rename -->
  <div class="modal fade" id="rename_modal" tabindex="-1" role="dialog" aria-labelledby="rename_modal" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Rename</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 20px;">
              <input type="text" id="rename" class="form-control">
              <h4 style="color: green; display: none;" id="rename_success">Success!</h4>
              <h4 style="color: red; display: none;" id="rename_error">Error! File with name exists!</h4>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="do_rename">Rename</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ajax -->
    <script type="text/javascript">
      $('#do_rename').click(function(){
        var $data_name = $('#rename').val();
        $.ajax({
          url: '//www.syncjar.com/rename',
          dataType: 'text',
          type: 'post',
          contentType: 'application/x-www-form-urlencoded',
          data: {data_id : rename_raised , data_name : $data_name},
          success: function( data, textStatus, jQxhr ){
              if(data == "Success!"){
                $('#rename_error').hide();
                $('#rename_success').show();
                window.location.reload();
              }
              else{
                $('#rename_success').hide();
                $('#rename_error').show();
              }
          },
          error: function( jqXhr, textStatus, errorThrown ){
              $('#rename_error').show();
          }
        });
      });
    </script>
  <!-- ajax -->
<!-- end of modal for move -->

<!-- End of right click models -->