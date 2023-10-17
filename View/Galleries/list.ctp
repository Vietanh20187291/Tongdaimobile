<article class="box_content">
	<header class="title">
		<div class="title_right">
			<div class="title_center">
				<h1><span><?php echo $a_category_c['name']?></span></h1>
				
				<?php 
					$url = array('controller'=>'galleries','action' => 'index','lang'=>$lang,'slug0'=>$a_category_c['slug']);
					$a_params = $this->params;
					if(!empty($a_params['sort']) && !empty($a_params['direction'])) $url = array_merge($url,array('sort'=>$a_params['sort'],'direction'=>$a_params['direction']));
					
					$this->Paginator->options(array(
						'url'=>$url
					));
				?>
				<ul class="sort">
					<li><?php echo __('Sắp xếp',true).': '?></li>
					<li><?php echo $this->Paginator->sort('name',__('Tên',true),array('title'=>__('Tên'),'class'=>'','rel'=>'nofollow'))?></li>
				</ul> <!--  end .sort -->
			</div> <!--  end .title_center -->
		</div> <!--  end .title_right -->
	</header> <!--  end .title -->
	
	<div class="des">
		<?php if(!empty($a_category_c['description'])){?>
		<div class="box_info_page">
			<div class="des">
				<?php echo $a_category_c['description']?>
			</div> <!--  end .des -->
				
		</div> <!--  end .box_info_page -->
		<?php }?>
		
		<?php if(!empty($a_galleries_c)){
			echo $this->element('frontend/c_gallery',array('data'=>$a_galleries_c))?>
		
		<div class="clear"></div>
		<div class="paginator">
			<?php 
				echo $this->Paginator->counter(array('format'=>'<span class="page">%page%/%pages%</span>'));
				echo $this->Paginator->first('<<',array('separator'=>false,'title'=>__('Trang đầu',true)));
				echo $this->Paginator->numbers(array('separator'=>false,'modulus'=>7,'class'=>'number'));
				echo $this->Paginator->last('>>',array('separator'=>false,'title'=>__('Trang cuối',true)));
			?>
		</div> <!-- end .paginator -->
		<?php }else echo __('Thông tin đang được cập nhật. Bạn vui lòng trở lại sau.',true)?>
	</div> <!--  end .des -->
</article> <!--  end .box_content -->