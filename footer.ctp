<!-- start footer.ctp -->
<?php
	$controller = $this->params['controller'];
	$action = $this->params['action'];
?>
<?php
	if ($controller == 'pages' && $action == 'home')
	{
?>
	<div id="sub-footer-1">
		<div class="container">
			<div class="row services">
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
					<i class="icon_oneweb icon-1"></i>
					<p class="text">
						<span class="big"><?php echo __('Sản phẩm chính hãng'); ?></span>
						<span class="small"><?php echo __('Mẫu mã đa dạng phong phú'); ?></span>
					</p>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
					<i class="icon_oneweb icon-2"></i>
					<p class="text">
						<span class="big"><?php echo __('Giá luôn rẻ nhất'); ?></span>
						<span class="small"><?php echo __('Khuyễn mãi không ngừng'); ?></span>
					</p>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
					<i class="icon_oneweb icon-3"></i>
					<p class="text">
						<span class="big"><?php echo __('Phục vụ tận tâm'); ?></span>
						<span class="small"><?php echo __('Khách hàng là trên hết'); ?></span>
					</p>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
					<i class="icon_oneweb icon-4"></i>
					<p class="text">
						<span class="big"><?php echo __('Hotline tư vấn'); ?></span>
						<span class="hotline">
							<?php
								foreach ($a_support_s as $val)
								{
									$item_support = $val['Support'];
									if (strtolower($item_support['name']) == 'hotline') {
										echo $item_support['phone'];
										break;
									}
								}
							?>
						</span>
					</p>
				</div>
			</div>
		</div>
	</div>
<?php
	}
?>
<div id="sub-footer-2" class="<?php if ($controller != 'pages' && $action != 'home') echo 'line-top'; ?>">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<?php
					if ( ! empty($a_site_info['footer2']))
					{
						// echo $a_site_info['footer2'];
						// preg_match( '@src="([^"]+)"@' , $a_site_info['footer2'] , $match );
						# $result = preg_replace('/(<img[^>]+>(?:<\/img>)?)/i', '$1</amp-img>', $a_site_info['footer2']);
		    #     $result_img =  str_replace('<img', '<amp-img layout="fixed" height="1" width="1"', $result);
		    #     echo preg_replace('/(<[^>]+) style=".*?"/i', '$1', $result_img);
		        echo preg_replace_callback('/<img[\s*]alt="([^"]*)"[\s*]src="([^"]*)"([\s*]?)((.*?)+?)>/',
		        function ($found) {
						    // $size = (getimagesize('https://hinlet.com'.$found[2]));
						     $str = preg_replace_callback('/[^>]*height:([\d]*)px[^>]*width:([\d]*)px/', 
								function ($f) {
									return 'height="' . $f[1] . '" width="'. $f[2];
								}, $found[4]);
								$str = str_replace('style="', '', $str);
								$str = str_replace('/', '', $str);

						    return '<amp-img src="' . $found[2] . '" '.$str.' layout="fixed" alt="' . $found[1] .'"></amp-img>';
						}, $a_site_info['footer2']);
					}
				?>
			</div>
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
				<div class="support-wrapper">
					<h4><?php echo __('Tổng đài hỗ trợ'); ?></h4>
					<ul class="supporters">
						<?php
							foreach ($a_support_s as $val)
							{
								$item_support = $val['Support'];
						?>
							<li>
								<i class="fa fa-phone" aria-hidden="true"></i>
								<?php echo $item_support['name'].': <span class="phone">'.$item_support['phone'].'</span>'; ?>
							</li>
						<?php
							}
						?>
					</ul>
				</div>
				<div class="social-wrapper">
					<h4><?php echo __('Kết nối với chúng tôi'); ?></h4>
					<p class="socials">
						<?php
							if ( ! empty($a_site_info['facebook']))
							{
								echo $this->Html->link('<i class="fa fa-facebook-square" aria-hidden="true"></i>', $a_site_info['facebook'],array('title'=>'Facebook','rel'=>'nofollow','class'=>'','target'=>'_blank','escape'=>false));
							}
						?>
						<?php
							if ( ! empty($a_site_info['google']))
							{
								echo $this->Html->link('<i class="fa fa-google-plus-square" aria-hidden="true"></i>', $a_site_info['google'],array('title'=>'Google','rel'=>'nofollow','class'=>'','target'=>'_blank','escape'=>false));
							}
						?>
						<?php
							if ( ! empty($a_site_info['twitter']))
							{
								echo $this->Html->link('<i class="fa fa-twitter-square" aria-hidden="true"></i>', $a_site_info['twitter'],array('title'=>'Twitter','rel'=>'nofollow','class'=>'','target'=>'_blank','escape'=>false));
							}
						?>
						<?php
							if ( ! empty($a_site_info['youtube']))
							{
								echo $this->Html->link('<i class="fa fa-youtube-square" aria-hidden="true"></i>', $a_site_info['youtube'],array('title'=>'Youtube','rel'=>'nofollow','class'=>'','target'=>'_blank','escape'=>false));
							}
						?>
					</p>
				</div>
				<address>
					<?php if (! empty($a_site_info['footer'])) {
						// echo $a_site_info['footer'];
						$replace_tel = str_replace('http://tel', 'tel', $a_site_info['footer']);
						$amp_img = preg_replace_callback('/<img[\s*]alt="([^"]*)"[\s*]src="([^"]*)"([\s*]?)((.*?)+?)>/',
		        function ($found) {
						    // $size = (getimagesize('https://hinlet.com'.$found[2]));
						    if($found[4] == '/') {
						    	$str = 'height="20" width="100"';
						    } else {
							    $str = preg_replace_callback('/height:([\d]*)px[^>]*width:([\d]*)px/', 
									function ($f) {
										return 'height="' . $f[1] . '" width="'. $f[2];
									}, $found[4]);
									$str = str_replace('style="', '', $str);
									$str = str_replace('/', '', $str);  
								}
						    return '<amp-img src="' . $found[2] . '" '.$str.' layout="fixed" alt="' . $found[1] .'"></amp-img>';
						}, $replace_tel);
						echo preg_replace_callback('/<img[\s*]src="([^"]*)"([\s*]?)((.*?)+?)>/',
		        function ($found) {
						    // $size = (getimagesize('https://hinlet.com'.$found[2]));
						    $str = preg_replace_callback('/[^>]*height:([\d]*)px[^>]*width:([\d]*)px/', 
								function ($f) {
									return 'height="' . $f[1] . '" width="'. $f[2];
								}, $found[3]);
								$str = str_replace('style="', '', $str);
								$str = str_replace('/', '', $str);  

						    return '<amp-img src="' . $found[1] . '" '.$str.' layout="fixed" alt=""></amp-img>';
						}, $amp_img);

					}?>
				</address>
			</div>
	</div>
