<?php 
	session_start();
	require_once('top.php');
	//something about index
	$sidebar ='research';
	$folder ='research/';
	$title= "Research";

	if(isset($_GET["cat"])){
		$id= mysql_real_escape_string($_GET["cat"]);
		$id= preg_replace('/[^(0-9)]*/','', $id);
	}
	else {
		$id=1;
	}

	$sub_sidebar=$id;

	if(isset($_GET["rid"])){
		$res_id= mysql_real_escape_string($_GET["rid"]);
		$res_id= preg_replace('/[^(0-9)]*/','', $res_id);
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
				case 1: include($folder.'add_research.php');
						break;
				case 2: include($folder.'all_research.php');
						break;
				case 3: include($folder.'coverage.php');
						break;
				case 4: include($folder.'research.php');
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