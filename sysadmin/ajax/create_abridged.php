<?php session_start();
//require_once('../../sysauth.php');
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

$base_font_size='15';
$meeting_types_full = array("","AGM", "EGM", "Postal Ballot","CCM");

$types_res_os_short = array("","O","S");


$line_height='1.5';

$report_id = mysql_real_escape_string($_GET["report_id"]);
$query = mysql_query("SELECT companies.com_name,companies.com_bse_code, companies.com_nse_sym, companies.com_isin, companies.com_id, proxy_ad.meeting_type, proxy_ad.meeting_date, proxy_ad.year, proxy_ad.evoting_plateform, proxy_ad.evoting_start, proxy_ad.evoting_end, proxy_ad.meeting_time, proxy_ad.meeting_venue, proxy_ad.notice, proxy_ad.notice_link, proxy_ad.annual_report, companies.com_address, companies.com_telephone, companies.com_sec_email, companies.com_website from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id='$report_id' limit 1 ");
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
    #footer { position: fixed; left: 0px; top:0; height:900px; z-index:100;  }
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
		width:750px;
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
	#firstpage{
		margin-top: -140px;
		margin-left:-30px;
		background:#FFF;
		width:820px;
		height:1060px;
		z-index:100;
		position:absolute;
	}
	#firstpage table{
		width:820px;
	}

	.rotated_vertical {
		position:absolute;
		top:300px;
		left:560px;
	    -webkit-transform:rotate(270deg);
	    -moz-transform:rotate(270deg);
	    -ms-transform:rotate(270deg);
	    -o-transform:rotate(270deg);
	    transform:rotate(270deg);
	    transform-origin: 50%;
	    width: 700px;
	    height:300px;
	    text-transform:uppercase;
	    color:#999;
	}
	</style>
	</head>
	<body>
		<div id="header">
			<table style=" border-bottom:2px solid #eee">
				<tr>
					<td><img src="../../logo.jpg" style="opacity:0.7"></td>
					<td style="text-align:right; color:#888">
						<h1>'.$row_comp["com_name"].'</h1>
						<span>'.$row_comp["com_website"].'</span>
					</td>
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
			<div class="rotated_vertical">Proxy Advisory Report | For Limited Circulation | Report Release Date : '.date("d-m-y", strtotime("now")).' </div>
			 <table style=" border-top:2px solid #eee; margin-top:820px">
				<tr>
					<td style="width:50px;"><img src="../../logo_small.jpg" style="opacity:0.7"></td>
					<td style="width:450px;">&copy; 2012 | Stakeholders Empowerment Services | All Rights Reserved</td>
					<td style="text-align:right; color:#888"> <span class="page" style="font-size:15px"> |&nbsp;<span style="font-size:12px">PAGE</span></span></td>
				</tr>
			</table>
		</div>

		<div id="firstpage">
			<table cellspacing="0">
				<tr>
					<td style="width:500px;" >
						<div style="height:450px; width:500px;" align="right">
							<img src="../../hands.png" style="margin-top:50px;">
						</div>
					</td>
					<td style="text-align:right; color:#888; background:#EB641B"></td>
				</tr>
				<tr>
					<td style="width:500px;">
						<div style="height:600px; width:500px; padding:5px; line-height: 1.7; font-size:16px" align="right">
							BSE Code: '.$row_comp["com_bse_code"].' | NSE Code: '.$row_comp["com_nse_sym"].' | ISIN : '.$row_comp["com_isin"].'<br>
							Sector:[]<br>
							Type: '.$meeting_types_full[$row_comp["meeting_type"]].'<br>
							e-Voting Platform: <a href="'.$row_comp["evoting_plateform"].'">'.$row_comp["evoting_plateform"].'</a><br>';
							if($row_comp["evoting_start"] && $row_comp["evoting_end"]):
								$str .= 'e-Voting Period: From '.date("d M y", $row_comp["evoting_start"]).' To '.date("d M y", $row_comp["evoting_end"]).'<br>';
							endif;
							if($row_comp["meeting_type"] != 3):
								$str .= 'Meeting Date: '.date("d M y", $row_comp["meeting_date"]);
								$str .= ($row_comp["meeting_time"])?' at '.$row_comp["meeting_time"]:'';
								$str .= '<br>';
							endif;
							$str .= ($row_comp["meeting_venue"])?'Meeting Venue: '.$row_comp["meeting_venue"].'<br>':'';
							$str .= '
							Notice:  <a href="'.$row_comp["notice_link"].'">Click Here</a> | Annual Report:  <a href="'.$row_comp["annual_report"].'">Click Here</a><br>
							Company Email: '.$row_comp["com_sec_email"].'<br>
							Phone: '.$row_comp["com_telephone"].' | Fax: []<br>
							Company Registered Office:<br>'.$row_comp["com_address"].'
						</div>
					</td>
					<td style="text-align:right; color:#888; background:#EB641B"></td>
				</tr>
			</table>
			<div style="position:absolute; top:290px; width:600px; background:#7F7F7F; padding:0px 40px; height:140px; color:#FFF; font-size:40px; line-height:1.5; border:1px solid #FFF; border-left:0" align="right">
				Proxy Advisory Report<br>'.$row_comp["com_name"].'
				
			</div>
			<div style="position:absolute; top:950px; width:500px; height:140px; font-size:14px; line-height:1.5; border:1px solid #FFF; border-left:0" align="right">
				<table style="width:500px; color:#888">
					<tr>
						<td style="padding:0 30px; border-right:3px solid #888; width:200px; line-height:1.2; font-weight:bold;">
							Proxy Advisory<br>
							Corporate Governance Research<br>
							Corporate Governance Scores<br>
							Stakeholders\' Education
						</td>
						<td style="padding:0 15px;">
							<img src="../../logo2.png" style="opacity:0.8">
						</td>
					</tr>
				</table>
			</div>
		</div>
		<p style="page-break-before: always;"></p>
