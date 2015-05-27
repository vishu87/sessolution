<?php 
	session_start();
	require_once('top.php');
	//something about index
	$sidebar ='proxy_ad';
	$folder ='proxy_ad/';
	$title= "Proxy Advisory";

	if(isset($_GET["cat"])){
		$id= mysql_real_escape_string($_GET["cat"]);
		$id= preg_replace('/[^(0-9)]*/','', $id);
	}
	else {
		$id=1;
	}

	$sub_sidebar=$id;

	if(isset($_GET["rid"])){
		$rid= mysql_real_escape_string($_GET["rid"]);
		$rid= preg_replace('/[^(0-9)]*/','', $rid);
	}

	if($id>8 || $id<1) {
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
				case 1: include($folder.'uplcsv.php');
						break;
				case 2: include($folder.'all_reports.php');
						break;
				case 3: include($folder.'coverage.php');
						break;
				case 4: include($folder.'history.php');
						break;
				case 5: include($folder.'edit_report.php');
						break;
				case 6: include($folder.'all.php');
						break;
				case 7: include($folder.'skipped.php');
						break;
				case 8: include($folder.'uplres.php');
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