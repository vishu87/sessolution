<?php 
	session_start();
	require_once('top.php');
	//something about index
	$title='Calender View';
	$sidebar ='upcoming';
	$folder ='upcoming/';
	if(isset($_GET["cat"])) {
		$id= mysql_real_escape_string($_GET["cat"]);
		$id= preg_replace('/[^(0-9)]*/','', $id);
	}
	else {
		$id=1;
	}
	if(isset($_GET["pan"])){
		$pan= mysql_real_escape_string($_GET["pan"]);
		$pan= preg_replace('/[^(0-9)]*/','', $pan);
	}
	else {
		$pan=1;
	}
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
				case 1: include($folder.'all_meetings.php');
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
