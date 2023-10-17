<section class="related">
    <div class="title">
        <span class="p-l-15 font-weight-bold"><?php echo __('Sản phẩm liên quan', true) ?></span>
    </div>
    <div class="line_title"></div>
    <div class="row auto-clear">
        <?php
        echo $this->element('frontend/c_product', array(
            'data' => $a_related_products_c,
            'position' => '',
            'limit' => '9',
            'cart' => false,
            'class' => '',
            'w' => 400,
            'zc' => 2
        ))
        ?>
    </div>
</section>