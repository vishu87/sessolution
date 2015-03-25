<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
  $user_id = $_SESSION["MEM_ID"];
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
  <div class="row-fluid">
    <h3 class="page-title">
      Subscriptions
      <small></small>
    </h3>
  </div>
<?php
  if(isset($_GET["success"]))
  {
    switch($_GET["success"])
    {
      
      case (0):
          $text_class= 'alert-error';
          $text = 'Error: Database error';
          break;
      case (1):
          $text_class= 'alert-success';
          $text = 'Successfully Requested.';
          break;
    }
    echo '<div class="alert '.$text_class.'">
      <button class="close" data-dismiss="alert"></button>
      '.$text.'
      </div>';
  }
  
  ?>

	<div class="row-fluid ">
               <div class="span12">
                  <div class="portlet box blue">
                          <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Currently Subscribed Packages</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                              
                          <table class="table table-hover tablesorter">
                           <thead>
                              <tr>
                                 <th>SN</th>
                                 <th>Package Name</th>
                                  <th>Package Type</th>
                                 <th>Package Year</th>
                                  <th>Companies</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                                $sql_pack = mysql_query("SELECT package.package_id,package.package_name,package.package_year,package.package_type from package inner join users_package on package.package_id = users_package.package_id where users_package.user_id='$user_id' order by package.package_year desc");
                                  $count_pack =1;
                                  while($row_pack = mysql_fetch_array($sql_pack)){
                                    ?>
                                     <tr>
                                       <td><?php echo $count_pack?></td>
                                       <td><?php echo $row_pack["package_name"]?></td>
                                       <td><?php echo $package_types[$row_pack["package_type"]];?></td>
                                       <td><?php echo $fetch_period[$row_pack["package_year"]];?></td>
                                       <td><a href="#myModal" role="button" class="btn" data-toggle="modal" onclick="view_companies('<?php echo $row_pack["package_name"];?>','<?php echo $row_pack["package_id"];?>');">Companies</a> 

                                        <button class="btn" data-toggle="modal" href="#myModal" onclick="upgrade_package(<?php echo $row_pack["package_id"];?>,<?php echo $row_pack["package_year"];?>,'<?php echo $row_pack["package_name"];?>')">Upgrade</button>
                                       </td>
                                        
                                    </tr>
                                    <?php
                                    
                                    $count_pack++;
                                  }
                              ?>
                             
                             
                           </tbody>
                        </table>

                              </div>
                           </div>
               </div>
            </div>
  
    <div class="row-fluid" >
               <div class="span12">
                  <div class="portlet box blue">
                          <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Request Additional Companies/Reports</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                  <form id="company_form" action="<?php echo $folder?>process.php?cat=2" class="form-horizontal " method="post" >
                                   
                                   <div class="row-fluid" style="margin-top: 10px;">
                                       <div class="span5 ">
                                         <div class="control-group">
                                           <label class="control-label">Choose Company</label>
                                           <div class="controls">
                                              <input type="text" placeholder="Select Company.."  name="com_string" id="com_string" autocomplete="off" class="typehead" required  />   
                              
                                              <span class="help-block"></span>
                                           </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span5 ">
                                        <div class="control-group">
                                           <label class="control-label">Choose Type</label>
                                           <div class="controls">
                                              <select name="type" id="type">
                                                <?php
                                                $query_comp = mysql_query("SELECT * from report_type");
                                                while ($row_comp = mysql_fetch_array($query_comp)) {
                                                  echo '<option value="'.$row_comp["type_id"].'"">'.$row_comp["type_name"].'</option>';
                                                }
                                                ?>
                                              </select>
                                              <span class="help-block"></span>
                                           </div>
                                          </div>
                                       </div>
                                       <!--/span-->

                                       <div class="span2 ">
                                        <div class="control-group pull-right">
                                           <button type="button" onclick="company_submit()" class="btn blue"><i class="icon-ok"></i> Request</button>
                                          </div>
                                       </div>
                                       <!--/span-->

                                    </div>
                                    <!--/row-->

                                 </form>
                              </div>
                           </div>
               </div>
            </div>	


            <div class="portlet box light-grey">
                          <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>List of Subscribed Additional Companies/ Reports</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body">
                                <table class="table table-hover tablesorter">
                                 <thead>
                                    <tr>
                                       <th>Year</th>
                                       <th width="30%">PA</th>
                                       <th width="30%">CGS</th>
                                       <th width="30%">Research Reports</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php


                                      $sql_year = mysql_query("SELECT year_sh from years order by year_sh desc");
                                        $years = array();
                                        while($row_year = mysql_fetch_array($sql_year)){
                                          array_push($years, $row_year["year_sh"]);
                                        }

                                        foreach ($years as $yr) {
                                          echo '<tr>';
                                          echo '<td>'.$fetch_period[$yr].'</td>';

                                          $arr_comp =array();

                                          $sql_comp = mysql_query("SELECT companies.com_name, companies.com_bse_code from companies inner join users_companies on companies.com_id = users_companies.com_id where users_companies.user_id='$user_id' and users_companies.type='1' and users_companies.year='$yr' ");
                                          while ($row_comp = mysql_fetch_array($sql_comp)) {
                                              array_push($arr_comp, $row_comp["com_name"].' ('.$row_comp["com_bse_code"].')');
                                          
                                          }
                                          echo '<td>'.implode(', ', $arr_comp).'</td>';

                                          $arr_comp =array();

                                          $sql_comp = mysql_query("SELECT companies.com_name, companies.com_bse_code from companies inner join users_companies on companies.com_id = users_companies.com_id where users_companies.user_id='$user_id' and users_companies.type='2' and users_companies.year='$yr' ");
                                          while ($row_comp = mysql_fetch_array($sql_comp)) {
                                            array_push($arr_comp, $row_comp["com_name"].' ('.$row_comp["com_bse_code"].')');
                                          }
                                          echo '<td>'.implode(', ', $arr_comp).'</td>';

                                          $arr_comp =array();

                                          $sql_comp = mysql_query("SELECT research.heading from research inner join research_users on research.res_id = research_users.res_id where research_users.user_id='$user_id' and research.year='$yr' ");
                                          while ($row_comp = mysql_fetch_array($sql_comp)) {
                                            array_push($arr_comp, $row_comp["heading"]);
                                          }
                                          echo '<td>'.implode('<br>', $arr_comp).'</td>';

                                          echo '<tr>';
                                        }
                                    ?>
                                   
                                   
                                 </tbody>
                              </table>
                               
                              </div>
                           </div>

            <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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


function view_companies(package_name, package_id){
   $("#myModalLabel").text(package_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'load_package_companies';
   $.post("ajax/"+ file +".php", {id:package_id}, function(data) {
      $("#modal-body").html(data);
   }); 

}

function company_submit(){
   if($("#com_string").val() != null){
      $('#company_form').submit();
   } 
   else{
    alert('Plase select some companies.');
    return false; 
   }
          
}


function upgrade_package(package_id,year,package_name){
    $("#myModalLabel").html(package_name);
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'upgrade_package';
   $.post("ajax/"+ file +".php", {package_id:package_id,year:year, package_name:package_name}, function(data) {
      $("#modal-body").html(data);
   }); 

}

</script>