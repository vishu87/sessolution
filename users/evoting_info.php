<?php 
	session_start();
	require_once('top.php');
	//something about index
	$sidebar ='evoting_info';
	$folder ='evoting_info/';
	$title= "eVoting Info";

	if(isset($_GET["cat"])){
		$id= mysql_real_escape_string($_GET["cat"]);
		$id= preg_replace('/[^(0-9)]*/','', $id);
	}
	else {
		$id=1;
	}

	$sub_sidebar=$id;

	$query = mysql_query("SELECT * from evoting_info where user_id = '$_SESSION[MEM_ID]' limit 1 ");
	if(mysql_num_rows($query) > 0){
		$value = mysql_fetch_array($query);
	} else {
		mysql_query("INSERT into evoting_info (user_id) values ('$_SESSION[MEM_ID]') ");
		$query_repeat = mysql_query("SELECT * from evoting_info where user_id = '$_SESSION[MEM_ID]' limit 1 ");
		$value = mysql_fetch_array($query_repeat);
	}

	require_once('header.php');?>
	<!-- BEGIN CONTAINER -->
	<div class="page-container row-fluid">
		<?php require_once('sidebar.php');?>
		<!-- BEGIN PAGE -->
		<div class="page-content">
		<?php
			switch($id)
			{
				case 1: include($folder.'evoting_info.php');
						break;
			}
		?>			
		</div>
		<!-- END PAGE -->	 	
	</div>
	<!-- END CONTAINER -->
<?php
	require_once('footer.php');
	
?>