<?php if(!empty($a_posts_c)){
	echo $this->Html->script('tag/url_tag');
	foreach($a_posts_c as $val){
		$item_post = $val['Post'];
		$item_cate = $val['PostCategory'];
// 		$url = array('controller'=>'posts','action'=>'view','lang'=>$lang,'position'=>$item_cate['position'],'slug_cate'=>$item_cate['slug'],'slug'=>$item_post['slug'],'ext'=>'html');
		$url = array('controller'=>'posts','action'=>'index','lang'=>$item_post['lang'],'position'=>$item_cate['position']);
		
		
		$tmp = explode(',', $item_cate['path']);
		for($i=0;$i<count($tmp);$i++){
			$url['slug'.$i]=$tmp[$i];
		}
		$url['slug'.count($tmp)] = $item_post['slug'];
		$url['ext']='html';
		
		$link_attr = array('title'=>$item_post['meta_title'],'target'=>$item_post['target'],'class'=>'name tooltip');
		if($item_post['rel']!='dofollow') $link_attr['rel'] = $item_post['rel'];
		
		$link_img_attr = array_merge($link_attr,array('escape'=>false));
		$link_img_attr['class']='tooltip';
		$link_more_attr['title'] = __('Chi tiết',true);
		$link_more_attr['class'] = 'more tooltip';
		
		
		
	?>			
		<div class="box_post ajax_item">
			<div class="box_post_bottom">
				<div class="box_post_middle">
					<div class="thumb">
						<?php echo $this->Html->link($this->OnewebVn->thumb('posts/'.$item_post['image'],array('alt'=>$item_post['meta_title'],'width'=>120,'height'=>96)),$url,$link_img_attr)?>
					</div>
					<div class="box_post_info">
						<h2>
							<?php 
								echo $this->Html->link($item_post['name'],$url,array('title'=>$item_post['meta_title'],'class'=>'name'));
							?>
						</h2>
						<span class="date_time"><?php echo date('G:i | d/m/Y',($item_post['created']));?></span>
						<div class="description">
							<?php echo $item_post['summary']; ?>
						</div>
					</div> <!-- end .box_post_info --> 
				</div> <!-- end .box_post_middle --> 
			</div> <!-- end .box_post_bottom --> 
		</div> <!-- end .box_post --> 			
	<?php } ?>
	<div class="clear"></div>
	<div class="paginator">
		<?php 
		echo $this->Html->tag('span',$current_page_c.'/'.$all_pages_c);
		if($current_page_c!=1) echo $this->Html->tag('span',$this->Html->link('&lt;&lt;','javascript:;',array('title'=>__('Trang đầu',true),'onclick'=>"getPost(1);backTopAtTag()",'escape'=>false)));
		for($i=0;$i<$all_pages_c;$i++){
			$page = $i+1;
			if($current_page_c == $page) echo $this->Html->tag('span',$page,array('class'=>'current'));
			else echo $this->Html->tag('span',$this->Html->link($i+1,'javascript:;',array('onclick'=>"getPost($page);backTopAtTag()")));
		}
		if($current_page_c!=$all_pages_c) echo $this->Html->tag('span',$this->Html->link('&gt;&gt;','javascript:;',array('title'=>__('Trang cuối',true),'onclick'=>"getPost($all_pages_c);backTopAtTag()",'escape'=>false)));
		?>
	</div>
<?php }else{ echo __('Không tim thấy bài viết nào',true); }?>