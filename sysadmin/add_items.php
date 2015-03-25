<?php 
	session_start();
	require_once('top.php');
	//something about index
	$title='Add Details';
	$sidebar ='add_items';
	$folder ='add_items/';
	if(isset($_GET["cat"])) {
		$id= mysql_real_escape_string($_GET["cat"]);
		$id= preg_replace('/[^0-9]*/','', $id);
	}
	else {
		$id=1;
	}
	if(isset($_GET["update"])){
		$update= mysql_real_escape_string($_GET["update"]);
		$update= preg_replace('/[^0-9]*/','', $update);
	}
	
	$sub_sidebar=$id;
	
	
	if($id>5 || $id<1) {header("location: ".STRSITE."access-denied.php");}
	require_once('header.php');?>
	<!-- BEGIN CONTAINER -->
	<div class="page-container row-fluid">
		<?php require_once('sidebar.php');?>
		<!-- BEGIN PAGE -->
		<div class="page-content">
		<?php
			switch($id)
			{
				case 1: include($folder.'res_type.php');
						break;
				case 2: include($folder.'res_reco.php');
						break;
				case 3: include($folder.'res_reason.php');
						break;
				case 4: include($folder.'location.php');
						break;
				case 5: include($folder.'years.php');
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
