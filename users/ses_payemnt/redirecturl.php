<?php define('IN_SCRIPT',1);
define('ADMIN_PATH','../');
include('functions/config.php');
@session_start();
ob_start();


error_reporting(E_ALL & ~E_NOTICE);
	require("libfuncs.php");
	
	$WorkingKey = "mt4v6pywhsy947rk4eiygxsshxzwraek";
	$MerchantId = $_REQUEST["Merchant_Id"];
	$OrderId = $_REQUEST["Order_Id"];
	$Amount = $_REQUEST["Amount"];
	$AuthDesc =$_REQUEST["AuthDesc"];
	$avnChecksum =$_REQUEST["Checksum"];
	$Checksum = verifyChecksum($MerchantId, $OrderId, $Amount, $AuthDesc, $WorkingKey, $avnChecksum);
	
	$billing_cust_name = $_REQUEST["billing_cust_name"];
	$billing_cust_email = $_REQUEST["billing_cust_email"];
	//echo $billing_cust_email;
	
	$res_order = mysql_query("select * from payment where order_id='$OrderId'");
	$order = mysql_fetch_array($res_order);
	
	if(mysql_num_rows($res_order) > 0)
		{
			$res = mysql_query("select * from tbladvisory where advisory_id='$order[advisory_id]'");
			//$res = mysql_query("select * from tbladvisory where advisory_id='1'");
			$row= mysql_fetch_array($res);
		}
	
?>

<html>
<head>
<title>Secure Zone|| SES Governance</title>
<script type="text/javascript" src="javascript/jquery-min.js"></script>
</head>

<body>
<div align="center">
<div style="margin-top:5%">
	<img src="logo.png">
	<div style="margin-top:20px">
	<img src="362.gif"><br>
	<h1>processing your order</h1>
	</div>
</div>
	<?php 
	
	$success= 'no';
	if($Checksum && $AuthDesc==="Y")
	{
		mysql_query("update payment set status='1' where order_id='$OrderId' "); // update status to 1 YES
		$success = 'yes';
		
	}
	else if($Checksum && $AuthDesc==="B")
	{
		mysql_query("update payment set status='2' where order_id='$OrderId' "); // update status to 2 YES
		$success ='yes';
	}
	else if($Checksum && $AuthDesc==="N")
	{
		mysql_query("update payment set status='-1' where order_id='$OrderId' "); // update status to -1 NO
		$success='dec';
	}
	else
	{
		echo "<br>Security Error. Illegal access detected";
	}	
	
if($success== 'yes')
{

////////////////MAIL
require_once('mail/class.phpmailer.php');

$mail = new PHPMailer();

$mail->IsSMTP();

$mail->SMTPAuth   = true; 

$mail->SMTPSecure = "tls"; 

$mail->Host       = "email-smtp.us-east-1.amazonaws.com";

$mail->Username   = "AKIAID7CBUQKCREFMSBQ";

$mail->Password   = "AvphiYmJWkhaQDvZsGEn6Jla1AFBmdVOqi4WnDf6wKdH";

$mail->SetFrom('info@sesgovernance.com', 'sesgovernance.com'); //from (verified email address)

$mail->Subject = "Order Confirmation - Your Order with sesgovernance.com [".$OrderId."] has been successfully placed!"; 

$mail->IsHTML(true);
$body = create_message($billing_cust_name,$OrderId,$row["company_name"],$row["sector"],$row["meeting_type"], $row["market_capitalization"],$row["meeting_date"]);
$body = eregi_replace("[\]",'',$body);
$mail->MsgHTML($body);
$mail->AddAddress($billing_cust_email, $billing_cust_name); 
if ($mail->Send()) { 

	echo "Message has been sent!"; //die; 

}
else { 

	echo "Mailer Error: " . $mail->ErrorInfo; 
} 
}
	?>
</div>
<form action="http://www.sesgovernance.com/thank-you/" method="get" id="my_form">
<input type="hidden" name="o" value="<?php echo base64_encode($OrderId)?>"/>
<input type="hidden" name="n" value="<?php echo base64_encode($billing_cust_name)?>"/>
<input type="hidden" name="e" value="<?php echo base64_encode($billing_cust_email)?>"/>
<input type="hidden" name="suc" value="<?php echo base64_encode($success) ?>"/>
</form>
</body>
<script type="text/javascript">
function myfunc () {
var frm = document.getElementById("my_form");
frm.submit();
}
window.onload = myfunc;
</script>
</html>
<?php

