<?php echo $this->Html->docType('html5');?>
<html>
<head>
	<?php
		echo $this->Html->charset();
		echo $this->Html->meta('favicon.ico',$this->Html->url('/webroot/favicon.ico'),array('type'=>'icon'));
//		echo $this->Html->tag('title','Thiết Kế Web OneWeb.vn');
		echo $this->Html->tag('title','Công ty thiết kế web URL | '.$title_for_layout);
		echo "<meta name='robots' content='noindex,nofollow'>";
		echo "<meta name='author' content='Công ty thiết kế web URL'>";
		echo $this->Html->css('admin/styles.css');
		echo $this->Html->script(array('admin/jquery','admin/nav','admin/hover','admin/jconfirmaction.jquery','admin/oneweb'));
	echo $this->Html->css(array('date_picker/daterangepicker.css','font-awesome.min.css'));
	echo $this->Html->script(array('Chart','date_picker/moment.min','date_picker/daterangepicker'));
	?>
</head>
<style>
	/** daterangepicker **/
	.daterangepicker.ltr .calendar.left {
		margin-right: 24px!important;
	}
	.daterangepicker.ltr .calendar.right {
		margin-right: 34px!important;
	}
	.dropdown-menu{
		box-shadow: none;
		display: none;
		float: left;
		font-size: 12px;
		left: 0;
		list-style: none;
		padding: 0;
		position: absolute;
		text-shadow: none;
		top: 100%;
		z-index: 9998;
		border: 1px solid #D9DEE4;
		border-top-left-radius: 0;
		border-top-right-radius: 0;
	}
	.daterangepicker{
		display: none;
	}
	.daterangepicker .ranges li {
		color: #73879C
	}
	.daterangepicker .ranges li.active,
	.daterangepicker .ranges li:hover {
		background: #536A7F;
		border: 1px solid #536A7F;
		color: #fff
	}
	.daterangepicker .input-mini {
		background-color: #eee;
		border: 1px solid #ccc;
		box-shadow: none !important
	}
	.daterangepicker .input-mini.active {
		border: 1px solid #ccc
	}
	.daterangepicker select.monthselect,
	.daterangepicker select.yearselect,
	.daterangepicker select.hourselect,
	.daterangepicker select.minuteselect,
	.daterangepicker select.secondselect,
	.daterangepicker select.ampmselect {
		font-size: 12px;
		padding: 1px;
		height: auto;
		margin: 0;
		cursor: default;
		height: 30px;
		border: 1px solid #ADB2B5;
		line-height: 30px;
		border-radius: 0px !important
	}
	.daterangepicker select.monthselect {
		margin-right: 2%
	}
	.daterangepicker td.in-range {
		background: #E4E7EA;
		color: #73879C
	}
	.daterangepicker td.active,
	.daterangepicker td.active:hover {
		background-color: #536A7F;
		color: #fff
	}
	.daterangepicker th.available:hover {
		background: #eee;
		color: #34495E
	}
	.daterangepicker:before,
	.daterangepicker:after {
		content: none
	}
	.daterangepicker .calendar.single {
		margin: 0 0 4px 0
	}
	.daterangepicker .calendar.single .calendar-table {
		width: 224px;
		padding: 0 0 4px 0 !important
	}
	.daterangepicker .calendar.single .calendar-table thead tr:first-child th {
		padding: 8px 5px
	}
	.daterangepicker .calendar.single .calendar-table thead th {
		border-radius: 0
	}
	.daterangepicker.picker_1 {
		color: #fff;
		background: #34495E
	}
	.daterangepicker.picker_1 .calendar-table {
		background: #34495E
	}
	.daterangepicker.picker_1 .calendar-table thead tr {
		background: #213345
	}
	.daterangepicker.picker_1 .calendar-table thead tr:first-child {
		background: #1ABB9C
	}
	.daterangepicker.picker_1 .calendar-table td.off {
		background: #34495E;
		color: #999
	}
	.daterangepicker.picker_1 .calendar-table td.available:hover {
		color: #34495E
	}
	.daterangepicker.picker_2 .calendar-table thead tr {
		color: #1ABB9C
	}
	.daterangepicker.picker_2 .calendar-table thead tr:first-child {
		color: #73879C
	}
	.daterangepicker.picker_3 .calendar-table thead tr:first-child {
		color: #fff;
		background: #1ABB9C
	}
	.daterangepicker.picker_4 .calendar-table thead tr:first-child {
		color: #fff;
		background: #34495E
	}
	.daterangepicker.picker_4 .calendar-table td,
	.daterangepicker.picker_4 .calendar-table td.off {
		background: #ECF0F1;
		border: 1px solid #fff;
		border-radius: 0
	}
	.daterangepicker.picker_4 .calendar-table td.active {
		background: #34495E
	}
	.calendar-exibit .show-calendar {
		float: none;
		display: block;
		position: relative;
		background-color: #fff;
		border: 1px solid #ccc;
		margin-bottom: 20px;
		border: 1px solid rgba(0, 0, 0, 0.15);
		overflow: hidden
	}
	.calendar-exibit .show-calendar .calendar {
		margin: 0 0 4px 0
	}
	.calendar-exibit .show-calendar.picker_1 {
		background: #34495E
	}
	.calendar-exibit .calendar-table {
		padding: 0 0 4px 0
	}

