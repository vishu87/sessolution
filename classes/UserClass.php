<?php
class User {
	public $name;
	public $username;
	public $email;
	public $other_email;
	public $mobile;
	public $address;
	public $customized;
	public $pa_companies_total_year;
	public $pa_companies_total_year_string;
	public $pa_companies_subscribed_year;
	public $pa_companies_subscribed_year_string;

	public function __construct($id) {
		$sql = "select * from users where id='$id' ";
		$query = mysql_query($sql);
		if(mysql_num_rows($query) == 0){
			header("Location: ".STRSITE."access-denied.php");
		}
		$result = mysql_fetch_array($query);

		$this->name = stripslashes($result["name"]);
		$this->user_admin_name = stripslashes($result["user_admin_name"]);
		$this->username = $result["username"];
		$this->email = $result["email"];
		$this->other_email = $result["other_email"];
		$this->mobile = $result["mobile"];
		$this->address = stripcslashes($result["address"]);
		$this->sub_users = stripcslashes($result["sub_users"]);
		$this->itname = stripcslashes($result["IT_name"]);
		$this->itemail = stripcslashes($result["IT_email"]);
		$this->itcontact = stripcslashes($result["IT_contact"]);
		
		$this->parent = ($result["created_by_prim"] == 0)?$id:$result["created_by_prim"];
		$this->is_parent = ($result["created_by_prim"] == 0)?'1':'0';
		$this->meeting_alert = $result["meeting_alert"];
		$this->voting_access = $result["voting_access"];
		
		$query_req = mysql_query("SELECT proxy_module, email, voting_span, customized, voting_template, voting_template_type, pa_mail_details from users where id='".$this->parent."' ");
		$row_req = mysql_fetch_array($query_req);

		$this->customized = stripcslashes($row_req["customized"]);
		$this->voting_template = stripcslashes($row_req["voting_template"]);
		$this->voting_template_type = stripcslashes($row_req["voting_template_type"]);

		$this->pa_mail_details = $row_req["pa_mail_details"];
		
		$this->parent_email = $row_req["email"];
		// $this->pa_mail_com = $row_req["pa_mail_com"];

		$this->portal_access = stripcslashes($result["portal_access"]);
		$this->proxy_module = $row_req["proxy_module"];
		$this->voting_span = $row_req["voting_span"];
		$this->voting_class= ($row_req["voting_class"]=='')?'GeneralVoting':$row_req["voting_class"];
	}

	public function pa_subscribed_comapnies_year($user_id,$year) {
		$total_comp_subscribed = array();
		$total_comp_report_subscribed = array();
		$total_comp_limited_subscribed = array();

		$sql_report = mysql_query(" SELECT distinct package_company.com_id ,users_package.limited from package_company inner join users_package on package_company.package_id = users_package.package_id inner join package on users_package.package_id=package.package_id where users_package.user_id='$user_id' AND package.package_year='$year' AND package.package_type='1' "); 

		while($row_rep = mysql_fetch_array($sql_report)){
			array_push($total_comp_subscribed, $row_rep["com_id"]);
			if($row_rep["limited"] == 0) array_push($total_comp_report_subscribed, $row_rep["com_id"]);
			if($row_rep["limited"] == 1) array_push($total_comp_limited_subscribed, $row_rep["com_id"]);
		}

		$sql_report = mysql_query("SELECT distinct com_id from users_companies where type='1' and year='$year' and user_id='$user_id'  ");
	      while($row_rep = mysql_fetch_array($sql_report)){
	        if(!in_array($row_rep["com_id"], $total_comp_subscribed)){
				array_push($total_comp_subscribed, $row_rep["com_id"]);
	        	array_push($total_comp_report_subscribed, $row_rep["com_id"]);
	        }
	      }

		$this->companies_subscribed_year = $total_comp_subscribed;
		$this->companies_report_subscribed_year = $total_comp_report_subscribed;
		
		$this->companies_limited_year = $total_comp_limited_subscribed;

		$this->companies_subscribed_year_string = (sizeof($total_comp_subscribed) > 0)?implode(',', $total_comp_subscribed):'';

	}

