<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<style type="text/css">
   .meet{
      margin:0 5px;
      text-align: center;
      padding: 2px 3px;
      color:#fff;
      cursor: pointer;
      margin-bottom: 1px;

   }
   .meet1, .meet1_big{
     background: #6576C2;
   }
   .meet2, .meet2_big{
     background: #76AF73;
   }
   .meet3, .meet3_big{
     background: #8E698B;
   }
   .meet4, .meet4_big{
     background: #CC3300;
   }
   .meet1:hover, .meet1_big:hover{
     background: #002AFF;
   }
   .meet2:hover, .meet2_big:hover{
     background: #76AF33;
   }
   .meet3:hover, .meet3_big:hover{
     background: #AA2AFF;
   }
   .meet4:hover, .meet4_big:hover{
     background: #FF3300;
   }
   .big2{
    /*padding-top:2px;
    padding-bottom:2px;*/
    border:2px solid #444;
    margin-top:-2px;
   }
</style>
<?php
    if(isset($_GET["type"])){
      $type = decrypt($_GET["type"]);
      if($type < 0 || $type > 4) die("You are not playing well");
      else {
        echo '<style type="text/css">';
         switch ($type) {
          case '1':
            echo '.meet2,.meet3,.meet4{display:none;}';
            break;
          
           case '2':
            echo '.meet1,.meet3,.meet4{display:none;}';
            break;
             case '3':
            echo '.meet2,.meet1,.meet4{display:none;}';
            break;
             case '4':
            echo '.meet2,.meet3,.meet1{display:none;}';
            break;
        }
        echo '</style>';
      }
     
    } else {
      $type = 0;
    }


    if(isset($_GET["com_type"])){
      $com_type = decrypt($_GET["com_type"]);
      if($com_type < 5 || $com_type > 7) die("You are not playing well");  
    } else {
      $com_type = 6;
    }

   if($_GET["mon"] && $_GET["yr"]){
      $year = $_GET["yr"];
      $month = $_GET["mon"];
      if(strlen($month) == 1){
         $month = '0'.$month;
      }
     
   } else {
       $today = strtotime("today");
      $month = date('m', $today);
      $year = date("Y",$today);
   }
  
   $firstday = date("w", strtotime("01-".$month."-".$year));
   $month_n = date("n", strtotime("01-".$month."-".$year));

   $year_pre = $year;
   $year_next = $year;

   $check_timestamp = strtotime("01-04-".$year);

   if(strtotime("01-".$month."-".$year) >= $check_timestamp){
      $year_rep = $year;
   } else {
      $year_rep =  ($year -1);
   }

   $month_pre = $month_n -1;
   if($month_pre == 0){
      $month_pre = 12;
      $year_pre = $year - 1;
   }
   

   $month_next = $month_n + 1;
   if($month_next == 13){
      $month_next = 1;
      $year_next = $year + 1;
   }
   
