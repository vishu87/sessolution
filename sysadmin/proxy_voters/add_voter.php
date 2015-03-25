<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Add Proxy Voter		
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
					<div class="span12">
						<div class="portlet box blue">
						        <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Add</h4>
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
                                             <label class="control-label">Location</label>
                                             <div class="controls">
                                              <select name="location">
                                                <?php
                                                  $loc_sql = mysql_query("SELECT * from locations where status=0 order by place asc");
                                                  while ($row_loc = mysql_fetch_array($loc_sql)) {
                                                    echo '<option value="'.$row_loc["id"].'">'.$row_loc["place"].'</option>';
                                                  }
                                                ?>

                                              </select>
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->   
                                    
                                 
                                    <div class="form-actions">
                                       <button type="button" onclick="check_submit()" class="btn blue"><i class="icon-ok"></i> Create</button>
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
                        <h4><i class="icon-globe"></i>List</h4>
                     </div>
                     <?php
            
                     $sql_an = mysql_query("SELECT proxy_voters.*, locations.place from proxy_voters inner join locations on proxy_voters.location = locations.id order by proxy_voters.name asc");
                     

                     ?>
                     <div class="portlet-body">
                       <table class="table table-stripped tablesorter">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Name</th>
                                 <th>Location</th>
                                 <th>Email</th>
                                 <th>Mobile</th>
                                 <th></th>
                              </tr>
                              
                           </thead>
                  
                           <tbody>
                           <?php
                           $count=1;
                           while ($row_an = mysql_fetch_array($sql_an)) {
                             ?><tr>
                             <td><?php echo $count?></td>
                             <td><?php echo $row_an["name"]?></td>
                             <td><?php echo $row_an["place"]?></td>
                             <td><?php echo $row_an["email"]?></td>
                              <td><?php echo $row_an["mobile"]?></td>
                             <td>
                              <a href="proxy_voters.php?cat=3&amp;aid=<?php echo $row_an["vid"]?>" class="btn" data-toggle="modal">Edit</a>


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
              <form method="post" target="_blank" action="../excel/list_voters.php">
               
                <button type="submit" class="btn" style=""><i class="icon-share"></i> Export</button>
      
              </form>
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

</script>