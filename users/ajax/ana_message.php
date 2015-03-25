<?php session_start();
require_once('../../auth.php');

$user = $_SESSION["MEM_ID"];

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

?>
<form action="#" class="form-horizontal">
   <div class="control-group">
      <label class="control-label">Your Message</label>
      <div class="controls">
         <textarea id="message" class="textareahtml" style="width:75%; height:100px;"></textarea>
         <span class="help-inline"></span>
      </div>
   </div>

  <div style="text-align:right">
      <button type="button" class="btn blue btn_send" onclick="send_message(<?php echo $_POST["id"];?>,1);"><i class="icon-ok"></i> Send</button>
   </div>
      
</form>