?>
<div class="container-fluid">
   <div class="row-fluid">
    <div class="span6"><h3 class="page-title">
      Calender View 
      <small></small>
    </h3></div>
    <div class="span6" style="text-align:right;margin-top:20px">
      
    </div>
  </div>

   <div class="row-fluid" style="margin-bottom:20px;">
    <div class="span4"> 
      <a class="btn <?php echo (!isset($type) || $type == 0)?'big2':''; ?>" style="" href="?mon=<?php echo $month;?>&amp;yr=<?php echo $year;?>&amp;com_type=<?php echo encrypt($com_type); ?>">All</a>
      <a class="btn meet1_big <?php echo ($type == 1)?'big2':''; ?>" style="color:#fff;" href="?mon=<?php echo $month;?>&amp;yr=<?php echo $year; ?>&amp;type=<?php echo encrypt('1') ?>&amp;com_type=<?php echo encrypt($com_type); ?>">AGM</a>
      <a class="btn meet2_big <?php echo ($type == 2)?'big2':''; ?>" style="color:#fff;" href="?mon=<?php echo $month;?>&amp;yr=<?php echo $year; ?>&amp;type=<?php echo encrypt('2') ?>&amp;com_type=<?php echo encrypt($com_type); ?>">EGM</a>
      <a class="btn meet3_big <?php echo ($type == 3)?'big2':''; ?>" style="color:#fff;" href="?mon=<?php echo $month;?>&amp;yr=<?php echo $year; ?>&amp;type=<?php echo encrypt('3') ?>&amp;com_type=<?php echo encrypt($com_type); ?>">PB</a>
      <a class="btn meet4_big <?php echo ($type == 4)?'big2':''; ?>" style="color:#fff;" href="?mon=<?php echo $month;?>&amp;yr=<?php echo $year; ?>&amp;type=<?php echo encrypt('4') ?>&amp;com_type=<?php echo encrypt($com_type); ?>">CCM</a>

    </div>
        <div class="span4" align="center"> 
          <div class="btn-group">
            
            <a class="btn blue <?php echo ($com_type == 6)?'active':''; ?>" style="color:#fff;" href="?mon=<?php echo $month;?>&amp;yr=<?php echo $year; ?>&amp;type=<?php echo encrypt($type) ?>&amp;com_type=<?php echo encrypt('6') ?>">Subscribed</a>
            <a class="btn blue <?php echo ($com_type == 7)?'active':''; ?>" style="color:#fff;" href="?mon=<?php echo $month;?>&amp;yr=<?php echo $year; ?>&amp;type=<?php echo encrypt($type) ?>&amp;com_type=<?php echo encrypt('7') ?>">My Portfolio</a>
            <a class="btn blue <?php echo ($com_type == 5)?'active':''; ?>" style="color:#fff;" href="?mon=<?php echo $month;?>&amp;yr=<?php echo $year; ?>&amp;type=<?php echo encrypt($type) ?>&amp;com_type=<?php echo encrypt('5') ?>">SES Coverage</a>
          </div>
      

    </div>
    <div class="span4" align="right">
      <a href="?mon=<?php echo $month_pre ?>&amp;yr=<?php echo $year_pre?>&amp;type=<?php echo encrypt($type); ?>&amp;com_type=<?php echo encrypt($com_type); ?>" class="btn" style="" ><i class="m-icon-swapleft"></i> Pre</a>
      <a href="#" class="btn blue" style="" ><?php echo date("M, Y",strtotime("01-".$month."-".$year))?></a>
       <a href="?mon=<?php echo $month_next ?>&amp;yr=<?php echo $year_next?>&amp;type=<?php echo encrypt($type); ?>&amp;com_type=<?php echo encrypt($com_type); ?>" class="btn" style="" >Next <i class="m-icon-swapright"></i></a>
    </div>
  </div>

	<div class="row-fluid">
      <table class="" style="width:100%" cellpadding="0" cellspacing="0">
         <thead>
            <th style="width:14%; background:#eee; border:1px solid #ccc;">Sun</th>
            <th style="width:14%; background:#eee; border:1px solid #ccc;">Mon</th>
            <th style="width:14%; background:#eee; border:1px solid #ccc;">Tue</th>
            <th style="width:14%; background:#eee; border:1px solid #ccc;">Wed</th>
            <th style="width:14%; background:#eee; border:1px solid #ccc;">Thu</th>
            <th style="width:14%; background:#eee; border:1px solid #ccc;">Fri</th>
            <th style="width:14%; background:#eee; border:1px solid #ccc;">Sat</th>
            <th></th>
         </thead>
         <tbody>
            <tr>
               <?php
                  $user_id = $_SESSION["MEM_ID"];

                  switch ($com_type) {
                    case 5:
                       $member->pa_total_comapnies_year($year_rep);
                        $total_comp_subscribed = $member->companies_total_year;
                      break;
                    case 6:
                       $member->pa_subscribed_comapnies_year($user_id, $year_rep);
                        $total_comp_subscribed = $member->companies_subscribed_year;
                      break;
                    case 7:
                       $member->wishlist($user_id, $year_rep);
                        $total_comp_subscribed = $member->wishlist;
                      break;
                  }
                  
                  
                  $total_sub = sizeof($total_comp_subscribed);

                  $flag = 1;
                  $count = 0;
                  $count_day = 1;
                  for ($i=0; $i < $firstday ; $i++) { 
                     echo '<td><div style="min-height:130px; border:1px solid #eee"></div></td>';
                     $count++;
                  }
                  while ($flag == 1) {
                     $date = strtotime($count_day."-".$month."-".$year);   
                     
                    ?>
                    <td style="border:1px solid #4d90fe">
                        <div style="min-height:130px;">
                           <div style="margin:2px 0; text-align:right; font-style:italic; padding-right:5px;">
                              <?php echo $count_day;?>
                           </div>
                           <?php 
                              $proxy_ids = array();

                              if($total_sub != 0) {
                                 $str_comp = implode(',', $total_comp_subscribed);
                                 $query = mysql_query("SELECT proxy_ad.id from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id  where proxy_ad.com_id IN (".$str_comp.") and  ((proxy_ad.meeting_date = '$date' and proxy_ad.evoting_end = '') OR proxy_ad.evoting_end = '$date') order by proxy_ad.meeting_type asc ");
                                 while ($row = mysql_fetch_array($query)) {
                                    array_push($proxy_ids, $row["id"]);
                                 }
                              }
                              
                              $query = mysql_query("SELECT proxy_ad.id from proxy_ad inner join manual_subscription on proxy_ad.com_id = manual_subscription.report_id  where manual_subscription.report_type='1' and manual_subscription.user_id='$user_id' and  ((proxy_ad.meeting_date = '$date' and proxy_ad.evoting_end = '') OR proxy_ad.evoting_end = '$date') ");
                              while ($row = mysql_fetch_array($query)) {
                                 if(in_array($row["id"], $proxy_ids)){
                                    array_push($proxy_ids, $row["id"]);
                                 }
                              }

                              if(sizeof($proxy_ids) < 5 || ( isset($type) && $type != 0 )){
                                 foreach ($proxy_ids as $proxy_id) {
                                    $sql_p = mysql_query("SELECT companies.com_name, proxy_ad.meeting_type, proxy_ad.meeting_date from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id='$proxy_id' limit 1 ");
                                    $row_p = mysql_fetch_array($sql_p);
                                    $com_name_clr = name_filter($row_p["com_name"]);
                                    $tooltip_check = ($row_p["meeting_type"] == 3)?'':'ttip';
                                    echo '<div class="meet meet'.$row_p["meeting_type"].' '.$tooltip_check.'" data-html="true" data-toggle="modal" href="#stack1" onclick="view_report(\''.$com_name_clr.'\','.$proxy_id.',1);" data-toggle="tooltip" title="Meeting Date: '.date("d-M-y",$row_p["meeting_date"]).'">'.$row_p["com_name"].'</div>';
                                 }
                              } else {
                                 $str_proxy = implode(',', $proxy_ids);
                                 echo '<input type="hidden" value="'.$str_proxy.'" id="proxy_id_fetch_'.$count_day.'">';

                                 echo '<div data-toggle="modal" href="#myModalCompact" onclick="view_compact(\''.date("d-M-y", $date).'\','.$count_day.');">';

                                 $sql_p = mysql_query("SELECT id from proxy_ad  where meeting_type='1' and id IN (".$str_proxy.") ");
                                 if(mysql_num_rows($sql_p)>0) echo '<div class="meet meet1">'.mysql_num_rows($sql_p).' AGM</div>';

                                 $sql_p = mysql_query("SELECT id from proxy_ad  where meeting_type='2' and id IN (".$str_proxy.") ");
                                 if(mysql_num_rows($sql_p)>0) echo '<div class="meet meet2">'.mysql_num_rows($sql_p).' EGM</div>';

                                 $sql_p = mysql_query("SELECT id from proxy_ad  where meeting_type='3' and id IN (".$str_proxy.") ");
                                 if(mysql_num_rows($sql_p)>0) echo '<div class="meet meet3">'.mysql_num_rows($sql_p).' PB</div>';
                                  $sql_p = mysql_query("SELECT id from proxy_ad  where meeting_type='4' and id IN (".$str_proxy.") ");
                                 if(mysql_num_rows($sql_p)>0) echo '<div class="meet meet4">'.mysql_num_rows($sql_p).' CCM</div>';


                                 echo '</div>';

                              }
                             ?>

                        </div>
                     </td>
                    <?php
                     $count++;$count_day++;
                     if($count % 7 == 0) echo '<td></td></tr><tr>';
                     if($count_day >28) {
                        $flag = checkdate($month_n, $count_day, $year); 
                        if($flag == 0){
                           $count_day--;
                           $lastday = date("w", strtotime($count_day."-".$month."-".$year));
                        }
                     } 
                  }
                   for ($i=$lastday; $i < 6 ; $i++) { 
                     echo '<td><div style="min-height:130px; border:1px solid #eee"></div></td>';
                     $count++;
                  }
               ?>
            </tr>
         </tbody>
      </table>
   </div>      
