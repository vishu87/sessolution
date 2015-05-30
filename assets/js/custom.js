function addslashes(str) {
    str = str.replace(/\\/g, '\\\\');
    str = str.replace(/\'/g, '\\\'');
    str = str.replace(/\"/g, '\\"');
    str = str.replace(/\0/g, '\\0');
    return str;
}
 
function stripslashes(str) {
    str = str.replace(/\\'/g, '\'');
    str = str.replace(/\\"/g, '"');
    str = str.replace(/\\0/g, '\0');
    str = str.replace(/\\\\/g, '\\');
    return str;
}

function check_add_vote(report_id){
  if($("#resolution_name").val()){
    var file = 'check_resolution_number';
    $.post("ajax/"+ file +".php", {id:report_id, res_number:$("#resolution_number").val() }, function(data) {
          if(data == 'success'){ 
             var file = 'add_vote';
        $("#ses_voting_button").html("Adding");
         $.post("ajax/"+ file +".php", {id:report_id, res_name:$("#resolution_name").val(),res_number:$("#resolution_number").val(),ses_reco:$("#ses_reco").val(),res:$("#resolution").val(), detail:$("#detail").val(),man_reco:$("#man_reco").val(),man_share_reco:$("#man_share_reco").val(), reason:$("#reason").val(), type_business:$("#type_business").val(), type_res_os:$("#type_res_os").val(), focus:$("#focus").val()}, function(data) {

            $('#table_votes').html(data);
            $("#resolution_name").val('');
            $("#resolution_number").val('');
            $("#ses_reco").val('');
            $("#resolution").val('');
            $("#detail").val('');
            $("#reason").val('');
            $("#type_business option:first-child").attr('selected','selected');
            $("#type_res_os option:first-child").attr('selected','selected');
             $("#ses_voting_button").html("Add Vote");
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

//used
function voting(resolution_name, vote_id){
  $("#stack2 .modal-header h3").text(resolution_name); 
   $("#stack2 .modal-body").html("<p>Loading...</p>");

   var file = 'load_voting_ui';
   $.post("ajax/"+ file +".php", {id:vote_id}, function(data) {
       $("#stack2 .modal-body").html(data);
   }); 

}

function voting_submit(report_id,vote_id){
      //alert($("#com_id_select").val());
       

   if($("#resolution_name_pop").val()){
    var file = 'check_resolution_number_edit';
    $.post("ajax/"+ file +".php", {id:report_id, res_number:$("#resolution_number_pop").val(), vote_id:vote_id }, function(data) {
          if(data == 'success'){
             var file = 'edit_vote';
        
         $.post("ajax/"+ file +".php", {report_id:report_id, id:vote_id,  res_name:$("#resolution_name_pop").val(),res_number:$("#resolution_number_pop").val(),  res:$("#resolution_pop").val(), ses_reco:$("#ses_reco_pop").val(), detail:$("#detail_pop").val(),man_reco:$("#man_reco_pop").val(), man_share_reco:$("#man_share_reco_pop").val(), reason:$("#reason_pop").val(), type_business:$("#type_business_pop").val(), type_res_os:$("#type_res_os_pop").val(), focus:$("#focus_pop").val()}, function(data) {
            
            $("#table_votes").html(data);
            $("#close_button2").trigger('click');
       }); 

          } else {
            alert('Duplicate Resolution Number');
          }
       }); 
     

  } else {
    alert('Please Add Resolution Name');
    $("#resolution_name_pop").focus();
  }
      
}

//used
function view_users(report_id, company_id, company_name, year){
   $("#myModalLabel").text(company_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'load_pa_subscribers';
   $.post("ajax/"+ file +".php", {id:company_id, report_id:report_id,year: year}, function(data) {
      $("#modal-body").html(data);
   }); 

}

//used
function add_sub_ui(count,company_id, company_name, report_id,year){
  var type =1;
   $("#myModalLabel").text(company_name); 
   $("#modal-body").html("<p>Loading...</p>");
   var file = 'load_subscribers_ui';
   $.post("ajax/"+ file +".php", {count:count,company_id:company_id, year: year, report_id:report_id, type:type}, function(data) {
      $("#modal-body").html(data);
   }); 

}

function sub_add_submit(count,report_id,company_id,year,type) {
if($("#user_id_sub").val()){
  $("vote_s").html("Adding");
  $("vote_s").removeAttr("onclick");
  
   var file = 'add_subscriber';
   $.post("ajax/"+ file +".php", {company_id:company_id, year: year, report_id:report_id, type:type, user_id:$("#user_id_sub").val()}, function(data) {
      if(data == 'success'){
        $("#close_button").trigger('click');
        refresh_tr(count, report_id);
      } else {
          bootbox.alert("This user is already subscribed for this report!");
      }
   });

} else {
  alert("Please select a user");
}
}

function refresh_tr(count,report_id){
  var file = 'refresh_report';
  $("#sub_btn").remove();
  var c_class = $("#tr_"+report_id).attr('class');
  $("#tr_"+report_id).removeClass(c_class);
  $("#tr_"+report_id).animate({backgroundColor:'#ffff00'},{duration:500});


   $.post("ajax/"+ file +".php", {count:count, id:report_id}, function(data) {
      $("#tr_"+report_id).html(data);
       $("#close_button").removeAttr('onclick');
       $("#tr_"+report_id).animate({backgroundColor:''},{duration:500});
      $("#tr_"+ report_id).addClass(c_class,{duration:500});

   });
}

function ses_voting(count, company_name, report_id){
   $("#stack1 .modal-header h3").text(company_name); 
   $("#stack1 .modal-body").html("<p>Loading...</p>");
    $("#close_button1").attr('onclick','refresh_tr('+count+','+report_id+')');
   var file = 'ses_voting_ui';
   $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
      $("#stack1 .modal-body").html(data);
   }); 

}



function delete_report(id) {
     var file = 'delete_proxy';
        bootbox.confirm("Are you sure?", function(result) {
          if(result) {
            $.post("ajax/"+ file +".php", {id:id}, function(data) {
                if(data == 'success') {
                  $('#tr_'+ id).hide("slow", function(){
                    $("#tr_"+id).remove();
                  });
                } else {
                  alert("Database error");
                }
             });
          }
          else {
          
          }
        });
  }

  function skip_report(count,id) {
     var file = 'skip_proxy';
        bootbox.confirm("Are you sure?", function(result) {
          if(result) {
            $.post("ajax/"+ file +".php", {id:id}, function(data) {
                if(data == 'success') {
                  $('#tr_'+ id).hide("slow", function(){
                    $("#tr_"+id).remove();
                  });
                } else {
                  alert("Database error");
                }
             });
          }
          else {
          
          }
        });
  }

function load_edit(count,report_id, company_name, meeting_date){
   $("#close_button").attr('onclick','refresh_tr('+count+','+report_id+')');
   $("#myModalLabel").text("Edit: "+company_name+" on "+meeting_date);
   $("#myModal").css('width','980px');
   $("#myModal").css('margin-left','-490px');
   $("#modal-body").css('min-height','405px');
   $("#modal-body").html('<iframe src="proxy_ad/edit.php?id=' +report_id+ '" style="border:0; width:100%; height:400px;"></iframe>');
   
}
function load_edit_all(count,report_id, company_name, meeting_date){
   $("#myModalLabel").text("Edit: "+company_name+" on "+meeting_date);
   $("#myModal").css('width','980px');
   $("#myModal").css('margin-left','-490px');
   $("#modal-body").css('min-height','405px');
   $("#modal-body").html('<iframe src="proxy_ad/edit.php?id=' +report_id+ '" style="border:0; width:100%; height:400px;"></iframe>');
   
}
function load_custom(count, report_id, company_name, meeting_date, company_id){
  $("#close_button").attr('onclick','refresh_tr('+count+','+report_id+')');
  $("#myModalLabel").text("Custom Reports: "+company_name+" on "+meeting_date);
  $("#modal-body").css('min-height','405px');
  $("#modal-body").html('<iframe src="proxy_ad/custom_reports.php?com_id='+company_id+'&id=' +report_id+ '" style="border:0; width:100%; height:400px;"></iframe>');
}
function delete_voting(vote_id){
  var file = 'delete_vote';
    bootbox.confirm("Are you sure?", function(result) {
      if(result) {
        $.post("ajax/"+ file +".php", {id:vote_id}, function(data) {
            if(data == 'success') {
              $('#tr_vote_'+ vote_id).hide("slow", function(){$('#tr_vote_'+ vote_id).remove()});
            } else {
              alert("Database error");
            }
         });
      }
      else {
      
      }
    });
}

function release_reports(count, company_id, report_id, year){
 
  bootbox.confirm("Are you sure to release reports?", function(result) {
      if(result) {
         $("#release_"+report_id).html("Checking Votes..");
          var file = 'check_votes';
           $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
            if(data == 'success') {
              $("#release_"+report_id).html("Releasing..");
              $("#release_"+report_id).removeAttr("onclick");

              var file = 'release_reports';
               $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
                if(data == 'success') {
                 refresh_tr(count, report_id);
                } else {
                  bootbox.alert(data);
                  //alert("Database error");
                }

             });
            } else {
              bootbox.alert("Please mark complete before releasing the reports");
               $("#release_"+report_id).html("Release Reports");
            }

         });
      }
      else {
      
      }
    });

}

function unrelease_reports(count, company_id, report_id, year){
 
  bootbox.confirm("Are you sure to unrelease reports?", function(result) {
      if(result) {
         $("#release_"+report_id).html("Unreleasing..");
          $("#release_"+report_id).removeAttr("onclick");

          var file = 'unrelease_reports';
           $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
            if(data == 'success') {
             refresh_tr(count, report_id);
            } else {
              alert("Database error");
            }

         });
      }
      else {
      
      }
    });
}

function freeze(report_id){
  var file = "freeze_all";
     $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
        if(data == 'success'){
          refresh_voting_panel(report_id);
        }
   });
}

