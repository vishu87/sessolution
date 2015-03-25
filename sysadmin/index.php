<?php 
  session_start();
  require_once('top.php');
  //something about index
  $sidebar ='dashboard';

  $title= "SES Admin";

  require_once('header.php');?>
  <!-- BEGIN CONTAINER -->
  <div class="page-container row-fluid">
    <?php require_once('sidebar.php');?>
    <!-- BEGIN PAGE -->
    <div class="page-content">
    <?php include('dashboard/front.php'); ?>
    <!-- END PAGE -->   
  </div>
  <!-- END CONTAINER -->
<?php
  require_once('footer.php');
  
?>