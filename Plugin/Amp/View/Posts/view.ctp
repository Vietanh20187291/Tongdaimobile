<!-- start posts/view.ctp -->
<?php
	$item_post = $a_post_c['Post'];
	$item_cate = $a_post_c['PostCategory'];
	// $str = '-webkit-transform:rotate(0.00rad); margin-right:5px; height:5px; font-size:12px; width:10px;';
	// $str = preg_replace_callback('/height:([\d]*)[^>]*width:([\d]*)/', 
	// 	function ($f) {
	// 		debug($f);
	// 		return $f[0];
	// 	}, $str);
	// debug($str);die;
?>
<article class="box_content read">
	<div class="bg_white clearfix">
		<header class="title">
			<h1><?php echo $item_post['name']?></h1>
		</header>
		<div class="des">
			<?php 
				$remove_tag_font = preg_replace('/(<font[^>]*>)|(<\/font>)/', '', $item_post['description']);
				$replace_tel = str_replace('http://tel', 'tel', $remove_tag_font);
				$remove_important = str_replace('!important;', '', $replace_tel);
				// // $result_style = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $result_img);
	   //      //iframe
	      $iframe = preg_replace('/(<iframe[^>]+>(?:<\/iframe>)?)/i', '$1</amp-iframe></div>', $remove_important);
	      $amp_iframe = str_replace('<iframe', '<div class="amp-iframe"><amp-iframe sandbox="allow-scripts allow-same-origin" layout="responsive" frameborder="0"', $iframe);
				
				$amp_img = preg_replace_callback('/<img[\s*]alt="([^"]*)"[\s*]src="([^"]*)"([\s*]?)((.*?)+?)>/',
	        function ($found) {
					    // $size = (getimagesize('https://hinlet.com'.$found[2]));
					    // preg_match( '/(<[^>]+) style=".*?"/i', $found[2], $size );
					    $str = preg_replace_callback('/[^>]*height:([\d]*)px[^>]*width:([\d]*)px/', 
							function ($f) {
								return 'height="' . $f[1] . '" width="'. $f[2];
							}, $found[4]);
							$str = str_replace('style="', '', $str);
							$str = str_replace('/', '', $str);
							
							if(!empty(strpos($found[2], 'gif'))) {
								return '<amp-img src="' . $found[2] . '" '.$str.' layout="fixed" alt="' . $found[1] .'"></amp-img>';
							} else {
								return '<amp-img src="' . $found[2] . '" '.$str.' layout="responsive" alt="' . $found[1] .'"></amp-img>';
							}
					}, $amp_iframe);

					echo preg_replace_callback('/<img[\s*]src="([^"]*)"([\s*]?)((.*?)+?)>/',
		        function ($found) {
						    // $size = (getimagesize('https://hinlet.com'.$found[2]));
						    // preg_match( '/(<[^>]+) style=".*?"/i', $found[2], $size );[^"]
						    $str = preg_replace_callback('/[^>]*height:([\d]*)px[^>]*width:([\d]*)px/', 
								function ($f) {
									return 'height="' . $f[1] . '" width="'. $f[2];
								}, $found[3]);
								$str = str_replace('style="', '', $str);
								$str = str_replace('/', '', $str);
						    return '<amp-img src="' . $found[1] . '" '.$str.' layout="fixed" alt=""></amp-img>';
						}, $amp_img);
         ?>
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


			<!-- Liên hệ tư vấn -->
			<div class="row text-center">
					<h3 class="contact-title"><?php echo $this->Html->link('Yêu cầu tư vấn', rtrim($http_host,'/').$this->Html->url($origin_url) , array('title'=>'Yêu cầu tư vấn', 'class'=>'btn btn-default')) ?></h3>
			</div>			

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
					<span class="font-weight-bold"><?php echo __('Bài viết liên quan',true)?></span>
				</div>
				</header>
				<?php
					echo $this->element('c_post_related',array(
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
<!-- end posts/view.ctp -->