<?php session_start();
require_once('../../sysan.php');
require_once('../../config.php');
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}?>
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
	<?php
		$id = $_GET["id"];
		$rep_id = $_GET["rep_id"];
		$rep_type = $_GET["rep_type"];
		$sql = "SELECT id from report_analyst where an_id='$_SESSION[MEM_ID]' and report_id='$rep_id' and type='3' ";
		$check = mysql_query($sql);
		if(mysql_num_rows($check) == 0) die('You are not authorized');
	?>
<form class="form-horizontal" id="submit_form" action="process.php?id=<?php echo $id; ?>&amp;rep_id=<?php echo $rep_id; ?>&amp;rep_type=<?php echo $rep_type; ?>" method="post" enctype="multipart/form-data">
  <div class="control-group">
    <label class="control-label" for="inputEmail">Upload File</label>
    <div class="controls">
      <input type="file" id="attach_file" name="attachfile">
      <br><br>
      <button type="button" onclick="check_submit()" class="btn">Upload</button>

    </div>
  </div>

  
</form>
</body>
<script type="text/javascript">
function check_submit(){
var ext = $("#attach_file").val().split('.').pop().toLowerCase();
   if($.inArray(ext, ['pdf','doc','docx','xls','xlsx']) == -1) {
      alert("Please Choose a valid file");
      return false;
   } else {
      $("#submit_form").submit();
   }
}
</script>
</html>