	public function cgs_subscribed_comapnies_year($user_id,$year) {
		$total_comp_subscribed = array();

		$sql_report = mysql_query(" SELECT distinct package_company.com_id from package_company inner join users_package on package_company.package_id = users_package.package_id inner join package on users_package.package_id=package.package_id where users_package.user_id='$user_id' AND package.package_year='$year' AND package.package_type='2' "); 

		while($row_rep = mysql_fetch_array($sql_report)){
			array_push($total_comp_subscribed, $row_rep["com_id"]);
		}

		$sql_report = mysql_query("SELECT distinct com_id from users_companies where type='2' and year='$year' and user_id='$user_id'  ");
	      while($row_rep = mysql_fetch_array($sql_report)){
	        if(!in_array($row_rep["com_id"], $total_comp_subscribed))
	          array_push($total_comp_subscribed, $row_rep["com_id"]);
	      }

		$this->cgs_companies_subscribed_year = $total_comp_subscribed;

	}


	public function pa_total_comapnies_year($year) {

		$total_comp_total = array();

		$sql_report = mysql_query(" SELECT distinct package_company.com_id from package_company inner join package on package_company.package_id=package.package_id where package.package_year='$year' AND package.package_type='1' "); 

		while($row_rep = mysql_fetch_array($sql_report)){
			array_push($total_comp_total, $row_rep["com_id"]);
		}

		$sql_report = mysql_query("SELECT distinct com_id from users_companies where type='1' and year='$year'  ");
	      while($row_rep = mysql_fetch_array($sql_report)){
	        if(!in_array($row_rep["com_id"], $total_comp_total))
	          array_push($total_comp_total, $row_rep["com_id"]);
	      }

		$this->companies_total_year = $total_comp_total;
		$this->companies_total_year_string = (sizeof($total_comp_total) > 0)?implode(',', $total_comp_total):'';

	}

	public function wishlist($user_id, $year=null){
		$wishlist_companies = array();

		$sql_report = mysql_query(" SELECT com_id from user_voting_company where user_id='$user_id' "); 

		while($row_rep = mysql_fetch_array($sql_report)){
			array_push($wishlist_companies, $row_rep["com_id"]);
		}
		$this->wishlist = $wishlist_companies;
		$this->wishlist_string = (sizeof($wishlist_companies) > 0)?implode(',', $wishlist_companies):'';
	}

	public function voting_records($user_id, $proxy_allow = null){
		$voting_records_companies = array();

		if($proxy_allow == 1){
			$sql_report = mysql_query("SELECT distinct report_id from user_voting_proxy_reports where user_id='$user_id' UNION SELECT distinct report_id from user_proxy_allow where user_id = $user_id and status = 0 ");	
		} else {
			$sql_report = mysql_query("SELECT distinct report_id from user_voting_proxy_reports where user_id='$user_id' ");
		} 

		while($row_rep = mysql_fetch_array($sql_report)){
			array_push($voting_records_companies, $row_rep["report_id"]);
		}
		$this->voting_records = $voting_records_companies;
		$this->voting_records_string = (sizeof($voting_records_companies) > 0)?implode(',', $voting_records_companies):'';
	}

	public function voting_access_companies(){
		$voting_ac_comp = array();

		$sql_report = mysql_query("SELECT distinct com_id from voting_access where user_id='$_SESSION[MEM_ID]' "); 

		while($row_rep = mysql_fetch_array($sql_report)){
			array_push($voting_ac_comp, $row_rep["com_id"]);
		}
		$this->voting_ac_comp = $voting_ac_comp;
	}

