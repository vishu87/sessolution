<?php
class Member {
	public $name;
	public $username;
	public $email;

	public function __construct($id=null) {
		if(!$id){
			if($_SESSION["PRIV"] == 0)
				$sql = "select * from users where id='$_SESSION[MEM_ID]' limit 1";
			if($_SESSION["PRIV"] == 1)
				$sql = "select * from admin where id='$_SESSION[MEM_ID]' limit 1";
			if($_SESSION["PRIV"] == 2)
				$sql = "select * from analysts where an_id='$_SESSION[MEM_ID]' limit 1";
		}

		$query = mysql_query($sql);
		$result = mysql_fetch_array($query);

		$this->name = stripslashes($result["name"]);
		$this->username = $result["username"];
		$this->email = $result["email"];
		
	}

}

function fetch_years($type, $selected =null){
	$str = '<select id="'.$type.'" name="'.$type.'">';
	$sql_yr = mysql_query("SELECT * from years order by year_sh desc");
	while($row_yr = mysql_fetch_array($sql_yr)){
		$str .= '<option value="'.$row_yr["year_sh"].'" ';
		if($row_yr["year_sh"] == $selected) $str .= 'selected';
		$str .= '>'.$row_yr["period"].'</option>';
	}
	$str .= '</select>';
	return $str;
}

$fetch_period = array();
$sql_yr = mysql_query("SELECT * from years order by year_sh asc");
	while($row_yr = mysql_fetch_array($sql_yr)){
		$fetch_period[$row_yr["year_sh"]] = $row_yr["period"];
}

function fetch_customized_users($com_id, $year){

	$users = array();
	$customized_users = array();
	$sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '$com_id' and package.package_year='$year' and package.package_type = '1' and users_package.limited = 0  ");
   
   while ($row_pack = mysql_fetch_array($sql_pack_user)) {
     array_push($users, $row_pack["user_id"]);
   }

    $sql_addi_user = mysql_query("SELECT distinct user_id from users_companies where com_id = '$com_id' and year = '$year' and type='1' ");
    while ($row_pack = mysql_fetch_array($sql_addi_user)) {
     array_push($users, $row_pack["user_id"]);
   }
   
   if(sizeof($users) > 0){
      $users_string = implode(',', $users);
      $sql_custom = mysql_query("SELECT id from users where id IN ($users_string) and customized = '1' ");
      while ($row_custom = mysql_fetch_array($sql_custom)) {
      	array_push($customized_users, $row_custom["id"]);
      }
    }

    return $customized_users; 

}

function fetch_normal_users($com_id, $year){

	$users = array();
	$normal_users = array();
	$sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '$com_id' and package.package_year='$year' and package.package_type = '1' and users_package.limited = 0 ");
   
   while ($row_pack = mysql_fetch_array($sql_pack_user)) {
     array_push($users, $row_pack["user_id"]);
   }

    $sql_addi_user = mysql_query("SELECT distinct user_id from users_companies where com_id = '$com_id' and year = '$year' and type='1' ");
    while ($row_pack = mysql_fetch_array($sql_addi_user)) {
     array_push($users, $row_pack["user_id"]);
   }
   
   if(sizeof($users) > 0){
      $users_string = implode(',', $users);
      $sql_custom = mysql_query("SELECT id from users where id IN ($users_string) and customized = '0' ");
      while ($row_custom = mysql_fetch_array($sql_custom)) {
      	array_push($normal_users, $row_custom["id"]);
      }
    }

    return $normal_users; 

}

function fetch_limited_users($com_id, $year){

	$limited_users = array();
	$sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '$com_id' and package.package_year='$year' and package.package_type = '1' and users_package.limited = 1 ");
   
   while ($row_pack = mysql_fetch_array($sql_pack_user)) {
     array_push($limited_users, $row_pack["user_id"]);
   }

    return $limited_users; 

}


