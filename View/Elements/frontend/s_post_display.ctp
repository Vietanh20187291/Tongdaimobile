<aside class="box s_post <?php if(!empty($class)) echo  $class?>">
	<p class="title"><span><?php echo __($oneweb_post['display'][$position],true)?></span></p>
	
	<?php if(!empty($run)){?>
	<?php }?>
	
	<ul id="show_post_<?php echo $position ?>">
		<?php 
		foreach($data as $val){
			$item_post = $val['Post'];
			$item_cate = $val['PostCategory'];
			$url = array('controller'=>'posts','action'=>'index','lang'=>$item_post['lang']);
			
			$tmp = $item_post['slug'];
			for($i=0;$i<count($tmp);$i++){
				$url['slug'.$i]=$tmp;
			}
			$url['ext']='html';
			
			$link_attr = array('title'=>$item_post['meta_title'],'target'=>$item_post['target'],'class'=>'name');
			if($item_post['rel']!='dofollow') $link_attr['rel'] = $item_post['rel'];
			
			echo $this->Html->tag('li',
				$this->Html->link($item_post['name'],$url,$link_attr).
				$this->Html->tag('p',$this->Text->truncate(trim(strip_tags($item_post['summary'])),90,array('exact'=>false)),array('class'=>'sumary'))
			);
		}
		?>
	</ul>
</aside> <!--  end .box -->	