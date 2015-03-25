<?php
session_start();
//echo $_SESSION["PRIV"];
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
  <meta charset="utf-8" />
  <title>Change Password</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta content="" name="description" />
  <meta content="" name="author" />
  <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/metro.css" rel="stylesheet" />
  <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <link href="assets/css/style_responsive.css" rel="stylesheet" />
  <link href="assets/css/style_light.css" rel="stylesheet" id="style_color" />
  <link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">

  <!-- BEGIN LOGIN -->
  <div class="content" style="margin-top:40px;">
    <!-- BEGIN LOGIN FORM -->
    <form class="form-vertical login-form" action="#">
      <h3 class="form-title" style="text-align:center">Change Password</h3>
      <!-- <span style="text-align:center">Your password is more than 45 days old</span> -->
	  <div class="alert alert-error hide" id="error-box">
		
		</div>
      <div class="control-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label">Old Password</label>
        <div class="controls">
          <div class="input-icon left">
             <i class="icon-lock"></i>
            <input class="m-wrap placeholder-no-fix" type="password" placeholder="Old Password" name="old_p" id="old_p"/>
          </div>
		  <span id="username_info" style="color:#f00"></span>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">New Password</label>
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-lock"></i>
            <input class="m-wrap placeholder-no-fix ttip" data-placement="right" type="password" placeholder="New Password" name="new_p" id="new_p" onkeyup="clear_password()" title="Password must be atleast 8 charaters long. It must contain atleast one Uppercase letter (A-Z), one special charaters ( ! @ # $ % _ ^ * &amp; ~ ) ,and one number(0-9)."/><!-- <a href="javascript:;" rel="tooltip" class="ttip icn-only" data-placement="right" title="Password must be atleast 8 charaters long. It must contain atleast one Uppercase letter (A-Z),<br> one special charaters ( ! @ # $ % _ ^ * &amp; ~ ) ,and one number(0-9)."><i class="icon-question-sign"></i></a> -->
          </div>
		        <span id="password_info" style="color:#f00"></span>
        </div>
      </div>

       <div class="control-group">
        <label class="control-label">Re-Type Password</label>
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-lock"></i>
            <input class="m-wrap placeholder-no-fix" type="password" placeholder="Re-type Password" name="re_new_p" onkeyup="clear_password()" id="re_new_p"/>
          </div>
       <span id="password_info" style="color:#f00"></span>
        </div>
      </div>

      <div class="form-actions">
        <a href="javascript:;" rel="tooltip" class="btn ttip icn-only" data-placement="right" title="Password must be changed in every 45 days. Password must be atleast 8 charaters long. It must contain atleast one Uppercase letter (A-Z),<br> one special charaters ( ! @ # $ % _ ^ * &amp; ~ ) ,and one number(0-9)."><i class="icon-question-sign"></i></a>
       <button type="button" onclick="change_save()" class="btn green pull-right" id="change_button">
        Change Password
        </button>            
      </div>
	  
	  
    </form>
    <!-- END LOGIN FORM -->        
    
  </div>
  <!-- END LOGIN -->
  <!-- BEGIN COPYRIGHT -->
  <div class="copyright" style="font-size:15px; width:100%">
    Designed &amp; Maintained by <a href="http://www.gkmit.co" target="_blank">GKM IT Pvt. Ltd.</a>
  </div>
  <!-- END COPYRIGHT -->
  <!-- BEGIN JAVASCRIPTS -->

  <script src="assets/js/jquery-1.8.3.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>  
  <script src="assets/js/jquery.blockui.js"></script>
  <script src="assets/uniform/jquery.uniform.min.js"></script> 
  <script src="assets/js/app.js"></script>
  <script src="assets/js/jinclude.js"></script>
  <script>
    jQuery(document).ready(function() { 
      $('.ttip').tooltip();
    });
  </script>
    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>