function fetch_cgs_users($com_id, $year){

	$users = array();
	$sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '$com_id' and package.package_year='$year' and package.package_type = '2' ");
   while ($row_pack = mysql_fetch_array($sql_pack_user)) {
     array_push($users, $row_pack["user_id"]);
   }

    $sql_addi_user = mysql_query("SELECT distinct user_id from users_companies where com_id = '$com_id' and year = '$year' and type='2' ");
    while ($row_pack = mysql_fetch_array($sql_addi_user)) {
     array_push($users, $row_pack["user_id"]);
   }
   

    return $users; 

}

function fetch_research_users($res_id){

	$users = array();
	$sql_sub = mysql_query("SELECT distinct user_id from research_users where res_id='".$res_id."' ");
   while ($row_pack = mysql_fetch_array($sql_sub)) {
     array_push($users, $row_pack["user_id"]);
   }

   return $users; 

}

class PA_admin{

	public $meeting_type;

	public function __construct($id) {
		$sql = "SELECT proxy_ad.*, companies.com_name, companies.com_id, met_type.type as meeting_type_name from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id join met_type on proxy_ad.meeting_type = met_type.id  where proxy_ad.id='$id' ";

		$query = mysql_query($sql);
		$result = mysql_fetch_array($query);

		$this->id = $id;
		$this->company_id = stripslashes($result["com_id"]);
		$this->company_name = stripslashes($result["com_name"]);
		$this->meeting_date = ($result["meeting_date"])?date("d M Y",$result["meeting_date"]):'';
		$this->evoting_start = ($result["evoting_start"])?date("d M Y",$result["evoting_start"]):'';
		$this->evoting_end = ($result["evoting_end"])?date("d M Y",$result["evoting_end"]):'';
		$this->evoting_plateform = $result["evoting_plateform"];
		
		$this->meeting_timestamp = $result["meeting_date"];
		$this->meeting_type = $result["meeting_type_name"];
		$this->meeting_type_id = $result["meeting_type"];
		$this->year = $result["year"];
		$this->gen_report = $result["report"];
		$this->teasor = $result["teasor"];
		$this->annual_report = $result["annual_report"];
		$this->meeting_outcome = $result["meeting_outcome"];
		$this->meeting_minutes = $result["meeting_minutes"];
		$this->notice = $result["notice"];
		$this->notice_link = $result["notice_link"];
		$this->proxy_slip = $result["proxy_slip"];
		$this->is_skipped = ($result["skipped_on"] == 0)?0:1;
		$this->flag_users = 0;
		$this->sub_users = array();
		$this->completed = ($result["completed_on"] == '')?0:1;
		$this->released_on = $result["released_on"];
		$this->previous_release = $result["previous_release"];
		$this->abridged_release = $result["abridged_release"];
		$this->previous_abridged_release = $result["previous_abridged_release"];
		$this->abridged_report = $result["abridged_report"];
		$this->vote_completed_on = $result["vote_completed_on"];
		$this->template_release = $result["template_release"];
		
	}

	public function report(){
		$this->company_name = name_filter($this->company_name);
		return ($this->gen_report)?'<a href="../proxy_reports/'.$this->gen_report.'" target="_blank">View</a>': 'Pending'; 
	}

	public function check_resolutions(){
		$sql_vote = mysql_query("SELECT id from voting where report_id='".$this->id."' ");
		if(mysql_num_rows($sql_vote) > 0) return true;
		else return false;
	}

	public function check_freeze(){
		$check_freeze = mysql_query("SELECT final_freeze, final_unfreeze from admin_proxy_ad where report_id='".$this->id."' and final_freeze != 0 order by id desc limit 1");
		if(mysql_num_rows($check_freeze) > 0){
			$row_freeze = mysql_fetch_array($check_freeze);
			if($row_freeze["final_freeze"] != 0 && $row_freeze["final_unfreeze"] == 0) return true;
			else return false;
		} else return false;
	}


	public function vote(){
	 $vot_check = mysql_query("SELECT id from voting where report_id='$row[id]' ");
      return (mysql_num_rows($vot_check) > 0) ?'Available':'Pending';

	}

	public function subs_bool(){
		$sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '".$this->company_id."' and package.package_year='".$this->year."' and package.package_type = '1' ");

		$sql_addi_user = mysql_query("SELECT distinct user_id from users_companies where com_id = '".$this->company_id."' and year = '".$this->year."' and type='1' ");

		if((mysql_num_rows($sql_pack_user) + mysql_num_rows($sql_addi_user)) > 0 ) return true;
		else return false;
	}

