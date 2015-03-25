<?php session_start();
require_once('../../sysauth.php');
require_once('../../config.php');
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}


if(!isset($_POST["report_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$report_id = $_POST["report_id"];
$company_id = $_POST["company_id"];
$year = $_POST["year"];
$type = $_POST["type"];

echo '<form id="voting_form" action="" class="form-horizontal" method="post" enctype="multipart/form-data" >
                                   
       <div class="row-fluid">
           
            <div class="span12 ">
             <div class="control-group">
           <label class="control-label">User Name</label>
           <div class="controls">
              <select name="user_id" id="user_id_sub">';
              $sql_reso = mysql_query("Select id,name from users where created_by_prim = 0 and active=0 ");
              while ($row_reso = mysql_fetch_array($sql_reso)) {
                echo '<option value="'.$row_reso["id"].'" ';

                echo '>'.$row_reso["name"].'</option>';
              }

              echo '</select>
              
           </div>
           </div>
           </div>
           <!--/span-->
           <!--/span-->
        </div>
        <!--/row-->
        
           	 <button type="button" onclick="sub_add_submit('.$_POST["count"].','.$report_id.','.$company_id.','.$year.','.$type.')" class="btn blue" id="vote_s"><i class="icon-ok"></i> Add</button>
     </form>';





?>