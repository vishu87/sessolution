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
$strtime = strtotime("now");

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

if(mysql_query("UPDATE $table set proxy_skipped ='$strtime' where id='$id' and user_id='$_SESSION[MEM_ID]' ")){
   echo '<div class="alert alert-success"><strong>Success!</strong> Mail has been successfully sent to the proxy voter.</div>'; 
} else {
    echo 'fail';
    die();
  }

$body = '';

            
            $recos = array();
            $sql_reco = mysql_query("SELECT * from ses_recos");
            while ($row_reco = mysql_fetch_array($sql_reco)) {
              $recos[$row_reco["id"]] = $row_reco["reco"];
            }

            $update = array();
            
            $body = '<table class="table table-bordered table-hover">';

                $ar_fields_name = array("Company","Meeting Details","Client Details","Proxy Appointed");
                $ar_fields_type = array("Company","MetDet","ProxyRequest","ProxyAppointed");
                
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
                }
                
              $count = 0;
              
              foreach ($ar_fields_type as $ar) {
                  $body .= "<tr><td>".$ar_fields_name[$count]."</td><td>".$update[$ar]."</td></tr>";
                $count++;
              }

            $body .= '</table><br><br><br>';

            ob_start();
            $voting = new SesVoting();
            $voting->user_votes_final($report_id,$_SESSION["MEM_ID"],2);
            $votes = ob_get_clean();
           
            $body .= $votes;
            $body = preg_replace('/table /', 'table cellpadding=5 cellspacing =0 border=1 ', $body);

            $sql_user = mysql_query("SELECT email,other_email from users where id='$_SESSION[MEM_ID]' ");
            $row_user = mysql_fetch_array($sql_user);

            $subject = "Proxy Voting Notification";

            mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$voter_email','$row_user[email]','$row_user[other_email]','','$subject', '$body','','') ");

?>