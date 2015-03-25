<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
    $subscribed_img = '<img src="../assets/img/subs.png">';
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Pending Tasks
			<small></small>
		</h3>
	</div>
  <style type="text/css">
  .burn_red_dark{
    background: #f00 !important;
  }
  .burn_red{
    background: #ff5C33 !important;
  }
  .burn_yellow{
    background: #ff0 !important;
  }
  .burn_green{
    
  }

  </style>

            <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-globe"></i>Details</h4>
                     </div>
                     <?php
                     $analysts = array();
                     $sql_an = mysql_query("SELECT an_id, name from analysts ");
                     while ($row_an = mysql_fetch_array($sql_an)) {
                       $analysts[$row_an["an_id"]] = $row_an["name"];
                     }


                      $sql = mysql_query("SELECT * from report_analyst where an_id='$_SESSION[MEM_ID]' and completed_on='' order by deadline asc ");
                        
                      $count =1;
                    
                      $task_type=array("","Data","Analysis","Review");

                      $burn = array();
                      $sql_burn=mysql_query("SELECT * from deadline_burn");
                      while ($row_burn = mysql_fetch_array($sql_burn)) {
                        array_push($burn, $row_burn["days_left"]);
                      }

                     ?>
                     <div class="portlet-body">
                       <table class="table table-stripped tablesorter">
                           <thead>
                              <tr>
                                 <th class="filter-select filter-exact" data-placeholder="">Report Type</th>
                                 <th>Company Name</th>
                                 
                                 <th class="filter-select filter-exact" data-placeholder="">Type</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Task</th>
                                 <th >Deadline</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Status</th>
                                 <th>Action</th>
                              </tr>
                              
                           </thead>
                           <tfoot>
                              <tr>
                                 <th class="filter-select filter-exact" data-placeholder="">Report Type</th>
                                 <th>Company Name</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Type</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Task</th>
                                 <th >Deadline</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Status</th>
                                 <th>Action</th>
                              </tr>
                              <tr>
                                <th colspan="9" class="ts-pager form-horizontal">
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
                           
                           $today = strtotime("today");
                          
                           while($row = mysql_fetch_assoc($sql))
                           {
                           ?>
                            <?php      
                                switch ($row["rep_type"]) {
                                     case '1':
                                     $report = new PA_admin($row["report_id"]);
                                     break;
                                   
                                    case '2':
                                     $report = new CGS_admin($row["report_id"]);
                                     break;

                                     case '3':
                                     $report = new Research_admin($row["report_id"]);
                                     break;
                                  }
                                if($report->meeting_timestamp < $today) continue;
                                 ?>
                                <tr id="tr_<?php echo $row["id"];?>">
                                 <td><?php echo $report_types[$row["rep_type"]]; ?> <?php if($report->subs_bool()) echo $subscribed_img; ?></td>
                                 <td><?php echo $report->company_name;  $report->company_name = name_filter($report->company_name); ?></td>
                                 <td><?php echo (isset($report->meeting_type))?$report->meeting_type:''; ?></td>
                                 <td><?php echo $task_type[$row["type"]]; ?></td>
                                 <td class="<?php
                                  $days_left = ($row["deadline"] != '')?(($row["deadline"] - strtotime("now"))/86400):'50';
                                  if($days_left <=0){
                                    echo 'burn_red_dark';
                                  } elseif($days_left <= $burn[0]){
                                    echo 'burn_red';
                                  } elseif ($days_left <= $burn[1]) {
                                    echo 'burn_yellow';
                                  } else{
                                    echo 'burn_green';
                                  }
                                 ?>"><?php echo ($row["deadline"] != '')?date("d M Y",$row["deadline"]):'Not set'; ?></td>
                                
                                 <td><?php
                                 $flag_check =0;
                                  if($row["type"] ==1){
                                    echo 'Pending';
                                  } 
                                  elseif ($row["type"] == 2) {
                                   
                                    $query_check_1 = mysql_query("SELECT completed_on from report_analyst where report_id='$row[report_id]' and rep_type='$row[rep_type]' and type='1' ");
                                    $check1= mysql_fetch_array($query_check_1);
                                    if($check1["completed_on"] != '') { echo 'Pending'; }
                                    else {echo 'Contingent';  $flag_check=1;}

                                  }
                                  elseif ($row["type"] == 3) {

                                    
                                    $query_check_1 = mysql_query("SELECT completed_on from report_analyst where report_id='$row[report_id]' and rep_type='$row[rep_type]' and type='1' ");
                                    $check1= mysql_fetch_array($query_check_1);
                                    
                                    if($check1["completed_on"] != '') {
                                       
                                       $query_check_2 = mysql_query("SELECT completed_on from report_analyst where report_id='$row[report_id]' and rep_type='$row[rep_type]' and type='2' ");
                                        $check2= mysql_fetch_array($query_check_2);
                                        if($check2["completed_on"] != '') {
                                          echo 'Pending';
                                         }
                                        else {echo 'Contingent';  $flag_check=1;}

                                     }
                                    else {echo 'Contingent';  $flag_check=1;}
                                  }
                                 ?></td>
                                 <td>
                                 <?php 
                                  if($flag_check == 0){
                                    if($row["type"] != 1){
                                      ?>
                                        <a href="javascript:void(0)"  class="btn yellow" data-toggle="modal" onclick="send_back(<?php echo $row["id"]?>);">Send Back</a>&nbsp;&nbsp;
                                      <?php
                                    }
                                  }



                                 if($row["type"] ==1 || $row["type"] == 2){
                                  if($flag_check == 0){
                                  ?>
                                  <a href="javascript:void(0)"  class="btn blue" data-toggle="modal"  id="mark_complete_<?php echo $row["id"]?>" onclick="mark_complete(<?php echo $row["id"]?>);">Mark Complete</a>&nbsp;&nbsp;

                                 <?php }}

                                 else {
                                  if($flag_check == 0){
                                     if($report->gen_report == '') {
                                    ?>
                                    
                                     <a role="button" href="#myModal"  class="btn blue" data-toggle="modal" onclick="upload_report('<?php echo $report->company_name?>','<?php echo $row["id"]?>','<?php echo $row["report_id"]?>','<?php echo $row["rep_type"]?>');" data-backdrop="static" data-keyboard="false">Upload Report</a>

                                     &nbsp;&nbsp;
                                      <?php 
                                    } else {
                                      ?>
                                         <a role="button" href="javascript:void(0)"  class="btn red"  onclick="remove_report('<?php echo $report->company_name?>','<?php echo $row["id"]?>','<?php echo $row["report_id"]?>','<?php echo $row["rep_type"]?>');">Remove Report</a>&nbsp;&nbsp;
                                     
                                      <?php 
                                    }
                                        if($row["rep_type"] == 1){//means proxy advisory where we can have custome reports

                                         
                                               $users = fetch_customized_users($report->company_id , $report->year);

                                               if(sizeof($users) > 0){
                                                  
                                              ?>
                                             <a href="#myModal" role="button" class="btn" data-toggle="modal"  onclick="load_custom(<?php echo $row["id"];?>,<?php echo $row["report_id"];?>,'<?php echo $report->company_name?>','<?php echo $report->meeting_date;?>',<?php echo $report->company_id ?>)" data-backdrop="static" data-keyboard="false">Custom Reports</a>
                                              <?php
                                              
                                            }

                                        } 

                                      ?>
                                     
                                    <?php
                                  }
                                 }

                                 ?>
                                 <a href="task.php?cat=<?php 
                                      switch ($row["rep_type"]) {
                                        case '1':
                                          echo '3';
                                          break;
                                        case '2':
                                          echo '4';
                                           break;

                                       case '3':
                                         echo '5';
                                           break;
                                        
                                      }

                                     ?>&amp;<?php 
                                      switch ($row["rep_type"]) {
                                        case '1':
                                          echo 'proxy';
                                          break;
                                        case '2':
                                          echo 'cgs';
                                           break;

                                       case '3':
                                         echo 'res';
                                           break;
                                        
                                      }
                                     ?>=<?php echo encrypt($row["report_id"]); ?>" target="_blank"  class="btn" >Edit</a>&nbsp;&nbsp;
                                  <a role="button" href="#myModal"  class="btn" data-toggle="modal" onclick="view_rep('<?php echo $report->company_name?>','<?php echo $row["id"]?>','<?php echo $row["report_id"]?>','<?php echo $row["rep_type"]?>');">Details</a>
                                 
                                 <a class="btn icn-only" id="refresh_<?php echo $row["id"];?>" onclick="refresh_tr(<?php echo $row["id"]?>)"><i class="icon-refresh"></i></a>
                                  </td>
                              </tr>
                           <?php $count++; } 

                          ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  
           
				
	<!-- Button to trigger modal -->


