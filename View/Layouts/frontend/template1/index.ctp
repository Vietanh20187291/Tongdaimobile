<?php echo $this->Html->docType('html5');?>
<html lang="<?php echo $lang?>">

<head>
    <?php
        header("Cache-Control: public, max-age=3600"); // Chính sách cache: lưu trữ trong bộ nhớ cache trong 1 giờ
        header("Expires: " . gmdate("D, d M Y H:i:s", time() + 3600) . " GMT"); // Thời gian hết hạn: 1 giờ
    ?>

    <meta name="viewport" content="width=device-width, height=device-height">
    <meta name='dmca-site-verification' content='WUNLNFVJRHM1blAzTkNENm9mWWVFZz090' />


    <?php
		$controller = $this->params['controller'];
		$action = $this->params['action'];
		echo $this->Html->charset();
		echo $this->Html->meta('favicon.ico',$this->Html->url('/favicon.ico'),array('type'=>'icon'));
		echo '<title>'.((!empty($this->params['page']) && $this->params['page']>1)?$this->params['page'].' - ':'').$title_for_layout.'</title>';
		if(!empty($meta_keyword_for_layout)) echo $this->Html->meta('keywords',$meta_keyword_for_layout);
		if(!empty($meta_description_for_layout)) echo $this->Html->meta('description',$meta_description_for_layout);

		//Robots
		$robots = !empty($meta_robots_for_layout)?$meta_robots_for_layout:'index,follow';
		if(!empty($this->params['sort'])) $robots='noindex,nofollow';

// 		$robots='noindex,nofollow';	//***********************************************************************//
		if($robots!='index,follow') echo "<meta name='robots' content='$robots'>";
		//End Robots

		echo "<meta name='author' content='{$_SERVER['HTTP_HOST']}'>";
		if($controller == 'posts') {
            if(!empty($a_canonical)){
                echo "<link rel='amphtml' href='".$http_host.'amp'.$this->Html->url($a_canonical)."'/>";
            }
	    echo '<meta name="apple-mobile-web-app-capable" content="yes">';
	    echo '<meta name="apple-mobile-web-app-title" content="">';
	  }
		 if(!empty($a_canonical)){
		 	echo "<link rel='canonical' href='https://".$_SERVER['HTTP_HOST'].$this->Html->url($a_canonical)."'/>";		//Canonical
		 	if($_SERVER["REQUEST_URI"] != $this->Html->url($a_canonical) && !strpos($_SERVER["REQUEST_URI"],'?')) throw new NotFoundException(__('Trang này không tồn tại',true));
            // {	//Redirect về link chính xác - Tránh trg hợp cố tình nhập sai
		 	// 	header("Location: {$this->Html->url($a_canonical)}");
		 	// 	exit;
		 	// }
		 }
        echo $this->Html->css(array(
            'bootstrap/bootstrap.min',
            // 'bootstrap/bootstrap-theme.min',
            $oneweb_web['layout'].'/styles.min',

            // $oneweb_web['layout'].'/jquery.fancybox.min',
            // $oneweb_web['layout'].'/font-awesome.min',
        ));
        echo $this->fetch('css');
        echo $this->Html->script(array(

            'jquery-3.2.1.min',
            // 'bootstrap.min',
            // 'owl.carousel.min',
            // 'jquery.lockfixed',
            // 'oneweb',
            // 'jquery.carouFredSel',
            // 'jquery.marquee',
        ));
        echo $this->fetch('script');

		?>

    <meta property="fb:app_id" content="2677362302353573" />
    <meta property="og:locale" content="vi_VN" />
