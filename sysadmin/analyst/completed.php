<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
    $date_low = ($_POST["date_from"])?strtotime($_POST["date_from"]):'';
  $date_high = ($_POST["date_to"])?strtotime($_POST["date_to"]):'';
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Completed Meetings
			<small></small>
		</h3>
	</div>
   <style type="text/css">
  .burn_red_dark{
    background: #f00 !important;
  }
  .burn_red{
    background: #f35a5a !important;
  }
  .burn_yellow{
    background: #ff0 !important;
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


                   

                        $sql = mysql_query("SELECT proxy_ad.*,companies.com_name from report_analyst inner join proxy_ad on report_analyst.report_id = proxy_ad.id inner join companies on proxy_ad.com_id = companies.com_id where report_analyst.completed_on != '' and report_analyst.rep_type='1' and report_analyst.type='3' ");
                        
                        $count =1;
                       
                        $report_types  = array("","Proxy Advisory","CGS","Research");

                     ?>
                     <div class="portlet-body">
                       <table class="table table-stripped tablesorter">
                           <thead>
                              <tr>
                                 <th class="filter-select filter-exact" data-placeholder="">Report Type</th>
                                 <th>Company Name</th>
                                 <th>Meeting/Pub. Date</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Type</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Data</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Analysis</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Review</th>
                                 <th>Deadlines</th>
                                 
                              </tr>
                              
                           </thead>
                           <tfoot>
                              <tr>
                                 <th>Report Type</th>
                                 <th>Company Name</th>
                                 <th>Meeting/Pub. Date</th>
                                 <th class="filter-select filter-exact" data-placeholder="">Type</th>
                                 <th>Data</th>
                                 <th>Analysis</th>
                                 <th>Review</th>
                                 <th>Deadlines</th>
                                 
                              </tr>
                              <tr>
                                <th colspan="9" class="ts-pager form-horizontal">
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
                                <th colspan="9">
                                
                                   
                                    <button type="button" class="btn burn_green">Completed w/o Delay</button>
                                   
                                     <button type="button" class="btn burn_red">Completed Deadline Breached</button>
                                   </th>
                              </tr>
                            </tfoot>
                           <tbody>
                           <?php
                           
                           
                          
                           while($row = mysql_fetch_assoc($sql))
                           {
                           ?>
                              <tr id="tr_<?php echo $count;?>">
                                 <td><?php echo $report_types[1]; ?></td>
                                 <td><?php echo stripcslashes($row["com_name"]);?></td>
                                 <td><?php echo date("d M Y",$row["meeting_date"]);?></td>
                                  <td><?php echo $meeting_types[$row["meeting_type"]];?></td>
                                 <?php
                                  $sql_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[id]' and rep_type='1' and type= '1' ");
                                  $data = mysql_fetch_array($sql_data);
                                  $sql_analysis = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[id]' and rep_type='1' and type= '2' ");
                                  $analysis = mysql_fetch_array($sql_analysis);
                                  $sql_review = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[id]' and rep_type='1' and type= '3' ");
                                  $review = mysql_fetch_array($sql_review);
                                 
                                 ?>
                                 <td class="<?php echo (($data["completed_on"] - $data["deadline"])<86400)?'burn_green':'burn_red';?>"><?php  echo $analysts[$data["an_id"]]; ?></td>
                                 <td class="<?php echo (($analysis["completed_on"] - $analysis["deadline"])<86400)?'burn_green':'burn_red';?>"><?php echo $analysts[$analysis["an_id"]]?></td>
                                 <td class="<?php echo (($review["completed_on"] - $review["deadline"])<86400)?'burn_green':'burn_red';?>"><?php echo $analysts[$review["an_id"]]?></td>
                                 <td><?php echo ($data["deadline"])?'Data: '.date("d-m-y",$data["deadline"]).'<br>':'';
                                    echo ($analysis["deadline"])?'Analysis: '.date("d-m-y",$analysis["deadline"]).'<br>':'';
                                    echo ($review["deadline"])?'Review: '.date("d-m-y",$review["deadline"]):'';
                                 ?></td>
                                 
                              </tr>
                           <?php $count++; } 

                           $sql_cgs = mysql_query("SELECT cgs.*,companies.com_name from report_analyst inner join cgs on report_analyst.report_id = cgs.cgs_id inner join companies on cgs.com_id = companies.com_id where report_analyst.completed_on != '' and report_analyst.rep_type='2' and report_analyst.type='3' ");

                        while($row = mysql_fetch_assoc($sql_cgs))
                           {
                           ?>
                              <tr id="tr_<?php echo $count;?>">
                                 <td><?php echo $report_types[2]; ?></td>
                                 <td><?php echo stripcslashes($row["com_name"]);?></td>
                                 <td><?php echo date("d M Y",$row["publishing_date"]);?></td>
                                  <td></td>
                                 <?php
                                  $sql_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[cgs_id]' and rep_type='2' and type= '1' ");
                                  $data = mysql_fetch_array($sql_data);
                                  $sql_analysis = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[cgs_id]' and rep_type='2' and type= '2' ");
                                  $analysis = mysql_fetch_array($sql_analysis);
                                  $sql_review = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[cgs_id]' and rep_type='2' and type= '3' ");
                                  $review = mysql_fetch_array($sql_review);
                                  
                                 ?></td>
                                 <td class="<?php echo (($data["completed_on"] - $data["deadline"])<86400)?'burn_green':'burn_red';?>"><?php  echo $analysts[$data["an_id"]]; ?></td>
                                 <td class="<?php echo (($analysis["completed_on"] - $analysis["deadline"])<86400)?'burn_green':'burn_red';?>"><?php echo $analysts[$analysis["an_id"]]?></td>
                                 <td class="<?php echo (($review["completed_on"] - $review["deadline"])<86400)?'burn_green':'burn_red';?>"><?php echo $analysts[$review["an_id"]]?></td>
                                 <td><?php echo ($data["deadline"])?'Data: '.date("d-m-y",$data["deadline"]).'<br>':'';
                                    echo ($analysis["deadline"])?'Analysis: '.date("d-m-y",$analysis["deadline"]).'<br>':'';
                                    echo ($review["deadline"])?'Review: '.date("d-m-y",$review["deadline"]):'';
                                 ?></td>
                                 
                              </tr>
                           <?php $count++; }
                            $sql_research = mysql_query("SELECT research.*,companies.com_name from report_analyst inner join research on report_analyst.report_id = research.res_id inner join companies on research.com_id = companies.com_id where report_analyst.completed_on != '' and report_analyst.rep_type='3' and report_analyst.type='3' ");

                        while($row = mysql_fetch_assoc($sql_research))
                           {
                           ?>
                              <tr id="tr_<?php echo $count;?>">
                                 <td><?php echo $report_types[3]; ?></td>
                                 <td><?php echo stripcslashes($row["com_name"]);?></td>
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
                                 <td class="<?php echo (($data["completed_on"] - $data["deadline"])<86400)?'burn_green':'burn_red';?>"><?php  echo $analysts[$data["an_id"]]; ?></td>
                                 <td class="<?php echo (($analysis["completed_on"] - $analysis["deadline"])<86400)?'burn_green':'burn_red';?>"><?php echo $analysts[$analysis["an_id"]]?></td>
                                 <td class="<?php echo (($review["completed_on"] - $review["deadline"])<86400)?'burn_green':'burn_red';?>"><?php echo $analysts[$review["an_id"]]?></td>
                                 <td><?php echo ($data["deadline"])?'Data: '.date("d-m-y",$data["deadline"]).'<br>':'';
                                    echo ($analysis["deadline"])?'Analysis: '.date("d-m-y",$analysis["deadline"]).'<br>':'';
                                    echo ($review["deadline"])?'Review: '.date("d-m-y",$review["deadline"]):'';
                                 ?></td>
                                
                              </tr>
                           <?php $count++; }
                            ?>                                    

                           </tbody>
                        </table>
                     </div>
                  </div>
                  <div class="row-fluid">
     <div class="span6">
              <form method="post" target="_blank" action="../excel/list_completed_analyst.php">
                <input type="hidden" name="date_from_p" value="<?php echo $date_low;?>">
                <input type="hidden" name="date_to_p"  value="<?php echo $date_high; ?>">
                <button type="submit" class="btn" style=""><i class="icon-share"></i> Export</button>
      
              </form>
            </div>      
				
	<!-- Button to trigger modal -->


<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body" id="modal-body">
    
  </div>
  <div class="modal-footer">

    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
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
}

</script>