</style>
<body>
	<div id="notice_sound"></div>

	<div id="wrapper">
		<?php echo $this->element('backend/header') ?>

		<div id="main">
			<?php
				echo $this->Session->flash();
				echo $this->element('backend/sidebar');
				echo $content_for_layout;
//				echo $this->element('sql_dump');
			?>
		</div> <!--  end #main -->
	</div> <!--  end #wrapper -->

	<script type="text/javascript">
		//Kiểm tra xem có liên hệ hoặc đơn hàng mới không
		function check(){
			$.ajax({
				type:'post',
				url:'<?php echo $this->Html->url(array('controller'=>'pages','action'=>'ajaxCheck'))?>',
				dataType: 'json',
				success:function(result){
					if(result.product!=0 || result.post!=0) {		//Đơn hàng và liên hệ
						$("#header .notice").show();
						notice = 'Bạn có \n\r';
						if(result.product!=0){
							notice+=result.product+' <?php echo __('đơn hàng',true)?>';
							if(result.post!=0) notice += ' và ';
						}
						if(result.post!=0) notice+=result.post+' <?php echo __('liên hệ',true)?>';
						notice += ' mới';
						$("#header .notice").attr('title',notice);
					}
					if(result.comment!=0)	$("#header #comment_notice").addClass('new');			//Comment
					if(result.sound==true) $("#notice_sound").html('<embed src="<?php echo $this->Html->url('/notice.swf');?>" height="1" width="1" repeat="100"></embed>');
					else $("#notice_sound").html('');

					if(result.modified==true) $("#left_middle .del_cache").show();
				}
			});
		}

		//Thay đổi slug, meta_title khi nhập xong tên
		function getFieldByName(model,name){
			<?php if(!empty($oneweb_seo)){?>
			if(name==null) name = 'Name';
			val = $("#"+model+name).val();

			//Slug
			flag_slug = false;
			if($("#"+model+'Slug').val()=='') flag_slug = true;
			else {
				var c = confirm("Bạn có muốn thay đổi lại Slug theo tên mới không");
				if(c==true) flag_slug = true;
			}


			if(flag_slug==true){
				$.ajax({
					type:'post',
					url:'<?php echo $this->Html->url(array('controller'=>$this->params['controller'],'action'=>'ajaxGetSlug'))?>',
					data:'val='+val,
					beforeSend:function(){
						$("#loading").show();
					},
					success:function(result){
						$("#"+model+'Slug').val(result);
						$("#loading").hide();
					}
				});
			}

			//Meta title
			if(name=="Name"){
				flag_title = false;
				if($("#"+model+'MetaTitle').val()=='') flag_title = true;
				else {
					var c = confirm("Bạn có muốn thay đổi lại Meta Tile theo tên mới không");
					if(c==true) flag_title = true;
				}

				if(flag_title==true) $("#"+model+'MetaTitle').val(val);
			}
			<?php }?>
		}
		function init_daterangepicker() {

			if( typeof ($.fn.daterangepicker) === 'undefined'){ return; }
			console.log('init_daterangepicker');

			var cb = function(start, end, label) {
				console.log(start.toISOString(), end.toISOString(), label);
				$('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
				load_data(start.format('DD/MM/YY'),end.format('DD/MM/YY'));
			};

			var optionSet1 = {
				startDate: moment().subtract(365, 'days'),
				endDate: moment(),
				minDate: '01/01/2018',
				maxDate: moment().endOf('month'),
				dateLimit: {
					days: 90
				},
				showDropdowns: true,
				showWeekNumbers: true,
				timePicker: false,
				timePickerIncrement: 1,
				timePicker12Hour: true,
				ranges: {
					'Hôm nay': [moment(), moment()],
					'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'7 ngày gần nhất': [moment().subtract(6, 'days'), moment()],
					'30 ngày gần nhất': [moment().subtract(29, 'days'), moment()],
					'Tuần này': [moment().startOf('week'), moment().endOf('week')],
					'Tuần trước': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
					'Tháng này': [moment().startOf('month'), moment().endOf('month')],
					'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				},
				opens: 'right',
				buttonClasses: ['btn btn-primary'],
				applyClass: 'btn-small btn-primary',
				cancelClass: 'btn-small',
				format: 'DD/MM/YYYY',
				separator: ' to ',
				locale: {
					applyLabel: 'Chọn',
					cancelLabel: 'Xóa',
					fromLabel: 'From',
					toLabel: 'To',
					customRangeLabel: 'Tùy chọn',
					daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
					monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
					firstDay: 1
				}
			};
			$('#reportrange span').html(moment().subtract(29, 'days').format('DD/MM/YYYY') + ' - ' + moment().format('DD/MM/YYYY'));
			load_data(moment().subtract(29, 'days').format('DD/MM/YY'),moment().format('DD/MM/YY'));
			$('#reportrange').daterangepicker(optionSet1, cb);
			$('#reportrange').on('show.daterangepicker', function() {
				console.log("show event fired");
			});
			$('#reportrange').on('hide.daterangepicker', function() {
				console.log("hide event fired");
			});
			$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
				console.log("apply event fired, start/end dates are " + picker.startDate.format('DD/MM/YYYY') + " to " + picker.endDate.format('DD/MM/YYYY'));
			});
			$('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
				console.log("cancel event fired");
			});
			$('#options1').click(function() {
				$('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
			});
			$('#destroy').click(function() {
				$('#reportrange').data('daterangepicker').remove();
			});

		}
	</script>
</body>
</html>