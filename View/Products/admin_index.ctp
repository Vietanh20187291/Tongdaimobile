<?php if(!empty($_GET['keyword'])) echo $this->Html->script('admin/highlight');?>

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

	//Thay đổi trạng thái
	function changeStatus(field,id){
		$.ajax({
				type:'post',
				url: '<?php echo $this->Html->url(array('action'=>'ajaxChangeStatus'));?>',
				data: 'field='+field+'&id='+id,
				beforeSend: function(){
					$("#loading").show();
				},
				dataType:'json',
				success: function(result){
					$("#item_"+id+" ."+field+" a.act").removeClass(result.remove);
					$("#item_"+id+" ."+field+" a.act").addClass(result.add);
					if(field!='status') $("#item_"+id+" a.display").text(result.count);
					$("#loading").hide();
				}
			});
	}

	//Xóa sản phẩm
	function trashItem(id){
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('action'=>'ajaxTrashItem'))?>',
			data:'id='+id,
			beforeSend:function(){
				$("#loading").show();
			},
			success: function(result){
				if(result){
					$("#item_"+id).fadeOut(110);
				}else{
					$(".question").fadeOut();
					alert('<?php echo __('Có lỗi, vui lòng thử lại',true)?>');
				}
				$("#loading").hide();
			}
		});
	}

	//Sắp xếp lại sản phẩm
	function changeSort(val,field,id){
		if(val<1) val=1;
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('action'=>'ajaxChangeSort'))?>',
			data:'val='+val+'&field='+field+'&id='+id,
			beforeSend:function(){
				$("#loading").show();
			},
			success:function(){
				$("#loading").hide();
			}
		});
	}
</script>

<?php echo $this->element('backend/c_comment')?>

