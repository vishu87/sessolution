<?php session_start();
require_once('../../subuserauth.php');

require_once('../../classes/UserClass.php');
$user = new User($_SESSION["MEM_ID"]);
require_once('../../classes/'.$user->voting_class.'.php');

$voting = new SesVoting();
$argv = array();
foreach ($voting->add_fields as $field) {
    array_push($argv, $_POST[$field]);
}

$voting->addUpdateVote($argv);

?>
