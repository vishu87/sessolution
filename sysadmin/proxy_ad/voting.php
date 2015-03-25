<?php 
if(!isset($title) && !isset($rid)) {
		die('This page can not be viewed');
	}

  $sql_rep = mysql_query("SELECT proxy_ad.meeting_date, proxy_ad.meeting_type, companies.com_name from proxy_ad inner join companies on proxy_ad.com_id=companies.com_id where proxy_ad.id='$rid' ");
  $report = mysql_fetch_array($sql_rep);
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Voting: <?php echo $report["com_name"].', '.$meeting_types[$report["meeting_type"]].' on '.date("d M Y", $report["meeting_date"]); ?>
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
          $text = 'Vote is successfully added';
          break;
      case (4):
          $text_class= 'alert-error';
          $text = 'Error: Duplicate company bse code';
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
                                 <h4><i class="icon-reorder"></i>Add Vote</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form action="<?php echo $folder;?>process.php?cat=4&amp;rid=<?php echo $rid;?>" class="form-horizontal" method="post">
                                   
                                   <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Resolution</label>
                                       <div class="controls">
                                           <select name="resolution" id="resolution">
                                            <?php
                                              $sql_reso = mysql_query("Select * from resolutions");
                                              while ($row_reso = mysql_fetch_array($sql_reso)) {
                                                echo '<option value="'.$row_reso["id"].'">'.$row_reso["resolution"].'</option>';
                                              } ?>

                                            </select>
                                          <span class="help-block"></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Details</label>
                                       <div class="controls">
                                          <textarea name="detail" id="detail"></textarea>   </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->

                                     <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Reasons</label>
                                       <div class="controls">
                                           <select name="reason[]" id="reason" class="chosen" multiple>
                                            <?php
                                              $sql_reso = mysql_query("Select * from reasons");
                                              while ($row_reso = mysql_fetch_array($sql_reso)) {
                                                echo '<option value="'.$row_reso["id"].'">'.$row_reso["reason"].'</option>';
                                              }?></select>
                                          <span class="help-block" id="fileInfo"></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                   
                                    <!--/row-->
                                    <div class="form-actions">
                                       <button type="submit" class="btn blue"><i class="icon-ok"></i> Add Vote</button>
                                      
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
          </div>
        </div>


     <table class="table table-bordered table-hover tablesorter" id="table_votes" >
     <tr><th>#</th><th>Resolution</th><th>Details</th><th>Reasons</th><th>Action</th></tr>
<?php
     $sql_vote = mysql_query("SELECT * from voting where report_id='$rid' order by id asc");
     $count =1;
     while($row_vote = mysql_fetch_array($sql_vote)) {
      echo '<tr id="tr_vote_'.$row_vote["id"].'"><td>'.$count.'</td>';
      $sql_reso = mysql_query("Select * from resolutions where id='$row_vote[resolution]' ");
        while ($row_reso = mysql_fetch_array($sql_reso)) {
          $reso = $row_reso["resolution"];
          echo '<td>'.$row_reso["resolution"].'</td>';
        }
        echo '<td>'.stripcslashes($row_vote["detail"]).'</td><td>';
        if($row_vote["reasons"] != ''){
        $sql_reso = mysql_query("Select * from reasons where id IN ($row_vote[reasons]) ");
        while ($row_reso = mysql_fetch_array($sql_reso)) {
          echo '<p>'.$row_reso["reason"].'</p>';
        }
    } ?>
        </td><td><a href="#myModal" role="button" class="btn" data-toggle="modal" onclick="voting('<?php echo $reso; ?>','<?php echo $row_vote["id"]; ?>');">Edit</a>
</td></tr>

<?php
        $count++;

     } ?>

</table>


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