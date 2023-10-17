<?php echo $this->Html->script(array('ckeditor/ckeditor','ckfinder/ckfinder'));?>
<?php echo $this->Html->script(array('jquery-ui/jquery-ui-1.10.3.custom'));?>
<script type="text/javascript">
$(function() {
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#autocomplete" )
      // don't navigate away from the field on tab when selecting an item
      .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data( "ui-autocomplete" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
        	$.ajax({
        	url: "<?php echo $this->Html->url(array('controller'=>'member_messages', 'action'=>'ajaxLoadMember','admin'=>true))?>",
        	dataType: "json",
        	data: {
        	featureClass: "P",
        	style: "full",
        	maxRows: 12,
        	name_startsWith: request.term
        	},
        	success: function( data ) {
	        	response( $.ui.autocomplete.filter(
	        			data, extractLast( request.term ) ) );
        	}
        	});
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
  });
  </script>

<div id="column_right">
	<!-- tab --> 
	<div id="action_top">
		<ul class="tabs">
   			<li><a href="#tab1"><?php echo __('Thông báo cho từng thành viên',true)?></a></li>
   		</ul> <!-- end .tabs -->
    		
    	<ul class="action_top_2">
    		<li><?php echo $this->Html->link('&nbsp;',(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('title'=>__('Thoát',true),'class'=>'exit','escape'=>false))?></li>
   		</ul> <!-- end .action_top_2 -->
	</div> <!--  end #action_top -->
	
	<div id="content">
		<?php 
			echo $this->Form->create('',array('type'=>'file','id'=>'form','inputDefaults'=>array('label'=>false,'div'=>false)));
		?>
		
		<div class="tab_container">
			<div id="tab1" class="tab_content">
				<table class="add">
					
					<tr>
						<th><?php echo $this->Form->label('title',__('Tiêu đề',true))?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('title',array('class'=>'larger'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('member_receive',__('Thành viên',true))?></th>
						<td><?php echo $this->Form->input('member_receive',array('type'=>'text','class'=>'medium','required'=>false,'id'=>'autocomplete')); ?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('message',__('Nội dung',true))?> <span class="im">*</span></th>
						<td>
							<?php 
								echo $this->Form->input('message', array('type'=> 'textarea','div'=>'description','required'=>false));
								echo $this->CkEditor->create('MemberMessage.message',array('toolbar'=>'user'));	
							?>
						</td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('status',__('Kích hoạt',true))?></th>
						<td><?php echo $this->Form->checkbox('status',array('checked'=>true))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('created',__('Ngày tạo',true))?></th>
						<td>
							<?php 
								echo $this->Form->input('created',array('type'=>'date','dateFormat'=>'DMY','maxYear'=>date('Y')+1,'minYear'=>date('Y')-10)).'&nbsp;';
								echo $this->Form->input('created',array('type'=>'time','timeFormat'=>'24'));
							?>
						</td>
					</tr>
				</table> <!-- end .add -->
			</div> <!-- end #tab1 -->
			
			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?></li>
				<li><?php echo $this->Form->submit(__('Lưu & Thêm mới',true),array('name'=>'save_add','div'=>false))?></li>
				<li><?php echo $this->Form->submit(__('Lưu & Thoát',true),array('name'=>'save_exit','div'=>false))?></li>
				<li><?php echo $this->Html->link(__('Thoát',true),array('action'=>'index'),array('class'=>'exit'))?></li>
			</ul> <!-- end .submit -->
			
		</div> <!-- end .tab_container -->
		
		<?php echo $this->Form->end();?>
	</div> <!--  end #content -->
</div> <!--  end #column_right -->