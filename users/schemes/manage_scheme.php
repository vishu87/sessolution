<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Scheme Management
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
					$text = 'Scheme is successfully added';
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
                                 <h4><i class="icon-reorder"></i>Add New Scheme</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>              
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <form action="<?php echo $folder?>process.php?cat=1" method="post" class="horizontal-form" id="submit_form">
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="name">Scheme Name</label>
                                             <div class="controls">
                                              <input id="scheme_name" name="scheme_name" class="m-wrap span12" placeholder="" type="text" >
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName">DP ID</label>
                                             <div class="controls">
                                              <input id="dp_id" name="dp_id" class="m-wrap span12" placeholder="eg. XYZ123" type="text">
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
                                             <label class="control-label">Client ID
                                             <div class="controls">
												                        <input id="client_id" name="client_id" class="m-wrap span12" placeholder="eg. AB1234" type="text">
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                        <div class="control-group">
                                             <label class="control-label">Depository
                                             <div class="controls">
                                              <select name="depository" class="m-wrap">
                                                <option value="0">Select</option>
                                                <?php
                                                  foreach ($depositories as $key => $value) {
                                                    ?>
                                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                    <?php
                                                  }
                                                ?>
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
					</div>
				</div>

          <div class="row-fluid ">
          <div class="span12">
            <div class="portlet box blue">
                    <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Bulk Upload of Schemes</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>

                                                
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <form action="<?php echo $folder?>process.php?cat=2" method="post" class="horizontal-form" id="accessattachment_form" target="_blank" enctype="multipart/form-data">
                                    <!--/row-->
                                   <div class="row-fluid">
                                        <div class="span4 ">
                                         <div class="control-group">
                                           <label class="control-label">Date</label>
                                           <div class="controls">
                                              <input type="text" name="upload_date" class="datepicker_month">
                                              <span class="help-block" id=""></span>
                                           </div>
                                          </div>
                                       </div>
                                       <div class="span5 ">
                                         <div class="control-group">
                                           <label class="control-label">Upload File (CSV)</label>
                                           <div class="controls">
                                              <input type="file" name="attach_file" id="accessattach_file">
                                              <span class="help-block" id="accessfileInfo"></span>
                                           </div>
                                          </div>
                                       </div>
                                       <div class="span3" align="right">
                                         <a type="button" href="<?php echo STRSITE ?>Bulk_Scheme_Comp.csv" class="btn icn-only ttip" rel="tooltip" title="" target="_blank" data-original-title="Download Format">
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
                        <h4><i class="icon-globe"></i>List of Schemes</h4>
                     </div>
                     <?php
            
                     $sql_an = mysql_query("SELECT * from schemes where user_id = '$_SESSION[MEM_ID]' order by scheme_name asc");
                     

                     ?>
                     <div class="portlet-body">
                       <table class="table table-stripped tablesorter">
                           <thead>
                              <tr>
                                 <th>SN</th>
                                 <th>Scheme Name</th>
                                 <th>DP Id</th>
                                 <th>Client Id</th>
                                 <th>Depository</th>
                                 <th>Companies</th>
                                 <th>Action</th>
                              </tr>
                              
                           </thead>
                  
                           <tbody id="userbody">
                           <?php
                           $count=1;
                           while ($row_an = mysql_fetch_array($sql_an)) {
                             ?><tr id="tr_<?php echo $count; ?>">
                               <td><?php echo $count?></td>
                               <td><?php echo $row_an["scheme_name"]?></td>
                               <td><?php echo $row_an["dp_id"]?></td>
                               <td><?php echo $row_an["client_id"]?></td>
                               <td><?php echo $depositories[$row_an["depository"]]?></td>
                               <td><a href="#myModal" class="btn" data-toggle="modal" onclick="view_scheme_comp(<?php echo $row_an["id"]?>,'<?php echo $row_an["scheme_name"]?>',<?php echo $count; ?>)" >View</a></td>
                               <td>
                                  <a href="schemes.php?cat=2&update=<?php echo encrypt($row_an["id"]) ?>" class="btn" >Edit</a>
                                  <a href="javascript:;" class="btn red" onclick="remove_scheme(<?php echo $row_an["id"]?>,'<?php echo $row_an["scheme_name"]?>',<?php echo $count; ?>)" >Remove</a>
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
  var submit_form = $("#submit_form");
      if(validate_required_name_info($("#scheme_name").val(), 'scheme_name','Please input valid name. Only numbers, alphabates and space.')){
        submit_form.submit();
      } else {
         return false;
      }
}


function view_scheme_comp(scheme_id,scheme_name,count){

   $("#myModalLabel").html(scheme_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'view_scheme_comp';
   $.post("ajax/"+ file +".php", {scheme_id:scheme_id}, function(data) {
      $("#modal-body").html(data);
      initialize();
   }); 

}

function remove_scheme(scheme_id, scheme_name, count){
   var file = "delete_scheme";
      bootbox.confirm("Are you sure to remove "+ scheme_name+ "?", function(result) {
        if(result) {
          $.post("ajax/"+ file +".php", {scheme_id:scheme_id}, function(data) {
            if(data == 'success'){
                $("#tr_"+count).hide("slow",function(){$("#tr_"+count).remove();});
              } else {
              alert(data);
            }
           }); 
        }
        else {
        }
      });       
}

function remove_scheme_company(id, com_id){
  $("#rm_comp_"+id).html("Removing..");
    var file = "delete_scheme_company";
    $.post("ajax/"+ file +".php", {id:id, com_id:com_id}, function(data) {
      if(data == 'success'){
          $("#tr_pop_"+id).hide("slow",function(){$("#tr_pop_"+id).remove();});
        } else {
        alert(data);
        $("#rm_comp_"+id).html("Remove");
      }
     });      
}
function accessattachment_submit(){
   if( validateFile('accessattach_file','accessfileInfo'))
      $('#accessattachment_form').submit();
   else
      return false;     
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

</script>