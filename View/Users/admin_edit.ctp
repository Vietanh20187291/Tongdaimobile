<div id="column_right">
	<!-- tab -->
	<div id="action_top">
		<ul class="tabs">
				<li><a href="#tab1">Thông tin</a></li>
			</ul> <!-- end .tabs -->

			<ul class="action_top_2">
				<li><?php echo $this->Html->link('&nbsp;',(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('title'=>'Thoát','class'=>'exit','escape'=>false))?></li>
			</ul> <!-- end .action_top_2 -->
	</div> <!-- end #action_top -->

	<div id="content">
		<?php
			echo $this->Form->create('User',array('id'=>'form','url'=>array('action'=>'edit','?'=>array('url'=>(!empty($_GET['url']))?urldecode($_GET['url']):'')),'inputDefaults'=>array('label'=>false,'div'=>false)));
			echo $this->Form->input('id');
		?>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
				<table class="add">
					<tr>
						<th><?php echo $this->Form->label('username','Tài khoản')?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('username',array('class'=>'medium','disabled'=>true))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('name','Họ tên')?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('name',array('class'=>'medium'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('email','Email')?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('email',array('class'=>'medium','disabled'=>true))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('password','Mật khẩu mới')?></th>
						<td><?php echo $this->Form->input('password',array('class'=>'medium','required'=>false))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('group_id','Nhóm')?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('group_id',array('type'=>'select','options'=>array('1'=>'admin', '2'=>'staff'),'empty'=>'Chọn nhóm','class'=>'medium','required'=>true))?></td>
					</tr>
<!--                    --><?php //if($a_user_c['User']['group_id'] == 1){?>
                        <tr>
                            <?php $role =  array(
                                '1'=>'Xem tất cả',
                                '2'=>'Xem sửa mục bài viết',
                                '3'=>'Xem sửa sản phẩm',
                                '4'=>'xem sửa đơn hàng',
                            ); ?>
                            <th><?php echo __('Phân quyền',true)?></th>
                            <td class="display">
                                <ul>
                                    <?php foreach($role as $key=>$val){?>
                                        <li>
                                            <?php
                                            echo $this->Form->checkBox('pos_'.$key);
                                            echo $this->Form->label('pos_'.$key,__($val,true));
                                            ?>
                                        </li>
                                    <?php }?>
                                </ul> <!-- end .display -->
                            </td>
                        </tr>
<!--                    --><?php //}?>
				</table> <!-- end .add -->
			</div> <!-- end #tab1 -->

			<ul class="submit">
				<li><?php echo $this->Form->submit('LƯU',array('name'=>'save','div'=>false))?></li>
				<li><?php echo $this->Form->submit('LƯU & THOÁT',array('name'=>'save_exit','div'=>false))?></li>
				<li><?php echo $this->Html->link('Thoát',(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('title'=>'Thoát','class'=>'exit'))?></li>
			</ul> <!-- end .submit -->

		</div> <!-- end .tab_container -->

		<?php echo $this->Form->end();?>
	</div> <!--  end #content -->
</div> <!--  end #column_right -->