';


$str .= '<div class="rest_page">
<div style="width:400px; background:#EB641B; padding:10px 40px; color:#FFF; font-size:24px; line-height:1; margin-left:420px; text-transform:uppercase">
	<span style="font-size:30px">SES R</span>ecommendations
</div>
<div style="margin:10px 0 0 0; padding:10px 0 0 0; border-top:1px solid #000; font-size:14px; line-height:1; text-transform:uppercase">
	<span style="font-size:16px">T</span>ABLE 1 - <span style="font-size:16px">A</span>GENDA <span style="font-size:16px">I</span>TEMS AND <span style="font-size:16px">R</span>ECOMMENDATIONS
</div>

<table style="">
	<tr style="background:#464646; color:#fff"><td class="center">S. No.</td><td>Resolution</td><td class="center">O/S</td><td class="center">Recommendation</td><td class="center">Focus</td></tr>
';
$count =0;
$query = mysql_query("SELECT voting.resolution_number, voting.resolution_name, ses_recos.reco, voting.type_res_os from voting inner join ses_recos on voting.ses_reco = ses_recos.id where voting.report_id='$report_id' order by voting.resolution_number asc ");
while ($row = mysql_fetch_array($query)) {
	$str .= '<tr class="';
	$str .= ($count%2 == 0)?'light':'dark';
	$str .= '">
		<td class="center" style="width:40px">'.$row["resolution_number"].'</td>
		<td>'.$row["resolution_name"].'</td>
		<td class="center" style="width:50px">'.$types_res_os_short[$row["type_res_os"]].'</td>
		<td class="center" style="width:100px">'.$row["reco"].'</td>
		<td class="center" style="width:80px">'.$types_res_os[$row["type_res_os"]].'</td>
	</tr>';
	$count++;
}
$str .= '</table>
	<div style="margin:5px 0 0 60px; font-size:14px; line-height:1;">
		<i>O - Ordinary Resolution; S - Special Resolution</i>
	</div>
	<div style="margin:15px 0 0 0px; font-size:14px; line-height:1.5; text-align:justify">
		<b><i>C - Compliance:</i></b> The Company has not met statutory compliance requirements.<br>
		<b><i>F - Fairness: </i></b> The Company has proposed steps which may lead to undue advantage of a particular class of shareholders and can have adverse impact on non-controlling shareholders including minority shareholders<br>
		<b><i>G - Governance: </i></b> SES questions the governance practices of the Company. The Company may have complied with the statutory requirements in letter. However, SES finds governance issues as per its standards.<br>
		<b><i>T- Disclosures & Transparency: </i></b> The Company has not made adequate disclosures necessary for shareholders to make an informed decision. The Company has intentionally or unintentionally kept the shareholders in dark.<br>
	</div>
	<div style="margin:15px 0 0 0px; font-size:14px; line-height:1.5; text-align:justify">
		<b>EXPLANATION</b><br>
		In view of the fact that E-Voting neither has any scope of interaction of shareholders with the management, nor there is any possibility for amendment of resolution and management cannot explain its rationale any further than what is provided in Notice, therefore to ease decision making and e-voting process for the users of the reports SES has discontinued using recommendations such as -MODIFY, SPLIT, WITHDRAW and CONDITIONAL FOR/ AGAINST. Henceforth SES will give only FOR or AGAINST recommendation. However in Analysis section of the Report, SES will continue to analyse and indicate any of the discontinued recommendations subject to further disclosures etc. This will enable the companies to draft the future notices in a manner which will give relevant information to shareholders to take a considered decision.

	</div>
	<p style="page-break-before: always;"></p>

