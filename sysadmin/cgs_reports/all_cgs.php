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
    <div class="span6"><h3 class="page-title">
      Governance Scores
      <small></small>
    </h3></div>
    <div class="span6">
      <form style="margin-top:30px; text-align:center" action="?cat=2" method="post">
        <input type="text" name="date_from" class="datepicker_month span3" value="<?php echo $_POST["date_from"]?>" placeholder="From">&nbsp;-&nbsp;<input type="text" name="date_to" class="datepicker_month span3" value="<?php echo $_POST["date_to"]?>" placeholder="To">&nbsp;<button type="submit" class="btn blue icn-only" style="margin-top:-10px;"><i class="m-icon-swapright m-icon-white"></i></button>
      </form>
    </div>
  </div>

            <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-globe"></i>CGS List</h4>
                     </div>
                     <?php
                     if($date_high && $date_low){
                         $date_sql = "where publishing_date <= '$date_high' and publishing_date >= '$date_low' ";
                        } elseif ($date_high && !$date_low) {
                          $date_sql = "where publishing_date <= '$date_high' ";
                        } elseif(!$date_high && $date_low) {
                          $date_sql = "where publishing_date >= '$date_low' ";
                        } else {
                          $date_sql = '';
                        }

                        $sql = mysql_query("SELECT cgs_id from cgs ".$date_sql." order by publishing_date desc" );
                        if(mysql_num_rows($sql) > 0) {

                     ?>
                     <div class="portlet-body">
                       <table class="table table-hover tablesorter">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Company Name</th>
                                 <th>Publishing Date</th>
                                  <th>Year</th>
                                 <th>Govt Index</th>
                                 <th>India Man.</th>
                                 <th>Report</th>
                                 <th>Subscribers</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tfoot>
                              <tr>
                                <th>#</th>
                                 <th>Company Name</th>
                                 <th>Publishing Date</th>
                                  <th>Year</th>
                                 <th>Govt Index</th>
                                 <th>India Man.</th>
                                 <th>Report</th>
                                 <th>Subscribers</th>
                                 <th>Action</th>
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
                            </tfoot>
                           <tbody>
                           <?php
                           
                           $count =1;
                           while($row = mysql_fetch_array($sql))
                           {
                            $cgs_report = new CGS_admin($row["cgs_id"]);
                           ?>
                              <tr id="tr_<?php echo $cgs_report->id;?>">
                                 <td><?php echo $count;?></td>
                                 <td><?php echo $cgs_report->company_name;
                                  $cgs_report->company_name = name_filter($cgs_report->company_name);
                                 ?></td>
                                 <td><?php echo $cgs_report->meeting_date;?></td>
                                 <td><?php echo $fetch_period[$cgs_report->year];?></td>
                                 <td><?php echo $cgs_report->govt_index;?></td>
                                 <td><?php echo $cgs_report->india_man;?></td>
                                 <td><?php echo $cgs_report->report(); ?></td>
                                 <td><?php 
                                  $cgs_report->subscribers($count);
                                 ?>
                                 
                               </td>
                                 <td>
                                  <?php
                                  $cgs_report->edit($count);
                                  $cgs_report->release($count);
                                  $cgs_report->delete($count);
                                  
                                ?>
                                </td>
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
            <div class="span6">
              <form method="post" target="_blank" action="../excel/list_cgs.php">
                <input type="hidden" name="date_from_p" value="<?php echo $date_low;?>">
                <input type="hidden" name="date_to_p"  value="<?php echo $date_high; ?>">
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
function view_users(report_id, company_id, company_name, year){
   $("#myModalLabel").text(company_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'load_cgs_subscribers';
   $.post("ajax/"+ file +".php", {report_id:report_id, id:company_id, year: year}, function(data) {
      $("#modal-body").html(data);
   }); 

}

//used
function edit_cgs(count,cgs_id, company_name){
   $("#myModalLabel").text(company_name); 
    $("#close_button").attr('onclick','refresh_tr_cgs('+count+','+cgs_id+')');
  $("#modal-body").html('<iframe src="cgs_reports/edit.php?id=' +cgs_id+ '" style="border:0; width:100%; height:300px;"></iframe>');
   
   /*var file = 'load_cgs_subscribers';
   $.post("ajax/"+ file +".php", {id:company_id, year: year}, function(data) {
      $("#modal-body").html(data);
   }); */ 

}
//used
function refresh_tr_cgs(count,report_id){
  var file = 'refresh_cgs';

  var c_class = $("#tr_"+report_id).attr('class');
  $("#tr_"+report_id).removeClass(c_class);
  $("#tr_"+report_id).animate({backgroundColor:'#ffff00'},{duration:500});


   $.post("ajax/"+ file +".php", {count:count, id:report_id}, function(data) {
      $("#tr_"+report_id).html(data);
       $("#close_button").removeAttr('onclick');
       $("#tr_"+report_id).animate({backgroundColor:''},{duration:500});
      $("#tr_"+ report_id).addClass(c_class,{duration:500});

   });
}

//used
function delete_cgs(cgs_id) {
     var file = 'delete_cgs';
        bootbox.confirm("Are you sure?", function(result) {
          if(result) {
            $.post("ajax/"+ file +".php", {id:cgs_id}, function(data) {
                if(data == 'success') {
                  $('#tr_'+ cgs_id).hide("slow");
                } else {
                  alert("Database error");
                }
             });
          }
          else {
          
          }
        });
  }
//used
function add_sub_ui(count,company_id, company_name, report_id,year){
  var type =2;
   $("#myModalLabel").text(company_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'load_subscribers_ui';
   $.post("ajax/"+ file +".php", {count:count,company_id:company_id, year: year, report_id:report_id, type:type}, function(data) {
      $("#modal-body").html(data);
   }); 

}

function sub_add_submit(count,report_id,company_id,year,type) {
if($("#user_id_sub").val()){
  $("vote_s").html("Adding");
  $("vote_s").removeAttr("onclick");
  
   var file = 'add_subscriber';
   $.post("ajax/"+ file +".php", {company_id:company_id, year: year, report_id:report_id, type:type, user_id:$("#user_id_sub").val()}, function(data) {
      if(data == 'success'){
        $("#close_button").trigger('click');
        refresh_tr_cgs(count, report_id);
      } else {
          bootbox.alert("This user is already subscribed for this report!");
      }
   });

} else {
  alert("Please select a user");
}
}

function release_cgs(count, cgs_id){
 
  bootbox.confirm("Are you sure to release cgs?", function(result) {
      if(result) {
         $("#release_"+cgs_id).html("Releasing..");
          $("#release_"+cgs_id).removeAttr("onclick");

          var file = 'release_cgs';
           $.post("ajax/"+ file +".php", {id:cgs_id}, function(data) {
            if(data == 'success') {
             refresh_tr_cgs(count, cgs_id);
            } else {
              alert("Database error");
            }

         });
      }
      else {
      
      }
    });

}

function unrelease_cgs(count, cgs_id){
 
  bootbox.confirm("Are you sure to unrelease cgs?", function(result) {
      if(result) {
         $("#release_"+cgs_id).html("Unreleasing..");
          $("#release_"+cgs_id).removeAttr("onclick");

          var file = 'unrelease_cgs';
           $.post("ajax/"+ file +".php", {id:cgs_id}, function(data) {
            if(data == 'success') {
             refresh_tr_cgs(count, cgs_id);
            } else {
              alert("Database error");
            }

         });
      }
      else {
      
      }
    });

}


</script>