	public function users(){


		 $sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '".$this->company_id."' and package.package_year='".$this->year."' and package.package_type = '1' ");

		 while ($row_pack = mysql_fetch_array($sql_pack_user)) {
		   array_push($this->sub_users, $row_pack["user_id"]);
		 }

		  $sql_addi_user = mysql_query("SELECT distinct user_id from users_companies where com_id = '".$this->company_id."' and year = '".$this->year."' and type='1' ");
		  while ($row_pack = mysql_fetch_array($sql_addi_user)) {
		   array_push($this->sub_users, $row_pack["user_id"]);
		 }

		  $this->flag_users =  ( ( mysql_num_rows($sql_pack_user) + mysql_num_rows($sql_addi_user) ) > 0)? '1':'0';
		 
		 
		  if($this->flag_users == 1) {

		    ?>
		    <a href="#myModal" role="button" class="btn blue" data-toggle="modal" onclick="view_users(<?php echo $this->id;?>,'<?php echo $this->company_id;?>','<?php echo $this->company_name; ?>','<?php echo $this->year;?>');">View</a> 
		    <?php 
		}
	}

	public function add_user_button($count){
		?>
		 <a href="#myModal" role="button" class="btn" data-toggle="modal" onclick="add_sub_ui(<?php echo $count?>,<?php echo $this->company_id;?>,'<?php echo $this->company_name;?>',<?php echo $this->id;?>,<?php echo $this->year; ?>);">Add</a> 
		 <?php
	}

	public function release($count){
		if($this->check_status() == 0){
			if($this->released_on == 0){
		?>
		 <a href="javascript:;" role="button" class="btn blue" id="release_<?php echo $this->id ?>" onclick="release_reports(<?php echo $count?>,<?php echo $this->company_id;?>,<?php echo $this->id;?>,<?php echo $this->year; ?>);">Release<br>Reports</a> 
		 <?php
		} else {
			?>
			<a href="javascript:;" role="button" class="btn red" id="release_<?php echo $this->id ?>" onclick="unrelease_reports(<?php echo $count?>,<?php echo $this->company_id;?>,<?php echo $this->id;?>,<?php echo $this->year; ?>);">Unrelease<br>Reports</a> 
			<?php
		}
		}
	}

	public function release_abridged($count){
		if($this->vote_completed_on != 0){
			if($this->abridged_release == 0){
		?>
		 <a href="javascript:;" role="button" class="btn blue" id="abridged_release_<?php echo $this->id ?>" onclick="abridged_release(<?php echo $count?>,<?php echo $this->company_id;?>,<?php echo $this->id;?>,<?php echo $this->year; ?>);">Release<br>Abridged</a> 
		 <?php
		} else {
			?>
			<a href="javascript:;" role="button" class="btn yellow" id="abridged_release_<?php echo $this->id ?>" onclick="abridged_release(<?php echo $count?>,<?php echo $this->company_id;?>,<?php echo $this->id;?>,<?php echo $this->year; ?>);">Re-release<br>Abridged</a> 
			<?php
		}
		}
	}

	public function ses_voting($count){
		if($this->meeting_type_id != 5){
			if($this->check_freeze()){
				$class_add = 'green';
			} else if($this->check_resolutions()) {
				$class_add = 'yellow';
			}
			?>
			<button class="btn <?php echo $class_add ?>" data-toggle="modal" href="#stack1" onclick="ses_voting(<?php echo $count?>,'<?php echo $this->company_name; ?>',<?php echo $this->id?>)">Voting</button>
			<?php
		}	
	}

	public function edit_button($count){
		$color ='';
		if(($this->notice !='' || $this->notice_link != '') && $this->annual_report !='' ){
			if($this->gen_report){
				if($this->meeting_outcome != '' && $this->meeting_minutes != ''){
					$color = 'blue';
				} else {
					$color = 'green';
				}
			} else {
				$color = 'yellow';
			}
		}


		?>
		 <a href="#myModal" role="button" class="btn <?php echo $color; ?>" data-toggle="modal"  onclick="load_edit(<?php echo $count;?>,<?php echo $this->id;?>,'<?php echo $this->company_name?>','<?php echo $this->meeting_date;?>')">Edit</a>
		 <?php
	}

