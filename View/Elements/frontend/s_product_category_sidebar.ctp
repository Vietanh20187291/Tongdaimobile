<?php
if(!empty($data)){
?>
<aside class="box category_sub">
	<div class="title">
		<i class="fa fa-list-ul" aria-hidden="true"></i>
		<?php echo $title?>
	</div>
	<div class="nav-v" id="tree">
		<?php echo $this->OnewebVn->productCategory($data,0)?>
	</div>
</aside>
<?php }?>