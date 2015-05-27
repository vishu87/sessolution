function refresh_ses_voting(report_id,type){
    $("#stack1 .modal-body").html("Loading..");
    var file = 'add_vote_user';
         $.post("ajax/"+ file +".php", {id:report_id, type:type}, function(data) {
             $("#stack1 .modal-body").html(data);
             initialize();
       });
}

function view_vote(company_name,proxy_id,type){
      // $("#stack1 .modal-header h3").text(company_name); 
    count = parseInt($("#tr_"+proxy_id+" td:first").html());
      $("#close_button1").attr('onclick',' refresh_tr('+proxy_id+')');
      
      $("#stack1 .modal-header").html('<div class="row-fluid"><div class="span6"><h3>'+company_name+'</h3></div><div align="right"><a href="javascript:;" role="button" class="btn yellow" onclick="view_vote(\''+company_name+'\',' + proxy_id +','+type+' )" ><i class="icon-refresh"></i> </a></div></div>');
       $("#stack1 .modal-body").html("<p>Loading...</p>");
        var file = 'add_vote_user';
        $("#vote_s").html('<i class="icon-ok"></i>Processing');
         $.post("ajax/"+ file +".php", {id:proxy_id,type:type}, function(data) {
             $("#stack1 .modal-body").html(data);
             initialize();             
       }); 
}

function load_user_votes(report_id,parent_id){
   $("#user_votes").show();
  $("#user_votes").html("Processing...");
     var file = "load_user_votes";
     $.post("ajax/"+ file +".php", {report_id:report_id,parent_id:parent_id}, function(data) {
        $("#user_votes").html(data);
        $("#vote_loader").html('Hide User Votes');
        $("#vote_loader").attr('onclick','hide_user_votes('+report_id+','+parent_id+')');
   });
}

function hide_user_votes(report_id,parent_id){
   $("#user_votes").hide("slow");
   $("#vote_loader").html('View User Votes');
   $("#vote_loader").attr('onclick','load_user_votes('+report_id+','+parent_id+')');
}

function voting_page(type){
	$("#voting_button").html("Processing...");
     var file = "add_voting";
     var my_data = $("#VotingForm").serialize();
     my_data += '&voting_type='+type;
     $.post("ajax/"+ file +".php", my_data, function(data) {
        grit("Success!", "Your votes are succefully saved", "gritter-light");
        // var html = '<div class="alert alert-success" id="alert"><strong>Success!</strong> Voting is successfully saved.</div>';
        // $('<div></div>').appendTo("#alertSpan").hide().append(html).fadeIn('slow').delay(500).fadeOut("slow");
        var text_button = (type == 1)?'Save My Votes':'Save Final Votes';
        $("#voting_button").html(text_button);
        
   });  
}

function view_vote_upcoming(company_name,proxy_id){
       $("#stack1 .modal-body").html("<p>Loading...</p>");
        var file = 'add_vote_user';
         $.post("ajax/"+ file +".php", {id:proxy_id, page_type:'upcoming', type:1}, function(data) {
             $("#stack1 .modal-body").html(data);
             initialize();
       });  
}

function request_vote(report_id){

   $("#req_vote_"+report_id).html("<p>Processing..</p>");
    var file = 'request_vote';
     $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
      if(data == 'success') $("#req_vote_"+report_id).replaceWith("Requested");
      else alert(data);
   });
     
}


//self voting
function view_self_vote(company_name,proxy_id){
      
   //$("#stack1 .modal-header h3").text(company_name); 
    $("#stack1 .modal-header").html('<div class="row-fluid"><div class="span6"><h3>'+company_name+'</h3></div><div align="right"><a href="javascript:;" role="button" class="btn yellow" onclick="view_vote(\''+company_name+'\',' + proxy_id +' )" ><i class="icon-refresh"></i> </a></div></div>');

       count = parseInt($("#tr_"+proxy_id+" td:first").html());
      $("#close_button1").attr('onclick',' refresh_tr('+proxy_id+')');

   $("#stack1 .modal-body").html("<p>Loading...</p>");
   var file = 'ses_voting_ui';
   $.post("ajax/"+ file +".php", {id:proxy_id}, function(data) {
      $("#stack1 .modal-body").html(data);
      initialize();
   }); 

}

