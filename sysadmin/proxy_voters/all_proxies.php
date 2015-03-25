<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}

$meeting_types = array("");
$sql_met = mysql_query("SELECT type from met_type order by id asc");
while ($row_met = mysql_fetch_array($sql_met)) {
   array_push($meeting_types, $row_met["type"]);
 } 

 $voters = array();
$sql_met = mysql_query("SELECT vid,name from proxy_voters ");
while ($row_met = mysql_fetch_array($sql_met)) {
   $voters[$row_met["vid"]] = $row_met["name"];
 } 

 ?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
    <div class="span6">
      <h3 class="page-title">
      Pending Proxy Voting
      <small></small>
    </h3>
    </div>
    <div class="span6">
     
    </div>
		
	</div>
  <style type="text/css">
  .burn_purple{
    background: #f0f !important;
  }
  .burn_red{
    background: #ff5C33 !important;
  }
  .burn_yellow{
    background: #ff0 !important;
  }
  .burn_green{
    background: #0f0 !important;
  }

  </style>

            <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-globe"></i>Pending List</h4>
                     </div>
                     <?php
                    

                     ?>
                     <div class="portlet-body">
                       <table class="table table-stripped tablesorter">
                           <thead>
                              <tr>
                                 <th>Company Name</th>
                                 <th>Meeting Date</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Type</th>
                                 <th>User Name</th>
                                
                                 <th>Proxy Voter</th>
                                 <th>Status</th>
                                 <th>Details</th>
                              </tr>
                              
                           </thead>
                           <tfoot>
                              <tr>
                                 <th>Company Name</th>
                                 <th>Meeting Date</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Type</th>
                                 <th>User Name</th>
                                
                                 <th>Proxy Voter</th>
                                 <th>Status</th>
                                 <th>Details</th>
                              </tr>
                              <tr>
                                <th colspan="7" class="ts-pager form-horizontal">
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
                           
                           $count = 0;
                            $sql = mysql_query("SELECT proxies.* ,users.name from proxies inner join users on proxies.user_id = users.id where proxies.final_date ='' order by proxies.add_date asc");
                           while($row = mysql_fetch_array($sql))
                           {

                            $sql_com = mysql_query("SELECT proxy_ad.meeting_date, proxy_ad.meeting_type,companies.com_name from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id='$row[proxy_id]' ");
                            $row_com = mysql_fetch_array($sql_com);
                           ?>
                              <tr id="tr_<?php echo $row["id"];?>">
                                 
                                 <td><?php echo stripcslashes($row_com["com_name"]);  $row_com["com_name"] = name_filter($row_com["com_name"]);?></td>
                                 <td><?php echo date("d M Y",$row_com["meeting_date"]);?></td>
                                 <td><?php echo $meeting_types[$row_com["meeting_type"]];?></td>
                                 <td><?php echo $row["name"];?></td>
                                 <?php
                                   
                                    
                                    if ($row["voter_id"] == 0) {
                                       $status = 'Proxy Requested';
                                      $button = '';
                                    }  elseif ($row["form"] == '') {
                                       $status = 'Proxy Assigned';
                                       $button = '';
                                    } else {
                                       $status = 'Proxy Form Recieved';
                                       $button = '<a href="#myModal" role="button" class="btn yellow" data-toggle="modal" onclick="upload_slip('.$row["id"].')">Upload Slip</a>';
                                    }
                                   
                                  ?>

                                 <td><a  href="#myModal" id="voter_<?php echo $row["id"];?>" role="button" class="btn <?php echo ($row["voter_id"] != 0)?'green':'';?>" data-toggle="modal" onclick="assign_voter('<?php echo stripcslashes($row_com["com_name"]); ?>', <?php echo $row["id"];?>,'<?php echo $row["name"]; ?>')"><?php echo ($row["voter_id"] != 0)?$voters[$row["voter_id"]]:'Assign';?></a>
                                  </td>
                                
                                  <td id="td_proxy_status_<?php echo $row["id"]?>"><?php echo $status;?></td>
                                 <td>
                                  <?php echo $button;?> 
                                  <a  href="#myModal" role="button" class="btn " data-toggle="modal" onclick="view_report('<?php echo stripcslashes($row_com["com_name"]); ?>', <?php echo $row["proxy_id"];?>)">Details</a> 
                                  <a  href="#myModal" role="button" class="btn " data-toggle="modal" onclick="view_voting('<?php echo stripcslashes($row_com["com_name"]); ?>', <?php echo $row["id"];?>)">Proxy Details</a> 
                                  <a  href="javascript:;" role="button" class="btn black" onclick="delete_request(<?php echo $row["id"];?>)">Delete Request</a>
                                  </td>
                              </tr>

                           <?php $count++; } 

                          
                            ?>                                    

                           </tbody>
                        </table>
                     </div>
                  </div>
                  
              
           <div class="row-fluid">
            <div class="span6">
              <form method="post" target="_blank" action="../excel/list_request.php">
               
                <button type="submit" class="btn" style=""><i class="icon-share"></i> Export</button>
      
              </form>
            </div>
           </div>     
				
	<!-- Button to trigger modal -->


