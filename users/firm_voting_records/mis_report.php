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
      MIS/Audit Reports
      <small></small>
    </h3>
    </div>
    <div class="span6">

    </div>
    
  </div>
   <?php 
      
   ?>

            <div class="portlet box blue">
                     <div class="portlet-title">
                        <h4><i class="icon-reorder"></i> Generate SEBI Compliance MIS Report</h4>
                         <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                     </div>
                     <div class="portlet-body" style="overflow-x:auto">
                      <form action="../excel/generate_mis.php" target="blank" class="horizontal-form" method="post">
                        <div class="row-fluid">
                           <div class="span3 ">
                              <div class="control-group">
                                 <label class="control-label">Select User</label>
                                 <div class="controls">
                                    <select class="m-wrap span12" name="user_id" id="user_id">
                                      <option value="0">Firm Wide</option>
                                      <?php
                                      $sql = mysql_query("SELECT id,name,user_admin_name from users where created_by_prim = '$_SESSION[MEM_ID]' OR id='$_SESSION[MEM_ID]' order by id asc");
                                      while ($row = mysql_fetch_array($sql)) {

                                        echo '<option value="'.$row["id"].'">';
                                        echo ($row["id"] == $_SESSION["MEM_ID"])?$row["user_admin_name"]:$row["name"];
                                        echo '</option>';
                                      }
                                      ?>
                                    </select>                                   
                                 </div>
                              </div>
                           </div>
                           <!--/span-->
                           <div class="span3 ">
                              <div class="control-group">
                                 <label class="control-label">Date From</label>
                                 <div class="controls">
                                    <input type="text" id="date_from" name="date_from" class="m-wrap span12 datepicker_month">                                   
                                 </div>
                              </div>
                           </div>

                           <div class="span3 ">
                              <div class="control-group">
                                 <label class="control-label">Date To</label>
                                 <div class="controls">
                                    <input type="text" id="date_to" name="date_to" class="m-wrap span12 datepicker_month">                                   
                                 </div>
                              </div>
                           </div>
                           <!--/span-->

                            <div class="span3 ">
                              <div class="control-group">
                                 <label class="control-label">Select Type</label>
                                 <div class="controls">
                                    <select class="m-wrap span12" name="type_id" id="type_id">
                                     <option value="0">Individual Quarter</option>
                                     <option value="1">Financial Year</option>
                                    </select>                                   
                                 </div>
                              </div>
                           </div>

                        </div>

                        <div class="row-fluid">
                           <div class="span4 ">
                              <button class="btn blue">Generate</button>
                           </div>
                        </div>


                                    
                     </form>
                     </div>
                  </div>
                  
            <div class="portlet box blue">
                     <div class="portlet-title">
                        <h4><i class="icon-reorder"></i> Generate Internal MIS Report</h4>
                         <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                     </div>
                     <div class="portlet-body" style="overflow-x:auto">
                      <form action="../excel/generate_internal_mis.php" target="blank" class="horizontal-form" method="post">
                        <div class="row-fluid">
                           <div class="span3 ">
                              <div class="control-group">
                                 <label class="control-label">Select User</label>
                                 <div class="controls">
                                    <select class="m-wrap span12" name="user_id" id="user_id">
                                      <option value="0">Firm Wide</option>
                                      <?php
                                      $sql = mysql_query("SELECT id,name,user_admin_name from users where created_by_prim = '$_SESSION[MEM_ID]' OR id='$_SESSION[MEM_ID]' order by id asc");
                                      while ($row = mysql_fetch_array($sql)) {

                                        echo '<option value="'.$row["id"].'">';
                                        echo ($row["id"] == $_SESSION["MEM_ID"])?$row["user_admin_name"]:$row["name"];
                                        echo '</option>';
                                      }
                                      ?>
                                    </select>                                   
                                 </div>
                              </div>
                           </div>
                           <!--/span-->
                           <div class="span3 ">
                              <div class="control-group">
                                 <label class="control-label">Date From</label>
                                 <div class="controls">
                                    <input type="text" id="date_from" name="date_from" class="m-wrap span12 datepicker_month">                                   
                                 </div>
                              </div>
                           </div>

                           <div class="span3 ">
                              <div class="control-group">
                                 <label class="control-label">Date To</label>
                                 <div class="controls">
                                    <input type="text" id="date_to" name="date_to" class="m-wrap span12 datepicker_month">                                   
                                 </div>
                              </div>
                           </div>
                           <!--/span-->

                            <div class="span3 ">
                             
                           </div>

                        </div>

                        <div class="row-fluid">
                           <div class="span4 ">
                              <button class="btn blue">Generate</button>
                           </div>
                        </div>


                                    
                     </form>
                     </div>
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
  $.post("ajax/"+ file +".php", {count:count, id:report_id, special:2}, function(data) {
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

function subscribe(report_id, com_id, report_type){
  var file = "sub_request";
  $.post("ajax/"+ file +".php", {report_id:report_id, com_id:com_id, report_type: report_type}, function(data) {
        bootbox.alert(data);
        $("#sub_"+report_id).html("Subscription requested");
   }); 
}

</script>