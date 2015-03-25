<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
   function check_data($data){
      if($data < 40){
         return 'red';
      } elseif ($data <70) {
         return 'blue';
      } else {
         return 'green';
      }
   }
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Welcome, <?php echo $member->name; ?>
			<small></small>
		</h3>
	</div>
<hr style="margin-top:0">

<div class="row-fluid">
   <?php
   $proxy_ids_subs = array();
   $year_sql = mysql_query("SELECT year_sh from years order by year_sh desc");
   while ($year_row = mysql_fetch_array($year_sql)) {

     $total_comp = array();
     $year = $year_row["year_sh"];

     $sql_report = mysql_query("SELECT distinct package_company.com_id from package_company inner join package on package_company.package_id = package.package_id inner join users_package on users_package.package_id = package.package_id where package.package_year='$year' and package.package_type='1' ");
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
       $sql = mysql_query("SELECT id from proxy_ad where com_id IN (".$str_comp.") and year ='$year' and meeting_type='1' ");
       while ($row = mysql_fetch_array($sql)) {
         array_push($proxy_ids_subs, $row["id"]);
       }
     }

   }
   $tot = sizeof($proxy_ids_subs);
   if($tot > 0){
      $str_p = implode(',', $proxy_ids_subs);

      $sql = mysql_query("SELECT id from proxy_ad where id IN (".$str_p.") and completed_on != '' ");
   }

  if($tot > 0) $data2 = mysql_num_rows($sql)/$tot*100;
  
            
         ?>
                  <div class="span4 responsive" data-tablet="span4" data-desktop="span4">
                     <div class="dashboard-stat <?php echo check_data($data2);?>">
                        <div class="visual">
                           <i class="icon-beaker"></i>
                        </div>
                        <div class="details">
                           <div class="number">
                             <?php echo round($data2,1).'%'?>
                           </div>
                           <div class="desc">                           
                              AGM Completion<br>Subscribed Proxy Advisory<br><br>
                           </div>
                        </div>
                                         
                     </div>
                  </div>
                  <?php
    $proxy_ids_coverage = array();
    $proxy_ids_coverage_agm = array();

   $year_sql = mysql_query("SELECT year_sh from years order by year_sh desc");
   while ($year_row = mysql_fetch_array($year_sql)) {

     $total_comp = array();
     $year = $year_row["year_sh"];

     $sql_report = mysql_query("SELECT distinct package_company.com_id from package_company inner join package on package_company.package_id = package.package_id where package.package_year='$year' and package.package_type='1' ");
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
       $sql = mysql_query("SELECT id, meeting_type from proxy_ad where com_id IN (".$str_comp.") and year ='$year' ");
       while ($row = mysql_fetch_array($sql)) {
         array_push($proxy_ids_coverage, $row["id"]);
         if($row["meeting_type"] == 1)  array_push($proxy_ids_coverage_agm, $row["id"]);
       }
     }

        }
   $tot = sizeof($proxy_ids_coverage_agm); // proxy advisory which is under coverage
   if($tot > 0){
      $str_p = implode(',', $proxy_ids_coverage_agm);

      $sql = mysql_query("SELECT id from proxy_ad where id IN (".$str_p.") and completed_on != '' ");
   }
   $data2 = mysql_num_rows($sql)/$tot*100;
                     
                  ?>


                  <div class="span4 responsive" data-tablet="span4" data-desktop="span4">
                     <div class="dashboard-stat <?php echo check_data($data2);?>">
                        <div class="visual">
                           <i class="icon-tags"></i>
                        </div>
                        <div class="details">
                           <div class="number"><?php echo round($data2,1).'%'?></div>
                           <div class="desc">AGM Completion<br>Proxy Advisory Coverage<br><br></div>
                        </div>
                                         
                     </div>
                  </div>
                 
               </div>