	public function edit_button_all($count){
		?>
		 <a href="#myModal" role="button" class="btn <?php echo $color; ?>" data-toggle="modal"  onclick="load_edit_all(<?php echo $count;?>,<?php echo $this->id;?>,'<?php echo $this->company_name?>','<?php echo $this->meeting_date;?>')">Edit</a>
		 <?php
	}

	public function meeting_results($count){
		?>
		 <a href="#myModal" role="button" class="btn blue" data-toggle="modal"  onclick="meeting_results_ui(<?php echo $count;?>,<?php echo $this->id;?>,'<?php echo $this->company_name?>','<?php echo $this->meeting_date;?>')">Meeting<br>Results</a>
		 <?php
	}
    
    public function custom_button($count){
    	$flag_check = 0;
    	if($this->flag_users == 1){
    		$users = fetch_customized_users($this->company_id, $this->year);
            if(sizeof($users) > 0){
		    foreach ($users as $user) {
		       $query_custom = mysql_query("SELECT report_upload from customized_reports where user_id='$user' and report_id='".$this->id."' ");
		       if(mysql_num_rows($query_custom) > 0){
		         $row_custom = mysql_fetch_array($query_custom);
		        if($row_custom["report_upload"] == '') $flag_check = 1;
		       } else {
		        $flag_check = 1;
		     }
		    }	
		    $color = ($flag_check == 1)?'':'green';
    	?>
		  <a href="#myModal" role="button" class="btn <?php echo $color; ?>" data-toggle="modal" data-backdrop="static" data-keyboard="false"  onclick="load_custom(<?php echo $count;?>,<?php echo $this->id;?>,'<?php echo $this->company_name?>','<?php echo $this->meeting_date?>',<?php echo $this->company_id?>)">Custom Reports</a>
		 <?php
		}
		}
	}
	public function skip($count){
		if($this->flag_users == 0){
			if($this->is_skipped == 0){
		?>
			<a href="javascript:;" role="button" class="btn" onclick="skip_report(<?php echo $count ?>,<?php echo $this->id;?>);">Skip</a>
		 <?php
			}
		}
	} 

	public function unskip($count){
		if($this->is_skipped == 1){
			if($this->meeting_timestamp >= strtotime("today")){
			?>
				<a href="javascript:;" role="button" class="btn yellow" onclick="unskip_report(<?php echo $count ?>,<?php echo $this->id;?>);">UnSkip</a>
			<?php
			}
		}
	}

	public function delete(){
		if($this->flag_users == 0){
		?>
		 <a href="javascript:;" role="button" class="btn" onclick="delete_report('<?php echo $this->id;?>');">Delete</a>
		 <?php
		}
	}

	public function delete_all(){
		
		?>
		 <a href="javascript:;" role="button" class="btn" onclick="delete_report('<?php echo $this->id;?>');">Delete</a>
		 <?php
		
	}

	public function check_status(){
		$flag_check = 0;
	    if($this->gen_report == '') $flag_check = 1;

		  $users = fetch_customized_users($this->company_id, $this->year);
		  if(sizeof($users) > 0){
		    foreach ($users as $user) {
		       $query_custom = mysql_query("SELECT report_upload from customized_reports where user_id='$user' and report_id='".$this->id."' ");
		       if(mysql_num_rows($query_custom) > 0){
		         $row_custom = mysql_fetch_array($query_custom);
		        if($row_custom["report_upload"] == '') $flag_check = 1;
		       } else {
		        $flag_check = 1;
		     }
		    }
		  }
		  return $flag_check;
	}

	public function custom_report($customized_user){
		$query_custom = mysql_query("SELECT report_upload from customized_reports where user_id='$customized_user' and report_id='".$this->id."' ");
		$row_custom = mysql_fetch_array($query_custom);
		return $row_custom["report_upload"];
	}

}

