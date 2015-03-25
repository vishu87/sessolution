<?php session_start();
require_once('../../auth.php');

require_once('../../classes/UserClass.php');


if(!isset($_POST["report_id"])) header("Location: ".STRSITE."access-denied.php");

$report_id = mysql_real_escape_string($_POST["report_id"]);

$user_id = mysql_real_escape_string($_POST["an_id"]);
$user = new User($user_id);

$pa_report = new PA($report_id);

$users_auth = $pa_report->fetch_all_users();
$flag_auth = (in_array($_SESSION["MEM_ID"], $users_auth))?true:false;

if($user->parent != $_SESSION["MEM_ID"]) die("You are not authorized");

$recos = array();
$sql_reco = mysql_query("SELECT * from ses_recos");
while ($row_reco = mysql_fetch_array($sql_reco)) {
  $recos[$row_reco["id"]] = $row_reco["reco"];
}

$str = '<tr><th>SN</th><th>Resolution Name</th><th>Management / Shareholder Recommendation</th><th>Proposal by Management or Shareholder</th><th>SES Recommendation</th>';
if($flag_auth) $str .= '<th>Copy</th>';
$str .= '<th>Your Vote</th><th>Comment</th></tr>';
	$man_recos = array("","FOR","AGAINST","ABSTAIN");
		

		$sql_vote = mysql_query("SELECT * from voting where report_id='$report_id' order by resolution_number asc");
		     $count =1;
		     while($row_vote = mysql_fetch_array($sql_vote)) {
		      $str .= '<tr id="tr_vote_'.$row_vote["id"].'">';
		       $str .= '<td><input type="hidden" name="vote_id[]" value="'.$row_vote["id"].'" >'.stripcslashes($row_vote["resolution_number"]).'</td>';
		         $str .= '<td>'.stripcslashes($row_vote["resolution_name"]).'</td>';
		         
		         $str .= '<td>'.$man_recos[$row_vote["man_reco"]].'</td>';
		         $str .= '<td>'.$man_share_recos[$row_vote["man_share_reco"]].'</td><td>';
		            if($flag_auth)
		         	$str .= '<a href="javascript:;" id="ses_reco_'.$count.'" class="btn ttip span12" rel="tooltip" title="'.$row_vote["detail"].'">'.$recos[$row_vote["ses_reco"]].'</a></td><td><a href="javascript:;" class="btn icn-only yellow ttip span12" rel="tooltip" onclick="copy_comments('.$count.')" title="Click to copy SES comment"><i class="icon-chevron-right"></i><span style="display:none" id="ses_comment_'.$count.'">'.$row_vote['detail'].'</span></a>';
		        	 else
		         	$str .= $recos[$row_vote["ses_reco"]];
		         $str .= '</td><td>';

		         $sql ="SELECT vote, comment from user_voting where vote_id = '$row_vote[id]' and proxy_id = '$report_id' and user_id='$user_id' ";
		         $sql_vote_pre = mysql_query($sql);
		         $count_n = mysql_num_rows($sql_vote_pre);
		         if($count_n > 0) $pre = mysql_fetch_array($sql_vote_pre);

		         
			         $str .= '<select name="vote[]" class="small m-wrap" id="vote_'.$count.'"><option value="0">Select</option> ';
			         $sql_reso = mysql_query("Select * from votes");
			         while ($row_reso = mysql_fetch_array($sql_reso)) {
			            $str .= '<option value="'.$row_reso["id"].'" ';
			            if($count_n > 0){
			                if($row_reso["id"] == $pre["vote"]) $str .= 'selected';
			            }
			            $str .= '>'.$row_reso["vote"].'</option>';
			         }
			         $str .= '</select>';
		     	
		         $str .= '</td><td>';

		         
		         	 $str .= '<textarea name="comment[]" id="comment_'.$count.'">';
		          	if($count_n > 0){
		                $str .= stripcslashes($pre["comment"]);
		            }
		        	 $str .= '</textarea>';
		         
		
		         $str .= '</td></tr>';
		         $count++;
		     }
		     echo $str;
?>
