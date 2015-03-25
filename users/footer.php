<!-- BEGIN FOOTER -->
<style>
.tooltip > .tooltip-inner { opacity:1; font-size:12px; max-width:700px;}
.tooltip.in {
	opacity: 1;
	filter: alpha(opacity=100);
}
.portlet-body{
	overflow-x:auto;
}
</style>
	<div class="footer">
		<div class="copyright" style="font-size:15px; width:100%">
    Designed &amp; Maintained by <a href="http://www.gkmit.co" target="_blank">GKM IT Pvt. Ltd.</a>
  </div>
		<div class="pull-right" style="float:right">
			<span class="go-top"><i class="icon-angle-up"></i></span>
		</div>
	</div>
	<!-- END FOOTER -->
	<!-- BEGIN JAVASCRIPTS -->
	<!-- Load javascripts at bottom, this will reduce page load time -->
	<script src="../assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
	<script src="../assets/js/jquery-1.8.3.min.js"></script>
	<script src="../assets/jquery-ui/jquery-ui-1.10.1.custom.min.js"></script>	
	<script src="../assets/breakpoints/breakpoints.js"></script>
	<script src="../assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="../assets/js/jquery.blockui.js"></script>
	<script src="../assets/js/jquery.cookie.js"></script>
	<script src="../assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
	<script src="../assets/js/bootbox.min.js"></script>
	<script src="../assets/js/clockface.js"></script>
	<script src="../assets/gritter/js/jquery.gritter.js"></script>
			<!-- Tablesorter: required for bootstrap -->

	<script src="../assets/js/jquery.tablesorter.js"></script>
	<script src="../assets/js/jquery.tablesorter.widgets.js"></script>
	<script src="../assets/js/jquery.tablesorter.pager.js"></script>
	<script src="../assets/chosen-bootstrap/chosen/chosen.jquery.js"></script>
	<script src="../assets/js/GeneralVoting.js"></script>
	
	<!-- ie8 fixes -->
	<!--[if lt IE 9]>
	<script src="../assets/js/excanvas.js"></script>
	<script src="../assets/js/respond.js"></script>
	<![endif]-->
	<script type="text/javascript" src="../assets/uniform/jquery.uniform.js"></script>
	<script type="text/javascript" src="../assets/datepicker/js/bootstrap-datepicker.js"></script>
	
	<script src="../assets/js/app.js"></script>		
	<script>
	$.fn.modal.Constructor.prototype.enforceFocus = function () {};
		jQuery(document).ready(function() {	
			//App.setPage('calendar');		
			// initiate layout and plugins
			App.init();
			$('.ttip').tooltip();
			$('.ttip-left').tooltip();

			$('.textareahtml').wysihtml5({
				"font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
				"emphasis": true, //Italics, bold, etc. Default true
				"lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
				"html": false, //Button which allows you to edit the generated HTML. Default false
				"link": false, //Button to insert a link. Default true
				"image": false, //Button to insert an image. Default true,
				"color": false //Button to change color of font  
			});
			$('.textareahtml_textonly').wysihtml5({
				"font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
				"emphasis": true, //Italics, bold, etc. Default true
				"lists": false, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
				"html": false, //Button which allows you to edit the generated HTML. Default false
				"link": false, //Button to insert a link. Default true
				"image": false, //Button to insert an image. Default true,
				"color": false //Button to change color of font  
			});
			$('.datepicker_month').datepicker({format:'dd-mm-yyyy', minViewMode:'1',}).on('changeDate', function(ev){
				$(this).datepicker('hide');
			});
			
			 $( "#sortable" ).sortable();
			
			$( "#sortable" ).disableSelection();
			 $( ".sortable" ).sortable();
			 $( ".sortable" ).disableSelection();

			 $(function() {
	        $('.chosen-select').chosen();
	        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
	    });

		});

		$(function() {
			$('.typehead').typeahead ({
				source: function(query, process) {
					$.ajax({
						url: 'ajax/source.php',
						type: 'POST',
						data: 'query=' + query,
						dataType: 'JSON',
						async: true,
						success: function(data) {
						 process(data);
						}
					});
				}
			
			});
		
		});

		


