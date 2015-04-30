<?php 
	session_start();
	require_once('top.php');
	//something about index
	$sidebar ='schemes';
	$folder ='schemes/';
	$title= "Scheme Management";

	if(isset($_GET["cat"])){
		$id= mysql_real_escape_string($_GET["cat"]);
		$id= preg_replace('/[^(0-9)]*/','', $id);
	}
	else {
		$id=1;
	}

	$sub_sidebar=$id;
	if($_GET["update"]){
		$update_id = decrypt($_GET["update"]);
		if(is_numeric($update_id)) {
			$query = mysql_query("SELECT * from schemes where id = $update_id and user_id  = $_SESSION[MEM_ID] limit 1 ");
			if(mysql_num_rows($query) == 0){
				header("location: ".STRSITE."access-denied.php");
			} else {
				$update = mysql_fetch_array($query);
			}
		} else header("location: ".STRSITE."access-denied.php");
	}

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
				case 1: include($folder.'manage_scheme.php');
						break;
				case 2: include($folder.'edit.php');
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