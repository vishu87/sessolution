<?php
class SesVoting {

	public $add_fields = array("proxy_id","vote_id","vote","comment","shares","voting_type");
	public $check_form = 0;

	function voting_button($report_id, $company_name,$type){

		if($this->check_final_freeze($report_id) && $type == 2){
			$class_add = 'green'; $icon_add='icon-thumbs-up';
		}
		elseif($this->check_freeze($report_id) && $type == 1){
			$class_add = 'green'; $icon_add='icon-thumbs-up';
		} else {
			$class_add=''; $icon_add='icon-pencil';
		}

		return '<a id="vote_'.$report_id.'" href="#stack1" role="button" class="btn '.$class_add.' span12" style="max-width:100px" data-toggle="modal" onclick="view_vote(\''.stripcslashes($company_name).'\','.$report_id.','.$type.')" data-backdrop="static" data-keyboard="false">Vote</a>';
	
	}

	

	public function check_freeze($report_id) {
		$check_freeze = mysql_query("SELECT freeze_on, unfreeze_on from user_proxy_ad where user_id='$_SESSION[MEM_ID]' and report_id='".$report_id."' and freeze_on != 0 order by id desc limit 1");
		if(mysql_num_rows($check_freeze) > 0){
			$row_freeze = mysql_fetch_array($check_freeze);
			if($row_freeze["freeze_on"] != 0 && $row_freeze["unfreeze_on"] == 0) return true;
			else return false;
		} else return false;
	}

	public function check_final_freeze($report_id) {
		$query = mysql_query("SELECT created_by_prim from users where id='$_SESSION[MEM_ID]' ");
		$row = mysql_fetch_array($query);
		if($row["created_by_prim"] == 0) $parent_id = $_SESSION["MEM_ID"];
		else $parent_id = $row["created_by_prim"];

		$check_freeze = mysql_query("SELECT final_freeze, final_unfreeze from user_admin_proxy_ad where user_id='".$parent_id."' and report_id='".$report_id."' and final_freeze != 0 order by id desc limit 1");
		if(mysql_num_rows($check_freeze) > 0){
			$row_freeze = mysql_fetch_array($check_freeze);
			if($row_freeze["final_freeze"] != 0 && $row_freeze["final_unfreeze"] == 0) return true;
			else return false;
		} else return false;
	}


