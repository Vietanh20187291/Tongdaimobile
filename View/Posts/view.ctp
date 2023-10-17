<!-- start posts/view.ctp -->
<?php
	$item_post = $a_post_c['Post'];
	$item_cate = $a_post_c['PostCategory'];
?>
<article class="box_content read">
	<div class="bg_white clearfix">
		<header class="title">
			<h1><?php echo $title_for_layout?></h1>
		</header>
		<div class="des">
			<div class="post_des ">
			<?php echo $item_post['description']; ?>
			<?php if(!empty($oneweb_post['rate'])){?>
			<div class="rate">
					<span><?php echo __('Đánh giá',true)?>: </span>
					<?php echo $this->element('frontend/c_rate',array(
												'item_id'=>$item_post['id'],
												'model'=>'Post',
												'star_rate'=>$item_post['star_rate'],
												'star_rate_count'=>$item_post['star_rate_count']
											));
					?>
			</div>
			<?php }?>

			<?php if(!empty($oneweb_post['tag']) && !empty($item_post['tag'])){?>
			<div class="tag">
				<span>Tags: </span>
				<p>
					<?php
					foreach($item_post['tag'] as $val)
						echo $this->Html->link($val['name'],array('controller'=>'tags','action'=>'index','lang'=>$lang,'slug'=>$val['name']),array('title'=>$val['meta_title'],'rel'=>'tag','class'=>'tooltip')).', ';
					?>
				</p>
			</div>
			<?php }?>
			</div>
			<div class="clear"></div>
			<div class="p_readmore text-center p-y-15" style="display: none">
				<button class="btn btn-default btn_viewmore" id="b_readmore">Đọc thêm <span class="fa fa-caret-down"></button>
			</div>
			<hr class="m-b-30">
			<!-- <script type="text/javascript">
				function viewMore(){
					var height = $('.des .post_des').height();
					if(height > 1200){
						$('.p_readmore').show();
						$('.des .post_des').addClass('height_limit');
					}
				}
				$('#b_readmore').click(function(){
					if($('.des .post_des').hasClass('height_limit')){
						$('.des .post_des').removeClass('height_limit');
						$('#b_readmore').html('Thu gọn <span class="fa fa-caret-up">');
					}else{
						$('.des .post_des').addClass('height_limit');
						$('#b_readmore').html('Đọc thêm <span class="fa fa-caret-down">');
					}
				});
				$(document).ready(function() {
					viewMore();
				});
			</script> -->
			<!-- Liên hệ tư vấn -->
			<div class="row">
				<div class="col-xs-12 col-sm-8 col-sm-offset-2">
					<h3 class="contact-title">Yêu cầu tư vấn</h3>
					<?php echo $this->Form->create('Contact', array('url' => array('controller' => 'contacts', 'action' => 'request_support', 'lang' => $lang), 'inputDefaults' => array('div' => false, 'label' => false))) ?>
						<div class="form-group hidden">
							<?php
								echo $this->Form->input('position',array('class'=>'form-control'))
							?>
						</div>
						<div class="form-group hidden">
							<?php
								echo $this->Form->label('subject',__('Tiêu đề',true).' <span class="im">*</span>', array('for' => 'postSubject'));
								echo $this->Form->input('subject',array('class'=>'form-control','id' => 'postSubject'))
							?>
						</div>
						<div class="form-group">
							<?php
								echo $this->Form->label('name',__('Họ và tên',true).' <span class="im">*</span>', array('for' => 'postName'));
								echo $this->Form->input('name',array('class'=>'form-control','id' => 'postName'));
							?>
						</div>
						<div class="form-group">
							<?php
								echo $this->Form->label('phone',__('Điện thoại',true).' <span class="im">*</span>', array('for' => 'postPhone'));
								echo $this->Form->input('phone',array('class'=>'form-control','id' => 'postPhone'))
							?>
						</div>
						<div class="form-group">
							<?php
								echo $this->Form->label('email',__('Email',true), array('for' => 'postEmail'));
								echo $this->Form->input('email',array('class'=>'form-control','id' => 'postEmail'))
							?>
						</div>
						<div class="form-group">
							<?php
								echo $this->Form->label('message',__('Vấn đề cần tư vấn',true));
								echo $this->Form->textarea('message',array('class'=>'form-control'))
							?>
						</div>
						<div class="form-group text-center">
							<?php echo $this->Form->submit(__('Gửi yêu cầu'),array('class'=>'btn btn-default', 'div' => false))?>
						</div>
					<?php echo $this->Form->end();?>
				</div>
			</div>

			<?php if(!empty($oneweb_post['comment'])) echo $this->element('frontend/c_comment',array(
																									'item_id'=>$item_post['id'],
																									'model'=>'Post'
																								))
			?>
			<?php if(!empty($oneweb_post['comment_face'])) echo $this->element('frontend/c_comment_face',array(
																									'url'=>$this->request->url,
																									'width'=>'100%'
																							));
			?>
			<?php if(!empty($oneweb_post['comment_google'])) echo $this->element('frontend/c_comment_google',array(
																									'url'=>$this->request->url,
																									'width'=>'100%'
																							));
			?>

			<?php if(!empty($a_related_posts_c)){
			//Kích thước ảnh thumbnail
			$w=400;
			$full_size = $oneweb_post['size']['post'];
			$h = intval($w*$full_size[1]/$full_size[0]);
			?>
			<section class="related">
				<header>
				<div class="title">
					<span class="icon_oneweb"></span>
				</div>
				</header>
				<?php
					echo $this->element('frontend/c_post_related',array(
																		'data'		=> $a_related_posts_c,
																		'class'		=> 'post',
																		'limit'		=>120,
																		'datetime'	=>false,
																		'w'			=> 400,
																		'zc'		=> 1
																	));
				?>
			</section>
			<?php }?>
		</div>
	</div>