	public function voting_records_firm($parent_id, $proxy_allow = null){
		$voting_records_companies = array();
		$user_ids  = array();
		array_push($user_ids, $parent_id);

		$sql_user = mysql_query("SELECT id from users where created_by_prim='$parent_id' ");
		while ($row_user = mysql_fetch_array($sql_user)) {
			array_push($user_ids, $row_user["id"]);
		}

		$user_string = implode(',', $user_ids);

		if($proxy_allow == 1){
			$sql_report = mysql_query("SELECT distinct report_id from user_voting_proxy_reports where user_id IN ($user_string) UNION SELECT distinct report_id from user_proxy_allow where user_id IN ($user_string) and status = 0 ");
		} else {
			$sql_report = mysql_query("SELECT distinct report_id from user_voting_proxy_reports where user_id IN ($user_string) ");
		}

		while($row_rep = mysql_fetch_array($sql_report)){
			array_push($voting_records_companies, $row_rep["report_id"]);
		}

		$this->user_string = $user_string;
		$this->voting_records_firm = $voting_records_companies;
		$this->voting_records_firm_string = (sizeof($voting_records_companies) > 0)?implode(',', $voting_records_companies):'';
	}


}

class PA{

	public $meeting_type;

	public function __construct($id) {
		$sql = "SELECT proxy_ad.*, companies.com_name, companies.com_id, companies.com_isin, evoting.name, evoting.link, met_type.type as meeting_type_name from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id left join evoting on LOWER(proxy_ad.evoting_plateform) = evoting.evoter join met_type on proxy_ad.meeting_type = met_type.id  where proxy_ad.id='$id' ";

		$query = mysql_query($sql);
		$result = mysql_fetch_array($query);

		$this->id = $id;
		$this->company_id = stripslashes($result["com_id"]);
		$this->company_name = stripslashes($result["com_name"]);
		$this->isin = stripslashes($result["com_isin"]);
		$this->meeting_date = ($result["meeting_date"])?date("d M Y",$result["meeting_date"]):'';
		$this->record_date = ($result["record_date"])?date("d M Y",$result["record_date"]):'';
		$this->evoting_start = ($result["evoting_start"])?date("d M Y",$result["evoting_start"]):'';
		$this->evoting_end = ($result["evoting_end"])?date("d M Y",$result["evoting_end"]):'';
		$this->evoting_plateform = $result["evoting_plateform"];
		$this->evoting_name = $result["name"];
		$this->evoting_link = $result["link"];

		$this->old_meeting = ($result["meeting_date"] < strtotime("today"))?true:false;
		$this->meeting_type = $result["meeting_type_name"];
		$this->meeting_type_id = $result["meeting_type"];
		$this->year = $result["year"];
		$this->gen_report = $result["report"];
		$this->teasor = '<a target="_blank" href="'.$result["teasor"].'">'.$result["teasor"].'</a>';
		$this->annual_report = '<a target="_blank" href="'.$result["annual_report"].'">'.$result["annual_report"].'</a>';
		$this->meeting_outcome = '<a target="_blank" href="'.$result["meeting_outcome"].'">'.$result["meeting_outcome"].'</a>';

		$this->meeting_minutes = '<a target="_blank" href="'.$result["meeting_minutes"].'">'.$result["meeting_minutes"].'</a>';
		$this->notice = $result["notice"];
		$this->notice_link = '<a target="_blank" href="'.$result["notice_link"].'">'.$result["notice_link"].'</a>';
		$this->proxy_slip = $result["proxy_slip"];
		$this->attendance_slip = $result["attendance_slip"];
		$this->is_skipped = ($result["skipped_on"] == 0)?0:1;
		$this->is_released = ($result["released_on"] == 0)?false:true;
		
	}

	public function subscribed($companies){
		if(in_array($this->company_id, $companies)){
			return true;
		} else {
			return false;
		}
	}

	public function subscription_request($content=null){
		if(!$content) { $content_fill = 'Subscribe'; $type =0; }
		else {$content_fill = $content; $type =1; }
		$check = mysql_query("SELECT id from subscription_request where com_id = '".$this->company_id."' and report_type='1' and user_id='$_SESSION[MEM_ID]' and status='0' limit 1");
		if(mysql_num_rows($check) > 0) return (!$content)?'Subscription<br>Requested':'Full Report<br>Requested';
		else return '<a href="javascript:;" class="btn green span12" style="max-width:120px"  onclick="subscribe('.$this->id.','.$this->company_id.',1,'.$type.')">'.$content_fill.'</a>';
	}

	public function coverage($companies){
		if(in_array($this->company_id, $companies)){
			if($this->is_skipped == 0) return true;
			else return false;
		} else {
			return false;
		}
	}

