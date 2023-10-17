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
			echo $this->Form->create('SeoLink',array('type'=>'file','id'=>'form','inputDefaults'=>array('label'=>false,'div'=>false)));
			echo $this->Form->input('id');
		?>

		<div class="tab_container">
			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?><span></span></li>
				<!-- <li><?php //echo $this->Form->submit(__('Lưu & Thêm mới',true),array('name'=>'save_add','div'=>false))?><span></span></li> -->
				<li><?php echo $this->Form->submit(__('Lưu và Thoát',true),array('name'=>'save_exit','div'=>false))?><span></span></li>
			</ul> <!-- end .submit -->

			<div id="tab1" class="tab_content">
				<table class="add">
					<table class="add">
					<tr>
						<th><?php echo $this->Form->label('link',__('Link',true))?></th>
						<td><?php echo $this->Form->input('link',array('type'=>'text','readonly'=>true,'class'=>'larger','required'=>false))?></td>
					</tr>

					<tr>
						<?php if(!empty($this->request->data['SeoLink']['content'])) $content =  $this->request->data['SeoLink']['content'];
						else {
							if($this->request->data['SeoLink']['model'] == 'Project') $content = unserialize($a_config_c['Config']['project']);
							else if($this->request->data['SeoLink']['model'] == 'Competition') $content = unserialize($a_config_c['Config']['competition']);
							else if($this->request->data['SeoLink']['model'] == 'Post') $content = unserialize($a_config_c['Config']['post']);
							$content = $content['description'];
						}?>
                         <th>
                            <?php echo $this->Form->label('content',__('Mô tả',true))?></th>
						<td><?php echo $this->Form->input('content',array('class'=>'medium', 'value'=>$content));
						echo $this->CkEditor->create('content',array('toolbar'=>'standard'));
						?></td>
					</tr>
                    <?php if($this->request->data['SeoLink']['model'] == 'Project' || $this->request->data['SeoLink']['model'] == 'Competition'): ?>
                    <tr>
                        <?php if(!empty($this->request->data['SeoLink']['post_content'])) $content =  $this->request->data['SeoLink']['post_content'];
                        else {
                            if($this->request->data['SeoLink']['model'] == 'Project') $content = $a_config_c['Config']['info_project'];
                            else if($this->request->data['SeoLink']['model'] == 'Competition') $content = $a_config_c['Config']['info_competition'];
                            $content = '';
                        }
                        ?>
                        <th><?php echo $this->Form->label('post_content',__('Bài viết trong link',true))?></th>
                        <td><?php echo $this->Form->textarea('post_content',array('class'=>'medium', 'value'=>$content));
                            echo $this->CkEditor->create('post_content',array('toolbar'=>'standard'));
                            ?></td>
                    </tr>
                    <?php endif; ?>

					<tr>
						<th><?php echo $this->Form->label('meta_title',__('Meta title',true))?></th>
						<td><?php echo $this->Form->input('meta_title',array('class'=>'larger','required'=>false))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('meta_keyword',__('Meta keyword',true))?></th>
						<td><?php echo $this->Form->input('meta_keyword',array('class'=>'larger'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('meta_description',__('Meta description',true))?></th>
						<td><?php echo $this->Form->input('meta_description',array('class'=>'medium'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('meta_robots',__('Meta robots',true))?></th>
						<td><?php echo $this->Form->input('meta_robots',array('type'=>'select','options'=>array('index,follow'=>'index,follow','noindex,nofollow'=>'noindex,nofollow','index,nofollow'=>'index,nofollow','noindex,follow'=>'noindex,follow'),'class'=>'medium'))?></td>
					</tr>
					<!-- <tr>
						<th><?php //echo $this->Form->label('rel',__('Rel',true))?></th>
						<td><?php //echo $this->Form->input('rel',array('type'=>'select','options'=>array('dofollow'=>'dofollow','nofollow'=>'nofollow'),'class'=>'medium'))?></td>
					</tr> -->
				</table> <!-- end .add -->
			</div> <!-- end #tab1 -->

			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?><span></span></li>
				<!-- <li><?php //echo $this->Form->submit(__('Lưu & Thêm mới',true),array('name'=>'save_add','div'=>false))?><span></span></li> -->
				<li><?php echo $this->Form->submit(__('Lưu & Thoát',true),array('name'=>'save_exit','div'=>false))?><span></span></li>
				<li><?php echo $this->Html->link(__('Thoát',true),(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('class'=>'exit'))?></li>
			</ul> <!-- end .submit -->
		</div> <!-- end .tab_container -->
		<?php echo $this->Form->end();?>
	</div> <!--  end #content -->
</div> <!--  end #column_right -->