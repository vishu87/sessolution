<?php define('IN_SCRIPT',1);
define('ADMIN_PATH','../');
include('functions/config.php');
@session_start();
ob_start();

error_reporting(E_ALL & ~E_NOTICE);
$advisory_id = mysql_real_escape_string($_GET["advisory_id"]);
$mysql ="SELECT * FROM tbladvisory where advisory_id='$advisory_id' LIMIT 1";
$res2 = @mysql_query($mysql);
$row=@mysql_fetch_assoc($res2);

?>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<style>
#billing_form span{
	color:#f00;
	font-size:11px;
	font-style:italic;
}
</style>
<script type="text/javascript" src="javascript/jquery-min.js"></script>

<form action="checkout.php?advisory_id=<?php echo $advisory_id ?>" method="post">
    <div id="search">
	  
		 <div class="acStretchOut" id="acStretcher_page27_0">
		  <div style=" padding-top:2px;">
	     <table width="681" border="0" align="center" cellpadding="0" cellspacing="0" >
           <tr>
             <td >
				<div style="padding:8px 10px; background:#fff; border:1px solid #dadada; font-family:arial; font-size:13px;">
					<div style="padding:2px 0; border-bottom:1px solid #eee; margin-bottom:5px; font-size:15px; color:#999"><b>Product Information</b></div>
					<div style="width:50%; float:left; padding:2px 0;"><b>Company Name:</b> <?php echo $row["company_name"];?></div>
					<div style="width:50%; float:left; padding:2px 0;"><b>Sector Details:</b> <?php echo $row["sector"];?></div>
					<div style="width:50%; float:left; padding:2px 0;"><b>Meeting Type:</b> <?php echo $row["meeting_type"];?></div>
					<div style="width:50%; float:left; padding:2px 0;"><b>Market Capitalization:</b> <?php echo $row["market_capitalization"];?></div>
					<div style="width:50%; float:left; padding:2px 0;"><b>Meeting Date:</b> <?php echo $row["meeting_date"];?></div>
					<div style="width:50%; float:left; padding:2px 0;"><b>File Type:</b> PDF</div>
					<div style="clear:both"></div>
				</div>
				<br>
				
				<div style="padding:8px 10px; background:#fff; border:1px solid #dadada; font-family:arial; font-size:13px;" id="billing_form">
					<div style="padding:2px 0; border-bottom:1px solid #eee; margin-bottom:5px; font-size:15px; color:#999"><b>Billing Details</b></div>
					<div style="width:20%; float:left; padding:5px 0;"><b>Name:</b></div>
					<div style="width:80%; float:left; padding:2px 0;"><input type="text" name="name" id="name" style="padding: 5px;"/> <span id="nameInfo"></span></div>
					<div style="clear:both"></div>
					
					<div style="width:20%; float:left; padding:5px 0;"><b>E-mail:</b></div>
					<div style="width:80%; float:left; padding:2px 0;"><input type="text" name="email" id="email" style="padding: 5px;"/><span id="emailInfo"></span></div>
					<div style="clear:both"></div>
					
					<div style="width:20%; float:left; padding:5px 0;"><b>Mobile Number:</b></div>
					<div style="width:80%; float:left; padding:2px 0;"><input type="text" name="contact" id="contact" style="padding: 5px;"/><span id="contactInfo"></span></div>
					<div style="clear:both"></div>
					
					
				</div>
				<br>
				<div style="padding:8px 10px; background:#fff; border:1px solid #dadada; font-family:arial; font-size:13px;">
					
					<div style="width:50%; float:left; padding:5px 0; font-size:20px; color:#920C0A"><b>Price: <?php echo $row["price"];?></b></div>
					<div style="width:50%; float:left; padding:2px 0;" align="right"><input type="submit" value="PROCEED" style="padding:5px 10px;" id="form_submit"></div>
					<div style="clear:both"></div>
					
					
				</div>
				
			 </td>
           </tr>
         </table>
		 </div>
		 </div>
	  </div>
     </form>
      <br clear="all" />
  <script>
	$(document).ready(function()
	{
	
		$('#form_submit').click(function(){
					if(validateName() & validateEmail() & validatePhone() ){
						return true;
					}
					
					else{ 
						return false;
					}
		});
	});
	function validateName() {
		var a = $("#name").val();
		var filter = /[a-zA-Z0-9]{3,}/;
		//if it's valid name
		if(filter.test(a)){
			$("#person_email").removeClass("sys_error");
			$("#nameInfo").text("");
			return true;
		}
		//if it's NOT valid
		else{
			$("#person_email").addClass("sys_error");
			$("#nameInfo").text(" Name should be more than 3 letters");
			return false;
		}
	}
	
	function validateEmail() {
		var a = $("#email").val();
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
		//if it's valid email
		if(filter.test(a)){
			$("#email").removeClass("sys_error");
			$("#emailInfo").text("");
			return true;
		}
		//if it's NOT valid
		else{
			$("#email").addClass("sys_error");
			$("#emailInfo").text(" Type a valid e-mail please");
			return false;
		}
	}
	function validatePhone() {
		var a = $("#contact").val();
		var filter = /\+?(\d{2}|0)?-?\d{10}/;
		//if it's valid email
		if(filter.test(a)){
			$("#contact").removeClass("sys_error");
			$("#contactInfo").text("");
			return true;
		}
		//if it's NOT valid
		else{
			$("#contact").addClass("sys_error");
			$("#contactInfo").text(" Correct number format ( eg. 9876411092, 09876411092, +72-9876411092");
			return false;
		}
	}
  </script>
      
  
