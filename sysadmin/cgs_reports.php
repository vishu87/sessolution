<?php 
	session_start();
	require_once('top.php');
	//something about index
	$sidebar ='cgs_reports';
	$folder ='cgs_reports/';
	$title= "CGS";

	if(isset($_GET["cat"])){
		$id= mysql_real_escape_string($_GET["cat"]);
		$id= preg_replace('/[^(0-9)]*/','', $id);
	}
	else {
		$id=1;
	}

	$sub_sidebar=$id;

	if(isset($_GET["cid"])){
		$cgs_id= mysql_real_escape_string($_GET["cid"]);
		$cgs_id= preg_replace('/[^(0-9)]*/','', $cgs_id);
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
				case 1: include($folder.'add_cgs.php');
						break;
				case 2: include($folder.'all_cgs.php');
						break;
				case 3: include($folder.'coverage.php');
						break;
				case 4: include($folder.'cgs.php');
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