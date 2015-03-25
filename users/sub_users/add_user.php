<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			User Management
			<small></small>
		</h3>
	</div>
	<?php
	if(isset($_GET["success"]))
	{
		switch($_GET["success"])
		{
			
			case (0):
					$text_class= 'alert-error';
					$text = 'Error: Database error';
					break;
			case (1):
					$text_class= 'alert-success';
					$text = 'User is successfully added';
					break;
			case (2):
					$text_class= 'alert-error';
					$text = 'Error: Duplicate email in database';
					break;
      case (3):
          $text_class= 'alert-error';
          $text = 'Error: Invalid email';
          break;
		}
		echo '<div class="alert '.$text_class.'">
			<button class="close" data-dismiss="alert"></button>
			'.$text.'
			</div>';
	}
  
	?>

	<div class="row-fluid ">
					<div class="span12">
						<div class="portlet box blue">
						        <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Add New Portfolio Manager</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <?php
                              
                              $priv_sub = mysql_query("SELECT name,sub_users from users where id='$_SESSION[MEM_ID]' ");
                              $row_sub = mysql_fetch_array($priv_sub);
                                $sql_name = mysql_query("SELECT id from users where created_by_prim = '$_SESSION[MEM_ID]' ");
                                $total_names = mysql_num_rows($sql_name);
                                if($total_names < $row_sub["sub_users"]){
                                  $name_text = $row_sub["name"].' User-'.($total_names + 1);
                                  
                              ?>
                                                
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <form action="<?php echo $folder?>process.php?cat=1" method="post" class="horizontal-form" id="submit_form">
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="name">Name</label>
                                             <div class="controls">
                                              <input id="name" name="name" class="m-wrap span12" placeholder="e.g. Rahul Kumar" type="text" >
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName">Email</label>
                                             <div class="controls">
                                              <input id="email" name="email" class="m-wrap span12" placeholder="eg. abcd@xyz.com" type="text">
                                                <span class="help-block"></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label">Contact
                                             <div class="controls">
												            <input id="mobile" name="mobile" class="m-wrap span12" placeholder="eg. 9876543256" type="text">
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                        <div class="control-group">
                                             <label class="control-label">Voting Access
                                             <div class="controls">
                                                 <select class="small m-wrap" name="voting_access">
                                                  <option value="0">All</option>
                                                  <option value="1">Restricted</option>
                                                 </select>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->   
                                    
                                 
                                    <div class="form-actions" style="padding: 19px 10px 20px;">
                                       <button type="button" onclick="check_submit()" class="btn blue"><i class="icon-ok"></i> Create</button>
                                      <button type="button" class="btn" onclick="location.reload()">Cancel</button>
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                              <?php } else {
                                echo '<div style="background:#fff; padding:10px;">You can not add more users. To add more please contact us.</div>';
                              }?>
                           </div>
					</div>
				</div>

          <div class="row-fluid ">
          <div class="span6">
            <div class="portlet box blue">
                    <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Upload Portfolio Manager List</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <?php
                              
                              $priv_sub = mysql_query("SELECT name,sub_users from users where id='$_SESSION[MEM_ID]' ");
                              $row_sub = mysql_fetch_array($priv_sub);
                                $sql_name = mysql_query("SELECT id from users where created_by_prim = '$_SESSION[MEM_ID]' ");
                                $total_names = mysql_num_rows($sql_name);
                                if($total_names < $row_sub["sub_users"]){
                                  $name_text = $row_sub["name"].' User-'.($total_names + 1);
                                  
                              ?>
                                                
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <form action="<?php echo $folder?>process.php?cat=2" method="post" class="horizontal-form" id="pmattachment_form" target="_blank" enctype="multipart/form-data">
                                    <!--/row-->
                                   <div class="row-fluid">
                                       <div class="span9 ">
                                         <div class="control-group">
                                           <label class="control-label">Upload File (CSV)</label>
                                           <div class="controls">
                                              <input type="file" name="attach_file" id="pmattach_file">
                                              <span class="help-block" id="pmfileInfo"></span>
                                           </div>
                                          </div>
                                       </div>
                                       <div class="span3" align="right">
                                         <a type="button" href="http://127.0.0.1:4001/ses/PMProfile.csv" class="btn icn-only ttip" rel="tooltip" title="" target="_blank" data-original-title="Download Format">
                                                <i class="icon-download-alt"></i>
                                              </a>
                                       </div>
                                       <!--/span-->
                                    </div>  
                                    
                                 
                                    <div class="form-actions" style="padding: 19px 10px 20px;">
                                       <button type="button" onclick="pmattachment_submit()" class="btn blue"><i class="icon-ok"></i> Upload</button>
                                      <button type="button" class="btn" onclick="location.reload()">Cancel</button>
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                              <?php } else {
                                echo '<div style="background:#fff; padding:10px;">You can not add more users. To add more please contact us.</div>';
                              }?>
                           </div>
          </div>
          <div class="span6">
            <div class="portlet box blue">
                    <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>PM Voting Access</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>

                                                
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <form action="<?php echo $folder?>process.php?cat=3" method="post" class="horizontal-form" id="accessattachment_form" target="_blank" enctype="multipart/form-data">
                                    <!--/row-->
                                   <div class="row-fluid">
                                       <div class="span9 ">
                                         <div class="control-group">
                                           <label class="control-label">Upload File (CSV)</label>
                                           <div class="controls">
                                              <input type="file" name="attach_file" id="accessattach_file">
                                              <span class="help-block" id="accessfileInfo"></span>
                                           </div>
                                          </div>
                                       </div>
                                       <div class="span3" align="right">
                                         <a type="button" href="http://127.0.0.1:4001/ses/PMAccess.csv" class="btn icn-only ttip" rel="tooltip" title="" target="_blank" data-original-title="Download Format">
                                                <i class="icon-download-alt"></i>
                                              </a>
                                       </div>
                                       <!--/span-->
                                    </div>  
                                    
                                 
                                    <div class="form-actions" style="padding: 19px 10px 20px;">
                                       <button type="button" onclick="accessattachment_submit()" class="btn blue"><i class="icon-ok"></i> Upload</button>
                                      <button type="button" class="btn" onclick="location.reload()">Cancel</button>
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
          </div>
        </div>


             <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-globe"></i>List of Portfolio Managers</h4>
                     </div>
                     <?php
            
                     $sql_an = mysql_query("SELECT * from users where created_by_prim = '$_SESSION[MEM_ID]' order by name asc");
                     

                     ?>
                     <div class="portlet-body">
                       <table class="table table-stripped tablesorter">
                           <thead>
                              <tr>
                                 <th>SN</th>
                                 <th>User Name</th>
                                 <th>Username/Email</th>
                                 <th>Mobile</th>
                                 <th>Action</th>
                              </tr>
                              
                           </thead>
                  
                           <tbody id="userbody">
                           <?php
                           $count=1;
                           while ($row_an = mysql_fetch_array($sql_an)) {
                             ?><tr id="tr_<?php echo $count; ?>">
                               <td><?php echo $count?></td>
                               <td><?php echo $row_an["name"]?></td>
                               <td><?php echo $row_an["username"]?></td>
                               <td><?php echo $row_an["mobile"]?></td>
                               <td><a href="#myModal" class="btn" data-toggle="modal" onclick="edit_user_ui(<?php echo $row_an["id"]?>,'<?php echo $row_an["name"]?>',<?php echo $count; ?>)" >Edit</a>&nbsp;
                                <?php if($row_an["voting_access"] != 0){ ?>
                               <a href="#myModal" class="btn" data-toggle="modal" onclick="voting_companies(<?php echo $row_an["id"]?>,'<?php echo $row_an["name"]?>',<?php echo $count; ?>)" >Voting Companies</a>
                               
                              <?php } ?>
                              <a href="javascript:;" class="btn red" onclick="remove_user(<?php echo $row_an["id"]?>,'<?php echo $row_an["name"]?>',<?php echo $count; ?>)" >Remove</a>
                              </td>
                             </tr>
                             <?php
                              $count++;
                           }

                           ?>                            

                           </tbody>
                        </table>
                     </div>
                  </div>
   
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:90%; margin-left:-45%">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body" id="modal-body" style="min-height:300px;">
    
  </div>
  <div class="modal-footer">

    <button class="btn" data-dismiss="modal" aria-hidden="true" id="close_button">Close</button>
  </div>