function unfreeze(report_id){
  var file = "unfreeze_all";
   $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
    if(data == 'success'){
          refresh_voting_panel(report_id);
        }
  });
         
}

function mark_comp(report_id,type){
  var file = "mark_comp_voting";
  $.post("ajax/"+ file +".php", {report_id:report_id, type:type}, function(data) {
     if(data == 'success'){
          refresh_voting_panel(report_id);
        } else {
            bootbox.alert(data);
        }
  });
}

function refresh_voting_panel(report_id){
  $("#stack1 .modal-body").html('Loading..');
  var file = 'ses_voting_ui';
   $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
      $("#stack1 .modal-body").html(data);
   });
}

function fetch_reasons(idinfo,idtype){
  $("#"+idinfo).html('Loading..');
  var file = 'fetch_reasons';
   $.post("ajax/"+ file +".php", {res_type_id:$("#"+idtype).val()}, function(data) {
      $("#"+idinfo).html(data);
   });
}

function release_template(report_id){
  $("#release_template").html('Releasing...').removeAttr('onclick');
  var file = 'release_template';
   $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
      if(data == 'success'){
         $("#release_template").html('Template Released');
      }
   });
}

function abridged_release(count, company_id, report_id, year){
 
   $("#abridged_release_"+report_id).html("Creating PDF...");
    var file = 'create_abridged';
     $.post("ajax/"+ file +".php", {report_id:report_id}, function(data) {
      if(data == 'success') {
        $("#abridged_release_"+report_id).html("Now Mailing..");
        $("#abridged_release_"+report_id).removeAttr("onclick");

        var file = 'release_abridged_reports';
         $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
          if(data == 'success') {
           refresh_tr(count, report_id);
          } else {
            bootbox.alert(data);
          }

       });

      } else {
        bootbox.alert("Problem in creating PDF");
         $("#abridged_release_"+report_id).html("Release Abridged");
      }

   });
}
function meeting_results_ui(count,report_id, company_name, meeting_date){
  $("#close_button").attr('onclick','refresh_tr('+count+','+report_id+')');
   $("#myModalLabel").text("Meeting Results: "+company_name+" on "+meeting_date);
   $("#myModal").css('width','98%');
   $("#myModal").css('margin-left','-49%');
   $("#modal-body").css('min-height','450px');
   $("#modal-body").html('Loading');
   $("#myModal .modal-footer").prepend('<button id="sub_btn" class="btn blue" onclick="update_meeting_result('+report_id+')">Update</button>');
   var file = 'ses_meeting_results';
   $.post("ajax/"+ file +".php", {id:report_id}, function(data) {
      $("#modal-body").html(data);
   });
}

