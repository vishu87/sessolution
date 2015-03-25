<?php 
	session_start();
	require_once('top.php');
	//something about index
	$sidebar ='users';
	$folder ='users/';
	$title= "Users";

	if(isset($_GET["cat"])){
		$id= mysql_real_escape_string($_GET["cat"]);
		$id= preg_replace('/[^(0-9)]*/','', $id);
	}
	else {
		$id=1;
	}

	$sub_sidebar=$id;

	if(isset($_GET["uid"])){
		$user_id= mysql_real_escape_string($_GET["uid"]);
		$user_id= preg_replace('/[^(0-9)]*/','', $user_id);
	}
	

	if($id>5 || $id<1) {
		header("location: ".STRSITE."access-denied.php");
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
				case 1: include($folder.'add_user.php');
						break;
				case 2: include($folder.'all_users.php');
						break;
				case 3: include($folder.'user.php');
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