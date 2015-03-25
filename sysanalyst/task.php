<?php 
	session_start();
	require_once('top.php');
	//something about index
	$sidebar ='task';
	$folder ='task/';
	$title= "Tasks";

	if(isset($_GET["cat"])){
		$id= mysql_real_escape_string($_GET["cat"]);
		$id= preg_replace('/[^(0-9)]*/','', $id);
	}
	else {
		$id=1;
	}


	if(isset($_GET["res"])){
		$res_id= decrypt($_GET["res"]);
		$res_id= preg_replace('/[^0-9]*/','', $res_id);
	}

	if(isset($_GET["cgs"])){
		$cgs_id= decrypt($_GET["cgs"]);
		$cgs_id= preg_replace('/[^0-9]*/','', $cgs_id);
	}

	if(isset($_GET["proxy"])){
		$rid= decrypt($_GET["proxy"]);
		$rid= preg_replace('/[^0-9]*/','', $rid);
	}

	$sub_sidebar=$id;

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
				case 1: include($folder.'pending.php');
						break;
				case 2: include($folder.'completed.php');
						break;
				case 3: include($folder.'edit_report.php');
						break;
				case 4: include($folder.'cgs.php');
						break;
				case 5: include($folder.'research.php');
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