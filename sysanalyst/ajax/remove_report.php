<?php session_start();
require_once('../../sysan.php');
require_once('../../config.php');
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}

$id= $_POST["id"];
$rep_id = $_POST["rep_id"];
$rep_type = $_POST["rep_type"];
$strtime = '';
$filename='';

    switch ($rep_type) {
      case '1':
        	mysql_query("UPDATE proxy_ad set report='$filename',  completed_on= '$strtime' where id='$rep_id' ");
          break;
      
       case '2':
        	mysql_query("UPDATE cgs set report_upload='$filename', completed_on= '$strtime' where cgs_id='$rep_id' ");
          break;

         case '3':
        	mysql_query("UPDATE research set report_upload='$filename', completed_on= '$strtime' where res_id='$rep_id' ");
          break;
    }

    mysql_query("UPDATE report_analyst set completed_on= '$strtime' where id='$id' ");
    echo 'success';
 
  

?>