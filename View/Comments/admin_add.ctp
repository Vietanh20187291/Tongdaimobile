<?php echo $this->Html->script(array('ckeditor/ckeditor','ckfinder/ckfinder'));?>

<div id="column_right">
	<!-- tab -->
	<div id="action_top">
		<ul class="tabs">
				<li><a href="#tab1"><?php echo __('Thông tin',true)?></a></li>
			</ul> <!-- end .tabs -->

			<ul class="action_top_2">
				<li><?php echo $this->Html->link('&nbsp;',(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('title'=>__('Thoát',true),'class'=>'exit','escape'=>false))?></li>
			</ul> <!-- end .action_top_2 -->
	</div> <!--  end #action_top -->

	<div id="content">
		<?php
			$item_comment = $a_comment_c['Comment'];
			echo $this->Form->create('Comment',array('type'=>'file','id'=>'form','inputDefaults'=>array('label'=>false,'div'=>false)));
			echo $this->Form->input('parent_id',array('type'=>'hidden','value'=>$_GET['parent_id']));
			echo $this->Form->input('model',array('type'=>'hidden','value'=>$item_comment['model']));
			echo $this->Form->input('item_id',array('type'=>'hidden','value'=>$item_comment['item_id']));
		?>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
				<table class="add">
					<tr>
						<th><?php echo __('Họ tên',true)?></th>
						<td><?php echo $item_comment['name']?></td>
					</tr>
					<tr>
						<th><?php echo __('Email',true)?></th>
						<td><?php echo $item_comment['email']?></td>
					</tr>
					<tr>
						<th><?php echo __('SDT',true)?></th>
						<td><?php echo $item_comment['phone']?></td>
					</tr>
					<tr>
						<th><?php echo __('Nội dung',true)?></th>
						<td><?php echo $item_comment['description'] ?></td>
					</tr>
					<tr>
                        <th><?php echo __('Đánh giá',true)?></th>
                        <td><?php echo $item_comment['star'].' ✯'?></td>
					</tr>
					<tr>
						<th><?php echo __('Ngày tạo',true)?></th>
						<td><?php echo date('d/m/Y - H:i:s',$item_comment['created'])?></td>
					</tr>
					<tr>
						<th>IP/Proxy</th>
						<td>
							<?php
								echo $item_comment['ip'];
								if(!empty($item_comment['proxy'])) echo '/'.$item_comment['proxy'];
								echo '&nbsp;&nbsp;&nbsp;'.$this->Html->link('Kiểm tra IP','http://whois.domaintools.com/'.$item_comment['ip'],array('title'=>'Kiểm tra IP','class'=>'tooltip','target'=>'_blank'))
							?>
						</td>
					</tr>
					<tr>
						<th class="title"><h3><?php echo __('Trả lời',true)?></h3></th>
					</tr>
					<tr class="hidden">
						<th><?php echo $this->Form->label('name',__('Họ tên',true))?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('name',array('class'=>'larger', 'value' => 'QTV'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('description',__('Nội dung',true))?> <span class="im">*</span></th>
						<td>
							<?php
								echo $this->Form->input('description', array('type'=> 'textarea','div'=>'description','required'=>false));
								echo $this->CkEditor->create('Comment.description',array('toolbar'=>'user'));
							?>
						</td>
					</tr>
					<!-- <tr>
						<th><?php echo $this->Form->label('like',__('Thích',true))?></th>
						<td><?php echo $this->Form->input('like',array('class'=>'small','value'=>0))?></td>
					</tr> -->
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