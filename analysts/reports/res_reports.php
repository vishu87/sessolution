<?php 
if(!isset($title)) {
      die('This page can not be viewed');
   }
?>
<div class="container-fluid">
   <!-- BEGIN PAGE HEADER-->
   <div class="row-fluid">
      <h3 class="page-title">
         Governance Research
         <small></small>
      </h3>
   </div>
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
                                  <th>#</th>
                                 <th>Company Name</th>
                                 <th>Publishing Date</th>
                                 <th>Heading</th>
                                
                                 <th>Report</th>
                                 
                              </tr>
                              
                           </thead>
                           <tfoot>
                              <tr>
                                <th>#</th>
                                 <th>Company Name</th>
                                 <th>Publishing Date</th>
                                 <th>Heading</th>
                                
                                 <th>Report</th>
                                 
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
                                                      
                            $sql = mysql_query("SELECT res_id from research order by publishing_date desc" );

                            if(mysql_num_rows($sql) > 0){
                            
                            
                              while($row = mysql_fetch_assoc($sql))
                           {
                            $res_report = new Research($row["res_id"]);
                           ?>
                              <tr id="tr_<?php echo $row["res_id"];?>">
                                 <td><?php echo $count;?></td>
                                 <td><?php echo $res_report->company_name;?></td>
                                 <td><?php echo $res_report->meeting_date;?></td>
                                  <td>
                                 <a href="javascript:;" class="ttip" rel="tooltip" title="<?php echo substr($res_report->description, 0, 100).''; ?>" style="text-decoration:none; color:inherit;"><?php echo $res_report->heading;?></a>
                                  </td>
                                 <td><?php 
                                  if($res_report->subscribed($member->parent)) {
                                    echo $res_report->report();
                                   } else {
                                   
                                   echo 'Not Subscribed';
                                  
                                }
                                 ?></td>
                                
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
         $.post("ajax/"+ file +".php", {report_id:proxy_id, report_type:'3'}, function(data) {
             $("#modal-body").html(data);
       });
}



</script>