</div>  
				
				
</div>
<script type="text/javascript">
var count = 0;
var name = '';
function check_submit(){

      if( validate_required_name_info($("#name").val(), 'name','Please input valid name. Only numbers, alphabates and space.') && validate_required_email_idinfo($("#email").val(), 'email','Please input valid email')  ){
         submit_form.submit();
      } else {
         return false;
      }
}

function edit_user_ui(sub_user_id,sub_user_name, count_in ){
   count = count_in;
   name = sub_user_name;
   $("#myModalLabel").text(sub_user_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'edit_user_ui';
   $.post("ajax/"+ file +".php", {id:sub_user_id}, function(data) {
      $("#modal-body").html(data);
   }); 

}
function save_changes(sub_user_id){
         var file = 'edit_user';
         $.post("ajax/"+ file +".php", {id:sub_user_id, voting_access:$("#voting_access_up").val(), mobile:$("#mobile_up").val(), name:$("#name_up").val() }, function(data) {
            if(data == 'success'){
              //var txt = '<td>' + count + '</td><td>'+name+'</td><td>'+$("#email_up").val()+'</td><td>'+$("#mobile_up").val()+'</td><td><a href="#myModal" class="btn" data-toggle="modal" onclick="edit_user_ui('+ sub_user_id + ',\'' + name + '\','+ count +')" >Edit</a>';
              //$("#tr_"+count).html(txt);
               $("#close_button").trigger('click');
              bootbox.alert('User is successfully edited.');
            } else {
              alert('Database error');
            }
         }); 
      
}

function voting_companies(sub_user_id,sub_user_name,count_in ){

   count = count_in;
   $("#myModalLabel").text(sub_user_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'voting_companies';
   $.post("ajax/"+ file +".php", {id:sub_user_id}, function(data) {
      $("#modal-body").html(data);
      initialize();
   }); 

}
function voting_companies_add(sub_user_id){
  if($("#com_string").val()){
    $("#add_button").html("Adding.. Please Wait");
   var file = 'voting_companies_add';
   $.post("ajax/"+ file +".php", {id:sub_user_id,com_string:$("#com_string").val()}, function(data) {
     $("#com_string").val('');
      if(data == ''){
        bootbox.alert('Company Already Exists!');
        $("#add_button").html("Add");
      }else if(data != 'fail'){
        $("#add_button").html("Add");
        $("#table_tbody").prepend(data);
        $("#table_tbody tr:first").css("background",'#ffff00');
        $("#table_tbody tr:first").animate({backgroundColor:''},{duration:500});
      } else {
        alert("Databse Error");
      }
   }); 
 } else {
  bootbox.alert("Please select some company");
 }

}
function voting_companies_delete(sub_user_id,company_id){
 var file = 'voting_companies_delete';

            $.post("ajax/"+ file +".php", {id:sub_user_id,company_id:company_id}, function(data) {
              
               if(data == 'success') {
                  $("#tr_"+company_id).hide("slow");
                } else {
                  alert("Database error");
                }
             });
          
}

function remove_user(user_id, user_name, count){
   
     var file = "delete_user";
        bootbox.confirm("Are you sure to remove "+ user_name+ "?", function(result) {
          if(result) {
            $.post("ajax/"+ file +".php", {user_id:user_id}, function(data) {
                  if(data == 'success'){
                      $("#tr_"+count).hide("slow",function(){$("#tr_"+count).remove();});
                    } else {
                    alert('Deletion error');
                  }   
             }); 
          }
          else {
          
          }
        });       
}
function validateFile(filename,spanname){
   var ext = $("#"+filename).val().split('.').pop().toLowerCase();
   if($.inArray(ext, ['csv']) == -1) {
      $('#'+spanname).text("Please Choose a valid csv file");
      return false;
   } else {
      $('#'+spanname).text("");
      return true;
   }
}
function attachment_submit(){
   if( validateFile('attach_file','fileInfo'))
      $('#attachment_form').submit();
   else
      return false;     
}
function pmattachment_submit(){
   if( validateFile('pmattach_file','pmfileInfo'))
      $('#pmattachment_form').submit();
   else
      return false;     
}
function accessattachment_submit(){
   if( validateFile('accessattach_file','accessfileInfo'))
      $('#accessattachment_form').submit();
   else
      return false;     
}
</script>