function self_voting(){
     var file = "add_self_voting";
     $("#voting_button").html("Processing...");
        var my_data = $("#VotingForm").serialize();
         $.post("ajax/"+ file +".php", my_data, function(data) {
            if(data == 'success'){
              var html = '<div class="alert alert-success" id="alert"><strong>Success!</strong> Voting is successfully saved.</div>';
               $('<div></div>').appendTo("#alertSpan").hide().append(html).fadeIn('slow').delay(500).fadeOut("slow");
            } else {
              alert('Voting error');
            }   
            $("#voting_button").html("Save");
       });    
}


function check_add_vote(report_id){
 if($("#resolution_number").val()){
  if($("#resolution_name").val()){
    var file = 'check_resolution_number';
    $.post("ajax/"+ file +".php", {id:report_id, res_number:$("#resolution_number").val() }, function(data) {
          if(data == 'success'){
             var file = 'add_vote';
             $("#ses_voting_button").html("Adding");
             $.post("ajax/"+ file +".php", {id:report_id, res_name:$("#resolution_name").val(),res_number:$("#resolution_number").val(), man_reco:$("#man_reco").val()}, function(data) {
                 refresh_self_voting(report_id);

           });
          } else {
            alert('Duplicate Resolution Number');
          }
       }); 
     

  } else {
    alert('Please Add Resolution Name');
    $("#resolution_name").focus();
  }
}
  else {
     alert('Please Add Resolution Number');
     $("#resolution_number").focus();
  }

}

function refresh_self_voting(report_id){
   var file = 'ses_voting_ui';
   $("#stack1 .modal-body").html("Loading..");
   $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
      $("#stack1 .modal-body").html(data);
      initialize();
 });
}

function freeze_vote(report_id,type){
   var file = 'check_freeze_vote';
   $("#freeze_button").html('Checking..');
   $.post("ajax/"+ file +".php", {id:report_id,type:1}, function(data) {
    data = JSON.parse(data);
         if(data.success){
            var file = 'freeze_vote';
              $("#freeze_button").html('Freezing..');
             $("#freeze_button").removeAttr('onclick');
             $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
                refresh_ses_voting(report_id, type);
                grit("Success!","Your votes have been successfully freezed.","gritter-light");
           });    
         } else {
            if(data.type == 1){
                bootbox.confirm(data.message, function(result) {
                if(result) {
                  var file = 'freeze_vote';
                  $("#freeze_button").html('Freezing..');
                     $("#freeze_button").removeAttr('onclick');
                     $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
                        refresh_ses_voting(report_id, type);
                        grit("Success!","Your votes have been successfully freezed.","gritter-light");
                   });

                }
                else {
                  $("#freeze_button").html('Freeze My Votes');
                }
              }); 
            } else {
               bootbox.alert(data.message);
              $("#freeze_button").html('Freeze My Votes');
            }
           
         }
  });
}

function unfreeze_vote(report_id,type){
   var file = 'unfreeze_vote';
    $("#unfreeze_button").html('Un-Freezing..');
   $("#unfreeze_button").removeAttr('onclick');
   $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
       refresh_ses_voting(report_id, type);
                grit("Success!","Your votes have been successfully un-freezed.","gritter-light");
 });
}

