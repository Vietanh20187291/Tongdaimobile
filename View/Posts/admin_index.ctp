<?php if(!empty($_GET['keyword'])) echo $this->Html->script('admin/highlight');?>
<?php
$error_list = $this->Session->read('error_list');
$count_import = $this->Session->read('count_import');
$count_replace = $this->Session->read('count_replace');
$count_error = $this->Session->read('count_error');
$count_add_link = $this->Session->read('count_add_link');
//var_dump($this->Session->read('error_list'));exit;
?>
<style>
	/* Thiết Lập Modal Background */
	.modal {
		display: none; /* Ẩn Mặc Định */
		position: fixed;
		z-index: 1;
		padding-top: 100px; /* Vị trí của modal */
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		overflow: auto; /* thiết lập scroll khi cần thiết */
		background-color: rgb(0,0,0);
		background-color: rgba(0,0,0,0.4);
	}
	#myBtn {
		background-color: #4CAF50; /* Green */
		border: none;
		color: white;
		padding: 15px 32px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 16px;
		margin: 4px 2px;
		cursor: pointer;
		-webkit-transition-duration: 0.4s; /* Safari */
		transition-duration: 0.4s;
		box-shadow: 0 1px 1px rgba(0,0,0,0.12),
		0 2px 2px rgba(0,0,0,0.12),
		0 4px 4px rgba(0,0,0,0.12),
		0 8px 8px rgba(0,0,0,0.12),
		0 16px 16px rgba(0,0,0,0.12);
		outline: none;
	}

	/* Nội Dung Modal */
	.modal-content {
		background-color: #fefefe;
		margin: auto;
		padding: 20px;
		border: 1px solid #888;
		width: 50%;
		box-shadow: 0 1px 1px rgba(0,0,0,0.12),
		0 2px 2px rgba(0,0,0,0.12),
		0 4px 4px rgba(0,0,0,0.12),
		0 8px 8px rgba(0,0,0,0.12),
		0 16px 16px rgba(0,0,0,0.12);
		border-radius: 25px;
	}

	/* Nút Đóng Modal */
	.close {
		color: #aaaaaa;
		float: right;
		font-size: 28px;
		font-weight: bold;
	}

	.close:hover,
	.close:focus {
		color: #000;
		text-decoration: none;
		cursor: pointer;
	}