	function voting_ui($proxy_id,$parent_id=null,$type){
		$report_id= $proxy_id;

		if($type == 1){
			$voting_table = 'user_voting';
			$detail_table = 'user_proxy_ad';
		} else if($type == 2){
			$voting_table = 'user_admin_voting';
			$detail_table = 'user_admin_proxy_ad';
		}

		$pa_report = new PA($report_id);
		if($pa_report->meeting_type_id == 5) die('No voting facility available for these meetings');

		$users_auth = $pa_report->fetch_all_users();

		$flag_auth = (in_array($parent_id, $users_auth))?true:false;
		$check_auth = $this->get_voting_users($parent_id,$proxy_id);

		$user_pa = new user_proxy_ad($report_id,$parent_id,$type);

		if($_SESSION["MEM_ID"] != $parent_id){
			if(!in_array($_SESSION["MEM_ID"], $check_auth)){
				die('You are not Authorized to vote for this company');
			}
		}
		
		if($type == 1){
			$query_check = mysql_query("SELECT id from user_voting_proxy_reports where report_id='$report_id' and user_id = '$_SESSION[MEM_ID]' ");
			if(mysql_num_rows($query_check) == 0){
				die('Please add this meeting in your portfolio before voting. / If record date is over, please ask admin to allow voting for this meeting.');
			}
		}

		$recos = array();
		$sql_reco = mysql_query("SELECT * from ses_recos");
		while ($row_reco = mysql_fetch_array($sql_reco)) {
		  $recos[$row_reco["id"]] = $row_reco["reco"];
		}
		
		if($_SESSION["MEM_ID"] == $parent_id && $type == 2){

			?>
			<div class="row-fluid" style="margin:0 0 10px 0;">
				<div class="span4">
					<form action="#" class="form-horizontal" style="margin-left:0">
						<input type="text" value="<?php echo $user_pa->deadline ?>" id="deadline_report" name="deadline_report" class="m-wrap small datepicker_month ttip" data-placement="bottom" placeholder="Set Deadline" title="Deadline">
                        <button type="button" class="btn blue icn-only" id="set_deadline" onclick="set_dline(<?php echo $proxy_id; ?>)" ><i class="m-icon-swapright m-icon-white"></i></button>
                     </form>
				</div>
				<div class="span4" align="center">
					<?php  

					if($user_pa->can_change && $user_pa->freeze == 0 && $user_pa->ignore_an == 0){ ?>
                      <select id="an_ses_id" onchange="select_ses_voting(<?php echo $report_id ?>)" class="m-wrap">
                      	<option value="0">Copy Data From..</option>
                      	<?php 
                      	$query_sql_all = mysql_query("SELECT users.id, users.name, users.user_admin_name from users inner join user_voting_proxy_reports on users.id = user_voting_proxy_reports.user_id where (users.created_by_prim='$parent_id' || users.id='$parent_id') && user_voting_proxy_reports.report_id = $report_id  ");
						while ($row_u = mysql_fetch_array($query_sql_all)) {
							echo '<option value="'.$row_u["id"].'">';
							if($row_u["id"] == $parent_id){
								echo ($row_u["user_admin_name"]!='')?$row_u["user_admin_name"]:$row_u["name"];
							} else echo $row_u["name"];
							echo '</option>';
						}

                      	?>
                      	<option value="-1"> None</option>
                      </select>
                    <?php
                      }
                    ?>
				</div>
				<?php if($user_pa->final_freeze == 0) { ?>
				<div class="span4" align="right">
					<div class="btn-group hidden-phone">
						<a href="javascript:;" class="btn <?php echo ($user_pa->ignore_an == 0)?'green':'';?>" onclick="ignore_an(<?php echo $proxy_id; ?>)" id="ign">Do not include PM's Votes</a>
						<a href="javascript:;" class="btn <?php echo ($user_pa->ignore_an == 1)?'green':'';?>" onclick="include_an(<?php echo $proxy_id; ?>)" id="inc">Include PM's Votes</a>
					</div>
				</div>
				<?php } ?>
			</div>
			<?php
		} else {	
			?>
			<div class="row-fluid" style="margin:0 0 10px 0;">
				<div class="span12">
					<button class="btn yellow">Deadline: <?php echo $user_pa->deadline; ?></button>
				</div>
			</div>
			<?php

		}

		$check_freeze = mysql_query("SELECT final_freeze, final_unfreeze from admin_proxy_ad where report_id='".$report_id."' and final_freeze != 0 order by id desc limit 1");
		if(mysql_num_rows($check_freeze) > 0){
			$row_freeze = mysql_fetch_array($check_freeze);
			if($row_freeze["final_freeze"] != 0 && $row_freeze["final_unfreeze"] == 0) $flag_v = 1;
			else $flag_v = 0;
		} else $flag_v = 0;

		if($flag_v == 1){ // if votes have beeen freezed by admin

		$str = '<form id="VotingForm"><input type="hidden" name="proxy_id" value="'.$proxy_id.'" >';

		if($user_pa->ignore_an == 0 || $type == 1){

		$str .= '<table class="table table-striped table-bordered table-advance table-hover" id="VotingFormTable"><tr><th>SN</th><th>Resolution Name</th><th>Management Recommendation</th><th>Proposal by Management or Shareholder</th><th>SES Recommendation</th>';
		if($flag_auth && $user_pa->can_change && $user_pa->freeze == 0) $str .= '<th>Copy</th>';
		$str .= '<th>Your Vote</th><th>Comment</th></tr>';
		$man_recos = array("","FOR","AGAINST","ABSTAIN");
		$man_share_recos = array("","Management","Shareholders");

		$check_custom_vote = mysql_query("SELECT check_id from customized_reports where user_id = '$parent_id' and report_id = '$proxy_id' limit 1 ");
		if(mysql_num_rows($check_custom_vote) > 0){
			$row_check_custom = mysql_fetch_array($check_custom_vote);
			if($row_check_custom["check_id"] == 0){
				$sql_vote_string = "SELECT * from voting where report_id='$proxy_id' order by resolution_number asc";
			} else {
				$sql_vote_string = "SELECT voting.id, voting.resolution_number, voting.resolution_name, customized_votes.ses_reco, customized_votes.detail, voting.man_reco, voting.man_share_reco from voting left join customized_votes on voting.id = customized_votes.vote_id where voting.report_id='$proxy_id' order by voting.resolution_number asc";
			}
		} else {
			$sql_vote_string = "SELECT * from voting where report_id='$proxy_id' order by resolution_number asc";
		}
		$sql_vote = mysql_query($sql_vote_string);
		     $count =1;
		     while($row_vote = mysql_fetch_array($sql_vote)) {
		      $str .= '<tr id="tr_vote_'.$row_vote["id"].'">';
		       $str .= '<td><input type="hidden" name="vote_id[]" value="'.$row_vote["id"].'" >'.stripcslashes($row_vote["resolution_number"]).'</td>';
		         $str .= '<td>'.stripcslashes($row_vote["resolution_name"]).'</td>';
		         
		         $str .= '<td>'.$man_recos[$row_vote["man_reco"]].'</td>';
		         $str .= '<td>'.$man_share_recos[$row_vote["man_share_reco"]].'</td><td>';
		         
		         if($flag_auth && $user_pa->can_change && $user_pa->freeze == 0)
		         	$str .= '<a href="javascript:;" id="ses_reco_'.$count.'" class="btn ttip span12" rel="tooltip" title="'.$row_vote["detail"].'">'.$recos[$row_vote["ses_reco"]].'</a></td><td><a href="javascript:;" class="btn icn-only yellow ttip span12" rel="tooltip" onclick="copy_comments('.$count.')" title="Click to copy SES comment"><i class="icon-chevron-right"></i><span style="display:none" id="ses_comment_'.$count.'">'.$row_vote['detail'].'</span></a></td>';
		         else
		         	$str .= $recos[$row_vote["ses_reco"]].'</td>';

		         $str .= '</td><td>';

		         $sql ="SELECT vote, comment from $voting_table where vote_id = '$row_vote[id]' and proxy_id = '$proxy_id' and user_id='$_SESSION[MEM_ID]' ";
		         $sql_vote_pre = mysql_query($sql);
		         $count_n = mysql_num_rows($sql_vote_pre);
		         if($count_n > 0) $pre = mysql_fetch_array($sql_vote_pre);

		         if($user_pa->can_change && $user_pa->freeze == 0) {
			         $str .= '<select name="vote[]" class="small m-wrap vote" id="vote_'.$count.'" ><option value="0">Select</option> ';
			         $sql_reso = mysql_query("Select * from votes");
			         while ($row_reso = mysql_fetch_array($sql_reso)) {
			            $str .= '<option value="'.$row_reso["id"].'" ';
			            if($count_n > 0){
			                if($row_reso["id"] == $pre["vote"]) $str .= 'selected';
			            }
			            $str .= '>'.$row_reso["vote"].'</option>';
			         }
			         $str .= '</select>';
		     	}
		     	else {
		     		
		     		if($count_n > 0){
		     			$sql_reso = mysql_query("Select * from votes where id= '$pre[vote]' ");
				        $row_reso = mysql_fetch_array($sql_reso);

				        if($pre["vote"] == 1){
							$value_put = $row_reso["vote"];

						} else if($pre["vote"] == 2){
							$value_put = '<button class="btn disabled red">'.$row_reso["vote"].'</button>';

						} else if($pre["vote"] == 3){
							$value_put = '<button class="btn disabled blue">'.$row_reso["vote"].'</button>';
						} else {
							$value_put = '';
						}				
			     		$str .= $value_put;
		     		}
		     	}
		         $str .= '</td><td>';

		         if($user_pa->can_change && $user_pa->freeze == 0){
		         	 $str .= '<textarea name="comment[]" class="comment" id="comment_'.$count.'">';
		          	if($count_n > 0){
		                $str .= stripcslashes($pre["comment"]);
		            }
		        	 $str .= '</textarea>';
		         } else {
		         	 if($count_n > 0){
		                $str .= stripcslashes($pre["comment"]);
		            }
		         }
		
		         $str .= '</td></tr>';
		         $count++;

		     }
		echo $str.'</table>';
	}
		$sqlm= "SELECT id,share from $detail_table where user_id='$_SESSION[MEM_ID]' and report_id='$proxy_id' and share !='' limit 1";
		
		$check_share = mysql_query($sqlm);
		$row_share = mysql_fetch_array($check_share);
		
				
		echo '<div class="row-fluid" style="padding-top:10px;" >
				<div class="span6" style="padding-top:2px;" id="share_box">';
			if($user_pa->ignore_an == 0 || $type == 1){
				echo '<input type="text" name="shares"  class="span12 ttip" value="'.$user_pa->share.'" placeholder="No. of/Comment on Shares" title="No. of/Comment on Shares" ';
				echo (!$user_pa->can_change || $user_pa->freeze == 1)?'disabled':'';
				echo '>';
			} else {
				echo '<a class="btn span12 disabled" href="javascript:;" style="font-size:11px">PM Votes have been included for this meeting</a>';
			}
				echo '</div>


		<div class="span6">';
		if($user_pa->can_change){
			if($user_pa->freeze != 0){
				if($type == 1)
				echo '<a href="javascript:;" class="btn yellow span4" id="unfreeze_button" onclick="unfreeze_vote('.$proxy_id.','.$type.')">Un-Freeze My Votes</a>';
			} else {
				if($type == 1){
					echo '<a href="javascript:;" class="btn blue span4" id="voting_button" onclick="voting_page('.$type.','.$proxy_id.',0)">Save My Votes</a>';
				} else {
					if($user_pa->ignore_an == 0){
						echo '<a href="javascript:;" class="btn blue span4" id="voting_button" onclick="voting_page('.$type.','.$proxy_id.',0)">Save Final Votes</a>';
					}
				}

				if($type == 1)
					echo '<a href="javascript:;" class="btn green span4" id="freeze_button" onclick="voting_page('.$type.','.$proxy_id.',1)">Freeze My Votes</a>';
			}
		} else {
			
				echo '<a class="btn span4 disabled" href="javascript:;" style="font-size:11px">Votes have been freezed by Admin or deadline is breached</i></a>';
			
		}

		if($_SESSION["MEM_ID"] == $parent_id && $type == 2){
			if($user_pa->marked == 0 || $user_pa->marked < strtotime("today")){
				?>
					<a href="javascript:;" class="btn span4 yellow" id="set_mark" onclick="set_mark(<?php echo $proxy_id; ?>)">Mark for Voting Committee Approval</a>
				<?php
			} else {
				?>
					<a href="javascript:;" class="btn span4 yellow" id="set_unmark" onclick="set_unmark(<?php echo $proxy_id; ?>)">Unmark for Voting Committee Approval</a>
				<?php
			}
		}

		if($_SESSION["MEM_ID"] == $parent_id && $type == 2){
			if($user_pa->final_freeze == 0){
				?>
					<a href="javascript:;" class="btn span4 yellow" id="freeze_all" onclick="voting_page(<?php echo $type; ?>,<?php echo $proxy_id; ?>,1)">Freeze All Votes</a>
				<?php
			} else if($user_pa->final_freeze == 1){
				?>
					<a href="javascript:;" class="btn span4 yellow" id="unfreeze_all" onclick="set_unfreeze(<?php echo $proxy_id; ?>,<?php echo $type; ?>)">UnFreeze All Votes</a>
				<?php
			}
		}



		echo '</div>

		</div>';


		
		
	} else {
		echo '<div class="alert alert-info" >Voting Recommendations will be updated soon by SES admin&nbsp;&nbsp;| &nbsp;&nbsp;';
		$check = mysql_query("SELECT id from vote_request where report_id='".$proxy_id."' and user_id='$_SESSION[MEM_ID]' and solved = 0 ");
		if(mysql_num_rows($check) > 0){
			echo 'Votes Requested';
		} else {
			echo '<a id="req_vote_'.$proxy_id.'" href="javascript:;" class="btn blue" style="max-width:100px" onclick="request_vote('.$proxy_id.')" >Request</a>';
		}

		

		echo '</div>';
	}
		

		echo '<div id="alertSpan" style="margin-top:10px;"></div>';

		echo  '</form>';
		if($_SESSION["MEM_ID"] == $parent_id && $type == 2){
			echo ' <hr>
			<a href="javascript:;" onclick="load_user_votes('.$proxy_id.','.$parent_id.')" id="vote_loader" class="btn yellow">View User Votes</a>
			<div id="user_votes" style="margin-top:20px;">
			</div>
			';
		}
	}

