<?php session_start();
require_once('../../sysauth.php');
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

$query = mysql_query("SELECT companies.com_name, companies.com_id, proxy_ad.meeting_type, proxy_ad.meeting_date, proxy_ad.year from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id='$report_id' limit 1 ");
$row_comp = mysql_fetch_array($query);

$base_font_size='15';

$line_height='1.5';

$report_id = mysql_real_escape_string($_POST["report_id"]);
$query = mysql_query("SELECT companies.com_name, companies.com_id, proxy_ad.meeting_type, proxy_ad.meeting_date, proxy_ad.year from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id='$report_id' limit 1 ");
$row_comp = mysql_fetch_array($query);

$str ='';
	$str = $str.'<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<style type="text/css">
	@page {
	margin: 140px auto 120px 30px;
	}
	
	#header { position: fixed; left: 0px; top: -120px; right: 0px; height: 150px;  }
    #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px;  }
    #footer .page:before { content: counter(page); }

	body {
	
			font-family:Arial;
			font-size:'.$base_font_size.'px;
			line-height:1.5;
			
	}
	
	  h2,  h3,  h4, h5, h6{
		font-size:'.($base_font_size+5).'px;
		margin:0;
		padding:0
	}

	  h1{
		font-size:'.($base_font_size+15).'px;
		margin:0;
		padding:0
	}

	 table, div, p {
		width:760px;
	}
	 tr.head {
		background:#ddd;
	}
	 td,th{
		margin:0;
		
	}
	 th {
		font-size:'.($base_font_size+1).'px;
		text-transform:uppercase;
		text-align:left;
	}
	 td.right {
		text-align:right;
	}
	 td.left {
		text-align:left;
	}
	td.center {
		text-align:center;
	}
	td{
		padding:4px 5px;
	}
	.dark{
		background:#d9d9d9;
	}
	.light{
		background:#f2f2f2;
	}
	p{
		text-align:justify;
		line-height:'.$line_height.'
	}
	a {
		color:#ff8119;
	}
	</style>
	</head>
	<body>
	<div id="header">
	<table style=" border-bottom:2px solid #eee">
		<tr>
			<td><img src="../../logo.jpg" style="opacity:0.7"></td>
			<td style="text-align:right; color:#888"><h1>'.$row_comp["com_name"].'</h1></td>
		</tr>
	</table>
	<table style=" ">
		<tr>
			<td style="color:#888">Meeting Type: '.$meeting_types[$row_comp["meeting_type"]].'</td>
			<td style="text-align:right; color:#888">Meeting Date: '.date("d M y", $row_comp["meeting_date"]).'</td>
		</tr>
	</table>
    	
	 </div>

	  <div id="footer">
		 <table style=" border-top:2px solid #eee">
			<tr>
				<td style="width:50px;"><img src="../../logo_small.jpg" style="opacity:0.7"></td>
				<td style="width:450px;">&copy; 2012 | Stakeholders Empowerment Services | All Rights Reserved</td>
				<td style="text-align:right; color:#888"> <span class="page" style="font-size:15px"> |&nbsp;<span style="font-size:12px">PAGE</span></span></td>
			</tr>
		</table>
	   
	  </div>

	
';


$str .= '<table style="">
	<tr style="background:#464646; color:#fff"><td class="center">S. No.</td><td>Resolution</td><td class="center">Recommendation</td></tr>
';
$count =0;
$query = mysql_query("SELECT voting.resolution_number, voting.resolution_name, ses_recos.reco from voting inner join ses_recos on voting.ses_reco = ses_recos.id where voting.report_id='$report_id' order by voting.resolution_number asc ");
while ($row = mysql_fetch_array($query)) {
	$str .= '<tr class="';
	$str .= ($count%2 == 0)?'light':'dark';
	$str .= '">
		<td class="center" style="width:40px">'.$row["resolution_number"].'</td>
		<td>'.$row["resolution_name"].'</td>
		<td class="center" style="width:200px">'.$row["reco"].'</td>
	</tr>';
	$count++;
}
$str .= '</table>';

$str .= '<div style="margin-top:15px; border-top:1px solid #888;border-bottom:1px solid #888;padding:5px 0; color:#555"><h2>Comments</h2></div>';

