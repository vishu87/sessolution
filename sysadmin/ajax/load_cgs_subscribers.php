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


if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$users = array();
$sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '$_POST[id]' and package.package_year='$_POST[year]' and package.package_type='2' ");

while ($row = mysql_fetch_array($sql_pack_user)) {
	if(!in_array($row["user_id"], $users))
	array_push($users, $row["user_id"]);
}

$sql_addi_user = mysql_query("SELECT user_id from users_companies where com_id = '$_POST[id]' and year = '$_POST[year]' and type='2' ");
while ($row = mysql_fetch_array($sql_addi_user)) {
	if(!in_array($row["user_id"], $users))
	array_push($users, $row["user_id"]);
}
echo '<h4>Subscribed</h4>';
echo '<table class="table table-bordered table-hover tablesorter"><th>#</th><th>Name</th><th>Username</th><th>Email</th>';
	$count = 1;
foreach($users as $user)  {

	$query = mysql_query("SELECT name, username,email from users where id='$user' ");
	$row = mysql_fetch_array($query);
	echo '<tr id="tr_com_'.$row["com_id"].'">';

			echo "<td>".$count."</td><td>".$row["name"]."</td><td>".$row["username"]."</td>";
			echo '<td>'.$row["email"].'</td>';
		
		echo '</tr>';
		$count++;
	}

echo '</table>';

?>