</article>
<script>
	function adjustYoutubeIframeHeight() {
		var width = $(window).width();
		var height = (width < 793) ? 250 : 500;
		
		$('iframe[src*="youtube.com"]').css('height', height + 'px');
	}

	$(document).ready(adjustYoutubeIframeHeight);
	$(window).resize(adjustYoutubeIframeHeight);
</script>

<script>
	const images = document.querySelectorAll('img');

	images.forEach(img => {
		let src = img.getAttribute('src');
		if (!src || src.trim() === '') {
			img.parentNode.removeChild(img);
		}
		img.onerror = function() {
			img.parentNode.removeChild(img);
		}
	});
</script>
<!-- end posts/view.ctp -->

<script>
    const related = document.querySelector('.related');
    if (related) {
		const images = related.querySelectorAll('a img');
		
        images.forEach(img => {
			let src = img.getAttribute('src');
            if (!src || src.trim() === '') {
                img.setAttribute('src','https://hstatic.net/620/1000063620/10/2016/4-16/4g-la-gi.jpg');
                img.style.objectFit = 'cover';
            }
            img.onerror = function() {
                img.setAttribute('src','https://hstatic.net/620/1000063620/10/2016/4-16/4g-la-gi.jpg');
                img.style.objectFit = 'cover';
            }
        });
    }
	//  Lấy 3 thẻ H2 đầu tiên    ------------------------------------------------------------------------
	// function getFirstListItems() {
	// 	const tocList = document.querySelector('#toc_container>ul');
	// 	console.log(tocList);
	// 	if (tocList) {
	// 		const firstLiElements = Array.from(tocList.querySelectorAll('ul>li')).slice(0, 3);
	// 		const itemList = firstLiElements.map(li => {
	// 			const linkElement = li.querySelector('a');
	// 			return {
	// 				text: li.textContent.trim(),
	// 				href: linkElement ? linkElement.getAttribute('href') : null
	// 			};
	// 		});
	// 		return itemList;
	// 	}
	// 	return [];
	// }
	//
	// // Gọi hàm và in ra danh sách các thẻ <li> đầu tiên
	// const firstLiItems = getFirstListItems();
	//
	// firstLiItems.forEach((item, index) => {
	// 	// Loại bỏ số thứ tự và dấu cách sau số thứ tự
	// 	const updatedText = item.text.replace(/^\d+\.\s*/, '');
	//
	// 	// Thay thế phần text trong mảng dữ liệu bằng phần text đã được loại bỏ số thứ tự
	// 	firstLiItems[index].text = updatedText;
	// });
	// console.log(firstLiItems);
	// var itemHTML = ``;
	// // Khai báo biến chứa đoạn mã HTML
	// // firstLiItems.map()
	// for (const key in firstLiItems) {
	// 	if (firstLiItems.hasOwnProperty(key)) {
	// 		const itemData = firstLiItems[key];
	//
	// 		itemHTML += `<div class="item clearfix" itemscope="" itemtype="http://schema.org/Event">
    //     <span class="date" itemprop="startDate" content="2021-01-01T16:14:02+0000">
    //     </span>
    //     <meta itemprop="endDate" content="2030-01-01T16:14:02+0000">
    //     <a itemprop="url" href="${itemData.href}" title="⭐ ${itemData.text}">
    //         <span itemprop="name">⭐ ${itemData.text}</span>
    //     </a>
    //     <span itemprop="location" itemscope="" itemtype="http://schema.org/Place">
    //         <meta itemprop="name" content="Thiết Bị Y Tế MEMART">
    //         <meta itemprop="url" content="${itemData.href}">
    //         <span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
    //             <meta itemprop="streetAddress" content="Số 49, TT16, Khu đô thị Văn Phú, P. Phú La, Q. Hà Đông">
    //             <meta itemprop="addressLocality" content="HN">
    //             <meta itemprop="addressRegion" content="Việt Nam">
    //         </span>
    //     </span>
    //     <span itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
    //         <meta itemprop="price" content="0">
    //         <meta itemprop="priceCurrency" content="VND">
    //         <meta itemprop="url" content="${itemData.href}">
    //     </span>
    // </div>`;
	// 	}
	// }
	//
	// // Lấy đối tượng <div id="item_scope">
	// const itemScopeDiv = document.getElementById("item_scope");
	// console.log(itemScopeDiv);
	// // Thêm nội dung vào <div id="item_scope">
	// itemScopeDiv.innerHTML = itemHTML;
	//

	//     ------------------------------------------------------------------------
</script>
