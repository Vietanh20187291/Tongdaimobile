<aside class="box sidebar s_post <?php if(!empty($class)) echo  $class?> m-b-15 clearfix">
	<div class="p-l-10 p-r-10">
	<div class="title">
		<i class="fa fa-list-ul" aria-hidden="true"></i>
		<?php echo __('bài viết nổi bật',true)?>
	</div>
		<?php if(!empty($data)){?>
		<div class="inner">
			<?php
			$full_size = Configure::read('Post.size.post');
			$w = 332;
			$h = intval($w*$full_size[1]/$full_size[0]);
			foreach($data as $val){
				$item_post = $val['Post'];
				$item_cate = $val['PostCategory'];
				$url = array('controller'=>'posts','action'=>'index','lang'=>$item_post['lang'],'position'=>$item_cate['position']);
				$tmp = explode(',', $item_cate['path']);

				for($i=0;$i<count($tmp);$i++){
					$url = array_merge($url,array('slug'.$i=>$tmp[$i]));
				}
                $url['slug'.count($tmp)] = $item_post['slug'];
				$url['ext']='html';

				$link_attr = array('title'=>$item_post['meta_title'],'target'=>$item_post['target'],'class'=>'name font-bold');
				if($item_post['rel']!='dofollow') $link_attr['rel'] = $item_post['rel'];
				$link_img_attr = array_merge($link_attr,array('escape'=>false));
				$link_img_attr['class']='';
				?>
				<div class="pull-left">
                    <div style="width: 100%;">
					<?php
                            if ($item_post['user_id']!=14) {
                                echo $this->Html->link($this->OnewebVn->thumb('posts/' . $item_post['image'], array('alt' => $item_post['meta_title'], 'class' => 'img-responsive', 'width' => $w, 'height' => $h,'loading'=>'lazy')), $url, $link_img_attr);
                            }else {
                                echo $this->Html->link($this->html->image($item_post['image'], array('alt' => $item_post['meta_title'], 'class' => 'img-responsive','style'=>'height: 202px', 'width' => $w, 'height' => $h,'loading'=>'lazy')), $url, $link_img_attr);
                            }

                                ?>                    </div>
						<?php echo $this->Html->link($item_post['name'],$url,$link_attr) ?>
						<?php echo $this->Html->tag('p',$this->Text->truncate(trim(strip_tags($item_post['summary'])),90,array('exact'=>false)),array('class'=>'sumary')) ?>
					<hr>
				</div>
			<?php } ?>
		</div>
		<?php }?>
	</div>
</aside> <!--  end .box -->