function delete_resolution(user_resolution_id,report_id){
   
     var file = "delete_resolution";
        bootbox.confirm("Are you sure?", function(result) {
          if(result) {
            $.post("ajax/"+ file +".php", {res_id:user_resolution_id, id:report_id}, function(data) {
                  if(data == 'success'){
                      refresh_self_voting(report_id);
                    } else {
                    alert('Deletion error');
                  }   
             }); 
          }
          else {
          
          }
        });       
}
function load_self_user_votes(report_id,parent_id){
  $("#user_votes_self").show();
  $("#user_votes_self").html("Processing...");
     var file = "load_self_user_votes";
     $.post("ajax/"+ file +".php", {report_id:report_id,parent_id:parent_id}, function(data) {
        $("#user_votes_self").html(data);
        $("#vote_loader_self").html('Hide User Votes');
        $("#vote_loader_self").attr('onclick','hide_self_user_votes('+report_id+','+parent_id+')');
   });
  }

  function hide_self_user_votes(report_id,parent_id){
   $("#user_votes_self").hide("slow");
   $("#vote_loader_self").html('View User Votes');
   $("#vote_loader_self").attr('onclick','load_self_user_votes('+report_id+','+parent_id+')');

}

function set_dline(report_id){
  if(validate_required_date_info($("#deadline_report").val(),"Please input a proper date")){
    $("#set_deadline").html('...').removeAttr('onclick');
        var file = "set_deadline";
       $.post("ajax/"+ file +".php", {deadline:$("#deadline_report").val(),report_id:report_id}, function(data) {
          $("#set_deadline").attr('onclick','set_dline('+report_id+')');
          $("#set_deadline").html('<i class="m-icon-swapright m-icon-white"></i>').delay(1000);
          grit('Success!','Deadline is successfully set', 'gritter-light');
     });
  }
}

function set_freeze(report_id,type){
   var file = 'check_freeze_vote';
    //$("#freeze_all").html('Freezing.. Please Wait').removeAttr('onclick');
    $("#freeze_all").html('Freezing.. Please Wait');
        $.post("ajax/"+ file +".php", {id:report_id,type:2}, function(data) {
          data = JSON.parse(data);
         if(data.success){
           //create a second pop-up for votes
            $("#stack2 .modal-body").html('Loading..');
            var file = 'final_votes_data';;
             $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
                $("#stack2 .modal-body").html(data);
                $("#accept_freeze").attr('onclick','accept_freeze('+report_id+','+type+')');
            });


            $('#stack2').modal('show');  
         } else {
            if(data.type == 1){
               bootbox.confirm(data.message, function(result) {
                if(result) {
                  $('#stack2').modal('show');  

                  $("#stack2 .modal-body").html('Loading..');
                  var file = 'final_votes_data';;
                   $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
                      $("#stack2 .modal-body").html(data);
                      $("#accept_freeze").attr('onclick','accept_freeze('+report_id+','+type+')');
                  });

                }
                else {
                $("#freeze_all").html('Freeze All Votes');
                }
              }); 
            } else {
              bootbox.alert(data.message);
              $("#freeze_all").html('Freeze All Votes');
            }
         }
  });
}

function set_unfreeze(report_id,type){

    $("#unfreeze_all").html('Unfreezing.. Please Wait');
      var file = "check_upload_form";
       $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
         if(data == 'success'){
          $("#unfreeze_all").removeAttr('onclick');
              var file = "unfreeze_all";
             $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
                $("#stack1 .modal-body").html(data);
           });
         } else {
            bootbox.alert('The form has been uploaded for this meeting. Please reset the proxy to change votes. If you want to change votes after ther proxy voting has been done. You can do that on the next day of meeting in Edit Past Votings section.');
            $("#unfreeze_all").html('Unfreeze All');
         }
     });
}

$(document).ready(function()
{
  $(".modal-body").css("max-height",$(window).height()*0.65);
  //$(".stack").css("max-height",$(window).height()*0.65);
  $(".alert").delay(1000).hide("slow");
});

function select_ses_voting(report_id){
  $("#VotingFormTable").html('Loading.. Please Wait');
        var file = "select_ses_voting";
       $.post("ajax/"+ file +".php", {an_id:$("#an_ses_id").val(),report_id:report_id}, function(data) {
          $("#VotingFormTable").html(data);
             initialize();
     });
}

