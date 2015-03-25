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

<?php
require("libfuncs.php");
$Merchant_Id = "M_inf32399_32399" ;
$Order_Id = getUniqueId();

$Redirect_Url = "http://www.sesgovernance.com/redirecturl.php" ;
//$WorkingKey = "l1jhlh1n1jj0yjj0xf";
$WorkingKey = "mt4v6pywhsy947rk4eiygxsshxzwraek";
$Amount = number_format($row["price"], 2, '.', '');
$Checksum = getCheckSum($Merchant_Id,$Amount,$Order_Id ,$Redirect_Url,$WorkingKey);

$customer_name=$_POST["name"];
//$customer_address=$_POST["address"];
$customer_address="N/A";
$customer_statename="N/A";
$customer_country="N/A";
$customer_contact_no=$_POST["contact"];
$customer_email=$_POST["email"];
$customer_city="N/A";
$billing_zip = "N/A";
$customer_message= "Meeting with ".$row["company_name"]."on ".$row["meeting_date"];;

mysql_query("insert into payment (order_id, advisory_id, name, email, contact, timestamp) values ('$Order_Id', '$row[advisory_id]', '$customer_name', '$customer_email', '$customer_contact_no', NOW())");

$billing_cust_name=$customer_name;
$billing_cust_address=$customer_address;
$billing_cust_state=$customer_statename;
$billing_cust_country=$customer_country;;
$billing_cust_tel=$customer_contact_no;
$billing_cust_email=$customer_email;
$delivery_cust_name=$customer_name;
$delivery_cust_address=$customer_address;
$delivery_cust_state = $customer_statename;
$delivery_cust_country = $customer_country;
$delivery_cust_tel=$customer_contact_no;
$delivery_cust_notes=$customer_message;
$billing_city = $customer_city;
$billing_zip = $customer_zipcode;
$delivery_city = $customer_city;


