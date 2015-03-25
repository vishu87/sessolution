<?php define('IN_SCRIPT',1);

define('ADMIN_PATH','../');

include('functions/config.php');

session_start();

ob_start();

error_reporting(E_ALL & ~E_NOTICE);

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="style/style.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" media="screen" href="css.css" />



<script type="text/javascript" src="jquery-1.3.2.js"></script>

<style type="text/css">
#paging_button ul li{height:23px;width:23px;font-size: 14px;
padding-top: 3px;}
.selected
{
	color:white;
}
.selected li:hover
{
	color:white;
}
.selected li
{
	
	margin: 20px;
width: 460px;
height:200px;
list-style: none outside none;
background: none repeat scroll 0 0 #920C0A;
}
li:hover{color:black;}
</style>

<script type="text/javascript">

function Page(test2,per_page,i)

{

var pg=$(this).val();
$('.page').removeClass('selected');
	$('#'+ i).addClass('selected');
var $links = $('#paging_button');

var data='test2='+test2+'&per_page='+per_page+'&i='+i+'&pg='+pg;

$.ajax({



				url: 'research-data.php',



				type: 'POST',



				data: data,



				success: function(data1) 



				{



					   $('#container').html(data1);



					



				   }	



			});



			}

function Page1()
{
	$('#1').addClass('selected');
}
</script>

</head>

<body onload="Page1();">










<?php



$per_page = 5;







$sql = "SELECT * FROM tblresearch WHERE status=1";



$rsd = mysql_query($sql);



$count = mysql_num_rows($rsd);



$pages = ceil($count/$per_page)



?>



<div align="center" style="margin-top: -135px;">



	<script type="text/javascript"><!--



	google_ad_client = "pub-7651803006865066";



	/* 728x90, created 5/13/10 For demos top image */



	google_ad_slot = "6387617802";



	google_ad_width = 728;



	google_ad_height = 90;



	//-->



	



	</script>



	<script type="text/javascript"



	src="http://pagead2.googlesyndication.com/pagead/show_ads.js">



	</script>



	



	



	<div id="container">







	  <div id="content" style="height:auto">



        <?php



$from = 1;		



$per_page = 6;



$sqlc = "SELECT * FROM tblresearch WHERE status=1";



$rsdc = mysql_query($sqlc);



$cols = mysql_num_rows($rsdc);



$test1=$from*$per_page;



$test2=$cols-$test1;



$page = $_REQUEST['page'];



$sql = "SELECT * FROM tblresearch WHERE status=1 ORDER BY insert_date DESC LIMIT 6";



$rsd = mysql_query($sql);



?>





<?php
	$count=0;
?>
<table>
<tr>

<?php



while ($row = mysql_fetch_assoc($rsd))



{



$research_id=$row['research_id'];



$report_name=$row['report_name'];



$description100=$row['description'];



$file_upload=$row['file_upload'];



?>


<td valign="top">
	<div class="sc-col-1-1"  style="height:110px;">
		<div class="sc-box">
			<div style="float: left; margin-left: 4px; margin-top: 10px;">

				<?php

				if(strlen($report_name)>0)

				{

				$front_name=substr($report_name, 0, 50);

				} ?>

				<h2 style="margin: 0;">

				<?php echo($front_name);?></h4>

			</div>
			<div class="clear"></div>
		</div>


		<br><br>
		<?php 

		$description100 ='';

		$text = $row['description'];

		$words = explode(" ",$text); 

		for($x=0;$x<26;$x++)

		{

		$description100 .= $words[$x]."  ";


		} 

		?>
		<p align="justify"><?php echo($description100);?></p>

	</div>

		<div class="button-wrap" style="margin-bottom: 0px;
margin-left: 5px;
}">

		<?php if($file_upload=='') { ?>

		<a class="sc-button sc-button-black" href="#"><strong> Read More</strong></a>

		<?php } else { ?>

		<a target="_blank" class="sc-button sc-button-black" href="admin/images_store/<?php echo($file_upload); ?>"><strong> Read More</strong></a>

		<?php } ?>

		</div>

		<p>&nbsp;</p>









<div>&nbsp;</div>

</td>
<td width="5%;"></td>
<?php
$count=$count+1;
if($count==2)
{
	echo "<tr></tr>";
	$count=0;

}
   } ?>



</table>



       </div>



	</div>



<div id="paging_button">



		<ul>



		<?php



		//Show page links



		for($i=1; $i<=$pages; $i++)



		{ ?>



			<a href="javascript:void(0)" id="<?php echo($i);?>" class="page" onClick="Page('<?php echo($cols);?>','<?php echo($per_page);?>','<?php echo($i);?>');"><li id="<?php echo($i)?>"><?php echo($i)?></li></a>



	



		<?php }?>



		</ul>



	</div>



</div>











<br clear="all" /><br clear="all" /><br clear="all" /><br clear="all" /><br clear="all" /><br clear="all" />







<script type="text/javascript">



var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");



document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));







</script>



<script type="text/javascript">



try {



var pageTracker = _gat._getTracker("UA-15782198-1");



pageTracker._trackPageview();



} catch(err) {}</script>







</body>



</html>