function copy_voting(user_id, vote_id){
  $("#cp_btn_"+user_id+"_"+vote_id).html('Copying');
        var file = "copy_voting";
       $.post("ajax/"+ file +".php", {user_id:user_id,vote_id:vote_id}, function(data) {
            data = $.parseJSON(data);
          if(data.success == 1){
            $('.cp_btn_'+vote_id).html('Copy').removeClass('green');
            $("#cp_btn_"+user_id+"_"+vote_id).html('Copied').addClass('green');
              var vote_select = $("#tr_vote_"+vote_id+' .vote');
              var comment_select = $("#tr_vote_"+vote_id+' .comment');

              vote_select.find('option').each(function(){
                if($(this).val() == data.vote){
                  $(this).attr('selected','selected');
                }
              });
              comment_select.val(data.comment);
          } else {
            $("#cp_btn_"+user_id+"_"+vote_id).html('Copy');
          }
     });
}


function select_self_voting(report_id,parent_id){
  $("#VotingFormTable").html('Loading.. Please Wait');
        var file = "select_self_voting";
       $.post("ajax/"+ file +".php", {an_id:$("#an_ses_id").val(),report_id:report_id, parent_id:parent_id}, function(data) {
          $("#VotingFormTable").html(data);
             initialize();             
     });
}

function delete_meeting_voting_records(report_id){
  $("#stack1 .modal-header h3").html('Delete this company meeting');
    $("#stack1 .modal-body").html("<p>Loading...</p>");
        var file = 'delete_meeting_voting_records';
         $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
             $("#stack1 .modal-body").html(data);
             initialize();
  }); 
}



function add_meeting_voting_records(){
  $("#stack1 .modal-body").css('min-height','400px');
  $("#stack1 .modal-header").html('<h3>Add a company meeting</h3>');
    $("#stack1 .modal-body").html("<p>Loading...</p>");
        var file = 'add_meeting_voting_records';
         $.post("ajax/"+ file +".php", {}, function(data) {
             $("#stack1 .modal-body").html(data);
             initialize();
  }); 
}