$(function() {

	$.extend($.tablesorter.themes.bootstrap, {
		// these classes are added to the table. To see other table classes available,
		// look here: http://twitter.github.com/bootstrap/base-css.html#tables
		table      : 'table table-bordered',
		header     : 'bootstrap-header', // give the header a gradient background
		footerRow  : '',
		footerCells: '',
		icons      : '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
		sortNone   : 'bootstrap-icon-unsorted',
		sortAsc    : 'icon-chevron-up',
		sortDesc   : 'icon-chevron-down',
		active     : '', // applied when column is sorted
		hover      : '', // use custom css here - bootstrap class may not override it
		filterRow  : '', // filter row class
		even       : '', // odd row zebra striping
		odd        : ''  // even row zebra striping
	});

	// call the tablesorter plugin and apply the uitheme widget
	$(".tablesorter").tablesorter({
		// this will apply the bootstrap theme if "uitheme" widget is included
		// the widgetOptions.uitheme is no longer required to be set
		theme : "bootstrap",

		widthFixed: true,

		headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

		// widget code contained in the jquery.tablesorter.widgets.js file
		// use the zebra stripe widget if you plan on hiding any rows (filter widget)
		widgets : [ "uitheme", "filter", "zebra" ],

		widgetOptions : {
			// using the default zebra striping class name, so it actually isn't included in the theme variable above
			// this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
			zebra : ["even", "odd"],

			// reset filters button
			filter_reset : ".reset"

			// set the uitheme widget to use the bootstrap theme class names
			// this is no longer required, if theme is set
			// ,uitheme : "bootstrap"

		},
		headers: {
      		<?php
      			if($sidebar == 'voting_records' &&  $sub_sidebar == 1){
      				echo '0: {sorter: false}, 3:{sorter:false}';
      			} else if($sidebar == 'voting_records' && ($sub_sidebar == 2 || $sub_sidebar == 5)){
      				echo '0: {sorter: false}, 4:{sorter:false},5:{sorter:false},6:{sorter:false}';
      			} else if($sidebar == 'firm_voting_records' && $sub_sidebar == 1){
      				echo '0: {sorter: false}, 4:{sorter:false},5:{sorter:false},6:{sorter:false},8:{sorter:false},9:{sorter:false}';
      			} else if($sidebar == 'firm_voting_records' && $sub_sidebar == 2){
      				echo '0: {sorter: false}, 4:{sorter:false}, 5:{sorter:false}, 7:{sorter:false},8:{sorter:false}';
      			}else if($sidebar == 'proxy_voters'){
      				echo '0: {sorter: false}, 2:{sorter:false},3:{sorter:false},4:{sorter:false}';
      			}else if($sidebar == 'reports' && $sub_sidebar == 1){
      				echo '0: {sorter: false}, 4:{sorter:false},5:{sorter:false},6:{sorter:false}';
      			}else if($sidebar == 'reports' && ($sub_sidebar == 2 || $sub_sidebar == 3)){
      				echo '0: {sorter: false}, 3:{sorter:false},4:{sorter:false},5:{sorter:false}';
      			}else {
      				echo '0: {sorter: false}';
      			}
      		?>
	    }
	}).tablesorterPager({

		// target the pager markup - see the HTML block below
		container: $(".ts-pager"),

		// target the pager page select dropdown - choose a page
		cssGoto  : ".pagenum",

		// remove rows from the table to speed up the sort of large tables.
		// setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
		removeRows: false,

		// output string - default is '{page}/{totalPages}';
		// possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
		output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'

	});

	$(".tablesorter_without").tablesorter({
		// this will apply the bootstrap theme if "uitheme" widget is included
		// the widgetOptions.uitheme is no longer required to be set
		theme : "bootstrap",

		widthFixed: true,

		headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

		// widget code contained in the jquery.tablesorter.widgets.js file
		// use the zebra stripe widget if you plan on hiding any rows (filter widget)
		widgets : [ "uitheme", "filter", "zebra" ],

		widgetOptions : {
			// using the default zebra striping class name, so it actually isn't included in the theme variable above
			// this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
			zebra : ["even", "odd"],

			// reset filters button
			filter_reset : ".reset"

			// set the uitheme widget to use the bootstrap theme class names
			// this is no longer required, if theme is set
			// ,uitheme : "bootstrap"

		},
		headers: {
      // disable sorting of the first column (we start counting at zero)
	      0: {
	        
	        sorter: false
	      },
	       3: {
	        
	        sorter: false
	      },
	      4: {
	        
	        sorter: false
	      },
	       5: {
	        
	        sorter: false
	      },
	       6: {
	        
	        sorter: false
	      },
	       7: {
	        
	        sorter: false
	      },
	      8: {
	        
	        sorter: false
	      },
	      9: {
	        
	        sorter: false
	      }
	    }
	});


		});


