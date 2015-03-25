<?php session_start();
require_once('../../sysauth.php');
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

$filename = $_FILES["attachfile"]["name"]; 
$ar = explode('.', $filename);
$num = sizeof($ar);
$ext = $ar[$num-1];
$strtime = strtotime("now");
$new_filename = strtotime("now").md5($_SESSION["MEM_ID"]).'.'.$ext;
  if($filename != '') {
   
     
        move_uploaded_file($_FILES["attachfile"]["tmp_name"],"../../user_proxy_slips/".$new_filename);
        if(file_exists("../../user_proxy_slips/".$new_filename)){
        	$new_filename = mysql_real_escape_string($new_filename);
        	mysql_query("UPDATE proxies set slip ='$new_filename',  final_date= '$strtime' where id='$id' ");
        }

    echo '<div class="alert alert-success">
            <strong>Success!</strong> Slip uploaded.
          </div>';
    
  } else{
    echo 'fail';
    die();
  }
  die();
  

?>