?>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="javascript/jquery-min.js"></script>
<style>
.tab_select{
border: 1px solid #aaa;
margin-top: -1px;
padding: 20px 50px;}
</style>

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
				
				<div style="padding:8px 10px; background:#fff; border:1px solid #dadada; font-family:arial; font-size:13px;">
					<div style="padding:2px 0; border-bottom:1px solid #eee; margin-bottom:5px; font-size:15px; color:#999"><b>Billing Details</b></div>
					<div style="width:20%; float:left; padding:5px 0;"><b>Name:</b></div>
					<div style="width:50%; float:left; padding:2px 0;"><?php echo $customer_name?></div>
					<div style="clear:both"></div>
					
					<div style="clear:both"></div>
					
					<div style="width:20%; float:left; padding:5px 0;"><b>Mobile Number:</b></div>
					<div style="width:50%; float:left; padding:2px 0;"><?php echo $customer_contact_no?></div>
					<div style="clear:both"></div>
					
					<div style="width:20%; float:left; padding:5px 0;"><b>E-mail:</b></div>
					<div style="width:50%; float:left; padding:2px 0;"><?php echo $customer_email?></div>
					<div style="clear:both"></div>
					
					<div style="width:20%; float:left; padding:5px 0;"><b>Order Id:</b></div>
					<div style="width:50%; float:left; padding:2px 0;"><?php echo $Order_Id?></div>
					<div style="clear:both"></div>
					
					<div style="width:20%; float:left; padding:5px 0;"><b>Date:</b></div>
					<div style="width:50%; float:left; padding:2px 0;"><?php echo date("d, M Y", strtotime("now"))?></div>
					<div style="clear:both"></div>
					<div style="width:50%; float:right; padding:5px 0;" align="right"><INPUT TYPE="button" value="EDIT DETAILS" onclick="javascript: window.history.go(-1)" style="padding:5px 10px;"></div>
					<div style="clear:both"></div>
					
				</div>
				<br>
				<div style="padding:8px 10px; background:#fff; border:1px solid #dadada; font-family:arial; font-size:13px;">
					
					<div style="width:50%; float:left; padding:5px 0; font-size:20px; color:#920C0A"><b>Price: <?php echo $Amount;?></b></div>
					
					<div style="clear:both"></div>
					
					<form method="post" name='frmTrans' action="https://www.ccavenue.com/servlet/new_txn.PaymentIntegration" target="_parent">
					<input type="hidden" name="Merchant_Id" value="<?php echo $Merchant_Id; ?>">
						  <input type="hidden" name="Amount" value="<?php echo $Amount; ?>">
						  <input type="hidden" name="Order_Id" value="<?php echo $Order_Id; ?>">
						  <input type="hidden" name="Redirect_Url" value="<?php echo $Redirect_Url; ?>">
						  <input type="hidden" name="Checksum" value="<?php echo $Checksum; ?>">
						  <input type="hidden" name="billing_cust_name" value="<?php echo $billing_cust_name; ?>">
						  <input type="hidden" name="billing_cust_address" value="<?php echo $billing_cust_address; ?>">
						  <input type="hidden" name="billing_cust_country" value="<?php echo $billing_cust_country; ?>">
						  <input type="hidden" name="billing_cust_state" value="<?php echo $billing_cust_state; ?>">
						  <input type="hidden" name="billing_zip" value="<?php echo $billing_zip; ?>">
						  <input type="hidden" name="billing_cust_tel" value="<?php echo $billing_cust_tel; ?>">
						  <input type="hidden" name="billing_cust_email" value="<?php echo $billing_cust_email; ?>">
						  <input type="hidden" name="delivery_cust_name" value="<?php echo $delivery_cust_name; ?>">
						  <input type="hidden" name="delivery_cust_address" value="<?php echo $delivery_cust_address; ?>">
						  <input type="hidden" name="delivery_cust_country" value="<?php echo $delivery_cust_country; ?>">
						  <input type="hidden" name="delivery_cust_state" value="<?php echo $delivery_cust_state; ?>">
						  <input type="hidden" name="delivery_cust_tel" value="<?php echo $delivery_cust_tel; ?>">
						  <input type="hidden" name="delivery_cust_notes" value="<?php echo $delivery_cust_notes; ?>">
						  <input type="hidden" name="Merchant_Param" value="<?php echo $Merchant_Param; ?>">
						  <input type="hidden" name="billing_cust_city" value="<?php echo $billing_city; ?>">
						  <input type="hidden" name="billing_zip_code" value="<?php echo $billing_zip; ?>">
						  <input type="hidden" name="delivery_cust_city" value="<?php echo $delivery_city; ?>">
						  <input type="hidden" name="delivery_zip_code" value="<?php echo $delivery_zip; ?>">
					<div style="margin-top:10px">
						<div class="tabs tab_payment_active" id="atm_tab">ATM CUM DEBIT CARD</div>
						<div class="tabs tab_payment" id="netbank_tab">NET BANKING</div>
						<div class="tabs tab_payment" id="cashcard_tab">CASH CARD</div>
						<div class="tabs tab_payment" id="creditcard_tab">CREDIT/DEBIT CARD</div>
					</div>
					<div style="clear:both"></div>
					<div style="display:none">
						  <input name='cardOption' type="radio" value='NonMoto' checked/><strong>Pay By ATM-CUM-DEBIT CARD </strong><br>
						  
						  <input name='cardOption' value='netBanking' type="radio" />
			  <strong>Pay Using Your Internet Enabled Bank Account</strong> <br>
			  

						  <input name='cardOption' value='CCRD' type="radio" />
			  <strong>Pay by ITZ CASH CARDS</strong><br>
			 			 					
					</div>
					<div id="tabs_select1" class="tab_select">
						<div id="select_atm" style="float:left">
							<select name="NonMotoCardType" id="atm">	
					<option value='' selected>--Select--</option>



<option value ="CANVIS_N" > Canara Bank Debit Card (ATM PIN)</option>
<option value ="CBIDEB_N" > Citibank Debit Card (IPIN)</option>
<option value ="IOBDB_N" > Indian Overseas Bank Debit Card (ATM PIN)</option>
<option value ="PNBM_N" > Punjab National Bank Debit Card (ATM PIN)</option>
<option value ="UNIDB_N" > Union Bank of India Debit Card (ATM PIN)</option>
<option value ="SBMDB_Y" >State Bank of India Debit Card</option>


							</select>
							<select size="1" name="netBankingCards" id="netbank" style="display:none">
					<option value="" selected="selected">-----------------------Select----------------------</option>