</div><!-- END CONTAINER -->

<!-- Modal -->
<div id="myModalCompact" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:30%; margin-left:-15%">
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


<div id="stack1" class="modal hide fade" tabindex="-1" data-focus-on="input:first" style="width:90%; margin-left:-45%">
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
  </div>
</div>   

<script type="text/javascript">

function view_report(company_name, proxy_id, report_type){
   $("#stack1 .modal-header").html('<div class="row-fluid"><div class="span6"><h3>'+company_name+'</h3></div><div class="span6" style="text-align:right"><a href="javascript:;" role="button" class="btn blue" onclick="view_vote_upcoming(\''+company_name+'\',' + proxy_id +' )" >Vote</a>&nbsp;<a href="javascript:;" role="button" class="btn yellow" onclick="view_report(\''+company_name+'\',' + proxy_id +',1 )" >Details</a></div></div>'); 
      $("#stack1 .modal-body").html("<p>Loading...</p>");
        var file = 'view_report';
         $.post("ajax/"+ file +".php", {report_id:proxy_id, report_type:report_type}, function(data) {
             $("#stack1 .modal-body").html(data);
       });
}
//<a href="javascript:;" role="button" class="btn blue" onclick="view_request(\''+company_name+'\',' + proxy_id +' )" >Proxy Request</a>&nbsp;

