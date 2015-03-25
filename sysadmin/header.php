<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title><?php echo $title;?></title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<link href="../assets/chosen-bootstrap/chosen/chosen.css" rel="stylesheet" />
	<link href="../assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" />
	<link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../assets/css/metro.css" rel="stylesheet" />
	<link href="../assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
	<link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<link href="../assets/datepicker/css/datepicker.css" rel="stylesheet" />
	<link href="../assets/css/style.css" rel="stylesheet" />
	<link href="../assets/css/style_responsive.css" rel="stylesheet" />
	<link href="../assets/css/style_default.css" rel="stylesheet" id="style_color" />
	<link href="../assets/css/master_cv.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="../assets/uniform/css/uniform.default.css" />
	<link rel="stylesheet" type="text/css" href="../assets/css/clockface.css" />
		<link rel="stylesheet" href="../assets/css/theme.bootstrap.css">
	<link rel="shortcut icon" href="favicon.ico" />
	<script>


		function validate_required_name_idinfo(value, id_info, alttext){
			
				if (value.match(/^[A-Za-z0-9 \-_]+$/) == null){
					$("#"+id_info).parent().find('span').text(alttext);
					$("#"+id_info).parent().parent().addClass("error");
					return false;
				}
				else {
					$("#"+id_info).parent().find('span').text("");
					$("#"+id_info).parent().parent().removeClass("error");
					return true;
				}
		}

		function validate_required_number_idinfo(value, id_info, alttext){
			
				if (value.match(/^[0-9]+$/) == null){
					$("#"+id_info).parent().find('span').text(alttext);
					$("#"+id_info).parent().parent().addClass("error");
					return false;
				}
				else {
					$("#"+id_info).parent().find('span').text("");
					$("#"+id_info).parent().parent().removeClass("error");
					return true;
				}
		}

		function validate_required_gen_idinfo(value, id_info, alttext){
			
				if (value.match(/^.+$/) == null){
					$("#"+id_info).parent().find('span').text(alttext);
					$("#"+id_info).parent().parent().addClass("error");
					return false;
				}
				else {
					$("#"+id_info).parent().find('span').text("");
					$("#"+id_info).parent().parent().removeClass("error");
					return true;
				}
		}

		function validate_required_email_idinfo(value, id_info, alttext){
			
				if (value.match(/([\w\-]+\@[\w\-]+\.[\w\-]+)/) == null){
					$("#"+id_info).parent().find('span').text(alttext);
					$("#"+id_info).parent().parent().addClass("error");
					return false;
				}
				else {
					$("#"+id_info).parent().find('span').text("");
					$("#"+id_info).parent().parent().removeClass("error");
					return true;
				}
		}

		function validate_required_date_idinfo(value, id_info, alttext){
			
				if (value.match(/^\d{2}-\d{2}-\d{4}$/) == null){
					$("#"+id_info).parent().find('span').text(alttext);
					$("#"+id_info).parent().parent().addClass("error");
					return false;
				}
				else {
					$("#"+id_info).parent().find('span').text("");
					$("#"+id_info).parent().parent().removeClass("error");
					return true;
				}
		}

		function validate_name(value,field,alerttxt){
			
				if (value.match(/^[A-Za-z0-9 \-_]+$/) == null){
						alert(alerttxt);
						$("#"+field).focus();
					return false;
				}
				else{	
					return true;
				}
			
		}

			function validate_com_name(value,field,alerttxt){
			
				if (value.match(/^.+$/) == null){
						alert(alerttxt);
						$("#"+field).focus();
					return false;
				}
				else{	
					return true;
				}
			
		}

		function validate_required_date(field,alerttxt){
			with (field){
				if (value.match(/^\d{2}\/\d{2}\/\d{4}$/) == null){
					alert(alerttxt+ " only dd/mm/yyyy");return false;
				}
				else		
					return true;
			}
		}

		function validate_required_phone(field,alerttxt){
			with (field){
				if (value.match(/^\d{10,11}$/) == null){
						alert(alerttxt+ " only 10 or 11 digits");return false;
					}
				else
				return true;
			}
		}

		function validate_required_number(field,alerttxt)
		{
			with (field){	
				if (value.match(/^\d+$/) == null){
					alert(alerttxt+ " only digits are allowed");return false;
				}
				else
					return true;
			}
		}
		function validate_required_password(field,alerttxt)
		{
			with (field){
				if (value.match(/^.{8,}$/) == null){
					alert(alerttxt+ "");return false;
				}
				else
				return true;
			}
		}
		function validate_required_userid(field,alerttxt)
		{
			with (field){
				if (value.match(/^.{5,}$/) == null){
						alert(alerttxt+ "");return false;
				}
				else
					return true;
			}
		}
		function validate_duplicate(value, table, column, file, span_info ){

			$.post("ajax/"+ file +".php", {tbl:table, col: column, val:value }, function(data) {
				alert(data);
				if (data == 'success') {
					$("#"+span_info).text("");
					$("#"+span_info).parent().parent().removeClass("error");
					return true;
				} else {
					$("#"+span_info).text("Duplicate entry");
					$("#"+span_info).parent().parent().addClass("error");
					return false;
				}
			});   

		}
	</script>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
	<!-- BEGIN HEADER -->
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div class="navbar-inner">
			<div class="container-fluid">
				<!-- BEGIN LOGO -->
				<a class="brand" href="index.php">
					SES ADMIN PORTAL
				</a>
				<!-- END LOGO -->
				<!-- BEGIN RESPONSIVE MENU TOGGLER -->
				<a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
				<img src="../assets/img/menu-toggler.png" alt="" />
				</a>          
				<!-- END RESPONSIVE MENU TOGGLER -->				
				<!-- BEGIN TOP NAVIGATION MENU -->					
				<ul class="nav pull-right">
					
					<!-- BEGIN USER LOGIN DROPDOWN -->
					<li class="dropdown user">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img alt="" src="../assets/img/avatar1_small.jpg" />
						<span class="username"><?php echo $member->username;?></span>
						<i class="icon-angle-down"></i>
						</a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo STRSITE?>logout.php"><i class="icon-key"></i> Log Out</a></li>
						</ul>
					</li>
					<!-- END USER LOGIN DROPDOWN -->
				</ul>
				<!-- END TOP NAVIGATION MENU -->	
			</div>
		</div>
		<!-- END TOP NAVIGATION BAR -->
	</div>
	<!-- END HEADER -->