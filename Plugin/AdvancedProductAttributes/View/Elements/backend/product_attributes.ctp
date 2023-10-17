<?php echo $this->Html->css('AdvancedProductAttributes.admin/styles') ?>
<?php echo $this->Html->script('AdvancedProductAttributes.admin/scripts') ?>
<table class="add">
	<tr>
		<th>
			<div class="float-left color_picker_th">
				<?php echo $this->Form->label('ProductAttribute.product_color_id',__('Màu sắc',true))?>
				<?php echo $this->Form->label('ProductAttribute.product_color_id',__('Mã màu',true))?>
				<?php echo $this->Form->label('ProductAttribute.product_size_id',__('Kích thước',true))?>
				<?php echo $this->Form->label('ProductAttribute.qty',__('Số lượng',true))?>
			</div>
		</th>
		<td>
			<div id="product_color" class="float-left">
				<?php if(empty($product_attributes)) { ?>
				<div class="float-left color_picker" data-idx="0">
					<?php echo $this->Form->input('ProductAttribute.0.product_id',array('type'=>'hidden', 'value'=>$this->request->data['Product']['id']))?>
					<?php echo $this->Form->input('ProductAttribute.0.id',array('type'=>'hidden', 'class'=>'w52'))?>
					<?php echo $this->Form->input('ProductAttribute.0.product_color_id',array('type'=>'select','options'=>$list_product_color, 'empty'=>'', 'class'=>'w52', 'onchange'=>"changeColor(this.value, '0')", 'required'=>false))?>
					<?php echo $this->Form->input('Color.0.hex',array('type'=>'text','id'=>'ColorHex0','name'=>'ColorHex0', 'readOnly'=>true, 'class'=>'w52 color_hex', 'required'=>false))?>
					<?php echo $this->Form->input('ProductAttribute.0.product_size_id',array('type'=>'select', 'options'=>$list_product_size,'empty'=>'', 'class'=>'w52', 'required'=>false))?>
					<?php echo $this->Form->input('ProductAttribute.0.qty',array('type'=>'number', 'class'=>'w52 number-only', 'onKeyPress'=>"if(this.value.length==5) return false;", 'min'=>0, 'required'=>false))?>
				</div>
				<?php } else {
					foreach($product_attributes as $key=>$product_attribute) { ?>
						<div class="float-left color_picker"  data-idx="<?php echo $key ?>">
							<?php echo $this->Form->input('ProductAttribute.'.$key.'.id',array('type'=>'hidden', 'class'=>'w52', 'value'=>$product_attribute['ProductAttribute']['id']));
								echo $this->Form->input('ProductAttribute.'.$key.'.product_id',array('type'=>'hidden', 'value'=>$this->request->data['Product']['id']));
							?>
							<?php echo $this->Form->input('ProductAttribute.'.$key.'.product_color_id',array('type'=>'select','options'=>$list_product_color, 'class'=>'w52', 'value'=>$product_attribute['ProductAttribute']['product_color_id'], 'onchange'=>"changeColor(this.value, '{$key}')", 'empty'=>'','required'=>false))?>
							<?php echo $this->Form->input('Color.'.$key.'.hex',array('type'=>'text', 'readOnly'=>true,'id'=>'ColorHex'.$key,'name'=>'ColorHex'.$key, 'class'=>'w52 color_hex', 'value'=>$product_attribute['ProductColor']['hex'],'style'=>'background-color: '.$product_attribute['ProductColor']['hex'].';'))?>
							<?php echo $this->Form->input('ProductAttribute.'.$key.'.product_size_id',array('type'=>'select','options'=>$list_product_size, 'class'=>'w52', 'value'=>$product_attribute['ProductAttribute']['product_size_id']))?>
							<?php echo $this->Form->input('ProductAttribute.'.$key.'.qty',array('type'=>'number', 'class'=>'w52 number-only', 'onKeyPress'=>"if(this.value.length==5) return false;", 'min'=>0, 'value'=>$product_attribute['ProductAttribute']['qty']))?>
							<!-- <script>
								$('#ProductAttribute<?= $key ?>Hex').ColorPicker({
									onSubmit: function(hsb, hex, rgb, el) {
										$(el).val(hex);
										$(el).ColorPickerHide();
									},
									onBeforeShow: function () {
										$(this).ColorPickerSetColor(this.value);
									},
									onChange: function (hsb, hex, rgb) {
										$('#ProductAttribute<?= $key ?>Hex').val('#' + hex);
										$('#ProductAttribute<?= $key ?>Hex').css('background-color', '#' + hex);
									}
								})
								.bind('keyup', function(){
									$(this).ColorPickerSetColor(this.value);
								});
								</script> -->
						</div>
					<?php } ?>
				<?php } ?>
			</div>
			<a href="javascript:;" title="Thêm" onclick="addProductAttribute()" class="act add tooltip">&nbsp;</a>
		</td>
	</tr>
</table>
<i>&nbsp;&nbsp;Ghi chú: Nếu muốn xóa thì chọn màu về giá trị rỗng.</i>
<script>
	$(function(){

	});

	function changeColor(colr_id, idx) {
		var product_id = $("#ProductAttribute0ProductId").val();
		$.ajax({
			type:'post',
			dataType:'json',
			url:'<?php echo $this->Html->url(array('plugin'=>'AdvancedProductAttributes', 'controller'=>'product_colors', 'action'=>'admin_ajaxChangeColor'))?>',
			data:{'id':colr_id,'product_id':product_id},
			success:function(result) {
				$("#ColorHex"+idx).val(result.hex);
				$("#ColorHex"+idx).css('background-color', result.hex);
				$("#ProductAttribute"+idx+"Qty").attr('required', true);
				$("#ProductAttribute"+idx+"ProductSizeId").attr('required', true);

				// options = '';
				// $.each(result.product_size,function(key,value){
				// 	options += '<option value="'+key+'">'+value+'</option>';
				// });
				// $("#ProductAttribute"+i+"ProductSizeId").html(options);
			}
		});
	}
</script>
<!-- <?php if(empty($product_attributes)) {?>
<script>
$('#ColorHex').({
	onSubmit: function(hsb, hex, rgb, el) {
		$(el).val(hex);
		$(el).ColorPickerHide();
	},
	onBeforeShow: function () {
		$(this).ColorPickerSetColor(this.value);
	},
	onChange: function (hsb, hex, rgb) {
		$('#ColorHex').val('#' + hex);
		$('#ColorHex').css('background-color', '#' + hex);
	}
})
.bind('keyup', function(){
	$(this).ColorPickerSetColor(this.value);
});
</script>
<?php } ?> -->