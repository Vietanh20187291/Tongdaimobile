<?php echo $this->Html->script(array('ckeditor/ckeditor','ckfinder/ckfinder'));?>

<div id="column_right">
	<!-- tab -->
	<div id="action_top">
		<ul class="tabs">
				<li><a href="#tab1"><?php echo __('Thông tin',true)?></a></li>
				<?php if(!empty($oneweb_product['images'])){?>
				<li><a href="#tab2"><?php echo __('Hình ảnh',true)?></a></li>
				<?php }?>
				<?php if(!empty($oneweb_seo)){?>
				<li><a href="#tab3"><?php echo __('SEO',true)?></a></li>
				<?php }?>
			</ul> <!-- end .tabs -->

			<ul class="action_top_2">
				<li><?php echo $this->Html->link('&nbsp;',array('action'=>'index'),array('title'=>__('Thoát',true),'class'=>'exit','escape'=>false))?></li>
			</ul> <!-- end .action_top_2 -->
	</div> <!--  end #action_top -->

	<div id="content">
		<?php echo $this->Form->create('Product',array('type'=>'file','id'=>'form','inputDefaults'=>array('label'=>false,'div'=>false)))?>

		<div class="tab_container">
			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?><span></span></li>
				<li><?php echo $this->Form->submit(__('Lưu & Thêm mới',true),array('name'=>'save_add','div'=>false))?><span></span></li>
				<li><?php echo $this->Form->submit(__('Lưu và Thoát',true),array('name'=>'save_exit','div'=>false))?><span></span></li>
			</ul> <!-- end .submit -->

			<div id="tab1" class="tab_content">
				<table class="add column1">
					<tr>
						<th><?php echo $this->Form->label('name',__('Tên sản phẩm',true))?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('name',array('class'=>'larger','onchange'=>'getFieldByName("Product")'))?></td>
					</tr>

					<tr>
						<th><?php echo $this->Form->label('name_en',__('Tên tiếng anh',true))?></th>
						<td><?php echo $this->Form->input('name_en',array('class'=>'larger'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('count_buyed',__('Số người mua',true))?></th>
						<td><?php echo $this->Form->input('count_buyed',array('class'=>'medium'))?></td>
					</tr>
					<tr<?php if(empty($oneweb_product['code'])) echo ' class="hidden"'?>>
						<th><?php echo $this->Form->label('code',__('Model',true))?></th>
						<td><?php echo $this->Form->input('code',array('class'=>'medium'))?></td>
					</tr>
					<?php if(!empty($oneweb_product['maker'])){?>
					<tr>
						<th><?php echo $this->Form->label('product_maker_id',__('Hãng sản xuất',true))?><span class="im">*</span></th>
						<td><?php echo $this->Form->input('product_maker_id',array('type'=>'select','options'=>$a_makers_c,'empty'=>__('Chọn hãng sản xuất',true),'class'=>'medium','required'=>true))?></td>
					</tr>
					<?php }?>
					<tr>
						<th><?php echo $this->Form->label('product_category_id',__('Danh mục sản phẩm',true))?><span class="im">*</span></th>
						<td>
							<?php
								echo $this->Form->input('product_category_id',array('type'=>'select','options'=>$a_categories_c,'empty'=>__('Chọn danh mục',true),'class'=>'medium','required'=>true));
								echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Chọn danh mục hiển thị khác',true),'onclick'=>"more('more_category')",'class'=>'act add tooltip','escape'=>false));
								echo $this->Form->input('category_other',array('type'=>'select','options'=>$a_categories_c,'empty'=>__('Chọn danh mục hiển thị khác',true),'class'=>'medium','multiple'=>true,'size'=>8,'div'=>array('id'=>'more_category')));
							?>
						</td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('image',__('Ảnh đại diện',true))?></th>
						<td>
							<?php
								echo $this->Form->input('image',array('type'=>'file'));
								echo $this->Html->tag('span',__('Kích thước',true).": {$oneweb_product['size']['product'][0]} x {$oneweb_product['size']['product'][1]} (px)",array('class'=>'size_img'));
							?>
						</td>
					</tr>
					<?php if(!empty($a_tabs_c) || !empty($oneweb_product['summary'])){?>
					<tr>
						<td colspan="2">
							<ul class="tabs_2">
								<?php foreach($a_tabs_c as $key=>$val){?>
								<li><a href="#tab2<?php echo $key?>"><?php echo $val?></a></li>
								<?php }?>
								<li class="more"><a href="#summary"><?php echo __('Mô tả thêm',true)?></a></li>
								</ul> <!-- end .tabs -->
							<div class="tab_container_2">
								<?php foreach($a_tabs_c as $key=>$val){?>
									<div id="tab2<?php echo $key?>" class="tab_content_2">
										<?php
											echo $this->Form->input('Product.description.'.($key+1), array('type'=> 'textarea','div'=>'description','required'=>false));
											echo $this->CkEditor->create('Product.description.'.($key+1),array('toolbar'=>'product'));
										?>
									</div> <!-- end #tab21 -->
								<?php }?>
								<div id="summary" class="tab_content_2">
									<?php
										echo $this->Form->input('Product.description.0', array('type'=> 'textarea','div'=>'description','required'=>false));
										echo $this->CkEditor->create('Product.description.0',array('toolbar'=>'product'));
									?>
								</div> <!-- end #tab21 -->
							</div> <!-- end .tab_container_2 -->
						</td>
					</tr>
					<?php }?>
					<?php if(!empty($oneweb_product['price'])){?>
					<tr>
						<th><?php echo $this->Form->label('price',__('Giá cũ',true))?></th>
						<td><?php echo $this->Form->input('price',array('class'=>'medium')).' '.$currency_c?></td>
					</tr>
					<?php }?>
					<?php if(!empty($oneweb_product['discount'])){?>
					<tr>
						<th><?php echo $this->Form->label('price_new',__('Giá mới',true))?></th>
						<td>
							<?php
								echo $this->Form->input('price_new',array('class'=>'medium')).' '.$currency_c;
							?>
						</td>
					</tr>
<!-- 					<tr>
						<th><?php //echo $this->Form->label('discount',__('Giảm',true))?></th>
						<td>
							<?php
								//echo $this->Form->input('discount',array('class'=>'medium')).'%';
							?>
						</td>
					</tr> -->
					<?php }?>
					<?php if(!empty($oneweb_product['promotion'])){?>
					<tr>
						<th><?php echo $this->Form->label('promotion',__('Khuyến mãi',true))?></th>
						<td><?php echo $this->Form->input('promotion',array('type' => 'text', 'class'=>'larger'));?></td>
					</tr>
					<?php }?>
					<?php if(!empty($oneweb_product['quantity'])){?>
					<tr>
						<th><?php echo $this->Form->label('quantity',__('Số lượng',true))?></th>
						<td>
							<?php
								echo $this->Form->input('quantity',array('class'=>'small','value'=>100)).' ';
								echo $this->Form->input('unit2',array('type'=>'select','options'=>$oneweb_product['unit'],'class'=>'small','empty'=>'Đơn vị'));
							?>
						</td>
					</tr>
					<?php }?>
					<tr>
						<th><?php echo $this->Form->label('made',__('Xuất xứ',true))?></th>
						<td>
							<?php
								echo $this->Form->input('made',array('class'=>'medium')).' ';
							?>
						</td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('Product.description.specification',__('Màu sắc',true))?></th>
						<td>
							<?php
								echo $this->Form->input('Product.description.specification',array('class'=>'larger')).' ';
							?>
						</td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('Product.description.unit',__('Kích cỡ',true))?></th>
						<td>
							<?php
								echo $this->Form->input('Product.description.unit',array('class'=>'larger')).' ';
							?>
						</td>
					</tr>
					<?php if(!empty($oneweb_product['warranty'])){?>
					<tr>
						<th><?php echo $this->Form->label('warranty',__('Bảo hành',true))?></th>
						<td><?php echo $this->Form->input('warranty',array('type' => 'text', 'class'=>'larger'));?></td>
					</tr>
					<?php }?>
					<?php if(!empty($oneweb_product['tax'])){?>
					<tr>
						<th><?php echo $this->Form->label('product_tax_id',__('Thuế',true))?></th>
						<td><?php echo $this->Form->input('product_tax_id',array('type'=>'select','options'=>$a_taxes_c,'empty'=>__('Chọn loại thuế',true),'class'=>'medium'))?></td>
					</tr>
					<?php }?>
					<?php if(!empty($oneweb_product['tag'])){?>
					<tr>
						<th><?php echo $this->Form->label('tag',__('Tag',true))?></th>
						<td><?php echo $this->Form->input('tag',array('class'=>'larger auto_complete_tag'))?></td>
					</tr>
					<?php }?>
					<tr>
						<th><?php echo $this->Form->label('Ngày tạo',__('Ngày tạo',true))?></th>
						<td><?php echo $this->Form->input('created',array('type'=>'datetime','minYear'=>date('Y')-5,'maxYear'=>date('Y')+5,'timeFormat'=>24,'empty'=>false))?></td>
					</tr>

					<tr>
						<th><?php echo $this->Form->label('Ngày hiển thị',__('Ngày hiển thị',true))?></th>
						<td><?php echo $this->Form->input('public',array('type'=>'datetime','minYear'=>date('Y')-5,'maxYear'=>date('Y')+5,'timeFormat'=>24,'empty'=>false))?></td>
					</tr>
					<?php if(!empty($oneweb_product['display'])){?>
					<tr>
						<th><?php echo __('Vị trí hiển thị',true)?></th>
						<td class="display">
							<ul>
								<?php foreach($oneweb_product['display'] as $key=>$val){?>
								<li>
									<?php
										echo $this->Form->checkBox('pos_'.$key);
										echo $this->Form->label('pos_'.$key,__($val,true));
									?>
								</li>
								<?php }?>
							</ul> <!-- end .display -->
						</td>
					</tr>
					<?php }?>
					<?php if(!empty($oneweb_seo)){?>
					<tr>
						<th><?php echo $this->Form->label('target','Target')?></th>
						<td><?php echo $this->Form->input('target',array('type'=>'select','options'=>array('_self'=>'_self','_blank'=>'_blank'),'class'=>'medium'))?></td>
					</tr>
					<?php }?>
					<?php if(empty($oneweb_product['quantity'])){?>
					<tr>
						<th><?php echo $this->Form->label('quantity',__('Tình trạng',true))?></th>
						<td><?php echo $this->Form->checkbox('quantity',array('checked'=>true)).' '.__('(còn hàng)')?></td>
					</tr>
					<?php }?>
					<tr>
						<th><?php echo $this->Form->label('hot',__('SP Hot',true))?></th>
						<td><?php echo $this->Form->checkbox('hot')?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('status',__('Kích hoạt',true))?></th>
						<td><?php echo $this->Form->checkbox('status',array('checked'=>true))?></td>
					</tr>
				</table> <!-- end .add -->
			</div> <!-- end #tab1 -->
			<?php if(!empty($oneweb_product['images'])){?>
			<div id="tab2" class="tab_content">
				<table class="add">
					<tr>
						<th><?php echo $this->Form->label('ProductImage',__('Thêm ảnh',true))?></th>
						<td>
							<?php
								echo $this->Form->input('ProductImage',array('type'=>'file','name'=>'data[ProductImage][]','multiple'=>true));
								echo $this->Html->tag('span',__('Kích thước',true).": {$oneweb_product['size']['product'][0]} x {$oneweb_product['size']['product'][1]} (px)",array('class'=>'size_img'));
							?>
						</td>
					</tr>
				</table> <!-- end .add -->
			</div> <!-- end #tab2 -->
			<?php }?>
			<?php if(!empty($oneweb_seo)){?>
			<div id="tab3" class="tab_content">
				<table class="add">
					<tr>
						<th><?php echo $this->Form->label('slug',__('slug',true))?></th>
						<td><?php echo $this->Form->input('slug',array('class'=>'larger','required'=>false))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('meta_title',__('Meta title',true))?></th>
						<td><?php echo $this->Form->input('meta_title',array('class'=>'larger','required'=>false))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('meta_keyword',__('Meta keyword',true))?></th>
						<td><?php echo $this->Form->input('meta_keyword',array('class'=>'larger'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('meta_description',__('Meta description',true))?></th>
						<td><?php echo $this->Form->input('meta_description',array('class'=>'medium'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('meta_robots',__('Meta robots',true))?></th>
						<td><?php echo $this->Form->input('meta_robots',array('type'=>'select','options'=>array('index,follow'=>'index,follow','noindex,nofollow'=>'noindex,nofollow','index,nofollow'=>'index,nofollow','noindex,follow'=>'noindex,follow'),'class'=>'medium'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('rel',__('Rel',true))?></th>
						<td><?php echo $this->Form->input('rel',array('type'=>'select','options'=>array('dofollow'=>'dofollow','nofollow'=>'nofollow'),'class'=>'medium'))?></td>
					</tr>
				</table> <!-- end .add -->
			</div> <!-- end #tab3 -->
			<?php }?>
			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?><span></span></li>
				<li><?php echo $this->Form->submit(__('Lưu & Thêm mới',true),array('name'=>'save_add','div'=>false))?><span></span></li>
				<li><?php echo $this->Form->submit(__('Lưu & Thoát',true),array('name'=>'save_exit','div'=>false))?><span></span></li>
				<li><?php echo $this->Html->link(__('Thoát',true),array('action'=>'index'),array('class'=>'exit'))?></li>
			</ul> <!-- end .submit -->

		</div> <!-- end .tab_container -->

		<?php echo $this->Form->end();?>
	</div> <!--  end #content -->
</div> <!--  end #column_right -->

<?php
if(!empty($oneweb_product['related']) || !empty($oneweb_product['tag'])){
	echo $this->Html->script(array('jquery-ui/autocomplete/jquery.ui.core','jquery-ui/autocomplete/jquery.ui.menu','jquery-ui/autocomplete/jquery.ui.position','jquery-ui/autocomplete/jquery.ui.widget','jquery-ui/autocomplete/jquery-ui-1.9.2.custom',));
}
?>
<script type="text/javascript">

	$(function() {
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}

		$( ".auto_complete_related" )
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
						name_startsWith: extractLast( request.term )
					},
					success: function( data ) {
						$("#ui-id-1").html('');
						if(data.length ==0){
							$("#ui-id-1").append('<li class="ui-menu-item"><a href="javascript:;">Không tìm thấy sản phẩm</a></li>');
						}else{

							response( $.ui.autocomplete.filter(
									data, extractLast( request.term ) ) );
						}

					}
				});
			},
			focus: function() {
				// prevent value inserted on focus
				return false;
			},
			select: function( event, ui ) {
				var terms = split( this.value );
				// remove the current input
				terms.pop();
				// add the selected item
				terms.push( ui.item.value );
				// add placeholder to get the comma-and-space at the end
				terms.push( "" );
				this.value = terms.join( ", " );
				return false;
			}
		});


	});
</script>

<script type="text/javascript">

	$(function() {
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}

		$( ".auto_complete_tag" )
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
					url: "<?php echo $this->Html->url(array('controller'=>'products', 'action'=>'ajaxLoadTag'))?>",
					dataType: "json",
					data: {
						featureClass: "P",
						style: "full",
						maxRows: 12,
						name_startsWith: extractLast( request.term )
					},
					success: function( data ) {
						$("#ui-id-1").html('');
						if(data.length ==0){
							$("#ui-id-1").append('<li class="ui-menu-item"><a href="javascript:;">Không tìm thấy sản phẩm</a></li>');
						}else{

							response( $.ui.autocomplete.filter(
									data, extractLast( request.term ) ) );
						}

					}
				});
			},
			focus: function() {
				// prevent value inserted on focus
				return false;
			},
			select: function( event, ui ) {
				var terms = split( this.value );
				// remove the current input
				terms.pop();
				// add the selected item
				terms.push( ui.item.value );
				// add placeholder to get the comma-and-space at the end
				terms.push( "" );
				this.value = terms.join( ", " );
				return false;
			}
		});


	});
</script>