<div id="column_right">
	<div id="action_top">
		<div id="action_1" class="box_select">
			<ul>
				<li class="active"><?php echo __('Kích hoạt',true)?></li>
				<li class="unactive"><?php echo __('Bỏ kích hoạt',true)?></li>
				<li class="trashes"><?php echo __('Thùng rác',true)?></li>
			</ul>
		</div> <!--  end .box_select -->

		<?php
		echo $this->Form->create('Product',array('url'=>array('action'=>'index'),'type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('position',array('type'=>'hidden','value'=>(!empty($_GET['position']))?$_GET['position']:''))?>
		<ul class="search">
			<li><?php echo $this->Form->input('category_id',array('type'=>'select','options'=>$a_product_categories_c,'value'=>(empty($_GET['keyword'])?'':(!empty($_GET['category_id'])?$_GET['category_id']:'')),'empty'=>__('Chọn danh mục',true),'class'=>'medium'))?></li>
			<li<?php if(empty($oneweb_product['maker'])) echo ' class="hidden"'?>><?php echo $this->Form->input('maker_id',array('type'=>'select','options'=>$a_product_makers_c,'empty'=>__('Chọn hãng sản xuất',true),'class'=>'medium'))?></li>
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tên sản phẩm, Mã sản phẩm',true),'class'=>'larger auto_complete_search','onblur'=>'if (this.value==""){ this.value="'.__('Tên sản phẩm, Mã sản phẩm',true).'";}','onfocus'=>'if (this.value=="'.__('Tên sản phẩm, Mã sản phẩm',true).'") { this.value=""; }','id'=>'keyword'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('sản phẩm',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>

		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->

	<?php
		echo $this->Form->create('Product',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<?php if(!empty($a_list_children_c)){	//Danh sách danh mục sản phẩm trực tiếp?>
		<?php if(empty($_GET['view'])) echo $this->Html->link('...','javascript:;',array('title'=>__('Danh mục'),'onclick'=>'viewListCategory()','class'=>'view_list'))?>
		<ul class="list_category<?php if(!empty($_GET['view'])) echo ' show'?>">
			<?php foreach($a_list_children_c as $val){
				$item_cate = $val['ProductCategory'];
				$a_counter = unserialize($item_cate['counter']);
				$view_product1 = array('action'=>'index','?'=>array('category_id'=>$item_cate['id']));
				$view_product2 = $view_product1;
				if(!empty($_GET['view'])) $view_product2['?']['view'] = 2;
				echo $this->Html->tag('li',
										$this->Html->tag('span','&nbsp;',array('class'=>'act folder'.(!$item_cate['status']?' unactive':''),'escape'=>false))
										.$this->Html->link($this->Text->truncate($item_cate['name'],30),$view_product2,array('title'=>$item_cate['name'],'class'=>'tooltip','escape'=>false))
										.' <span class="counter">[ '.$this->Html->link(number_format($a_counter['cate']),array('controller'=>'product_categories','action'=>'index','?'=>array('parent_id'=>$item_cate['id'])),array('title'=>number_format($a_counter['cate']).' '.__('danh mục con',true),'class'=>'tooltip'))
										.' - '.$this->Html->link(number_format($a_counter['item']),$view_product1,array('title'=>number_format($a_counter['item']).' '.__('sản phẩm',true),'class'=>'tooltip'))
										.' ]</span>'
									);
			}?>
		</ul> <!--  end .list_category -->
		<?php }?>

		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th class="small center"><?php __('Ảnh',true)?></th>
				<th><?php echo $this->Paginator->sort('name',__('Tên',true))?></th>
				<?php if(!empty($oneweb_product['price'])){?>
				<th class="small center"><?php echo $this->Paginator->sort('price',__('Giá cũ/Giá mới',true).' ('.$a_currency_c['unit'].')')?></th>
				<?php }?>
				<th class="small center"><?php echo $this->Paginator->sort('ProductCategory.name',__('Danh mục',true))?></th>
				<?php if(!empty($oneweb_product['maker'])){?>
				<th class="small center"><?php echo $this->Paginator->sort('ProductMaker.name',__('Hãng',true))?></th>
				<?php }?>
				<th class="small center"><?php echo __('Sắp xếp',true)?></th>
				<?php if(!empty($oneweb_product['display'])){?>
				<th class="small center"><?php __('Hiển thị',true)?></th>
				<?php }?>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('created',__('Ngày tạo',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_products_c as $val){
				$item_product = $val['Product'];
				$item_category = $val['ProductCategory'];
				$item_maker = $val['ProductMaker'];
				$comment = number_format(count($val['Comment']));
				$comment_active = 0;
				for($i=0;$i<$comment;$i++) if($val['Comment'][$i]['status']) $comment_active++;

				$url_edit = array('controller'=>'products','action'=>'edit',$item_product['id'],'?'=>array('url'=>$current_url_c));
				$url_view = array('controller'=>'products','action'=>'index','lang'=>$item_product['lang']);
				$tmp = explode(',', $item_category['path']);
				for($i=0;$i<count($tmp);$i++){
					$url_view['slug'.$i]=$tmp[$i];
				}
				$url_view['slug'.count($tmp)] = $item_product['slug'];
				$url_view['ext']='html';
				$url_view['admin'] = false;

				if(!empty($item_product['image'])) $img = '/timthumb.php?src='.$this->Html->url('/webroot/img/images/products/').$item_product['image'];
				
				$img_small = $img."&h=40&w=40&zc=2";
				$img_larger = $img."&h=300&w=300&zc=2";
			?>
			<tr id="<?php echo 'item_'.$item_product['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_product['id']?>" /></td>
				<td class="center">
					<div class="thumb">
						<?php
							if(!empty($item_product['discount'])) echo '<div class="discount"></div>';
							if(!empty($item_product['promotion'])) echo '<div class="promotion"></div>';

							echo $this->Html->link($this->Html->image($img_small,array('alt'=>$item_product['name'])),$img_larger,array('title'=>$item_product['name'],'class'=>'preview','target'=>'_blank','escape'=>false));
						?>
					</div>
				</td>
				<td>
					<?php
						echo $this->Html->link($this->Text->truncate($item_product['name'],100,array('extact'=>false)),$url_edit,array('title'=>$item_product['name'],'class'=>'tooltip name'));
						if ($item_product['quantity']<1) echo $this->Html->link('('.__('Hết hàng',true).')',array('controller'=>'products','action'=>'index','?'=>array('stock'=>0)),array('title'=>__('Hết hàng',true),'class'=>'quantity'));
					?>
					<div class="view-comment">
						<?php
							echo $this->Html->tag('p',__('Mã SP',true).': '.$item_product['code'],array('class'=>'code'));
							echo '<p class="view">'.$this->Html->link(__('Lượt xem',true).': '.number_format($item_product['view'],0,',','.'),'javascript:;',array('title'=>__('Có',true).' '.number_format($item_product['view'],0,',','.').' '.__('lượt xem',true),'class'=>'tooltip')).'</p>';
							if($oneweb_product['comment']){
								if($comment>0){
									if($comment_active<$comment) $cl_c = ' red';
									else $cl_c = '';

									echo $this->Html->link(__('Bình luận',true).': '.number_format($comment,0,',','.'),'javascript:;',array('title'=>__('Có',true).' '.number_format($comment_active,0,',','.').' '.__('bình luận được kích hoạt trên tổng số',true).' '.number_format($comment,0,',','.').' '.__('bình luận',true),'onclick'=>"comment({$item_product['id']},'Product');",'class'=>'tooltip'.$cl_c));
								}else
									echo $this->Html->link(__('Bình luận',true).': '.number_format($comment,0,',','.'),'javascript:;',array('title'=>__('Chưa có bình luận nào',true),'onclick'=>"comment({$item_product['id']},'Product');",'class'=>'comment tooltip'));
							}
						?>
					</div>
				</td>
				<?php if(!empty($oneweb_product['price'])){?>
				<td class="center">
					<?php
						if(!empty($item_product['price'])){
							if($a_currency_c['unit']=='VND' || $a_currency_c['unit']=='VNĐ') echo number_format($item_product['price'],0,',','.');
							else echo number_format($item_product['price'],0);
//							if(!empty($item_product['discount'])){
							if(!empty($item_product['price_new'])){
								echo ' / ';
								echo number_format($item_product['price_new'],0);
							}
						}else echo __('Call',true);
					?>
				</td>
				<?php }?>
				<td class="cate">
					<?php
						echo (!empty($item_category['name']))?$this->Html->link($this->Text->truncate($item_category['name'],20,array('exact'=>false)),array('controller'=>'products','action'=>'index','?'=>array('category_id'=>$item_category['id'])),array('title'=>$item_category['name'],'class'=>'tooltip'.(!$item_category['status']?' unactive':''))):'<span class="error">**&nbsp;ERROR&nbsp;**</span>';

						//Danh mục hiển thị khác
						if(!empty($item_product['category_other'])){
							$a_category_other = array_filter(explode('-', $item_product['category_other']));
							echo '<div>';
							foreach($a_category_other as $val2){
								if($val2!=$item_category['id'] && !empty($a_product_categories_c[$val2])){
									$item = array_filter(explode('_', $a_product_categories_c[$val2]));
									sort($item);
									echo $this->Html->link($this->Text->truncate($item[0],15,array('exact'=>false)),array('controller'=>'products','action'=>'index','?'=>array('category_id'=>$val2)),array('title'=>$item[0],'class'=>'tooltip')).', ';
								}
							}
							echo '</div>';
						}
					?>
				</td>
				<?php if(!empty($oneweb_product['maker'])){?>
				<td><?php echo (!empty($item_maker['name']))?$this->Html->link($this->Text->truncate($item_maker['name'],15,array('exact'=>false)),array('controller'=>'products','action'=>'index','?'=>array('maker_id'=>$item_maker['id'])),array('title'=>$item_maker['name'],'class'=>'tooltip')):'<span class="error">**&nbsp;ERROR&nbsp;**</span>'?></td>
				<?php }?>
				<td class="center">
					<?php
						if(!empty($_GET['position'])){
							$sort = $item_product['pos_'.$_GET['position']];
							$field_sort = 'pos_'.$_GET['position'];
						}else{
							$sort = $item_product['sort'];
							$field_sort = 'sort';
						}
						echo $this->Form->input('sort',array('class'=>'small','value'=>$sort,'onchange'=>"changeSort(this.value,'$field_sort',{$item_product['id']})"));
					?>
				</td>
				<?php if(!empty($oneweb_product['display'])){?>
				<td class="center">
					<div class="display">

						<ul>
							<?php
								$count_pos = 0;
								foreach($oneweb_product['display'] as $key2=>$val2){
									if(!empty($item_product['pos_'.$key2])) $count_pos++;
							?>
							<li class="pos_<?php echo $key2?>">
								<?php
									echo $this->Html->link('&nbsp;','javascript:;',array('onclick'=>"changeStatus('pos_$key2',{$item_product['id']})",'class'=>'act '.((empty($item_product['pos_'.$key2]))?'unactive':'active'),'escape'=>false));
									echo $this->Html->link(__($val2,true),array('controller'=>'products','action'=>'index','?'=>array('position'=>$key2)),array('title'=>__($val2,true),'class'=>'tooltip'));
								?>
							</li>
							<?php }?>
						</ul>
						<a href="javascript:;" class="act display"><?php echo $count_pos ?></a>
					</div> <!-- end .display -->
				</td>
				<?php }?>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('status',{$item_product['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_product['status']==1)?'active':'unactive')));?></td>
				<td class="center">
					<?php
						echo $this->Html->tag('p',date('d/m/Y',$item_product['created']),array('class'=>'date'));
						echo $this->Html->tag('p',date('H:i:s',$item_product['created']),array('class'=>'time'));
					?>
				</td>
				<td class="center action">
					<?php
						echo $this->Html->link('&nbsp;',$url_view,array('title'=>__('Xem',true),'class'=>'act view','escape'=>false,'target'=>'_blank'));
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:trashItem({$item_product['id']});",array('title'=>'Thùng rác','class'=>'act trash','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
<!--		<select>-->
<!--			<option value="">Copy</option>-->
<!--			<option>Copy tới mục tiếng anh</option>-->
<!--		</select>-->
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
					url: "<?php echo $this->Html->url(array('controller'=>'products', 'action'=>'ajaxLoadProductCode'))?>",
					dataType: "json",
					data: {
						featureClass: "P",
						style: "full",
						maxRows: 12,
						name_startsWith:request.term
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
