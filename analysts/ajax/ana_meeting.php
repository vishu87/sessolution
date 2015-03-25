<?php session_start();
require_once('../../subuserauth.php');

$user = $_SESSION["MEM_ID"];

if(!isset($_POST["id"]) ) header("Location: ".STRSITE."access-denied.php");

?>
<form action="#" class="form-horizontal">
   <div class="control-group">
      <label class="control-label">Date</label>
      <div class="controls">
         <input type="text" placeholder="Select Date" id="ana_date"  class="m-wrap medium datepicker_month" >
         <span class="help-inline"></span>
      </div>
   </div>
   <div class="control-group">
      <label class="control-label">Time</label>
      <div class="controls">
         <input type="text" id="ana_time" placeholder=" e.g. 2:30 PM" data-format="hh:mm A" class="input-small">
      </div>
   </div>
   <div class="control-group">
      <label class="control-label">Your Message</label>
      <div class="controls">
         <textarea id="message" class="textareahtml" style="width:75%; height:100px;"></textarea>
         <span class="help-inline"></span>
      </div>
   </div>

  <div style="text-align:right">
      <button type="button" class="btn blue btn_send" onclick="send_meeting(<?php echo $_POST["id"];?>,2);"><i class="icon-ok"></i> Send</button>
   </div>
      
</form>
