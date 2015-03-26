<?php 
if(!isset($title)) {
      die('This page can not be viewed');
   }
?>
<div class="container-fluid">
   <!-- BEGIN PAGE HEADER-->
   <div class="row-fluid">
    <div class="span6">
      <h3 class="page-title">
      Execute Vote
      <small></small>
    </h3>
    </div>
    <div class="span6">

    </div>
    
  </div>
   <?php 
      
   ?>

            <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-reorder"></i> List of Upcoming Meetings</h4>
                         <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                     </div>
                     <div class="portlet-body" style="overflow-x:auto">
                       <table class="table table-stripped tablesorter">
                           <thead>
                              <tr>
                                 <th style="width:10px;">SN</th>
                                 <th>Company Name</th>
                                 <th>Meeting Date</th>
                                 <th>e-Voting Deadline</th>
                                 <th>Type</th>
                                 <th>Report</th>
                                 <th>Record Vote</th>
                                 <th >Execute<br>Vote</th>
                                 <th>Proxy Status</th>
                                 <th>Analysts</th>
                                 <th>Details</th>
                              </tr>
                              
                           </thead>
                           <tfoot>
                              <tr>
                                <th>SN</th>
                                 <th>Company Name</th>
                                 <th>Meeting Date</th>
                                 <th>e-Voting Deadline</th>
                                 <th>Type</th>
                                 <th>Report</th>
                                  <th>Record Vote</th>
                                 <th>Execute Vote</th>
                                 <th>Proxy Status</th>
                                 <th>Analysts</th>
                                 <th>Details</th>
                                
                              </tr>
                              <tr>
                                <th colspan="10" class="ts-pager form-horizontal">
                                  <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
                                  <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
                                  <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                                  <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
                                  <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
                                  <select class="pagesize input-mini" title="Select page size">
                                    
                                    <option selected="selected" value="20">20</option>
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
                           

                           foreach ($years as $yr) {

                            $member->voting_records_firm($member->parent,1);
                            $member->pa_total_comapnies_year($yr);
                            $member->pa_subscribed_comapnies_year($user_id,$yr);

                                                       
                            if(sizeof($member->voting_records_firm) > 0){

                            $today = strtotime("today");
                           
                            $query = "SELECT id from proxy_ad where id IN (".$member->voting_records_firm_string.") and year='$yr' ";

                            $query .= " and meeting_date >= '$today' ";

                            $query .= " order by meeting_date asc";
                            
                            
                            $sql = mysql_query($query);

                            while($row = mysql_fetch_assoc($sql))
                            {
                              $pa_report = new PA($row["id"]);

                           ?>
                              <tr id="tr_<?php echo $row["id"];?>">
                                 <td><?php echo $count;?></td>
                                 <td>
                                 <?php echo $pa_report->company_name;;?>
                                </td>
                                 <td><?php echo $pa_report->meeting_date;?></td>
                                  <td><?php echo $pa_report->evoting_end;?></td>
                                  <td><?php echo $pa_report->meeting_type;?></td>
                                 <td id="sub_<?php echo $pa_report->id ?>">
                                  <?php 
                                  if(in_array($pa_report->company_id, $member->companies_total_year)){
                                  if($pa_report->subscribed($member->companies_subscribed_year)) {
                                   ?>
                                 <?php 
                                    if($pa_report->subscribed($member->companies_report_subscribed_year)) echo $pa_report->report($_SESSION["MEM_ID"],$member->customized);
                                        else echo $pa_report->subscription_request("Get Full Report");
                                  } else {
                                     echo $pa_report->subscription_request();
                                  }
                                } else {
                                     echo $pa_report->subscription_request();
                                }
                                   ?>

                                  </td>
                                 <td>
                                  <?php 

                                  if($pa_report->coverage($member->companies_total_year)) {
                                   ?>
                                 <?php 
                                       
                                   echo $pa_report->ses_voting($_SESSION["MEM_ID"],2);
                                      
                                  } else {
                                 
                                    echo $pa_report->self_voting($_SESSION["MEM_ID"],2);
                                 
                                  }
                               
                                   ?>
                                 <?php 
                                  
                                   ?>
                                  </td>
                                
                                 <td id="td_proxy_<?php echo $pa_report->id; ?>" >
                                  <?php
                                   $pa_report->request_proxy($_SESSION["MEM_ID"],$member->proxy_module);
                                   echo $pa_report->proxy_button;
                                  ?>
                                </td>

                               <td id="td_proxy_status_<?php echo $pa_report->id; ?>" style=" text-align:center"><?php echo $pa_report->proxy_status;?></td> 
                               <td><?php echo $pa_report->voting_record_users($member->parent);?></td>

                               <td><?php echo $pa_report->details();?></td>
                               <?php ?>

                              </tr>
                           <?php $count++; }//while
                            }//if
                           }//year
                           ?>
                           </tbody>
                        </table>
                     </div>
                     <?php
                      
                     ?>
                  </div>
                  
           
            
   <!-- Button to trigger modal -->


<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:90%; margin-left:-45%;">
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

