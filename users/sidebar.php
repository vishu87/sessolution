<!-- BEGIN SIDEBAR -->
		<div class="page-sidebar nav-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->        	
			<ul>
				<li>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler hidden-phone" style="margin-bottom: 15px;"></div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				</li>
				<li>
					<br>
				</li>
				<li class="<?php echo ($sidebar=='upcoming')?'active':'';?>">
					<a href="upcoming.php">
					<i class="icon-cogs"></i>
					<span class="title">Calender View</span>
					<span class="selected"></span>
					</a>
				</li>
				<li class="<?php echo ($sidebar=='voting_records')?'active open':'';?> has-sub">
					<a href="javascript:;">
					<i class="icon-legal"></i> 
					<span class="title">Portfolio Management</span>
					<span class="selected"></span>
					<span class="arrow open"></span>
					</a>
					<ul class="sub">
						<li <?php echo ($sub_sidebar=='1' && $sidebar=='voting_records')?'class="active"':'';?>><a href="voting_records.php?cat=1">Create Portfolio</a></li>
						<?php if($_SESSION["self_portfolio"] == 1){ ?>
							<li <?php echo ($sub_sidebar=='2' && $sidebar=='voting_records')?'class="active"':'';?>><a href="voting_records.php?cat=2">Upcoming Meetings</a></li>
							<?php if($_SESSION["alerts"] == 0){ ?>
							<li <?php echo ($sub_sidebar=='3' && $sidebar=='voting_records')?'class="active"':'';?>><a href="voting_records.php?cat=3">Set Email Alerts</a></li>
							<?php } ?>
							<li <?php echo ($sub_sidebar=='5' && $sidebar=='voting_records')?'class="active"':'';?>><a href="voting_records.php?cat=5">View Past Votings</a></li>
						<?php } ?>
					</ul>
				</li>


				<li class="<?php echo ($sidebar=='firm_voting_records' || $sidebar == 'proxy_voters')?'active open':'';?> has-sub">
					<a href="javascript:;">
					<i class="icon-legal"></i> 
					<span class="title">Vote Management</span>
					<span class="selected"></span>
					<span class="arrow open"></span>
					</a>
					<ul class="sub">
						<li <?php echo ($sub_sidebar=='1' && $sidebar=='firm_voting_records')?'class="active"':'';?>><a href="firm_voting_records.php?cat=1">Execute Vote</a></li>
						<li <?php echo ($sidebar=='proxy_voters')?'class="active"':'';?>><a href="proxy_voters.php">Add Proxy Voters</a></li>
						<?php if($_SESSION["alerts"] == 1){ ?>
							<li <?php echo ($sub_sidebar=='4' && $sidebar=='firm_voting_records')?'class="active"':'';?>><a href="firm_voting_records.php?cat=4">Set Email Alerts</a></li>
						<?php } ?>
						<li <?php echo ($sub_sidebar=='2' && $sidebar=='firm_voting_records')?'class="active"':'';?>><a href="firm_voting_records.php?cat=2">Edit Voting Records</a></li>
						<li <?php echo ($sub_sidebar=='3' && $sidebar=='firm_voting_records')?'class="active"':'';?>><a href="firm_voting_records.php?cat=3">MIS Report</a></li>
					</ul>
				</li>

				<li class="<?php echo ($sidebar=='reports')?'active open':'';?> has-sub">
					<a href="javascript:;">
					<i class="icon-adjust"></i> 
					<span class="title">SES Coverage</span>
					<span class="selected"></span>
					<span class="arrow open"></span>
					</a>
						<ul class="sub">
						<li <?php echo ($sub_sidebar=='1' && $sidebar=='reports')?'class="active"':'';?>><a href="reports.php?cat=1">Proxy Advisory</a></li>
						<li <?php echo ($sub_sidebar=='2' && $sidebar=='reports')?'class="active"':'';?>><a href="reports.php?cat=2">Governance Scores</a></li>
						<li <?php echo ($sub_sidebar=='3' && $sidebar=='reports')?'class="active"':'';?>><a href="reports.php?cat=3">Governance Research</a></li>
					</ul>
				</li>
								
				<li class="<?php echo ($sidebar=='my_profile' || $sidebar=='sub_users' || $sidebar=='ch_password' || $sidebar == 'schemes' || $sidebar == 'evoting_info')?'active open':'';?> has-sub">
					<a href="javascript:;">
					<i class="icon-user"></i> 
					<span class="title">Account Management</span>
					<span class="selected"></span>
					<span class="arrow open"></span>
					</a>
					<ul class="sub">
						<li <?php echo ($sub_sidebar=='1' && $sidebar=='my_profile')?'class="active"':'';?>><a href="my_profile.php?cat=1">Profile</a></li>
						<li <?php echo ($sub_sidebar=='2' && $sidebar=='my_profile')?'class="active"':'';?>><a href="my_profile.php?cat=2">Subscriptions</a></li>
						<li <?php echo ($sidebar =='schemes')?'class="active"':'';?>><a href="schemes.php">Scheme Management</a></li>
						<li <?php echo ($sidebar =='evoting_info')?'class="active"':'';?>><a href="evoting_info.php">eVoting Information</a></li>
						<li <?php echo ($sidebar =='sub_users')?'class="active"':'';?>><a href="sub_users.php">User Management</a></li>
						<li <?php echo ($sidebar=='ch_password')?'class="active"':'';?>><a href="ch_password.php">Change Password</a></li>
					</ul>
				</li>

				<?php if($sidebar=='payment'): ?>
				<li class="<?php echo ($sidebar=='payment')?'active':'';?>">
					<a href="javascript:;">
					<i class="icon-money"></i> 
					<span class="title">Subscription</span>
					<span class="selected"></span>
					</a>
				</li>
				<?php endif; ?>
				
				<li class="<?php echo ($sidebar=='contact_us')?'active':'';?>">
					<a href="contact_us.php">
					<i class="icon-cloud"></i> 
					<span class="title">Contact Us</span>
					<span class="selected"></span>
					</a>
				</li>


				<li class="">
					<a href="<?php echo STRSITE ?>logout.php">
					<i class="icon-key"></i> 
					<span class="title">Logout</span>
					<span class="selected"></span>
					</a>
				</li>

			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
		<!-- END SIDEBAR -->