	public function fetch_all_users(){

		$users = array();
			$sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '".$this->company_id."' and package.package_year='".$this->year."' and package.package_type = '1' ");
		   while ($row_pack = mysql_fetch_array($sql_pack_user)) {
		     array_push($users, $row_pack["user_id"]);
		   }

		    $sql_addi_user = mysql_query("SELECT distinct user_id from users_companies where com_id = '".$this->company_id."' and year = '".$this->year."' and type='1' ");
		    while ($row_pack = mysql_fetch_array($sql_addi_user)) {
		     array_push($users, $row_pack["user_id"]);
		   }
		   

		    return $users; 

		}

	public function report($user_id,$customized){
		$this->company_name = name_filter($this->company_name);

		if($this->is_released){

		if($customized == 0){
         if($this->gen_report != '') {
         	echo '<a href="../preview/report_preview_user.php?res='.encrypt($this->id).'&type='.encrypt(1).'" role="button" class="btn span12" style="max-width:100px;" data-toggle="modal" target="_blank">View</a>';
         	} else {
         		$query_deadline = mysql_query("SELECT deadline from report_analyst where report_id='".$this->id."' and rep_type='1' and type = '3' ");
         		if(mysql_num_rows($query_deadline) > 0){
         			$row_deadline = mysql_fetch_array($query_deadline);
         			$ts = $row_deadline["deadline"] + 86400;
         			if($row_deadline["deadline"] != '') echo 'Available on<br>'.date("d M y", $ts);
         			else echo 'Pending';
         		} else {
         			echo 'Pending';
         		}
         	}
        
        } else {

          $sql_c = mysql_query("SELECT report_upload from customized_reports where user_id= '$user_id' and report_id='".$this->id."' ");
          $row_c = mysql_fetch_array($sql_c);
           if($row_c["report_upload"] != '') {
           	echo '<a href="../preview/report_preview_user.php?res='.encrypt($this->id).'&amp;type='.encrypt(1).'" role="button" class="btn span12" style="max-width:100px;" data-toggle="modal" target="_blank">View</a>';
           } else {
           		$query_deadline = mysql_query("SELECT deadline from report_analyst where report_id='".$this->id."' and rep_type='1' and type = '3' ");
         		if(mysql_num_rows($query_deadline) > 0){
         			$row_deadline = mysql_fetch_array($query_deadline);
         			$ts = $row_deadline["deadline"] + 86400;
         			if($row_deadline["deadline"] != '') echo 'Available on<br>'.date("d M y", $ts);
         			else echo 'Pending';
         		} else {
         			echo 'Pending';
         		}
           }
        }

    	} else {
    		$query_deadline = mysql_query("SELECT deadline from report_analyst where report_id='".$this->id."' and rep_type='1' and type = '3' ");
         		if(mysql_num_rows($query_deadline) > 0){
         			$row_deadline = mysql_fetch_array($query_deadline);
         			$ts = $row_deadline["deadline"] + 86400;
         			if($row_deadline["deadline"] != '') echo 'Available on<br>'.date("d M y", $ts);
         			else echo 'Pending';
         		} else {
         			echo 'Pending';
         		}
    	}

	}

	public function details(){
		return	'<a  href="#myModal" role="button" class="btn blue span12" style="max-width:100px" data-toggle="modal" onclick="view_report(\''.$this->company_name.' '.$this->isin.'\','.$this->id.')" >Details</a>';
	}

	public function notice_final(){
		if($this->notice != ''){
         	return '<a href="../proxy_notices/'.$this->notice.'" target="_blank">View</a><br>'.$this->notice_link;
        } else {
        	return $this->notice_link;
        }

	}

	public function slip(){
		if($this->proxy_slip != ''){
         	return '<a href="../proxy_slips/'.$this->proxy_slip.'" target="_blank">View<a>';
        } else {
        	return '';
        }

	}
	public function attendance(){
		if($this->attendance_slip != ''){
         	return '<a href="../attendance_slips/'.$this->attendance_slip.'" target="_blank">View<a>';
        } else {
        	return '';
        }

	}