<div id="stack1" class="modal hide fade" tabindex="-1" data-focus-on="input:first" style="width:94%; margin-left:-47%">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Stack One</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
    <p>One fine body…</p>
    <p>One fine body…</p>
    <input type="text" data-tabindex="1">
    <input type="text" data-tabindex="2">
    <button class="btn" data-toggle="modal" href="#stack2">Launch modal</button>
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn" id="close_button1">Close</button>
  </div>
</div>
 
<div id="stack2" class="modal hide fade" tabindex="-1" data-focus-on="input:first" style="width:90%; margin-left:-45%">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <button type="button" class="btn pull-right" style="margin-right:15px;" onclick="PrintElem('#stack2 .modal-body')">Print</button>
    <h3>Final Votes</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
    <p>One fine body…</p>
    <input type="text" data-tabindex="1">
    <input type="text" data-tabindex="2">
    <button class="btn" data-toggle="modal" href="#stack3">Launch modal</button>
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn blue" id="accept_freeze">Accept &amp; Freeze</button>
    <button type="button" data-dismiss="modal" class="btn" id="close_button2" onclick="cancel_freeze()">Cancel</button>
  </div>
</div>
 
<div id="stack3" class="modal hide fade" tabindex="-1" data-focus-on="input:first">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Stack Three</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
    <input type="text" data-tabindex="1">
    <input type="text" data-tabindex="2">
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn">Close</button>
    <button type="button" class="btn btn-primary">Ok</button>
  </div>




</div>

<script type="text/javascript">
var type_voting =2;

function view_report(company_name, proxy_id){
   $("#myModalLabel").text(company_name); 
       $("#modal-body").html("<p>Loading...</p>");
        var file = 'view_report';
         $.post("ajax/"+ file +".php", {report_id:proxy_id, report_type:'1'}, function(data) {
             $("#modal-body").html(data);
       });
}

function request_proxy(proxy_id){
 
   var file = 'request_proxy';
   $("#td_proxy_"+proxy_id).html('Processing');
         $.post("ajax/"+ file +".php", {report_id:proxy_id}, function(data) {
          if(data == 'success'){
            $("#td_proxy_"+proxy_id).html('');
            $("#td_proxy_status_"+proxy_id).html('Proxy Requested');
          } else {
            alert('Proxy already requested');
          }
             
       });
}

function upload_form(proxy_id, request_id, proxy_module){
    $("#modal-body").html("Loading..");
    count = parseInt($("#tr_"+proxy_id+" td:first").html());
    $("#close_button").attr('onclick','check_upload('+proxy_id+','+request_id+')');
   var file = 'upload_form_ui';
   $("#myModalLabel").text('Upload Proxy Form'); 
         $.post("ajax/"+ file +".php", {request_id:request_id, proxy_module:proxy_module}, function(data) {
          $("#modal-body").html(data); 
       });
}

function check_upload(proxy_id,request_id){
   refresh_tr(proxy_id);
}

function refresh_tr(report_id){
  var file = 'refresh_report';
  var c_class = $("#tr_"+report_id).attr('class');
  $("#tr_"+report_id).removeClass(c_class);
  $("#tr_"+report_id).animate({backgroundColor:'#ffff00'},{duration:500});
  $.post("ajax/"+ file +".php", {count:count, id:report_id, special:2, type:type_voting}, function(data) {
      $("#tr_"+report_id).html(data);
     $("#close_button").removeAttr('onclick');
     $("#tr_"+report_id).animate({backgroundColor:''},{duration:500});
      $("#tr_"+ report_id).addClass(c_class,{duration:500});

   });
}

function assign_voter(company_name,report_id){
    $("#modal-body").html("Loading..");
   count = parseInt($("#tr_"+report_id+" td:first").html());
   var file = 'assign_voter';
   $("#myModalLabel").text(company_name); 
     $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
      $("#modal-body").html(data); 
   });

}

function submit_voter(report_id){
    var file = 'submit_voter';
       $.post("ajax/"+ file +".php", {voter:$("#voter_names").val(), report_id:report_id}, function(data) {
        refresh_tr(report_id);
        if(data == 'success'){
          $("#modal-body").html('<div class="alert alert-success"><strong>Success!</strong> The voter has been assigned.</div>');
        } else if(data == 'success2') {
           $("#modal-body").html('<div class="alert alert-success"><strong>Success!</strong> The voter has been unassigned.</div>');
        } else {
          alert("Error");
        }

     });
   
}

function reset_proxy(report_id){
  count = parseInt($("#tr_"+report_id+" td:first").html());
  var file = "reset_proxy";
    bootbox.confirm("Are you sure to delete current proxy voting details and reset it?", function(result) {
      if(result) {
        $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
              if(data == 'success'){
                  refresh_tr(report_id);
                } else if(data == 'admincheck') {
                 bootbox.alert('Email has been sent to admin to reset your proxy details. After approval proxy request will be reset.<br> Please contact admin for further details.');
                 }    else {
                bootbox.alert('Deletion error');
              }   
         }); 
      }
    });     
}

</script>