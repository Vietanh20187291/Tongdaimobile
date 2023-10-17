<div id="column_right">
	<!-- tab --> 
	<div id="action_top">
		<ul class="tabs">
    		<li><a href="#tab1"><?php echo __('Robots.txt',true)?></a></li>
    	</ul> <!-- end .tabs -->
	</div> <!--  end #action_top -->
	
	<div id="content">
		<?php echo $this->Form->create('Robot',array('url'=>array('controller'=>'sitemaps','action'=>'robots'),'id'=>'form','inputDefaults'=>array('label'=>false,'div'=>false)))?>
		
		<div class="tab_container">
			<div id="tab1" class="tab_content">
				<table class="add">
					<tr>
						<td><?php echo $this->Form->input('description',array('type'=>'textarea','class'=>'robot'))?></td>
					</tr>
				</table> <!-- end .add -->
			</div> <!-- end #tab1 -->
			
			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?><span></span></li>
			</ul> <!-- end .submit -->
			
		</div> <!-- end .tab_container -->
		
		<?php echo $this->Form->end();?>
	</div> <!--  end #content -->
</div> <!--  end #column_right -->