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
        <a href="../excel/list_companies_portfolio.php" class="btn pull-right">Export List</a>
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
	

  <?php if($_SESSION["self_portfolio"] == 0) { ?>
    <div class="row-fluid ">
               <div class="span6">
                  <div class="portlet box blue">
                          <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Add Companies (Self)</h4>
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
                                 <h4><i class="icon-reorder"></i>Upload Companies List (Self)</h4>
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
            <?php } ?>

            <div class="row-fluid ">
               <div class="span6">
                  <div class="portlet box blue">
                          <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Upload List (PM)</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form id="pmattachment_form" action="<?php echo $folder?>process.php?cat=4" class="horizontal-form" method="post" enctype="multipart/form-data" target="_blank" >
                                   
                                   <div class="row-fluid">
                                       <div class="span9 ">
                                         <div class="control-group">
                                           <label class="control-label">Upload File (CSV)</label>
                                           <div class="controls">
                                              <input type="file" name="attach_file" id="pmattach_file">
                                              <span class="help-block" id="pmfileInfo"></span>
                                           </div>
                                          </div>
                                       </div>
                                       <div class="span3" align="right">
                                         <a type="button" href="<?php echo STRSITE ?>PMUpload.csv" class="btn icn-only ttip" rel="tooltip" title="Download Format" target="_blank">
                                                <i class="icon-download-alt"></i>
                                              </a>
                                       </div>
                                       <!--/span-->
                                    </div>
                                     <div class="row-fluid">
                                      <div class="span6 ">
                                         <div class="control-group">
                                           <label class="control-label">Action</label>
                                           <div class="controls">
                                              <select name="data_type">
                                                <option value="1">Add</option>
                                                <option value="2">Delete</option>
                                              </select>
                                              <span class="help-block"></span>
                                           </div>
                                          </div>
                                       </div>
                                       <div class="span6 ">
                                         <div class="control-group">
                                           <label class="control-label">&nbsp;</label>
                                           <div class="controls">
                                              <button type="button" onclick="pmattachment_submit()" class="btn blue span6" style="">Upload</button>
                                             
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
            <?php
              if(!isset($_POST["pm_id"])) $pm_id = $user_id;
              else $pm_id = mysql_real_escape_string($_POST["pm_id"]);
            ?>

           <div class="portlet box light-grey">
                          <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i> Companies in Portfolio</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body">
                                <form class="form-horizontal" action="" method="POST">
                                  <select class="control" name="pm_id">
                                    <option value="<?php echo $user_id; ?>" <?php echo ($user_id == $pm_id)?'selected':''; ?> >Self</option>
                                    <?php
                                      $query_pm = mysql_query("SELECT id, name from users where created_by_prim = $user_id order by name asc");
                                      while ($row_pm = mysql_fetch_array($query_pm)) {
                                        echo '<option value="'.$row_pm["id"].'" ';
                                        echo ($row_pm["id"] == $pm_id)?'selected':'';
                                        echo '>'.$row_pm["name"].'</option>';
                                      }

                                    ?>
                                  </select>
                                  <button type="submit" class="btn">Go</button>
                                </form>
                <table class="table table-stripped tablesorter">
                           <thead>
                              <tr>
                                 <th>SN</th>
                                 <th>Company Name</th>
                                 <th>BSE Code</th>
                                 <th>PM Assigned</th>
                                <th>Action</th>
                              </tr>
                              
                           </thead>
                           <tfoot>
                              <tr>
                                <th>#</th>
                                 <th>Company Name</th>
                                 <th>BSE Code</th>
                                 <th>PM Assigned</th>
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
                              

                                $sql = mysql_query("SELECT companies.com_name, companies.com_id, companies.com_bse_code from user_voting_company inner join companies on user_voting_company.com_id = companies.com_id where user_voting_company.user_id='$pm_id' order by companies.com_name asc");
                                $count =1;
                                while ($row = mysql_fetch_array($sql)) {
                                 echo '<tr id="tr_'.$row["com_id"].'">';
                                 echo '<td>'.$count.'</td>';
                                    echo '<td>'.$row["com_name"].'</td>';
                                    echo '<td>'.$row["com_bse_code"].'</td>';
                                    ?>
                                <td>
                                  <a href="#stack1" data-toggle="modal" role="button" onclick="view_company_users(<?php echo $row["com_id"] ?>)" class="btn span12" style="max-width:100px;">View</a>
                                </td>
                                    <?php
                                    echo '<td><a href="javascript:;" onclick="delete_voting_company('.$row["com_id"].','.$pm_id.')" class="btn red">Delete</a></td>';
                                    echo '</tr>';
                                    $count++;
                                }
                                                                
                                    
                                  
                              ?>
                             
                             
                           </tbody>
                        </table>
            </div>
          </div>

</div><!-- END CONTAINER -->

<div id="stack1" class="modal hide fade" tabindex="-1" data-focus-on="input:first" style="width:94%; margin-left:-47%">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Stack One</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
    <p>One fine body…</p>
    <p>One fine body…</p>
    <input type="text" data-tabindex="1">
    <input type="text" data-tabindex="2">
    <button class="btn" data-toggle="modal" href="#stack2">Launch modal</button>
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn" id="close_button1">Close</button>
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

function validateFile(filename,spanname){
   var ext = $("#"+filename).val().split('.').pop().toLowerCase();
   if($.inArray(ext, ['csv']) == -1) {
      $('#'+spanname).text("Please Choose a valid csv file");
      return false;
   } else {
      $('#'+spanname).text("");
      return true;
   }
}
function attachment_submit(){
   if( validateFile('attach_file','fileInfo'))
      $('#attachment_form').submit();
   else
      return false;     
}
function pmattachment_submit(){
   if( validateFile('pmattach_file','pmfileInfo'))
      $('#pmattachment_form').submit();
   else
      return false;     
}
function delete_voting_company(company_id, user_id){
   
     var file = "delete_voting_company";
        bootbox.confirm("Are you sure?", function(result) {
          if(result) {
            $.post("ajax/"+ file +".php", {id:company_id, user_id:user_id}, function(data) {
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