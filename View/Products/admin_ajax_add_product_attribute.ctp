<div class="float-left color_picker" data-idx="<?php echo $idx ?>">
	<?php echo $this->Form->input('ProductAttribute.'.$idx.'.product_id',array('type'=>'hidden', 'value'=>$product_id))?>
	<?php echo $this->Form->input('ProductAttribute.'.$idx.'.id',array('type'=>'hidden', 'class'=>'w52'))?>
	<?php echo $this->Form->input('ProductAttribute.'.$idx.'.product_color_id',array('type'=>'select','options'=>$list_product_color, 'class'=>'w52','label'=>false,'empty'=>'', 'onchange'=>"changeColor(this.value, '{$idx}')", 'required'=>true))?>
	<?php echo $this->Form->input('Color.'.$idx.'.hex',array('type'=>'text','label'=>false,'id'=>'ColorHex'.$idx,'name'=>'ColorHex'.$idx, 'readOnly'=>true, 'class'=>'w52', 'required'=>true))?>
	<?php echo $this->Form->input('ProductAttribute.'.$idx.'.product_size_id',array('type'=>'select', 'options'=>$list_product_size,'label'=>false, 'class'=>'w52', 'required'=>true))?>
	<?php echo $this->Form->input('ProductAttribute.'.$idx.'.qty',array('type'=>'number','label'=>false, 'class'=>'w52 number-only', 'onKeyPress'=>"if(this.value.length==5) return false;", 'min'=>0, 'required'=>true))?>
</div>