	public function user_votes($report_id,$parent_id){

		if($parent_id == $_SESSION["MEM_ID"] || $_SESSION["PRIV"] == 1){ //display voting by others
			$sql = "SELECT * from voting where report_id='$report_id' order by resolution_number asc";
			$sql_vote = mysql_query($sql);
			$user_array = $this->get_portfolio_users($parent_id,$report_id);
			//array_push($user_array, $parent_id);
			$resolution_ids = array();
			$types = array("Vote","Comment");
			$types_field = array("vote","comment");
			$votes = array();
			$sql_vote_fetch = mysql_query("SELECT * from votes ");
			while ($row_vote_fetch = mysql_fetch_array($sql_vote_fetch)) {
				$votes[$row_vote_fetch["id"]] = $row_vote_fetch["vote"];
			}
			?>
			<div style="clear:both"></div>
			<div id="vote_others" style="overflow-x:auto">
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th colspan="3">Voting Details of Portfolio Managers <br><br> Resolutions</th>
							<?php 
								// while ($row_res = mysql_fetch_array($sql_vote)) {
									?>
									<!-- <th><?php echo $row_res["resolution_name"] ?></th> -->
									<?php
								// 	array_push($resolution_ids, $row_res["id"]);
								// }

								foreach($user_array as $usr_id) {
									$name_sql = mysql_query("SELECT name, user_admin_name from users where id='$usr_id' ");
									$row_name = mysql_fetch_array($name_sql);
									$count_type = 0;

									$sqlm="SELECT id,share from user_proxy_ad where user_id='$usr_id' and report_id='$report_id' and share !='' limit 1";
									$check_share = mysql_query($sqlm);
									$row_share = mysql_fetch_array($check_share);
									echo '<th>';
									echo ($usr_id == $parent_id)?$row_name["user_admin_name"]:$row_name["name"];
									echo '<br><span style="font-weight:normal">Shares: '.$row_share["share"].'</span>';

									$flag_freeze = 0;
									$query1 = "SELECT freeze_on, unfreeze_on from user_proxy_ad where user_id='$usr_id' and report_id='$report_id' and freeze_on != 0 order by id desc limit 1";
									$check_freeze = mysql_query($query1);
									if(mysql_num_rows($check_freeze) > 0){
										$row_freeze = mysql_fetch_array($check_freeze);
										if($row_freeze["freeze_on"] != 0 && $row_freeze["unfreeze_on"] == 0) $flag_freeze = 1;
									}
									echo '<br>';
									echo ($flag_freeze == 1)?'<button class="btn disabled green">Freezed</button>':'<button class="btn disabled yellow">Draft Mode</button>';
									echo '</th>';
								}
							?>
						</tr>
					</thead>
					<tbody>
						<?php

							while($row_reso = mysql_fetch_array($sql_vote)){
								echo '<tr>';
								echo '<td rowspan="2">'.$row_reso["resolution_number"].'</td><td rowspan="2">'.$row_reso["resolution_name"].'</td>';
								$count_type = 0;
								foreach ($types as $type) {
									echo '<td>'.$type.'</td>';
									foreach ($user_array as $usr_id) {
										$field = $types_field[$count_type];
										$sql = "SELECT $field from user_voting where user_id='$usr_id' and vote_id='$row_reso[id]' and proxy_id='$report_id' ";
										$query_res = mysql_query($sql);
										$row_res = mysql_fetch_array($query_res);
										switch ($count_type) {
											case 0:
												if($row_res["vote"] == 1){
													$value_put = $votes[$row_res["vote"]];

												} else if($row_res["vote"] == 2){
													$value_put = '<button class="btn disabled red">'.$votes[$row_res["vote"]].'</button>';

												} else if($row_res["vote"] == 3){
													$value_put = '<button class="btn disabled blue">'.$votes[$row_res["vote"]].'</button>';
												} else {
													$value_put = '';
												}
												$value_put .= '<button class="mini btn pull-right cp_btn_'.$row_reso["id"].'" style="font-size:10px; padding:0 10px; margin:5px 0" id="cp_btn_'.$usr_id.'_'.$row_reso["id"].'" onclick="copy_voting('.$usr_id.','.$row_reso["id"].')">Copy</button>';
												break;
											
											case 1:
												$value_put = stripcslashes($row_res["comment"]);
												break;
										}
										echo '<td>'.$value_put.'</td>';
									}
									$count_type++;
									if($count_type == sizeof($types)){
										echo '</tr>';
									} else {
										echo '</tr><tr>';
									}
								}
							}
						?>
					</tbody>
				</table>
			</div>

			<?php
		} else {
			echo 'Not Authorized';
		}
	}