<!--    <meta property="og:type" content="--><?php //= $og_type ?><!--" />-->
    <meta property="og:url" content="<?= $this->Html->url(NULL, true) ?>" />
    <meta property="og:title" content="<?= (!empty($title_for_layout)?$title_for_layout:'') ?>" />
    <meta property="og:description"
        content="<?= (!empty($meta_description_for_layout)?$meta_description_for_layout:'') ?>" />
    <meta property="og:site_name" content="<?php echo $title_for_layout?>" />

    <?php if(!empty($og_image)){?>
        <meta property="og:image" content="<?php if (strpos($og_image,'https:/') !== false||strpos($og_image,'http:/')  !== false){echo $og_image;}else{echo $this->Html->url('/img/'.$og_image, true);} ?>" />
    <?php }else{?>
        <meta property="og:image" content="<?php echo $this->Html->url('/img/images/default-og-image.png', true);?>" />
    <?php } ?>

    <!--[if IE 7]>
		<?php echo $this->Html->css($oneweb_web['layout'].'/ie7')?>
	<![endif]-->
    <?php
		// Hiển thị các thẻ meta dc đặt từ quản trị
		if ($oneweb_advertisement['enable']) echo $this->element('frontend/c_meta_header');
	?>
</head>
<body class="<?php echo $controller ?>" >
    <?php if($controller == 'posts' && !empty($a_canonical['ext']) == 'html') { ?>
    <div style="width: 100%;height: 250px; display: flex;justify-content: center" >
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7762333905186367"
                crossorigin="anonymous"></script>
        <!-- Banner Trên cùng -->
        <ins class="adsbygoogle"
            style="display:inline-block;width:970px;height:250px"
            data-ad-client="ca-pub-7762333905186367"
            data-ad-slot="4211209450"
            data-full-width-responsive="true">
        </ins>
        <script>
            setTimeout(function(){(adsbygoogle = window.adsbygoogle || []).push({})}, 300);
        </script>
    </div>


    <?php } ?>

    <?php
        $cache_h_nav_top = Cache::read("cache_h_nav_top_$lang",'oneweb_view');
        if(!$cache_h_nav_top) {
            $cache_h_nav_top = $this->element('frontend/h_nav_top');
            Cache::write("cache_h_nav_top_$lang",$cache_h_nav_top,'oneweb_view');
        }
        echo $cache_h_nav_top;
        ?>
    <?php echo $this->element('frontend/header')?>
    <?php if($controller!='pages'){
        if(!empty($a_banner_run)){?>
    <div class="container m-t-15 m-b-15 hidden-xs">
        <div class="row">
            <?php if(count($a_banner_run) < 10){?>

                <ul class="header_maker nav">
                    <?php foreach($a_banner_run as $key => $val){
                        $item = $val['Banner'];
                        if (!empty($item)){
                            $link_attr = array('title'=>$item['name'],'target'=>$item['target']);
                            $link_img_attr = array_merge($link_attr, array('escape' => false));
                            if($item['rel']!='dofollow') $link_attr['rel'] = $item['rel'];
                            if(!empty($item['link']))
                            $link = $item['link'];
                            else
                            $link = 'javascript:;';
                            ?>
                <li class="col-xs-12">
                    <div class="thumb">
                        <?php
									if(!empty($item['image'])){
                                        echo $this->Html->link($this->OnewebVn->thumb('banners/'.$item['image'],array('width'=>$oneweb_banner['size'][5][0],'height'=>$oneweb_banner['size'][5][1],'zc'=>2,'class'=>'img-responsive img-center')),$link,$link_img_attr);
									}else{
                                        // echo $this->Html->link($this->Html->image('no_maker.jpg',array('class'=>'img-responsive img-center')),$link,$link_img_attr);
									}
                                    ?>
                    </div>
                    <div class="name text-center">
                        <?php echo $this->Html->link($item['name'],$link,$link_attr)?>
                    </div>
                </li>
                <?php }}?>
            </ul>
            <?php }else{
                echo $this->element('frontend/c_banner_run',array(
                    'data'=> $a_banner_run,
                    'position'=>5
                ));

            }?>
        </div>
    </div>
    <div
        class="container m-t-15 m-b-15 hidden-sm hidden-md hidden-lg <?php if($controller == 'products' && $this->params['ext'] == 'html') echo 'hidden-xs' ?>">
        <div class="row">
            <?php echo $this->element('frontend/c_banner_run',array(
									'data'=> $a_banner_run,
									'position'=>5
														)); ?>
        </div>
    </div>

    <?php }}?>
    <?php if($controller!='pages'){?>
    <div id="breadcrumb" class="full_width <?php if($controller=='pages') echo 'home'?>">
        <div class="container">
            <div class="row">
                <?php echo $this->element('frontend/c_breadcrumb');?>
            </div>
        </div>
    </div>
    <?php }?>

    <div id="content">
        <div class="container <?php if(!empty($class)) echo $class?>">
            <?php
				$col_left = $oneweb_web['column_left'];
				$col_right = $oneweb_web['column_right'];

				if(!empty($col_right)) $col_right = (isset($column_right)?$column_right:true);
				if(!empty($col_left)) $col_left = (isset($column_left)?$column_left:true);

				if(in_array($controller,array('information','faqs','contacts','orders','members'))) $col_right = false;
				if(in_array($controller,array('orders', 'pages'))) $col_left = false;

				$class_col = ((!$col_right)?' no_col_right':'').((!$col_left)?' no_col_left':'');
			?>
