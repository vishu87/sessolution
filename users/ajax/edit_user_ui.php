<?php session_start();
require_once('../../auth.php');

$user = $_SESSION["MEM_ID"];

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$check = mysql_query("SELECT id from users where created_by_prim='$_SESSION[MEM_ID]' and id = '$_POST[id]' ");
if(mysql_num_rows($check) == 0) die('You are not authorized for this.' );

$query = mysql_query("SELECT name, voting_access, mobile from users where id='$_POST[id]' ");
$row = mysql_fetch_array($query);

?>
<form action="#" class="form-horizontal">
   <div class="control-group">
      <label class="control-label">Voting Access</label>
      <div class="controls">
          <select class="small m-wrap" name="voting_access" id="voting_access_up">
           <option value="0" <?php if($row["voting_access"] == 0) echo 'selected'; ?>>All</option>
           <option value="1" <?php if($row["voting_access"] == 1) echo 'selected'; ?>>Restricted</option>
          </select>
         <span class="help-inline"></span>
      </div>
   </div>
    <div class="control-group">
      <label class="control-label">Name</label>
      <div class="controls">
         <input type="text" placeholder="Name" id="name_up" class="m-wrap medium" value="<?php echo $row["name"]; ?>">
         <span class="help-inline"></span>
      </div>
   </div>
      <div class="control-group">
      <label class="control-label">Contact</label>
      <div class="controls">
         <input type="text" placeholder="Mobile No." id="mobile_up" class="m-wrap medium" value="<?php echo $row["mobile"]; ?>">
         <span class="help-inline"></span>
      </div>
   </div>
  
      <button type="button" class="btn blue" onclick="save_changes(<?php echo $_POST["id"];?>);"><i class="icon-ok"></i> Save</button>
      
</form>
