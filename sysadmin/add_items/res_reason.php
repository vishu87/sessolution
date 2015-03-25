<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Resolution Reasons
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
					$text = 'Successfully updated/added.';
					break;
       case (2):
          $text_class= 'alert-success';
          $text = 'Reason is successfully blocked/unblocked.';
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
   if(isset($update)){
       $query = mysql_query("SELECT * from reasons where id='$update' limit 1");
       $row = mysql_fetch_array($query);
   }

	?>
	<div class="row-fluid">
      <div class="span12">
         <div class="portlet box blue">
                     <div class="portlet-title">
                        <h4><i class="icon-reorder"></i>Add/update</h4>
                        <div class="tools">
                           
                        </div>
                     </div>
                     <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form action="<?php echo $folder ?>process.php?cat=<?php echo (isset($update))?'6&amp;update='.$update:'5'; ?>" class="horizontal-form" id="form" method="post">
                          
                           <div class="row-fluid">
                              <div class="span6 ">
                                 <div class="control-group">
                                    <label class="control-label" for="firstName">Reason</label>
                                    <div class="controls">
                                       <input type="text" id="name" name="name" class="m-wrap span12" placeholder="" value ="<?php echo (isset($row))?stripcslashes($row["reason"]):''; ?>">
                                       <span class="help-block"></span>
                                    </div>
                                 </div>
                              </div>
                              <!--/span-->
                              <div class="span6 ">
                                <div class="control-group">
                                    <label class="control-label" for="firstName">Resolution Type</label>
                                    <div class="controls">
                                       <select name="res_type_id" class="span12 m-wrap">
                                        <option value="0">Select Resolution Type</option>
                                        <?php
                                          $sql_type = mysql_query("SELECT id,resolution from resolutions where status =0 order by resolution asc");
                                          while ($row_type = mysql_fetch_array($sql_type)) {
                                            ?>
                                            <option value='<?php echo $row_type["id"] ?>' <?php
                                              if(isset($row)){
                                                if($row_type["id"] == $row["res_type_id"]) echo 'selected';
                                              }
                                             ?>><?php echo $row_type["resolution"] ?></option>
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
                           
                           <div class="form-actions">
                              <button type="button" class="btn blue" onclick="form_submit()"><i class="icon-ok"></i> Save</button>
                           </div>
                        </form>
                        <!-- END FORM--> 
                     </div>
                  </div>
      </div>
   </div>

<div class="portlet box light-grey">
   <div class="portlet-title">
      <h4><i class="icon-globe"></i>Types</h4>
   </div>
   
   <div class="portlet-body">
     <table class="table table-stripped tablesorter">
         <thead>
            <tr>
               <th></th>
               <th>Reason</th>
               <th>Resolution Type</th>
               <th></th>
            </tr>
            
         </thead>
         <tfoot>
            <tr>
              <th></th>
               <th>Reason</th>
               <th>Resolution Type</th>
               <th></th>
            </tr>
            <tr>
              <th colspan="10" class="ts-pager form-horizontal">
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
          $count =1;
          $query = mysql_query("SELECT reasons.*, resolutions.resolution from reasons left join resolutions on reasons.res_type_id = resolutions.id order by reasons.reason asc");
          while ($row = mysql_fetch_array($query)) {
             
          ?>
          <tr id="tr_<?php echo $row["id"]; ?>" >
            <td><?php echo $count; ?></td>
             <td><?php echo stripcslashes($row["reason"]);  ?></td>
             <td><?php echo stripcslashes($row["resolution"]);  ?></td>
             
               <td><a href="add_items.php?cat=3&amp;update=<?php echo $row["id"]?>" class="btn">Edit</a>
                <a href="<?php echo $folder;?>process.php?cat=14&amp;update=<?php echo $row["id"]?>" class="btn">
                <?php echo ($row["status"] == 0)?'Block':'Unblock'; ?>
               </a>
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
				
</div><!-- END CONTAINER -->

<script type="text/javascript">

function form_submit(){
   if(validate_required_gen_idinfo($("#name").val(), 'name','Please input valid entry') ){
      
               $('#form').submit();
            
   }
     
   else
      return false;     
}

</script>