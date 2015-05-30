<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');

if(!isset($_POST["scheme_id"])) header("Location: ".STRSITE."access-denied.php");

$scheme_id = mysql_real_escape_string($_POST["scheme_id"]);
$shares_held = mysql_real_escape_string($_POST["shares_held"]);
$com_string = $_POST["company_name"];
$coms = explode('/', $com_string);
$com_name = addslashes($coms[0]);
$com_bse_code = $coms[1];


$check_auth_scheme = mysql_query("SELECT user_id from schemes where id = $scheme_id limit 1");
$row_check = mysql_fetch_array($check_auth_scheme);
if($row_check["user_id"] != $_SESSION["MEM_ID"]) die();


$sql_com = mysql_query("SELECT com_id, com_name from companies where com_name='$com_name' and com_bse_code='$com_bse_code' limit 1 ");
if(mysql_num_rows($sql_com) > 0){
	$row_com = mysql_fetch_array($sql_com);
	$ar = $row_com["com_id"];
	$com_name = $row_com["com_name"];

	$sql_check = mysql_query("SELECT com_id from scheme_companies where scheme_id='$scheme_id' and com_id = '$ar' ");
	if(mysql_num_rows($sql_check) > 0){
		$response["success"] = false;
		$response["message"] = "Company already exists in the scheme";
	} else {
	  if($ar != 0){
	     	mysql_query("INSERT into scheme_companies (scheme_id, com_id, shares_held) values ('$scheme_id','$ar','$shares_held') ");
	     	$insert_id = mysql_insert_id();
	     	$response["success"] = true;
	     	$response["message"] = '<tr id="tr_pop_'.$insert_id.'">
					<td>'.($_POST["count"] + 1).'</td>
					<td>'.$com_name.'</td>
					<td>'.$shares_held.'</td>
					<td>
						<a href="javascript:;" class="btn red" id="rm_comp_'.$insert_id.'" onclick="remove_scheme_company('.$insert_id.','.$ar.')">Remove</a>
					</td>
				</tr>';
	  }
	}
} else {
	$response["success"] = false;
	$response["message"] = "Company not found";
}
echo json_encode($response);
?>