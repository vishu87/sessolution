<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Price
			<small></small>
		</h3>
	</div>
	<?php

	if(isset($_GET["success"]))
	{
		switch($_GET["success"])
		{
			case (1):
					$text_class= 'alert-success';
					$text = 'Prices are successfully changed.';
					break;
			case (0):
					$text_class= 'alert-error';
					$text = 'Error: Database Error';
					break;
		}
		echo '<div class="alert '.$text_class.'">
			<button class="close" data-dismiss="alert"></button>
			'.$text.'
			</div>';
	}
	?>
	<div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN SAMPLE FORM PORTLET-->   
                  <div class="portlet box blue tabbable">
                     <div class="portlet-title">
                        <h4>
                           <i class="icon-reorder"></i>
                           <span class="hidden-480">Set Price</span>
                           &nbsp;
                        </h4>
                     </div>
                     <div class="portlet-body form">
                        <div class="tabbable portlet-tabs">
                           <ul class="nav nav-tabs">
                              <li class="active"><a href="#portlet_tab1" data-toggle="tab">Change</a></li>
                           </ul>
                           <div class="tab-content">
                              <div class="tab-pane active" id="portlet_tab1">
                                 <!-- BEGIN FORM-->
                                 <form action="<?php echo $folder;?>process.php" method="post" class="form-horizontal">
                                    
                                    <?php 
                                       $sql_price = mysql_query("SELECT * from price ");
                                       while ($row_price = mysql_fetch_assoc($sql_price)) {
                                          
                                       

                                    ?>
                                    <div class="control-group">
                                       <label class="control-label"><?php echo $row_price["type"]?></label>
                                       <div class="controls">
                                             <input type="text" name="price_<?php echo $row_price["id"]; ?>" class="m-wrap small" value="<?php echo $row_price["price"]?>">
                                          <span class="help-inline"></span>
                                       </div>
                                    </div>
                                    <?php } ?>
                                    
                                    <div class="form-actions">
                                       <button type="submit" class="btn blue"><i class="icon-ok"></i> Save</button>
                                       <button type="button" class="btn">Cancel</button>
                                    </div>
                                 </form>
                                 <!-- END FORM-->  
                              </div>
                             
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- END SAMPLE FORM PORTLET-->
               </div>
            </div>


				
</div><!-- END CONTAINER -->

</script>