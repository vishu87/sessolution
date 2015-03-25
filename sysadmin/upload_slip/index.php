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
	?>
<form class="form-horizontal" id="submit_form" action="process.php?id=<?php echo $id; ?>&amp;rep_id=<?php echo $rep_id; ?>&amp;rep_type=<?php echo $rep_type; ?>" method="post" enctype="multipart/form-data">
  <div class="control-group">
    <label class="control-label" for="inputEmail">Upload File</label>
    <div class="controls">
      <input type="file" id="attachfile" name="attachfile">
      <br><br>
      <button type="button" onclick="check_submit()" class="btn">Upload</button>

    </div>
  </div>

  
</form>
</body>
<script type="text/javascript">
function check_submit(){
	if($("#attachfile").val() == ''){
		alert("Please select a file");
	} else {
		$("#submit_form").submit();
	}
}
</script>
</html>