<hr style="margin-top:0">
<!-- SECOND ROW -->
<div class="row-fluid">
  
    <?php
                  
   $agm = 0;
   $egm = 0;
   $pb =0;
   $ccm = 0;
   $year_sql = mysql_query("SELECT year_sh from years order by year_sh desc");
   while ($year_row = mysql_fetch_array($year_sql)) {

     $total_comp = array();
     $year = $year_row["year_sh"];

     $sql_report = mysql_query("SELECT distinct package_company.com_id from package_company inner join package on package_company.package_id = package.package_id where package.package_year='$year' and package.package_type='1' ");
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
       $sql = mysql_query("SELECT id from proxy_ad where com_id IN (".$str_comp.") and year ='$year' and meeting_type='1' ");
       $agm += mysql_num_rows($sql);

       $sql = mysql_query("SELECT id from proxy_ad where com_id IN (".$str_comp.") and year ='$year' and meeting_type='2' ");
       $egm += mysql_num_rows($sql);

       $sql = mysql_query("SELECT id from proxy_ad where com_id IN (".$str_comp.") and year ='$year' and meeting_type='3' ");
       $pb += mysql_num_rows($sql);

          $sql = mysql_query("SELECT id from proxy_ad where com_id IN (".$str_comp.") and year ='$year' and meeting_type='4' ");
       $ccm += mysql_num_rows($sql);

     }

    

   }
   $tot = $agm + $egm +$pb;
   $agmp = ($tot !=0)?$agm/$tot*100:'0';
    $egmp = ($tot !=0)?$egm/$tot*100:'0';
     $pbp = ($tot !=0)?$pb/$tot*100:'0';
     $ccmp = ($tot !=0)?$ccm/$tot*100:'0';
                  ?>
                  <div class="span3 responsive" data-tablet="span3" data-desktop="span3">
                     <div class="dashboard-stat" style="background:#6576C2">
                        <div class="visual">
                           <i class="icon-tag"></i>
                        </div>
                        <div class="details">
                           <div class="number">
                             <?php echo round($agmp,1).'%'?>
                           </div>
                           <div class="desc">                           
                              <div class="desc"><?php echo $agm.' ';?>AGM<br>PA Coverage<br><br></div>
                           </div>
                        </div>
                                         
                     </div>
                  </div>
                 

                  <div class="span3 responsive" data-tablet="span3" data-desktop="span3">
                     <div class="dashboard-stat" style="background:#76AF73">
                        <div class="visual">
                           <i class="icon-tags"></i>
                        </div>
                        <div class="details">
                           <div class="number"><?php echo round($egmp,1).'%'?></div>
                           <div class="desc"><?php echo $egm.' ';?>EGM<br>PA Coverage<br><br></div>
                        </div>                  
                     </div>
                  </div>
                 
               <div class="span3 responsive" data-tablet="span3" data-desktop="span3">
                     <div class="dashboard-stat" style="background:#8E698B">
                        <div class="visual">
                           <i class="icon-legal"></i>
                        </div>
                        <div class="details">
                           <div class="number"><?php echo round($pbp,1).'%'?></div>
                          <div class="desc"><?php echo $pb.' ';?>PB<br>PA Coverage<br><br></div>
                        </div>
                                         
                     </div>
                  </div>

                  <div class="span3 responsive" data-tablet="span3" data-desktop="span3">
                     <div class="dashboard-stat" style="background:#CC3300">
                        <div class="visual">
                           <i class="icon-legal"></i>
                        </div>
                        <div class="details">
                           <div class="number"><?php echo round($ccmp,1).'%'?></div>
                          <div class="desc"><?php echo $ccm.' ';?>CCM<br>PA Coverage<br><br></div>
                        </div>
                                         
                     </div>
                  </div>
                 
               </div>


            <hr style="margin-top:0">
            <!-- THIRD ROW -->
            <div class="row-fluid">
              
                <?php
                 
                  $proxy_string = implode(',', $proxy_ids_coverage);

                  $today = strtotime("today");
                  $upcoming = $today + 17*86400;
                  $critical = $today + 12*86400;
                  $fivedays = $today + 5*86400;

                  $sql = mysql_query("SELECT id from proxy_ad where id IN (".$proxy_string.") and meeting_date BETWEEN $today and $upcoming ");
                  $total_upcoming = mysql_num_rows($sql);

                  $sql = mysql_query("SELECT cgs_id from cgs where publishing_date BETWEEN $today and $upcoming ");
                  $total_upcoming += mysql_num_rows($sql);

                  $sql = mysql_query("SELECT res_id from research where publishing_date BETWEEN $today and $upcoming ");
                  $total_upcoming += mysql_num_rows($sql);

                  $sql = mysql_query("SELECT id from proxy_ad where  id IN (".$proxy_string.") and completed_on != '' and meeting_date BETWEEN $today and $upcoming ");
                  $total_upcoming_completed = mysql_num_rows($sql);

                  $sql = mysql_query("SELECT cgs_id from cgs where completed_on != '' and publishing_date BETWEEN $today and $upcoming ");
                  $total_upcoming_completed += mysql_num_rows($sql);

                  $sql = mysql_query("SELECT res_id from research where completed_on != '' and publishing_date BETWEEN $today and $upcoming ");
                  $total_upcoming_completed += mysql_num_rows($sql);
                     
                  ?>
                  <div class="span4 responsive" data-tablet="span4" data-desktop="span4">
                     <div class="dashboard-stat <?php echo check_data($total_upcoming_completed/$total_upcoming*100);?>">
                        <div class="visual">
                           <i class=" icon-exclamation-sign"></i>
                        </div>
                        <div class="details">
                           <div class="number">
                             <?php echo $total_upcoming; ?>
                           </div>
                           <div class="desc">                           
                              <div class="desc"><?php echo $total_upcoming_completed.' ';?>Completed<br>Upcoming Meetings<br><br></div>
                           </div>
                        </div>
                                         
                     </div>
                  </div>
                  <?php
                     $sql = mysql_query("SELECT id from proxy_ad where id IN (".$proxy_string.") and meeting_date BETWEEN $today and $critical ");
                     $total_critical = mysql_num_rows($sql);

                     $sql = mysql_query("SELECT cgs_id from cgs where publishing_date BETWEEN $today and $critical ");
                     $total_critical += mysql_num_rows($sql);

                     $sql = mysql_query("SELECT res_id from research where publishing_date BETWEEN $today and $critical ");
                     $total_critical += mysql_num_rows($sql);

                     $sql = mysql_query("SELECT id from proxy_ad where  id IN (".$proxy_string.") and completed_on != '' and meeting_date BETWEEN $today and $critical ");
                     $total_critical_completed = mysql_num_rows($sql);

                     $sql = mysql_query("SELECT cgs_id from cgs where completed_on != '' and publishing_date BETWEEN $today and $critical ");
                     $total_critical_completed += mysql_num_rows($sql);

                     $sql = mysql_query("SELECT res_id from research where completed_on != '' and publishing_date BETWEEN $today and $critical ");
                     $total_critical_completed += mysql_num_rows($sql);
                     
                  ?>

                 <div class="span4 responsive" data-tablet="span4" data-desktop="span4">
                     <div class="dashboard-stat <?php echo check_data($total_critical_completed/$total_critical*100);?>">
                        <div class="visual">
                           <i class="icon-time"></i>
                        </div>
                        <div class="details">
                           <div class="number">
                             <?php echo $total_critical; ?>
                           </div>
                           <div class="desc">                           
                              <div class="desc"><?php echo $total_critical_completed.' ';?>Completed<br>Critical Meetings<br><br></div>
                           </div>
                        </div>
                                         
                     </div>
                  </div>
                  <?php
                     $sql = mysql_query("SELECT id from proxy_ad where  id IN (".$proxy_string.") and completed_on= '' ");
                     $total_outstanding = mysql_num_rows($sql);

                     $sql = mysql_query("SELECT cgs_id from cgs where completed_on= '' ");
                     $total_outstanding += mysql_num_rows($sql);

                     $sql = mysql_query("SELECT res_id from research where completed_on= '' ");
                     $total_outstanding += mysql_num_rows($sql);

                  ?>
               <div class="span4 responsive" data-tablet="span4" data-desktop="span4">
                     <div class="dashboard-stat blue">
                        <div class="visual">
                           <i class="icon-tasks"></i>
                        </div>
                        <div class="details">
                           <div class="number"><?php echo $total_outstanding;?></div>
                          <div class="desc">Outstanding Reports<br><br><br></div>
                        </div>
                                         
                     </div>
                  </div>
                 
               </div>
               <div class="row-fluid">
  
    
                  <?php
                     
                     $sql = mysql_query("SELECT id from proxy_ad where  id IN (".$proxy_string.") and completed_on != '' ");
                     $total_completed = mysql_num_rows($sql);

                     $sql = mysql_query("SELECT cgs_id from cgs where completed_on != '' ");
                     $total_completed += mysql_num_rows($sql);

                     $sql = mysql_query("SELECT res_id from research where completed_on != '' ");
                     $total_completed += mysql_num_rows($sql);
                     
                  ?>

                 <div class="span4 responsive" data-tablet="span4" data-desktop="span4">
                     <div class="dashboard-stat green">
                        <div class="visual">
                           <i class="icon-check"></i>
                        </div>
                        <div class="details">
                           <div class="number">
                             <?php echo $total_completed; ?>
                           </div>
                           <div class="desc">                           
                              <div class="desc">Completed Reports<br><br><br></div>
                           </div>
                        </div>
                                         
                     </div>
                  </div>
                  <?php
                  
                  $sql = mysql_query("SELECT id from proxy_ad where id IN (".$proxy_string.") and meeting_date < $today and completed_on = '' ");
                  $total_skipped = mysql_num_rows($sql);

                  $sql = mysql_query("SELECT cgs_id from cgs where publishing_date < $today and completed_on = '' ");
                  $total_skipped += mysql_num_rows($sql);

                  $sql = mysql_query("SELECT res_id from research where publishing_date < $today and completed_on = '' ");
                  $total_skipped += mysql_num_rows($sql);

                     
                  ?>
                  <div class="span4 responsive" data-tablet="span4" data-desktop="span4">
                     <div class="dashboard-stat <?php echo check_data($total_upcoming_completed/$total_upcoming*100);?>">
                        <div class="visual">
                           <i class=" icon-check-empty"></i>
                        </div>
                        <div class="details">
                           <div class="number">
                             <?php echo $total_skipped; ?>
                           </div>
                           <div class="desc">                           
                              <div class="desc">Skipped Reports<br><br><br></div>
                           </div>
                        </div>
                                         
                     </div>
                  </div>
               <div class="span4 responsive" data-tablet="span4" data-desktop="span4">
                     
                  </div>
                 
               </div>

               <hr style="margin-top:0">
