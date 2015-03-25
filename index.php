<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
  <meta charset="utf-8" />
  <title>Login to system</title>
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
      <div align="center" style="margin:0 0 20px 0 ">
        <img src="logo.png">
      </div>
	  <div class="alert alert-error hide" id="error-box">
		
		</div>
      <div class="control-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Username</label>
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-user"></i>
            <input class="m-wrap placeholder-no-fix" type="text" placeholder="Username" name="username_login" id="username_login"/>
          </div>
		  <span id="username_info" style="color:#f00"></span>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label visible-ie8 visible-ie9">Password</label>
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-lock"></i>
            <input class="m-wrap placeholder-no-fix" type="password" placeholder="Password" name="password_login" id="password_login"/>
          </div>
		   <span id="password_info" style="color:#f00"></span>
        </div>
      </div>

      <div class="form-actions">
        <label class="checkbox" style="margin-left: -20px;">
        <input type="checkbox" name="remember" value="1"/>&nbsp;Remember me
        </label>
        <button type="submit" class="btn green pull-right" id="login_submit">
        Login <i class="m-icon-swapright m-icon-white"></i>
        </button>            
      </div>
	  
      <div class="forget-password">
        <h4><a href="javascript:;" class="" id="forget-password">Lost Password?</a></h4>
      </div>
	  
    </form>
    <!-- END LOGIN FORM -->        
    <!-- BEGIN FORGOT PASSWORD FORM -->
    <form class="form-vertical forget-form" action="index.html">
      <h3 class="">Lost Password ?</h3>
      <p>Enter your e-mail address below to reset your password.</p>
      <div class="control-group">
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-envelope"></i>
            <input class="m-wrap placeholder-no-fix" type="text" placeholder="Email" name="email_forgot" id="email_forgot" />
          </div>
           <span id="emailInfo" style="color:#f00"></span>
        </div>
      </div>
      <div class="form-actions">
        <button type="button" id="back-btn" class="btn">
        <i class="m-icon-swapleft"></i> Back
        </button>
        <button type="submit" class="btn green pull-right" id="forgot_submit">
        Submit <i class="m-icon-swapright m-icon-white"></i>
        </button>            
      </div>
    </form>
    <!-- END FORGOT PASSWORD FORM -->
    
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
  
    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>