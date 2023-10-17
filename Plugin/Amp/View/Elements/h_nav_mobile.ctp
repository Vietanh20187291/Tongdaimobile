<?php
	$controller = $this->params['controller'];
	$action = $this->params['action'];
?>
<!-- Begin menu -->
	<nav class="navbar" role="navigation">
		<!-- Collect the nav links, forms, and other content for toggling -->
		<ul class="nav navbar-nav">
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown"><div><span class="fa fa-bars"></span></div><?php echo $this->Html->link(__('Danh mục'), rtrim($http_host,'/').$this->Html->url($origin_url) ) ?>
				</a>
				<?php if(!empty($a_product_categories_s)){?>
					<ul class="dropdown-menu">
						<?php foreach($a_product_categories_s as $key=>$val){
							$item_cate =$val['ProductCategory'];
							if(empty($item_cate['link'])){
								$url = array('plugin'=>false, 'controller'=>'products','action' => 'index','lang'=>$item_cate['lang']);
								$tmp = explode(',', $item_cate['path']);
								$url = array_merge($url,array('slug0'=>$tmp[count($tmp)-1]));
							}else $url = $item_cate['link'];
							$link_attr = array('title'=>$item_cate['meta_title'],'target'=>$item_cate['target'],'escape'=>false);
							if($item_cate['rel']!='dofollow') $link_attr['rel'] = $item_cate['rel'];
						if(!empty($val['children'])){
						?>
						<li class="dropdown">
							<?php echo $this->Html->link($item_cate['name'].'<div class="submenu-caret-wrapper"><span class="caret"></span></div>',$url,$link_attr)?>
							<ul class="dropdown-menu">
							<?php foreach ($val['children'] as $key1=>$val1){
									$item_cate1 =$val1['ProductCategory'];
									if(empty($item_cate1['link'])){
										$url1 = array('plugin'=>false, 'controller'=>'products','action' => 'index','lang'=>$item_cate1['lang']);
										$tmp1 = explode(',', $item_cate1['path']);
										$url1 = array_merge($url1,array('slug0'=>$tmp1[count($tmp1)-1]));
									}else $url1 = $item_cate1['link'];
									$link_attr1 = array('title'=>$item_cate1['meta_title'],'target'=>$item_cate1['target'],'escape'=>false,'class'=>'clearfix');
									if($item_cate1['rel']!='dofollow') $link_attr1['rel'] = $item_cate1['rel'];
								?>
								<li><?php echo $this->Html->link($item_cate1['name'].'<i class="icon-arrow-right"></i>',$url1,$link_attr1)?></li>
							<?php }?>
							</ul>
						</li>
						<?php }else{?>
						<li><?php echo $this->Html->link($item_cate['name'],$url,$link_attr)?></li>
						<?php }
						}?>
					</ul>
					<?php }?>
			</li>
			<li>
				<?php
					foreach ($a_support_s as $val)
					{
						$item_support = $val['Support'];
						if (strtolower($item_support['name']) == 'hotline') {
							echo $this->Html->link('<div><span class="fa fa-phone"></span></div>'.$item_support['phone'], 'tel:'.$this->OnewebVn->rawPhone($item_support['phone'], array('class' => 'tel')),array('escape'=>false));
							break;
						}
					}
				?>
			</li>
			<li>
				<a href="" class="chat_fb col-xs-12"><div><span class="facebook-messenger"></span></div>
					<?php echo $this->Html->link(__('Chat facebook'), rtrim($http_host,'/').$this->Html->url($origin_url) ) ?></a>
				<?php
					if(!empty($a_site_info['facebook'])){
				 ?>					
				<?php } ?>
			</li>
			<li>
				<a class="request-support">
					<div><span class="fa fa-envelope"></span></div>
					<?php echo $this->Html->link(__('Tư vấn'), rtrim($http_host,'/').$this->Html->url($origin_url) ) ?>
				</a>
			</li>
			<li><?php
						$qty_cart = 0;
						if($this->Session->check("Order_$lang")){
							$cart = $this->Session->read("Order_$lang");
							for($i=0;$i<count($cart);$i++) $qty_cart+=$cart[$i]['qty'];
						}
						echo $this->Html->link('<div><span class="fa fa-shopping-cart"></span>'.' '.$this->Html->tag('span',"$qty_cart",array('class'=>'number_product_cart')).'</div>'.__('Giỏ hàng'),rtrim($http_host,'/').$this->Html->url($origin_url),array('title'=>__('Giỏ hàng',true),'class'=>'link_cart','escape'=>false));
					?>
			</li>
		</ul>
	</nav>
<!-- End Menu -->