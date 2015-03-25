 <?php
   require_once('config.php');
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}

    
  
  
   $sql_case="SELECT * from students ";
$sql_student =$sql_case." ORDER BY id DESC";
$result_case=mysql_query($sql_student);

while($row_student = mysql_fetch_array($result_case))
{
$id = $row_student["id"];
$sql="SELECT * from payment_history WHERE student_id='$id' ORDER by doe DESC ";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);

if($row["doe"])
{
mysql_query("UPDATE students SET doe= '$row[doe]' WHERE id='$id'");
}


}

  
  
  
  
  ?>
 