function search_meeting(){
  $("#results").html("<p>Loading...</p>");
   var file = 'search_meeting';
     $.post("ajax/"+ file +".php", {com_string:$("#com_string").val(), date_to:$("#date_to").val(), date_from:$("#date_from").val()}, function(data) {
         $("#results").html(data);
});
   }


   function delete_meeting_rec(user_id,report_id){
    count = parseInt($("#tr_"+report_id+" td:first").html());
     var file = "delete_meeting_rec";
        bootbox.confirm("Are you sure?", function(result) {
          if(result) {
            $.post("ajax/"+ file +".php", {user_id:user_id, report_id:report_id}, function(data) {

                  if(data == 'success'){
                      $("#btn_"+user_id).hide("slow", function(){
                        $("#btn_"+user_id).remove();
                      });
                      // refresh_tr(report_id);
                    } else {
                    alert('Deletion error');
                  }   
             }); 
          }
          else {
          
          }
        });       
}

 function delete_all_meeting_rec(report_id){
    count = parseInt($("#tr_"+report_id+" td:first").html());
     var file = "delete_meeting_rec_all";
        bootbox.confirm("Are you sure?", function(result) {
          if(result) {
            $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {

                  if(data == 'success'){
                    $("#close_button1").trigger('click');
                      $("#tr_"+report_id).hide("slow", function(){
                        $("#tr_"+report_id).remove();
                      });
                      // refresh_tr(report_id);
                    } else {
                    alert('Deletion error');
                  }   
             }); 
          }
          else {
          
          }
        });       
}


   function add_meeting_rec(report_id){

    var val = [];
    $(':checkbox:checked').each(function(i){
      val[i] = $(this).val();
    });

    if(val.length === 0){
      bootbox.alert("Please select some portfolios");
    } else {
      $("#add_met_button_"+report_id).html("Adding.. Please Wait");
      var file = 'add_meeting_rec';
       $.post("ajax/"+ file +".php", {report_id:report_id, val:val}, function(data) {
         $("#add_met_button_"+report_id).html("Add to voting records");
         bootbox.alert(data);
      });
    }

   }

   function ignore_an(report_id){
    bootbox.confirm("Are you sure to ignore votes?", function(result) {
          if(result) {
            $("#ign").addClass('yellow');
            $("#inc").removeClass('green').removeClass('yellow');
             var file = 'ignore_an';
               $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
                 if(data == 'success'){
                   $("#ign").addClass('green').removeClass('yellow');
                     refresh_ses_voting(report_id,2);
                   
                 }
              });
          }
          else {
          
          }
        }); 
    
   }

   function include_an(report_id){
    bootbox.confirm("Are you sure to include votes?", function(result) {
          if(result) {
             $("#inc").addClass('yellow');
              $("#ign").removeClass('green').removeClass('yellow');
               var file = 'include_an';
                 $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
                   if(data == 'success'){
                     $("#inc").addClass('green').removeClass('yellow');
                     refresh_ses_voting(report_id,2);
                   }
                });
          }
          else {
          
          }
        }); 

   
   }

   function view_portfolio_users(report_id){
   $("#stack1 .modal-header h3").html('Users for this meeting');
   $("#stack1 .modal-body").html("<p>Loading...</p>");
    var file = 'view_portfolio_users';
       $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
             $("#stack1 .modal-body").html(data);
      });
   }

    function view_company_users(com_id){
     $("#stack1 .modal-header h3").html('Users for this company');
     $("#stack1 .modal-body").html("<p>Loading...</p>");
      var file = 'view_company_users';
         $.post("ajax/"+ file +".php", {com_id:com_id}, function(data) {
               $("#stack1 .modal-body").html(data);
        });
   }

   function reload(){
    location.reload();
   }
   function hideall(){
   bootbox.hideAll();
   }

   function change_check(id){

    var btn = $("#btn_"+id);
    var check = $("#check_"+id);

    if(btn.hasClass('green')){
      btn.removeClass('green');
      check.attr('checked', false);
    } else {
      btn.addClass('green');
      check.attr('checked', true);   
    }


   }

function copy_comments(count){
  var vote_value = $("#ses_reco_"+count).text();
  var comment_value = $("#ses_comment_"+count).text();
  //alert(vote_value + comment_value);
  var vote_select = $("#vote_"+count);
  var comment_select = $("#comment_"+count);

  // vote_select.find('option').each(function(){
  //   if($(this).text() == vote_value){
  //     $(this).attr('selected','selected');
  //   }
  // });
  comment_select.val(comment_value);
}

function subscribe(report_id, com_id, report_type,type){
  var file = "sub_request";
  $.post("ajax/"+ file +".php", {report_id:report_id, com_id:com_id, report_type: report_type}, function(data) {
        bootbox.alert(data);
        if(type == 0) $("#sub_"+report_id).html("Subscription<br>Requested");
        else $("#sub_"+report_id).html("Full Report<br>Requested");
   });
}

function cancel_freeze(){
    $("#freeze_all").html('Freeze All Votes');
    $("#accept_freeze").removeAttr('onclick');
}

function accept_freeze(report_id,type){
    var file = 'freeze_all';;
     $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
        $("#stack1 .modal-body").html(data);
        $("#accept_freeze").removeAttr('onclick');
   });   
}

function proxy_allow_ui(report_id){
  $("#stack1 .modal-body").css('min-height','400px');
  count = $("#tr_"+report_id+" td").eq(0).html();
      $("#close_button1").attr('onclick',' refresh_tr('+report_id+')');

  $("#stack1 .modal-header").html('<h3>Allow Users to Vote for this Meeting</h3>');
    $("#stack1 .modal-body").html("<p>Loading...</p>");
        var file = 'proxy_allow_ui';
         $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
             $("#stack1 .modal-body").html(data);
  }); 
}

