<?php 
	session_start();
	require_once('top.php');
	//something about index

	$sidebar ='settings';
	$folder ='settings/';
	if(isset($_GET["cat"])) {
		$id= mysql_real_escape_string($_GET["cat"]);
		$id= preg_replace('/[^(0-9)]*/','', $id);
	}
	else {
		$id=1;
	}

	$title = 'Settings';

	if(isset($_GET["type"])){
		$type= mysql_real_escape_string($_GET["type"]);
		$type= preg_replace('/[^(0-9)]*/','', $type);
	}
	else {
		$type=0;
	}

	$flag = 0;

	$user_id = $_SESSION["MEM_ID"];
	
	$sub_sidebar=$id;
	if($id>1 || $id<1) {header("location: ".STRSITE."access-denied.php");}

	require_once('header.php');?>
	<!-- BEGIN CONTAINER -->
	<div class="page-container row-fluid">
		<?php require_once('sidebar.php');?>
		<!-- BEGIN PAGE -->
		<div class="page-content">
		<?php
			switch($id)
			{
				case 1: include($folder.'add_setting.php');
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