class CGS_admin{

	public function __construct($id) {
		$sql = "SELECT cgs.*, companies.com_name, companies.com_id from cgs inner join companies on cgs.com_id = companies.com_id  where cgs.cgs_id='$id' ";

		$query = mysql_query($sql);
		$result = mysql_fetch_array($query);

		$this->id = $id;
		$this->company_id = stripslashes($result["com_id"]);
		$this->company_name = stripslashes($result["com_name"]);
		$this->meeting_date = ($result["publishing_date"])?date("d M Y",$result["publishing_date"]):'';
		$this->meeting_timestamp = $result["publishing_date"];
		$this->year = $result["year"];
		$this->gen_report = $result["report_upload"];
		$this->govt_index = $result["govt_index"];
		$this->india_man = $result["india_man"];
		$this->govt_index_score = $result["govt_index_score"];
		$this->board_dir = $result["board_dir"];
		$this->dir_rem = $result["dir_rem"];
		$this->stake_eng = $result["stake_eng"];
		$this->fin_rep = $result["fin_rep"];
		$this->sustain = $result["sustain"];
		$this->comp_score = $result["comp_score"];
		$this->released_on = $result["released_on"];
		$this->previous_release = $result["previous_release"];
		$this->flag_sub = 0;
	}


	public function report(){
			$this->company_name = name_filter($this->company_name);

			return ($this->gen_report != '')?'<a href="../cgs/'.$this->gen_report.'" role="button" class="btn" data-toggle="modal" target="_blank">View</a>': 'Pending';

	}

	public function subscribers($count){

		$sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '".$this->company_id."' and package.package_year='".$this->year."' and package.package_type = '2' ");

	      $sql_sub = mysql_query("SELECT user_id from users_companies where com_id='".$this->company_id."' and type='2' and year='".$this->year."' ");

	      $this->flag_sub =  ((mysql_num_rows($sql_sub)+mysql_num_rows($sql_pack_user)) > 0)? '1':'0';
	      
	      if($this->flag_sub == 1) {
	        ?>
	        <a href="#myModal" role="button" class="btn" data-toggle="modal" onclick="view_users(<?php echo $this->id ?>,'<?php echo $this->company_id;?>','<?php echo $this->company_name?>','<?php echo $this->year;?>');">View</a> 

	      <?php
	    }
	    ?>
	     <a href="#myModal" role="button" class="btn" data-toggle="modal" onclick="add_sub_ui(<?php echo $count?>,<?php echo $this->company_id;?>,'<?php echo $this->company_name?>',<?php echo $this->id;?>,<?php echo $this->year; ?>);">Add</a>
	     <?php
	}

	public function subs_bool(){
		$sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '".$this->company_id."' and package.package_year='".$this->year."' and package.package_type = '2' ");

		$sql_addi_user = mysql_query("SELECT distinct user_id from users_companies where com_id = '".$this->company_id."' and year = '".$this->year."' and type='1' ");

		if((mysql_num_rows($sql_pack_user) + mysql_num_rows($sql_addi_user)) > 0 ) return true;
		else return false;
	}
                        
	public function delete($count){
		if($this->flag_sub == 0){
		?>
		 <a href="javascript:;" role="button" class="btn red" onclick="delete_cgs( <?php echo $this->id;?>);">Delete</a>
		<?php
		}
	}

	public function release($count){
		
			if($this->gen_report != ''){
				if($this->released_on == 0){
		?>
		 <a href="javascript:;" role="button" class="btn blue" id="release_<?php echo $this->id;?>" onclick="release_cgs(<?php echo $count;?>, <?php echo $this->id;?>);">Release Report</a>
		<?php
				} else {
					?>
					 <a href="javascript:;" role="button" class="btn red" id="release_<?php echo $this->id;?>" onclick="unrelease_cgs(<?php echo $count;?>, <?php echo $this->id;?>);">Unrelease Report</a>

					<?php
				}
			}
		
	}

