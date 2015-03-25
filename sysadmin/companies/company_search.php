<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<div class="span6"><h3 class="page-title">
      Search &amp; Edit Company   
      <small></small>
    </h3></div>
    <div class="span6" style="text-align:right">
      <a href="../excel/list_companies.php" class="btn" style="margin-top:20px" target="_blank"><i class="icon-share"></i> Export</a>
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

	<div class="row-fluid search-forms search-default">
               <form class="form-search" action="" method="post">
                     
                     <input type="text"  class="typehead_comp span6" data-provide="typehead_comp" name="company_name" placeholder="Search for company name or bse code" value="<?php echo stripcslashes($search_company) ?>" autocomplete="off" required/>
                     <button type="submit" class="btn green offset1">Search &nbsp; <i class="m-icon-swapright m-icon-white"></i></button>
                  
               </form>
            </div>
            <?php if($search_company != ''): ?>
            <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-globe"></i>Search Results for Companies</h4>
                     </div>
                     <?php
                        $sql = mysql_query("select com_name, com_id, com_bse_code from companies where com_name LIKE '%{$search_company}%' OR com_bse_code LIKE '%{$search_company}' OR com_bse_srcip LIKE '%{$search_company}' OR com_nse_sym LIKE '%{$search_company}' OR com_reuters LIKE '%{$search_company}' OR com_bloomberg LIKE '%{$search_company}' OR com_isin LIKE '%{$search_company}' ");
                        if(mysql_num_rows($sql) > 0) {
                     ?>
                     <div class="portlet-body">
                       <table class="table table-hover tablesorter">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Company Name</th>
                                 <th>BSE Code</th>
                                 <th>Packages</th>
                                 <th>View</th>
                              </tr>
                           </thead>
                           <tfoot>
                              <tr>
                                 <th>#</th>
                                 <th>Company Name</th>
                                 <th>BSE Code</th>
                                 <th>Packages</th>
                                 <th>View</th>
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
                              <tr id="tr_<?php echo $row["com_id"];?>">
                                 <td><?php echo $count;?></td>
                                 <td id="com_<?php echo $row["com_id"];?>"><?php echo stripcslashes($row["com_name"]);?></td>
                                 <td><?php 
                                    echo stripcslashes($row["com_bse_code"]);
                                    $row["com_name"] = name_filter($row["com_name"]);
                                 ?>
                                 </td>
                                 <td><?php 
                                  $sql_pack = mysql_query("SELECT package.package_name, package.package_year from package inner join package_company on package_company.package_id = package.package_id where package_company.com_id='$row[com_id]' ");
                                  $count_pack =0;
                                  while ($row_pack = mysql_fetch_array($sql_pack)) {
                                    if($count_pack != 0) echo ', ';
                                    echo stripcslashes($row_pack["package_name"]).' ('.$fetch_period[$row_pack["package_year"]].')';
                                    $count_pack++;
                                  }
                                 ?></td>
                                 <td><a href="#myModal" role="button" class="btn" data-toggle="modal" onclick="view_load('<?php echo $row["com_name"];?>','<?php echo $row["com_id"];?>');">View</a> <a href="#myModal" role="button" class="btn" data-toggle="modal" onclick="edit_load('<?php echo $row["com_name"];?>','<?php echo $row["com_id"];?>');">Edit</a> <a href="javascript:void(0)" onclick="delete_company('<?php echo $row["com_id"];?>')" class="btn red icn-only"><i class="icon-remove icon-white"></i></a></td>
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
                  
            <?php endif; ?>
				
	<!-- Button to trigger modal -->


<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body">
    
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>			

</div>

<script type="text/javascript">

function check_edit_submit(){
      if(validate_com_name($('#com_name').val(),'com_name','Please input valid name') ){
        var file = 'update_company';

         $.post("ajax/"+ file +".php", {<?php 
         $ar_fields_all = array("com_id","com_name","com_bse_srcip","com_nse_sym","com_reuters","com_bloomberg","com_isin","com_address","com_telephone","com_website","com_sec_email","com_full_name");
         $count_check =0;
          foreach ($ar_fields_all as $ar) {
            if($count_check != 0) echo ', ';
            echo $ar.": $('#".$ar."').val()";
            $count_check++;
          }
        ?>}, function(data) {
          if(data == 'success'){
            $("#com_"+ $("#com_id").val()).html($("#com_name").val());
             $(".modal-body").html("Successfully Updated.");
          } else{
            $(".modal-body").html("Database error: Try Again.");
          }
          
       }); 

      } else {
         return false;
      }
}

function view_load(company_name, company_id){
   $("#myModalLabel").text(company_name); 
   $(".modal-body").html("<p>Loading...</p>");
   var file = 'load_company_view';

   $.post("ajax/"+ file +".php", {id:company_id}, function(data) {
      $(".modal-body").html(data);
   }); 

}

function edit_load(company_name, company_id){
   $("#myModalLabel").text("Edit Details"); 
   $(".modal-body").html("<p>Loading...</p>");
   var file = 'load_company_edit';

   $.post("ajax/"+ file +".php", {id:company_id}, function(data) {
      $(".modal-body").html(data);
   }); 

}

function delete_company(company_id) {
     var file = 'delete_company';
        bootbox.confirm("Are you sure?", function(result) {
          if(result) {
            $.post("ajax/"+ file +".php", {id:company_id}, function(data) {
                if(data == 'success') {
                  $('#tr_'+ company_id).hide("slow");
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