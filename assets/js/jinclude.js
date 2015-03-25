$(document).ready(function(){
	//Forget and login forms
	$("#forget-password").click(function(){
		$(".forget-form").show();
		$(".login-form").hide();
	  
	  });
	  
	  $("#back-btn").click(function(){
		$(".forget-form").hide();
		$(".login-form").show();
	  
	  });

	$('#login_submit').click(function(){
		
		if(validateUserName() && validateUserPassword())
		{
			$("#login_submit").text('Validating....').fadeIn(3000);
			$.post("login-exec.php",{ user_name:$('#username_login').val(),password:$("#password_login").val(), login_as:$("[name=login_as]:checked").val() }  ,function(data)
				{	
					if(data == 'change_password'){
						window.location.replace("change_password.php");
					}
					else if(data== 'yes'){
						window.location.replace("users/");
					}
					else if(data== 'yesadmin'){
						window.location.replace("sysadmin/");
					}
					else if(data== 'yesanalyst'){
						window.location.replace("sysanalyst/");
					}
					else if(data== 'yesaddon'){
						window.location.replace("analysts/");
					}
					else if (data == 'no_username'){
						$("#login_submit").html('Login <i class="m-icon-swapright m-icon-white"></i>').fadeIn(3000);
						$("#error-box").fadeIn(500).html("Invalid Username");
					}
					else if (data == 'no_password'){
						$("#login_submit").html('Login <i class="m-icon-swapright m-icon-white"></i>').fadeIn(3000);
						$("#error-box").fadeIn(500).html("Invalid Password");
					}
				});
			return false;
		}
		else return false;
	});

	$('#forgot_submit').click(function(){
		
		if(validateEmail())
		{
			$("#forgot_submit").text('Processing..').fadeIn(3000);
			$.post("forgot-password.php",{ email:$('#email_forgot').val()}  ,function(data)
				{	
					if(data== 'success'){
							$("#email_forgot").removeClass("sys_error");
							$("#emailInfo").html("<span style='color:#1d943b;'>Your password has been reset and sent to your email-id. Please check.<span>");
							$("#forgot_submit").hide();
							return true;
					}
					else {
						$("#email_forgot").addClass("sys_error");
						$("#emailInfo").text("This email-id is not correct");
						$("#forgot_submit").text('Submit');
					}
				});
			return false;
		}
		else return false;
	});
	
});

function validateUserName(){
			if( $("#username_login").val().length < 1){
				$("#username_login").addClass('sys_error');
				$('#username_info').text("*Please enter a valid username");
				return false;
			}

			else{
					$("#username_login").removeClass('sys_error');
					$('#username_info').text("");
					return true;
			}
		}

function validateUserPassword(){
	if( $("#password_login").val().length < 1){

		$("#password_login").addClass('sys_error');
		$('#password_info').text("*Please enter a valid password");
		return false;
	}

	else {
			$("#password_login").removeClass('sys_error');
			$('#password_info').text("");
			return true;
	}
}
		
		
function validateEmail(){
//testing regular expression
var a = $("#email_forgot").val();
var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
//if it's valid email
if(filter.test(a)){
	$("#email_forgot").removeClass("sys_error");
	$("#emailInfo").text("");
	return true;
}
//if it's NOT valid
else{
	$("#email_forgot").addClass("sys_error");
	$("#emailInfo").text("Type a valid e-mail please");
	return false;
}
}

function change_save(){
  var id_info = 'new_p';
  if($("#"+id_info).val().match(/^(?=.*\d)(?=.*[A-Z])(?=.*[~!@#$%&_^*]).{8,}$/) == null){
     $("#"+id_info).parent().parent().find('span').text("Please input a valid password.");
     $("#"+id_info).parent().parent().parent().addClass("error");
  } else {
     if($("#new_p").val() !== $("#re_new_p").val()){
        $("#re_new_p").parent().parent().find('span').text("Both passwords do not match.");
        $("#re_new_p").parent().parent().parent().addClass("error");
     } else {
        $("#change_button").text('Validating....').fadeIn(3000);
			$.post("change_p.php",{old_p:$("#old_p").val(), new_p:$("#new_p").val(), re_new_p:$("#re_new_p").val() }  ,function(data)
				{	
					if(data == 'old_p'){
						$("#change_button").html('Change Password').fadeIn(3000);
						$("#error-box").fadeIn(500).html("Old Password is incorrect");
					} else if(data == 'new_p_format'){
						$("#change_button").html('Change Password').fadeIn(3000);
						$("#error-box").fadeIn(500).html("New Password does not meet minimum requirements");
					} else if(data == 'new_p'){
						$("#change_button").html('Change Password').fadeIn(3000);
						$("#error-box").fadeIn(500).html("Password should not be same as last three passwords");
					}else if(data == 're_new_p'){
						$("#change_button").html('Change Password').fadeIn(3000);
						$("#error-box").fadeIn(500).html("Retype password correctly");
					}else if (data == 'users'){
						window.location.replace("users/");
					}else if (data == 'analysts'){
						window.location.replace("analysts/");
					}
				});
			return false;
     }
  }
}

function clear_password(){
  var id_info = 'new_p';
  $("#"+id_info).parent().parent().find('span').text("");
  $("#"+id_info).parent().parent().parent().removeClass("error");

  var id_info = 're_new_p';
  $("#"+id_info).parent().parent().find('span').text("");
  $("#"+id_info).parent().parent().parent().removeClass("error");
}