<!-- start c_display_product_category.ctp -->
<?php
if(!empty($a_display_category)) {
	$url_cate = '';
	if(empty($a_display_category['link'])) {
		$url_cate = array('controller'=>'products','action' => 'index', 'slug0' => $a_display_category['slug'], 'lang'=>$a_display_category['lang']);
		$link_cate_attr = array('title'=>$a_display_category['meta_title'],'target'=>$a_display_category['target']);
		if($a_display_category['rel']!='dofollow') $link_attr['rel'] = $a_display_category['rel'];
	} else $url_cate = $a_display_category['link'];
?>
<article class="row">
	<div class="col-xs-12 box_content m-t-15">
		<div class="row">
			<div class="col-xs-12">
				<div class="box-content bg_white">
					<div class="row">
						<div class="col-xs-12 col-sm-3 text-center">
							<div class="bg_title">
								<h2 class="text-uppercase font-weight-bold"><?php echo $this->Html->link($a_display_category['name'],$url_cate,array('title'=>$a_display_category['name'])); ?></h2><span class="title-arrow-r"></span>
							</div>
						</div>
						<?php if(!empty($a_display_children)) {
						if(count($a_display_children)<5) $cccount_child = count($a_display_children);
						else $cccount_child = 4?>
						<nav class="nav sub_category col-sm-9 hidden-xs">
							<?php
								for($c=0;$c<$cccount_child;$c++) {
									$item_cate = $a_display_children[$c]['ProductCategory'];
									if(empty($item_cate['link'])){
										$url = array('controller'=>'products','action' => 'index','lang'=>$item_cate['lang']);
										$tmp = explode(',', $item_cate['path']);
										for($i=0;$i<count($tmp);$i++){
											$url = array_merge($url,array('slug'.$i=>$tmp[$i]));
										}
									}else $url = $item_cate['link'];
									if($cccount_child ==3)$link_attr = array('title'=>$item_cate['meta_title'],'target'=>$item_cate['target'],'class'=>'nav-link text-center font-weight-bold col-sm-4 col-md-4','escape'=>false);
									else $link_attr = array('title'=>$item_cate['meta_title'],'target'=>$item_cate['target'],'class'=>'nav-link text-center font-weight-bold col-sm-3 col-md-3','escape'=>false);
									if($item_cate['rel']!='dofollow') $link_attr['rel'] = $item_cate['rel'];

									$current = false;
									$controller = $this->params['controller'];
									$action = $this->params['action'];

									if($controller=='products' && $action=='index'){
										$get_url = explode('/', $this->request->url);
										if(in_array($item_cate['slug'], $get_url)) $current = true;
									}
							?>
							<?php echo $this->Html->link($this->Html->tag('span class="row"',$this->Text->truncate($item_cate['name'],30,array('exact'=>false))), $url,$link_attr); ?>
							<?php } ?>
						</nav>
						<?php } ?>
					</div>

					<div class="line_title col-xs-12"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<?php
				echo $this->element('frontend/c_product_run_home',array(
															'data'					=> $a_products_c,
															'position_p'		=> $id_slide,
															'limit'					=> '',
															'cart'					=> true,
															'run'						=> true,
															'direction'			=> 'left',
															'class'					=> 'spc',
															'w'							=> 160,
															'zc'						=> 2,
															'items' 				=> 5
														));
			?>

			<div class="clear"></div>
		</div>
	</div>
</article>
<?php } ?>
<!-- end c_display_product_category.ctp -->
