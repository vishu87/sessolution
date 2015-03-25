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
$user = mysql_real_escape_string($_POST["user"]);


if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$sql = "SELECT users.name,users.username, users.email from users inner join users_package on users.id = users_package.user_id where users_package.package_id='$_POST[id]' ";

$query_sent = mysql_query($sql);

echo '<table class="table table-bordered table-hover tablesorter"><th>#</th><th>Name</th><th>Username</th><th>Email</th>';
	$count = 1;
while($row = mysql_fetch_array($query_sent))  {

	echo '<tr id="tr_com_'.$row["com_id"].'">';

			echo "<td>".$count."</td><td>".$row["name"]."</td><td>".$row["username"]."</td>";
			echo '<td>'.$row["email"].'</td>';
		
		echo '</tr>';
		$count++;
	}

echo '</table>';

?>