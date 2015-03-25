<?php 
if(!isset($title)  || !isset($user_id)) {
		die('This page can not be viewed');
	}
include('../classes/UserClass.php');
$user = new User($user_id);
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<div class="span6">
         <h3 class="page-title">
            <?php echo $user->name;?>  
            <small></small>
         </h3>
      </div>
      <div class="span6" align="right">
         <a href="users.php?cat=2" class="btn bigicn-only" style="margin-top:10px;"><i class="m-icon-big-swapleft"></i></a>
      </div>
	</div>
   
   <?php
   if(isset($_GET["success"]))
   {
      switch($_GET["success"])
      {
         case (1):
               $text_class= 'alert-success';
               $text = 'User information is successfully updated.';
               break;
        case (2):
             $text_class= 'alert-success';
             $text = 'Package is successfully added.';
             break;
         case (0):
               $text_class= 'alert-error';
               $text = 'Error: Database error';
               break;
        case (3):
               $text_class= 'alert-error';
               $text = 'Invalid Package';
               break;
        case (4):
             $text_class= 'alert-success';
             $text = 'Companies are successfully added.';
             break;
        case (5):
             $text_class= 'alert-success';
             $text = 'User is successfully added.';
             break;
        case (6):
             $text_class= 'alert-error';
             $text = 'Some companies were already subscribed by user.';
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
                                 <h4><i class="icon-reorder"></i>Edit Details</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form id="update_form" action="<?php echo $folder?>process.php?cat=1&amp;uid=<?php echo $user_id;?>" class="form-horizontal " method="post" >
                                   
                                   <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Name</label>
                                       <div class="controls">
                                          <input type="text" name="name" id="name" value="<?php echo $user->name;?>" readonly/>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Username/Email</label>
                                       <div class="controls">
                                          <input type="text" name="email" id="email" value="<?php echo $user->email;?>" readonly/>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->

                                    <div class="row-fluid">
                                       
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Other Emails</label>
                                       <div class="controls">
                                          <input type="text" name="other_email" id="other_email" value="<?php echo $user->other_email;?>"/>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                   
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Address</label>
                                       <div class="controls">
                                          <textarea name="address" id="address" ><?php echo $user->address;?></textarea>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Mobile</label>
                                       <div class="controls">
                                         <input type="text" name="mobile" value="<?php echo $user->mobile;?>">
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Addon Users</label>
                                       <div class="controls">
                                         <input type="text" name="sub_users" id="sub_users" value="<?php echo $user->sub_users;?>">
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Customized</label>
                                       <div class="controls">
                                        <select name="customized">
                                          <option value="0" >No</option>
                                          <option value="1" <?php if($user->customized == 1) echo 'selected'; ?> >Yes</option>
                                        </select>
                                        
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->

                                    
                                   
                                    <!--/row-->
                                    <div class="form-actions">
                                       <button type="button" onclick="update_submit()" class="btn blue"><i class="icon-ok"></i> Update</button>
                                      
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
					</div>
				</div>
	
  <div class="row-fluid ">
          <div class="span12">
            <div class="portlet box blue">
                    <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>IT Details</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                   
                                   <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label"><i>IT Name</i></label>
                                       <div class="controls">
                                          <?php echo $user->itname;?>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label"><i>IT Email</i></label>
                                       <div class="controls">
                                          <?php echo $user->itemail;?>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->

                                    <div class="row-fluid">
                                       
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label"><i>IT Contact</i></label>
                                       <div class="controls">
                                          <?php echo $user->itcontact;?>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                   
                                       <div class="span6 ">
                                          
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                   
                                   
                              </div>
                           </div>
          </div>
        </div>

   <div class="row-fluid ">
               <div class="span12">
                  <div class="portlet box blue">
                          <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Packages</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form id="" action="<?php echo $folder?>process.php?cat=2&amp;uid=<?php echo $user_id;?>" class="form-horizontal " method="post" >
                                   
                                   <div class="row-fluid">
                                       <div class="span9 ">
                                         <div class="control-group">
                                       <label class="control-label">Package Name</label>
                                       <div class="controls">
                                          <?php
                                             $arr_pack = array();
                                              $sql_pack = mysql_query("SELECT package_id from users_package where user_id='$user_id' ");
                                              while($row_pack = mysql_fetch_array($sql_pack)){
                                                array_push($arr_pack, $row_pack["package_id"]);
                                              }
                                              $string_pack = implode(',', $arr_pack);
                                              ?>
                                              <select name="select_package" class="large m-wrap">
                                              <?php
                                              if($string_pack != ''){
                                                $sql_pack = mysql_query("SELECT package_id, package_name, package_year, package_type from package where package_id NOT IN ($string_pack) order by package_year desc");
                                                 } else {
                                                 $sql_pack = mysql_query("SELECT package_id, package_name, package_year, package_type from package order by package_year desc");
                                                 }
                                             
                                                 while ($row_pack = mysql_fetch_array($sql_pack)) {
                                                 echo '<option value="'.$row_pack["package_id"].'">'.$package_types[$row_pack["package_type"]].': '.$row_pack["package_name"].' ('.$fetch_period[$row_pack["package_year"]].')</option>';
                                              }
                                          ?></select>&nbsp;&nbsp;
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span3 ">
                                        <button type="submit" class="btn blue"><i class="icon-ok"></i> Add</button>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                 </form>
                                 <!-- END FORM--> 

                                 <table class="table table-hover tablesorter">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Package Name</th>
                                 <th>Package Type</th>
                                 <th>Package Year</th>
                                 <th></th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                                $sql_pack = mysql_query("SELECT package.package_id, package.package_name,package.package_year, package.package_type from package inner join users_package on package.package_id = users_package.package_id where users_package.user_id='$user_id' order by package.package_year desc");
                                  $count_pack =1;
                                  while($row_pack = mysql_fetch_array($sql_pack)){
                                    ?>
                                     <tr>
                                       <td><?php echo $count_pack?></td>
                                       <td><?php echo $row_pack["package_name"]?></td>
                                        <td><?php echo $package_types[$row_pack["package_type"]]?></td>
                                       <td><?php echo $fetch_period[$row_pack["package_year"]];?></td>
                                       <td>
                                        <button class="btn" data-toggle="modal" href="#myModal" onclick="upgrade_package(<?php echo $user_id ?>,<?php echo $row_pack["package_id"] ?>, <?php echo $row_pack["package_year"];?>)">Upgrade</button>
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
	
    <div class="row-fluid ">
               <div class="span12">
                  <div class="portlet box blue">
                          <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Additional Companies</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form id="company_form" action="<?php echo $folder?>process.php?cat=3&amp;uid=<?php echo $user_id;?>" class="form-horizontal " method="post" >
                                   
                                   <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                           <label class="control-label">Choose Company</label>
                                           <div class="controls">
                                              <select name="com_id_select[]" id="com_id_select" data-placeholder="Choose.." class="chosen-select" multiple style="width:350px;" >
                                                <?php
                                                $query_comp = mysql_query("SELECT com_id,com_bse_code, com_name from companies");
                                                while ($row_comp = mysql_fetch_array($query_comp)) {
                                                  echo '<option value="'.$row_comp["com_id"].'"">'.$row_comp["com_name"].' ('.$row_comp["com_bse_code"].')</option>';
                                                }
                                                ?>
                                                
                                              </select>
                                              <span class="help-block"></span>
                                           </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
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
                                    </div>
                                    <!--/row-->

                                    <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                           <label class="control-label">Choose Year</label>
                                           <div class="controls">
                                             <?php echo fetch_years('year');?>
                                              <span class="help-block"></span>
                                           </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                        <label class="control-label">
                                        <button type="button" onclick="company_submit()" class="btn blue"><i class="icon-ok"></i> Add</button>
                                       </label>
                                       </div>
                                       <!--/span-->
                                    </div>


                                 </form>
                                 <!-- END FORM--> 

                                 <table class="table table-hover tablesorter">
                           <thead>
                              <tr>
                                 <th>Year</th>
                                 <th>PA</th>
                                 <th>CGS</th>
                                <!-- <th>Research</th> -->
                              </tr>
                           </thead>
                           <tbody>
                              <?php


                                $sql_year = mysql_query("SELECT distinct year from users_companies where user_id='$user_id' order by year desc");
                                  $years = array();
                                  while($row_year = mysql_fetch_array($sql_year)){
                                    array_push($years, $row_year["year"]);
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
                                    /*
                                    $arr_comp =array();

                                    $sql_comp = mysql_query("SELECT companies.com_name, companies.com_bse_code from companies inner join users_companies on companies.com_id = users_companies.com_id where users_companies.user_id='$user_id' and users_companies.type='3' and users_companies.year='$yr' ");
                                    while ($row_comp = mysql_fetch_array($sql_comp)) {
                                      array_push($arr_comp, $row_comp["com_name"].' ('.$row_comp["com_bse_code"].')');
                                    }
                                    echo '<td>'.implode(', ', $arr_comp).'</td>';
                                    */
  
                                    echo '<tr>';
                                  }
                              ?>
                             
                             
                           </tbody>
                        </table>
                        

                              </div>
                           </div>
               </div>
            </div>

</div><!-- END CONTAINER -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Upgrade Package</h3>
  </div>
  <div class="modal-body" id="modal-body" style="">
   
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>  

<script type="text/javascript">


function update_submit(){
   if(validate_required_gen_idinfo($("#name").val(), 'name','Please input valid name') && validate_required_gen_idinfo($("#email").val(), 'email','Please input valid email') && validate_required_number_idinfo($("#sub_users").val(), 'sub_users','Please input valid number')) {
//      alert('yes');
      $('#update_form').submit();
   } 
   else
      return false;     
}

function company_submit(){
   if($("#com_id_select").val() != null){
      $('#company_form').submit();
   } 
   else{
    alert('Plase select some companies.');
    return false; 
   }
          
}

function upgrade_package(user_id,package_id,year){

   $("#modal-body").html("<p>Loading...</p>");
   var file = 'upgrade_package';
   $.post("ajax/"+ file +".php", {user_id:user_id,package_id:package_id,year:year}, function(data) {
      $("#modal-body").html(data);
   }); 

}
</script>