	public function user_votes_final($report_id,$parent_id,$type){

		if($parent_id == $_SESSION["MEM_ID"] || $_SESSION["PRIV"] == 1){ 

			$sql = "SELECT * from voting where report_id='$report_id' order by resolution_number asc";
			$sql_vote = mysql_query($sql);

			$sql_an = mysql_query("SELECT ignore_an from user_admin_proxy_ad where user_id='$parent_id' and report_id='$report_id' ");
			if(mysql_num_rows($sql_an) > 0){
				$row_an = mysql_fetch_array($sql_an);
				$flag_an = ($row_an["ignore_an"] == 1)?'1':'0';
			} else $flag_an = 0;

			if($flag_an == 1){
				$initial_user_array = $this->get_portfolio_users($parent_id,$report_id);
				$voting_table = 'user_voting';
				$detail_table = 'user_proxy_ad';
				//array_push($initial_user_array, $parent_id);
			} else {
				$initial_user_array = array();
				$voting_table = 'user_admin_voting';
				$detail_table = 'user_admin_proxy_ad';
				array_push($initial_user_array, $parent_id);
			}

			$resolution_ids = array();
			$types = array("Vote","Comment");
			$types_field = array("vote","comment");
			$votes = array();
			$sql_vote_fetch = mysql_query("SELECT * from votes ");
			while ($row_vote_fetch = mysql_fetch_array($sql_vote_fetch)) {
				$votes[$row_vote_fetch["id"]] = $row_vote_fetch["vote"];
			}
			?>
			<div id="vote_others" style="overflow-x:auto">
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th colspan="3">Voting Details <br><br> Resolutions</th>
							<?php
								$user_array = array();

								if($flag_an == 1){
									foreach ($initial_user_array as $usr_id) {
										$query1 = "SELECT freeze_on, unfreeze_on from user_proxy_ad where user_id='$usr_id' and report_id='$report_id' and freeze_on != 0 order by id desc limit 1";
										$check_freeze = mysql_query($query1);
										if(mysql_num_rows($check_freeze) > 0){
											$row_freeze = mysql_fetch_array($check_freeze);
											if($row_freeze["freeze_on"] != 0 && $row_freeze["unfreeze_on"] == 0){
												array_push($user_array, $usr_id);
											}
										}
									}
								} else {
									$user_array = $initial_user_array;
								}

								foreach($user_array as $usr_id) {
									$name_sql = mysql_query("SELECT name, user_admin_name from users where id='$usr_id' ");
									$row_name = mysql_fetch_array($name_sql);
									$count_type = 0;

									$sqlm="SELECT id,share from $detail_table where user_id='$usr_id' and report_id='$report_id' and share !='' limit 1";
									$check_share = mysql_query($sqlm);
									$row_share = mysql_fetch_array($check_share);
									echo '<th>';
									if($flag_an == 0){
										echo $row_name["name"];
									} else {
										if($usr_id == $parent_id) echo $row_name["user_admin_name"];
										else echo $row_name["name"];
									}
									echo '<br><span style="font-weight:normal">Shares: '.$row_share["share"].'</span></th>';
								}	
							?>
						</tr>
					</thead>
					<tbody>
						<?php

							while($row_reso = mysql_fetch_array($sql_vote)){
								echo '<tr>';
								echo '<td rowspan="2">'.$row_reso["resolution_number"].'</td><td rowspan="2">'.$row_reso["resolution_name"].'</td>';
								$count_type = 0;
								foreach ($types as $type) {
									echo '<td>'.$type.'</td>';
									foreach ($user_array as $usr_id) {
										$field = $types_field[$count_type];
										$sql = "SELECT $field from $voting_table where user_id='$usr_id' and vote_id='$row_reso[id]' and proxy_id='$report_id' ";
										$query_res = mysql_query($sql);
										$row_res = mysql_fetch_array($query_res);
										switch ($count_type) {
											case 0:
												$value_put = $votes[$row_res["vote"]];
												break;
											
											case 1:
												$value_put = stripcslashes($row_res["comment"]);
												break;
										}
										echo '<td>'.$value_put.'</td>';
									}
									$count_type++;
									if($count_type == sizeof($types)){
										echo '</tr>';
									} else {
										echo '</tr><tr>';
									}
								}
							}
						?>
					</tbody>
				</table>
			</div>

			<?php
		} else {
			echo 'Not Authorized';
		}
	}

