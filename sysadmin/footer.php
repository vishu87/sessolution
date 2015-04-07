<!-- BEGIN FOOTER -->
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
			<!-- Tablesorter: required for bootstrap -->

	<script src="../assets/js/jquery.tablesorter.js"></script>
	<script src="../assets/js/jquery.tablesorter.widgets.js"></script>
	<script src="../assets/js/jquery.tablesorter.pager.js"></script>
	<script src="../assets/chosen-bootstrap/chosen/chosen.jquery.js"></script>
	<script src="../assets/js/custom.js"></script>
	
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
			// initiate layout and plugins
			App.init();
			$('.textareahtml').wysihtml5({
				"font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
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
			 $('#t2').clockface({
				format: 'HH:mm',
				trigger: 'manual'
			});   
		 
			$('#toggle-btn').click(function(e){   
				e.stopPropagation();
				$('#t2').clockface('toggle');
			});
			
			 $('#t1').clockface({
				format: 'HH:mm',
				trigger: 'manual'
			});   
		 
			$('#toggle-btn1').click(function(e){   
				e.stopPropagation();
				$('#t1').clockface('toggle');
			});
	
			$('.datepicker_month').datepicker({format:'dd-mm-yyyy', minViewMode:'1',}).on('changeDate', function(ev){
				$(this).datepicker('hide');
			});
			 $( "#sortable" ).sortable();
			
			$( "#sortable" ).disableSelection();
			
			$(".delete_box").click(function() {
			var id = $(this).attr("id");
			var cat= $(this).attr("cat");
			var fl = $(this).attr("fl");
				bootbox.confirm("Are you sure?", function(result) {
					if(result) {
						window.location.assign(fl+"/process.php?cat="+ cat +"&id="+id);
					}
					else {
					
					}
				});
			});

		});
		
		$(function() {
	        $('.chosen-select').chosen();
	        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
	    });

		$(function() {
			$('.typehead').typeahead ({
				source: function(query, process) {
					$.ajax({
						url: 'ajax/source.php',
						type: 'POST',
						data: 'query=' + query,
						dataType: 'JSON',
						hightlight:false,
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
		<?php
		if($sidebar == 'analyst' && $sub_sidebar == 1) echo 'sortList: [[3,0]],';
		else if($sidebar == 'analyst' && $sub_sidebar == 4) echo 'sortList: [[2,1]],';
		?>
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
			echo ($sidebar == 'analyst')?'9':'0';
		?>: {
	        // disable it by setting the property sorter to false
	        sorter: false
	      },
	     <?php
			echo ($sidebar == 'analyst')?'1':'0';
		?>: {
	        // disable it by setting the property sorter to false
	        sorter: false
	      }

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
		




		});
function initialize(){

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

	$('.datepicker_month').datepicker({format:'dd-mm-yyyy', minViewMode:'1',}).on('changeDate', function(ev){
				$(this).datepicker('hide');
			});
}
	</script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
