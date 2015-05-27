<!-- BEGIN SIDEBAR -->
		<div class="page-sidebar nav-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->        	
			<ul>
				<li>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler hidden-phone"></div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				</li>
				<li>
					<br>
				</li>
				<!--
				<li>
					
					<form class="sidebar-search" action="verify.php" method="post" id="student_side_search" >
						<div class="input-box">
							<input type="text" placeholder="Search Students.."  name="student_name" autocomplete="off" class="typehead" required />				
							<input type="submit" class="submit" value=" " />
						</div>
					</form>
				
				</li> 
				<li>
					
					<form class="sidebar-search" action="verify.php" method="post" id="student_side_search" >
						<div class="input-box">
							<input type="text" placeholder="Search Companies.."  name="company_name" autocomplete="off" class="typehead_comp" required />				
							<input type="submit" class="submit" value="" />
						</div>
					</form>
				
				</li> 
				-->
				<li class="<?php echo ($sidebar=='dashboard')?'active':'';?>">
					<a href="index.php">
					<i class="icon-home"></i> 
					<span class="title">Dashboard</span>
					<span class="selected"></span>
					</a>
				</li>
				
			

				<li class="<?php echo ($sidebar=='companies')?'active':'';?> has-sub">
					<a href="javascript:;">
					<i class="icon-adjust"></i> 
					<span class="title">Companies</span>
					<span class="selected"></span>
					<span class="arrow open"></span>
					</a>
						<ul class="sub">
						<li class="<?php echo ($sidebar=='companies' && $sub_sidebar == 1)?'active':'';?>">
							<a href="companies.php">Upload Company List</a>
						</li>
						<li class="<?php echo ($sidebar=='companies' && $sub_sidebar == 2)?'active':'';?>">
							<a href="companies.php?&amp;cat=2">Add Company</a>
						</li>
						<li class="<?php echo ($sidebar=='companies' && $sub_sidebar == 3)?'active':'';?>">
							<a href="companies.php?&amp;cat=3">Search &amp; Edit</a>
						</li>
					</ul>
				</li>

				<li class="<?php echo ($sidebar=='package')?'active':'';?> has-sub">
					<a href="javascript:;">
					<i class="icon-book"></i> 
					<span class="title">Package</span>
					<span class="selected"></span>
					<span class="arrow open"></span>
					</a>
						<ul class="sub">
						<li class="<?php echo ($sidebar=='package' && $sub_sidebar == 1)?'active':'';?>">
							<a href="package.php">Add Package</a>
						</li>
						<li class="<?php echo ($sidebar=='package' && $sub_sidebar == 2)?'active':'';?>">
							<a href="package.php?&amp;cat=2">All Packages</a>
						</li>
					</ul>
				</li>

				
				<li class="<?php echo ($sidebar=='users')?'active':'';?> has-sub">
					<a href="javascript:;">
					<i class="icon-user"></i> 
					<span class="title">Users</span>
					<span class="selected"></span>
					<span class="arrow open"></span>
					</a>
						<ul class="sub">
						<li class="<?php echo ($sidebar=='users' && $sub_sidebar == 1)?'active':'';?>">
							<a href="users.php">Add User</a>
						</li>
						<li class="<?php echo ($sidebar=='users' && $sub_sidebar == 2)?'active':'';?>">
							<a href="users.php?cat=2">All Users</a>
						</li>
					</ul>
				</li>
				<li class="<?php echo ($sidebar=='proxy_ad')?'active':'';?> has-sub">
					<a href="javascript:;">
					<i class="icon-cog"></i> 
					<span class="title">Proxy Advisory</span>
					<span class="selected"></span>
					<span class="arrow open"></span>
					</a>
						<ul class="sub">
						<li class="<?php echo ($sidebar=='proxy_ad' && $sub_sidebar == 1)?'active':'';?>">
							<a href="proxy_ad.php">Upload Schedule</a>
						</li>
						<li class="<?php echo ($sidebar=='proxy_ad' && $sub_sidebar == 8)?'active':'';?>">
							<a href="proxy_ad.php?cat=8">Upload Resolutions</a>
						</li>
						<li class="<?php echo ($sidebar=='proxy_ad' && $sub_sidebar == 2)?'active':'';?>">
							<a href="proxy_ad.php?cat=2">Upcoming</a>
						</li>
						<li class="<?php echo ($sidebar=='proxy_ad' && $sub_sidebar == 4)?'active':'';?>">
							<a href="proxy_ad.php?cat=4">Archived</a>
						</li>
						<li class="<?php echo ($sidebar=='proxy_ad' && $sub_sidebar == 7)?'active':'';?>">
							<a href="proxy_ad.php?cat=7">Skipped</a>
						</li>
						<li class="<?php echo ($sidebar=='proxy_ad' && $sub_sidebar == 3)?'active':'';?>">
							<a href="proxy_ad.php?cat=3">Reports Coverage</a>
						</li>
						<li class="<?php echo ($sidebar=='proxy_ad' && $sub_sidebar == 6)?'active':'';?>">
							<a href="proxy_ad.php?cat=6">All Reports</a>
						</li>
						
					</ul>
				</li>

				<li class="<?php echo ($sidebar=='cgs_reports')?'active':'';?> has-sub">
					<a href="javascript:;">
					<i class="icon-certificate"></i> 
					<span class="title">Governance Scores</span>
					<span class="selected"></span>
					<span class="arrow open"></span>
					</a>
						<ul class="sub">
						<li class="<?php echo ($sidebar=='cgs_reports' && $sub_sidebar == 1)?'active':'';?>">
							<a href="cgs_reports.php">Add CGS Report</a>
						</li>
						<li class="<?php echo ($sidebar=='cgs_reports' && $sub_sidebar == 2)?'active':'';?>">
							<a href="cgs_reports.php?cat=2">All CGS</a>
						</li>
						<li class="<?php echo ($sidebar=='cgs_reports' && $sub_sidebar == 3)?'active':'';?>">
							<a href="cgs_reports.php?cat=3">CGS Coverage</a>
						</li>

					</ul>
				</li>
				<li class="<?php echo ($sidebar=='research')?'active':'';?> has-sub">
					<a href="javascript:;">
					<i class="icon-search"></i> 
					<span class="title">Research</span>
					<span class="selected"></span>
					<span class="arrow open"></span>
					</a>
						<ul class="sub">
						<li class="<?php echo ($sidebar=='research' && $sub_sidebar == 1)?'active':'';?>">
							<a href="research.php">Add Research</a>
						</li>
						<li class="<?php echo ($sidebar=='research' && $sub_sidebar == 2)?'active':'';?>">
							<a href="research.php?cat=2">All Research</a>
						</li>
						<li class="<?php echo ($sidebar=='research' && $sub_sidebar == 3)?'active':'';?>">
							<a href="research.php?cat=3">Research Coverage</a>
						</li>
						
					</ul>
				</li>
				

				<li class="<?php echo ($sidebar=='analyst')?'active':'';?> has-sub">
					<a href="javascript:;">
					<i class="icon-folder-close"></i> 
					<span class="title">Analyst</span>
					<span class="selected"></span>
					<span class="arrow open"></span>
					</a>
						<ul class="sub">
						<li class="<?php echo ($sidebar=='analyst' && $sub_sidebar == 1)?'active':'';?>">
							<a href="analyst.php?cat=1">Pending</a>
						</li>
						<li class="<?php echo ($sidebar=='analyst' && $sub_sidebar == 4)?'active':'';?>">
							<a href="analyst.php?cat=4">Completed</a>
						</li>
						<li class="<?php echo ($sidebar=='analyst' && $sub_sidebar == 2)?'active':'';?>">
							<a href="analyst.php?cat=2">Add/Edit Analyst</a>
						</li>
						
					</ul>
				</li>
				<li class="<?php echo ($sidebar=='proxy_voters')?'active':'';?> has-sub">
					<a href="javascript:;">
					<i class="icon-legal"></i> 
					<span class="title">Proxy Voting</span>
					<span class="selected"></span>
					<span class="arrow open"></span>
					</a>
						<ul class="sub">
						<li class="<?php echo ($sidebar=='proxy_voters' && $sub_sidebar == 1)?'active':'';?>">
							<a href="proxy_voters.php?cat=1">Pending</a>
						</li>
						<li class="<?php echo ($sidebar=='proxy_voters' && $sub_sidebar == 4)?'active':'';?>">
							<a href="proxy_voters.php?cat=4">Completed</a>
						</li>
						<li class="<?php echo ($sidebar=='proxy_voters' && $sub_sidebar == 2)?'active':'';?>">
							<a href="proxy_voters.php?cat=2">Add/Edit Voter</a>
						</li>
						
					</ul>
				</li>

				<li class="<?php echo ($sidebar=='price')?'active':'';?>">
					<a href="price.php">
					<i class="icon-bar-chart"></i> 
					<span class="title">Price</span>
					<span class="selected"></span>
					</a>
				</li>

				<li class="<?php echo ($sidebar=='subscription_req')?'active':'';?>">
					<a href="subscription_req.php">
					<i class="icon-bar-chart"></i> 
					<span class="title">Subscription Requests</span>
					<span class="selected"></span>
					</a>
				</li>
				<li class="<?php echo ($sidebar=='add_items')?'active':'';?> has-sub">
					<a href="javascript:;">
					<i class="icon-plus"></i> 
					<span class="title">Add Details</span>
					<span class="selected"></span>
					<span class="arrow open"></span>
					</a>
						<ul class="sub">
						<li class="<?php echo ($sidebar=='add_items' && $sub_sidebar == 1)?'active':'';?>">
							<a href="add_items.php?cat=1">Resolution Type</a>
						</li>
						<li class="<?php echo ($sidebar=='add_items' && $sub_sidebar == 2)?'active':'';?>">
							<a href="add_items.php?cat=2">SES Recommendations</a>
						</li>
						<li class="<?php echo ($sidebar=='add_items' && $sub_sidebar == 3)?'active':'';?>">
							<a href="add_items.php?cat=3">Reasons</a>
						</li>
						<li class="<?php echo ($sidebar=='add_items' && $sub_sidebar == 4)?'active':'';?>">
							<a href="add_items.php?cat=4">Locations</a>
						</li>
						<li class="<?php echo ($sidebar=='add_items' && $sub_sidebar == 5)?'active':'';?>">
							<a href="add_items.php?cat=5">Years</a>
						</li>
						
					</ul>
				</li>

				<li class="<?php echo ($sidebar=='ch_password')?'active':'';?>">
					<a href="ch_password.php">
					<i class="icon-edit"></i> 
					<span class="title">Change Password</span>
					<span class="selected"></span>
					</a>
				</li>
				
				
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
		<!-- END SIDEBAR -->