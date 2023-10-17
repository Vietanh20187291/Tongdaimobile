<article>
	<div class="box_info_page">
		<header><h1><span class="title"><?php echo __('Những câu hỏi thường gặp',true)?></span></h1></header>
		
		<?php if(!empty($a_faq_configs_c['faq_description'])){?>
		<div class="box_info_page">
			<div class="des">
				<?php echo $a_faq_configs_c['faq_description'] ?>
			</div> <!--  end .des -->
					
			<div class="top"></div>
			<div class="bottom"></div>
		</div> <!--  end .box_info_page -->
		<?php }?>
	</div> <!--  end .box_info_page -->
	
	<div class="box_content_faq">
		<div class="title">
			<h2><span><?php echo __('Câu hỏi',true)?></span></h2>
		</div> <!--  end .title -->
		
		<div class="des">
			<?php if(!empty($a_most_faqs_c)){?>
			<h3><?php echo __('Thường gặp nhất',true)?></h3>
			<ul class="question">
				<?php foreach($a_most_faqs_c as $val){
					$item_most_faq = $val['Faq'];
				?>
				<li><?php echo $this->Html->link($item_most_faq['question'],'#'.$item_most_faq['slug'],array('title'=>$item_most_faq['question'],'class'=>'tooltip'))?></li>
				<?php }?>
			</ul>
			<?php }?>
			
			<?php if(!empty($a_faqs_c)){
				foreach($a_faqs_c as $val){
					$item_cate = $val['FaqCategory'];
			?>
			<h3><?php echo $item_cate['name']?></h3>
			<ul class="question">
				<?php foreach ($val['Faq'] as $val2){?>
				<li><?php echo $this->Html->link($val2['question'],'#'.$val2['slug'],array('title'=>$val2['question'],'class'=>'tooltip'))?></li>
				<?php }?>
			</ul>
			<?php }}?>
		</div> <!--  end .des -->
		
		<div class="line"></div>
		
		<?php if(!empty($a_faqs_c)){
			foreach($a_faqs_c as $val){
				$item_cate = $val['FaqCategory'];
		?>
		<div class="title">
			<h2><span><?php echo $item_cate['name']?></span></h2>
		</div> <!--  end .title -->
		
		<div class="des">
			<?php foreach($val['Faq'] as $val2){?>
			<h4 id="<?php echo $val2['slug']?>"><?php echo $val2['question']?></h4>
			<div class="answer">
				<?php echo $val2['answer']?> 
			</div> <!--  end .answer -->
			<?php }?>
		</div> <!--  end .des -->
		<?php }}?>
				
		<div class="top"></div>
		<div class="bottom"></div>
	</div> <!--  end .box_content_faq -->
</article>