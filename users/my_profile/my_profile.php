<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Profile
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
					$text = 'Successfully Updated';
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
                                 <h4><i class="icon-reorder"></i>Update Your Details: <?php echo stripcslashes($row_sub["name"]);?></h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <?php
                              $priv_sub = mysql_query("SELECT * from users where id='$_SESSION[MEM_ID]' ");
                              $row_sub = mysql_fetch_array($priv_sub);
                              ?>
                                                
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <form action="<?php echo $folder?>process.php?cat=1" method="post" class="horizontal-form" id="submit_form">
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="name">User Admin Name</label>
                                             <div class="controls">
                                              <input id="user_admin_name" name="user_admin_name" class="m-wrap span12" value="<?php echo stripcslashes($row_sub["user_admin_name"]);?>" type="text" >
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName">Email</label>
                                             <div class="controls">
                                              <input id="email" name="email" class="m-wrap span12" value="<?php echo stripcslashes($row_sub["email"]);?>" type="text" readonly>
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
                                             <label class="control-label" for="name">Contact</label>
                                             <div class="controls">
                                              <input id="mobile" name="mobile" class="m-wrap span12" value="<?php echo stripcslashes($row_sub["mobile"]);?>" type="text" >
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName">Address</label>
                                             <div class="controls">
                                              <input id="address" name="address" class="m-wrap span12" value="<?php echo stripcslashes($row_sub["address"]);?>" type="text">
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
                                             <label class="control-label" for="name">IT Name</label>
                                             <div class="controls">
                                              <input id="IT_name" name="IT_name" class="m-wrap span12" value="<?php echo stripcslashes($row_sub["IT_name"]);?>" type="text" >
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName">IT Contact</label>
                                             <div class="controls">
                                              <input id="IT_contact" name="IT_contact" class="m-wrap span12" value="<?php echo stripcslashes($row_sub["IT_contact"]);?>" type="text">
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
                                             <label class="control-label" for="name">IT Email</label>
                                             <div class="controls">
                                              <input id="IT_email" name="IT_email" class="m-wrap span12" value="<?php echo stripcslashes($row_sub["IT_email"]);?>" type="text" >
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="name">Default Voting Deadline</label>
                                             <div class="controls">
                                              <input id="def_deadline_vote" name="def_deadline_vote" class="m-wrap span12" value="<?php echo stripcslashes($row_sub["def_deadline_vote"]);?>" type="text" >
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->

                                    <!--/row-->
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="name">Self Portfolio</label>
                                             <div class="controls">
                                              <select name="self_portfolio" class="m-wrap span12">
                                                <option value="0">No</option>
                                                <option value="1" <?php echo ($row_sub["self_portfolio"] == 1)?'selected':''; ?>>Yes</option>
                                              </select>
                                              <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                    
                                 
                                    <div class="form-actions" style="padding: 19px 10px 20px;">
                                       <button type="submit" class="btn blue"><i class="icon-ok"></i> Update</button>
                                     
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                              <?php ?>
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
                              </tr>
                              
                           </thead>
                  
                           <tbody id="userbody">
                           <?php
                           $count=1;
                           while ($row_an = mysql_fetch_array($sql_an)) {
                             ?><tr>
                             <td><?php echo $count?></td>
                             <td><?php echo $row_an["name"]?></td>
                             <td><?php echo $row_an["username"]?></td>
                             <td><?php echo $row_an["mobile"]?></td>
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
