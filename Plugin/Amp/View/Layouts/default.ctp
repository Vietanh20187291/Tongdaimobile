<?php echo $this->Html->docType('html5');?>
<html ⚡ lang="vi">
<head>
    <meta charset="utf-8">
    <link rel='canonical' href='<?php echo rtrim($http_host,'/').$this->Html->url($origin_url) ?>'/>
	<meta name="viewport" content="width=device-width,minimum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-title" content="hinlet.com" />

    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "NewsArticle",
            "mainEntityOfPage": "<?php echo $http_host.$this->Html->url($a_canonical)?>",
            "headline": "<?php //echo $title_for_layout ?>",
            "image": {
                "@type": "ImageObject",
                "url": "<?php //echo $this->Html->url('/img/images/posts/'.$a_post_c['Post']['image'],true)?>",
                "width": 254,
                "height": 190
            },
            "description": "<?php //echo $meta_description_for_layout ?>",
            "datePublished": "<?php //echo date('Y-m-dTH:i:s+07:00', $a_post_c['Post']['created']) ?>",
            "dateModified": "<?php //echo date('Y-m-dTH:i:s+07:00', $a_post_c['Post']['modified']) ?>",
            "publisher": {
                "@type": "Organization",
                "name": "Url",
                "logo": {
                    "@type": "ImageObject",
                    "url": "https://hinlet.com/img/logo.png",
                    "width": 590,
                    "height": 59
                }
            },
            "author": {
                "@type": "Person",
                "name": "BTV Url"
            }
        }
    </script>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
    <script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
    <script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>
    <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
    <style amp-custom>
        <?php include APP.'Plugin'.DS.'Amp'.DS.'webroot'.DS.'css'.DS.'styles.css'; ?>
    </style>
    
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
</head>
<body>
    <amp-analytics type="gtag" data-credentials="include">
        <script type="application/json">
        {
          "vars" : {
            "gtag_id": "UA-31155264-1",
            "config" : {
              "UA-31155264-1": { "groups": "default" }
            }
          }
        }
        </script>
    </amp-analytics>

    <amp-sidebar id="sidebar-left"
        layout="nodisplay"
        side="left" class="hidden-md hidden-lg">
        <!-- <amp-img class="amp-close-image"
            width="20"
            height="20"
            alt="close sidebar"
            on="tap:sidebar-left.close"
            role="button"
            tabindex="0">x</amp-img> -->
        <?php
    $controller = $this->params['controller'];
    $action = $this->params['action'];
?>
<!-- Begin menu -->
    <nav class="navbar navbar-transform" role="navigation">
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li  class="<?php if($controller=='pages') echo 'active'?>"><?php echo $this->Html->link($this->Html->tag('span',__('Trang chủ',true)),array('controller'=>'pages','action'=>'home','lang'=>$lang),array('title'=>__('Trang chủ',true),'escape'=>false))?></li>
                <li id="product-nav-tree" class="dropdown banner mobile open">
                    <a class="" data-toggle="collapse" data-target="nav_category_list">Danh mục sản phẩm<span class="fa fa-bars" id="toggle-product-menu"></span>
                        <div class="submenu-caret-wrapper"><span class="caret"></span></div>
                    </a>

                        <?php echo $this->HtmlAmp->productCategoryNavBanner($a_product_categories_s,0)?>
                </li>
            
                <?php
                if(!empty($a_post_categories_s)){
                    $count = 0;
                    foreach($a_post_categories_s as $key=>$val){
                    $item_cate =$val['PostCategory'];
                    if(empty($item_cate['link'])){
                        $url = array('plugin'=>false, 'controller'=>'posts','action' => 'index','lang'=>$item_cate['lang'],'position'=>$item_cate['position']);
                        $tmp = $item_cate['slug'];
                        for($i=0;$i<count($tmp);$i++){
                            $url['slug'.$i]=$tmp;
                        }
                    }else $url = $item_cate['link'];
                    $link_attr = array('title'=>$item_cate['meta_title'],'target'=>$item_cate['target']);
                    if($item_cate['rel']!='dofollow') $link_attr['rel'] = $item_cate['rel'];
                    if(in_array($item_cate['position'], array(1,2,3)) && $count < 4) {
                        if(!empty($val['children'])){
                        ?>
                        <li class="dropdown">
                            <?php echo $this->Html->link($item_cate['name'].$this->Html->tag('span class="caret"'),$url,array('class'=>'dropdown-toggle','data-toggle'=>"dropdown",'escape'=>false))?>
                            <ul class="dropdown-menu">
                            <?php foreach ($val['children'] as $key1=>$val1){
                                    $item_cate1 =$val1['PostCategory'];
                                    if(empty($item_cate1['link'])){
                                        $url1 = array('plugin'=>false, 'controller'=>'posts','action' => 'index','lang'=>$item_cate1['lang'],'position'=>$item_cate1['position']);
                                        $tmp1 = $item_cate1['slug'];
                                        for($i=0;$i<count($tmp1);$i++){
                                            $url1['slug'.$i]=$tmp1;
                                        }
                                    }else $url1 = $item_cate1['link'];
                                    $link_attr1 = array('title'=>$item_cate1['meta_title'],'target'=>$item_cate1['target'],'escape'=>false);
                                    if($item_cate1['rel']!='dofollow') $link_attr1['rel'] = $item_cate1['rel'];
                                ?>
                                <li><?php echo $this->Html->link($item_cate1['name'].'<i class="icon-arrow-right"></i>',$url1,$link_attr1)?></li>
                            <?php }?>
                            </ul>
                        </li>
                        <?php }else{?>
                        <li><?php echo $this->Html->link($item_cate['name'],$url,$link_attr)?></li>
                        <?php }
                        $count ++;
                }}}?>
                
                <?php if(!empty($a_information_nav)) echo $this->HtmlAmp->linkInformation($a_information_nav,array(8),$sub=true)?>

                <!-- Liên hệ -->
                <?php if(!empty($oneweb_product['maker'])){?>
                    <li class="dropdown auto_dropdown <?php if($controller=='products' && $action == 'maker') echo 'active'?>">
                        <?php echo $this->Html->link($this->Html->tag('span',__('Thương hiệu',true)).'<div class="submenu-caret-wrapper"><span class="caret"></span></div>','#',array('title'=>__('Our Tours',true),'class'=>'dropdown-toggle','data-toggle'=>"dropdown",'escape'=>false));
                        ?>
                        <?php if(!empty($a_product_makers_s)){?>
                        <ul class="dropdown-menu menu_marker">
                            <?php foreach($a_product_makers_s as $val){
                                $item_maker = $val['ProductMaker'];
                                if (!empty($item_maker)){
                                    $link_maker_attr = array('title'=>$item_maker['meta_title'],'target'=>$item_maker['target']);
                                    if($item_maker['rel']!='dofollow') $link_maker_attr['rel'] = $item_maker['rel']; 
                            ?>
                            <li><?php echo $this->Html->link($item_maker['name'],array('plugin'=>false, 'controller'=>'products','action'=>'maker','lang'=>$lang,'slug'=>$item_maker['slug']),$link_maker_attr)?></li>
                            <?php }}?>
                        </ul>
                        <?php }?>
                    </li>
                    <?php }
                ?>
                <?php
                    if ( ! empty($oneweb_contact['enable']))
                    {
                ?>
                    <li class="<?php if($controller=='contacts') echo ' current'?>"><?php echo $this->Html->link($this->Html->tag('span',__('Liên hệ',true)),array('plugin'=>false, 'controller'=>'contacts','action'=>'index','lang'=>$lang),array('title'=>__('Liên hệ',true),'rel'=>'nofollow','escape'=>false))?>
                    </li>
                <?php
                    }
                ?>
            </ul>
        </div><!-- .navbar-collapse -->
    </nav>
