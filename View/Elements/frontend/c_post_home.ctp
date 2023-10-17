<div id="show_post_home" class="col-xs-12 col-sm-8">
	<div class="box-content bg_white">
		<div class="title">
			<h2><span class="col-xs-12 text-uppercase font-weight-bold color_blue">Tin tức - tư vấn</span></h2>
			<div class="col-xs-12 line_title"></div>
		</div>
		<div class="home_box_post col-xs-12 m-t-15 m-b-15">
			<div class="row">
				<div class="col-xs-12 col-sm-5">
					<?php $w = 332;
						$limit = 350;
						//Kich thước ảnh thumbnail
						$full_size = $oneweb_post['size']['post'];
						$h = intval($w*$full_size[1]/$full_size[0]);
						$item_post = $data[0]['Post'];
						$item_cate = $data[0]['PostCategory'];
						$url = array('controller'=>'posts','action'=>'index','lang'=>$item_post['lang'],'position'=>$item_cate['position']);
						
						$tmp = explode(',', $item_cate['path']);
						for($i=0;$i<count($tmp);$i++){
							$url['slug'.$i]=$tmp[$i];
						}
						$url['slug'.count($tmp)] = $item_post['slug'];
						$url['ext']='html';
						
						$link_attr = array('title'=>$item_post['meta_title'],'target'=>$item_post['target'],'class'=>'name font-weight-bold');
						if($item_post['rel']!='dofollow') $link_attr['rel'] = $item_post['rel'];
						$link_img_attr = array_merge($link_attr,array('escape'=>false));
						$link_img_attr['class']='';
					?>
					<div class="item <?php if(!empty($class)) echo $class;?>">
						<div class="thumb">
								<?php echo $this->Html->link($this->OnewebVn->thumb('posts/'.$item_post['image'],array('alt'=>$item_post['meta_title'],'class'=>'img-responsive','width'=>$w,'height'=>$h)),$url,$link_img_attr)?>
						</div> <!--  end .thumb -->
						<?php 
							echo $this->Html->link($this->Text->truncate($item_post['name'],60,array('exact'=>false)),$url,$link_attr);
							if(!empty($datetime)) echo $this->Html->tag('p',date('d/m/Y',$item_post['created']),array('class'=>'datetime'));
							if(!empty($limit)) echo $this->Html->tag('p',$this->Text->truncate(trim(strip_tags($item_post['summary'])),$limit,array('exact'=>false)),array('class'=>'sumary'));
						?>
					</div>
				</div>
				<div class="col-xs-12 col-sm-7 p_right">
					<?php
					$count = count($data);
					if($count >5) $limit_item = 5;
					else $limit_item = $count;
					for($k=1;$k<$limit_item;$k++){
					$item_post = $data[$k]['Post'];
					$item_cate = $data[$k]['PostCategory'];
					$url = array('controller'=>'posts','action'=>'index','lang'=>$item_post['lang'],'position'=>$item_cate['position']);
					
					$tmp = explode(',', $item_cate['path']);
					for($i=0;$i<count($tmp);$i++){
						$url['slug'.$i]=$tmp[$i];
					}
					$url['slug'.count($tmp)] = $item_post['slug'];
					$url['ext']='html';
					
					$link_attr = array('title'=>$item_post['meta_title'],'target'=>$item_post['target'],'class'=>'name font-weight-bold');
					if($item_post['rel']!='dofollow') $link_attr['rel'] = $item_post['rel'];
					$link_img_attr = array_merge($link_attr,array('escape'=>false));
					$link_img_attr['class']='';
					$w = 125;
					$limit = 130;
					//Kich thước ảnh thumbnail
					$full_size = $oneweb_post['size']['post'];
					$h = intval($w*$full_size[1]/$full_size[0]);
					?>
					<div class="row item <?php if(!empty($class)) echo $class;?>">
						<div class="thumb col-xs-4">
								<?php echo $this->Html->link($this->OnewebVn->thumb('posts/'.$item_post['image'],array('alt'=>$item_post['meta_title'],'class'=>'img-responsive','width'=>$w,'height'=>$h)),$url,$link_img_attr)?>
						</div> <!--  end .thumb -->
						<div class="col-xs-8">
							<?php 
								echo $this->Html->link($this->Text->truncate($item_post['name'],60,array('exact'=>false)),$url,$link_attr);
								if(!empty($datetime)) echo $this->Html->tag('p',date('d/m/Y',$item_post['created']),array('class'=>'datetime'));
								if(!empty($limit)) echo $this->Html->tag('p',$this->Text->truncate(trim(strip_tags($item_post['summary'])),$limit,array('exact'=>false)),array('class'=>'sumary'));
							?>
						</div>
					</div>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="fanpage_facebook" class="col-xs-12 col-sm-4">
	<div class="box-content bg_white">
		<div class="title">
			<h2><span class="col-xs-12 text-uppercase font-weight-bold color_blue"><?php echo __('Video')?></span></h2>
			<div class="col-xs-12 line_title"></div>
		</div>
		<div class="col-xs-12  p-y-15 video">
			<iframe width="100%" height="308" src="https://www.youtube.com/embed/<?php echo $a_videos_pos1[0]['Video']['youtube']?>?rel=0" allowfullscreen></iframe>
		</div>
	</div>
</div>