	public function voting_record_users($parent_id=null){

		return '<a href="#stack1" data-toggle="modal"  role="button" onclick="view_portfolio_users('.$this->id.')" class="btn span12" style="max-width:100px;" >View</a>';
		
		 // $vot_sql = mysql_query("SELECT users.name from user_voting_proxy_reports inner join users on user_voting_proxy_reports.user_id = users.id where report_id='".$this->id."' and (users.created_by_prim='$parent_id' OR users.id='$parent_id' ) ");
		 // $name_array = array();
	  //    while ($row_sql = mysql_fetch_array($vot_sql)) {
	  //    	array_push($name_array, $row_sql["name"]);
	  //    }
	  //    return implode('<br>',$name_array);
	}

	public function ses_voting($user_id, $type=null){
		if($this->meeting_type_id != 5){

			$query = mysql_query("SELECT user_proxy_allow.id from user_proxy_allow left join users on user_proxy_allow.user_id = users.id where (user_proxy_allow.user_id='$user_id' OR users.created_by_prim = '$user_id') AND user_proxy_allow.report_id='".$this->id."' and user_proxy_allow.status = 0 ");

			if(mysql_num_rows($query) == 0){
				
				$voting = new SesVoting();
				$check_freeze = mysql_query("SELECT final_freeze, final_unfreeze from admin_proxy_ad where report_id='".$this->id."' and final_freeze != 0 order by id desc limit 1");
				if(mysql_num_rows($check_freeze) > 0){
					$row_freeze = mysql_fetch_array($check_freeze);
					if($row_freeze["final_freeze"] != 0 && $row_freeze["final_unfreeze"] == 0) $flag_v = 1;
					else $flag_v = 0;
				} else $flag_v = 0;

			     if($flag_v == 1){
			     	return $voting->voting_button($this->id,$this->company_name.' '.$this->isin, $type);
			     } else {
			     	return $this->request_button();
			     }

			} else {

				if($type == 1)
					return 'The company was added post record date';
				else
					return '<a href="#stack1" data-toggle="modal" role="button" onclick="proxy_allow_ui('.$this->id.')" class="btn yellow span12 ttip" data-toggle="tooltip" title="The company was added in portfolio post the record date for the meeting. Click to allow users record votes for this meeting" style="max-width:100px; margin:5px 0 0 0" >Allow</a>';
			}
		}
	}

	private function request_button(){

		$check = mysql_query("SELECT id from vote_request where report_id='".$this->id."' and user_id='$_SESSION[MEM_ID]' and solved = 0 ");
		if(mysql_num_rows($check) > 0){
			return 'Requested';
		} else {
			return '<a id="req_vote_'.$this->id.'" href="javascript:;" class="btn span12" style="max-width:100px" onclick="request_vote('.$this->id.')" >Request</a>';
		}
	}

	public function meeting_results(){

		$check = mysql_query("SELECT id from meeting_results where report_id='".$this->id."' limit 1");
		if(mysql_num_rows($check) == 0){
			return '';
		} else {
			return '<a href="#stack3" data-toggle="modal" role="button" class="btn span12" style="max-width:100px" onclick="meeting_results('.$this->id.')" >View</a>';
		}
	}

	public function self_voting($user_id, $type){

		return $this->ses_voting($user_id,$type);

		// if($this->check_final_freeze()){
		// 	$class_add = 'green'; $icon_add='icon-thumbs-up';
		// }
		// elseif($this->check_freeze()){
		// 	$class_add = 'yellow'; $icon_add='icon-thumbs-up';
		// } else {
		// 	$class_add=''; $icon_add='icon-pencil';
		// }

	 //    return '<a id="vote_'.$this->id.'" href="#stack1" role="button" class="btn '.$class_add.'" data-toggle="modal" onclick="view_self_vote(\''.stripcslashes($this->company_name).'\','.$this->id.')" data-backdrop="static" data-keyboard="false" ><i class="'.$icon_add.'"></i> Vote</a>';

	}

