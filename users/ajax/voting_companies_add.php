<?php session_start();
require_once('../../auth.php');

$user = $_SESSION["MEM_ID"];
$_POST["id"] = mysql_real_escape_string($_POST["id"]);

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$check = mysql_query("SELECT id from users where created_by_prim='$_SESSION[MEM_ID]' and id = '$_POST[id]' ");
if(mysql_num_rows($check) == 0) die('You are not authorized for this.' );
$flag_check = 0;
$string ='';

  $com_string = $_POST["com_string"];
  $coms = explode('/', $com_string);
  $com_name = addslashes($coms[0]);
  $com_bse_code = $coms[1];

  $sql = mysql_query("SELECT com_id from companies where com_name='$com_name' and com_bse_code='$com_bse_code' limit 1 ");
  if(mysql_num_rows($sql) > 0){
    $com_det = mysql_fetch_array($sql);
    $com_id = $com_det["com_id"];
  }else {
    die('fail');
  }

  $check = mysql_query("SELECT id from voting_access where user_id='$_POST[id]' and com_id='$com_id' ");
  if(mysql_num_rows($check) == 0){

    if(mysql_query("INSERT into voting_access (user_id, com_id,added_by,add_date) values ('$_POST[id]','$com_id','$user','".strtotime("now")."') ")){
      $query_detail = mysql_query("SELECT com_id, com_name, com_bse_code from companies where com_id='$com_id' ");
      $row_detail = mysql_fetch_array($query_detail);
      $flag_check = 0;
      $string .= '<tr id="tr_'.$row_detail["com_id"].'"><td>'.$row_detail["com_name"].'</td><td>'.$row_detail["com_bse_code"].'</td><td><a href="javascript:;" class="btn yellow" onclick="voting_companies_delete('.$_POST["id"].','.$row_detail["com_id"].')">Delete</a></td></tr>';

    } else $flag_check =1;
  }


echo ($flag_check == 0)?$string:'fail';
?>
