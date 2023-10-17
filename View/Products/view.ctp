<!-- start products/view.ctp -->
<?php
echo $this->Html->css(array($oneweb_web['layout'] . '/smoothproducts/smoothproducts'));
echo $this->Html->script(array('smoothproducts/smoothproducts'));

$item_product = $a_product_c['Product'];
//var_dump($item_product);
$item_category = $a_product_c['ProductCategory'];
$item_maker = $a_product_c['ProductMaker'];
$item_images = array('0' => array('image' => $item_product['image'], 'name' => $item_product['meta_title']));
$item_images = array_merge($item_images, $a_product_c['ProductImage']);

// Kích thước ảnh thumbnail
$full_size = $oneweb_product['size']['product'];
$w = 600;
$h = intval($w * $full_size[1] / $full_size[0]);
?>

<!-- tab -->
<script type="text/javascript">
    $(document).ready(function() {
        //Default Action
        $(".tab_content").hide(); //Hide all content
        $("ul.nav-tabs li:first").addClass("active").show(); //Activate first tab
        $(".tab_content:first").show(); //Show first tab content

        //On Click Event
        $("ul.nav-tabs li").click(function() {
            $("ul.nav-tabs li").removeClass("active"); //Remove any "active" class
            $(this).addClass("active"); //Add "active" class to selected tab
            $(".tab_content").hide(); //Hide all tab content
            var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
            $(activeTab).fadeIn(); //Fade in the active content
            return false;
        });
        $('html, body').animate({
            scrollTop: $("#product_mobile").offset().top - 50
        }, 100);
        return false;
    });
</script>

