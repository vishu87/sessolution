<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
  $today = strtotime("today");

  $critical_proxy = $today + 12*86400;
  $upcoming_proxy = $today + 17*86400;

  $critical_cgs = $today + 2*86400;
  $upcoming_cgs = $today + 7*86400;

  $critical_research = $today + 2*86400;
  $upcoming_research = $today + 7*86400;

  $date_low = ($_POST["date_from"])?strtotime($_POST["date_from"]):'';
  $date_high = ($_POST["date_to"])?strtotime($_POST["date_to"]):'';

if($date_high && $date_low){

  $str_time_1 = " and meeting_date between $date_low and $date_high ";
  $str_time_2 = " and cgs.publishing_date between $date_low and $date_high ";
  $str_time_3 = " and research.publishing_date between $date_low and $date_high ";

   
  } elseif ($date_high && !$date_low) {

    $str_time_1 = " and meeting_date <= $date_high ";
  $str_time_2 = " and cgs.publishing_date <= $date_high ";
  $str_time_3 = " and research.publishing_date <= $date_high ";

  } elseif(!$date_high && $date_low) {

     $str_time_1 = " and meeting_date >= $date_low ";
  $str_time_2 = " and cgs.publishing_date >= $date_low ";
  $str_time_3 = " and research.publishing_date >= $date_low ";
  
  } else {
      $str_time_1 = " and meeting_date >= $today ";
  $str_time_2 = " and cgs.publishing_date >= $today ";
  $str_time_3 = " and research.publishing_date >= $today ";
  }
 

  switch ($type) {
   
    case '1':
      $str_time_1 = " and meeting_date <= $critical_proxy ";
      $str_time_2 = " and cgs.publishing_date <= $critical_cgs ";
      $str_time_3 = " and research.publishing_date <= $critical_research ";
      break;

    case '2':
      $str_time_1 = " and meeting_date between $today and $upcoming_proxy ";
      $str_time_2 = " and cgs.publishing_date between $today and $upcoming_cgs ";
      $str_time_3 = " and research.publishing_date between $today and $upcoming_research ";
      break;
  }
  
  function check_status($deadline, $completed){
    $timenow = strtotime("now");
    if($deadline == ''){
      return '';
    }
    elseif($completed == ''){
      if(($timenow - $deadline) < 86400) echo 'burn_yellow';
      else echo 'burn_red';
    }
    else {
      if(($completed - $deadline) < 86400) echo 'burn_green';
      else echo 'burn_purple';
    }

  }
  $critical_img = '<img src="../assets/img/critical.png">';
  $upcoming_img = '<img src="../assets/img/upcoming.png">';
  $subscribed_img = '<img src="../assets/img/subs.png">';
  $limited_img = '<img src="../assets/img/lim.png">';
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
    <div class="span4">
      <h3 class="page-title">
      Meeting Details
      <small></small>
    </h3>
    </div>
    <div class="span4">
      <form style="margin-top:25px; text-align:center" action="?cat=1" method="post">
        <input type="text" name="date_from" class="datepicker_month span4" value="<?php echo $_POST["date_from"]?>" placeholder="From">&nbsp;-&nbsp;<input type="text" name="date_to" class="datepicker_month span4" value="<?php echo $_POST["date_to"]?>" placeholder="To">&nbsp;<button type="submit" class="btn blue icn-only" style="margin-top:-10px;"><i class="m-icon-swapright m-icon-white"></i></button>
      </form>
    </div>
    <div class="span4">
      <div class="btn-group" style="padding-top:25px; float:right">
        <a href="analyst.php?cat=1" class="btn blue <?php echo ($type ==0)?'active':''; ?>">No filter</a>
        <a href="analyst.php?cat=1&amp;type=1" class="btn blue <?php echo ($type ==1)?'active':''; ?>">Critical</a>
        <a href="analyst.php?cat=1&amp;type=2" class="btn blue <?php echo ($type ==2)?'active':''; ?>">Upcoming</a>
      </div>
    </div>
		
	</div>
  <style type="text/css">
  .burn_purple{
    background: #7dcfe7 !important;
  }
  .burn_red{
    background: #e77575 !important;
  }
  .burn_yellow{
    background: #f6f5ad !important;
  }
  .burn_green{
    background: #74bd6e !important;
  }

  </style>

            <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-globe"></i>Analysts</h4>
                     </div>
                     <?php
                     $analysts = array();
                     $sql_an = mysql_query("SELECT an_id, name from analysts ");
                     while ($row_an = mysql_fetch_array($sql_an)) {
                       $analysts[$row_an["an_id"]] = $row_an["name"];
                     }
                     $count =1;

                      $proxy_ids = array();

                      $year_sql = mysql_query("SELECT year_sh from years order by year_sh desc");
                        while ($year_row = mysql_fetch_array($year_sql)) {
                          $total_comp = array();
                          $year = $year_row["year_sh"];

                          $sql_report = mysql_query("SELECT distinct package_company.com_id from package_company inner join package on package_company.package_id = package.package_id where package.package_year='$year' and package.package_type=1 ");
                            while($row_cgs = mysql_fetch_array($sql_report)){
                              array_push($total_comp, $row_cgs["com_id"]);
                            }

                          $sql_report = mysql_query("SELECT distinct com_id from users_companies where type='1' and year='$year' ");
                          while($row_cgs = mysql_fetch_array($sql_report)){
                            if(!in_array($row_cgs["com_id"], $total_comp))
                              array_push($total_comp, $row_cgs["com_id"]);
                          }

                          if(sizeof($total_comp) > 0){

                            $str_comp = implode(",", $total_comp);
                            $sql = mysql_query("SELECT id from proxy_ad where com_id IN (".$str_comp.") and completed_on = '' and year ='$year' ".$str_time_1." and skipped_on = 0 order by meeting_date desc");
                            while ($row = mysql_fetch_array($sql)) {
                              array_push($proxy_ids, $row["id"]);
                            }
                          }

                        }


                    
                        
                        $count =1;
                       
                        $report_types  = array("","Proxy Advisory","CGS","Research");

                     ?>
                     <div class="portlet-body">
                       <table class="table table-stripped tablesorter">
                           <thead>
                              <tr>

                                 <th class="filter-select filter-exact" data-placeholder="">Report Type</th>
                                 <th></th>
                                 <th>Company Name</th>
                                 <th>Meeting/Pub. Date</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Type</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Data</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Analysis</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Review</th>
                                 <th>Deadlines</th>
                                 <th></th>
                              </tr>
                              
                           </thead>
                           <tfoot>
                              <tr>
                                 <th>Report Type</th>
                                 <th></th>
                                 <th>Company Name</th>
                                 <th>Meeting/Pub. Date</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Type</th>
                                 <th>Data</th>
                                 <th>Analysis</th>
                                 <th>Review</th>
                                 <th>Deadlines</th>
                                 <th></th>
                              </tr>
                              <tr>
                                <th colspan="10" class="ts-pager form-horizontal">
                                  <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
                                  <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
                                  <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                                  <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
                                  <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
                                  <select class="pagesize input-mini" title="Select page size">
                                    <option selected="selected" value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                  </select>
                                  <select class="pagenum input-mini" title="Select page number"></select>
                                </th>
                              </tr>
                              <tr>
                                <th colspan="10">
                                
                                   <button type="button" class="btn burn_purple">Completed with Delay</button>
                                    <button type="button" class="btn burn_green">Completed w/o Delay</button>
                                    <button type="button" class="btn burn_yellow">Pending</button>
                                     <button type="button" class="btn burn_red">Pending Deadline Breached</button>
                                   </th>
                              </tr>
                            </tfoot>
                           <tbody>
                           <?php
                           
                           if(sizeof($proxy_ids) > 0){
                          
                           foreach ($proxy_ids as $proxy_id) {
                            /////PROXY REPORTS
                             $sql = mysql_query("SELECT proxy_ad.com_id, proxy_ad.meeting_type, proxy_ad.year, proxy_ad.id, proxy_ad.meeting_date, proxy_ad.report, companies.com_name from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id  where proxy_ad.id='$proxy_id'  ");
                            $row = mysql_fetch_assoc($sql);
                           ?>
                              <tr id="tr_<?php echo $count;?>">
                                 <td><?php echo $report_types[1]; ?></td>
                                 <td><?php
                                  if($row["meeting_date"] <= $critical_proxy) echo $critical_img;
                                  elseif($row["meeting_date"] <= $upcoming_proxy && $row["meeting_date"] >= $today) echo $upcoming_img;

                                  $sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '$row[com_id]' and package.package_year='$row[year]' and package.package_type='1' and users_package.limited = 0 ");


                                  $sql_addi_user = mysql_query("SELECT id from users_companies where com_id = '$row[com_id]' and year = '$row[year]' and type='1' ");

                                  if(mysql_num_rows($sql_pack_user)+mysql_num_rows($sql_addi_user) > 0){
                                    echo $subscribed_img;
                                  } else {
                                    $sql_pack_user_lim = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '$row[com_id]' and package.package_year='$row[year]' and package.package_type='1' and users_package.limited = 1 ");
                                      if(mysql_num_rows($sql_pack_user_lim) > 0) echo $limited_img;
                                  }
                                 ?></td>
                                 <td><?php echo stripcslashes($row["com_name"]);  $row["com_name"] = name_filter($row["com_name"]);?></td>
                                 <td><?php echo date("d M Y",$row["meeting_date"]);?></td>
                                  <td><?php echo $meeting_types[$row["meeting_type"]];?></td>
                                 <?php
                                  $sql_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[id]' and rep_type='1' and type= '1' ");
                                  $data = mysql_fetch_array($sql_data);
                                  $sql_analysis = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[id]' and rep_type='1' and type= '2' ");
                                  $analysis = mysql_fetch_array($sql_analysis);
                                  $sql_review = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[id]' and rep_type='1' and type= '3' ");
                                  $review = mysql_fetch_array($sql_review);
                                  
                                 ?><td class="<?php echo check_status($data["deadline"],$data["completed_on"]);?>"><?php echo $analysts[$data["an_id"]]; ?></td>
                                 <td class="<?php echo check_status($analysis["deadline"],$analysis["completed_on"]);?>"><?php echo $analysts[$analysis["an_id"]]?></td>
                                 <td class="<?php echo check_status($review["deadline"],$review["completed_on"]);?>"><?php echo $analysts[$review["an_id"]]?></td>
                                 <td><?php echo ($data["deadline"])?'Data: '.date("d-m-y",$data["deadline"]).'<br>':'';
                                    echo ($analysis["deadline"])?'Analysis: '.date("d-m-y",$analysis["deadline"]).'<br>':'';
                                    echo ($review["deadline"])?'Review: '.date("d-m-y",$review["deadline"]):'';
                                 ?></td>
                                 <td>
                                  <a href="#myModal" role="button" class="btn blue icn-only" data-toggle="modal" onclick="edit_analyst('<?php echo $count;?>','<?php echo $row["com_name"]; ?>',<?php echo $row["id"];?>,1);"><i class="m-icon-swapright m-icon-white"></i></a>
                                  </td>
                              </tr>
                           <?php $count++; } 
                         }

                         ////////////////CGS
                           $sql_cgs = mysql_query("SELECT cgs.com_id, cgs.cgs_id, cgs.publishing_date, companies.com_name, cgs.year from cgs inner join companies on cgs.com_id = companies.com_id  and cgs.completed_on='' ".$str_time_2." ");

                           while($row = mysql_fetch_assoc($sql_cgs))
                           {
                           ?>
                              <tr id="tr_<?php echo $count;?>">
                                 <td><?php echo $report_types[2]; ?></td>
                                 <td><?php
                                  if($row["publishing_date"] <= $critical_cgs) echo $critical_img;
                                  elseif($row["publishing_date"] <= $upcoming_cgs && $row["publishing_date"] >= $today) echo $upcoming_img;

                                $sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '$row[com_id]' and package.package_year='$row[year]' and package.package_type='2' ");


                                  $sql_addi_user = mysql_query("SELECT id from users_companies where com_id = '$row[com_id]' and year = '$row[year]' and type='2' ");

                                  
                                  echo ( ( mysql_num_rows($sql_pack_user) + mysql_num_rows($sql_addi_user) ) > 0)? $subscribed_img:'';

                                 
                                 ?></td>
                                 <td><?php echo stripcslashes($row["com_name"]);  $row["com_name"] = name_filter($row["com_name"]);?></td>
                                 <td><?php echo date("d M Y",$row["publishing_date"]);?></td>
                                  <td></td>
                                <?php
                                  $sql_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[cgs_id]' and rep_type='2' and type= '1' ");
                                  $data = mysql_fetch_array($sql_data);
                                  $sql_analysis = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[cgs_id]' and rep_type='2' and type= '2' ");
                                  $analysis = mysql_fetch_array($sql_analysis);
                                  $sql_review = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[cgs_id]' and rep_type='2' and type= '3' ");
                                  $review = mysql_fetch_array($sql_review);
                                 
                                 ?>
                                 <td class="<?php echo check_status($data["deadline"],$data["completed_on"]);?>"><?php echo $analysts[$data["an_id"]]; ?></td>
                                 <td class="<?php echo check_status($analysis["deadline"],$analysis["completed_on"]);?>"><?php echo $analysts[$analysis["an_id"]]?></td>
                                 <td class="<?php echo check_status($review["deadline"],$review["completed_on"]);?>"><?php echo $analysts[$review["an_id"]]?></td>
                                 <td><?php echo ($data["deadline"])?'Data: '.date("d-m-y",$data["deadline"]).'<br>':'';
                                    echo ($analysis["deadline"])?'Analysis: '.date("d-m-y",$analysis["deadline"]).'<br>':'';
                                    echo ($review["deadline"])?'Review: '.date("d-m-y",$review["deadline"]):'';
                                 ?></td>
                                 <td>
                                  <a href="#myModal" role="button" class="btn blue icn-only" data-toggle="modal" onclick="edit_analyst('<?php echo $count;?>','<?php echo $row["com_name"]; ?>',<?php echo $row["cgs_id"];?>,2);"><i class="m-icon-swapright m-icon-white"></i></a>
                                  </td>
                              </tr>
                           <?php $count++; }
                            $sql_research = mysql_query("SELECT research.com_id, research.res_id, research.year, research.publishing_date, companies.com_name from research inner join companies on research.com_id = companies.com_id and research.completed_on='' ".$str_time_3." ");

                        while($row = mysql_fetch_assoc($sql_research))
                           {
                           ?>
                              <tr id="tr_<?php echo $count;?>">
                                 <td><?php echo $report_types[3]; ?></td>
                                 
                                  <td><?php
                                  if($row["publishing_date"] <= $critical_research ) echo $critical_img;
                                  elseif($row["publishing_date"] <= $upcoming_research && $row["publishing_date"] >= $today) echo $upcoming_img;
                                  
                                  $sql = "SELECT id from research_users where res_id='$row[res_id]' ";
                                  $sql_sub = mysql_query($sql);
                                  echo (mysql_num_rows($sql_sub) > 0)? $subscribed_img:'';

                                 
                                 ?></td>
                                 <td><?php echo stripcslashes($row["com_name"]);  $row["com_name"] = name_filter($row["com_name"]);?></td>
                                 <td><?php echo date("d M Y",$row["publishing_date"]);?></td>
                                  <td></td>
                                <?php
                                  $sql_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[res_id]' and rep_type='3' and type= '1' ");
                                  $data = mysql_fetch_array($sql_data);
                                  $sql_analysis = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[res_id]' and rep_type='3' and type= '2' ");
                                  $analysis = mysql_fetch_array($sql_analysis);
                                  $sql_review = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[res_id]' and rep_type='3' and type= '3' ");
                                  $review = mysql_fetch_array($sql_review);
                                  
                                 ?>
                                 <td class="<?php echo check_status($data["deadline"],$data["completed_on"]);?>"><?php echo $analysts[$data["an_id"]]; ?></td>
                                 <td class="<?php echo check_status($analysis["deadline"],$analysis["completed_on"]);?>"><?php echo $analysts[$analysis["an_id"]]?></td>
                                 <td class="<?php echo check_status($review["deadline"],$review["completed_on"]);?>"><?php echo $analysts[$review["an_id"]]?></td>
                                 <td><?php echo ($data["deadline"])?'Data: '.date("d-m-y",$data["deadline"]).'<br>':'';
                                    echo ($analysis["deadline"])?'Analysis: '.date("d-m-y",$analysis["deadline"]).'<br>':'';
                                    echo ($review["deadline"])?'Review: '.date("d-m-y",$review["deadline"]):'';
                                 ?></td>
                                 <td>
                                  <a href="#myModal" role="button" class="btn blue icn-only" data-toggle="modal" onclick="edit_analyst('<?php echo $count;?>','<?php echo $row["com_name"]; ?>',<?php echo $row["res_id"];?>,3);"><i class="m-icon-swapright m-icon-white"></i></a>
                                  </td>
                              </tr>
                           <?php $count++; }
                            ?>                                    

                           </tbody>
                        </table>
                     </div>
                  </div>
                  
              <div class="row-fluid">
            <div class="span6">
              <form method="post" target="_blank" action="../excel/list_pending_analyst.php">
                <input type="hidden" name="date_from_p" value="<?php echo $date_low;?>">
                <input type="hidden" name="date_to_p"  value="<?php echo $date_high; ?>">
                <input type="hidden" name="type"  value="<?php echo $type; ?>">
                <button type="submit" class="btn" style=""><i class="icon-share"></i> Export</button>
      
              </form>
            </div>
           </div>
				
	<!-- Button to trigger modal -->


