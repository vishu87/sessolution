<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');

$base_font_size='15';

$line_height='1.5';

$report_id = mysql_real_escape_string($_POST["report_id"]);
$self_proxy_id = mysql_real_escape_string($_POST["self_proxy_id"]);
$query = mysql_query("SELECT companies.com_name, companies.com_full_name, proxy_ad.com_id from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id='$report_id' limit 1 ");

$row_comp = mysql_fetch_array($query);

$str ='';
	$str = $str.'<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<style type="text/css">
	@page {
		margin: 50px auto 120px 30px;
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
		border:1px solid #CCC;
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
		
';


$str .= '<div class="rest_page">


<table cellspacing="0">
	<tr >
		<td class="center" style="text-transform:uppercase; font-size:20px;" colspan="6">'.$row_comp["com_full_name"].'</td>
	</tr>
	<tr >
	<td class="center">S. No.</td>
	<td>Scheme</td>
	<td class="center">Total Quantity</td>
	<td class="center">Custodian</td>
	<td class="center">DP ID</td>
	<td class="center">Client ID</td>
	</tr>
';
$count =1;
$query = mysql_query("SELECT schemes.scheme_name, schemes.dp_id, schemes.client_id, scheme_companies.shares_held from schemes join scheme_companies on schemes.id = scheme_companies.scheme_id where scheme_companies.com_id = '$row_comp[com_id]' and schemes.user_id = '$_SESSION[MEM_ID]' ");
while ($row = mysql_fetch_array($query)) {
	$str .= '<tr class="';
	$str .= ($count%2 == 0)?'light':'dark';
	$str .= '">
		<td class="center" style="width:40px">'.$count.'</td>
		<td style="width:400px">'.$row["scheme_name"].'</td>
		<td class="center" >'.$row["shares_held"].'</td>
		<td class="center" ></td>
		<td class="center" >'.$row["dp_id"].'</td>
		<td class="center">'.$row["client_id"].'</td>
	</tr>';
	$count++;
}
$str .= '</table>
	</body>
	</html>';
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

	$name = substr(str_shuffle(strtotime("now")), 0, 10).name_filter($row_comp["com_name"]).'_Holdings.pdf';


	file_put_contents(dirname(__FILE__).'/../../holdings/'.$name, $pdf);
	
	if(mysql_query("UPDATE self_proxies set hodlings_file ='$name' where id='$self_proxy_id' and proxy_id = '$report_id' and user_id = '$_SESSION[MEM_ID]' ")){
    	echo 'success';
   }

?>