
<article class="box_content row">
	<div class="col-xs-12">
		<header class="title">
			<h1>
				<?php echo __('Kết quả tìm kiếm',true).': '.$total_c.' '.__('sản phẩm',true)?>
			</h1>
			<?php
				$url = array('controller'=>'filters','action' => 'product','lang'=>$lang);
				$a_filter = array();
				if(!empty($_GET['key'])) $a_filter = array_merge($a_filter,array('key'=>$_GET['key']));
				if(!empty($_GET['cate_id'])) $a_filter = array_merge($a_filter,array('cate_id'=>$_GET['cate_id']));
				if(!empty($_GET['sort'])) $a_filter = array_merge($a_filter,array('sort'=>$_GET['sort']));
				if(!empty($_GET['direction'])) $a_filter = array_merge($a_filter,array('direction'=>$_GET['direction']));
				if(!empty($_GET['page'])) $a_filter = array_merge($a_filter,array('page'=>$_GET['page']));
				$url = array_merge($url,array('?'=>$a_filter));
			?>

			<ul class="sort">
				<li><?php echo __('Sắp xếp',true).': '?></li>
				<li>
					<div class="dropdown">
						<button class="btn btn-default dropdown-toggle sort-btn" type="button" id="dropdownSort" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<?php
								$sort = (!empty($_GET['sort']))?$_GET['sort']:'';
								$direction = (!empty($_GET['direction']))?$_GET['direction']:'';
								if ($sort == 'Product.price' && $direction == 'asc') echo __('Giá tăng dần');
								if ($sort == 'Product.price' && $direction == 'desc') echo __('Giá giảm dần');
								if ($sort == 'Product.name' && $direction == 'asc') echo __('Tên từ A-Z');
								if ($sort == 'Product.name' && $direction == 'desc') echo __('Tên từ Z-A');
								if ( empty($sort) && empty($direction)) echo __('Chưa sắp xếp');
							?>
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownSort">
							<li>
								<?php
									$a_filter1 = array_merge($a_filter,array('sort' => 'Product.price', 'direction' => 'asc'));
									echo $this->Html->link(__('Giá tăng dần'), array_merge($url, array('?'=>$a_filter1))); ?>
							</li>
							<li>
								<?php
									$a_filter2 = array_merge($a_filter,array('sort' => 'Product.price', 'direction' => 'desc'));
									echo $this->Html->link(__('Giá giảm dần'), array_merge($url, array('?'=>$a_filter2))); ?>
							</li>
							<li>
								<?php
									$a_filter3 = array_merge($a_filter,array('sort' => 'Product.name', 'direction' => 'asc'));
									echo $this->Html->link(__('Tên từ A-Z'), array_merge($url, array('?'=>$a_filter3))); ?>
							</li>
							<li>
								<?php
									$a_filter4 = array_merge($a_filter,array('sort' => 'Product.name', 'direction' => 'desc'));
									echo $this->Html->link(__('Tên từ Z-A'), array_merge($url, array('?'=>$a_filter4))); ?>
							</li>
						</ul>
					</div>
				</li>
			</ul>
		</header>

		<div class="des row auto-clear">
			<?php
			$c_page = (!empty($_GET['page']))?$_GET['page']:1;
			echo $this->element('frontend/c_product_filter',array(
															'data'		=> $a_products_c,
															'position'	=> '',
															'limit'		=> '',
															'cart'		=> true,
															'class'		=> '',
															'w'			=> 400,
															'zc'		=> 2,
															'page'			=>$c_page,
															'page_limit'	=>$limit
														))
			?>
		</div>
		<div class="paginator">
			<?php
			$link = "";
			$page = $a_page['current'];
			$pages= $a_page['total'];
			$value= 2 ;?>
			<span class="page"><?php echo $page.'/'.$pages?></span>
			<?php if ($pages >= 1 && $page <= $pages){
					$link = "";
					if($page > 1){
						$a_filter = array_merge($a_filter,array('page' => $c_page - 1));
						if($c_page == 2)
							unset($a_filter['page']);
						$link .= $this->Html->tag('span',$this->Html->link('&lt;',array_merge($url, array('?'=>$a_filter)),array('rel'=>'nofollow','escape'=>false)),array('class'=>'number'));
					}
					if ($page == ($value + 1)){
						unset($a_filter['page']);
						$link .= $this->Html->tag('span',$this->Html->link('1',array_merge($url, array('?'=>$a_filter)),array('rel'=>'nofollow','escape'=>false)),array('class'=>'number'));
					}
					if ($page > ($value + 1)){
						unset($a_filter['page']);
						$link .= $this->Html->tag('span',$this->Html->link('1',array_merge($url, array('?'=>$a_filter)),array('rel'=>'nofollow','escape'=>false)),array('class'=>'number')).'<span>...</span>';
					}
					for ($x = $page - 1; $x <=$pages;$x++) {
						if ($x ==$page - 1 && $x > 0){
							$a_filter = array_merge($a_filter,array('page' => $x));
							if($x == 1)
								unset($a_filter['page']);
							$link .= $this->Html->tag('span',$this->Html->link($x,array_merge($url, array('?'=>$a_filter)),array('rel'=>'nofollow','escape'=>false)),array('class'=>'number'));
						}
						if($x ==$page){
				 			$link .=  $this->Html->tag('span',$x,array('class'=>'number current'));
						}
						if ($x ==$page + 1){
							$a_filter = array_merge($a_filter,array('page' => $x));
							$link .= $this->Html->tag('span',$this->Html->link($x,array_merge($url, array('?'=>$a_filter)),array('rel'=>'nofollow','escape'=>false)),array('class'=>'number'));
						}
					}
					if ($page == $pages - $value){
						$a_filter = array_merge($a_filter,array('page' => $pages));
						$link .= $this->Html->tag('span',$this->Html->link($pages,array_merge($url, array('?'=>$a_filter)),array('rel'=>'nofollow','escape'=>false)),array('class'=>'number'));
					}
					if ($page < $pages - $value){
						$a_filter = array_merge($a_filter,array('page' => $pages));
						$link .= "<span>...</span>".$this->Html->tag('span',$this->Html->link($pages,array_merge($url, array('?'=>$a_filter)),array('rel'=>'nofollow','escape'=>false)),array('class'=>'number'));
					 }
					if($page < $pages){
						$a_filter = array_merge($a_filter,array('page' => $c_page + 1));
						$link .= $this->Html->tag('span',$this->Html->link('&gt;',array_merge($url, array('?'=>$a_filter)),array('rel'=>'nofollow','escape'=>false)),array('class'=>'number'));
					}
		 		}
				echo $link;
				?>
		</div>
	</div>
</article>
<!-- end filters/product.ctp