<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Add Company		
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
			case (3):
					$text_class= 'alert-success';
					$text = 'Company is successfully added';
					break;
			case (4):
					$text_class= 'alert-error';
					$text = 'Error: Duplicate company BSE code';
					break;
         case (5):
               $text_class= 'alert-error';
               $text = 'Error: Duplicate company NSE Symbol';
               break;

         case (6):
               $text_class= 'alert-error';
               $text = 'Error: Duplicate company ISIN number';
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
                                 <h4><i class="icon-reorder"></i>Add Company</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <form action="<?php echo $folder?>process.php?cat=2" method="post" class="horizontal-form" id="submit_form">
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="name">Company Capitaline Name</label>
                                             <div class="controls">
                                                <input id="name" name="name" class="m-wrap span12" placeholder="eg. ABB Limited" type="text">
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName">BSE Code</label>
                                             <div class="controls">
                                              <input id="bse_code" name="bse_code" class="m-wrap span12" placeholder="eg. 786543" type="text">
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
                                             <label class="control-label">BSE Scrip</label>
                                             <div class="controls">
												            <input id="bse_srcip" name="bse_srcip" class="m-wrap span12" placeholder="eg. ABACUS" type="text">
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label">NSE Symbol</label>
                                             <div class="controls">
                                                <input id="nse_sym" name="nse_sym" class="m-wrap span12" placeholder="eg. ACC" type="text" >
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->   
                                     <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label">Reuters</label>
                                             <div class="controls">
                                                <input id="reuters" name="reuters" class="m-wrap span12" placeholder="eg. ACC.BO" type="text">
                                                <span class="help-block" id="usernameInfo"></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label">Bloomberg</label>
                                             <div class="controls">
                                                <input id="bloomberg" name="bloomberg" class="m-wrap span12" placeholder="eg. ACC IN" type="text" >
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->   

                                     <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label">ISIN</label>
                                             <div class="controls">
                                                <input id="isin" name="isin" class="m-wrap span12" placeholder="eg. INE192D01011" type="text">
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label">Address</label>
                                             <div class="controls">
                                                <input id="address" name="address" class="m-wrap span12" placeholder="eg. Andheri, Mumbai" type="text">
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
                                             <label class="control-label">Telephone</label>
                                             <div class="controls">
                                                <input id="telephone" name="telephone" class="m-wrap span12" placeholder="eg. 9634857986" type="text">
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label">Website</label>
                                             <div class="controls">
                                                <input id="website" name="website" class="m-wrap span12" placeholder="eg. www.abc.com" type="text">
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                          
                                       </div>
                                       <!--/span-->
                                    </div>

                                      <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label">Secretary email ID</label>
                                             <div class="controls">
                                                <input id="sec_email" name="sec_email" class="m-wrap span12" placeholder="eg. abc@abc.com" type="text">
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label">Full Name</label>
                                             <div class="controls">
                                                <input id="full_name" name="full_name" class="m-wrap span12" placeholder="eg. XYZ Limited" type="text">
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                          
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row--> 

                                 
                                    <div class="form-actions">
                                       <button type="button" onclick="check_submit()" class="btn blue"><i class="icon-ok"></i> Create</button>
                                      <button type="button" class="btn" onclick="location.reload()">Cancel</button>
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
					</div>
				</div>

				
				
</div>
<?php
 $name_validate = array("name","bse_code","bse_srcip","nse_sym","reuters","bloomberg","isin");
?>
<script type="text/javascript">
function check_submit(){

      if(validate_required_gen_idinfo($("#name").val(), 'name','Please input valid name')) {
         $("#submit_form").submit();
      } else {
         return false;
      }
}
</script>