	public function addUpdateVote($argv) {
		$proxy_id =  $argv[0];
		$vote_id =  $argv[1];
		$vote = $argv[2];
		$comments = $argv[3];
		$share = $argv[4];
		$report_id = $proxy_id;
		$voting_type = $argv[5];

		if($voting_type == 1){
			$voting_table = 'user_voting';
			$detail_table = 'user_proxy_ad';
		} else if($voting_type == 2){
			$voting_table = 'user_admin_voting';
			$detail_table = 'user_admin_proxy_ad';
		}

		
		$query = mysql_query("SELECT created_by_prim from users where id='$_SESSION[MEM_ID]' ");
		$row = mysql_fetch_array($query);
		if($row["created_by_prim"] == 0) $parent_id = $_SESSION["MEM_ID"];
		else $parent_id = $row["created_by_prim"];

		$user_pa_ck = new user_proxy_ad($report_id, $parent_id);
		if(!$user_pa_ck->can_change) die("fail");

		$count = 0;
		$timenow = strtotime("now");
		if($proxy_id != 0){

		if($share != ''){
			$check_share = mysql_query("SELECT id,share from $detail_table where user_id='$_SESSION[MEM_ID]' and report_id='$proxy_id' ");
			if(mysql_num_rows($check_share) > 0){
				$row_share = mysql_fetch_array($check_share);
				mysql_query("UPDATE $detail_table set share='$share', add_date ='$timenow' where user_id='$_SESSION[MEM_ID]' and report_id='$proxy_id' and id='$row_share[id]' ");
				mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type, details) values ('$_SESSION[MEM_ID]','24','$report_id','1','$row_share[share]')");
			} else {
				mysql_query("INSERT into $detail_table (share, add_date, user_id, report_id) values ('$share','$timenow','$_SESSION[MEM_ID]','$proxy_id') ");
				mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type, details) values ('$_SESSION[MEM_ID]','24','$report_id','1','')");
			}
		}
		$details = '';
		foreach ($vote_id as $id) {
			$insert_check = mysql_query("SELECT * from $voting_table where proxy_id= '$proxy_id' and user_id = '$_SESSION[MEM_ID]' and vote_id='$id' ");
		    if($vote[$count] != 0){
		        if(mysql_num_rows($insert_check) > 0){

		        	$res = mysql_fetch_array($insert_check);
		        	$id_change = $res["id"];
		        	if($res["vote"] != $vote[$count] || $res["comment"] != addslashes($comments[$count])) {

		        		mysql_query("UPDATE $voting_table set vote='$vote[$count]', comment = '".addslashes($comments[$count])."' , modified = '$timenow' where id = '$id_change' ");
		        	}
		    	 	$details .= $res["vote_id"].'||'.$res["vote"].'||'.$res["comment"].'/';

		        } else {
		        	mysql_query("INSERT into $voting_table (user_id, vote_id, proxy_id, vote, comment, add_date) values ('$_SESSION[MEM_ID]','$id','$proxy_id','$vote[$count]','".addslashes($comments[$count])."','$timenow') ");
		        	
		        }

		    } else {
		    	 if(mysql_num_rows($insert_check) > 0){
		    	 	$res = mysql_fetch_array($insert_check);
		        	$id_change = $res["id"];
		    	 	$details .= $res["vote_id"].'||'.$res["vote"].'||'.$res["comment"].'/';
		        	mysql_query("DELETE from $voting_table where id='$id_change' ");
		    	 }
		    }
		    $count++;
		}
		mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type,voting_type, details) values ('$_SESSION[MEM_ID]','13','$report_id','1','$voting_type','$details')");
		}

	}

	private function get_voting_users($parent_id,$report_id){
		 $users = array();
		$query_sql_all = mysql_query("SELECT id from users where created_by_prim='$parent_id' and voting_access='0' ");
		while ($row_u = mysql_fetch_array($query_sql_all)) {
			array_push($users, $row_u["id"]);
		}

		$com_id_sql = mysql_query("SELECT com_id from proxy_ad where id='$report_id' ");
		$com_id_row  = mysql_fetch_array($com_id_sql);
		$com_id = $com_id_row["com_id"];

		$query_sql_rest = mysql_query("SELECT user_id from voting_access where added_by='$parent_id' and com_id='$com_id' ");
		while ($row_u = mysql_fetch_array($query_sql_rest)) {
			if(!in_array($row_u["user_id"], $users)) array_push($users, $row_u["user_id"]);
		}
		return $users;
	}

	private function get_portfolio_users($parent_id,$report_id){
		$users = array();
		$query_sql_all = mysql_query("SELECT user_voting_proxy_reports.user_id from user_voting_proxy_reports inner join users on user_voting_proxy_reports.user_id= users.id where user_voting_proxy_reports.report_id='$report_id' and ( users.created_by_prim='$parent_id' OR users.id='$parent_id' ) ");
		while ($row_u = mysql_fetch_array($query_sql_all)) {
			array_push($users, $row_u["user_id"]);
		}
		return $users;
	}
}



