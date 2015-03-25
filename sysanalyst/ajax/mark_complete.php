<?php session_start();

require_once('../../sysan.php');
require_once('../../config.php');
require_once('../../mail/class.phpmailer.php');

$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}

$report_id = $_POST["id"];
$str = strtotime("now");

mysql_query("UPDATE report_analyst set completed_on='$str' where id='$report_id' and an_id='$_SESSION[MEM_ID]' ");

$query = mysql_query("SELECT * from report_analyst where id='$report_id' limit 1");
$row = mysql_fetch_array($query);
$report_id = $row["report_id"];
$rep_type = $row["rep_type"];
$task_next = $row["type"] + 1;
$sql  ="SELECT analysts.email, report_analyst.deadline from analysts inner join report_analyst on analysts.an_id = report_analyst.an_id where report_analyst.report_id = '$report_id' and report_analyst.rep_type='$rep_type' and report_analyst.type='$task_next' ";
$query_an = mysql_query($sql);
if(mysql_num_rows($query_an) > 0){
	$row_an = mysql_fetch_array($query_an);
	$email  = $row_an["email"];
	$deadline  = date("d M y",$row_an["deadline"]);

	switch ($rep_type) {
      case '1':
        	$query_rep =  mysql_query("SELECT companies.com_name, proxy_ad.meeting_date, proxy_ad.meeting_type from  proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where id='$report_id' ");
          break;
      
       case '2':
        	$query_rep =  mysql_query("SELECT companies.com_name, cgs.publishing_date as meeting_date from  cgs inner join companies on cgs.com_id = companies.com_id where cgs_id='$report_id' ");
          break;

         case '3':
        	$query_rep =  mysql_query("SELECT companies.com_name, research.publishing_date as meeting_date from  research inner join companies on research.com_id = companies.com_id where res_id='$report_id' ");
          break;
    }
    $row_rep = mysql_fetch_array($query_rep);

    $com_name = $row_rep["com_name"];
    $meeting_date = date("d M y", $row_rep["meeting_date"]);
    $meeting_type = ($row_rep["meeting_type"])?$meeting_types[$row_rep["meeting_type"]]:'N/A';
    $task_next = $task_types[$task_next];

    $message .= '<p>
		Following report has been marked from contingent to pending.
		</p>
		<p>Meeting Details:</p>
		
		Company Name: '.$com_name.'<br>
		Meeting Date: '.$meeting_date.'<br>
		Meeting Type: '.$meeting_type.'   </p>
<p>=================================</p>
		<p>Task Details<br>
		Type: '.$task_next.' <br>
		Deadline: '.$deadline.'</p>';

  $subject = "Contingent to Pending Status"; 
  $body = mysql_real_escape_string($message);

    if(mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$email','','','','$subject', '$body','','') ")) echo 'success';
    else echo 'Mail can not be sent right now. Please try again later';
} 
  

?>