function proxy_allow(report_id,user_id, portfolio_id){
    $(".btn_ad_"+portfolio_id).eq(1).html("Processing..");
    var file = 'proxy_allow';
         $.post("ajax/"+ file +".php", {report_id:report_id, user_id:user_id, portfolio_id:portfolio_id}, function(data) {
          $(".btn_ad_"+portfolio_id).hide("slow");
  }); 
}

function proxy_disallow(report_id,user_id, portfolio_id){
    $(".btn_ad_"+portfolio_id).eq(2).html("Processing..");
    var file = 'proxy_disallow';
         $.post("ajax/"+ file +".php", {report_id:report_id, user_id:user_id, portfolio_id:portfolio_id}, function(data) {
          $(".btn_ad_"+portfolio_id).hide("slow");
  }); 
}

function proxy_allow_all(report_id,user_id){
    $("#allow_all").html("Processing..");
    var file = 'proxy_allow_all';
         $.post("ajax/"+ file +".php", {report_id:report_id, parent_id:user_id}, function(data) {
          $("#close_button1").trigger('click');
  }); 
}

function proxy_disallow_all(report_id,user_id){
    $("#disallow_all").html("Processing..");
    var file = 'proxy_disallow_all';
         $.post("ajax/"+ file +".php", {report_id:report_id, parent_id:user_id}, function(data) {
          $("#close_button1").trigger('click');
          $("#tr_"+report_id).hide(500, function(){
            $(this).remove();
          });
  }); 
}

function send_reminder(user_id,report_id){
    var item = $("#reminder_"+user_id);
    item.html("Sending..");
    var file = 'send_reminder';
     $.post("ajax/"+ file +".php", {report_id:report_id, user_id:user_id}, function(data) {
      if(data == 'success'){
        item.html("Reminder Sent");
        item.removeAttr('onclick');
        item.removeClass('red').addClass('yellow');
      } else {
        item.html("Send Reminder");
        bootbox.alert('Failure in sending reminder');
      }
  }); 
}

function set_mark(report_id){
    var item = $("#set_mark");
    item.html("Marking..");
    var file = 'set_mark';
     $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
      if(data == 'success'){
        item.html("Unmark for Voting Committee Approval");
        item.attr('onclick','set_unmark('+report_id+')');
      } else {
        item.html("Mark for Voting Committee Approval");
        bootbox.alert('Failure');
      }
  }); 
}

function set_unmark(report_id){
    var item = $("#set_unmark");
    item.html("Unmarking..");
    var file = 'set_unmark';
     $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
      if(data == 'success'){
        item.html("Mark for Voting Committee Approval");
        item.attr('onclick','set_unmark('+report_id+')');
      } else {
        item.html("Unmark for Voting Committee Approval");
        bootbox.alert('Failure');
      }
  }); 
}

function meeting_results(report_id){

   $("#stack3 h3").text("Meeting Results");
   $("#stack3").css('width','98%');
   $("#stack3").css('margin-left','-49%');
   $("#stack3 .modal-body").css('min-height','500px');
   $("#stack3 .modal-body").html('Loading');
   
   var file = 'meeting_results';
   $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
      $("#stack3 .modal-body").html(data);
   });
}

function show_hide(voting_id){
  var item = $(".show_hide_btn"+voting_id);
  var show = item.attr('data-show');
  if(show == 0){
    item.html('Details <i class="icon-chevron-up"></i>');
    item.attr('data-show',1);
    $('.tr_'+voting_id).show();
  } else {
    item.html('Details <i class="icon-chevron-down"></i>');
    item.attr('data-show',0);
    $('.tr_'+voting_id).hide();
  }
}