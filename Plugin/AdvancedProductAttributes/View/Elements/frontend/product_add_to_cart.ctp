<?php if(!empty($product_attributes)) {?>
 <?php echo $this->Html->css('AdvancedProductAttributes.styles') ?>
<?php echo $this->Html->script('AdvancedProductAttributes.scripts') ?>
<div class="product-options-bottom">
    <div class="price-button-box">
        <div class="add-to-cart">
            <div class="qty-wrapper">
                <label for="qty">Số lượng:</label>
                <div class="control">
                    <input type="text" name="qty" id="qty" minlength="1" maxlength="12" value="1" title="Số lượng" class="input-text qty validation-passed number-only">

                    <span class="btn-number qtyminus" data-type="minus" data-field="qty">-</span>
                    <span class="btn-number qtyplus" data-type="plus" data-field="qty">+</span>
                </div>
                <div id="addToCartBtn" class="hidden-xs">
                    <a href="javascript:;" onclick="add_to_cart()" class="btn-cart">
                        <i class="icon_oneweb icon_cart"></i>
                        <span><?php echo __('Thêm vào giỏ hàng'); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>