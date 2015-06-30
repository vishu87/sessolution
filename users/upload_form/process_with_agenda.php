<?php session_start();
require_once('../../auth.php');
require_once('../../config.php');
require_once('../../classes/UserClass.php');
require_once('../../classes/GeneralVoting.php');
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}
if( $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");
?>

<style type="text/css">
.alert {
padding: 8px 35px 8px 14px;
margin-bottom: 20px;
text-shadow: 0 1px 0 rgba(255,255,255,0.5);
background-color: #fcf8e3;
border: 1px solid #fbeed5;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
border-radius: 4px;
}
.alert-success {
color: #468847;
background-color: #dff0d8;
border-color: #d6e9c6;
}
</style>
<?php

$id= $_GET["id"];
$proxy_module = $_GET["proxy_module"];
$request_id = $id;

if($proxy_module == 1){
  $table = 'proxies';
   $sql = mysql_query("SELECT proxies.*, proxy_voters.email from proxies inner join proxy_voters on proxies.voter_id = proxy_voters.vid where proxies.id='$request_id' ");
} elseif ($proxy_module == 2){
  $table = 'self_proxies';
   $sql = mysql_query("SELECT self_proxies.*, self_proxy_voters.email from self_proxies inner join self_proxy_voters on self_proxies.voter_id = self_proxy_voters.vid where self_proxies.id='$request_id' ");
} else {
  header("Location: ".STRSITE."access-denied.php");
}
$res = mysql_fetch_array($sql);
$report_id = $res["proxy_id"];
$user_id = $res["user_id"];
$voter_email = $res["email"];

$filename = $_FILES["attachfile"]["name"]; 
$ar = explode('.', $filename);
$num = sizeof($ar);
$ext = $ar[$num-1];

if(!in_array($ext, $file_types)) die('Please input a valid file');

$strtime = strtotime("now");
$new_filename = substr(str_shuffle(strtotime("now")), 0, 10).$filename;
  if($filename != '') {
        move_uploaded_file($_FILES["attachfile"]["tmp_name"],"../../user_proxy_forms/".$new_filename);
        if(file_exists("../../user_proxy_forms/".$new_filename)){
        	$new_filename = mysql_real_escape_string($new_filename);
        	mysql_query("UPDATE $table set form ='$new_filename',  form_upload_date= '$strtime' where id='$id' and user_id='$_SESSION[MEM_ID]' ");
          
          mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type) values ('$_SESSION[MEM_ID]','5','$report_id','1')");
        }

    echo '<div class="alert alert-success"><strong>Success!</strong> Proxy form uploaded.</div>'; 
  } else {
    echo 'fail';
    die();
  }

