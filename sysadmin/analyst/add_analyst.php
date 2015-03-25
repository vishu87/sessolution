<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Add Analyst		
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
					$text = 'Analyst is successfully added';
					break;
			case (2):
					$text_class= 'alert-error';
					$text = 'Error: Duplicate Email';
					break;
      case (3):
        $text_class= 'alert-error';
        $text = 'Error: Please input a valid Email';
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
                                             <label class="control-label" for="lastName">Email (Username)</label>
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
                                             <label class="control-label">Contact</label>
                                             <div class="controls">
                                    <input id="contact" name="contact" class="m-wrap span12" placeholder="eg. 9634628759" type="text">
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                         <div class="control-group">
                                             <label class="control-label">Details</label>
                                             <div class="controls">
                                                 <textarea id="details" name="details" class="m-wrap span12"></textarea>
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
                        <h4><i class="icon-globe"></i>Analysts</h4>
                     </div>
                     <?php
            
                     $sql_an = mysql_query("SELECT * from analysts order by name asc");
                     

                     ?>
                     <div class="portlet-body">
                       <table class="table table-stripped tablesorter">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Analyst Name</th>
                                 <th>Username/Email</th>
                                 <th>Contact</th>
                                 <th>Details</th>
                                 <th></th>
                              </tr>
                              
                           </thead>
                             <tfoot>
                              <tr>
                                  <th>#</th>
                                 <th>Analyst Name</th>
                                 <th>Username/Email</th>
                                 <th>Contact</th>
                                 <th>Details</th>
                                 <th></th>
                              </tr>
                              <tr>
                                <th colspan="8" class="ts-pager form-horizontal">
                                  <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
                                  <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
                                  <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                                  <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
                                  <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
                                  <select class="pagesize input-mini" title="Select page size">
                                    <option selected="selected" value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                  </select>
                                  <select class="pagenum input-mini" title="Select page number"></select>
                                </th>
                              </tr>
                            </tfoot>
                           <tbody>
                           <?php
                           $count=1;
                           while ($row_an = mysql_fetch_array($sql_an)) {
                             ?><tr>
                             <td><?php echo $count?></td>
                             <td><?php echo stripcslashes($row_an["name"]);?></td>
                             <td><?php echo stripcslashes($row_an["email"]);?></td>
                             <td><?php echo stripcslashes($row_an["contact"]);?></td>
                             <td><?php echo stripcslashes($row_an["details"]);?></td>
                             <td>
                              <a href="analyst.php?cat=3&amp;aid=<?php echo $row_an["an_id"]?>" class="btn" data-toggle="modal">Edit</a>
                              <?php
                                if($row_an["active"] == 0){
                                  ?>
                                    <a href="<?php echo $folder?>process.php?cat=3&amp;aid=<?php echo $row_an["an_id"]?>" class="btn red" data-toggle="modal">Block</a>
                                  <?php
                                } else {
                                  ?>
                                  <a href="<?php echo $folder?>process.php?cat=4&amp;aid=<?php echo $row_an["an_id"]?>" class="btn yellow" data-toggle="modal">UnBlock</a>
                                  <?php
                                }
?>

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
              <form method="post" target="_blank" action="../excel/list_analyst.php">
                
                <button type="submit" class="btn" style=""><i class="icon-share"></i> Export</button>
      
              </form>
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

      if(validate_required_name_idinfo($("#name").val(), 'name','Please input valid name') &&  validate_required_email_idinfo($("#email").val(), 'email','Please input valid email') ){
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