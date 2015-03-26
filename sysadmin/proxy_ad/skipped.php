<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
  $date_low = ($_POST["date_from"])?strtotime($_POST["date_from"]):'';
  $date_high = ($_POST["date_to"])?strtotime($_POST["date_to"]):'';
  $today = strtotime("today");

?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<div class="span6"><h3 class="page-title">
      Proxy Advisory Reports
      <small></small>
    </h3></div>
    <div class="span6">
      <form style="margin-top:30px; text-align:center" action="?cat=7" method="post">
        <input type="text" name="date_from" class="datepicker_month span3" value="<?php echo $_POST["date_from"]?>" placeholder="From">&nbsp;-&nbsp;<input type="text" name="date_to" class="datepicker_month span3" value="<?php echo $_POST["date_to"]?>" placeholder="To">&nbsp;<button type="submit" class="btn blue icn-only" style="margin-top:-10px;"><i class="m-icon-swapright m-icon-white"></i></button>
      </form>
    </div>
	</div>

            <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-globe"></i>List</h4>
                     </div>
                     <?php
                        
                        $proxy_ids = array();

                        
                        if($date_high && $date_low){
                         $date_sql = "and meeting_date <= '$date_high'  and meeting_date >= '$date_low' and (skipped_on != 0 OR (vote_completed_on = 0 and meeting_date<'$today')) ";
                        } elseif ($date_high && !$date_low) {
                          $date_sql = "and meeting_date <= '$date_high'  and (skipped_on != 0 OR (vote_completed_on = 0 and meeting_date<'$today')) ";
                        } elseif(!$date_high && $date_low) {
                          $date_sql = "and meeting_date >= '$date_low'  and (skipped_on != 0 OR (vote_completed_on = 0 and meeting_date<'$today')) ";
                        } else {
                          $date_sql = "and (skipped_on != 0 OR (vote_completed_on = 0 and meeting_date<'$today')) ";
                        }

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
                            $sql = mysql_query("SELECT id from proxy_ad where com_id IN (".$str_comp.") and year ='$year' ".$date_sql." order by meeting_date desc");
                            while ($row = mysql_fetch_array($sql)) {
                              array_push($proxy_ids, $row["id"]);
                            }
                          }

                        }

                        

                        if(sizeof($proxy_ids) > 0){

                          
                        
                     ?>
                     <div class="portlet-body">
                       <table class="table table-stripped tablesorter">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Company Name</th>
                                 <th>Meeting Date</th>
                                 <th>e-Voting Deadline</th>
                                 <th>Type</th>
                                 <th>Report</th>
                                 <th>Subscribers</th>
                                 <th>Action</th>
                              </tr>
                              
                           </thead>
                           <tfoot>
                              <tr>
                                <th>#</th>
                                 <th>Company Name</th>
                                 <th>Meeting Date</th>
                                 <th>e-Voting Deadline</th>
                                 <th>Type</th>
                                 <th>Report</th>
                                 <th>Subscribers</th>
                                 <th>Action</th>
                              </tr>
                              <tr>
                                <th colspan="8" class="ts-pager form-horizontal">
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
                            </tfoot>
                           <tbody>
                           <?php
                           
                           $count =1;
                           
                           
                          
                           foreach ($proxy_ids as $proxy_id) {
                              $proxy_report = new PA_admin($proxy_id);
                           
                           ?>
                              <tr id="tr_<?php echo $proxy_report->id; ?>">
                                 <td><?php echo $count;?></td>
                                 <td><?php echo $proxy_report->company_name; 
                                  $row["com_name"] = name_filter($proxy_report->company_name);
                                 ?></td>
                                 
                                 <td><?php echo $proxy_report->meeting_date; ?></td>
                                 <td><?php echo $proxy_report->evoting_end; ?></td>
                                 <td><?php echo $proxy_report->meeting_type;?></td>
                                 
                                 <td><?php echo $proxy_report->report() ?></td>
                                 
                                
                                 <td>
                                  <?php 
                                  $proxy_report->users();
                                  $proxy_report->add_user_button($count);
                                 ?>
                                 
                               </td>
                                 <td>
                                  <?php 
                                    $proxy_report->ses_voting($count);
                                    $proxy_report->edit_button($count);
                                    $proxy_report->custom_button($count); 
                                    $proxy_report->unskip($count); 
                                    $proxy_report->delete(); 
                                  ?>
                                </td>
                              </tr>
                           <?php $count++; } ?>
                           </tbody>
                        </table>
                     </div>
                     <?php
                       
                      }
                        else {
                           echo "No results found";
                        }
                     ?>
                  </div>
                  
           <div class="row-fluid">
            <div class="span6">
              <form method="post" target="_blank" action="../excel/list_pa.php">
                <input type="hidden" name="date_from_p" value="<?php echo $date_low;?>">
                <input type="hidden" name="date_to_p"  value="<?php echo $date_high; ?>">
                <button type="submit" class="btn" style=""><i class="icon-share"></i> Export</button>
      
              </form>
            </div>
           </div>
				
	<!-- Button to trigger modal -->


<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:90%; margin-left:-45%;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body" id="modal-body">
    
  </div>
  <div class="modal-footer">
 
    <button class="btn" data-dismiss="modal" aria-hidden="true" id="close_button" >Close</button>
  </div>
</div>		

<div id="stack1" class="modal hide fade" tabindex="-1" data-focus-on="input:first" style="width:94%; margin-left:-47%">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Stack One</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
    <p>One fine body…</p>
    <p>One fine body…</p>
    <input type="text" data-tabindex="1">
    <input type="text" data-tabindex="2">
    <button class="btn" data-toggle="modal" href="#stack2">Launch modal</button>
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn" id="close_button1">Close</button>
  </div>
</div>
 
<div id="stack2" class="modal hide fade" tabindex="-1" data-focus-on="input:first" style="width:90%; margin-left:-45%">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Stack Two</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
    <p>One fine body…</p>
    <input type="text" data-tabindex="1">
    <input type="text" data-tabindex="2">
    <button class="btn" data-toggle="modal" href="#stack3">Launch modal</button>
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn" id="close_button2">Close</button>
  </div>
</div>
 
<div id="stack3" class="modal hide fade" tabindex="-1" data-focus-on="input:first">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Stack Three</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
    <input type="text" data-tabindex="1">
    <input type="text" data-tabindex="2">
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn">Close</button>
    <button type="button" class="btn btn-primary">Ok</button>
  </div>
</div>    	


</div>