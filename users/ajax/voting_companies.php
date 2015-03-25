<?php session_start();
require_once('../../auth.php');

$user = $_SESSION["MEM_ID"];
$_POST["id"] = mysql_real_escape_string($_POST["id"]);

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$check = mysql_query("SELECT id from users where created_by_prim='$_SESSION[MEM_ID]' and id = '$_POST[id]' ");
if(mysql_num_rows($check) == 0) die('You are not authorized for this.' );

$query = mysql_query("SELECT com_id, com_name, com_bse_code from companies ");

?>
<form action="#" class="form-horizontal">
   <div class="control-group">
      <label class="control-label">Add Companies</label>
      <div class="controls">
          <input type="text" placeholder="Select Company.."  name="com_string" id="com_string" autocomplete="off" class="typehead" required style="width:350px;" />   
                                              
         <span class="help-inline"><button type="button" class="btn blue" onclick="voting_companies_add(<?php echo $_POST["id"];?>);" style="margin-left:-5px;" id="add_button"><i class="icon-ok"></i> Add</button></span>
      </div>
   </div>  
</form>
<?php $query_voting = mysql_query("SELECT companies.com_name, voting_access.com_id, companies.com_bse_code from voting_access inner join companies on voting_access.com_id = companies.com_id where user_id='$_POST[id]' "); ?>
<div class="row-fluid">
  <div class="span12">
    <table class="table table-stripped tablesorter">
      <thead>
        <th>Company Name</th>
        <th>Company BSE Code</th>
        <th>Action</th>
      </thead>
      <tbody id="table_tbody">
        <?php
          while ($row = mysql_fetch_array($query_voting)) {
            echo '<tr id="tr_'.$row["com_id"].'"><td>'.$row["com_name"].'</td>';
            echo '<td>'.$row["com_bse_code"].'</td>';
            echo '<td><a href="javascript:;" class="btn yellow" onclick="voting_companies_delete('.$_POST["id"].','.$row["com_id"].')">Delete</a></td></tr>';
          }
        ?>
      </tbody>
    </table>
  </div>
</div>
