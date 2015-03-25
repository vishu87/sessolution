<?php session_start();
require_once '../subuserauth.php';

$user_info = mysql_query("SELECT name, user_admin_name from users where id='$_SESSION[MEM_ID]' limit 1");
$row_user = mysql_fetch_array($user_info);

$name_final = ($row_user["user_admin_name"] == '')?$row_user["name"]:$row_user["user_admin_name"];

header('Content-Type: application/excel');
header('Content-Disposition: attachment; filename="'.$name_final.'_Upload_Format.csv"');

$data = array();

$dm = array();
$query = mysql_query("SELECT * from user_portfolio where user_id='$_SESSION[MEM_ID]' order by portfolio_name asc ");
while ($row = mysql_fetch_array($query)) {
  array_push($dm, $row["portfolio_name"]);
}
if(sizeof($dm) > 0){
  array_push($data, implode(',', $dm)); 
}
$string = array();
  for($i=0; $i<sizeof($dm);$i++) {
    array_push($string, "BSE OR ISIN");
  }
$string_put = implode(',', $string);
if(sizeof($dm)>0){
  for($i=1; $i<=3;$i++) {
    array_push($data, $string_put);
  }
}


$fp = fopen('php://output', 'w');
foreach ( $data as $line ) {
    $val = explode(",", $line);
    fputcsv($fp, $val);
}
fclose($fp);

?>