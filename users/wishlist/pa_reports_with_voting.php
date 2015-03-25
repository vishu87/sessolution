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
      Proxy Advisory Reports
      <small></small>
    </h3>
    </div>
    <div class="span6">
      <div class="btn-group" style="padding-top:20px; float:right">
        <a href="wishlist.php?cat=2" class="btn blue <?php echo ($type ==0)?'active':''; ?>">Upcoming</a>
        <!--<a href="wishlist.php?cat=2&amp;type=1" class="btn blue <?php echo ($type ==1)?'active':''; ?>">Subscribed</a>-->
        <a href="wishlist.php?cat=2&amp;type=2" class="btn blue <?php echo ($type ==2)?'active':''; ?>">All History</a>
      </div>
    </div>
    
  </div>
   <?php 
      
   ?>

            <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-globe"></i>List</h4>
                     </div>
                     <div class="portlet-body">
                       <table class="table table-stripped tablesorter">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Company Name</th>
                                 <th>Meeting Date</th>
                                 <th>Type</th>

                                 <th>Report</th>
                                 <!--
                                 <th>Record Vote</th>
                                 <th>Action</th>
                                 <th>Proxy Status</th>

                               -->
                                 <th>Details</th>
                              </tr>
                              
                           </thead>
                           <tfoot>
                              <tr>
                                <th>#</th>
                                 <th>Company Name</th>
                                 <th>Meeting Date</th>
                                 <th>Type</th>
                                 <th>Report</th>
                                  <th>Record Vote</th>
                                 <th>Action</th>
                                 <th>Proxy Status</th>
                                 <th>Details</th>
                                
                              </tr>
                              <tr>
                                <th colspan="9" class="ts-pager form-horizontal">
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

                            $member->wishlist($user_id,$yr);
                            $member->pa_total_comapnies_year($yr);
                            $member->pa_subscribed_comapnies_year($user_id,$yr);

                            sizeof($member->wishlist);
                            
                            if(sizeof($member->wishlist) > 0){

                            $today = strtotime("today");

                            $query = "SELECT id from proxy_ad where com_id IN (".$member->wishlist_string.") and year='$yr' ";

                            if($type == 0) $query .= " and meeting_date >= '$today' ";

                            $query .= " order by meeting_date desc";
                            
                            
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
                                  <td><?php echo $pa_report->meeting_type;?></td>
                                 <td>
                                  <?php 
                                  if(in_array($pa_report->company_id, $member->companies_total_year)){
                                  if($pa_report->subscribed($member->companies_subscribed_year)) {
                                   ?>
                                 <?php 
                                   echo $pa_report->report($_SESSION["MEM_ID"],$member->customized); 
                                  } else {
                                   echo '<form action="payment.php" method="post" ><input type="hidden" name="rep_type" value="1"><input type="hidden" name="rep_id" value="'.$pa_report->id.'"><button type="submit" role="button" class="btn green">Subscribe</button></form>';
                                  }
                                } else {
                                  echo 'Not in Coverage';
                                }
                                   ?>

                                  </td>
                                 <td>
                                  <?php 

                                  if($pa_report->subscribed($member->companies_subscribed_year)) {
                                   ?>
                                 <?php 
                                    echo $pa_report->ses_voting($_SESSION["MEM_ID"]);
                                  } else {
                                    echo $pa_report->self_voting($_SESSION["MEM_ID"]);
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

                               <td><a  href="#myModal" role="button" class="btn blue" data-toggle="modal" onclick="view_report('<?php echo $pa_report->company_name; ?>', <?php echo $pa_report->id;?>)" >Details</a></td>
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
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:960px; margin-left:-480px">
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
    <h3>Stack Two</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
    <p>One fine body…</p>
    <input type="text" data-tabindex="1">
    <input type="text" data-tabindex="2">
    <button class="btn" data-toggle="modal" href="#stack3">Launch modal</button>
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn" id="close_button2">Close</button>
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
var count_row = 0;

function check_add_vote(report_id){
 if($("#resolution_number").val()){
  if($("#resolution_name").val()){
    var file = 'check_resolution_number';
    $.post("ajax/"+ file +".php", {id:report_id, res_number:$("#resolution_number").val() }, function(data) {
          if(data == 'success'){
             var file = 'add_vote';
             $("#ses_voting_button").html("Adding");
             $.post("ajax/"+ file +".php", {id:report_id, res_name:$("#resolution_name").val(),res_number:$("#resolution_number").val()}, function(data) {
                  $("#resolution_name").val('');
                  $("#resolution_number").val('');
                  $("#ses_voting_button").html("Add Vote");
                  refresh_self_voting(report_id);
           });
          } else {
            alert('Duplicate Resolution Number');
          }
       }); 
     

  } else {
    alert('Please Add Resolution Name');
    $("#resolution_name").focus();
  }
}
  else {
     alert('Please Add Resolution Number');
     $("#resolution_number").focus();
  }

}

function refresh_self_voting(report_id){
   var file = 'refresh_self_voting';
   $("#myform").html("Loading..");
   $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
        $('#myform').html(data);
 });
}

//used
function view_vote(company_name,proxy_id){
       $("#myModalLabel").text(company_name); 
       $("#modal-body").html("<p>Loading...</p>");
        var file = 'add_vote_user';
        $("#vote_s").html('<i class="icon-ok"></i>Processing');
         $.post("ajax/"+ file +".php", {id:proxy_id}, function(data) {
             $("#modal-body").html(data);
       }); 

      
}

function view_self_vote(company_name,proxy_id){
      
   $("#stack1 .modal-header h3").text(company_name); 
   $("#stack1 .modal-body").html("<p>Loading...</p>");
   var file = 'ses_voting_ui';
   $.post("ajax/"+ file +".php", {id:proxy_id}, function(data) {
      $("#stack1 .modal-body").html(data);
   }); 

}


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
  $.post("ajax/"+ file +".php", {count:count, id:report_id}, function(data) {
      $("#tr_"+report_id).html(data);
     $("#close_button").removeAttr('onclick');
     $("#tr_"+report_id).animate({backgroundColor:''},{duration:500});
      $("#tr_"+ report_id).addClass(c_class,{duration:500});

   });
}
function assign_voter(company_name,report_id){
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
</script>