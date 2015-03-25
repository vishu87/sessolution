<?php 
	session_start();
	require_once('top.php');
	//something about index
	$sidebar ='proxy_voters';
	$folder ='proxy_voters/';
	$title= "Vote Management";

	if(isset($_GET["cat"])){
		$id= mysql_real_escape_string($_GET["cat"]);
		$id= preg_replace('/[^0-9]*/','', $id);
	}
	else {
		$id=1;
	}

	if(isset($_GET["aid"])){
		$aid= decrypt($_GET["aid"]);
		$aid= preg_replace('/[^0-9]*/','', $aid);
	}
	

	$sub_sidebar=$id;

	if($id>2 || $id<1) {
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
				
				case 1: include($folder.'add_voter.php');
						break;
				case 2: include($folder.'voter.php');
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