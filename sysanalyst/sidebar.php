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
				
				<li class="<?php echo ($sidebar=='dashboard')?'active':'';?>">
					<a href="index.php">
					<i class="icon-home"></i> 
					<span class="title">Dashboard</span>
					<span class="selected"></span>
					</a>
				</li>-->
				
			

				<li class="<?php echo ($sidebar=='task')?'active':'';?> has-sub">
					<a href="javascript:;">
					<i class="icon-adjust"></i> 
					<span class="title">Tasks</span>
					<span class="selected"></span>
					<span class="arrow open"></span>
					</a>
						<ul class="sub">
						<li class="<?php echo ($sidebar=='task' && $sub_sidebar == 1)?'active':'';?>">
							<a href="task.php">Pending Tasks</a>
						</li>
						<li class="<?php echo ($sidebar=='task' && $sub_sidebar == 2)?'active':'';?>">
							<a href="task.php?&amp;cat=2">Completed Tasks</a>
						</li>
					</ul>
				</li>

				

				<li class="<?php echo ($sidebar=='ch_password')?'active':'';?>">
					<a href="ch_password.php">
					<i class="icon-bar-chart"></i> 
					<span class="title">Change Password</span>
					<span class="selected"></span>
					</a>
				</li>
				
				
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
		<!-- END SIDEBAR -->