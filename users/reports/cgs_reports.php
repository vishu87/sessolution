<?php 
if(!isset($title)) {
      die('This page can not be viewed');
   }
?>
<div class="container-fluid">
   <!-- BEGIN PAGE HEADER-->
   <div class="row-fluid">
      <h3 class="page-title">
         Governance Scores
         <small></small>
      </h3>
   </div>
   <?php 
      $years = array();
      $year_sql = mysql_query("SELECT year_sh from years order by year_sh desc");
      while ($row_yr = mysql_fetch_array($year_sql)) {
        array_push($years, $row_yr["year_sh"]);
      }
   ?>

            <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-reorder"></i>List of Companies</h4>
                         <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                     </div>
                     <div class="portlet-body">
                       <table class="table table-stripped tablesorter">
                           <thead>
                              <tr>
                                  <th>SN</th>
                                 <th>Company Name</th>
                                 <th>Publishing Date</th>
                                 <th>Report</th>
                                 <th>Action</th>
                              </tr>
                              
                           </thead>
                           <tfoot>
                              <tr>
                                <th>SN</th>
                                 <th>Company Name</th>
                                 <th>Publishing Date</th>
                                 <th>Report</th>
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
                           
                            $sql = mysql_query("SELECT cgs.cgs_id, companies.com_name from cgs inner join companies on cgs.com_id = companies.com_id order by cgs.publishing_date desc" );

                            if(mysql_num_rows($sql) > 0){
                            
                            
                              while($row = mysql_fetch_assoc($sql))
                           {
                            $cgs_report = new CGS($row["cgs_id"]);
                           ?>
                              <tr id="tr_<?php echo $row["cgs_id"];?>">
                                 <td><?php echo $count;?></td>
                                 <td><?php echo $cgs_report->company_name;?></td>
                                 <td><?php echo $cgs_report->meeting_date;?></td>
                                 <td id="sub_<?php echo $cgs_report->id ?>"><?php 
                                 $member->cgs_subscribed_comapnies_year($user_id, $cgs_report->year);

                                 if($cgs_report->subscribed($member->cgs_companies_subscribed_year)) {
                                    echo $cgs_report->report();
                                 } else {
                                    if($cgs_report->subscription_request()) echo '<a href="javascript:;" class="btn green span12" style="max-width:100px;"  onclick="subscribe('.$cgs_report->id.','.$cgs_report->company_id.',2)">Subscribe</a>';
                                         else echo 'Subscription Requested';
                                 }


                                 ?>
                                 </td>
                                 <td>
                                    <a href="#myModal" role="button" class="btn blue span12" style="max-width:100px;" data-toggle="modal" onclick="view_report('<?php echo $cgs_report->company_name;?>','<?php echo $cgs_report->id;?>');">Details</a> 

                                  </td>
                                
                              </tr>
                           <?php $count++; }
                            }
                           
                           ?>
                           </tbody>
                        </table>
                     </div>
                     <?php
                      
                     ?>
                  </div>
                  
           
            
   <!-- Button to trigger modal -->


<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:750px;">
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

var select_pack_id = 0;

function view_report(company_name, proxy_id){
   $("#myModalLabel").text(company_name); 
       $("#modal-body").html("<p>Loading...</p>");
        var file = 'view_report';
         $.post("ajax/"+ file +".php", {report_id:proxy_id, report_type:'2'}, function(data) {
             $("#modal-body").html(data);
       });
}
function subscribe(report_id, com_id, report_type){
  var file = "sub_request";
  $.post("ajax/"+ file +".php", {report_id:report_id, com_id:com_id, report_type: report_type}, function(data) {
        bootbox.alert(data);
        $("#sub_"+report_id).html("Subscription requested");
   }); 
}

</script>