	public function check_freeze() {
		$check_freeze = mysql_query("SELECT freeze_on, unfreeze_on from user_proxy_ad where user_id='$_SESSION[MEM_ID]' and report_id='".$this->id."' and freeze_on != 0 order by id desc limit 1");
		if(mysql_num_rows($check_freeze) > 0){
			$row_freeze = mysql_fetch_array($check_freeze);
			if($row_freeze["freeze_on"] != 0 && $row_freeze["unfreeze_on"] == 0) return true;
			else return false;
		} else return false;
	}

	public function check_final_freeze() {
		$query = mysql_query("SELECT created_by_prim from users where id='$_SESSION[MEM_ID]' ");
		$row = mysql_fetch_array($query);
		if($row["created_by_prim"] == 0) $parent_id = $_SESSION["MEM_ID"];
		else $parent_id = $row["created_by_prim"];

		$check_freeze = mysql_query("SELECT final_freeze, final_unfreeze from user_proxy_ad where user_id='$parent_id' and report_id='".$this->id."' and final_freeze != 0 order by id desc limit 1");
		if(mysql_num_rows($check_freeze) > 0){
			$row_freeze = mysql_fetch_array($check_freeze);
			if($row_freeze["final_freeze"] != 0 && $row_freeze["final_unfreeze"] == 0) return true;
			else return false;
		} else return false;
	}


	public function request_proxy($user_id, $proxy_module){
		//SES Voting Module 0->ses
			if($proxy_module == 0)
			{
				$query_proxy = mysql_query("SELECT * from self_proxies where user_id='$user_id' and proxy_id = '".$this->id."' ");
		        $num2 = mysql_num_rows($query_proxy);
		        if($num2 != 0){
		        	$this->request_proxy($user_id, 1);
		        } else {
					$query_proxy = mysql_query("SELECT * from proxies where user_id='$user_id' and proxy_id = '".$this->id."' ");
			        $num = mysql_num_rows($query_proxy);
			        $proxy = mysql_fetch_array($query_proxy);
			        if($num == 0){
			          $this->proxy_status = '';
			          $this->proxy_button = '<a href="javascript:;" role="button" class="btn" onclick="request_proxy('.$this->id.')">Request Proxy</a>';
			        } elseif ($proxy["voter_id"] == 0) {
			           $this->proxy_status = 'Proxy Requested';
			           $this->proxy_button = '<a href="javascript:;" role="button" class="btn black" onclick="reset_proxy('.$this->id.')">Reset Proxy</a>';
			        }  elseif ($proxy["form"] == '') {
			           $this->proxy_status = 'Proxy Assigned';
			           $this->proxy_button = '<a href="#myModal" role="button" class="btn yellow" data-toggle="modal" onclick="upload_form('.$this->id.','.$proxy["id"].',1)" data-backdrop="static" data-keyboard="false" >Upload Form</a><a href="javascript:;" role="button" class="btn black" onclick="reset_proxy('.$this->id.')">Reset Proxy</a>';
			        } elseif($proxy["slip"] == '') {
			           $this->proxy_status = 'Proxy Form Recieved by SES';
			           $this->proxy_button = '<a href="javascript:;" role="button" class="btn black" onclick="reset_proxy('.$this->id.')">Reset Proxy</a>';
			        } else {
			           $this->proxy_status = 'Completed';
			           $this->proxy_button = '<a href="../user_proxy_slips/'.$proxy["slip"].'" role="button" class="btn yellow" target="_blank">View Slip</a>';
			        }
		  	  }
			}
		

		//Self Voting Module 1->self
		if($proxy_module == 1){
			$query_proxy = mysql_query("SELECT * from proxies where user_id='$user_id' and proxy_id = '".$this->id."' ");
	        $num2 = mysql_num_rows($query_proxy);
	        if($num2 != 0){
	        	$this->request_proxy($user_id, 0);
	        } else {
			$query_proxy = mysql_query("SELECT * from self_proxies where user_id='$user_id' and proxy_id = '".$this->id."' ");
	        $num = mysql_num_rows($query_proxy);
	        $proxy = mysql_fetch_array($query_proxy);
	        if($num == 0){
	          $this->proxy_status = '';
	          //$this->proxy_button = '<a href="#myModal" data-toggle="modal"  role="button" style="max-width:115px;" class="btn span12" onclick="assign_voter(\''.$this->company_name.'\','.$this->id.')">Appoint Voter</a>';
	          $this->proxy_button = '<a href="#stack1" data-toggle="modal"  role="button" style="max-width:115px;" class="btn span12" onclick="cast_vote(\''.$this->company_name.'\','.$this->id.')">Cast Vote</a>';
	        }  elseif ($proxy["form"] == '' && $proxy["proxy_skipped"] == 0) {
	           $this->proxy_status = 'Proxy Assigned';
	           $this->proxy_button = '<a href="#myModal" data-toggle="modal"  role="button" class="btn span12" style="max-width:115px;" onclick="assign_voter(\''.$this->company_name.'\','.$this->id.')">Change Voter</a><a href="#myModal" role="button" class="btn span12 yellow" data-toggle="modal" style="max-width:115px; margin-left: 0;" onclick="upload_form('.$this->id.','.$proxy["id"].',2)" data-backdrop="static" data-keyboard="false">Upload Form</a><a href="javascript:;" role="button" class="btn span12 black" onclick="reset_proxy('.$this->id.')" style="max-width:115px;margin-left: 0;">Reset Proxy</a>';
	        } else {
	           $this->proxy_status = 'Processed';
	           $this->proxy_button = '<a href="javascript:;" role="button" class="btn span12 black" onclick="reset_proxy('.$this->id.')" style="max-width:115px;margin-left: 0;">Reset Proxy</a>';
	        }
	    }
		}


		if($this->old_meeting) $this->proxy_button = '';
	}
                         

}

