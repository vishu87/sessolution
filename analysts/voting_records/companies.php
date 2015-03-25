<?php 
if(!isset($title)  || !isset($user_id)) {
		die('This page can not be viewed');
	}

?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<div class="span6">
         <h3 class="page-title">
           Create Portfolio 
            <small></small>
         </h3>
      </div>
      
      <div class="span6" style="padding-top:20px;">
        <a href="../excel/list_companies_portfolio_analyst.php" class="btn pull-right">Export List</a>
      </div>

	</div>
   
   <?php
   if(isset($_GET["success"]))
   {
      switch($_GET["success"])
      {
         case (1):
               $text_class= 'alert-success';
               $text = 'Companies are successfully added.';
               break;
        case (2):
             $text_class= 'alert-error';
             $text = 'Some Companies are already present in wishlist. Others have been successfully added.';
             break;
         case (0):
               $text_class= 'alert-error';
               $text = 'Error: Database error Duplicate entry in Wishlist.';
               break;
          case (3):
               $text_class= 'alert-error';
               $text = 'Error: Invalid Company Details.';
               break;
       
      }
      echo '<div class="alert '.$text_class.'">
         <button class="close" data-dismiss="alert"></button>
         '.$text.'
         </div>';
   }
   ?>
	

 
 <div class="row-fluid ">
               <div class="span6">
                  <div class="portlet box blue">
                          <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Add Companies to Portfolio</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form id="company_form" action="<?php echo $folder?>process.php?cat=1" class="horizontal-form" method="post" >
                                   
                                   <div class="row-fluid">
                                       <div class="span12 ">
                                         <div class="control-group">
                                           <label class="control-label">Choose Company</label>
                                           <div class="controls">
                                              <input type="text" placeholder="Select Company.."  name="com_string" id="com_string" autocomplete="off" class="typehead" style="width:90%" />   
                                              <span class="help-block"></span>
                                           </div>
                                          </div>
                                       </div>
                                     </div>
                                        <div class="row-fluid">
                                       <div class="span12 ">
                                         <label class="control-label">
                                        <button type="button" onclick="company_submit()" class="btn blue span3" style="margin-top:-8px;">Add</button>
                                       </label>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div style="height:37px;">&nbsp;</div>
                                 </form>
                              </div>
                           </div>
               </div>

               <div class="span6">
                  <div class="portlet box blue">
                          <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Upload Companies List to Portfolio</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form id="attachment_form" action="<?php echo $folder?>process.php?cat=2" class="horizontal-form" method="post" enctype="multipart/form-data" target="_blank" >
                                   
                                   <div class="row-fluid">
                                       <div class="span9 ">
                                         <div class="control-group">
                                           <label class="control-label">Upload File (CSV)</label>
                                           <div class="controls">
                                              <input type="file" name="attach_file" id="attach_file">
                                              <span class="help-block" id="fileInfo"></span>
                                           </div>
                                          </div>
                                       </div>
                                       <div class="span3" align="right">
                                         <a type="button" href="<?php echo STRSITE ?>BSEorISIN.csv" class="btn icn-only ttip" rel="tooltip" title="Download Format" target="_blank">
                                                <i class="icon-download-alt"></i>
                                              </a>
                                       </div>
                                       <!--/span-->
                                    </div>
                                     <div class="row-fluid">
                                      <div class="span6 ">
                                         <div class="control-group">
                                           <label class="control-label">Data Type</label>
                                           <div class="controls">
                                              <select name="data_type">
                                                <option value="1">BSE Code</option>
                                                <option value="2">ISIN</option>
                                              </select>
                                              <span class="help-block"></span>
                                           </div>
                                          </div>
                                       </div>
                                       <div class="span6 ">
                                         <div class="control-group">
                                           <label class="control-label">&nbsp;</label>
                                           <div class="controls">
                                              <button type="button" onclick="attachment_submit()" class="btn blue span6" style="">Upload</button>
                                             
                                           </div>
                                          </div>
                                    </div>
                                    <!--/row-->
                                 </form>
                                 <!-- END FORM--> 
                               </div>
                           </div>
               </div>
            </div>


                       <div class="portlet box light-grey">
                          <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i> Companies in My Portfolio</h4>
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
                                 <th>BSE Code</th>
                                 <th>Action</th>
                                
                              </tr>
                              
                           </thead>
                           <tfoot>
                              <tr>
                                <th>#</th>
                                 <th>Company Name</th>
                                 <th>BSE Code</th>
                                 <th></th>
                                 
                                
                              </tr>
                              <tr>
                                <th colspan="9" class="ts-pager form-horizontal">
                                  <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
                                  <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
                                  <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                                  <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
                                  <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
                                  <select class="pagesize input-mini" title="Select page size">
                                    
                                    <option selected="selected" value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                  </select>
                                  <select class="pagenum input-mini" title="Select page number"></select>
                                </th>
                              </tr>
                            </tfoot>
                           <tbody>
                              <?php


                                $sql = mysql_query("SELECT companies.com_name, companies.com_id, companies.com_bse_code,user_voting_company.type from user_voting_company inner join companies on user_voting_company.com_id = companies.com_id where user_voting_company.user_id='$user_id' order by companies.com_name asc");
                                $count =1;
                                while ($row = mysql_fetch_array($sql)) {
                                 echo '<tr id="tr_'.$row["com_id"].'">';
                                 echo '<td>'.$count.'</td>';
                                    echo '<td>'.$row["com_name"].'</td>';
                                    echo '<td>'.$row["com_bse_code"].'</td>';
                                    echo '<td>';
                                    if($row["type"] == 0){
                                      echo '<a href="javascript:;" onclick="delete_voting_company('.$row["com_id"].')" class="btn red">Delete</a>';
                                    }
                                    echo '</td>';
                                    echo '</tr>';
                                    $count++;
                                }
                                                                
                                    
                                  
                              ?>
                             
                             
                           </tbody>
                        </table>
            </div>
          </div>

</div><!-- END CONTAINER -->
<script type="text/javascript">


function update_submit(){
   if(validate_required_gen_idinfo($("#name").val(), 'name','Please input valid name') && validate_required_gen_idinfo($("#email").val(), 'email','Please input valid email') && validate_required_number_idinfo($("#sub_users").val(), 'sub_users','Please input valid number')) {
//      alert('yes');
      $('#update_form').submit();
   } 
   else
      return false;     
}
/*
function company_submit(){
   if($("#com_id_select").val() != null){
      $('#company_form').submit();
   } 
   else{
    alert('Plase select some companies.');
    return false; 
   }
          
}
*/
function company_submit(){
   if($("#com_string").val() != null){
      $('#company_form').submit();
   } 
   else{
    alert('Plase select some companies.');
    return false; 
   }
          
}

function validateFile(){
   var ext = $("#attach_file").val().split('.').pop().toLowerCase();
   if($.inArray(ext, ['csv']) == -1) {
      $('#fileInfo').text("Please Choose a valid csv file");
      return false;
   } else {
      $('#fileInfo').text("");
      return true;
   }
}
function attachment_submit(){
   if( validateFile())
      $('#attachment_form').submit();
   else
      return false;     
}
function delete_voting_company(company_id){
   
     var file = "delete_voting_company";
        bootbox.confirm("Are you sure?", function(result) {
          if(result) {
            $.post("ajax/"+ file +".php", {id:company_id}, function(data) {
                  if(data == 'success'){
                      $("#tr_"+company_id).hide("slow", function(){$("#tr_"+company_id).remove});
                    } else {
                    alert('Deletion error');
                  }   
             }); 
          }
          else {
          
          }
        });       
}

</script>