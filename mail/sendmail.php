<?php

function send_email($change_fields,$com_id,$proxy_id){

	$rep_sql = mysql_query("SELECT companies.com_name, proxy_ad.*, met_type.type from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id join met_type on proxy_ad.meeting_type = met_type.id where companies.com_id='$com_id' and proxy_ad.id='$proxy_id' ");
	$row_rep = mysql_fetch_array($rep_sql);
	$com_name = $row_rep["com_name"];

	$query = "SELECT user_id from user_voting_company where com_id = '$com_id' AND ( ";
	$count = 0;
	$body_in = '<p>Updates for <b>'.$com_name.'</b> / <b>'.$row_rep["type"].'</b> / <b>'.date("d-M-y",$row_rep["meeting_date"]).'</b></p>';
	$body_in .= '<table style="" cellpadding="5" cellspacing="3">';
	foreach ($change_fields as $change) {
		if($count != 0) $query.= ' OR ';
		switch ($change) {
			case 'notice':
				$query .= 'notice = 1';
				$body_in .= '<tr><td>Notice</td><td style="background:#EEE;"><a target="_blank" href="'.STRSITE.'preview/notice_preview_user.php?report_id='.encrypt($proxy_id).'">'.STRSITE.'preview/notice_preview_user.php?report_id='.encrypt($proxy_id).'</a></td></tr>'; 
				$count ++;
				break;
			case 'notice_link':
				$query .= 'notice = 1';
				$body_in .= '<tr><td>Notice Link</td><td style="background:#EEE;"><a target="_blank" href="'.$row_rep["notice_link"].'">'.$row_rep["notice_link"].'</a></td></tr>'; 
				$count ++;
				break;
			
			case 'annual_report':
				$query .= 'annual_report = 1';
				$body_in .= '<tr><td>Annual Report</td><td style="background:#EEE;"><a target="_blank" href="'.$row_rep["annual_report"].'">'.$row_rep["annual_report"].'</a></td></tr>'; 
				$count ++;
				break;
			case 'meeting_outcome':
				$query .= 'meeting_outcome = 1';
				$body_in .= '<tr><td>Meeting Outcome</td><td style="background:#EEE;"><a target="_blank" href="'.$row_rep["meeting_outcome"].'">'.$row_rep["meeting_outcome"].'</a></td></tr>'; 
				$count ++;
				break;
			case 'meeting_minutes':
				$query .= 'meeting_minutes = 1';
				$body_in .= '<tr><td>Meeting Minutes</td><td style="background:#EEE;"><a target="_blank" href="'.$row_rep["meeting_minutes"].'">'.$row_rep["meeting_minutes"].'</a></td></tr>'; 
				$count ++;
				break;
		}
		
	}

	$query = $query.' )';

	$body_in .= '</table>';
	
	$subject = "Meeting Details Update Alert";
	$noreply = 'noreply@sesgovernance.com';
	$body = mysql_real_escape_string($body_in);

	if($count > 0){
		$emails = array();
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result)) {
			$query_user = mysql_query("SELECT email, other_email from users where id='$row[user_id]' and active=0 ");
			while($row_user = mysql_fetch_array($query_user)){
				array_push($emails, $row_user["email"]);
				$row_user["other_email"] = preg_replace('/\s+/', '', $row_user["other_email"]);
				if($row_user["other_email"] != '')array_push($emails, $row_user["other_email"]);
			}
		}
		$email_string = implode(',', $emails);
	}

	mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$noreply','','$email_string','','$subject', '$body','','') ");

}

?>