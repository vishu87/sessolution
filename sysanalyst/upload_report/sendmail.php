  function send_email($proxy_id){

  $rep_sql = mysql_query("SELECT companies.com_name, proxy_ad.*, met_type.type from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id join met_type on proxy_ad.meeting_type = met_type.id where proxy_ad.id='$proxy_id' ");
  $row_rep = mysql_fetch_array($rep_sql);
  $com_name = $row_rep["com_name"];
  $com_id = $row_rep["com_id"];
  
  $mail = new PHPMailer();
  $mail->IsSMTP();
  $mail->SMTPAuth   = true; 
  $mail->SMTPSecure = "tls"; 
  $mail->Host       = "email-smtp.us-east-1.amazonaws.com";
  $mail->Username   = "AKIAID7CBUQKCREFMSBQ";
  $mail->Password   = "AvphiYmJWkhaQDvZsGEn6Jla1AFBmdVOqi4WnDf6wKdH";
  $mail->SetFrom('info@sesgovernance.com', 'SES Governance'); //from (verified email address)
  $mail->Subject = "Meeting Details Update Alert"; 
  $mail->IsHTML(true);
  $mail->addAddress('noreply@sesgovernance.com');
 
       $sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '$com_id' and package.package_year='$row_rep[year]' and package.package_type = '1' ");

       $users = array();
       while ($row_pack = mysql_fetch_array($sql_pack_user)) {
          if(!in_array($row_pack["user_id"], $users)){
            array_push($users, $row_pack["user_id"]);
        }
       }

        $sql_addi_user = mysql_query("SELECT distinct user_id from users_companies where com_id = '$com_id' and year = '$row_rep[year]' and type='1' ");
        while ($row_pack = mysql_fetch_array($sql_addi_user)) {
          if(!in_array($row_pack["user_id"], $users)){
            array_push($users, $row_pack["user_id"]);
        }
       }

       $users_list = implode(',', $users);
      
      $quer_sub = mysql_query("SELECT id from users where created_by_prim IN (".$users_list.") ");
       while ($row_sub = mysql_fetch_array($quer_sub)) {
        if(!in_array($row_sub["id"], $users)){
          array_push($users, $row_sub["id"]);
        }
       }

       $users_list = implode(',', $users);

       if(sizeof($users) > 0){
         $sql_send = "SELECT users.email, users.id, users.created_by_prim from users inner join user_voting_company on users.id = user_voting_company.user_id where users.id IN (".$users_list.") and user_voting_company.report_upload = 1 and user_voting_company.com_id='$com_id' ";
          //echo $sql_send;
          $query_send = mysql_query($sql_send);
          if(mysql_num_rows($query_send) > 0) {
            $count_user = 0;
            while ($row_send = mysql_fetch_array($query_send)) {
              $user_check = new User($row_send["id"]);
              if($user_check->customized == 0) {
                //echo $row_send["email"];
                $mail->addBCC($row_send["email"]);
                $count_user++;
              }
              
            }
            if($count_user > 0){
              $body_in = '<p> Report has been uploaded for <b>'.$com_name.'</b> / <b>'.$row_rep[type"].'</b> / <b>'.date("d-M-y",$row_rep["meeting_date"]).'</b>. Please check the attached file.</p><hr>
            <i>This is an auto generated email. Please do not reply.</i>';
            //echo $body_in;
              $mail->Subject = "Meeting Report Update Alert";
              //echo $row_rep["report"].'asd';
              $mail->addAttachment('../../proxy_reports/'.$row_rep["report"]); 
            $mail->IsHTML(true);
            $mail->MsgHTML($body_in);
            $mail->Send();
            $mail->ClearBCCs();
            }
            

          }
       }

  

}


******************************************************************


$users = array();
    array_push($users, $row["id"]);

    $sql_pack_user = mysql_query("SELECT id from users where created_by_prim='$row[id]' ");
    while ($row_pack_users = mysql_fetch_array($sql_pack_user)) {
      array_push($users, $row_pack_users["id"]);
    }

      $users_list = implode(',', $users);

       if(sizeof($users) > 0){
        $sql_send = "SELECT users.email from users inner join user_voting_company on users.id= user_voting_company.user_id where users.id IN (".$users_list.") and user_voting_company.report_upload = 1 and user_voting_company.com_id='$com_id' ";
        //echo $sql_send;
        $query_send = mysql_query($sql_send);
        if(mysql_num_rows($query_send) > 0) {
          
          $body_in = '<p> Report has been uploaded for <b>'.$com_name.'</b> / <b>'.$row_yr["type"].'</b> / <b>'.date("d-M-y",$row_yr["meeting_date"]).'</b>. Please check the attached file.</p><hr>
        <i>This is an auto generated email. Please do not reply.</i>';
          $mail->addAttachment('../../custom_reports/'.$name); 
        $mail->IsHTML(true);
        $mail->MsgHTML($body_in);
        while ($row_send = mysql_fetch_array($query_send)) {
          //echo $row_send["email"];
            $mail->addBCC($row_send["email"]);
          }
          //echo $body_in;
        $mail->Send();
        $mail->ClearBCCs();

        }
       }