function create_message($billing_cust_name,$OrderId,$c_name,$c_sec,$c_type,$c_mark,$c_date)
{
$message_body = '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rocket Mail</title>
<style type="text/css">
body {
	margin:0;
	padding:0;
	background-color:#fff;
	color:#777777;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	-webkit-text-size-adjust:none;
	-ms-text-size-adjust:none; 
}
h1, h2, h3 {
	color:#555555;
	margin-bottom:15px !important;
}
h4 {
	 color:#777777;
	 margin-bottom:0px !important;
}
a, a:link, a:visited {
	color:#ed7c0d;
	text-decoration:underline;
}
a:hover, a:active {
	text-decoration:none;
	color:#c74611 !important;
}
.phone a {
	text-decoration:none;
}
p {
	margin:0 0 14px 0;
	padding:0;
}
img {
	border:0;
}
table td {
	border-collapse:collapse;
}
td.border_b {
	border-bottom:1px #eeeeee solid;
}
td.border_r {
	border-right:1px #eeeeee solid;
}
td.border_b_r {
	border-bottom:1px #eeeeee solid;
	border-right:1px #eeeeee solid;
}
#pricingTable {
	border-collapse:separate !important;
}
.highlighted {
	color:#ed7c0d;
}

/*Hotmail and Yahoo specific code*/
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}
.yshortcuts {color: #777777;}
.yshortcuts a span {color: #777777; border-bottom: none !important; background: none !important; text-decoration:none !important;}
/*Hotmail and Yahoo specific code*/

</style>

<!--[if gte mso 9]>
<style type="text/css">
#pageContainer {
	background-color:transparent !important;
}

</style>
<![endif]-->
</head>

<body >

<table id="pageContainer" width="100%" align="center" background="http://sesgovernance.com/images/bg_mail.jpg" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; text-align:left; background-repeat:repeat; background-color:#fff;">
    <tr>
        <td style="padding-top:30px; padding-bottom:30px;">
			
            <!-- Start of view online link -->
            <table width="600" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse; font-family:Arial, Helvetica, sans-serif; font-size:11px; line-height:100%; color:#777777;">
                <tr>
                    <td style="padding-bottom:10px; font-family:Arial, Helvetica, sans-serif; font-size:11px; line-height:100%; color:#777777; text-align:right;">
                        
                    </td>
                </tr>
            </table>
        	<!-- End of view online link -->
            
            <!-- Start of logo, phone and banner container -->
            <table bgcolor="#ffffff" width="600" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse; text-align:left; font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:12px; line-height:15pt; color:#777777;">
                <tr>
                    <td width="270" valign="middle" style="padding-top:35px; padding-left:30px; padding-bottom:35px; font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:15pt; color:#ed7c0d; border-top:1px solid #dddddd;">
                        <img alt="Logo" src="http://www.sesgovernance.com/wp-content/uploads/2012/08/xlogo.png.pagespeed.ic.i7R9n0k8N9.png" align="left" border="0" vspace="0" hspace="0" style="display:block; width:180px" />
                    </td>
                    <td width="270" valign="middle" style="padding-top:25px; padding-right:30px; padding-bottom:25px; font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:15pt; color:#777777; text-align:right; border-top:1px solid #dddddd;">
                       <h4 class="phone" style="font-family:\'Segoe UI\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size:24px; line-height:100%; font-weight:300; color:#777777; margin-top:0; margin-bottom:0 !important; padding:0;">+91 22 4022 0322</h4>
                    </td>
                </tr>
                <tr>
                    <td width="600" colspan="2" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:20px; line-height:100%; text-align:center; color:#ed7c0d;">Order Confirmed!!</td>
                </tr>
            </table>
        	<!-- End of logo, phone and banner container -->
                        
            <!-- Start of letter content -->
            <table bgcolor="#ffffff" width="600" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse; text-align:left; font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:12px; line-height:15pt; color:#777777;">
               
                <tr>
                    <td style="padding-right:30px; padding-left:30px; padding-top:20px; font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:15pt; color:#222; text-align:justify;">
                        
                        Dear '.$billing_cust_name.',<br><br>
						Greetings from SES Governance!<br>
						We thank you for your order. This email contains your order summary.<br>
						Please find below the summary of your order <b>'.$OrderId.'</b> at sesgovernance.com:
                        
                    </td>
                </tr>
                <tr>
                    <td width="600" colspan="2" height="31" style="padding:25px 5px 5px 5px; font-size:2px;"><div style="padding:8px 10px; background:#fff; border:1px solid #dadada; font-family:arial; font-size:13px;">
					<div style="padding:2px 0; border-bottom:1px solid #eee; margin-bottom:5px; font-size:15px; color:#999"><b>Product Information</b></div>
					<div style="width:50%; float:left; padding:2px 0;"><b>Company Name:</b> '.$c_name.'</div>
					<div style="width:50%; float:left; padding:2px 0;"><b>Sector Details:</b> '.$c_sec.'</div>
					<div style="width:50%; float:left; padding:2px 0;"><b>Meeting Type:</b> '.$c_type.'</div>
					<div style="width:50%; float:left; padding:2px 0;"><b>Market Capitalization:</b> '.$c_mark.'</div>
					<div style="width:50%; float:left; padding:2px 0;"><b>Meeting Date:</b> '.$c_date.'</div>
					<div style="width:50%; float:left; padding:2px 0;"><b>File Type:</b> PDF</div>
					<div style="clear:both"></div>
					
					<div style="margin:15px 0 5px 0; font-size:11px;" align="center">

					<a href="http://sesgovernance.com/pdffile.php?order_id='.$OrderId.'" target="_blank" style="-moz-box-shadow:inset 0px 1px 0px 0px #fff6af;
	-webkit-box-shadow:inset 0px 1px 0px 0px #fff6af;
	box-shadow:inset 0px 1px 0px 0px #fff6af;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ffec64), color-stop(1, #ffab23) );
	background:-moz-linear-gradient( center top, #ffec64 5%, #ffab23 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#ffec64\', endColorstr=\'#ffab23\');
	background-color:#ffec64;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #ffaa22;
	display:inline-block;
	color:#333333;
	font-family:arial;
	font-size:15px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:1px 1px 0px #ffee66;">Download Link</a>
					<br><br>
					<span>You can use this link to download the file 5 times only. It is advised to save the file.</span>
					</div>
					
					
				</div></td>
                </tr>
				
            </table>
            <!-- End of letter content -->
            
           

            <!-- Start of footer -->
            <table width="640" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse; text-align:left; font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:12px; line-height:15pt; color:#777777;">
                <tr>
                    <td width="20" height="95" rowspan="2" valign="bottom" style="font-size:2px; line-height:0px;"><img alt="" src="http://sesgovernance.com/images/footerLeftShadow.png" height="95" width="20" align="right" border="0" vspace="0" hspace="0" style="display:block;" /></td>
                    <td width="30" rowspan="2" bgcolor="#ffffff"><img alt="" height="10" src="http://sesgovernance.com/images/blank.gif" width="30" align="left" vspace="0" hspace="0" border="0" style="display:block;" /></td>
                    <td width="330" height="10" bgcolor="#ffffff"><img alt="" height="10" src="http://sesgovernance.com/images/blank.gif" width="30" align="left" vspace="0" hspace="0" border="0" style="display:block;" /></td>
                    <td width="30" rowspan="2" bgcolor="#ffffff"><img alt="" height="10" src="http://sesgovernance.com/images/blank.gif" width="30" align="left" vspace="0" hspace="0" border="0" style="display:block;" /></td>
                    <td width="180" height="10" bgcolor="#ffffff"><img alt="" height="10" src="http://sesgovernance.com/images/blank.gif" width="30" align="left" vspace="0" hspace="0" border="0" style="display:block;" /></td>
                    <td width="30" rowspan="2" bgcolor="#ffffff"><img alt="" height="10" src="http://sesgovernance.com/images/blank.gif" width="30" align="left" vspace="0" hspace="0" border="0" style="display:block;" /></td>
                    <td width="20" height="95" rowspan="2" valign="bottom" style="font-size:2px; line-height:0px;"><img alt="" src="http://sesgovernance.com/images/footerRightShadow.png" height="95" width="20" align="left" border="0" vspace="0" hspace="0" style="display:block;" /></td>
                </tr>
                <tr>
                    <td width="330" bgcolor="#ffffff" valign="middle" style="font-family:\'Segoe UI\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size:24px; line-height:25pt; color:#ed7c0d; font-weight:300;">
                        
                        <p style="margin-top:0; margin-bottom:13px !important; padding:0;">
                        	<a style="text-decoration:none; color:#ed7c0d;" href="http://sesgovernance.com">www.sesgovernance.com</a>
                        </p>
                        
                    </td>
                    <td width="180" bgcolor="#ffffff" valign="middle" style="text-align:right;">
						<p style="margin-top:0; margin-bottom:3px; padding:0;">
                           
                    	</p>
                    </td>
                </tr>
                <tr>
                    <td width="640" colspan="7" height="45" valign="top" style="font-size:2px; line-height:0px;"><img alt="" src="http://sesgovernance.com/images/footerBottom.png" height="45" width="640" align="left" border="0" vspace="0" hspace="0" style="display:block;" /></td>
                </tr>
                <tr>
                	<td width="600" colspan="7" style="padding-left:20px; padding-right:20px; font-family:Arial, Helvetica, sans-serif; font-size:11px; line-height:13pt; color:#777777; text-align:center;">
                        
                        Copyright <img alt="©" src="http://sesgovernance.com/images/copyright.png" border="0" height="10" width="10" /> 2013 <a style="text-decoration:underline; color:#ed7c0d;" href="http://sesgovernance.com">sesgovernance.com</a>, All rights reserved. <br/>
                      
                        
                    </td>
                </tr>
            </table>
            <!-- End of footer -->
            
        </td>
    </tr>
</table>

</body>
</html>
';

return $message_body;
}



