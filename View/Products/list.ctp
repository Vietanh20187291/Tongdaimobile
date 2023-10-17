<!-- start products/list.ctp -->
<article class="row bg_white">
	<?php
		$flag = true;		//Hiển thị title ở vị trí sắp xếp
		if(!empty($a_child_direct_categories) || !empty($a_category_c['description'])) $flag = false;
		if(!$flag && !empty($a_category_c['description'])){
	?>

	<div class="box_info_page col-xs-12">
		<div style="list-style: disc;" class="des">
			<?php echo $a_category_c['description']?>
		</div>
	</div>
	<?php }?>
	<?php if($a_products_c){?>
	<div class="box_content col-xs-12">
		<header class="title">
			<h1><?php echo $title_for_layout?></h1>
			<?php
				$url = array('controller'=>'products','action' => 'index','lang'=>$lang);
				$tmp = explode(',', $a_category_c['path']);
				for($i=0;$i<count($tmp);$i++){
					$url = array_merge($url,array('slug'.$i=>$tmp[$i]));
				}

				$not_sorted_url = $url;

				$a_params = $this->params;
				if(!empty($a_params['sort']) && !empty($a_params['direction'])) $url = array_merge($url,array('sort'=>$a_params['sort'],'direction'=>$a_params['direction']));

				if ( ! empty($_GET['maker_id']) && ! empty($_GET['price_range_id'])) {
					$url = array_merge($url,array('?'=>array('maker_id'=>$_GET['maker_id'], '&price_range_id' => $_GET['price_range_id'])));
				} else {
					if ( ! empty($_GET['maker_id'])) $url = array_merge($url,array('?'=>array('maker_id'=>$_GET['maker_id'])));
					if ( ! empty($_GET['price_range_id'])) $url = array_merge($url,array('?'=>array('price_range_id'=>$_GET['price_range_id'])));
				}

				$this->Paginator->options(array(
					'url'=>$url
				));
			?>

			<ul class="sort">
				<li><?php echo __('Sắp xếp',true).': '?></li>
				<li>
					<div class="dropdown">
						<button class="btn btn-default dropdown-toggle sort-btn" type="button" id="dropdownSort" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<?php
								$sort = $this->params['sort'];
								$direction = $this->params['direction'];
								if ($sort == 'price' && $direction == 'asc') echo __('Giá tăng dần');
								if ($sort == 'price' && $direction == 'desc') echo __('Giá giảm dần');
								if ($sort == 'name' && $direction == 'asc') echo __('Tên từ A-Z');
								if ($sort == 'name' && $direction == 'desc') echo __('Tên từ Z-A');
								if ( empty($sort) && empty($direction)) echo __('Chưa sắp xếp');
							?>
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownSort">
							<li>
								<?php echo $this->Html->link(__('Giá tăng dần'), array_merge($url, array('sort' => 'price', 'direction' => 'asc'))); ?>
							</li>
							<li>
								<?php echo $this->Html->link(__('Giá giảm dần'), array_merge($url, array('sort' => 'price', 'direction' => 'desc'))); ?>
							</li>
							<li>
								<?php echo $this->Html->link(__('Tên từ A-Z'), array_merge($url, array('sort' => 'name', 'direction' => 'asc'))); ?>
							</li>
							<li>
								<?php echo $this->Html->link(__('Tên từ Z-A'), array_merge($url, array('sort' => 'name', 'direction' => 'desc'))); ?>
							</li>
						</ul>
					</div>
				</li>
			</ul>
		</header>
		<div id="product-wrapper" class="row auto-clear">

			<?php
				echo $this->element('frontend/c_product',array(
															'data'		=> $a_products_c,
															'position'	=> '',
															'limit'		=> '',
															'cart'		=> true,
															'class'		=> '',
															'w'			=> 400,
															'zc'		=> 2
														))
			?>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
				<input id="loaded-page" type="number" class="hidden" value="1">
				<button id="show-more"><?php echo __('Xem thêm 20 sản phẩm').'<i class="fa fa-caret-down" aria-hidden="true"></i>'?></button>
			</div>
		</div>
		<?php if(!empty($a_category_c['description2'])) { ?>
		<div class="row">
			<div class="col-xs-12">
				<hr>
				<div class="des">
					<?php echo str_replace('<table ','<div class = "table-responsive"> <table ',str_replace('</table>','</table> </div>',$a_category_c['description2'])) ;?>

				</div>
			</div>
		</div>
		<?php } ?>
        <section class="related m-b-15">
            <header>
                <div class="title">
                    <span class="icon_oneweb"></span>
                    <!--				<strong>--><!--</strong>-->
                </div>
            </header>
            <div id="show_post_related">
                <div class="row fix-safari">
                    <div class="member_exps col-xs-12">
                        <h3><span class="title title_text primary-color text-uppercase font-bold">Bài viết liên quan</span></h3>
                        <div class="row auto-clear fix-safari">
                            <?php
                            //Kich thước ảnh thumbnail
                            $full_size = $oneweb_post['size']['post'];
                            $data = $a_related_posts_c;

                            foreach($data as $key=>$val){
                            $item_post = $val['Post'];
                            $item_cate = $val['PostCategory'];

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
                            $link_more_attr['title'] = __('Read more',true);
                            $link_more_attr['class'] = 'readmore float_right';
							$w = 332;
                            $h = intval($w*$full_size[1]/$full_size[0]);
                            ?>

                            <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 m-b-15">
                                <div class="thumb">
                                    <?php echo $this->Html->link($this->OnewebVn->thumb('posts/'.$item_post['image'],array('alt'=>$item_post['meta_title'],'width'=>262,'height'=>150, 'zc' => '1', 'class'=>'img-responsive')),$url,$link_img_attr)?>
                                </div>
                                <div style="margin-top: 0px" class="name font-bold text-center m-t-15">
                                    <h3><?php echo $this->Html->link($item_post['name'],$url,$link_attr);?> </h3></div>
                            </div>

                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
	</div>

	<?php }else{?>
	<div class="col-xs-12 wait">
		<p><?php echo __('Hiện tại chưa có sản phẩm được cập nhật',true)?></p>
	</div>
	<?php }?>
</article>

<script>
	function getListMore(page){
		$.ajax({
			type: 'post',
			url:'<?php echo $this->Html->url(array('controller'=>'products','action'=>'ajaxListMore','lang'=>$lang))?>',
			data: {
				'page': page,
				'cate_id': '<?php echo $a_category_c["id"]; ?>',
				'lang': '<?php echo $lang; ?>'
			},
			beforeSend:function(){
				$("#ajax_loading").show();
			},
			success:function(result){
				$("#product-wrapper").append(result);
				$("#ajax_loading").hide();
			}
		});
	};
	$(document).ready(function(){
		if ($('#loaded-page').val() == '<?php echo $this->params['paging']['Product']['pageCount'];?>') $('#show-more').hide();
		$('#show-more').on('click', function(){
			$('#loaded-page').val(parseInt($('#loaded-page').val()) + 1);
			getListMore($('#loaded-page').val());
			if ($('#loaded-page').val() == '<?php echo $this->params['paging']['Product']['pageCount'];?>') $('#show-more').hide();
		});
	});
</script>
<!-- end products/list.ctp -->
