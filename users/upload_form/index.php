<?php session_start();
require_once('../../auth.php');
require_once('../../config.php');
require_once('../../classes/UserClass.php');
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}

if( $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$user = new User($_SESSION["MEM_ID"]);

?>
<?php
		$id = mysql_real_escape_string($_GET["id"]);
		$proxy_module = mysql_real_escape_string($_GET["proxy_module"]);

		if($proxy_module == 1){
		  $table = 'proxies';
		} elseif ($proxy_module == 2){
		  $table = 'self_proxies';
		}

		$sql = mysql_query("SELECT proxy_id, form from $table where id='$id' limit 1");
		$row = mysql_fetch_array($sql);

		if($row["form"] != '' ) die("Form has been already uploaded for this proxy advisory. Please reset proxy to upload again");

		$sql = "SELECT final_freeze, final_unfreeze from user_admin_proxy_ad where user_id='$_SESSION[MEM_ID]' and report_id='$row[proxy_id]' and final_freeze != 0 order by id desc limit 1";
		
		$check_final_freeze = mysql_query($sql);
		$flag_final_freeze = 0;
		if(mysql_num_rows($check_final_freeze) > 0){
			$row_freeze = mysql_fetch_array($check_final_freeze);
			if($row_freeze["final_freeze"] != 0 && $row_freeze["final_unfreeze"] == 0) $flag_final_freeze = 1;
			
		}

		if($flag_final_freeze == 0){
			die("<h4>Please freeze your votes first</h4>");
		}

	?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<link href="../../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../../assets/css/metro.css" rel="stylesheet" />
	<script src="../../assets/js/jquery-1.8.3.min.js"></script>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body >

<form class="form-horizontal" id="submit_form" action="process.php?id=<?php echo $id; ?>&amp;proxy_module=<?php echo $proxy_module; ?>" method="post" enctype="multipart/form-data">
  <div class="control-group">
    <label class="control-label" for="inputEmail">Upload File</label>
    <div class="controls">
      <input type="file" id="attachfile" name="attachfile">
      <br><br>
      <button type="button" id="uploadbtn" onclick="check_submit(<?php echo $row["proxy_id"] ?>, <?php echo $id ?>)" class="btn">Upload</button>

      <a href="javascript:;" onclick="skip_upload(<?php echo $row["proxy_id"] ?>, <?php echo $id ?>, <?php echo $proxy_module; ?>)" class="btn yellow" id="skipbtn">Skip Upload</a>

    </div>
  </div>

  
</form>
</body>
<script type="text/javascript">
function check_submit(report_id, self_proxy_id){
	var ext = $("#attachfile").val().split('.').pop().toLowerCase();
	if($.inArray(ext, ['pdf','doc','docx','xls','xlsx']) == -1) {
		alert("Please select a valid file");
	} else {
		$("#uploadbtn").html('Creating Holding Report..');
    	var file = 'create_holding';
		$.post("../ajax/"+ file +".php", {report_id:report_id, self_proxy_id:self_proxy_id}, function(data) {
	      if(data == 'success'){
				$("#uploadbtn").html('Uploading Form..');
				$("#submit_form").submit();
		      } else {
		       	$("#uploadbtn").html('Upload');
		      }
		  });
	}
}

function skip_upload(report_id, self_proxy_id, module_type){

		$("#skipbtn").html('Creating Holding Report..');
    	var file = 'create_holding';
		$.post("../ajax/"+ file +".php", {report_id:report_id, self_proxy_id:self_proxy_id}, function(data) {
	      if(data == 'success'){
				$("#skipbtn").html('Processing..');
				location.replace('skip.php?id='+self_proxy_id+'&proxy_module='+module_type);
		      } else {
		       	$("#skipbtn").html('Skip Upload');
		      }
		  });

}
</script>
</html>