<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:90%; margin-left:-45%">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body" id="modal-body">
    
  </div>
  <div class="modal-footer">

    <button class="btn" data-dismiss="modal" aria-hidden="true" id="close_button">Close</button>
  </div>
</div>			


</div>

<script type="text/javascript">
function view_report(company_name, proxy_id){
  $("#myModalLabel").text(company_name); 
   $("#modal-body").html("<p>Loading...</p>");
    var file = 'view_report';
     $.post("ajax/"+ file +".php", {report_id:proxy_id, report_type:'1'}, function(data) {
         $("#modal-body").html(data);
   });
}

function view_voting(company_name, request_id){
  $("#myModalLabel").text(company_name); 
   $("#modal-body").html("<p>Loading...</p>");
    var file = 'view_voting';
     $.post("ajax/"+ file +".php", {request_id:request_id}, function(data) {
         $("#modal-body").html(data);
   });
}

function assign_voter(company_name, request_id, user_name){
  $("#myModalLabel").text(company_name+': '+user_name); 
   $("#modal-body").html("<p>Loading...</p>");
    var file = 'assign_voter';
     $.post("ajax/"+ file +".php", {request_id:request_id}, function(data) {
         $("#modal-body").html(data);
   });
}

function fetch_voters(){

    var file = 'fetch_voters';
     $.post("ajax/"+ file +".php", {location:$("#location").val()}, function(data) {
         $("#voter_names").html(data);
   });
}
function submit_voter(request_id){

    var file = 'submit_voter';
    if($("#voter_names").val()){
       $.post("ajax/"+ file +".php", {voter:$("#voter_names").val(), request_id:request_id}, function(data) {

            $("#modal-body").html('<div class="alert alert-success"><strong>Success!</strong> The voter has been assigned.</div>');
           $("#voter_"+request_id).addClass('green');
           $("#voter_"+request_id).html(data);
     });
   } else {
    alert('Please select a voter');
   }
}

function unassign_voter(request_id){

    var file = 'unassign_voter';
 
       $.post("ajax/"+ file +".php", {request_id:request_id}, function(data) {
           $("#modal-body").html(data);
           $("#voter_"+request_id).removeClass('green');
           $("#voter_"+request_id).html('Assign');
     });
  
}

function upload_slip(request_id){
 
    $("#close_button").attr('onclick','check_upload('+request_id+')');
   var file = 'upload_slip_ui';
   $("#myModalLabel").text('Upload Slip'); 
         $.post("ajax/"+ file +".php", {request_id:request_id}, function(data) {
          $("#modal-body").html(data); 
       });
}
function check_upload(request_id){
  var file = 'check_upload_slip'; 

   var c_class = $("#tr_"+request_id).attr('class');
    $("#tr_"+request_id).removeClass(c_class);
    $("#tr_"+request_id).animate({backgroundColor:'#ffff00'},{duration:500});
     $.post("ajax/"+ file +".php", {request_id:request_id}, function(data) {
      $("#tr_"+request_id).animate({backgroundColor:''},{duration:500});
      $("#tr_"+ request_id).addClass(c_class,{duration:500});
       $("#close_button").removeAttr('onclick');
      if(data == 'success'){
        // $("#td_proxy_"+request_id).html('');
         $("#td_proxy_status_"+request_id).html('Completed');
          $("#tr_"+request_id).hide('slow', function(){ $("#tr_"+request_id).remove(); });
      }
      
   });
}
function delete_request(request_id){
  var file = "delete_request";
    bootbox.confirm("Are you sure to delete current proxy voting details and let user to reset it?", function(result) {
      if(result) {
        $.post("ajax/"+ file +".php", {id:request_id}, function(data) {
              if(data == 'success'){
                  $("#tr_"+request_id).hide("slow", function(){
                      $("#tr_"+request_id).remove();
                  })
                } else {
                bootbox.alert('Deletion error');
              }   
         }); 
      }
    });     
}

</script>