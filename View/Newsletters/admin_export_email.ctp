<?php echo $this->Html->script(array('cal','ckeditor/ckeditor','ckfinder/ckfinder'));?>
<script type="text/javascript">
jQuery(document).ready(function () {
	<?php 
		$date = date('m/d/Y');
		$start_date = date('m/d/Y',strtotime($date)-730*24*60*60);
	?>
	
	$('input.date').simpleDatepicker({ startdate: '<?php echo $start_date;?>', enddate: '<?php echo $date?>'});
});	
</script>
<div id="column_right">
	<!-- tab --> 
	<div id="action_top">
		<ul class="tabs">
    		<li><a href="#tab1"><?php echo __('Thông tin',true)?></a></li>
    	</ul> <!-- end .tabs -->
    		
    	
	</div> <!--  end #action_top -->
	
	<div id="content">
		<?php echo $this->Form->create('Newsletter',array('url'=>array('action'=>'exportEmail'),'type'=>'post','name'=>'export','inputDefaults'=>array('div'=>false,'label'=>false)))?>
		
		<div class="tab_container">
			
			
			<div id="tab1" class="tab_content">
				<table class="add one">
					<tr>
						<th>Module lấy Email: <span class="im">*</span></th>
						<td>
							<?php 
							if(!empty($oneweb_newsletter['enable'])){
								echo $this->Form->checkBox('Newsletters',array('value'=>'Newsletter'));
								echo $this->Form->label('Newsletters','Newsletters');
								echo '<br/>';
							}
								
								echo $this->Form->checkBox('Contact',array('value'=>'Contact'));
								echo $this->Form->label('Contact','Liên hệ');
								echo '<br/>';
							if(!empty($oneweb_product['order'])){
								echo $this->Form->checkBox('Order',array('value'=>'Order'));
								echo $this->Form->label('Order','Đơn hàng');
							}
							?>
							
							<div class="error-message" ><?php if(!empty($errorMessage)) echo $errorMessage;?></div>
						</td>
					</tr>
					<tr>
						<th>Chọn định dạng file export: <span class="im">*</span></th>
						<td>
						<?php 
							$type_options = array('email1,email2,email3,...'=>'email1,email2,email3,...','Firstname,Lastname,Email'=>'Firstname,Lastname,Email');
							echo $this->Form->radio('type_file',$type_options,array('legend'=>false,'value'=>$type_options['email1,email2,email3,...'],'class'=>'','separator'=>'<br/>'));
						?>
						</td>
					</tr>
					<th>Thời gian (năm):</th>
					<td> Từ
						<?php 
							/*$now = getdate(); 
							$year = $now['year'];
							$a_year = array();
							
							for($i=$year;$i>=$year-10;$i--){
								$a_year[$i]=$i;
							}
							
							echo $this->Form->input('year',array('type'=>'select','options'=>$a_year,'empty'=>'all','class'=>'small'));
							echo $this->Form->label('year','');
							echo $this->Form->input('from_date',array('type'=>'date','empty'=>array('day'=>'Day','month'=>'Month','year'=>"Year"),'class'=>'date'));*/
							
						?>
						<?php echo $this->Form->input('from_date',array('class'=>'small date','placeholder'=>'all'))?>
						Đến <?php echo $this->Form->input('to_date',array('class'=>'small date','placeholder'=>'all')); ?>
					</td>
					
					
				</table> <!-- end .add -->
			</div> <!-- end #tab1 -->
			
			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Export',true),array('name'=>'export','div'=>false))?><span></span></li>			
			</ul> <!-- end .submit -->
			
		</div> <!-- end .tab_container -->
		
		<?php echo $this->Form->end();?>
	</div> <!--  end #content -->
</div> <!--  end #column_right -->


