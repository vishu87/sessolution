<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
  if($type == 0){
    $query = mysql_query("SELECT * from subscription_request where status='0' order by add_date asc");
  } elseif($type == 1){
     $query = mysql_query("SELECT * from subscription_request where status='1'  order by add_date asc");
  } else {
    die();
  }
 ?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
    <div class="span4">
      <h3 class="page-title">
     Subscription Requests
      <small></small>
    </h3>
    </div>
    <div class="span4">
     
    </div>
    <div class="span4">
      <div class="btn-group" style="padding-top:25px; float:right">
        <a href="subscription_req.php?cat=1" class="btn blue <?php echo ($type ==0)?'active':''; ?>">Pending</a>
        <a href="subscription_req.php?cat=1&amp;type=1" class="btn blue <?php echo ($type ==1)?'active':''; ?>">Resolved</a>
      </div>
    </div>
		
	</div>
 

            <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-globe"></i>Requests</h4>
                     </div>
                     
                     <div class="portlet-body">
                       <table class="table table-stripped tablesorter">
                           <thead>
                              <tr>
                                 <th></th>
                                 <th>Company Name</th>
                                 <th>Package</th>
                                 <th>Report Type</th>
                                 <th>User Name</th>
                                 <th>Contact</th>
                                 <th>Request Date</th>
                                 <th></th>
                              </tr>
                              
                           </thead>
                           <tfoot>
                              <tr>
                                <th></th>
                                 <th>Company Name</th>
                                 <th>Package</th>
                                 <th>Report Type</th>
                                 <th>User Name</th>
                                 <th>Contact</th>
                                 <th>Request Date</th>
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
                              
                            </tfoot>
                           <tbody>
                            <?php
                            $count =1;
                            while ($row = mysql_fetch_array($query)) {
                              if($row["report_id"] != 0){
                                if($row["report_type"] == 1){
                                $rep_query = mysql_query("SELECT  companies.com_name, users.name, users.email, users.mobile from subscription_request inner join proxy_ad on subscription_request.report_id = proxy_ad.id inner join companies on proxy_ad.com_id = companies.com_id inner join users on subscription_request.user_id = users.id where subscription_request.id='$row[id]' ");
                               } elseif($row["report_type"] == 2) {
                                 $rep_query = mysql_query("SELECT  companies.com_name, users.name, users.email, users.mobile from subscription_request inner join cgs on subscription_request.report_id = cgs.cgs_id inner join companies on cgs.com_id = companies.com_id inner join users on subscription_request.user_id = users.id where subscription_request.id='$row[id]' ");

                                } elseif($row["report_type"] == 3) {
                                 $rep_query = mysql_query("SELECT  companies.com_name, users.name, users.email, users.mobile from subscription_request inner join research on subscription_request.report_id = research.res_id inner join companies on research.com_id = companies.com_id inner join users on subscription_request.user_id = users.id where subscription_request.id='$row[id]' ");
                               } else die();
                             } elseif($row["new_package"] == 0) {
                              if($row["report_type"] == 1){
                                $rep_query = mysql_query("SELECT  companies.com_name, users.name, users.email, users.mobile from subscription_request inner join companies on subscription_request.com_id = companies.com_id inner join users on subscription_request.user_id = users.id where subscription_request.id='$row[id]' ");
                               } elseif($row["report_type"] == 2) {
                                 $rep_query = mysql_query("SELECT  companies.com_name, users.name, users.email, users.mobile from subscription_request inner join companies on subscription_request.com_id = companies.com_id inner join users on subscription_request.user_id = users.id where subscription_request.id='$row[id]' ");

                                }  else die();
                             } else {
                                 $rep_query = mysql_query("SELECT  name, email, mobile from  users where id='$row[user_id]' ");

                                 $new_pack_sql = mysql_query("SELECT package_name,package_year from package where package_id='$row[new_package]' ");
                                 $new_pack = mysql_fetch_array($new_pack_sql);
                                 $old_pack_sql = mysql_query("SELECT package_name,package_year from package where package_id='$row[old_package]' ");
                                 $old_pack = mysql_fetch_array($old_pack_sql);
                             }


                               $row_rep = mysql_fetch_array($rep_query);
                            ?>
                            <tr id="tr_<?php echo $row["id"]; ?>" >
                              <td><?php echo $count; ?></td>
                               <td><?php echo $row_rep["com_name"];  ?></td>
                               <td><?php echo ($row["new_package"] == 0)?'':$old_pack["package_name"].' ('.$fetch_period[$old_pack["package_year"]].') >> '.$new_pack["package_name"].' ('.$fetch_period[$new_pack["package_year"]].')';  ?></td>
                               <td><?php echo $report_types[$row["report_type"]];?></td>
                                 <td><a href="users.php?cat=3&amp;uid=<?php echo $row["user_id"]; ?>" target="_blank"><?php echo $row_rep["name"];?></a></td>
                                 <td><?php echo $row_rep["email"].'<br>'.$row_rep["mobile"];  ?></td>
                                 <td><?php echo date("d-M-y h:i:s A",$row["add_date"]);  ?></td>
                                 <td><a href="javascript:;" onclick="move_complete(<?php echo $row["id"] ?>,<?php echo ($type==0)?'1':'0'; ?>)" class="btn" id="btn_<?php echo $row["id"]; ?>"><?php echo ($type == 0)?'Mark Resolved':'Mark Unresolved'; ?></a></td>
                            </tr>
                            <?php 
                            $count++;
                          }
                            ?>                                

                           </tbody>
                        </table>
                     </div>
                  </div>
                  
             			
	<!-- Button to trigger modal -->

</div>

<script type="text/javascript">

function move_complete(sub_req_id, move_status){
  $("#btn_"+sub_req_id).html("Marking..");
    $("#btn_"+sub_req_id).removeAttr('onclick');
        var file = 'move_complete';
         $.post("ajax/"+ file +".php", {sub_req_id:sub_req_id, move_status:move_status}, function(data) {
             if(data == 'success'){
              $("#tr_"+sub_req_id).hide("slow", function(){ $("#tr_"+sub_req_id).remove(); });
             }
             else {
              alert("Database Error or mail did not send. Details: "+data);
             }
       });
}


</script>