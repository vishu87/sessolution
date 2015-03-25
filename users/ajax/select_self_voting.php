<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');

if(!isset($_POST["report_id"])) header("Location: ".STRSITE."access-denied.php");

$report_id = mysql_real_escape_string($_POST["report_id"]);

$user_id = mysql_real_escape_string($_POST["an_id"]);
$parent_id = mysql_real_escape_string($_POST["parent_id"]);


$str .= '<tr><th>Resolution Number</th><th>Resolution Name</th><th>Management / Shareholder Recommendation</th><th>Your Vote</th><th>Comment</th><th></th></tr>';
	$sql_vote = mysql_query("SELECT * from user_resolution where report_id='$report_id' and user_id='$parent_id' order by resolution_number asc");
     $count =1;
     while($row_vote = mysql_fetch_array($sql_vote)) {
      $str .= '<tr id="tr_vote_'.$row_vote["id"].'">';
       $str .= '<td><input type="hidden" name="vote_id[]" value="'.$row_vote["resolution_id"].'" >'.stripcslashes($row_vote["resolution_number"]).'</td>';
         $str .= '<td>'.stripcslashes($row_vote["resolution_name"]).'</td>';
         $str .= '<td>'.stripcslashes($man_recos[$row_vote["man_reco"]]).'</td>';
         
         $str .= '<td>';
           
         $sql ="SELECT vote, comment from user_voting where user_resolution_id = '$row_vote[resolution_id]' and proxy_id = '$report_id' and user_id='$user_id' ";
         $sql_vote_pre = mysql_query($sql);
         $count_n = mysql_num_rows($sql_vote_pre);
         if($count_n > 0) $pre = mysql_fetch_array($sql_vote_pre);

       
        	 $str .= '<select name="vote[]" class="small m-wrap" ><option value="0">Select</option> ';
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

        
         	$str .= '<textarea name="comment[]">';
          if($count_n > 0){
                $str .= stripcslashes($pre["comment"]);
            }
         $str .= '</textarea>';
    
         
     

         $str .= '</td>
         <td>';
        
        	 $str_del = '<a href="javascript:;" onclick="delete_resolution('.$row_vote["resolution_id"].','.$report_id.')" class="btn red">Delete Resolution</a>';
         if($parent_id == $_SESSION["MEM_ID"]){
         	$str .= $str_del;

         }
         $str .= '</td></tr>'; 
         $count++;

     }
     echo $str;
     mysql_query("INSERT into user_activity (user_id, activity_id, report_id, report_type,details) values ('$_SESSION[MEM_ID]','28','$report_id','1','$user_id')");

     ?>