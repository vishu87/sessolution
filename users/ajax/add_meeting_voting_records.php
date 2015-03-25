<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');
$user = $_SESSION["MEM_ID"];

$users_ar = array();
array_push($users_ar, $user);
?>
<div class="row-fluid">
<h4>Select Portfolios</h4>
<?php

$sql_user = mysql_query("SELECT user_admin_name from users where id = '$_SESSION[MEM_ID]' ");
$row_user = mysql_fetch_array($sql_user);

echo '<button class="btn" id="btn_'.$_SESSION["MEM_ID"].'" onclick="change_check('.$_SESSION["MEM_ID"].')">'.$row_user["user_admin_name"].'</button> <input style="display:none;" type="checkbox" name="ids[]" id="check_'.$_SESSION["MEM_ID"].'" value="'.$_SESSION["MEM_ID"].'"  >';

//fetching users of this user admin
$sql_user = mysql_query("SELECT id,name from users where created_by_prim='$user' ");
while ($row_user = mysql_fetch_array($sql_user)) {
  echo '<button class="btn" id="btn_'.$row_user["id"].'" onclick="change_check('.$row_user["id"].')">'.$row_user["name"].'</button> <input style="display:none;" type="checkbox" name="ids[]" id="check_'.$row_user["id"].'" value="'.$row_user["id"].'"  >';
}

?>
</div><hr>
<h4>Search Meetings</h4>
<form action="#" class="horizontal-form">
    <div class="row-fluid">
       <div class="span3">
          <div class="control-group">
             <label class="control-label" for="firstName">Search Company</label>
             <div class="controls">
                <input type="text" placeholder="Select Company.." name="com_string" id="com_string" autocomplete="off" class="typehead" required="">
             </div>
          </div>
       </div>
       <!--/span-->
       <div class="span3">
          <div class="control-group">
             <label class="control-label" for="lastName">Date From</label>
             <div class="controls">
                <input type="text" name="date_from" id="date_from" class="datepicker_month">
             </div>
          </div>
       </div>
       <!--/span-->
       <div class="span3">
          <div class="control-group">
             <label class="control-label" for="lastName">Date To</label>
             <div class="controls">
                <input type="text" name="date_to" id="date_to" class="datepicker_month">
             </div>
          </div>
       </div>
       <!--/span-->
       <div class="span3">
          <div class="control-group">
             <label class="control-label" for="lastName">&nbsp;</label>
             <div class="controls">
                <button type="button" class="btn" style="margin-top:-2px;" onclick="search_meeting()">Search</button>
             </div>
          </div>
       </div>
    </div>
    <!--/row-->
 </form>

 <div id="results">

 </div>
