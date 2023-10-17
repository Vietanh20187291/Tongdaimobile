<script type="text/javascript">
	//submit action top
	$(document).ready(function(){
		$("#action_1").find('li').click(function(){
			var c = confirm("<?php echo __('Bạn có chắc chắn muốn thực hiện hành động này',true)?>?");
			if(c==true){
				val = $(this).attr('class');
				$("#action").val(val);
				document.form.submit();
			};
		});
	});

	//Xóa đơn hàng
	function deleteItem(id){
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('action'=>'ajaxDeleteItem'))?>',
			data:'id='+id,
			beforeSend:function(){
				$("#loading").show();
			},
			success: function(result){
				if(result){
					$("#item_"+id).fadeOut(110);
				}else{
					$(".question").fadeOut();
					alert('Có lỗi, vui lòng thử lại');
				}
				$("#loading").hide();
			}
		});
	};

	//Thay đổi danh mục
	function setCategory(order_id,cate_id){
		$.ajax({
			type: 'post',
			url : '<?php echo $this->Html->url(array('action'=>'ajaxSetCategory'))?>',
			data: 'order_id='+order_id+'&cate_id='+cate_id,
			beforeSend:function(){
				$("#loading").show();
			},
			success:function(result){
				$("#loading").hide();
				if(result==false) alert('<?php echo __('Có lỗi, vui lòng thử lại',true)?>');
			}
		});
	}
</script>