class CGS{

	public function __construct($id) {
		$sql = "SELECT cgs.*, companies.com_name, companies.com_id from cgs inner join companies on cgs.com_id = companies.com_id  where cgs.cgs_id='$id' ";

		$query = mysql_query($sql);
		$result = mysql_fetch_array($query);

		$this->id = $id;
		$this->company_id = stripslashes($result["com_id"]);
		$this->company_name = stripslashes($result["com_name"]);
		$this->meeting_date = ($result["publishing_date"])?date("d M Y",$result["publishing_date"]):'';
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
	}

	public function subscribed($companies){
		if(in_array($this->company_id, $companies)){
			return true;
		} else {
			return false;
		}
	}

	public function report(){
			$this->company_name = name_filter($this->company_name);
			return ($this->gen_report != '')?'<a href="../preview/report_preview_user.php?res='.encrypt($this->id).'&amp;type='.encrypt(2).'" role="button" class="btn span12" style="max-width:100px;" data-toggle="modal" target="_blank">View</a>': 'Pending';

	}

	public function subscription_request(){
		$check = mysql_query("SELECT id from subscription_request where com_id = '".$this->company_id."' and report_type='2' and user_id='$_SESSION[MEM_ID]' and status='0' limit 1");
		if(mysql_num_rows($check) > 0) return false;
		else return true;
	}
                        

}

class Research{
	public function __construct($id) {
		$sql = "SELECT research.*, companies.com_name, companies.com_id from research inner join companies on research.com_id = companies.com_id  where research.res_id='$id' ";

		$query = mysql_query($sql);
		$result = mysql_fetch_array($query);

		$this->id = $id;
		$this->company_id = stripslashes($result["com_id"]);
		$this->company_name = stripslashes($result["com_name"]);
		$this->meeting_date = ($result["publishing_date"])?date("d M Y",$result["publishing_date"]):'';
		$this->year = $result["year"];
		$this->gen_report = $result["report_upload"];
		$this->heading = stripcslashes($result["heading"]);
		$this->description = stripcslashes($result["description"]);
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
			return ($this->gen_report != '')?'<a href="../preview/report_preview_user.php?res='.encrypt($this->id).'&amp;type='.encrypt(3).'" role="button" class="btn span12" style="max-width:100px;" data-toggle="modal" target="_blank">View</a>': 'Pending';

	}

	public function subscription_request(){
		$check = mysql_query("SELECT id from subscription_request where report_id = '".$this->id."' and report_type='3' and user_id='$_SESSION[MEM_ID]' and status='0' limit 1");
		if(mysql_num_rows($check) > 0) return false;
		else return true;
	}

}