<?php if(!empty($product_attributes)) { ?>
<fieldset class="product-options" id="product-options-wrapper">
    <dl class="last">
        <dt class="swatch-attr">
            <label id="color_label" class="required">
                <em>*</em>Màu sắc:
                <span id="select_label_color" class="select-label"></span>
            </label>
        </dt>
        <dd class="clearfix swatch-attr">
            <div class="input-box">
                <?php echo $this->Form->input('product_color_id', array('type'=>'select','options'=>$list_product_color, 'label'=>false, 'class'=>'required-entry super-attribute-select no-display swatch-select validation-passed')) ?>
                <ul id="configurable_swatch_color" class="configurable-swatch-list clearfix list-unstyled">
                    <?php //foreach ($list_product_color as $key => $value) {
                        $arr_id_color = array();
                        foreach ($product_attributes as $product_attribute) {
                            $hex = $product_attribute['ProductColor']['hex'];
                            if(in_array($product_attribute['ProductColor']['id'], $arr_id_color)) continue;
                            else array_push($arr_id_color, $product_attribute['ProductColor']['id']);
                        // }
                        ?>
                        <li class="option-<?php echo $product_attribute['ProductColor']['slug'] ?> is-media" id="option<?php echo $product_attribute['ProductColor']['id'] ?>" data-label="<?php echo $product_attribute['ProductColor']['slug'] ?>">
                            <a href="javascript:void(0)" name="<?php echo $product_attribute['ProductColor']['color'] ?>" id="swatch<?php echo $product_attribute['ProductColor']['id'] ?>" class="swatch-link swatch-link-80 has-image" title="<?php echo $product_attribute['ProductColor']['color'] ?>" style="height: 23px; width: 23px;">
                                <span class="swatch-label" style="height: 21px; width: 21px; line-height: 21px; background-color: <?php echo $hex ?>">
                                </span>
                                <span class="x">X</span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </dd>
        <dt class="swatch-attr">
            <label id="size_label" class="required">
                <em>*</em>Kích cỡ:
                <span id="select_label_size" class="select-label"></span>
            </label>
        </dt>
        <?php  ?>
        <dd class="clearfix swatch-attr last">
            <div class="input-box">
                <?php echo $this->Form->input('product_size_id', array('type'=>'select','options'=>$list_product_size,'label'=>false, 'class'=>'required-entry super-attribute-select no-display swatch-select validation-failed')) ?>
                <ul id="configurable_swatch_size" class="configurable-swatch-list clearfix list-unstyled">
                    <?php foreach ($list_product_size as $key => $value) {
                        ?>
                    <li class="option-<?php echo strtolower($value);?>" id="option_<?php echo $key ?>" data-label="<?php echo strtolower($value)?>">
                        <a href="javascript:void(0)" name="<?php echo strtolower($value) ?>" id="swatch<?php echo $key ?>" class="swatch-link swatch-link-129" title="<?php echo $value ?>" style="height: 23px; min-width: 23px;">
                            <span class="swatch-label" style="height: 21px; min-width: 21px; line-height: 21px;">
                            <?php echo $value ?>                                 </span>
                            <span class="x">X</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </dd>
    </dl>
<script type="text/javascript">
    $(function(){
        $("#configurable_swatch_color li:first-child").addClass('selected');
        $("#select_label_color").html($("#configurable_swatch_color li:first-child a").attr('name'));
        selectColor($("#configurable_swatch_color li:first-child").attr('data-label'));
        // color
        $("#configurable_swatch_color li .swatch-link").hover(function(){
            var color_slug = $(this).parent().attr('data-label');
            var color_name = $(this).attr('name');
            $(".option-"+color_slug).addClass('hover');
            $("#select_label_color").html(color_name);
            selectColor(color_slug);
        }, function(){
            var sele_color = $("#configurable_swatch_color .selected .swatch-link").parent().attr('data-label');
            var sele_color_name = $("#configurable_swatch_color .selected .swatch-link").attr('name');
            $(".is-media").removeClass('hover');
            $("#select_label_color").html(sele_color_name);
            selectColor(sele_color);
        }).click(function(){
            $("#configurable_swatch_color li").removeClass('selected');
            var color_slug = $(this).parent().attr('data-label');
            $(".option-"+color_slug).addClass('selected');
            //select input
            color_id = $(".option-"+color_slug).attr('id');
            $("#product_color_id").val(color_id.substring(6,color_id.length));
        });
        //size
        $("#configurable_swatch_size li .swatch-link").hover (function(){
            var size_name = $(this).attr('name');
            $(".option-"+size_name).addClass('hover');
            $("#select_label_size").html(size_name);
            selectSize(size_name);
        }, function() {
            if($("#configurable_swatch_size li").hasClass("selected")) {
                var sele_size = $("#configurable_swatch_size .selected .swatch-link").attr('name');
                $("#select_label_size").html(sele_size);
                selectSize(sele_size);
            } else {
                $("#select_label_size").html('');
                selectSize(null);
            }
            $("#configurable_swatch_size li").removeClass('hover');
        }).click(function(){
            $("#configurable_swatch_size li").removeClass('selected');
            var size_name = $(this).attr('name');
            $(".option-"+size_name).addClass('selected');
            //select input
            size_id = $(".option-"+size_name).attr('id');
            $("#product_size_id").val(size_id.substring(7,size_id.length));
        });
    });

    function selectColor(color_slug) {
        var config = <?php echo json_encode($product_attributes) ?>;
        $("#configurable_swatch_size li").addClass('not-available');
        $.each(config, function( key, value ) {
            if(value.ProductColor.slug.toUpperCase() === color_slug.toUpperCase()) {
                if(value.ProductAttribute.qty != 0) {
                     $(".option-"+value.ProductSize.size.toLowerCase()).removeClass('not-available');
                    $("#addToCartBtn").html('<a href="javascript:;" onclick="add_to_cart()" class="btn-cart">\
                        <i class="icon_oneweb icon_cart"></i>\
                        <span><?php echo __('Thêm vào giỏ hàng'); ?></span>\
                    </a>');
                }
            }
        });
    }

    function selectSize(size_name) {
        var config = <?php echo json_encode($product_attributes) ?>;
        $(".is-media").addClass('not-available');
        $.each(config, function( key, value ) {
            // console.log(value);
            if(size_name == null) {
                $(".option-"+value.ProductColor.slug.toLowerCase()).removeClass('not-available');
                $("#addToCartBtn").html('<a href="javascript:;" onclick="add_to_cart()" class="btn-cart">\
                        <i class="icon_oneweb icon_cart"></i>\
                        <span><?php echo __('Thêm vào giỏ hàng'); ?></span>\
                    </a>');
            } else {
                if(value.ProductSize.size === size_name.toUpperCase()) {
                    if(value.ProductAttribute.qty != 0) {
                        $(".option-"+value.ProductColor.slug.toLowerCase()).removeClass('not-available');
                        $("#addToCartBtn").html('<a href="javascript:;" onclick="add_to_cart()" class="btn-cart">\
                        <i class="icon_oneweb icon_cart"></i>\
                        <span><?php echo __('Thêm vào giỏ hàng'); ?></span>\
                    </a>');
                    }
                }
            }
        });
    }
    </script>
</fieldset>
<?php } ?>