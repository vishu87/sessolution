<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
    <div class="span6"><h3 class="page-title">
      Packages  
      <small></small>
    </h3></div>
    <div class="span6" style="text-align:right">
      <a href="../excel/list_packages_company.php" class="btn" style="margin-top:20px" target="_blank"><i class="icon-share"></i> Export</a>
       
    </div>
  </div>
	<?php
	if(isset($_GET["success"]))
	{
		switch($_GET["success"])
		{
			
			case (0):
					$text_class= 'alert-error';
					$text = 'Error: Data;base error';
					break;
			case (3):
					$text_class= 'alert-success';
					$text = 'Company is successfully added';
					break;
			case (4):
					$text_class= 'alert-error';
					$text = 'Error: Duplicate company bse code';
					break;
		}
		echo '<div class="alert '.$text_class.'">
			<button class="close" data-dismiss="alert"></button>
			'.$text.'
			</div>';
	}
   
   if(isset($_POST["company_name"])) $search_company = mysql_real_escape_string($_POST["company_name"]);
   else $search_company ='';

	?>

           
            <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-globe"></i>List of Packages</h4>
                     </div>
                     <?php
                        $sql = mysql_query("select * from package ");
                        if(mysql_num_rows($sql) > 0) {
                     ?>
                     <div class="portlet-body">
                      
                       <table class="table table-hover tablesorter">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Package Name</th>
                                 <th>Package Type</th>
                                 <th>Year</th>
                                 <th>Visibility</th>
                                 <th>Actions</th>
                              </tr>
                           </thead>
                           <tfoot>
                              <tr>
                                 <th>#</th>
                                 <th>Package Name</th>
                                 <th>Package Type</th>
                                 <th>Year</th>
                                 <th>Visibility</th>
                                 <th>Actions</th>
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
                              <tr id="tr_<?php echo $row["package_id"];?>">
                                 <td><?php echo $count;?></td>
                                 <td id="package_<?php echo $row["package_id"];?>"><?php echo stripcslashes($row["package_name"]);?></td>
                                 <td id="package_type_<?php echo $row["package_id"];?>"><?php echo $package_types[$row["package_type"]];?></td>
                                 <td id="package_year_<?php echo $row["package_id"];?>"><?php echo $fetch_period[$row["package_year"]];?></td>
                                 <td id="package_visibility_<?php echo $row["package_id"];?>"><?php echo ($row["visibility"] == 0)?'Yes':'No'; ?></td>
                                 <td><a href="#myModal" role="button" class="btn" data-toggle="modal" onclick="view_companies('<?php echo $row["package_name"];?>','<?php echo $row["package_id"];?>');">Companies</a> 
                                  <a href="#myModalAdd" role="button" class="btn" data-toggle="modal" onclick="add_companies('<?php echo $row["package_name"];?>','<?php echo $row["package_id"];?>');">Add Company</a> 
                                  <a href="#myModal" role="button" class="btn" data-toggle="modal" onclick="view_users('<?php echo $row["package_name"];?>','<?php echo $row["package_id"];?>');">Users</a> 
                                  <a href="#myModal" role="button" class="btn" data-toggle="modal" onclick="edit_load('<?php echo $row["package_name"];?>','<?php echo $row["package_id"];?>');">Edit</a> 
                                  <a href="javascript:void(0)" onclick="delete_package('<?php echo $row["package_id"];?>')" class="btn red icn-only"><i class="icon-remove icon-white"></i></a></td>
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
                               <input type="text" placeholder="Select Company.." name="com_string" id="com_string" autocomplete="off" class="typehead span12" required >
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
         $ar_fields_all = array("package_id","package_name", "package_year", "visibility");
         $count_check =0;
          foreach ($ar_fields_all as $ar) {
            if($count_check != 0) echo ', ';
            echo $ar.": $('#".$ar."').val()";
            $count_check++;
          }
        ?>}, function(data) {

          if(data == 'success'){
            $("#package_"+ $("#package_id").val()).html($("#package_name").val());
            $("#package_type_"+ $("#package_id").val()).html($("#package_type").val());
            $("#package_year_"+ $("#package_id").val()).html($("#package_year").val()+'-'+(parseInt($("#package_year").val())+1));
            var visibility = ($("#visibility").val() == 1)?'No':'Yes';
            $("#package_visibility_"+ $("#package_id").val()).html(visibility);
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
       
      if( $("#com_string").val() != null ){
        var file = 'add_company_package';
        $("#modal-bodyAddResult").html('Processing... Please Wait');
         $.post("ajax/"+ file +".php", {pack_id:select_pack_id,<?php 
         $ar_fields_all = array("com_string");
         $count_check =0;
          foreach ($ar_fields_all as $ar) {
            if($count_check != 0) echo ', ';
            echo $ar.": $('#".$ar."').val()";
            $count_check++;
          }
        ?>}, function(data) {
          $(".typehead").val('');
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

function view_users(package_name, package_id){
   $("#myModalLabel").text(package_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'load_package_users';
   $.post("ajax/"+ file +".php", {id:package_id}, function(data) {
      $("#modal-body").html(data);
   }); 

}


function add_companies(package_name,package_id){
  $("#modal-bodyAddResult").html('');
  $(".typehead").val('');

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

function delete_package(package_id) {
     var file = 'delete_package';
        bootbox.confirm("Are you sure?", function(result) {
          if(result) {
            $.post("ajax/"+ file +".php", {pack_id:package_id}, function(data) {
                if(data == 'success') {
                  $('#tr_'+ package_id).hide("slow", function(){
                     $('#tr_'+ package_id).remove();
                  });
                }else  if(data == 'users'){
                  bootbox.alert("There are some users subscribed for this package");
                }
                 else {
                  bootbox.alert("Database error");
                }
             });
          }
          else {
          
          }
        });
  }

</script>