function initialize(){
	$('.datepicker_month').datepicker({format:'dd-mm-yyyy', minViewMode:'1',}).on('changeDate', function(ev){
				$(this).datepicker('hide');
			});

	$('.ttip').tooltip();
	
	$('#ana_time').clockface();  

	
			$('.typehead').typeahead ({
				source: function(query, process) {
					$.ajax({
						url: 'ajax/source.php',
						type: 'POST',
						data: 'query=' + query,
						dataType: 'JSON',
						async: true,
						success: function(data) {
						 process(data);
						}
					});
				}
			
			});
		
	
	$('.textareahtml').wysihtml5({
				"font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
				"emphasis": true, //Italics, bold, etc. Default true
				"lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
				"html": false, //Button which allows you to edit the generated HTML. Default false
				"link": false, //Button to insert a link. Default true
				"image": false, //Button to insert an image. Default true,
				"color": false //Button to change color of font  
			});
	$('.chosen-select').chosen();
	        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });

	        $.extend($.tablesorter.themes.bootstrap, {
		// these classes are added to the table. To see other table classes available,
		// look here: http://twitter.github.com/bootstrap/base-css.html#tables
		table      : 'table table-bordered',
		header     : 'bootstrap-header', // give the header a gradient background
		footerRow  : '',
		footerCells: '',
		icons      : '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
		sortNone   : 'bootstrap-icon-unsorted',
		sortAsc    : 'icon-chevron-up',
		sortDesc   : 'icon-chevron-down',
		active     : '', // applied when column is sorted
		hover      : '', // use custom css here - bootstrap class may not override it
		filterRow  : '', // filter row class
		even       : '', // odd row zebra striping
		odd        : ''  // even row zebra striping
	});

	// call the tablesorter plugin and apply the uitheme widget
	$(".tablesorter").tablesorter({
		// this will apply the bootstrap theme if "uitheme" widget is included
		// the widgetOptions.uitheme is no longer required to be set
		theme : "bootstrap",

		widthFixed: true,

		headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

		// widget code contained in the jquery.tablesorter.widgets.js file
		// use the zebra stripe widget if you plan on hiding any rows (filter widget)
		widgets : [ "uitheme", "filter", "zebra" ],

		widgetOptions : {
			// using the default zebra striping class name, so it actually isn't included in the theme variable above
			// this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
			zebra : ["even", "odd"],

			// reset filters button
			filter_reset : ".reset"

			// set the uitheme widget to use the bootstrap theme class names
			// this is no longer required, if theme is set
			// ,uitheme : "bootstrap"

		},
		headers: {
      // disable sorting of the first column (we start counting at zero)
	      0: {
	        // disable it by setting the property sorter to false
	        sorter: false
	      }
	    }
	})

}

function grit(title, text,class_name){
$.gritter.add({
	// (string | mandatory) the heading of the notification
	title: title,
	// (string | mandatory) the text inside the notification
	text: text,
	sticky: false,
	// (int | optional) the time you want it to be alive for before fading out
	time: '1000',
	class_name: class_name
	});
}

function PrintElem(elem){
    Popup($(elem).html());
}

function Popup(data) {
    var mywindow = window.open('', 'print_voting', 'height=500,width=700');
    mywindow.document.write('<html><head><title>Voting Details: Print</title>');
    mywindow.document.write('<link rel="stylesheet" href="<?php echo STRSITE; ?>assets/bootstrap/css/bootstrap.min.css" type="text/css" />');
    mywindow.document.write('</head><body >');
    mywindow.document.write(data);
    mywindow.document.write('</body></html>');
   mywindow.print();
   mywindow.close();

    return true;
}


	</script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