class user_proxy_ad {

	public $share;
	public $freeze;
	public $final_freeze;
	public $can_change;
	public $deadline;
	public $deadline_check;
	public $check_form;
	public $ignore_an;

	public function __construct($report_id, $user_id,$type) { // user_id is parent id here

		$this->freeze = 0;
		$this->ignore_an = 0;

		if($type == 1){
			$check_freeze = mysql_query("SELECT freeze_on, unfreeze_on from user_proxy_ad where user_id='$_SESSION[MEM_ID]' and report_id='$report_id' and freeze_on != 0 order by id desc limit 1");
			if(mysql_num_rows($check_freeze) > 0){
				$row_freeze = mysql_fetch_array($check_freeze);
				if($row_freeze["freeze_on"] != 0 && $row_freeze["unfreeze_on"] == 0) $this->freeze = 1;		
			}
		}

		$check_ignore = mysql_query("SELECT ignore_an, com_approval from user_admin_proxy_ad where user_id='$user_id' and report_id='$report_id' ");
		$this->marked = 0;

		if(mysql_num_rows($check_ignore)>0){
			$row_ignore = mysql_fetch_array($check_ignore);
			if($row_ignore["ignore_an"] == 1) $this->ignore_an = $row_ignore["ignore_an"];
			if($row_ignore["com_approval"] != 0 ) $this->marked = $row_ignore["com_approval"];
		}

		$check_final_freeze = mysql_query("SELECT final_freeze, final_unfreeze from user_admin_proxy_ad where user_id='$user_id' and report_id='$report_id' and  final_freeze != 0 order by id desc limit 1");
		$this->final_freeze = 0;
		if(mysql_num_rows($check_final_freeze) > 0){
			$row_freeze = mysql_fetch_array($check_final_freeze);
			if($row_freeze["final_freeze"] != 0 && $row_freeze["final_unfreeze"] == 0) $this->final_freeze = 1;
		}
		
		$query_deadline = mysql_query("SELECT deadline from user_admin_proxy_ad where user_id='$user_id' and report_id='$report_id' and deadline!='' order by add_date desc limit 1 ");
			if(mysql_num_rows($query_deadline) > 0){
				$row_deadline = mysql_fetch_array($query_deadline);
				$this->deadline = date("d-m-Y", $row_deadline["deadline"]);
				if(strtotime("now") > ($row_deadline["deadline"] + 86400)){
					$this->deadline_check = 1;
				} else {
					$this->deadline_check = 0;
				}
			} else {

				//check user setting
				$query_u = mysql_query("SELECT def_deadline_vote from users where id='$user_id' limit 1");
				$row_u = mysql_fetch_array($query_u);

				if($row_u["def_deadline_vote"] == 0){
					$days_deadline = 7;
				} else {
					$days_deadline = $row_u["def_deadline_vote"];
				}
				
				//meeting_date
				$query_rep = mysql_query("SELECT meeting_date from proxy_ad where id='$report_id' limit 1 ");
				$row_rep = mysql_fetch_array($query_rep);
				$deadline_in = $row_rep["meeting_date"] - $days_deadline*86400;
				//check_entry
				$query_en = mysql_query("SELECT id from user_admin_proxy_ad where user_id='$user_id' and report_id='$report_id' ");
				if(mysql_num_rows($query_en) > 0){
					$row_en = mysql_fetch_array($query_en);
					mysql_query("UPDATE user_admin_proxy_ad set deadline = '$deadline_in' where id='$row_en[id]' ");

					$d_query = mysql_query("SELECT deadline from user_admin_proxy_ad where id='$row_en[id]' limit 1");
					$final_deadline = mysql_fetch_array($d_query);

				} else {
					mysql_query("INSERT into user_admin_proxy_ad (user_id, report_id, deadline, add_date) values ('$user_id','$report_id','$deadline_in','".strtotime("now")."') ");
					$d_query = mysql_query("SELECT deadline from user_admin_proxy_ad where id='".mysql_insert_id()."' limit 1");
					$final_deadline = mysql_fetch_array($d_query);
				}


				$this->deadline = date("d-m-Y", $final_deadline["deadline"]);;
				
				if(strtotime("now") > ($final_deadline["deadline"] + 86400)){
					$this->deadline_check = 1;
				} else {
					$this->deadline_check = 0;
				}
			}

			
			$table = ($type == 1)?'user_proxy_ad':'user_admin_proxy_ad';
		$check_share = mysql_query("SELECT id,share from $table where user_id='$_SESSION[MEM_ID]' and report_id='$report_id' and share !='' limit 1");
		if(mysql_num_rows($check_share) > 0){
			$row_share = mysql_fetch_array($check_share);
			$this->share = stripcslashes($row_share["share"]);
		} else $this->share = '';

		$this->check_upload_form($report_id,$user_id);
		$this->change_check();
	}

	private function check_upload_form($report_id, $user_id){

		$sql_check_form = mysql_query("SELECT form from proxies where proxy_id='$report_id' and user_id='$user_id' ");
		if(mysql_num_rows($sql_check_form) > 0){
			$row_check_form  = mysql_fetch_array($sql_check_form);
			if($row_check_form["form"] == '') $this->check_form = 0;
			else $this->check_form =1;
		} else {
			$sql_check_form = mysql_query("SELECT form from self_proxies where proxy_id='$report_id' and user_id='$user_id' ");
			if(mysql_num_rows($sql_check_form) > 0){
				$row_check_form  = mysql_fetch_array($sql_check_form);
				if($row_check_form["form"] == '') $this->check_form = 0;
				else $this->check_form =1;
			} else {
				$this->check_form = 0;
			}
		}

		

	}

	private function change_check(){
		if($this->final_freeze == 0 && $this->deadline_check == 0){
			$this->can_change = true;
		} else {
			$this->can_change = false;
		}
	}

}