</style>
<script type="text/javascript">
	//submit action top
	$(document).ready(function(){
		$("#action_1").find('li').click(function(){
			var c = confirm("<?php echo __('Bạn có chắc chắn muốn thực hiện hành động này',true)?>?");
			if(c==true){
				val = $(this).attr('class');
				$("#action").val(val);
				document.form.submit();
			}
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

	//Xóa bài viết
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
					alert('<?php echo __('Có lỗi, vui lòng thử lại',true) ?>');
				}
				$("#loading").hide();
			}
		});
	}

	//Sắp xếp lại bài viết
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
		echo $this->Form->create('Post',array('url'=>array('action'=>'index'),'type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('position',array('type'=>'hidden','value'=>(!empty($_GET['position']))?$_GET['position']:''))?>
		<ul class="search">
			<li><?php echo $this->Form->input('category_id',array('type'=>'select','options'=>$a_post_categories_c,'value'=>(empty($_GET['keyword'])?'':(!empty($_GET['category_id'])?$_GET['category_id']:'')),'empty'=>__('Chọn danh mục',true),'class'=>'medium'))?></li>
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tìm kiếm',true),'class'=>'larger auto_complete_search','onblur'=>'if (this.value==""){ this.value="'.__('Tìm kiếm',true).'";}','onfocus'=>'if (this.value=="'.__('Tìm kiếm',true).'") { this.value=""; }','id'=>'keyword'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('bài viết',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>

		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->

	<?php
	echo $this->Form->create('Post',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
	echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<?php if(!empty($a_list_children_c)){	//Danh sách danh mục sản phẩm trực tiếp?>
			<?php if(empty($_GET['view'])) echo $this->Html->link('...','javascript:;',array('title'=>__('Danh mục'),'onclick'=>'viewListCategory()','class'=>'view_list'))?>
			<ul class="list_category<?php if(!empty($_GET['view'])) echo ' show'?>">
				<?php foreach($a_list_children_c as $val){
					$item_cate = $val['PostCategory'];
					$a_counter = unserialize($item_cate['counter']);
					$view_post1 = array('action'=>'index','?'=>array('category_id'=>$item_cate['id']));
					$view_post2 = $view_post1;
					if(!empty($_GET['view'])) $view_post2['?']['view'] = 2;
					echo $this->Html->tag('li',
						$this->Html->tag('span','&nbsp;',array('class'=>'act folder'.(!$item_cate['status']?' unactive':''),'escape'=>false))
						.$this->Html->link($this->Text->truncate($item_cate['name'],30),$view_post2,array('title'=>$item_cate['name'],'class'=>'tooltip','escape'=>false))
						.' <span class="counter">[ '.$this->Html->link(number_format($a_counter['cate']),array('controller'=>'post_categories','action'=>'index','?'=>array('parent_id'=>$item_cate['id'])),array('title'=>number_format($a_counter['cate']).' '.__('danh mục con',true),'class'=>'tooltip'))
						.' - '.$this->Html->link(number_format($a_counter['item']),$view_post1,array('title'=>number_format($a_counter['item']).' '.__('sản phẩm',true),'class'=>'tooltip'))
						.' ]</span>'
					);
				}?>
			</ul> <!--  end .list_category -->
		<?php }?>

		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th class="small center"><?php echo __('Ảnh',true)?></th>
				<th><?php echo $this->Paginator->sort('name',__('Tên',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('view',__('Lượt xem',true))?></th>
				<th class="small center"><?php echo __('Người đăng',true)?></th>
				<th class="small center"><?php echo $this->Paginator->sort('PostCategory.name',__('Danh mục',true))?></th>
				<th class="small center"><?php echo __('Sắp xếp',true)?></th>
				<th class="small center"><?php echo __('Hiển thị',true)?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('created',__('Ngày tạo',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_posts_c as $val){

				$item_post = $val['Post'];
				$item_user = $val['User'];
				$item_category = $val['PostCategory'];
				$comment = number_format(count($val['Comment']));
				$comment_active = 0;
				for($i=0;$i<$comment;$i++) if($val['Comment'][$i]['status']) $comment_active++;

				$url_edit = array('controller'=>'posts','action'=>'edit',$item_post['id'],'?'=>array('url'=>$current_url_c));
				$url_view = array('controller'=>'posts','action'=>'index','lang'=>$item_post['lang'],'position'=>$item_category['position']);
				$tmp = explode(',', $item_category['path']);
				for($i=0;$i<count($tmp);$i++){
					$url_view['slug'.$i]=$tmp[$i];
				}
				$url_view['slug'.count($tmp)] = $item_post['slug'];
				$url_view['ext']='html';
				$url_view['admin'] = false;

				if(!empty($item_post['image'])) $img = '/timthumb.php?src='.$this->Html->url('/webroot/img/images/posts/').$item_post['image'];

				$img_small = $img."&h=40&w=40&zc=2";
				$img_larger = $img."&h=300&w=300&zc=2";
				?>
				<tr id="<?php echo 'item_'.$item_post['id']?>">
					<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_post['id']?>" /></td>
					<td class="center"><?php
						if ($item_post['user_id'] == 14){
							echo $this->Html->link($this->Html->image($item_post['image'],array('alt'=>$item_post['name'],'style'=>'width: 100px;height: 100px;')),$img_larger,array('title'=>$item_post['name'],'class'=>'preview','target'=>'_blank','escape'=>false));
						}else{
							echo $this->Html->link($this->Html->image($img_small,array('alt'=>$item_post['name'],'style'=>'width: 100px;height: 100px;')),$img_larger,array('title'=>$item_post['name'],'class'=>'preview','target'=>'_blank','escape'=>false));
						}
						?></td>
					<td><?php echo $this->Html->link($this->Text->truncate($item_post['name'],100,array('extact'=>false)),$url_edit,array('title'=>$item_post['name'],'class'=>'tooltip name'))?>
						<div class="view-comment">
							<?php
							echo '<p class="view">'.$this->Html->link(__('Lượt xem',true).': '.number_format($item_post['view'],0,',','.'),'javascript:;',array('title'=>__('Có',true).' '.number_format($item_post['view'],0,',','.').' '.__('lượt xem',true),'class'=>'tooltip')).'</p>';
							if($oneweb_post['comment']){
								if($comment>0){
									if($comment_active<$comment) $cl_c = ' red';
									else $cl_c = '';

									echo $this->Html->link(__('Bình luận',true).': '.number_format($comment,0,',','.'),'javascript:;',array('title'=>__('Có',true).' '.number_format($comment_active,0,',','.').' '.__('bình luận được kích hoạt trên tổng số',true).' '.number_format($comment,0,',','.').' '.__('bình luận',true),'onclick'=>"comment({$item_post['id']},'Post');",'class'=>'tooltip'.$cl_c));
								}else
									echo $this->Html->link(__('Bình luận',true).': '.number_format($comment,0,',','.'),'javascript:;',array('title'=>__('Chưa có bình luận nào',true),'onclick'=>"comment({$item_post['id']},'Post');",'class'=>'comment tooltip'));
							}
							?>
						</div>
					</td>
					<td class="cate center"><?php echo '<p class="view">'.$this->Html->link(__('',true).number_format($item_post['view'],0,',','.'),'javascript:;',array('title'=>__('Có',true).' '.number_format($item_post['view'],0,',','.').' '.__('lượt xem',true),'class'=>'tooltip')).'</p>'; ?></td>
					<td class="cate center">
						<?php
						echo (!empty($item_user))?$item_user['name']:'';
						?>
					</td>
					<td class="cate">
						<?php
						echo (!empty($item_category['name']))?$this->Html->link($this->Text->truncate($item_category['name'],20,array('exact'=>false)),array('controller'=>'posts','action'=>'index','?'=>array('category_id'=>$item_category['id'])),array('title'=>$item_category['name'],'class'=>'tooltip'.(!$item_category['status']?' unactive':''))):'<span class="error">**&nbsp;ERROR&nbsp;**</span>';

						//Danh mục hiển thị khác
						if(!empty($item_post['category_other'])){
							$a_category_other = array_filter(explode('-', $item_post['category_other']));
							echo '<div>';
							foreach($a_category_other as $val2){
								if($val2!=$item_category['id'] && !empty($a_post_categories_c[$val2])){
									$item = array_filter(explode('_', $a_post_categories_c[$val2]));
									sort($item);
									echo $this->Html->link($this->Text->truncate($item[0],15,array('exact'=>false)),array('controller'=>'posts','action'=>'index','?'=>array('category_id'=>$val2)),array('title'=>$item[0],'class'=>'tooltip')).', ';
								}
							}
							echo '</div>';
						}
						?>
					</td>
					<td class="center">
						<?php
						if(!empty($_GET['position'])){
							$sort = $item_post['pos_'.$_GET['position']];
							$field_sort = 'pos_'.$_GET['position'];
						}else{
							$sort = $item_post['sort'];
							$field_sort = 'sort';
						}
						echo $this->Form->input('sort',array('class'=>'small','value'=>$sort,'onchange'=>"changeSort(this.value,'$field_sort',{$item_post['id']})"));
						?>
					</td>
					<td class="center">
						<div class="display">
							<ul>
								<?php
								$count_pos = 0;
								foreach($oneweb_post['display'] as $key2=>$val2){
									if(!empty($item_post['pos_'.$key2])) $count_pos++;
									?>
									<li class="pos_<?php echo $key2?>">
										<?php
										echo $this->Html->link('&nbsp;','javascript:;',array('onclick'=>"changeStatus('pos_$key2',{$item_post['id']})",'class'=>'act '.((empty($item_post['pos_'.$key2]))?'unactive':'active'),'escape'=>false));
										echo $this->Html->link(__($val2,true),array('controller'=>'posts','action'=>'index','?'=>array('position'=>$key2)),array('title'=>__($val2,true),'class'=>'tooltip'));
										?>
									</li>
								<?php }?>
							</ul>
							<a href="javascript:;" class="act display"><?php echo $count_pos ?></a>
						</div> <!-- end .display -->
					</td>
					<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('status',{$item_post['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_post['status']==1)?'active':'unactive')));?></td>
					<td class="center">
						<?php
						echo $this->Html->tag('p',date('d/m/Y',$item_post['created']),array('class'=>'date'));
						echo $this->Html->tag('p',date('H:i:s',$item_post['created']),array('class'=>'time'));
						?>
					</td>
					<td class="center action">
						<?php
						echo $this->Html->link('&nbsp;',$url_view,array('title'=>__('Xem',true),'class'=>'act view','escape'=>false,'target'=>'_blank'));
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:trashItem({$item_post['id']});",array('title'=>__('Thùng rác',true),'class'=>'act trash','escape'=>false))
						?>
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
						url: "<?php echo $this->Html->url(array('controller'=>'posts', 'action'=>'ajaxLoadPost'))?>",
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
<!-- Modal Cho Website -->
<div style="<?php if ($error_list != null || $count_import != null || $count_replace != null || $count_error != null)  echo 'display: block' ?>" id="ModalReport" class="modal">

	<!-- Nội Dung Modal -->
	<div class="modal-content">
		<span  class="cl-pu-report close">&times;</span>
		<div>
			<h1>Thông tin import</h1>
			<br>
		</div>
		<p>Số bài import thành công:<?php echo $count_import?> </p>
		<p>Số bài thay nội dung: <?php echo $count_replace ?> </p>
		<p>Số bài import thất bại: <?php echo $count_error ?></p>
		<?php foreach($error_list as $item){ ?>
			<p><?php echo $item?></p>
		<?php }
		?>
	</div>

</div>

<div style="<?php if ($count_add_link != null || $count_error != null)  echo 'display: block' ?>" id="ModalAddLink" class="modal">

	<!-- Nội Dung Modal -->
	<div class="modal-content">
		<span  class="cl-pu-report close">&times;</span>
		<div>
			<h1>Thông tin add link sitemap</h1>
			<br>
		</div>
		<p>Số bài add thành công:<?php echo $count_add_link?> </p>
		<p>Số bài add thất bại: <?php echo $count_error ?></p>
		<?php foreach($error_list as $item){ ?>
			<p><?php echo $item?></p>
		<?php }
		?>
	</div>

</div>
<script>
	$(document).on('click', ".cl-pu-report, #over", function() {
		$('#ModalReport').css('display','none');
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('action'=>'ajaxDeleteSession'))?>',
		})
	});
</script>

<script>
	$(document).on('click', ".cl-pu-report, #over", function() {
		$('#ModalAddLink').css('display','none');
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('action'=>'ajaxDeleteSession'))?>',
		})
	});
</script>