$query = mysql_query("SELECT resolution_name, detail from voting where report_id='$report_id' order by resolution_number asc ");
while ($row = mysql_fetch_array($query)) {
	$str .= '<p><b>'.$row["resolution_name"].'</b>: 
		'.$row["detail"].'
		'.$row["reco"].'</p>';
}
$str .= '<p style="page-break-before: always;"></p>
	
	<table >
		<tr class="light">
			<td>DISCLAIMERS</td>
		</tr>
	</table>

	<table style="line-height:1.2; text-align:justify">
		<tr >
			<td style="widht:550px; padding:0">
				<div style="width:550px; padding:2px 5px" class="light">Sources</div>
				<div style="width:100%; padding:2px 5px">Only publicly available data has been used while making the report. Our data sources include: BSE, NSE, SEBI, Capitaline, Moneycontrol, Businessweek, Reuters, Annual Reports, IPO Documents and Company Website.</div>

				<div style="width:550px; padding:2px 5px; margin-top:10px;" class="light">Analyst Certification</div>
				<div style="width:100%; padding:2px 5px">The analysts involved in development of this report certify that no part of any of the research analyst’s compensation was, is, or will be directly or indirectly related to the specific recommendations or views expressed by the research analyst(s) in this report.</div>

				<div style="width:550px; padding:2px 5px; margin-top:10px;" class="light">CAUTIONARY STATEMENT</div>
				<div style="width:100%; padding:2px 5px">The recommendations made by SES are based on publicly available information and conform to SES\'s stated Proxy-Advisory Guidelines. Further, SES analysis is recommendatory in nature. SES understands the different investment needs of our clients. Therefore, SES expects that the clients will evaluate the effect of their vote on their investments independently and diligently and will vote accordingly. Subscribers may also carry out an impact analysis of their votes and keep the same as an addendum for their records. In our opinion, Institutional investors are positioned significantly differently from other shareholders due to their ability to engage the board and the management to bring out desired result. As a firm, it is our endeavour to improve the level of corporate governance while not causing any disruption in company\'s proceedings and therefore we respect the independence of investors to choose alternate methods to achieve similar results.</div>

				<div style="width:550px; padding:2px 5px; margin-top:10px;" class="light">Disclaimer</div>
				<div style="width:100%; padding:2px 5px">While SES has made every effort and has exercised due skill, care and diligence in compiling this report based on publicly available information, it neither guarantees its accuracy, completeness or usefulness, nor assumes any liability whatsoever for any consequence from its use.  This report does not have any approval, express or implied, from any authority, nor is it required to have such approval.  The users are strongly advised to exercise due diligence while using this report.<br<br>
This report in no manner constitutes an offer, solicitation or advice to buy or sell securities, nor solicits votes or proxies on behalf of any party. SES, which is a not-for-profit Initiative or its staff, has no financial interest in the companies covered in this report except what is disclosed on its website.<br<br>
The report is released in India and SES has ensured that it is in accordance with Indian laws. Person resident outside India shall ensure that laws in their country are not violated while using this report; SES shall not be responsible for any such violation.
All disputes subject to jurisdiction of High Court of Bombay, Mumbai</div>

			</td>
			<td style="widht:250px; vertical-align:top; padding:0">
				<div style="width:100%; padding:2px 5px" class="light">Contact Information</div>
				<div style="width:100%; padding:2px 5px">
				<a href="mailto:research@sesgovernance.com">research@sesgovernance.com</a><br>
				<a href="mailto:info@sesgovernance.com">info@sesgovernance.com</a><br>
				+91 22 4022 0322</div>
				<div style="width:100%; padding:2px 5px;margin-top:10px;" class="light">Company Information</div>
				<div style="width:100%; padding:2px 5px; text-align:center">
					Stakeholders Empowerment Services<br><br>
					A 202, Muktangan, Upper Govind Nagar, Malad East, Mumbai, 400097<br><br>
					Tel +91 22 4022 0322<br><br>
					<img src="../../logo.jpg" style="margin:5px 0"><br>
					<a href="www.sesgovernance.com">www.sesgovernance.com</a><br><br>
				</div>
				<div style="width:100%; padding:2px 5px; text-align:justify">
										<span style="text-align:justify">This report or any portion hereof may not be reprinted, sold, reproduced or redistributed without the written consent of Stakeholders Empowerment Services</span>
				</div>
			</td>
		</tr>
	</table>


	</body>
	</html>';

	//echo $str;
?>
<?php

require_once("../../dompdf/dompdf_config.inc.php");

$is_local = true;

  if ( get_magic_quotes_gpc() )
    $str= stripslashes($str);
  
  $str = utf8_decode($str);
  $dompdf = new DOMPDF();
  $dompdf->load_html($str);
  $dompdf->set_paper('letter', 'portrait');
  $dompdf->render();
  $pdf = $dompdf->output();

	$name = substr(str_shuffle(strtotime("now")), 0, 10).name_filter($row_comp["com_name"]).'_SES Proxy Advisory Report Abridged_'.$meeting_types[$row_comp["meeting_type"]].' '.date("d-M-y",$row_comp["meeting_date"]).'.pdf';


	file_put_contents(dirname(__FILE__).'/../../abridged_reports/'.$name, $pdf);
	
	if(mysql_query("UPDATE proxy_ad set abridged_report='$name' where id='".$report_id."' ")){
    	echo 'success';
   }

?>