';

$str .= '<div style="width:300px; background:#EB641B; padding:10px 40px; color:#FFF; font-size:24px; line-height:1; margin-left:520px; text-transform:uppercase">
	<span style="font-size:30px">SES C</span>omments
</div>';

$query = mysql_query("SELECT resolution_number, resolution_name, detail from voting where report_id='$report_id' order by resolution_number asc ");
while ($row = mysql_fetch_array($query)) {
	$str .= '<p><b>Resolution #'.$row["resolution_number"].':'.$row["resolution_name"].'</b><br>
		'.$row["detail"].'</p>';
}

$str .= '<p style="page-break-before: always;"></p></div>
	
	<table style="line-height:1.2; text-align:justify">
		<tr class="light">
			<td colspan="2">DISCLAIMERS</td>
		</tr>
		<tr >
			<td style="widht:500px; padding:0">
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
			<td style="widht:270px; vertical-align:top; padding:0">
				<div style="width:100%; padding:2px 5px; font-weight:bold;" class="light" align="center">Company Information</div>
				<div align="center" style="width:100%; padding:2px 5px;"">
					<img src="../../logo.jpg" style="margin:10px 0"><br>
					<span style="font-size:13px">SEBI Reg. No. INH000000016</span>
					<a href="www.sesgovernance.com">www.sesgovernance.com</a><br><br>
				</div>
				<div style="width:100%; padding:2px 5px; text-align:justify">
					<span style="text-align:justify">This report or any portion hereof may not be reprinted, sold, reproduced or redistributed without the written consent of Stakeholders Empowerment Services</span>
				</div>
				<div style="width:100%; padding:2px 5px;margin-top:10px; font-weight:bold;" class="light" align="center">Contact Information</div>
				<div style="width:100%; padding:10px 5px; text-align:center">
					<span style="font-size:16px"><b>Stakeholders Empowerment Services</b></span><br>
					A 202, Muktangan,<br>Upper Govind Nagar,<br>Malad East,<br>Mumbai, 400097<br>
					Tel +91 22 4022 0322<br>
				</div>
				<div style="width:100%; padding:2px 5px" align="center">
					<a href="mailto:research@sesgovernance.com">research@sesgovernance.com</a><br>
					<a href="mailto:info@sesgovernance.com">info@sesgovernance.com</a><br>
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
  $dompdf->stream("hello_world.pdf",array('Attachment'=>0));
  //$dompdf->stream();
 //  $pdf = $dompdf->output();

	// $name = substr(str_shuffle(strtotime("now")), 0, 10).name_filter($row_comp["com_name"]).'_SES Proxy Advisory Report Abridged_'.$meeting_types[$row_comp["meeting_type"]].' '.date("d-M-y",$row_comp["meeting_date"]).'.pdf';


	// file_put_contents(dirname(__FILE__).'/../../abridged_reports/'.$name, $pdf);
	
	// if(mysql_query("UPDATE proxy_ad set abridged_report='$name' where id='".$report_id."' ")){
 //    	echo 'success';
 //   }

?>