function update_meeting_result(report_id){
  $("#sub_btn").html("Processing...");
  var file = "update_meeting_result";
  var count = 0;
  $('.meetingResultsForm').each(function(){
      var my_data = $(this).serialize();
       my_data = my_data+'&report_id='+report_id;
       $.post("ajax/"+ file +".php", my_data, function(data) {
        count++;
         if(count == $(".meetingResultsForm").length){
          bootbox.alert("Success! Meeting results are succefully saved");
          $("#sub_btn").html("Update");
        }
      });
  });
      
}

function custom_report_freeze(report_id){
  $(".freeze").html('Processing..');
  var file = "custom_report_freeze";
  var type = $(".freeze").attr("data-type");
   $.post("../ajax/"+ file +".php", {report_id:report_id, type:type}, function(data) {
      if(data == 'success'){
        if(type == 1){
          $(".freeze").attr("data-type",2);
          $(".freeze").html("Unfreeze").removeClass('green').addClass('yellow');
          $('button.formsubmit').removeClass('blue').html('Reports are freezed');
          $('.formsubmit').removeAttr('onclick');
        }
        if(type == 2){
          $(".freeze").attr("data-type",1);
          $(".freeze").html("Freeze").removeClass('yellow').addClass('green');
          $('button.formsubmit').addClass('blue').html('Save');
          $('.formsubmit').attr("onclick","$('#custom-form').submit()");

        }
      } else {
        alert(data);
        $(".freeze").html('Freeze');

      }
  });
}

$(document).ready(function(){
  $("select[name=meeting_type]").change(function(){
        if($(this).val() == 5){
          $(".ccm_type").show();
        } else {
          $(".ccm_type").hide();
        }
      });
});

function submit_custom_form(report_id, user_id){
  $("#custom_vote_submit").html('Processing..');
  var val = $("#custom_reso_form").serialize();
  val = val+'&report_id='+report_id+'&user_id='+user_id;
  var file = 'save_custom_votes';
  $.post("ajax/"+ file +".php", {val:val}, function(data) {
    alert(data);
  });
}

