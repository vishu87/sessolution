<?php 
if(!isset($title) && !isset($rid)) {
		die('This page can not be viewed');
	}

  $sql_rep = mysql_query("SELECT proxy_ad.*, companies.com_name from proxy_ad inner join companies on proxy_ad.com_id=companies.com_id where proxy_ad.id='$rid' ");
  $report = mysql_fetch_array($sql_rep);
  
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			<?php echo $report["com_name"].', '.$meeting_types[$report["meeting_type"]].' on '.date("d M Y", $report["meeting_date"]); ?>
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
          $text = 'Successfully updated';
          break;
      case (2):
          $text_class= 'alert-success';
          $text = 'Successfully Removed';
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
                                 <h4><i class="icon-reorder"></i>Edit</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form action="<?php echo $folder;?>process.php?cat=2&amp;rid=<?php echo $rid;?>" class="form-horizontal" method="post" enctype="multipart/form-data">
                                   
                                   <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Meeting Date</label>
                                       <div class="controls">
                                        <input type="hidden" name="com_id" value="<?php echo $report["com_id"]?>">
                                           <input type="text" name="meeting_date" class="datepicker_month" value="<?php echo date("d-m-Y", $report["meeting_date"]); ?>">
                                          <span class="help-block"></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Meeting Type</label>
                                       <div class="controls">
                                         <select name="meeting_type">
                                          <?php
                                            $sql_type = mysql_query("SELECT * from met_type");
                                            while ($type = mysql_fetch_array($sql_type)) {
                                              echo '<option value="'.$type["id"].'" ';
                                              if($type["id"] == $report["meeting_type"]) echo 'selected';
                                              echo '>'.$type["type"].'</option>';
                                            }
                                          ?>
                                         </select>
                                        </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Report</label>
                                       <div class="controls">
                                           <input type="file" name="report"><br>
                                           <?php
                                            if($report["report"] != '') {
                                           ?>
                                           <a href="../proxy_reports/<?php echo $report["report"]?>" target="_blanks">View Current</a>&nbsp;&nbsp;<a href="<?php echo $folder;?>process.php?cat=5&amp;rid=<?php echo $rid;?>">Remove Current</a>
                                           <?php } ?>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                           <div class="control-group">
                                       <label class="control-label">Notice</label>
                                       <div class="controls">
                                           <input type="file" name="notice"><br>
                                           <?php
                                            if($report["notice"] != '') {
                                           ?>
                                           <a href="../proxy_notices/<?php echo $report["notice"]?>" target="_blanks">View Current</a>&nbsp;&nbsp;<a href="<?php echo $folder;?>process.php?cat=6&amp;rid=<?php echo $rid;?>">Remove Current</a>
                                           <?php } ?>
                                           <br>OR<br>
                                           <input type="text" name="notice_link" placeholder="Add Link" value="<?php echo $report["notice_link"]?>">
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->

                                     <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Teasor</label>
                                       <div class="controls">
                                           <input type="text" name="teasor" placeholder="Teasor Link" value="<?php echo $report["teasor"]?>">
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Annual Report</label>
                                       <div class="controls">
                                           <input type="text" name="annual_report" placeholder="Annual Report Link" value="<?php echo $report["annual_report"]?>">
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                   

                                   <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Meeting Outcome</label>
                                       <div class="controls">
                                           <input type="text" name="meeting_outcome" placeholder="Meeting Outcome Link" value="<?php echo $report["meeting_outcome"]?>">
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Meeting Minutes</label>
                                       <div class="controls">
                                           <textarea name="meeting_minutes" placeholder="Meeting Minutes"><?php echo $report["meeting_minutes"]?></textarea>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->


                                    <!--/row-->
                                    <div class="form-actions">
                                       <button type="submit" class="btn blue"><i class="icon-ok"></i> Update</button>
                                      
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
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

var select_pack_id = 0;
function check_edit_submit(){
      if(validate_name($('#package_name').val(),'com_name','Please input valid name') ){
        var file = 'update_package';

         $.post("ajax/"+ file +".php", {<?php 
         $ar_fields_all = array("package_id","package_name", "package_year");
         $count_check =0;
          foreach ($ar_fields_all as $ar) {
            if($count_check != 0) echo ', ';
            echo $ar.": $('#".$ar."').val()";
            $count_check++;
          }
        ?>}, function(data) {

          if(data == 'success'){
            $("#package_"+ $("#package_id").val()).html($("#package_name").val());
            $("#package_year_"+ $("#package_id").val()).html($("#package_year").val());
             $("#modal-body").html("Successfully Updated.");
          } else{
            $("#modal-body").html("Database error: Try Again.");
          }
          
       }); 

      } else {
         return false;
      }
}

//used
function voting_submit(report_id,vote_id){
      //alert($("#com_id_select").val());
        var file = 'add_vote';
        
         $.post("ajax/"+ file +".php", {report_id:report_id, id:vote_id, res:$("#resolution_pop").val(), detail:$("#detail_pop").val(), reason:$("#reason_pop").val()}, function(data) {
            $("#modal-body").html('<p>UPDATED</p>');
            $("#table_votes").html(data);
       }); 

      
}


function view_users(company_id, company_name, year){
   $("#myModalLabel").text(company_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'load_pa_subscribers';
   $.post("ajax/"+ file +".php", {id:company_id, year: year}, function(data) {
      $("#modal-body").html(data);
   }); 

}

//used
function voting(resolution_name, vote_id){
   $("#myModalLabel").text(resolution_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'load_voting_ui';
   $.post("ajax/"+ file +".php", {id:vote_id}, function(data) {
      $("#modal-body").html(data);
   }); 

}




function add_companies(package_name,package_id){
  $("#modal-bodyAddResult").html('');
  $(".chosen-select").val('').trigger("liszt:updated");

  $("#myModalLabelAdd").text(package_name); 
   select_pack_id = package_id;
}
/*
function add_companies(package_name,package_id){
   $("#myModalLabel").text(package_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'load_add_companies';
   $.post("ajax/"+ file +".php", {id:package_id}, function(data) {
      $("#modal-body").html(data);
   }); 

}*/

function edit_load(package_name,package_id){
   $("#myModalLabel").text("Edit Details"); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'load_package_edit';

   $.post("ajax/"+ file +".php", {id:package_id}, function(data) {
      $("#modal-body").html(data);
   }); 

}


function delete_research(id) {
     var file = 'delete_research';
        bootbox.confirm("Are you sure?", function(result) {
          if(result) {
            $.post("ajax/"+ file +".php", {id:id}, function(data) {
                if(data == 'success') {
                  $('#tr_'+ id).hide("slow");
                } else {
                  alert("Database error");
                }
             });
          }
          else {
          
          }
        });
  }


</script>