<div id="column_right">
	<div id="action_top">
		<div id="action_1" class="box_select">
			<ul>
				<li class="view"><?php echo __('Đã đọc',true)?></li>
				<li class="unview"><?php echo __('Chưa đọc',true)?></li>
				<li class="del"><?php echo __('Xóa',true)?></li>
			</ul>
		</div> <!--  end .box_select -->

		<?php echo $this->Form->create('Order',array('url'=>array('action'=>'index'),'type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)))?>
		<ul class="search">
			<li><?php echo $this->Form->input('category_id',array('type'=>'select','options'=>$a_list_categories_s,'empty'=>__('Chọn nhóm',true),'class'=>'medium'))?></li>
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Mã giao dịch, Họ tên, Email, Phone',true),'class'=>'larger auto_complete_search','onblur'=>'if (this.value==""){ this.value="'.__('Mã giao dịch, Họ tên, Email, Phone',true).'";}','onfocus'=>'if (this.value=="'.__('Mã giao dịch, Họ tên, Email, Phone',true).'") { this.value=""; }'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('đơn hàng',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>

		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->

	<?php
		echo $this->Form->create('Order',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th class="small"><?php echo $this->Paginator->sort('transaction_code',__('Mã giao dịch',true))?></th>
				<th><?php echo $this->Paginator->sort('name',__('Họ tên',true))?></th>
				<th><?php echo $this->Paginator->sort('method_payment',__('Sản phẩm',true))?></th>
<!--				<th class="small">--><?php //echo $this->Paginator->sort('status',__('Trạng thái',true))?><!--</th>-->
<!--				<th class="small">--><?php //echo $this->Paginator->sort('method_payment',__('Thanh toán',true))?><!--</th>-->
<!--				<th class="small center">--><?php //echo $this->Paginator->sort('OrderCategory.name',__('Nhóm',true))?><!--</th>-->
				<th class="small center"><?php echo $this->Paginator->sort('created',__('Ngày tạo',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_orders_c as $val){
				$item_order = $val['Order'];
                $item_order['content'] = @unserialize($item_order['content']);
				$item_category = $val['OrderCategory'];

				$url_view = array('controller'=>'orders','action'=>'view',$item_order['id'],'?'=>array('url'=>$current_url_c));
			?>
			<tr id="<?php echo 'item_'.$item_order['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_order['id']?>" /></td>
				<td<?php if(!$item_order['view']) echo ' class="new"'?>><?php echo $this->Html->link($item_order['transaction_code'],$url_view,array('title'=>$item_order['transaction_code'],'class'=>'tooltip'))?></td>
				<td>
					<?php
						echo $this->Html->tag('p',$this->Text->truncate($item_order['name'],50,array('extact'=>false)));
						echo $this->Html->tag('p',
							$this->Html->link($item_order['email'],array('action'=>'index','?'=>array('keyword'=>$item_order['email']))).' - '.
							$this->Html->link($item_order['phone'],array('action'=>'index','?'=>array('keyword'=>$item_order['phone'])))
							,array('class'=>'email'))
					?>
				</td>

				<td class="small">
                    <?php
                    $sep1 = ',';
                    $sep2 = '.';
                    $decimal = 0;
                    if(!in_array($item_order['unit_payment'], array('đ','d','vnđ','vnd','Đ','D','VNĐ','VND'))){
                        $sep1 = '.';
                        $sep2 = ',';
                        $decimal = 2;
                    }

                    foreach ($item_order['content'] as $key=>$val){
                    $item_product = $val['Product'];
                    ?>
                    <?php echo $this->Html->tag('p',$item_product['name'],array('class'=>'name'));
                        ?>
                    <?php }?>

                </td>
<!--				<td class="small">--><?php //echo $this->Html->link($item_order['status'],array('action'=>'index','?'=>array('status'=>$item_order['status'])))?><!--</td>-->
<!--				<td class="small">--><?php //echo $this->Html->link($this->Text->truncate($item_order['method_payment'],15),array('action'=>'index','?'=>array('method'=>$item_order['method_payment'])))?><!--</td>-->
<!--				<td>--><?php //echo $this->Form->input('order_category_id',array('type'=>'select','options'=>$a_list_categories_c,'class'=>'medium','empty'=>__('Chọn nhóm',true),'value'=>$item_category['id'],'onchange'=>"setCategory({$item_order['id']},this.value)"))?><!--</td>-->
				<td class="center">
					<?php
						echo $this->Html->tag('p',$this->Html->link(date('d/m/Y',$item_order['created']),array('action'=>'index','?'=>array('start'=>$item_order['created'],'end'=>$item_order['created'])),array('title'=>'Ngày tạo: '.date('d/m/Y',$item_order['created']))),array('class'=>'date'));
						echo $this->Html->tag('p',date('H:i:s',$item_order['created']),array('class'=>'time'));
					?>
				</td>
				<td class="center action">
					<?php echo $this->Html->link('&nbsp;',"javascript:deleteItem({$item_order['id']});",array('title'=>__('Xóa',true),'class'=>'act delete','escape'=>false))?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>

</div> <!--  end #column_right -->
<?php
echo $this->Html->script(array('jquery-ui/autocomplete/jquery.ui.core','jquery-ui/autocomplete/jquery.ui.menu','jquery-ui/autocomplete/jquery.ui.position','jquery-ui/autocomplete/jquery.ui.widget','jquery-ui/autocomplete/jquery-ui-1.9.2.custom',));
?>
<script type="text/javascript">

	$(function() {
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}

		$( ".auto_complete_search" )
		// don't navigate away from the field on tab when selecting an item
		.bind( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).data( "ui-autocomplete" ).menu.active ) {
				event.preventDefault();
			}
		})
		.autocomplete({
			minLength: 0,
			source: function( request, response ) {

				// delegate back to autocomplete, but extract the last term
				$.ajax({
					url: "<?php echo $this->Html->url(array('controller'=>'orders', 'action'=>'ajaxLoadOrder'))?>",
					dataType: "json",
					data: {
						featureClass: "P",
						style: "full",
						maxRows: 12,
						name_startsWith: request.term
					},
					success: function( data ) {
						$("#ui-id-1").html('');
						if(data.length ==0){
							$("#ui-id-1").append('<li class="ui-menu-item"><a href="javascript:;">Không tìm thấy sản phẩm</a></li>');
						}else{

							response( $.ui.autocomplete.filter(
									data,request.term) );
						}

					}
				});
			},
			focus: function() {
				// prevent value inserted on focus
				return false;
			}
		});


	});
</script>
