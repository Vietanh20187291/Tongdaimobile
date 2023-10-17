<?php if(empty($type)){?>
<div class="paginator">
	<?php 
		echo $this->Paginator->counter(array('format'=>'<span class="page">%page%/%pages%</span>'));
		echo $this->Paginator->first('<<',array('separator'=>false,'title'=>__('Trang đầu',true)));
		echo $this->Paginator->prev('<', array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->next('>', array(), null, array('class' => 'next disabled'));
		echo $this->Paginator->last('>>',array('separator'=>false,'title'=>__('Trang cuối',true)));
	?>
</div> <!-- end .paginator -->
<?php }else{?>
<div class="paginator footer">
	<?php 
		echo $this->Paginator->counter(array('format'=>'<span class="page">%page%/%pages%</span>'));
		echo $this->Paginator->first('<<',array('separator'=>false,'title'=>__('Trang đầu',true)));
		echo $this->Paginator->prev('<', array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator'=>false,'modulus'=>7,'class'=>'number'));
		echo $this->Paginator->next('>', array(), null, array('class' => 'next disabled'));
		echo $this->Paginator->last('>>',array('separator'=>false,'title'=>__('Trang cuối',true)));
	?>
</div> <!-- end .paginator -->
<?php }?>