function view_compact(date, day_id){
   $("#myModalLabel").text(date); 
      $("#modal-body").html("<p>Loading...</p>");
        var file = 'view_compact';
         $.post("ajax/"+ file +".php", {report_ids:$("#proxy_id_fetch_"+day_id).val()}, function(data) {
             $("#modal-body").html(data);
             $('.ttip').tooltip();
       });
}


function view_request(company_name,proxy_id){
       $("#stack1 .modal-body").html("<p>Loading...</p>");
        var file = 'proxy_request_ui';
         $.post("ajax/"+ file +".php", {report_id:proxy_id}, function(data) {
             $("#stack1 .modal-body").html(data);
       }); 
}

function request_proxy(proxy_id){
   var file = 'request_proxy';
   $("#stack1 .modal-body").html("<p>Loading...</p>");
         $.post("ajax/"+ file +".php", {report_id:proxy_id}, function(data) {
          if(data == 'success'){
             var file = 'proxy_request_ui';
             $.post("ajax/"+ file +".php", {report_id:proxy_id}, function(data) {
                 $("#stack1 .modal-body").html(data);
           }); 
          } else {
            alert('Proxy already requested');
          }
             
       });
}
function upload_form(proxy_id, request_id,proxy_module){
 
   var file = 'upload_form_ui';
   $("#stack1 .modal-body").text('Loading..'); 
         $.post("ajax/"+ file +".php", {request_id:request_id,proxy_module:proxy_module}, function(data) {
          $("#stack1 .modal-body").html(data); 
       });
}

function assign_voter(company_name,report_id){

   var file = 'assign_voter';
      $("#stack1 .modal-body").html("<p>Loading...</p>");
     $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
        $("#stack1 .modal-body").html(data);
   });

}

function submit_voter(report_id){
    var file = 'submit_voter';
       $.post("ajax/"+ file +".php", {voter:$("#voter_names").val(), report_id:report_id}, function(data) {
        if(data == 'success'){
          $("#stack1 .modal-body").html('<div class="alert alert-success"><strong>Success!</strong> The voter has been assigned.</div>');
        } else if(data == 'success2') {
           $("#stack1 .modal-body").html('<div class="alert alert-success"><strong>Success!</strong> The voter has been unassigned.</div>');
        } else {
          alert("Error");
        }

     });
   
}

function reset_proxy(report_id){
  var file = "reset_proxy";
    bootbox.confirm("Are you sure to delete current proxy voting details and reset it?", function(result) {
      if(result) {
        $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
              if(data == 'success'){
                  $("#stack1 .modal-body").html('<div class="alert alert-success"><strong>Success!</strong> Proxy request has been reset.</div>');
                } else if(data == 'admincheck') {
                 bootbox.alert('Email has been sent to admin to reset your proxy details. After approval proxy request will be reset.<br> Please contact admin for further details.');
                 }    else {
                bootbox.alert('Deletion error');
              }   
         }); 
      }
    });     
}

/*
function refresh_request(proxy_id){
  var file = 'proxy_request_ui';
   $.post("ajax/"+ file +".php", {report_id:proxy_id}, function(data) {
       $("#stack2 .modal-body").html(data);
});
}
*/
</script>