<option value ="AND_N" >Andhra Bank</option>
<option value ="UTI_N" >AXIS Bank</option>
<option value ="BBK_N" >Bank of Bahrain & Kuwait</option>
<option value ="BOBCO_N" >Bank of Baroda Corporate Accounts</option>
<option value ="BOB_N" >Bank of Baroda Retail Accounts</option>
<option value ="BOI_N" >Bank of India</option>
<option value ="BOM_N" >Bank of Maharashtra</option>
<option value ="CAN_N" >Canara Bank</option>
<option value ="CSB_N" >Catholic Syrian Bank</option>
<option value ="CEN_N" >Central Bank of India</option>
<option value ="CBIBAN_N" >Citibank Bank Account Online</option>
<option value ="CITIUB_N" >City Union Bank</option>
<option value ="COP_N" >Corporation Bank</option>
<option value ="DCB_N" >DCB Bank ( Development Credit Bank )</option>
<option value ="DEUNB_N" >Deutsche Bank</option>
<option value ="FDEB_N" >Federal Bank</option>
<option value ="HDEB_N" >HDFC Bank</option>
<option value ="IDBI_N" >IDBI Bank</option>
<option value ="IOB_N" >Indian Overseas Bank</option>
<option value ="NIIB_N" >IndusInd Bank</option>
<option value ="ING_N" >ING Vysya Bank</option>
<option value ="JKB_N" >Jammu & Kashmir Bank</option>
<option value ="KTKB_N" >Karnataka Bank</option>
<option value ="KVB_N" >Karur Vysya Bank</option>
<option value ="NKMB_N" >Kotak Mahindra Bank</option>
<option value ="LVB_N" >Lakshmi Vilas Bank NetBanking</option>
<option value ="OBC_N" >Oriental Bank of Commerce</option>
<option value ="PNBCO_N" >Punjab National Bank Corporate Accounts</option>
<option value ="NPNB_N" >Punjab National Bank Retail Accounts</option>
<option value ="SIB_N" >South Indian Bank</option>
<option value ="SCB_N" >Standard Chartered Bank</option>
<option value ="SBJ_N" >State Bank of Bikaner and Jaipur</option>
<option value ="SBH_N" >State Bank of Hyderabad</option>
<option value ="SBI_N" >State Bank of India</option>
<option value ="SBM_N" >State Bank of Mysore</option>
<option value ="SBP_N" >State Bank of Patiala</option>
<option value ="SBT_N" >State Bank of Travancore</option>
<option value ="SYNBK_N" >Syndicate Bank</option>
<option value ="TNMB_N" >Tamilnad Mercantile Bank</option>
<option value ="UNI_N" >Union Bank of India</option>
<option value ="UBI_N" >United Bank of India</option>
<option value ="VJYA_N" >Vijaya Bank</option>
<option value ="YES_N" >YES Bank</option>

				</select>
							<select size="1" name="CCRDType" id="cashcard" style="display:none">
					<option value=''>--Select--</option>