$body = '';

  if($filename != ''){
            
            $recos = array();
            $sql_reco = mysql_query("SELECT * from ses_recos");
            while ($row_reco = mysql_fetch_array($sql_reco)) {
              $recos[$row_reco["id"]] = $row_reco["reco"];
            }

            $update = array();
            
            $body = '<table class="table table-bordered table-hover">';

                $ar_fields_name = array("Company","Meeting Details","Client Details","Proxy Appointed","Proxy Form");
                $ar_fields_type = array("Company","MetDet","ProxyRequest","ProxyAppointed","ProxyForm");
                
                $query_data = mysql_query("SELECT $table.*, companies.com_name, proxy_ad.* from $table inner join proxy_ad on $table.proxy_id = proxy_ad.id inner join companies on proxy_ad.com_id = companies.com_id where $table.id='$request_id' ");
                $data = mysql_fetch_array($query_data);
                $update["Company"] = $data["com_name"].' / '.$meeting_types[$data["meeting_type"]].' / '.date("d-M-Y",$data["meeting_date"]);
                $update["MetDet"] = $data["meeting_time"].' at '.$data["meeting_venue"];
                $client_sql = mysql_query("SELECT name from users where id='$user_id' ");
                $row_client = mysql_fetch_array($client_sql);
                $update["ProxyRequest"] = $row_client["name"];
                if(mysql_num_rows($query_data) == 0){

                } else {
                  $update["ProxyAppointed"] = 'Appointed on '.date("d-M-Y", $data["appoint_date"]);
                  $update["ProxyForm"] .= 'Attached with this email';
                }
                
              $count = 0;
              
              foreach ($ar_fields_type as $ar) {
                  $body .= "<tr><td>".$ar_fields_name[$count]."</td><td>".$update[$ar]."</td></tr>";
                $count++;
              }

            $body .= '</table>';


            $flag_self = 0;


             $sql_vote = mysql_query("SELECT * from voting where report_id='$report_id' order by priority, resolution_number asc");
             if(mysql_num_rows($sql_vote) > 0){
                $flag_self = 1;
                 $body .= '<h3>Agenda Items: SES</h3>';
                 $str = '<table class="table table-striped table-bordered table-advance table-hover"><tr><th>#</th><th>Resolution Name</th><th>Type</th><th>SES Recommendation</th><th>Recommendation</th></tr>';
                 $count =1;
                 while($row_vote = mysql_fetch_array($sql_vote)) {
                  $str .= '<tr id="tr_vote_'.$row_vote["id"].'"><td>'.$row_vote["resolution_number"].'</td>';
                   $str .= '<td>'.stripcslashes($row_vote["resolution_name"]).'</td>';
                  $sql_reso = mysql_query("Select * from resolutions where id='$row_vote[resolution_type]' ");
                     while ($row_reso = mysql_fetch_array($sql_reso)) {
                        $reso = $row_reso["resolution"];
                        $str .= '<td>'.$row_reso["resolution"].'</td>';
                     }
                     $str .= '<td>'.stripcslashes($recos[$row_vote["ses_reco"]]).'</td><td>'.$man_recos[$row_vote["man_reco"]].'</td>';
                     $str .= '</tr>';
                     $count++;
                 }
            $body .= $str.'</table>';
            $voting = new SesVoting();
            } else {
                $sql = "SELECT * from user_resolution where report_id='$report_id' and user_id='$user_id' order by resolution_number asc";

                $sql_vote = mysql_query($sql);
                if(mysql_num_rows($sql_vote) > 0){
                     $body .= '<h3>Agenda Items: Added by Client</h3>';
                     $str = '<table class="table table-striped table-bordered table-advance table-hover"><tr><th>#</th><th>Resolution Name</th><th>Recommendation</th></tr>';
                     $count =1;
                     while($row_vote = mysql_fetch_array($sql_vote)) {
                             $str .= '<tr id="tr_vote_'.$row_vote["id"].'"><td>'.$row_vote["resolution_number"].'</td>';
                             $str .= '<td>'.stripcslashes($row_vote["resolution_name"]).'</td>';
                             $str .= '<td>'.$man_recos[$row_vote["man_reco"]].'</td></td></tr>';
                             $count++;
                         }
                         $body .= $str.'</table>';
                    }
                    $voting = new SelfVoting();
            }
           // $body;
            ob_start();
            $voting->user_votes_final($report_id, $user_id); 
            $votes = ob_get_clean();
           
            $body .= $votes;
            $body = preg_replace('/table /', 'table cellpadding=5 cellspacing =0 border=1 ', $body);
            // $body = preg_replace('/class="btn green disabled"/', 'class="btn green disabled" style="background:#0f0"', $body);
            // $body = preg_replace('/class="btn green mini"/', 'class="btn green mini" style="background:#0f0"', $body);
            // $body = preg_replace('/class="btn yellow disabled"/', 'class="btn yellow disabled" style="background:#ff0"', $body);
            // $body = preg_replace('/class="btn yellow mini"/', 'class="btn yellow mini" style="background:#ff0"', $body);

            $sql_user = mysql_query("SELECT email,other_email from users where id='$_SESSION[MEM_ID]' ");
            $row_user = mysql_fetch_array($sql_user);

            $subject = "Proxy Voting Notification";
            $at_folder = 'user_proxy_forms';
            $at_file = $new_filename;
            mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$voter_email','$row_user[email]','$row_user[other_email]','','$subject', '$body','$at_folder','$at_file') ");
  }

?>