</div>

<?php
	if ( ! empty($a_site_info['footer_mobile']))
	{
?>
<div id="copyright">
	<div class="container">
		<div class="row">
			<!-- Liệt kê tags -->
			<?php if ( ! empty($oneweb_product['tag']) || ! empty($oneweb_post['tag'])) { ?>
			<div class="footer-tags">
				<div class="container">
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<?php
								$i = 0;
								$len = count($a_footer_tags);
								foreach ($a_footer_tags as $k => $val) {
									$item = $val['Tags'];
									$url = array('plugin'=>false,'controller' => 'tags','action' => 'index', 'lang' => $lang, 'id' => $item['id'], 'slug' => $item['slug']);
									echo $this->Html->link($this->OnewebVn->capitalFirstLetterVietnamese($item['name']), $url, array('title' => $item['name']));
									if ($i == $len - 1) echo ''; else echo ' | ';
									$i++;
								}
							?>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
			<?php  
			$result = preg_replace('/(<img[^>]+>(?:<\/img>)?)/i', '$1</amp-img>', $a_site_info['footer_mobile']);
        $result_img =  str_replace('<img', '<amp-img layout="responsive" height="7" width="5"', $result);
        echo preg_replace('/(<[^>]+) style=".*?"/i', '$1', $result_img);
        
        ?>
		</div>
	</div>
</div>

<?php
	}
?>
<div class="block-wiget">
    <ul class="ul-wiget">
        <li class="hotline-wiget">
            <a href="tel:0989427809">
                <img src="https://img.icons8.com/ios-filled/25/ffffff/phone.png"/>
                <span>Gọi ngay</span>
            </a>
        </li>
        <li class="zalo-wiget" id="zalo-wiget">
            <a target="_blank" rel="nofollow" href="https://chat.zalo.me/?phone=0967840437">
                <img src="https://img.icons8.com/ios-filled/25/ffffff/zalo.png"/>
                <span>Chat Zalo</span></a>

        </li>
        <li class="face-wiget">
            <a target="_blank" rel="nofollow" href="https://www.facebook.com/RdSic">
                <img src="https://img.icons8.com/ios-filled/25/ffffff/facebook-messenger--v2.png"/>
                <span>Chat Facebook</span>
            </a>
        </li>

    </ul>
</div>
<div class=" block-wiget-bottom ">
    <div class="wiget-bottom">
        <div class="row">
                <a href="" class="col-2">
                </a>
                <a href="tel:0989427809" class="col-3">
                    <img class="widget_img" style="width: 45px" ;height="45px" src="client/image/whatsapp.svg"/>
                </a>
                <a class="col-2"  href="https://zalo.me/0967840437" >
                    <img class="widget_img" style="width: 45px" ;height="45px"  src="client/image/zalo.svg">
                </a>


                <a class="col-3" target="_blank" rel="nofollow" href="https://www.facebook.com/RdSic">
                    <img class="widget_img" style="width: 45px" ;height="45px"  src="client/image/messenger.svg"/>
                </a>
            <a href="" class="col-2">
            </a>
        </div>
    </div>
</div>
<!-- end footer.ctp -->