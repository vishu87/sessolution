<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
		Contact Us
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
					$text = 'User is successfully added';
					break;
			case (2):
					$text_class= 'alert-error';
					$text = 'Error: Duplicate username';
					break;
		}
		echo '<div class="alert '.$text_class.'">
			<button class="close" data-dismiss="alert"></button>
			'.$text.'
			</div>';
	}
  $sql_an = mysql_query("SELECT * from analysts order by name asc");
	?>
<div class="row-fluid">
  <div class="span8">
     <div class="portlet box light-grey">
                     <div class="portlet-title">
                        <h4><i class="icon-globe"></i>Schedule Meeting with Our Analyst: </h4>
                     </div>
                     <div class="portlet-body">
                       <table class="table table-stripped">
                           <thead>
                              <tr>
                                 <th>Analyst Name</th>
                                 <th>Contact</th>
                                 <th></th>
                              </tr>
                              
                           </thead>
                  
                           <tbody>
                            <tr>
                              <td>Admin</td>
                               <td>admin@sesgovernance.com</td>
                               <td><a href="#myModal" class="btn" data-toggle="modal" onclick="ana_message(0,'Admin')" > Send Message</a>&nbsp;<a href="#myModal" class="btn" data-toggle="modal" onclick="ana_meeting(0,'Admin')" > Request Meeting</a></td>
                            </tr>
                           <?php
                           $count=1;
                           while ($row_an = mysql_fetch_array($sql_an)) {
                             ?><tr id="tr_<?php echo $count; ?>">
                               <td><?php echo $row_an["name"]?></td>
                               <td><?php echo $row_an["email"]?></td>
                               <td><a href="#myModal" class="btn" data-toggle="modal" onclick="ana_message(<?php echo $row_an["an_id"]?>,'<?php echo $row_an["name"]?>')" > Send Message</a>&nbsp;<a href="#myModal" class="btn" data-toggle="modal" onclick="ana_meeting(<?php echo $row_an["an_id"]?>,'<?php echo $row_an["name"]?>')" > Request Meeting</a></td>
                             </tr>
                             <?php
                              $count++;
                           }

                           ?>                            

                           </tbody>
                        </table>
                     </div>
              </div>
  </div>
  <div class="span4" style="">
                      <h3>Address</h3>
                      <div class="well">
                        <address>
                          <strong>Stakeholders Empowerment Services (SES)</strong><br>
                          A202 Muktangan Complex,<br>
                          Upper Govind Nagar,<br>
                          Malad East,<br>
                          Mumbai – 400097.<br>
                        </address>
                        <address>
                          <strong>Contact</strong><br>
                          +91 22 4022 0322
                        </address>
                         <address>
                          <strong>Email</strong><br>
                          <a href="mailto:info@sesgovernance.com">info@sesgovernance.com</a>
                        </address>
                      </div>
                    </div>

                    
</div>
            
   
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

function ana_message(analyst_id, analyst_name){
   $("#myModalLabel").text('Send a message to '+analyst_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'ana_message';
   $.post("ajax/"+ file +".php", {id:analyst_id}, function(data) {
      $("#modal-body").html(data);
      initialize();
   }); 

}

function ana_meeting(analyst_id, analyst_name){
   $("#myModalLabel").text('Request a meeting with '+analyst_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'ana_meeting';
   $.post("ajax/"+ file +".php", {id:analyst_id}, function(data) {
      $("#modal-body").html(data);
      initialize();
   }); 

}

function send_message(analyst_id, type) {
  if( validate_required_gen_idinfo($("#message").val(), 'message','Please write message')  ){
         var file = 'send_message';
         $('.btn_send').html('Processing..');
         $.post("ajax/"+ file +".php", { id:analyst_id, message:$("#message").val(), type:type }, function(data) {
            if(data == 'success'){
               $("#close_button").trigger('click');
               bootbox.alert('Your message is successfully sent.');
            } else {
              alert('Database error');
            }
         }); 
      } else {
         return false;
      }
}

function send_meeting(analyst_id) {
  if( validate_required_gen_idinfo($("#ana_date").val(), 'ana_date','Please input date') && validate_required_gen_idinfo($("#message").val(), 'message','Please write message')  ){
         var file = 'send_message';
         $('.btn_send').html('Processing..');
         $.post("ajax/"+ file +".php", { id:analyst_id, ana_date:$("#ana_date").val(), ana_time:$("#ana_time").val(), message:$("#message").val() }, function(data) {
            if(data == 'success'){
               $("#close_button").trigger('click');
               bootbox.alert('Your message is successfully sent.');
            } else {
              alert('Database error');
            }
         }); 
      } else {
         return false;
      }
}
</script>