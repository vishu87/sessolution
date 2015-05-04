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
				<li class="<?php echo ($sidebar=='upcoming')?'active':'';?> start">
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
						<li <?php echo ($sub_sidebar=='2' && $sidebar=='voting_records')?'class="active"':'';?>><a href="voting_records.php?cat=2">Upcoming Meetings</a></li>
						<li <?php echo ($sub_sidebar=='3' && $sidebar=='voting_records')?'class="active"':'';?>><a href="voting_records.php?cat=3">Set Email Alerts</a></li>
						<li <?php echo ($sub_sidebar=='4' && $sidebar=='voting_records')?'class="active"':'';?>><a href="voting_records.php?cat=4">View Past Votings</a></li>
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

				

				

				
				<li class="<?php echo ($sidebar=='contact_us')?'active':'';?>">
					<a href="contact_us.php">
					<i class="icon-cloud"></i> 
					<span class="title">Contact Us</span>
					<span class="selected"></span>
					</a>
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


				<li class="<?php echo ($sidebar=='ch_password')?'active':'';?>">
					<a href="ch_password.php">
					<i class="icon-refresh"></i> 
					<span class="title">Change Password</span>
					<span class="selected"></span>
					</a>
				</li>

				<li class="">
					<a href="<?php echo STRSITE; ?>logout.php">
					<i class="icon-key"></i> 
					<span class="title">Logout</span>
					<span class="selected"></span>
					</a>
				</li>

			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
		<!-- END SIDEBAR -->