<option value ="CCI_N" >ICash Card</option>
<option value ="ITZ_N" >Itz Cash / noQ 24x7</option>
<option value ="OXIG_N" >OxiCash Card</option>
<option value ="PCC_N" >PayCash Card</option>

				</select>
				
				
			
						</div>
						<div id="pay_now2" style="float:right">
							<INPUT TYPE="submit" value="PAY NOW" style="padding:5px 10px;" >
						</div>
						<div style="clear:both"></div>
						</form>
					</div>
					<div id="tabs_select2" class="tab_select" style="display:none">
					<form name="paymentform" method="post" action="https://www.ccavenue.com/shopzone/cc_details.jsp" id="cc_form" target="_blank">
					<input type="hidden" name="Merchant_Id" value="<?php echo $Merchant_Id; ?>">
						  <input type="hidden" name="Amount" value="<?php echo $Amount; ?>">
						  <input type="hidden" name="Order_Id" value="<?php echo $Order_Id; ?>">
						  <input type="hidden" name="Redirect_Url" value="<?php echo $Redirect_Url; ?>">
						  <input type="hidden" name="Checksum" value="<?php echo $Checksum; ?>">
						  <input type="hidden" name="billing_cust_name" value="<?php echo $billing_cust_name; ?>">
						  <input type="hidden" name="billing_cust_address" value="<?php echo $billing_cust_address; ?>">
						  <input type="hidden" name="billing_cust_country" value="<?php echo $billing_cust_country; ?>">
						  <input type="hidden" name="billing_cust_state" value="<?php echo $billing_cust_state; ?>">
						  <input type="hidden" name="billing_zip" value="<?php echo $billing_zip; ?>">
						  <input type="hidden" name="billing_cust_tel" value="<?php echo $billing_cust_tel; ?>">
						  <input type="hidden" name="billing_cust_email" value="<?php echo $billing_cust_email; ?>">
						  <input type="hidden" name="delivery_cust_name" value="<?php echo $delivery_cust_name; ?>">
						  <input type="hidden" name="delivery_cust_address" value="<?php echo $delivery_cust_address; ?>">
						  <input type="hidden" name="delivery_cust_country" value="<?php echo $delivery_cust_country; ?>">
						  <input type="hidden" name="delivery_cust_state" value="<?php echo $delivery_cust_state; ?>">
						  <input type="hidden" name="delivery_cust_tel" value="<?php echo $delivery_cust_tel; ?>">
						  <input type="hidden" name="delivery_cust_notes" value="<?php echo $delivery_cust_notes; ?>">
						  <input type="hidden" name="Merchant_Param" value="<?php echo $Merchant_Param; ?>">
						  <input type="hidden" name="billing_cust_city" value="<?php echo $billing_city; ?>">
						  <input type="hidden" name="billing_zip_code" value="<?php echo $billing_zip; ?>">
						  <input type="hidden" name="delivery_cust_city" value="<?php echo $delivery_city; ?>">
						  <input type="hidden" name="delivery_zip_code" value="<?php echo $delivery_zip; ?>">
						  <div id="pay_now" align="center">
						<INPUT TYPE="submit" value="PAY NOW USING CREDIT CARDS/DEBIT CARDS" style="padding:5px 10px;">
						</div>
						<div style="clear:both"></div>
						  </form>
					</div>
					
					
					
					
					
					<div style="clear:both"></div>
					
					
				</div>
				
			 </td>
           </tr>
         </table>
		 </div>
		 </div>
	  </div>

      <br clear="all" />
      
      <script>
	  $(document).ready(function() {
  
		$("#atm_tab").click( function () {
			$(".tabs").removeClass('tab_payment_active').addClass('tab_payment');
			$("#atm_tab").removeClass('tab_payment').addClass('tab_payment_active');
			$('input:radio[name="cardOption"]').filter('[value="NonMoto"]').attr('checked', true);
			$("#tabs_select1").show();
			$("#tabs_select2").hide();
			$("#atm").show();
			$("#netbank").hide();
			$("#cashcard").hide();
			$("#creditcard").hide();
		});
		
		$("#netbank_tab").click( function () {
			$(".tabs").removeClass('tab_payment_active').addClass('tab_payment');
			$("#netbank_tab").removeClass('tab_payment').addClass('tab_payment_active');
			$('input:radio[name="cardOption"]').filter('[value="netBanking"]').attr('checked', true);
			$("#tabs_select1").show();
			$("#tabs_select2").hide();
			$("#atm").hide();
			$("#netbank").show();
			$("#cashcard").hide();
			$("#creditcard").hide();
		});
		
		$("#cashcard_tab").click( function () {
			$(".tabs").removeClass('tab_payment_active').addClass('tab_payment');
			$("#cashcard_tab").removeClass('tab_payment').addClass('tab_payment_active');
			$('input:radio[name="cardOption"]').filter('[value="CCRD"]').attr('checked', true);
			$("#tabs_select1").show();
			$("#tabs_select2").hide();
			$("#atm").hide();
			$("#netbank").hide();
			$("#cashcard").show();
			$("#creditcard").hide();
		});
		
		$("#creditcard_tab").click( function () {
			$(".tabs").removeClass('tab_payment_active').addClass('tab_payment');
			$("#creditcard_tab").removeClass('tab_payment').addClass('tab_payment_active');
			$("#tabs_select2").show();
			$("#tabs_select1").hide();
			$("#atm").hide();
			$("#netbank").hide();
			$("#cashcard").hide();
			$("#creditcard").show();
		});
		
		
		});
	  
	  
      </script>
  
