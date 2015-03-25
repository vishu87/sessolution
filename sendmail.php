<?php
  $to = "vishu.iitd@gmail.com";
  $subject = "Test mail";
  $message = "Hello! This is a simple email message to check settings.";
  $from = "cds@iimb.ernet.in";
  $headers = "From:" . $from;
  mail($to,$subject,$message,$headers);
  echo "Mail Sent.";
?>