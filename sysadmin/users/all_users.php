<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
    <div class="span6"><h3 class="page-title">
      Users 
      <small></small>
    </h3></div>
    <div class="span6" style="text-align:right">
      <a href="../excel/list_users.php" class="btn" style="margin-top:20px" target="_blank"><i class="icon-share"></i> Export</a>
      </div>
  </div>

            <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-globe"></i>List of Users</h4>
                     </div>
                     <?php
                        $sql = mysql_query("select id,name,email, active from users where created_by_prim = 0 ");
                        if(mysql_num_rows($sql) > 0) {
                     ?>
                     <div class="portlet-body">
                       <table class="table table-hover tablesorter">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Company Name</th>
                                 <th>Email</th>
                                 <th>Base Packages</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tfoot>
                              <tr>
                                 <th>#</th>
                                 <th>Company Name</th>
                                 <th>Email</th>
                                 <th>Base Packages</th>
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
                           while($row = mysql_fetch_assoc($sql))
                           {
                           ?>
                              <tr id="tr_<?php echo $row["id"];?>">
                                 <td><?php echo $count;?></td>
                                 <td><?php echo stripcslashes($row["name"]);?></td>
                                 <td><?php echo stripcslashes($row["email"]);?></td>
                                 <td><?php 
                                  $sql_pack = mysql_query("SELECT package.package_name,package.package_year from package inner join users_package on package.package_id = users_package.package_id where users_package.user_id='$row[id]' order by package.package_year desc");
                                  $count_pack =0;
                                  while($row_pack = mysql_fetch_array($sql_pack)){
                                    if($count_pack !=0) echo ', ';
                                    echo stripcslashes($row_pack["package_name"]).' ('.$fetch_period[$row_pack["package_year"]].')';
                                    $count_pack++;
                                  }


                                 ?></td>
                                 <td><a href="users.php?cat=3&amp;uid=<?php echo $row["id"]?>" class="btn" data-toggle="modal">View</a>

                                  <?php
                                if($row["active"] == 0){
                                  ?>
                                    <a href="<?php echo $folder?>process.php?cat=5&amp;id=<?php echo $row["id"]?>" class="btn red" data-toggle="modal">Block</a>
                                  <?php
                                } else {
                                  ?>
                                  <a href="<?php echo $folder?>process.php?cat=6&amp;id=<?php echo $row["id"]?>" class="btn yellow" data-toggle="modal">UnBlock</a>
                                  <?php
                                }
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
                  
           
				
	<!-- Button to trigger modal -->


<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

<div id="myModalAdd" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabelAdd">Add Company</h3>
  </div>
  <div class="modal-body" id="modal-bodyAdd" style="min-height:400px">
    <div class="portlet box blue">
                     <div class="portlet-title">
                        <h4><i class="icon-reorder"></i>Add Companies</h4>
                     </div>
                     <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form class="form-horizontal">
                           
                           <div class="control-group">
                              <div class="controls" style="margin:0px auto" >
                                <select name="com_id_select" id="com_id_select" data-placeholder="Choose.." class="chosen-select" multiple style="width:350px;" >
                                  <?php
                                  $query_comp = mysql_query("SELECT com_id,com_bse_code, com_name from companies");
                                  while ($row_comp = mysql_fetch_array($query_comp)) {
                                    echo '<option value="'.$row_comp["com_id"].'"">'.$row_comp["com_name"].' ('.$row_comp["com_bse_code"].')</option>';
                                  }
                                  ?>
                                  
                                </select>
                              </div>
                           </div>
                          
                           <div class="form-actions">
                              <button type="button" class="btn blue" onclick="add_company_submit()">Add</button>
                           </div>
                        </form>
                        
                        <!-- END FORM-->
                        <div id="modal-bodyAddResult">
                        </div>           
                     </div>
                  </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>  



</div>

<script type="text/javascript">
var select_pack_id = 0;
function check_edit_submit(){
      if(validate_name($('#package_name').val(),'com_name','Please input valid name') ){
        var file = 'update_package';

         $.post("ajax/"+ file +".php", {<?php 
         $ar_fields_all = array("package_id","package_name", "package_year");
         $count_check =0;
          foreach ($ar_fields_all as $ar) {
            if($count_check != 0) echo ', ';
            echo $ar.": $('#".$ar."').val()";
            $count_check++;
          }
        ?>}, function(data) {

          if(data == 'success'){
            $("#package_"+ $("#package_id").val()).html($("#package_name").val());
            $("#package_year_"+ $("#package_id").val()).html($("#package_year").val());
             $("#modal-body").html("Successfully Updated.");
          } else{
            $("#modal-body").html("Database error: Try Again.");
          }
          
       }); 

      } else {
         return false;
      }
}

function add_company_submit(){
      //alert($("#com_id_select").val());
       
      if( $("#com_id_select").val() != null ){
        var file = 'add_company_package';
        $("#modal-bodyAddResult").html('Processing... Please Wait');
         $.post("ajax/"+ file +".php", {pack_id:select_pack_id,<?php 
         $ar_fields_all = array("com_id_select");
         $count_check =0;
          foreach ($ar_fields_all as $ar) {
            if($count_check != 0) echo ', ';
            echo $ar.": $('#".$ar."').val()";
            $count_check++;
          }
        ?>}, function(data) {
            $("#modal-bodyAddResult").html(data);
       }); 

      } else {
        alert("Please select a company");
         return false;
      }
}

function view_companies(package_name, package_id){
   $("#myModalLabel").text(package_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'load_package_companies';
   $.post("ajax/"+ file +".php", {id:package_id}, function(data) {
      $("#modal-body").html(data);
   }); 

}
function add_companies(package_name,package_id){
  $("#modal-bodyAddResult").html('');
  $(".chosen-select").val('').trigger("liszt:updated");

  $("#myModalLabelAdd").text(package_name); 
   select_pack_id = package_id;
}
/*
function add_companies(package_name,package_id){
   $("#myModalLabel").text(package_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'load_add_companies';
   $.post("ajax/"+ file +".php", {id:package_id}, function(data) {
      $("#modal-body").html(data);
   }); 

}*/

function edit_load(package_name,package_id){
   $("#myModalLabel").text("Edit Details"); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'load_package_edit';

   $.post("ajax/"+ file +".php", {id:package_id}, function(data) {
      $("#modal-body").html(data);
   }); 

}

function delete_company(package_id,company_id) {
     var file = 'delete_company_package';
        bootbox.confirm("Are you sure?", function(result) {
          if(result) {
            $.post("ajax/"+ file +".php", {id:company_id, pack_id:package_id}, function(data) {
                if(data == 'success') {
                  $('#tr_com_'+ company_id).hide("slow");
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