<!-- End Menu -->
    </amp-sidebar>
    <?php echo $this->element('h_nav_top')?>
    <?php echo $this->element('header')?>

    <div class="container m-t-15 m-b-15">
        <div class="row">
            <?php echo $this->element('c_banner_run',array(
                                'data'=> $a_banner_run,
                                'position'=>5
                                                    )); ?>
        </div>
    </div>
    <?php if($controller!='pages'){?>
    <div id="breadcrumb" class="full_width <?php if($controller=='pages') echo 'home'?>">
        <div class="container">
            <div class="row">
                <?php echo $this->element('c_breadcrumb');?>
            </div>
        </div>
    </div>
    <?php }?>
    <div id="content">
        <div class="container <?php if(!empty($class)) echo $class?>">
            <div class="row">
                <div class="col-xs-12 col-sm-12 m-b-15">
                    <?php echo $content_for_layout;?>
                </div>
            </div>
        </div> <!--  end .container -->
    </div>

    <?php echo $this->element('footer'); ?>
    <?php if(!empty($a_configs_h['hotline'])) { ?>
    <div id="call_top">
        <div class="container call_top">
            <span class="fa fa-phone"></span><?php echo __(' Hotline: '); ?><a href="tel:<?php echo $a_configs_h['hotline'] ?>" class="tel"><?php echo $a_configs_h['hotline'] ?></a>
        </div>
    </div>
    <?php } ?>
    <div id="fixed-adv-bottom">
        <?php
            if(!empty($a_banners_pos8)) {
                foreach($a_banners_pos8 as $val)
                {
                $item = $val['Banner'];
                $attr = array('alt'=>$item['name'],'layout'=>'fixed');
                if($oneweb_banner['size']['8'][0]!='n') $attr = array_merge($attr,array('width'=>$oneweb_banner['size']['8'][0]));
                if($oneweb_banner['size']['8'][1]!='n') $attr = array_merge($attr,array('height'=>$oneweb_banner['size']['8'][1]));
                $str_banner = $this->HtmlAmp->amp_image('images/banners/'.$item['image'],$attr);

                $link_attr = array('title'=>$item['name'],'target'=>$item['target'],'class'=>'','escape'=>false);
                if($item['rel']!='dofollow') $link_attr['rel'] = $item['rel'];

                if(!empty($item['link'])) $str_banner = $this->Html->link($str_banner,$item['link'],$link_attr);
        ?>
            <?php echo $str_banner; ?>
        <?php
                }
            }
        ?>
        <div class="x-close">
            <?php echo $this->HtmlAmp->amp_image('X.png', array('width'=>'1','height'=>'2','layout'=>'fixed')); ?>
        </div>
    </div>

    <p id="back-top" style="display: block;"><a href="#" title="Lên đầu"><span>&nbsp;</span></a></p>

    <?php
        if(@$a_site_info['enable']==false) echo $this->element('lock_web');
        echo $this->Session->flash();
        echo $this->element('sql_dump');
    ?>
</body>
</html>