<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body" id="modal-body">
    
  </div>
  <div class="modal-footer">

    <button class="btn" data-dismiss="modal" aria-hidden="true" onclick="check_complete()">Close</button>
  </div>
</div>			


</div>

<script type="text/javascript">

var current_id;

//used
function mark_complete(report_id){

   var file = 'mark_complete';
   $("#mark_complete_"+report_id).html('Marking..').removeAttr('onclick');
   $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
    if(data == 'success'){
      $("#tr_"+report_id).hide('slow', function(){ $("#tr_"+report_id).remove(); });
    }
      
   }); 

}
function refresh_tr(report_id){
    var c_class = $("#tr_"+report_id).attr('class');
    $("#tr_"+report_id).removeClass(c_class);
    $("#tr_"+report_id).animate({backgroundColor:'#00ff00'},{duration:500});
   var file = 'refresh_pending';
   $("#refresh_"+report_id).hide();
   $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
     $("#tr_"+report_id).addClass(c_class,{duration:500});
    $("#tr_"+report_id).html(data);     
   }); 

}

function upload_report(company_name, id, report_id, report_type){
   $("#myModalLabel").text(company_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'upload_report_ui';
   $.post("ajax/"+ file +".php", {id:id, report_id:report_id, report_type:report_type}, function(data) {
    $("#modal-body").html(data);
    current_id = id;
     //$("#tr_"+id).hide("slow"); 
   }); 

}

function view_rep(company_name, id, report_id, report_type){
   $("#myModalLabel").text(company_name); 
   $("#modal-body").html("<p>Loading...</p>");
    var file = 'view_report';
   $.post("ajax/"+ file +".php", {report_id:report_id, report_type:report_type}, function(data) {
    $("#modal-body").html(data);
    current_id = 0;
   }); 
  

}

function check_complete(){

  if(current_id != 0){
   var c_class = $("#tr_"+current_id).attr('class');
    $("#tr_"+current_id).removeClass(c_class);
    $("#tr_"+current_id).animate({backgroundColor:'#00ff00'},{duration:500});
    var file = 'check_complete';
     $.post("ajax/"+ file +".php", {id:current_id}, function(data) {
       if(data == 'success'){
        $("#tr_"+current_id).hide('slow', function(){ $("#tr_"+current_id).remove(); });
       } else {

        refresh_tr(current_id);
         $("#tr_"+ current_id).addClass(c_class,{duration:500});
       }
    });
  }
}

function load_custom(report_analyst_id, report_id, company_name, meeting_date, company_id){
  current_id = report_analyst_id;
   $("#myModalLabel").text("Custom Reports: "+company_name+" on "+meeting_date);
   $("#modal-body").css('min-height','405px');
   $("#modal-body").html('<iframe src="task/custom_reports.php?com_id='+company_id+'&id=' +report_id+ '" style="border:0; width:100%; height:400px;"></iframe>');
}

function send_back(report_analyst_id){
   bootbox.confirm("Are you sure?", function(result) {
          if(result) {
             var file = 'send_back';
             $.post("ajax/"+ file +".php", {id:report_analyst_id}, function(data) {
              if(data == 'success'){
                refresh_tr(report_analyst_id);
              } else{
                alert(data);
              }
                
             }); 
          }
          else {
          
          }
        });

}
function remove_report(company_name, id, report_id, report_type){
   bootbox.confirm("Are you sure?", function(result) {
          if(result) {
             var file = 'remove_report';
             $.post("ajax/"+ file +".php", {id:id, rep_id:report_id, rep_type:report_type}, function(data) {
              if(data == 'success'){
                refresh_tr(id);
              }
                
             }); 
          }
          else {
          
          }
        });

}

function check_complete(){

  if(current_id != 0){
   var c_class = $("#tr_"+current_id).attr('class');
    $("#tr_"+current_id).removeClass(c_class);
    $("#tr_"+current_id).animate({backgroundColor:'#00ff00'},{duration:500});
    var file = 'check_complete';
     $.post("ajax/"+ file +".php", {id:current_id}, function(data) {
       if(data == 'success'){
        $("#tr_"+current_id).hide('slow', function(){ $("#tr_"+current_id).remove(); });
       } else {

        refresh_tr(current_id);
         $("#tr_"+ current_id).addClass(c_class,{duration:500});
       }
    });
  }
}

</script>