<!-- THIRD ROW -->
<div class="row-fluid">
  
    <?php
                  

                  $sql = mysql_query("SELECT id from report_analyst where type='1' and completed_on='' ");
                  $under_data= mysql_num_rows($sql);

                 $sql = mysql_query("SELECT id from report_analyst where type='2' and completed_on='' ");
                  $under_analysis= mysql_num_rows($sql);

                  $sql = mysql_query("SELECT id from report_analyst where type='3' and completed_on='' ");
                  $under_review= mysql_num_rows($sql);
                     
                  ?>
                  <div class="span4 responsive" data-tablet="span4" data-desktop="span4">
                     <div class="dashboard-stat purple">
                        <div class="visual">
                           <i class=" icon-exclamation-sign"></i>
                        </div>
                        <div class="details">
                           <div class="number">
                             <?php echo $under_data; ?>
                           </div>
                           <div class="desc">                           
                              <div class="desc">Under Data<br><br><br></div>
                           </div>
                        </div>
                                         
                     </div>
                  </div>
                 
                 <div class="span4 responsive" data-tablet="span4" data-desktop="span4">
                     <div class="dashboard-stat yellow">
                        <div class="visual">
                           <i class="icon-time"></i>
                        </div>
                        <div class="details">
                           <div class="number">
                             <?php echo $under_analysis; ?>
                           </div>
                           <div class="desc">                           
                              <div class="desc">Under Analysis<br><br><br></div>
                           </div>
                        </div>
                                         
                     </div>
                  </div>
               <div class="span4 responsive" data-tablet="span4" data-desktop="span4">
                     <div class="dashboard-stat blue">
                        <div class="visual">
                           <i class="icon-tasks"></i>
                        </div>
                        <div class="details">
                           <div class="number"><?php echo $under_review;?></div>
                          <div class="desc">Under Review<br><br><br></div>
                        </div>
                                         
                     </div>
                  </div>
                 
               </div>