<article class="box_content col-xs-12">
    <div class="des product-view">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 my-3">
                <header id="product_mobile" class="name hidden-sm hidden-md hidden-lg">
                    <!--					<h1>--><?php //echo $item_product['name']
                                                    ?>
                    <!--</h1>-->
                    <!--					<h2>--><?php //echo $item_product['name_en']
                                                    ?>
                    <!--</h2>-->
                </header>
                <div class="sp-wrap hidden-xs">
                    <?php
                    if (!empty($item_images)) {
                        for ($i = 0; $i < count($item_images); $i++) {
                            echo $this->Html->link($this->OnewebVn->thumb('products/' . $item_images[$i]['image'], array('alt' => $item_images[$i]['name'], 'width' => $w, 'height' => $h, 'class' => 'img-responsive')), '/img/images/products/' . $item_images[$i]['image'], array('escape' => false));
                    ?>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                <div class="info-section product-info-right">
                    <?php
                    $show_promotion = false;
                    if ($show_promotion && !empty($item_product['promotion'])) {
                        echo $this->Html->tag('span class="icon_oneweb icon_promotion"', '');
                    }
                    ?>
                    <!-- Tên sản phẩm -->
                    <header class="name">
                        <h1><?php echo $title_for_layout ?></h1>
                        <!--					<h2>--><?php //echo $item_product['name_en']
                                                        ?>
                        <!--</h2>-->
                    </header>

                    <!-- Hãng sản xuất, model, bình luận sp -->
                    <ul class="detail-infos">
                        <?php
                        if (!empty($item_maker['name'])) {
                        ?>
                            <li>
                                <?php
                                if (empty($item_maker['link'])) $url_maker = array('controller' => 'products', 'action' => 'maker', 'lang' => $lang, 'slug' => $item_maker['slug']);
                                else $url_maker = $item_maker['link'];
                                $link_maker_attr = array('title' => $item_maker['meta_title'], 'target' => $item_maker['target'], 'class' => '');

                                echo __('Hãng sản xuất', true) . ': ' . $this->Html->link($item_maker['name'], $url_maker, $link_maker_attr); ?>
                            </li>
                        <?php
                        }
                        ?>

                        <?php
                        if (!empty($item_product['code'])) {
                        ?>
                            <li>
                                <?php echo __('Mã', true) . ': ' . $item_product['code']; ?>
                            </li>
                        <?php } ?>
                        <?php
                        if (!empty($item_product['made'])) {
                        ?>
                            <li>
                                <?php echo __('Xuất xứ', true) . ': ' . $item_product['made']; ?>
                            </li>
                        <?php } ?>
                        <!--					<li>-->
                        <!--						<a onclick="gotoComment()">-->
                        <?php //echo __('Bình luận sản phẩm'); 
                        ?>
                        <!--</a>-->
                        <!--					</li>-->
                    </ul>

                    <?php
                    if (!empty($oneweb_web['social'])) {
                        echo $this->element('frontend/c_share_social');
                    }
                    ?>

                    <!-- Giá sản phẩm -->
                    <?php
                    if (isset($item_product['price_new']) && $item_product['price_new'] > 0) {
                    ?>
                        <div class="price" style="margin-top: 10px;">
                            <p class="old-price">
                                <?php echo __('Giá sản phẩm') . ': '; ?>
                                <?php
                                echo $this->Html->tag('del', number_format($item_product['price'] / $a_currency_c['value'], $a_currency_c['decimal'], $a_currency_c['sep1'], $a_currency_c['sep2']) . ' ' . $a_currency_c['name'], array('class' => '')) . ' ';
                                ?>
                            </p>
                            <p class="new-price">
                                <?php echo __('Giá khuyến mãi') . ': '; ?>
                                <?php
                                echo $this->Html->tag('span', number_format($item_product['price_new'] / $a_currency_c['value'], $a_currency_c['decimal'], $a_currency_c['sep1'], $a_currency_c['sep2']) . ' ' . $a_currency_c['name'], array('class' => ''));
                                ?>
                            </p>
                            <?php if (!empty($item_product['unit2'])) { ?>
                                <p class="new-price">
                                    <?php echo __('Đơn vị') . ': ' . __($item_product['unit2']); ?>
                                </p>
                            <?php } ?>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="price" style="margin-top: 10px;">
                            <p class="new-price">
                                <?php echo __('Giá sản phẩm') . ': '; ?>
                                <?php
                                echo $this->Html->tag('span', number_format($item_product['price'] / $a_currency_c['value'], $a_currency_c['decimal'], $a_currency_c['sep1'], $a_currency_c['sep2']) . ' ' . $a_currency_c['name'], array('class' => '')) . ' ';
                                ?>
                            </p>
                        </div>
                    <?php } ?>
                    <?php if (!empty($item_product['description'][0])) { ?>
                        <div class="commit">
                            <?php echo $item_product['description'][0] ?>
                        </div>
                    <?php } ?>
                    <!-- Khuyến mãi, bảo hành -->
                    <?php ?>
                    <div class="promotion-warranty">
                        <?php
                        if (!empty($oneweb_product['promotion']) && !empty($item_product['promotion'])) {
                        ?>
                            <p class="promotion">
                                <i class="icon_oneweb icon_tick"></i>
                                <?php echo __('Khuyến mãi') . ': '; ?>
                                <?php echo $this->OnewebVn->rawText($item_product['promotion']); ?>
                            </p>
                        <?php
                        }
                        ?>
                        <?php
                        if (!empty($item_product['description']['specification'])) {
                            $color = explode(',', $item_product['description']['specification'])
                        ?>
                            <p>
                                <i class="icon_oneweb icon_tick"></i>
                                <!--                            --><?php //var_dump($color);
                                                                    ?>
                                <?php echo __('Màu sắc') . ': ';
                                foreach ($color as $item) {
                                ?>
                                    <input type="radio" name="color" value="<?php echo $item ?>">
                                    <?php echo $this->OnewebVn->rawText($item); ?>
                                <?php } ?>
                            </p>
                        <?php
                        }
                        ?>
                        <?php
                        if (!empty($item_product['description']['unit'])) {
                            $unit = explode(',', $item_product['description']['unit'])
                        ?>
                            <p>
                                <i class="icon_oneweb icon_tick"></i>
                                <!--                            --><?php //var_dump($color);
                                                                    ?>
                                <?php echo __('Màu sắc') . ': ';
                                foreach ($unit as $item) {
                                ?>
                                    <input type="radio" name="unit" value="<?php echo $item ?>">
                                    <?php echo $this->OnewebVn->rawText($item); ?>
                                <?php } ?>
                            </p>
                        <?php
                        }
                        ?>
                        <?php
                        if (!empty($oneweb_product['warranty']) && !empty($item_product['warranty'])) {
                        ?>
                            <p>
                                <i class="icon_oneweb icon_tick"></i>
                                <?php echo __('Bảo hành') . ': '; ?>
                                <?php echo $this->OnewebVn->rawText($item_product['warranty']); ?>
                            </p>
                        <?php
                        }
                        ?>
                    </div>
                    <!-- Cam kết -->
                    <div class="commit hidden">
                        <p>
                            <i class="fa fa-pencil-square-o"></i>
                            <?php echo __('100% sản phẩm chính hãng.'); ?>
                        </p>
                        <p>
                            <i class="fa fa-recycle"></i>
                            <?php echo __('1 đổi 1 trong vòng 7 ngày'); ?>
                        </p>
                        <p>
                            <i class="fa fa-truck"></i>
                            <?php echo __('Miễn phí vận chuyển với đơn hàng từ 600.000 VNĐ'); ?>
                        </p>
                    </div>
                    <?php if (!empty($product_attributes)) { ?>
                        <?php echo $this->element('AdvancedProductAttributes.frontend/product_option') ?>
                        <?php echo $this->element('AdvancedProductAttributes.frontend/product_add_to_cart') ?>
                    <?php } ?>
                    <!-- Mua ngay, thêm vào giỏ, gọi hỗ trợ -->
                    <div class="group-button">
                        <a data-id="<?php echo $item_product['id'] ?>" href="javascript:;" id="quick_buy_now" onclick="addCart(<?php echo $item_product['id'] ?>,1,true)">
                            <div class="btn-1">
                                <!-- <i class="icon_oneweb icon_cart"></i> -->
                                <?php echo __('Mua hàng nhanh'); ?>
                            </div>
                        </a>
                        <a href="javascript:;" id="add-to-cart" onclick="addToCart(<?php echo $item_product['id']; ?>, 1, true, '', '')">
                            <div class="btn-2">
                                <!-- <i class="icon_oneweb icon_cart"></i> -->
                                <?php echo __('Thêm vào giỏ hàng'); ?>
                            </div>
                        </a>
                        <?php
                        foreach ($a_support_s as $val) {
                            $item_support = $val['Support'];
                            if (strtolower($item_support['name']) === 'hotline') $hotline = $item_support;
                        }
                        ?>
                        <?php
                        if (!empty($hotline)) {
                        ?>
                            <a href="tel:<?php echo $this->OnewebVn->rawPhone($hotline['phone']); ?>" class="hidden">
                                <div class="btn-3">
                                    <i class="icon_oneweb icon-phone"></i>
                                    <p class="title-hotline">
                                        <span class="title">
                                            <?php echo __('Hotline (miễn cước)'); ?>
                                        </span>
                                        <?php echo $hotline['phone']; ?>
                                    </p>
                                </div>
                            </a>
                        <?php
                        }
                        ?>

                    </div>
                    <?php if (!empty($item_product['link_shopee']) || !empty($item_product['link_lazada']) || !empty($item_product['link_shopee_hcm']) || !empty($item_product['link_lazada_hcm'])) { ?>
                        <div class="group-button">
                            <?php if (!empty($item_product['link_shopee']) || !empty($item_product['link_shopee_hcm'])) { ?>
                                <a <?php if (empty($item_product['link_shopee']) && !empty($item_product['link_shopee_hcm'])) {
                                        echo "href=" . $item_product['link_shopee_hcm'] . '" target="_blank" rel="nofollow"';
                                    } elseif (!empty($item_product['link_shopee']) && empty($item_product['link_shopee_hcm'])) {
                                        echo "href=" . $item_product['link_shopee'] . '" target="_blank" rel="nofollow"';
                                    } else {
                                        echo 'data-toggle="modal" data-target="#autoAdsMaxLead_widget_click_popup_shopee"';
                                    } ?>>
                                    <div class="btn-1 btn-shopee">
                                        <!-- <i class="icon_oneweb icon_cart"></i> -->
                                        <?php echo __('Mua trên shopee'); ?>
                                        <br>
                                        <span> (FreeShip Extra)</span>
                                    </div>
                                </a>
                            <?php } ?>
                            <?php if (!empty($item_product['link_lazada']) || !empty($item_product['link_lazada_hcm'])) { ?>
                                <a <?php if (empty($item_product['link_lazada']) && !empty($item_product['link_lazada_hcm'])) {
                                        echo "href=" . $item_product['link_lazada_hcm'] . '" target="_blank" rel="nofollow"';
                                    } elseif (!empty($item_product['link_lazada']) && empty($item_product['link_lazada_hcm'])) {
                                        echo "href=" . $item_product['link_lazada'] . '" target="_blank" rel="nofollow"';
                                    } else {
                                        echo 'data-toggle="modal" data-target="#autoAdsMaxLead_widget_click_popup_lazada"';
                                    } ?>>
                                    <div class="btn-1 btn-lazada">
                                        <!-- <i class="icon_oneweb icon_cart"></i> -->
                                        <?php echo __('Mua trên Lazada'); ?>
                                        <br>
                                        <span> (FreeShip Extra)</span>
                                    </div>
                                </a>
                            <?php } ?>
                        </div>
                        <div id="autoAdsMaxLead_widget_click_popup_shopee" class="modal fade aml-modal aml_dk-desktop" data-channel="click_to_call" style="z-index: 2147483647; display: none;">
                            <div class="aml-modal-content">
                                <div class="aml-modal-body">
                                    <div id="Header-popup" class="d-flex align-items-center">
                                        <div style="color: blue;" id="Title-popup" class="font-weight-bold">Hãy chọn kho hàng gần với bạn</div>
                                        <div id="BtnCloseContainer" class="ml-auto d-flex justify-content-center align-items-center" ">
                            <div id=" BtnClose" data-dismiss="modal"></div>
                                    </div>
                                </div>
                                <div class="aml-list-phone">
                                    <a href="<?php echo $item_product['link_shopee_hcm'] ?>" target="_blank" rel="nofollow">
                                        <div style="text-align: center;" id="hotline" class="aml-phone-info">
                                            <span class="aml-region aml-line-clamp-1"></span><span class="aml-region"></span><span class="aml-phone-number">TP. Hồ Chí Minh</span>
                                        </div>
                                    </a>
                                    <a href="<?php echo $item_product['link_shopee'] ?>" target="_blank" rel="nofollow">
                                        <div style="text-align: center;" id="hotline" class="aml-phone-info">
                                            <span class="aml-region aml-line-clamp-1"></span><span class="aml-region"></span><span class="aml-phone-number">Hà Nội</span>
                                        </div>
                                    </a>
                                </div>

                            </div>
                        </div>
                        <div class="aml-modal-footer"></div>
                </div>
                <div id="autoAdsMaxLead_widget_click_popup_lazada" class="modal fade aml-modal aml_dk-desktop" data-channel="click_to_call" style="z-index: 2147483647; display: none;">
                    <div class="aml-modal-content">
                        <div class="aml-modal-body">
                            <div id="Header-popup" class="d-flex align-items-center">
                                <div id="Title-popup" class="font-weight-bold">Hãy chọn vị trí gần với bạn</div>
                                <div id="BtnCloseContainer" class="ml-auto d-flex justify-content-center align-items-center" ">
                            <div id=" BtnClose" data-dismiss="modal"></div>
                            </div>
                        </div>
                        <div class="aml-list-phone">
                            <a href="<?php echo $item_product['link_lazada'] ?>" target="_blank" rel="nofollow">
                                <div style="text-align: center;" id="hotline" class="aml-phone-info">
                                    <span class="aml-region aml-line-clamp-1"></span><span class="aml-region"></span><span class="aml-phone-number">TP. Hồ Chí Minh</span>
                                </div>
                            </a>
                            <a href="<?php echo $item_product['link_lazada_hcm'] ?>" target="_blank" rel="nofollow">
                                <div style="text-align: center;" id="hotline" class="aml-phone-info">
                                    <span class="aml-region aml-line-clamp-1"></span><span class="aml-region"></span><span class="aml-phone-number">Hà Nội</span>
                                </div>
                            </a>
                        </div>


                    </div>
                </div>
                <div class="aml-modal-footer"></div>
            </div>
        <?php } ?>
        <?php if (!empty($oneweb_product['rate'])) { ?>
            <div class="rate">
                <span><?php echo __('Đánh giá', true) ?>: </span>
                <?php echo $this->element('frontend/c_rate', array(
                    'item_id' => $item_product['id'],
                    'model' => 'Product',
                    'star_rate' => $item_product['star_rate'],
                    'star_rate_count' => $item_product['star_rate_count']
                ));
                ?>
            </div>
        <?php } ?>

        <?php
        $show_quantity = false;
        if ($show_quantity && !empty($oneweb_product['quantity'])) {
        ?>
            <?php echo ($item_product['quantity'] > 0) ? __('Còn hàng', true) : __('Hết hàng', true) ?>
        <?php
        }
        ?>

        <?php
        if (!empty($oneweb_product['tag']) && !empty($item_product['tag'])) {
        ?>
            <div class="tag hidden-xs">
                <strong><?php echo __('Từ khoá'); ?>:</strong>
                <span>
                    <?php
                    foreach ($item_product['tag'] as $val)
                        echo $this->Html->link($val['name'], array('controller' => 'tags', 'action' => 'index', 'lang' => $lang, 'id' => $val['id'], 'slug' => $val['slug']), array('title' => $val['meta_title'], 'rel' => 'tag', 'class' => '')) . ', ';
                    ?>
                </span>
            </div>
        <?php
        }
        ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php if (!empty($a_product_configs_c['description'])) { ?>
            <div class="info_general">
                <?php echo $a_product_configs_c['description'] ?>
            </div>
        <?php } ?>

        <?php if ((!empty($a_product_configs_c['tab']) && ((!empty($item_product['description'][0]) && count($item_product['description']) > 1) || (empty($item_product['description'][0]) && !empty($item_product['description'])))) || (!empty($oneweb_product['related']) && $item_product['related'])) {
            $a_tabs = explode(',', $a_product_configs_c['tab']);
        ?>
            <div class="product_des">
                <ul class="nav nav-tabs">
                    <?php foreach ($a_tabs as $key => $val) {
                        if (!empty($item_product['description'][$key + 1])) {
                    ?>
                            <li><a href="#tab<?php echo $key ?>"><?php echo $val ?></a></li>
                    <?php }
                    } ?>
                    <?php if ((!empty($oneweb_product['related']) && $item_product['related'])) { ?>
                        <li><a href="#option_related"><?php echo __('Phụ kiện liên quan', true) ?></a></li>
                    <?php } ?>
                </ul>

                <div class="clear"></div>
                <div class="tab_container m-t-15">
                    <?php foreach ($a_tabs as $key => $val) {
                        if (!empty($item_product['description'][$key + 1])) {
                    ?>
                            <div id="tab<?php echo $key ?>" class="tab_content">
                                <?php
                                if (!empty($item_product['description'][$key + 1])) echo preg_replace('/<iframe\s+.*?\s+height=(".*?")\s+.*?\s+src=(".*?")\s+.*?\s+width=(".*?").*?<\/iframe>/', '<div class = "video-youtube" data-src = $2 data-width = $3 data-height = $1 > </div>', str_replace('<table ', '<div class = "table-responsive"> <table ', str_replace('</table>', '</table> </div>', $item_product['description'][$key + 1])));
                                else echo __('Thông tin đang được cập nhật...', true);
                                ?>
                            </div> <!-- end #tab1 -->
                    <?php }
                    } ?>

                    <?php if (!empty($a_product_configs_c['address'])) { ?>
                        <div class="info_general">
                            <?php echo $a_product_configs_c['address'] ?>
                        </div>
                    <?php } ?>

                    <div id="option_related" class="tab_content">
                        <?php
                        echo $this->element('frontend/c_product', array(
                            'data' => $a_option_related_c,
                            'position' => '',
                            'limit' => '',
                            'cart' => false,
                            'class' => 'option_related',
                            'w' => 218,
                            'zc' => 2
                        ))
                        ?>
                    </div>

                </div>
                <div class="clear"></div>
                <div class="p_readmore text-center p-y-15" style="display: none">
                    <button class="btn btn-default btn_viewmore" id="b_readmore">Đọc thêm <span class="fa fa-caret-down"></span></button>
                </div>
                <hr class="m-b-30">
            </div>
        <?php } ?>

        <!-- Form đặt hàng ngay -->
        <div id="mua-ngay">
            <div class="row order">
                <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                    <div class="banner">
                        <?php
                        if (!empty($a_banner_fastorder) && is_array($a_banner_fastorder)) {
                            $item_banner = $a_banner_fastorder[rand(0, count($a_banner_fastorder) - 1)]['Banner'];

                            echo $this->element('frontend/banner', array(
                                'data' => $item_banner,
                                'size' => $oneweb_banner['size'][13],
                            ));
                        }
                        ?>
                    </div>
                    <div class="order_info" id="order_info">
                        <table id="order_info_content">
                            <tr>
                                <th colspan="2">
                                    <h3 class="title"><?php echo __('Đặt hàng nhanh Giao hàng ngay'); ?></h3>
                                </th>
                            </tr>
                            <tr class="product">
                                <td class="thumb">
                                    <?php
                                    $w = 400;
                                    $full_size = $oneweb_product['size']['product'];
                                    $h = intval($w * $full_size[1] / $full_size[0]);

                                    echo $this->OnewebVn->thumb('products/' . $item_product['image'], array('alt' => $item_product['meta_title'], 'width' => $w, 'height' => $h, 'zc' => 1, 'class' => 'img-responsive'), array('escape' => false));
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $this->Html->tag('p', $item_product['name'], array('class' => 'name'));

                                    //									$price = $item_product['price'];
                                    //									$discount = 0;
                                    //									if ( ! empty($item_product['discount'])) {
                                    //										if ($item_product['discount_unit']) {
                                    //											$price = $price-($price*$item_product['discount']/100);				//Giảm giá theo %
                                    //											$discount = $item_product['discount'];
                                    //										} else {
                                    //											$price = $price - $item_product['discount'];												//Giảm số tiền nhập vao
                                    //											$discount = $item_product['discount']*100/$price;
                                    //										}
                                    //									}
                                    //									if ($a_currency_c['location'] == 'first') echo $this->Html->tag('span',$a_currency_c['name'], array('class' => 'new'));
                                    //
                                    //									echo $this->Html->tag('span',number_format($price/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']), array('class' => 'new'));
                                    //
                                    //									if ($a_currency_c['location'] == 'last') echo $this->Html->tag('span', ' '.$a_currency_c['name'], array('class' => 'new'));
                                    //
                                    //									if ( ! empty($item_product['discount'])) {
                                    //										echo $this->Html->tag('p',number_format($item_product['price']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']), array('class' => 'old')).' ';
                                    //										echo $this->Html->tag('p', __('Giảm giá', true).': '.round($discount).'%', array('class' => 'discount'));
                                    //									}
                                    ?>
                                    <!-- Giá sản phẩm -->
                                    <?php
                                    if (isset($item_product['price_new']) && $item_product['price_new'] > 0) {
                                    ?>
                                        <div class="price" style="margin-top: 10px;">
                                            <p class="old-price">
                                                <?php echo __('Giá sản phẩm') . ': '; ?>
                                                <?php
                                                echo $this->Html->tag('del', number_format($item_product['price'] / $a_currency_c['value'], $a_currency_c['decimal'], $a_currency_c['sep1'], $a_currency_c['sep2']) . ' ' . $a_currency_c['name'], array('class' => '')) . ' ';
                                                ?>
                                            </p>
                                            <p class="new-price">
                                                <?php echo __('Giá khuyến mãi') . ': '; ?>
                                                <?php
                                                echo $this->Html->tag('span', number_format($item_product['price_new'] / $a_currency_c['value'], $a_currency_c['decimal'], $a_currency_c['sep1'], $a_currency_c['sep2']) . ' ' . $a_currency_c['name'], array('class' => ''));
                                                ?>
                                            </p>
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="price" style="margin-top: 10px;">
                                            <p class="new-price">
                                                <?php echo __('Giá sản phẩm') . ': '; ?>
                                                <?php
                                                echo $this->Html->tag('span', number_format($item_product['price'] / $a_currency_c['value'], $a_currency_c['decimal'], $a_currency_c['sep1'], $a_currency_c['sep2']) . ' ' . $a_currency_c['name'], array('class' => '')) . ' ';
                                                ?>
                                            </p>
                                        </div>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="form">
                        <?php echo $this->Form->create('Order', array('url' => array('controller' => 'orders', 'action' => 'info', 'lang' => $lang), 'inputDefaults' => array('div' => false, 'label' => false), 'onsubmit' => "return fastOrder()")) ?>

                        <div class="form-group">
                            <?php
                            echo $this->Form->label('name', __('Họ tên', true) . '<span class="im">*</span>');
                            echo $this->Form->input('name', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo $this->Form->label('phone', __('Điện thoại', true) . '<span class="im">*</span>');
                            echo $this->Form->input('phone', array('class' => 'form-control'))
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo $this->Form->label('address', __('Địa chỉ', true) . '<span class="im">*</span>');
                            echo $this->Form->input('address', array('class' => 'form-control', 'required' => true))
                            ?>
                        </div>
                        <div class="form-group hidden">
                            <?php
                            echo $this->Form->input('rate', array('value' => $a_currency_c['value'] / $a_currency_default_c['value']));
                            echo $this->Form->input('unit_payment', array('value' => $a_currency_c['name']));
                            ?>
                        </div>
                        <div class="form-group text-center">
                            <?php if ($item_product['quantity'] > 0) echo $this->Form->submit(__('Hoàn tất'), array('div' => false, 'class' => 'btn btn-default')) ?>
                            <?php
                            if (!empty($a_support_hotline)) {
                            ?>
                                <a onclick="gtag_report_conversion()" href="tel:<?php echo $this->OnewebVn->rawPhone($a_support_hotline['phone']); ?>" class="btn btn-default call-hotline-btn">
                                    <?php echo __('Gọi') . ': ' . $a_support_hotline['phone']; ?>
                                </a>
                            <?php
                            }
                            ?>
                        </div>

                        <?php echo $this->Form->end(); ?>
                    </div> <!-- end .form -->
                </div>
            </div>
        </div>

        <?php if (!empty($oneweb_product['comment'])) echo $this->element('frontend/c_comment', array(
            'item_id' => $item_product['id'],
            'item_name' => $item_product['name'],
            'model' => 'Product'
        ))
        ?>

        <!-- Bình luận facebook -->
        <?php if (!empty($oneweb_product['comment_face'])) echo $this->element('frontend/c_comment_face', array(
            'url' => $this->request->url,
            'width' => '100%'
        ));
        ?>
        <?php if (!empty($a_product_viewed)) { ?>
            <section class="row product_viewed">
                <div class="col-xs-12">
                    <div class="title">
                        <span class="p-l-15 font-weight-bold"><?php echo __('Sản phẩm đã xem', true) ?></span>
                    </div>
                    <div class="line_title"></div>
                    <div class="auto-clear">
                        <?php
                        echo $this->element('frontend/c_product_run', array(
                            'data' => $a_product_viewed,
                            'position' => '',
                            'class' => '',
                            'run' => true,            //Bật/Tắt chức năng chạy
                            'play' => 'true',
                            'loop' => 'false',
                            'w' => 400,
                            'zc' => 2,
                            'items' => 5,
                        ));
                        ?>
                    </div>
                </div>
            </section>
        <?php } ?>
        <!-- Sản phẩm liên quan -->
        <?php
        if (!empty($a_related_products_c)) {
        ?>
            <div class="product_related " id="product_related">
            </div>
            <?php
        }
            ?>
            

            </div>
</article>
<script type="text/javascript">
    
    //Đưa sản phẩm vào giỏ hàng
    function addToCart(id, qty, add, color, size) {
        var color = $('input[name="color"]:checked').val();
        var size = $('input[name="unit"]:checked').val();
        $.ajax({
            type: 'post',
            url: '<?php echo $this->Html->url(array('controller'=>'orders','action'=>'ajaxAddToCart','lang'=>$lang))?>',
            data: 'id=' + id + '&qty=' + qty + '&add=' + add + '&lang=<?php echo $lang?>' + '&color=' + color +
                '&size=' + size,
            beforeSend: function() {
                $("#message_top").show();
            },
            success: function(result) {
                $(".number_product_cart").text(result);
                $(".number_product_cart").addClass('bg_cart');
                showCart();
                $("#message_top").fadeOut('400', function() {
                    setTimeout(function() {
                        $("#message_cart").show();
                    }, 300);

                    setTimeout(function() {
                        $("#message_cart").hide();
                    }, 3000);
                    gtag('event', 'conversion', {
                        'send_to': 'AW-663378581/QvgjCPrZxPsCEJW1qbwC',
                    });
                });
            }
        });
    }

    function addCart(id, qty, add) {
        var color = $('input[name="color"]:checked').val();
        var size = $('input[name="unit"]:checked').val();
        $.ajax({
            type: 'post',
            url: '<?php echo $this->Html->url(array('controller'=>'orders','action'=>'ajaxAddToCart','lang'=>$lang))?>',
            data: 'id=' + id + '&qty=' + qty + '&add=' + add + '&lang=<?php echo $lang?>'+ '&color=' + color + '&size=' + size,
            beforeSend: function() {
                $("#message_top").show();
            },
            success: function(result) {
                $(".number_product_cart").text(result);
                $(".number_product_cart").addClass('bg_cart');
                $("#message_top").hide();
                window.location =
                    '<?php echo $this->Html->url(array('controller'=>'orders','action'=>'info','lang'=>$lang,'ext'=>'html'));?>';
                gtag('event', 'conversion', {
                    'send_to': 'AW-663378581/QvgjCPrZxPsCEJW1qbwC',
                });
            }
        });
    }

    function addCartFooter(id, qty, add) {
        var color = $('input[name="color"]:checked').val();
        var size = $('input[name="unit"]:checked').val();
        var numid = $('#quick_buy_now').attr('data-id');
        console.log(numid)
        $.ajax({
            type: 'post',
            url: '<?php echo $this->Html->url(array('controller'=>'orders','action'=>'ajaxAddToCart','lang'=>$lang))?>',
            data: 'id=' + numid + '&qty=' + qty + '&add=' + add + '&lang=<?php echo $lang?>'+ '&color=' + color + '&size=' + size,
            beforeSend: function() {
                $("#message_top").show();
            },
            success: function(result) {
                $(".number_product_cart").text(result);
                $(".number_product_cart").addClass('bg_cart');
                $("#message_top").hide();
                $('.link_cart').click();
                gtag('event', 'conversion', {
                    'send_to': 'AW-663378581/QvgjCPrZxPsCEJW1qbwC',
                });
            }
        });
    }
    function addFastOrder(id, qty, add) {
        $.ajax({
            type: 'post',
            url: '<?php echo $this->Html->url(array('controller'=>'orders','action'=>'ajaxFastOrder','lang'=>$lang))?>',
            data: 'id=' + id + '&qty=' + qty + '&add=' + add + '&lang=<?php echo $lang?>',
        });
    }

    function gotoComment() {
        $('html,body').animate({
            scrollTop: $("#comment").offset().top
        });
    }

    function add_to_cart() {
        <?php if (!empty($product_attributes)) { ?>
            var config = <?php echo json_encode($product_attributes) ?>;
            var color = $(".is-media.selected").attr('data-label');
            var size = $("#select_label_size").html();
            var qty = $("#qty").val();
            if (qty == 'undefined') qty = 1;
            var err = '';
            if ($("#configurable_swatch_size li.selected").hasClass('not-available')) {
                err = 'Sản phẩm không có kích cỡ này. Quý khách vui lòng liên hệ với chúng tôi.';
            }
            var cksel = 0;
            $("#configurable_swatch_size li").each(function(i, v) {
                if ($(v).hasClass('selected')) {
                    cksel++;
                }
            });
            if (cksel == 0) err = 'Quý khách vui lòng chọn kích cỡ sản phẩm';
            $.each(config, function(key, value) {

                if (value.ProductSize.size.toLowerCase() === size.toLowerCase() && value.ProductColor.slug.toLowerCase() === color.toLowerCase()) {
                    if (parseInt(qty) > value.ProductAttribute.qty) {
                        err = 'Số lượng không đủ đáp ứng.';
                        return false;
                    }
                    if (value.ProductAttribute.qty == 0) {
                        err = 'Sản phẩm này đã hết. Quý khách vui lòng liên hệ với chúng tôi.';
                        return false;
                    }
                }
            });
            if (err != '') {
                alert(err);
            } else {
                addToCart(<?php echo $item_product['id']; ?>, qty, false, color, size.toUpperCase());
                // $("#order_info").load(location.href + " #order_info_content");
            }
        <?php } ?>
    }

    function addToCartF() {
        addToCart(<?php echo $item_product['id']; ?>, 1, false, '', '');
    }

    function goToOrder() {
        $("#order_info").html('<p style="text-align: center; margin: 10px 0;">Đang tải...</p>');

        addToCart(<?php echo $item_product['id']; ?>, 1, false, '', '');
        $("#order_info").load(location.href + " #order_info_content");
        $("#mua-ngay").show();
        $('html,body').animate({
            scrollTop: $("#mua-ngay").offset().top - 80
        });
        $('#OrderName').focus();
        gtag('event', 'conversion', {
            'send_to': 'AW-663378581/QvgjCPrZxPsCEJW1qbwC',
        });
    }

    function fastOrder() {
        goToOrder();
        // addFastOrder(<?php //echo $item_product['id']; 
                        ?>, 1, false, false);
    }

    function viewMore() {
        var height = $('.product_des .tab_container').height();
        if (height > 1200) {
            $('.p_readmore').show();
            $('.product_des .tab_container').addClass('height_limit');
        }
    }

    function ajaxScrollLoadProductRelated(ele) {
        $.ajax({
            type:'post',
            data:{'product_id':<?php echo $item_product['id'] ?>},
            url:'<?php  echo $this->Html->url(array('controller'=>'products','action'=>'ajaxScrollLoadProductRelated','lang'=>$lang));?>',
            cache:false,
            beforeSend:function(){
                $("."+ele).html('<center id="ajaxloading" class="m-b-15"><?php echo $this->Html->image('loading.gif',array('alt'=>'loadding'))?></center>');
            },
            success:function(result){
                $("."+ele).html(result);
                $("#"+ele).removeClass(ele);
            }
        });
    }

    $('#b_readmore').click(function() {
        if ($('.product_des .tab_container').hasClass('height_limit')) {
            $('.product_des .tab_container').removeClass('height_limit');
            $('#b_readmore').html('Thu gọn <span class="fa fa-caret-up">');
        } else {
            $('.product_des .tab_container').addClass('height_limit');
            $('#b_readmore').html('Đọc thêm <span class="fa fa-caret-down">');
        }
    });
    $(document).ready(function() {
        $('.sp-wrap').smoothproducts();
    });
    $(function(){
        ajaxScrollLoadProductRelated('product_related');
    })
</script>
<!-- end products/view.ctp -->
<script>
    function gtag_report_conversion(url) {
        var callback = function() {
            if (typeof(url) != 'undefined') {
                window.location = url;
            }
        };
        gtag('event', 'conversion', {
            'send_to': 'AW-663378581/QvgjCPrZxPsCEJW1qbwC',
            'event_callback': callback
        });
        return false;
    }
    setTimeout(function() {
        var url = $('.video-youtube').attr('data-src')
        
        var data = $('.video-youtube').map(function() {
        return $(this).data('src');
        }).get();
        var width = $('.video-youtube').map(function() {
        return $(this).data('width');
        }).get();
        var height = $('.video-youtube').map(function() {
        return $(this).data('height');
        }).get();
        var myArray = $.map(data, function(v,i) {        // ***
            return {
                link: v,
                width: width[i],
                height: height[i]
            };                             // ***
        });
        console.log(myArray);
        
        if($(window).width()  > 991){
            $.each( myArray, function( key1, value ) {
                $('.video-youtube[data-src="'+value.link+'"]').append('<iframe frameborder="0" height="'+value.height+'" longdesc="Xe lăn điện Siêu nhẹ Gentle 120P" name="Xe lăn điện Siêu nhẹ Gentle 120P" scrolling="no" src="'+value.link+'" title="Xe lăn điện Siêu nhẹ Gentle 120P" width="'+value.width+'"></iframe>');
        });
        }else{
            $.each( myArray, function( key1, value ) {
              
                $('.video-youtube[data-src="'+value.link+'"]').append('<iframe frameborder="0" height="'+($(window).width()*0.8)*(value.height/value.width)+'" longdesc="Xe lăn điện Siêu nhẹ Gentle 120P" name="Xe lăn điện Siêu nhẹ Gentle 120P" scrolling="no" src="'+value.link+'" title="Xe lăn điện Siêu nhẹ Gentle 120P" width="'+($(window).width()*0.8)+'"></iframe>');
        });
        }
  }, 5000);
</script>