<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:90%; margin-left:-45%">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body" id="modal-body">
    
  </div>
  <div class="modal-footer">

    <button class="btn" data-dismiss="modal" aria-hidden="true" id="close_button">Close</button>
  </div>
</div>			


</div>

<script type="text/javascript">

//used
function edit_analyst(count,company_name, report_id, type){
   $("#myModalLabel").text(company_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'load_analyst_ui';
   $.post("ajax/"+ file +".php", {count:count, id:report_id, type: type}, function(data) {
      $("#modal-body").html(data);
      initialize();
   }); 

}

function analyst_submit(count,report_id, type){
data_ch = $("#data_deadline").val();
var data_d = (data_ch)?$.datepicker.parseDate("dd-mm-yy",  data_ch):'';

analysis_ch = $("#analysis_deadline").val();
var analysis_d = (analysis_ch)?$.datepicker.parseDate("dd-mm-yy",  analysis_ch):'';

review_ch = $("#review_deadline").val();
var review_d = (review_ch)?$.datepicker.parseDate("dd-mm-yy",  review_ch):'';

var flag =0;

if(review_d < analysis_d) flag = 1;
if(analysis_d < data_d) flag =1;
if(review_d < data_d) flag =1;

if(flag == 0){
   var file = 'add_analyst';
   $.post("ajax/"+ file +".php", {id:report_id, type: type <?php 
    $ar = array("data","analysis","review");
    foreach ($ar as $r) {
     echo ', '.$r.'_an_id:$("#'.$r.'_an_id").val(), '.$r.'_deadline:$("#'.$r.'_deadline").val()';
    }
    ?>}, function(data) {
       $("#modal-body").html(data);
       $.post("ajax/analyst_refresh.php", {count:count,id:report_id, type: type }, function(data) {
          $("#tr_"+count).html(data);
       });
   }); 
} else {
  bootbox.alert('Date sequence for data-analysis-review is not correct.');
}

}

function mark_complete(count,report_id, type, report_analyst_id){

   var file = 'mark_complete';
   $.post("ajax/"+ file +".php", {id:report_analyst_id}, function(data) {
    if(data == 'success'){
      analyst_submit(count,report_id, type);
    }
      
   }); 

}
function mark_incomplete(count,report_id, type, report_analyst_id){
  bootbox.confirm("Are you sure?", function(result) {
    if(result) {
       var file = 'mark_incomplete';
       $.post("ajax/"+ file +".php", {id:report_analyst_id}, function(data) {
        if(data == 'success'){
          analyst_submit(count,report_id, type);
        }    
       }); 
    }
    else {
    
    }
  });
}

</script>