<!-- ANALYST STATUS-->
 <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-globe"></i>Analyst-Pending Reports for next five days</h4>
                     </div>
                     <?php
                        $sql = mysql_query("select an_id,name from analysts where active = 0 order by name desc");
                        if(mysql_num_rows($sql) > 0) {
                     ?>
                     <div class="portlet-body">
                       <table class="table table-hover tablesorter">
                           <thead>
                              <tr>
                                <th>#</th>
                                 <th>Name</th>
                                 <th>Data</th>
                                 <th>Analysis/Contingent</th>
                                 <th>Review/Contingent</th>
                              </tr>
                           </thead>
                           <tbody>
                           <?php
                           
                           $count =1;
                           while($row = mysql_fetch_assoc($sql))
                           {
                           ?>
                              <tr >
                                 <td><?php echo $count;?></td>
                                 <?php 
                                  $sql_pack = mysql_query("SELECT id from report_analyst where an_id='$row[an_id]' and deadline BETWEEN $today and $fivedays and completed_on = ''  and type='1' ");
                                  $data_rem = mysql_num_rows($sql_pack);
                                  
                                  $sql_pack = mysql_query("SELECT id,report_id,rep_type from report_analyst where an_id='$row[an_id]' and deadline BETWEEN $today and $fivedays and completed_on = ''  and type='2' ");
                                  $analysis_rem = mysql_num_rows($sql_pack);

                                  $analysis_con = 0;
                                  while ($row_pack = mysql_fetch_array($sql_pack)) {

                                    $sql2 = "SELECT id from report_analyst where report_id='$row_pack[report_id]' and rep_type='$row_pack[rep_type]' and type='1' and completed_on =''";
                                    //echo $sql2;
                                    $sql_check = mysql_query($sql2);
                                    if(mysql_num_rows($sql_check) > 0 ) $analysis_con++;
                                  
                                  }


                                  $sql_pack = mysql_query("SELECT id,report_id,rep_type from report_analyst where an_id='$row[an_id]' and deadline BETWEEN $today and $fivedays and completed_on = ''  and type='3' ");
                                  $review_rem = mysql_num_rows($sql_pack);

                                  $review_con = 0;
                                  while ($row_pack = mysql_fetch_array($sql_pack)) {

                                    $sql2 = "SELECT id from report_analyst where report_id='$row_pack[report_id]' and rep_type='$row_pack[rep_type]' and type IN (1,2)  and completed_on =''";
                                    //echo $sql2;
                                    $sql_check = mysql_query($sql2);
                                    if(mysql_num_rows($sql_check) > 0 ) $review_con++;
                                  
                                  }

                                  
                                 ?>
                                 <td ><?php echo stripcslashes($row["name"]);?></td>
                                 <td><?php echo $data_rem; ?></td>
                                 <td><?php echo $analysis_rem.' / '.$analysis_con; ?></td>
                                 <td><?php echo $review_rem.' / '.$review_con; ?></td>
                              </tr>
                           <?php $count++; } ?>
                           </tbody>
                        </table>
                     </div>
                     <?php
                        } else {
                           echo "No results found";
                        }
                     ?>
                  </div>
		<div class="row-fluid">
    <div class="span12" style="text-align:right">
      <a href="../excel/list_pending.php" class="btn" style="margin-bottom:20px" target="_blank"><i class="icon-share"></i> Export</a>
       
    </div>
  </div>
</div><!-- END CONTAINER -->

</script>