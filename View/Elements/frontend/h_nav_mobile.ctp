<?php
	$controller = $this->params['controller'];
	$action = $this->params['action'];
?>
<!-- Begin menu -->
	<nav class="navbar" role="navigation">
		<!-- Collect the nav links, forms, and other content for toggling -->
		<ul class="nav navbar-nav">
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" aria-expanded="true"><div><span class="fa fa-bars"></span></div><?php echo __('Danh mục') ?>
				</a>
				<?php if(!empty($a_product_categories_s)){?>
					<ul class="dropdown-menu">
						<?php foreach($a_product_categories_s as $key=>$val){
							$item_cate =$val['ProductCategory'];
							if(empty($item_cate['link'])){
								$url = array('controller'=>'products','action' => 'index','lang'=>$item_cate['lang']);
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
										$url1 = array('controller'=>'products','action' => 'index','lang'=>$item_cate1['lang']);
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
			<li class="text-center">
				<a href="https://m.me/121416498347179?ref=livechat_hinlet" target='_blank'>
				<?php echo $this->Html->image('0b4ce03d3124d57a8c35.png', array('alt' => 'Xem fanpage', 'class' => 'chat_fb','width'=>30))?>
				<p><?php echo __('Chat facebook',true)?></p>
				</a>
			</li>
			<?php /* ?>
			<li>
				<a href="javascript:;" class="chat_fb col-xs-12" onclick="return false;"><div><span class="facebook-messenger"></span></div><?php echo __('Chat facebook',true)?></a>
				<?php
					if(!empty($a_site_info['facebook'])){
				 ?>
					<script>
					jQuery(document).ready(function () {
						jQuery(".chat_fb").click(function() {
							jQuery('.fchat').slideToggle("slow");
						});
					});
					</script>
					<div class="fchat">
						<div class="fb-page" data-tabs="messages" data-href="<?php echo $a_site_info['facebook']; ?>" data-width="300" data-height="260" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"></div>
					</div>
				<?php } ?>
			</li>*/?>
			<li>
				<a onclick="requestSupport()" class="request-support">
					<div><span class="fa fa-envelope"></span></div>
					<?php echo __('Tư vấn'); ?>
				</a>
			</li>
			<li><?php
						$qty_cart = 0;
						if($this->Session->check("Order_$lang")){
							$cart = $this->Session->read("Order_$lang");
							for($i=0;$i<count($cart);$i++) $qty_cart+=$cart[$i]['qty'];
						}
						echo $this->Html->link('<div><span class="fa fa-shopping-cart"></span>'.' '.$this->Html->tag('span',"$qty_cart",array('class'=>'number_product_cart')).'</div>'.__('Giỏ hàng'),'javascript:;',array('title'=>__('Giỏ hàng',true),'onclick'=>'showCart()','class'=>'link_cart','data-toggle'=>'modal', 'data-target'=>'#popup_modal','escape'=>false));
					?>
			</li>
		</ul>
	</nav>
<!-- End Menu -->