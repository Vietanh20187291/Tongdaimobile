<div id="column_right">
				
	<div id="action_top">
		<ul class="tabs">
    		<li><a href="#tab1"><?php echo __('Bảng điều khiển',true)?></a></li>
    		<li><a href="#tab2"><?php echo __('Thống kê',true)?></a></li>
    	</ul> <!-- end .tabs -->
	</div> <!--  end #action_top -->
	
	<div id="content" class="home">
	
		<div class="tab_container">
			<div id="tab1" class="tab_content">
				
				<?php if(!empty($oneweb_product['enable'])){?>
				<div class="box_manager col_2 product">
					<?php echo $this->Html->image('admin/product.jpg')?>
					<div class="box_manager_middle">

					</div> <!--  end .box_manager_middle -->
				</div> <!--  end .box_manager -->
				<?php }?>

				<?php if(!empty($oneweb_post['enable'])){?> 
				<div class="box_manager col_1 post">
					<?php echo $this->Html->image('admin/post.jpg')?>
					<div class="box_manager_middle">
						<table>
							<tr>
								<th colspan="2"><?php echo __('Bài viết',true)?></th>
							</tr>
							<tr>
								<td>
									<?php 
										echo $this->Html->link(__('Bài viết',true),array('controller'=>'posts','action'=>'index'),array('title'=>__('Bài viết',true),'class'=>'tooltip'));
										echo ' (100)';
										echo $this->Html->link('&nbsp;',array('controller'=>'posts','action'=>'add'),array('title'=>__('Thêm bài viết',true),'class'=>'act add tooltip','escape'=>false));
									?>
								</td>
							</tr>
							<tr>
								<td>
									<?php 
										echo $this->Html->link(__('Danh mục',true),array('controller'=>'post_categories','action'=>'index'),array('title'=>__('Danh mục',true),'class'=>'tooltip'));
										echo ' (10)';
										echo $this->Html->link('&nbsp;',array('controller'=>'post_categories','action'=>'add'),array('title'=>__('Thêm danh mục',true),'class'=>'act add tooltip','escape'=>false));
									?>
								</td>
							</tr>
						</table>
					</div> <!--  end .box_manager_middle -->
				</div> <!--  end .box_manager -->
				<?php }?>
					 
				<div class="box_manager col_1 info">
					<?php echo $this->Html->image('admin/info.jpg')?>
					<div class="box_manager_middle">
						<table>
							<tr>
								<th><?php echo __('Trang thông tin',true)?></th>
							</tr>
							<?php foreach($a_information_c as $val){
								$item_info = $val['Information'];
							?>
							<tr>
								<td>
									<?php 
										echo $this->Html->link($item_info['name'],array('controller'=>'information','action'=>'edit',$item_info['id']),array('title'=>$item_info['name'],'class'=>'tooltip'));
										echo ' ('.count($val['ChildInformation']).')';
									?>
								</td>
							</tr>
							<?php }?>
							<tr>
								<td>
									<?php 
										echo $this->Html->link(__('Tất cả',true),array('controller'=>'information','action'=>'index'),array('title'=>__('Tất cả',true),'class'=>'tooltip'));
									?>
								</td>
							</tr>
						</table>
					</div> <!--  end .box_manager_middle -->
				</div> <!--  end .box_manager -->
				<?php if($oneweb_banner['enable']){?>
				<div class="box_manager col_1 media">
				 	<?php echo $this->Html->image('admin/media.jpg')?>
					<div class="box_manager_middle">
						<table>
							<tr>
								<th colspan="2"><?php echo __('Media',true)?></th>
							</tr>
							<?php if(!empty($oneweb_media['gallery']['enable'])){?>
							<tr>
								<td>
									<?php 
										echo $this->Html->link(__('Hình ảnh'),array('controller'=>'galleries','action'=>'index'),array('title'=>__('Hình ảnh'),'class'=>'tooltip'));
										echo ' ('.$gallery_counter_c.')';
										echo $this->Html->link('&nbsp;',array('controller'=>'galleries','action'=>'add'),array('title'=>__('Thêm hình ảnh',true),'class'=>'act add tooltip','escape'=>false));
									?>
								</td>
							</tr>
							<?php }?>
							<?php if(!empty($oneweb_media['video']['enable'])){?>
							<tr>
								<td>
									<?php 
										echo $this->Html->link(__('Video',true),array('controller'=>'videos','action'=>'index'),array('title'=>__('Video',true),'class'=>'tooltip'));
										echo ' ('.$video_counter_c.')';
										echo $this->Html->link('&nbsp;',array('controller'=>'videos','action'=>'add'),array('title'=>__('Thêm Video',true),'class'=>'act add tooltip','escape'=>false));
									?>
								</td>
							</tr>
							<?php }?>
							<?php if(!empty($oneweb_media['document']['enable'])){?>
							<tr>
								<td>
									<?php 
										echo $this->Html->link(__('Tài liệu',true),array('controller'=>'documents','action'=>'index'),array('title'=>__('Tài liệu',true),'class'=>'tooltip'));
										echo ' ('.$document_counter_c.')';
										echo $this->Html->link('&nbsp;',array('controller'=>'documents','action'=>'add'),array('title'=>__('Thêm tài liệu',true),'class'=>'act add tooltip','escape'=>false));
									?>
								</td>
							</tr>
							<?php }?>
							<?php if(!empty($oneweb_banner['enable'])){?>
							<tr>
								<td>
									<?php 
										echo $this->Html->link(__('Banner',true),array('controller'=>'banners','action'=>'index'),array('title'=>__('Banner',true),'class'=>'tooltip'));
										echo ' ('.$banner_counter_c.')';
										echo $this->Html->link('&nbsp;',array('controller'=>'banners','action'=>'add'),array('title'=>__('Thêm Banner',true),'class'=>'act add tooltip','escape'=>false));
									?>
								</td>
							</tr>
							<?php }?>
						</table>
					</div> <!--  end .box_manager_middle -->
				</div> <!--  end .box_manager -->
				<?php }?>
				<?php if(!empty($oneweb_web['comment'])){?>	 	 
				<div class="box_manager col_1 comment">
				 	<?php echo $this->Html->image('admin/comment.jpg')?>
					<div class="box_manager_middle">
						<table>
							<tr>
								<th colspan="2"><?php echo __('Bình luận',true)?></th>
							</tr>
							<tr>
								<td>
									<?php 
										echo $this->Html->link(__('Tất cả',true),array('controller'=>'comments','action'=>'index'),array('title'=>__('Tất cả',true),'class'=>'tooltip'));
										echo ' ('.number_format($comment_count_c).')';
									?>
								</td>
							</tr>
							<?php if(!empty($oneweb_product['comment'])){?>
							<tr>
								<td>
									<?php
										echo $this->Html->link(__('Sản phẩm',true),array('controller'=>'comments','action'=>'index','?'=>array('model'=>'Product')),array('title'=>__('Sản phẩm',true),'class'=>'tooltip'));
										echo ' ('.number_format($comment_product_count_c).')';
									?>
								</td>
							</tr>
							<?php }?>
							<?php if(!empty($oneweb_post['comment'])){?>
							<tr>
								<td>
									<?php 
										echo $this->Html->link(__('Bài viết',true),array('controller'=>'comments','action'=>'index','?'=>array('model'=>'Post')),array('title'=>__('Bài viết',true),'class'=>'tooltip'));
										echo ' ('.number_format($comment_post_count_c).')';
									?>
								</td>
							</tr>
							<?php }?>
							<?php if(!empty($oneweb_media['gallery']['comment'])){?>
							<tr>
								<td>
									<?php 
										echo $this->Html->link(__('Hình ảnh',true),array('controller'=>'comments','action'=>'index','?'=>array('model'=>'Gallery')),array('title'=>__('Hình ảnh',true),'class'=>'tooltip'));
										echo ' ('.number_format($comment_gallery_count_c).')';
									?>
								</td>
							</tr>
							<?php }?>
							<?php if(!empty($oneweb_media['video']['comment'])){?>
							<tr>
								<td>
									<?php 
										echo $this->Html->link(__('Video',true),array('controller'=>'comments','action'=>'index','?'=>array('model'=>'Video')),array('title'=>__('Video',true),'class'=>'tooltip'));
										echo ' ('.number_format($comment_video_count_c).')';
									?>
								</td>
							</tr>
							<?php }?>
						</table>
					</div> <!--  end .box_manager_middle -->
				</div> <!--  end .box_manager -->
				<?php }?>					 
				 
				<?php if(!empty($oneweb_faq['enable'])){?>
				<div class="box_manager col_1 faq">
				 	<?php echo $this->Html->image('admin/faq.jpg')?>
					<div class="box_manager_middle">
						<table>
							<tr>
								<th colspan="2">FAQs</th>
							</tr>
							<tr>
								<td>
									<?php 
										echo $this->Html->link(__('Faqs',true),array('controller'=>'faqs','action'=>'index'),array('title'=>__('Faqs',true),'class'=>'tooltip'));
										echo ' ('.number_format($faq_count_c).')';
										echo $this->Html->link('&nbsp;',array('controller'=>'faqs','action'=>'add'),array('title'=>__('Thêm FAQs',true),'class'=>'act add tooltip','escape'=>false));
									?>
								</td>
							</tr>
							<tr>
								<td>
									<?php 
										echo $this->Html->link(__('Danh mục',true),array('controller'=>'faq_categories','action'=>'index'),array('title'=>__('Danh mục',true),'class'=>'tooltip'));
										echo ' ('.number_format($faq_cate_count_c).')';
										echo $this->Html->link('&nbsp;',array('controller'=>'faq_categories','action'=>'add'),array('title'=>__('Thêm danh mục',true),'class'=>'act add tooltip','escape'=>false));
									?>
								</td>
							</tr>
						</table>
					</div> <!--  end .box_manager_middle -->
				</div> <!--  end .box_manager -->
				<?php }?>
				
				<?php if(!empty($oneweb_support['enable'])){?>	 
				<div class="box_manager col_1 support">
				 	<?php echo $this->Html->image('admin/support.jpg')?>
					<div class="box_manager_middle">
						<table>
							<tr>
								<th colspan="2"><?php echo __('Hỗ trợ trực tuyến',true)?></th>
							</tr>
							<tr>
								<td>
									<?php 
										echo $this->Html->link(__('Hỗ trợ trực tuyến',true),array('controller'=>'supports','action'=>'index'),array('title'=>__('Hỗ trợ trực tuyến',true),'class'=>'tooltip'));
										echo ' ('.$support_count_c.')';
										echo $this->Html->link('&nbsp;',array('controller'=>'supports','action'=>'add'),array('title'=>__('Thêm hỗ trợ trực tuyến',true),'class'=>'act add tooltip','escape'=>false));
									?>
								</td>
							</tr>
						</table>
					</div> <!--  end .box_manager_middle -->
				</div> <!--  end .box_manager -->
				<?php }?>
				<?php if($oneweb_member['enable']){?> 
				<div class="box_manager col_1 member">
				 	<?php echo $this->Html->image('admin/member.jpg')?>
					<div class="box_manager_middle">
						<table>
							<tr>
								<th colspan="2"><?php echo __('Thành viên',true)?></th>
							</tr>
							<tr>
								<td><?php echo $this->Html->link(__('Quản lý thành viên',true),array('controller'=>'members','action'=>'index'),array('title'=>__('Quản lý thành viên',true),'class'=>'tooltip'))?></td>
							</tr>
							<tr>
								<td><?php echo $this->Html->link(__('Quốc gia',true),array('controller'=>'member_countries','action'=>'index'),array('title'=>__('Quốc gia',true),'class'=>'tooltip'));?></td>
							</tr>
							<tr>
								<td><?php echo $this->Html->link(__('Câu hỏi bí mật',true),array('controller'=>'member_question_secrets','action'=>'index'),array('title'=>__('Câu hỏi bí mật',true),'class'=>'tooltip'));?></td>
							</tr>
						</table>
					</div> <!--  end .box_manager_middle -->
				</div> <!--  end .box_manager -->
				<?php }?>	 
				<div class="box_manager col_2 other">
				 	<?php echo $this->Html->image('admin/other.jpg')?>
					<div class="box_manager_middle">
						<table>
							<tr>
								<th colspan="2"><?php echo __('Mục khác',true)?></th>
							</tr>
							<tr>
								<td>
									<?php echo $this->Html->link(__('Sitemap XML',true),array('controller'=>'sitemaps','action'=>'xml'),array('title'=>__('Sitemap XML',true),'class'=>'tooltip'))?>
								</td>
								<td>
									<?php echo $this->Html->link(__('Xóa Cache',true),array('controller'=>'pages','action'=>'delCache'),array('title'=>__('Xóa Cache',true),'class'=>'del_cache'))?>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $this->Html->link(__('Quản lý Tag',true),array('controller'=>'tags','action'=>'index'),array('title'=>__('Quản lý Tag',true),'class'=>'tooltip'))?>
								</td>
							</tr>
						</table>
					</div> <!--  end .box_manager_middle -->
				</div> <!--  end .box_manager -->
					 
				<div class="box_manager col_1 config">
				 	<?php echo $this->Html->image('admin/config.jpg')?>
					<div class="box_manager_middle">
						<table>
							<tr>
								<th colspan="2"><?php echo __('Cấu hình',true)?></th>
							</tr>
							<tr>
								<td>
									<?php 
										echo $this->Html->link(__('Cấu hình',true),array('controller'=>'configs','action'=>'edit'),array('title'=>__('Cấu hình',true),'class'=>'tooltip'));
									?>
							</tr>
						</table>
					</div> <!--  end .box_manager_middle -->
				</div> <!--  end .box_manager -->
			</div> <!-- end #tab1 -->
			
			<div id="tab2" class="tab_content">
				<aside class="box counter">
							<div id="counter">
							<table>
							<?php //if(!empty($oneweb_counter['online'])){?>
							<tr class="online">
								<th><?php echo __('Online',true)?></th>
								<td><?php echo number_format($a_counter_ip_c);?></td>
							</tr>
							<?php 
							foreach ($a_counters_c as $var){
								$a_counter =$var['CounterValue'];
								?>
							<?php //}if(!empty($oneweb_counter['yesterday'])){?>
							<tr class="yesterday">
								<th><?php echo __('Hôm qua',true)?></th>
								<td><?php echo $a_counter['yesterday_value']?></td>
							</tr>
							<?php //}if(!empty($oneweb_counter['today'])){?>
							<tr class="today">
								<th><?php echo __('Hôm nay',true)?></th>
								<td><?php echo $a_counter['day_value']?></td>
							</tr>
							<?php //}if(!empty($oneweb_counter['week'])){?>
							<tr class="week">
								<th><?php echo __('Trong tuần',true)?></th>
								<td><?php echo $a_counter['week_value']?></td>
							</tr>
							<?php //}if(!empty($oneweb_counter['month'])){?>
							<tr class="month">
								<th><?php echo __('Trong tháng',true)?></th>
								<td><?php echo $a_counter['month_value']?></td>
							</tr>
							<?php //}if(!empty($oneweb_counter['year'])){?>
							<tr class="year">
								<th><?php echo __('Trong năm',true)?></th>
								<td><?php echo $a_counter['year_value']?></td>
							</tr>
							<?php //}if(!empty($oneweb_counter['total'])){?>
							<tr class="total">
								<th><?php echo __('Tổng',true)?></th>
								<td><?php echo $a_counter['all_value']?></td>
							</tr>
							<?php }?>
							<?php //}?>
						</table>
					</div>
				</aside> <!--  end .box -->
			</div> <!-- end #tab2 -->
			
		</div> <!-- end .tab_container -->
	</div> <!--  end #content -->
</div> <!--  end #column_right -->