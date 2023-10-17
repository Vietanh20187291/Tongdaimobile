<?php echo $this->Html->script('highlight');?>

<article>
	<div class="search row margin-btm15">
		<?php 
			echo $this->Form->create('Filter',array('url'=>array('controller'=>'filters','action'=>'search','lang'=>$lang),'inputDefaults'=>array('div'=>false,'label'=>false)));
			echo $this->Form->input('type',array('type'=>'hidden','value'=>2));
		?>
		<ul>
			<li class="col-xs-4"><?php echo $this->Form->input('post_name',array('value'=>((!empty($_GET['key']))?$_GET['key']:__('Tìm kiếm',true)),'class'=>'larger','id'=>'filter_name','class'=>'form-control')) ?></li>
			<?php if(!empty($a_post_categories_2_s)){?>
			<li class="col-xs-4"><?php echo $this->Form->input('post_category',array('type'=>'select','options'=>$a_post_categories_2_s,'value'=>((!empty($_GET['cate_id']))?$_GET['cate_id']:''),'empty'=>__('Chọn danh mục',true),'class'=>'form-control'))?></li>
			<?php }?>
		</ul>
		<?php 
		echo $this->Form->submit(__('Tìm kiếm',true),array('class'=>'btn submit','div'=>'search col-xs-4'));
		echo $this->Form->end();
		?>	
	</div> <!-- end .search -->
	<header>
					<h1><span class="title"><?php echo __('Kết quả tìm kiếm',true).': '.$total_c.' '.__('bài viết',true)?></span></h1>
	</header> <!--  end .title -->
	<div class="row">
		<div class="col-xs-12">
			<div class="des row">
				<?php 
				echo $this->element('frontend/c_post',array(
														'data'	=> $a_posts_c,
														'class' 	=> 'post',
														'limit' 	=> 120,				//Cắt chuỗi
														'datetime' 	=> true,
														'w'			=> 222,
														'zc'		=> 2
													));
				?>
				
				<div class="clear"></div>
				<div class="paginator">
					<?php 
						$url = array('controller'=>'filters','action' => 'post','lang'=>$lang);
						if(!empty($_GET)) $url = array_merge($url,array('?'=>$_GET));
						$this->Paginator->options(array(
							'url'=>$url
						));
						
						echo $this->Paginator->counter(array('format'=>'<span class="page">%page%/%pages%</span>'));
						echo $this->Paginator->first('<<',array('separator'=>false,'title'=>__('Trang đầu',true)));
						echo $this->Paginator->numbers(array('separator'=>false,'modulus'=>7,'class'=>'number'));
						echo $this->Paginator->last('>>',array('separator'=>false,'title'=>__('Trang cuối',true)));
					?>
				</div> <!-- end .paginator -->
			</div> <!--  end .des -->
		</div>
	</div>
</article> <!--  end .box_content -->