<style> @media(max-width: 991px){#columnSwap{display:flex;flex-direction:column}#post_sidebar{order:2}#post_content{order:1}}</style>
            <div class="row" id="columnSwap">
                <?php
					if($col_left && !$col_right)
						echo $this->element('frontend/left_column',array(
								'controller'=>$controller,
								'action'=>$action));

					elseif(!$col_left && $col_right)
						echo $this->element('frontend/right_column',array(
								'controller'=>$controller,
								'action'=>$action));
					elseif($col_left && $col_right)
						echo $this->element('frontend/both_column',array(
								'controller'=>$controller,
								'action'=>$action));
					else echo $content_for_layout;
				?>
            </div>
        </div> <!--  end .container -->
    </div>
    <?php
            $footer = Cache::read("footer_$lang",'oneweb_view');
            if(!$footer){
                $footer = $this->element('frontend/footer',array(
                    'controller'=>$controller));
                Cache::write("footer_$lang",$footer,'oneweb_view');
            }
            echo $footer;
            ?>
    <?php if(!empty($a_configs_h['hotline'])) { ?>
    <div id="call_top">
        <div class="full_width call_top">
            <span class="fa fa-phone"></span><?php echo __(' Hotline: '); ?><a
                href="tel:<?php echo $a_configs_h['hotline'] ?>" class="tel"><?php echo $a_configs_h['hotline'] ?></a>
        </div>
    </div>
    <?php } ?>

    <?php  //echo $this->element('frontend/widget_contact') ?>
    <!-- <div id="call">
		<a href="tel:<?php if ( ! empty($a_support_hotline)) echo $this->OnewebVn->rawPhone($a_support_hotline['phone']); ?>">
			<?php echo $this->Html->image('hotline-dong.gif', array('alt' => 'Gọi hotline', 'class' => 'hotline'))?>
		</a>
	</div> -->
    <?php if(!empty($a_banners_pos8)) { ?>
    <div id="fixed-adv-bottom">
        <?php
				foreach($a_banners_pos8 as $val)
				{
				$item = $val['Banner'];
				$attr = array('alt'=>$item['name'],'class'=>'img-responsive');
				if($oneweb_banner['size']['8'][0]!='n') $attr = array_merge($attr,array('width'=>$oneweb_banner['size']['8'][0]));
				if($oneweb_banner['size']['8'][1]!='n') $attr = array_merge($attr,array('height'=>$oneweb_banner['size']['8'][1]));

				$str_banner = $this->Html->image('images/banners/'.$item['image'],$attr);

				$link_attr = array('title'=>$item['name'],'target'=>$item['target'],'class'=>'','escape'=>false);
				if($item['rel']!='dofollow') $link_attr['rel'] = $item['rel'];

				if(!empty($item['link'])) $str_banner = $this->Html->link($str_banner,$item['link'],$link_attr);
		?>
        <?php echo $str_banner; ?>
        <?php
				}
		?>
        <div class="x-close">
            <?php echo $this->Html->image('X.png'); ?>
        </div>
    </div>
    <?php } ?>

    <div id="message_top">
        <p id="loading"><?php echo __('Đang xử lý...',true)?></p>
    </div>

    <div id="message_cart">
        <p class="success"><?php echo __('Đã thêm vào giỏ hàng thành công',true)?></p>
    </div>
    <!-- Quảng cáo popup -->
    <noscript>
        <div class="mesages_full">
            <div>
                <p><?php echo __('Bạn phải bật JavaScript',true)?></p>
            </div>
        </div>
    </noscript>

    <?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.')){		//Chặn trình duyệt IE 6 ?>
    <div class="mesages_full">
        <div>
            <p><?php echo __('Trình duyệt của bạn quá cũ, bạn phải nâng cấp lên phiên bản mới hơn để có thể truy cập vào trang web',true)?>
            </p>

            <span class="title"><?php echo __('Một số trình duyệt phổ biến',true).':'?></span>
            <ul>
                <li class="firefox"><a href="http://www.mozilla.org" title="FireFox" target="_blank">Firefox</a></li>
                <li class="chrome"><a href="https://www.google.com/intl/en/chrome/browser/" title="Chrome"
                        target="_blank">Chrome</a></li>
                <li class="ie"><a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie"
                        title="Internet Explorer" target="_blank">Internet Explorer</a></li>
                <li class="safari"><a href="http://www.apple.com/safari/" title="Safari" target="_blank">Safari</a></li>
            </ul>
        </div>
    </div>

    <?php }?>
    <?php
		if(!empty($a_popups) && $controller == 'pages') {
			$cache_popup = Cache::read('popup_oneweb', 'oneweb_view');
			if(empty($cache_popup)) {
				$cache_popup = $this->element('frontend/popup_banner',array('data'=>$a_popups));
				Cache::write('popup_oneweb', $cache_popup, 'oneweb_view');
				echo $cache_popup;
			}
		}
		if(@$a_site_info['enable']==false) echo $this->element('frontend/lock_web');
		echo $this->Session->flash();
		echo $this->element('sql_dump');
	?>
    <?php if(!empty($oneweb_product['order'])){?>



    <script>
        function gtag_report_conversionzalo(url) {
            gtag('event', 'conversion', {
                'send_to': 'AW-663378581/h7JMCImx8PwBEJW1qbwC',
                'event_callback': function() {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            return false;
        }

        function gtag_report_conversionfacebook(url) {
            gtag('event', 'conversion', {
                'send_to': 'AW-663378581/5n-UCLy05fwBEJW1qbwC',
                'event_callback': function() {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            return false;
        }

        function gtag_report_conversionsdt(url) {
            gtag('event', 'conversion', {
                'send_to': 'AW-663378581/9lhkCK-Hz_wBEJW1qbwC',
                'event_callback': function() {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            return false;
        }
    </script>

    <script type="text/javascript">
    //Show thông tin giỏ hàng
    function showCart() {
        $.ajax({
            type: 'post',
            url: '<?php echo $this->Html->url(array('controller'=>'orders','action'=>'ajaxShowCart','lang'=>$lang))?>',
            data: 'lang=<?php echo $lang?>',
            beforeSend: function() {
                $("#message_top").show();
            },
            success: function(result) {
                $("#popup_modal").html(result);
                $("#message_top").hide();
            }
        });
    }

    //Xóa sản phẩm khỏi giỏ hàng
    function delProCart(id) {
        var c = confirm("<?php echo __('Bạn có chắc chắn muốn xóa sản phẩm này không',true)?>?");
        if (c == true) {
            $.ajax({
                type: 'post',
                url: '<?php echo $this->Html->url(array('controller'=>'orders','action'=>'ajaxDelProCart','lang'=>$lang))?>',
                data: 'id=' + id,
                beforeSend: function() {
                    $("#message_top").show();
                },
                success: function(result) {
                    $(".number_product_cart").text(result);
                    showCart();
                    $("#order_info").load(location.href + " #order_info_content");
                    $("#message_top").hide();
                }
            });
        }
    }
    </script>
    <?php }?>
    <div id="popup_modal" class="modal fade" role="dialog"></div>

    <!-- Facebook livechat -->
    <?php
		if(!empty($oneweb_support['livechat']) && !empty($a_site_info['facebook'])){
		if($lang == 'es') $jlang = 'es_SP';
		elseif($lang == 'vi') $jlang = 'vi_VN';
		elseif($lang == 'pt') $jlang = 'pt_PT';
		else $jlang = 'en_EN';
	?>
    <div id="fb-root"></div>
    <script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/<?php echo $jlang?>/sdk.js#xfbml=1&version=v2.5";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>
    <script>
    jQuery(document).ready(function() {
        jQuery(".chat_fb").click(function() {
            jQuery('.fchat').slideToggle("slow", function() {
                if ($('#fb_btn .glyphicon').hasClass('glyphicon-menu-down')) {
                    $("#fb_btn .glyphicon").removeClass('glyphicon-menu-down');
                    $("#fb_btn .glyphicon").addClass('glyphicon-menu-up');
                } else {
                    $("#fb_btn .glyphicon").removeClass('glyphicon-menu-up');
                    $("#fb_btn .glyphicon").addClass('glyphicon-menu-down');
                }
            });
        });
    });
    </script>
    <div id="cfacebook">
        <a href="javascript:;" class="chat_fb col-xs-12" onclick="return false;"><span
                class="icon_oneweb icon_facebook"></span><?php echo __('Chat facebook',true)?><span id="fb_btn"
                class="pull-right"><span class="glyphicon glyphicon-menu-up"></span></span></a>
        <div class="fchat">
            <div class="fb-page" data-tabs="messages" data-href="<?php echo $a_site_info['facebook']; ?>"
                data-width="250" data-height="260" data-small-header="true" data-adapt-container-width="true"
                data-hide-cover="false" data-show-facepile="true" data-show-posts="false"></div>
        </div>
    </div>
    <?php
		}
	?>
    <?php
		// Mã tiếp thị
		if ($oneweb_advertisement['enable']) echo $this->element('frontend/c_google_analytics');
	?>

    <script>
    function gtag_report_conversion(url) {
        gtag('event', 'conversion', {
            'send_to': 'AW-663378581/le2RCNHRqsoBEJW1qbwC',
            'event_callback': function() {
                if (url) {
                    window.location = url;
                }
            }
        });
        return false;
    }
    </script>
    <?php echo $this->Html->script(array(
                    'bootstrap.min',
			        'jquery.fancybox.min',
                    'scripts',
                    'jquery.lockfixed',
                    'oneweb',
                    // 'owl.carousel.min',
                    // 'jquery.carouFredSel',
                    // 'jquery.marquee',
    ))?>
    <script>
        $(window).scroll(function () {
            if ($(window).width() <= 991) {
                var currentPosition = $(window).scrollTop();
                var scroll = $(this).scrollTop();
                var height = $('.navbar-oneweb').height() + 'px';
                var h_top = $('#header_top').height() + $('#header').height();
                if (scroll > (h_top + 200)) {
                    $('.navbar-oneweb').addClass('fixed_top');
                } else {
                    $('.navbar-oneweb').removeClass('fixed_top');
                }
            }
        });
    </script>
</body>
    <!--[if lt IE 9]>
		<?php echo $this->Html->script(array('html5.js','respond.min'))?>
	<![endif]-->

</html>
