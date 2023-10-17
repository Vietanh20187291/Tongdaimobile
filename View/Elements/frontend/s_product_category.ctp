<?php 
if($oneweb_product['multi_box']){
foreach ($a_product_categories_s as $key=>$val){
	$item_cate = $val['ProductCategory'];
	$item_cate_child = $val['children'];
?>
<aside class="box category bg_white hidden-xs ex_border">
	<div class="title">
		<span class="icon"></span>
		<h2><span class="title_text"><?php echo $item_cate['name']?></span></h2>
	</div>
	<div class="nav-v" id="tree_<?php echo $key;?>">
		<?php echo $this->OnewebVn->productCategory($item_cate_child,0)?>						
		<div class="clear"></div>				
	</div> <!--  end #tree -->
</aside> <!--  end .box -->
<?php }}else{?>
<aside class="box category bg_white hidden-xs  ex_border">
	<div class="nav-v" id="tree">
		<?php echo $this->OnewebVn->productCategory($a_product_categories_s,0)?>						
		<div class="clear"></div>				
	</div> <!--  end #tree -->
</aside> <!--  end .box -->
<?php }?>
