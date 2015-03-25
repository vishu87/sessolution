<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Proxy Voters		
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
					$text = 'Voter is successfully added';
					break;

		}
		echo '<div class="alert '.$text_class.'">
			<button class="close" data-dismiss="alert"></button>
			'.$text.'
			</div>';
	}
  
	?>

	<div class="row-fluid ">
					<div class="span6">
						<div class="portlet box blue">
						        <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Add Proxy Voter</h4>
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
                                             <label class="control-label" for="name">Name</label>
                                             <div class="controls">
                                                <input id="name" name="name" class="m-wrap span12" placeholder="eg. Rahul" type="text">
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName">Email</label>
                                             <div class="controls">
                                              <input id="email" name="email" class="m-wrap span12" placeholder="eg. abcd@ses.com" type="text">
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
                                             <label class="control-label">Mobile</label>
                                             <div class="controls">
												            <input id="mobile" name="mobile" class="m-wrap span12" placeholder="eg. 9587986254" type="text">
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                         <div class="control-group">
                                             <label class="control-label">&nbsp;</label>
                                             <div class="controls">
                                     <button type="button" onclick="check_submit()" class="btn blue"><i class="icon-ok"></i> Create</button>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->   

                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
					</div>
			
               <div class="span6">
                  <div class="portlet box blue">
                          <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Upload Proxy Voter List</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form id="attachment_form" action="<?php echo $folder?>process.php?cat=3" class="horizontal-form" method="post" enctype="multipart/form-data" target="_blank" >
                                   
                                   <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                           <label class="control-label">Upload File (CSV)</label>
                                           <div class="controls">
                                              <input type="file" name="attach_file" id="attach_file">
                                              <span class="help-block" id="fileInfo"></span>
                                           </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6" align="right">
                                         <a type="button" href="<?php echo STRSITE ?>ProxyVoter.csv" class="btn icn-only ttip" rel="tooltip" title="Download Format" target="_blank">
                                                <i class="icon-download-alt"></i>
                                              </a>
                                       </div>
                                       <!--/span-->
                                    </div>

                                    <div class="row-fluid">
                                       <div class="span6 ">
                                         <button type="button" onclick="attachment_submit()" class="btn blue" style=""><i class="icon-ok"></i> Upload</button>
                                       </div>
                                       <!--/span-->
                                    </div>
                                      <div style="height:60px;">&nbsp;</div>
                                 </form>
                                 <!-- END FORM--> 
                               </div>
                           </div>
               </div>
            </div>


             <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-reorder"></i>List of Existing Proxy Voters</h4>
                         <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                     </div>
                     <?php
            
                     $sql_an = mysql_query("SELECT * from self_proxy_voters where user_id='$_SESSION[MEM_ID]' order by name asc");
                     

                     ?>
                     <div class="portlet-body">
                       <table class="table table-stripped tablesorter">
                           <thead>
                              <tr>
                                 <th>SN</th>
                                 <th>Name</th>
                                 <th>Email</th>
                                 <th>Mobile</th>
                                 <th></th>
                              </tr>
                              
                           </thead>
                  
                           <tbody>
                           <?php
                           $count=1;
                           while ($row_an = mysql_fetch_array($sql_an)) {
                             ?><tr id="tr_<?php echo $row_an["vid"]; ?>">
                             <td><?php echo $count?></td>
                             <td><?php echo $row_an["name"]?></td>
                             <td><?php echo $row_an["email"]?></td>
                              <td><?php echo $row_an["mobile"]?></td>
                             <td>
                              <a href="proxy_voters.php?cat=2&amp;aid=<?php echo encrypt($row_an["vid"])?>" class="btn span6" data-toggle="modal">Edit</a>
                              <a href="javascript:;" onclick="delete_voter(<?php echo $row_an["vid"];?>)" class="btn span6 red" data-toggle="modal">DELETE</a>
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
<div class="row-fluid">
            <div class="span6">
            
            </div>
           </div>
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:750px;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body" id="modal-body">
    
  </div>
  <div class="modal-footer">

    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>  
				
				
</div>

<script type="text/javascript">
function check_submit(){

      if(validate_required_gen_idinfo($("#name").val(), 'name','Please input valid name') &&  validate_required_gen_idinfo($("#email").val(), 'email','Please input email') ){
         $("#submit_form").submit();
      } else {
         return false;
      }
}

function edit_an(analyst_name, analyst_id){
   $("#myModalLabel").text(analyst_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'load_analyst_edit';
   $.post("ajax/"+ file +".php", {id:analyst_id}, function(data) {
      $("#modal-body").html(data);
   }); 

}
function validateFile(){
   var ext = $("#attach_file").val().split('.').pop().toLowerCase();
   if($.inArray(ext, ['csv']) == -1) {
      $('#fileInfo').text("Please Choose a valid csv file");
      return false;
   } else {
      $('#fileInfo').text("");
      return true;
   }
}
function attachment_submit(){
   if( validateFile())
      $('#attachment_form').submit();
   else
      return false;     
}
function delete_voter(voter_id){
   
     var file = "delete_voter";
        bootbox.confirm("Are you sure?", function(result) {
          if(result) {
            $.post("ajax/"+ file +".php", {id:voter_id}, function(data) {
                  if(data == 'success'){
                      $("#tr_"+voter_id).hide("slow", function(){$("#tr_"+voter_id).remove});
                    } else {
                    alert('Deletion error');
                  }   
             }); 
          }
          else {
          
          }
        });       
}

</script>