	public function edit($count){
		$color ='';
		if($this->govt_index != '' && $this->india_man != '' && $this->board_dir != '' && $this->dir_rem != '' && $this->stake_eng != '' && $this->fin_rep != '' and $this->sustain != '' ){
			if($this->gen_report != ''){
				$color  = 'green';
			} else {
				$color = 'purple';
			}
		} else {
			if($this->gen_report != ''){
				$color  = 'yellow';
			}
		}
		?>
		<a href="#myModal" class="btn <?php echo $color; ?>" data-toggle="modal" onclick="edit_cgs(<?php echo $count;?>,'<?php echo $this->id;?>','<?php echo $this->company_name;?>');">Edit</a>
		<?php
	}
}

class Research_admin{
	public function __construct($id) {
		$sql = "SELECT research.*, companies.com_name, companies.com_id from research inner join companies on research.com_id = companies.com_id  where research.res_id='$id' ";

		$query = mysql_query($sql);
		$result = mysql_fetch_array($query);

		$this->id = $id;
		$this->company_id = stripslashes($result["com_id"]);
		$this->company_name = stripslashes($result["com_name"]);
		$this->meeting_date = ($result["publishing_date"])?date("d M Y",$result["publishing_date"]):'';
		$this->meeting_timestamp = $result["publishing_date"];		
		$this->year = $result["year"];
		$this->gen_report = $result["report_upload"];
		$this->heading = stripcslashes($result["heading"]);
		$this->description = stripcslashes($result["description"]);
		$this->flag_sub = 0;
		$this->released_on = $result["released_on"];
		$this->previous_release = $result["previous_release"];
	}

	public function subscribed($user_id){

		$sql_check = mysql_query("SELECT id from research_users where res_id='".$this->id."' and user_id = '".$user_id."' ");

		if(mysql_num_rows($sql_check)){
			return true;
		} else {
			return false;
		}
	}

	public function report(){
			$this->company_name = name_filter($this->company_name);
			return ($this->gen_report != '')?'<a href="../research/'.$this->gen_report.'" role="button" class="btn" data-toggle="modal" target="_blank">View</a>': 'Pending';

	}
	public function edit($count){
			?>
			<a href="#myModal" class="btn" data-toggle="modal" onclick="edit_res(<?php echo $count?>,<?php echo $this->id?>,'<?php echo $this->company_name?>')">Edit</a>
			<?php
	}
	public function delete($count){
			if($this->flag_sub == 0){
			?>
			<a href="javascript:;" role="button" class="btn red" onclick="delete_research('<?php echo $this->id;?>');">Delete</a>
			<?php
		}
	}
	public function subscribers($count){
		 $sql_sub = mysql_query("SELECT distinct user_id from research_users where res_id='".$this->id."' ");
          $this->flag_sub =  (mysql_num_rows($sql_sub) > 0)? '1':'0';
          if($this->flag_sub == 1) {
            ?>
            <a href="#myModal" role="button" class="btn" data-toggle="modal" onclick="view_users('<?php echo $this->id;?>','<?php echo $this->company_name;?>','<?php echo $this->year;?>');">View</a> 
          <?php
        }

         ?>
         <a href="#myModal" role="button" class="btn" data-toggle="modal" onclick="add_sub_ui(<?php echo $count?>,<?php echo $this->company_id;?>,'<?php echo $this->company_name;?>',<?php echo $this->id;?>,<?php echo $this->year; ?>);">Add</a>
         <?php
	}

	public function subs_bool(){
		$sql_sub = mysql_query("SELECT distinct user_id from research_users where res_id='".$this->id."' ");

		if( mysql_num_rows($sql_sub) > 0 ) return true;
		else return false;
	}

	public function release($count){

			if($this->gen_report != ''){
				if($this->released_on == 0){
		?>
		 <a href="javascript:;" role="button" class="btn blue" id="release_<?php echo $this->id;?>" onclick="release_research(<?php echo $count;?>, <?php echo $this->id;?>);">Release Report</a>
		<?php
				} else {
					?>
					 <a href="javascript:;" role="button" class="btn red" id="release_<?php echo $this->id;?>" onclick="unrelease_research(<?php echo $count;?>, <?php echo $this->id;?>);">Unrelease Report</a>

					<?php
				}
			}
		
	}


}