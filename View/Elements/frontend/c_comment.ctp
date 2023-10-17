<script type="text/javascript">
	$("document").ready(function(){
		getComment(1);
	})
	function getComment(page){
		$.ajax({
			type: 'post',
			url: '<?php echo $this->Html->url(array('controller'=>'comments','action'=>'ajaxComment','lang'=>$lang))?>',
			data:'id=<?php echo $item_id ?>&page='+page+'&model=<?php echo $model?>',
			beforeSend:function(){
				if(page!=1) $("#message_top").show();
			},
			success: function(result){
				$("#message_top").hide();
				$("#loading_comment").html(result);
			}
		})
	}

	//Thêm comment
	function addComment(){
		data = $("#form_comment").serialize();
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('controller'=>'comments','action'=>'ajaxAddComment','lang'=>$lang))?>',
			data:data,
			dataType:'json',
			beforeSend: function(){
				$("#message_top").show();
			},
			success:function(result){
				$("#message_top").hide();
				if(result.error){
					if(result.empty!=''){
						alert(result.empty)
					}else alert('<?php echo __('Có lỗi, bạn vui lòng thử lại',true)?>')
				}else{
					alert('<?php echo __('Cảm ơn bạn đã cho ý kiến',true)?>');
					$('#form_comment')[0].reset();
				};
                gtag('event', 'conversion', {
                    'send_to': 'AW-663378581/QvgjCPrZxPsCEJW1qbwC',
                });
				//Load lai captcha
				javascript:document.images.captcha.src='<?php echo $this->Html->url(array('controller'=>'comments','action'=>'captchaImage','lang'=>$lang));?>?' + Math.round(Math.random(0)*1000)+1;
				$("#CommentCaptcha").val('');

			}
		})
	}
</script>
<div class="clear"></div>
<section>
	    <?php if($model == 'Product'){ ?>
        <script type="text/javascript">
            $("document").ready(function(){
                getRateComment();
            })
            function getRateComment(){
                product_id = <?php echo $item_id ?>;
                $.ajax({
                    type:'post',
                    data: 'product_id='+product_id+'&model=Product',
                    cache: true,
                    url:'<?php echo $this->Html->url(array('controller'=>'comments','action'=>'ajaxGetRateComment','lang'=>$lang))?>',
                    beforeSend:function(){
                        $("#message_top").fadeIn(0);
                    },
                    success:function(result){
                        $("#rate_comment").html(result);
                        $("#message_top").hide();
                    }
                });
            }
        </script>
        <div id="rate_comment">
            <?php echo $this->Html->image('ajax-loading.gif',array('alt'=>'Loading...'))?>
        </div>
    <?php } ?>
	<div id="comment">
        <span class="title3" id="write_comment"><?php if ($model == 'Product') echo __('Mời các bạn đánh giá và bình luận hoặc đặt câu hỏi về sản phẩm'); else echo __('Mời các bạn bình luận hoặc đặt câu hỏi');?></span>
		<?php if (isset($item_name)) { ?>
			<span class="product-name"><?php echo $item_name; ?></span>
		<?php } ?>

		<?php echo $this->Form->create('Comment',array('id'=>'form_comment','inputDefaults'=>array('label'=>false,'div'=>false)));
			echo $this->Form->input('model',array('type'=>'hidden','value'=>$model));
			echo $this->Form->input('item_id',array('type'=>'hidden','value'=>$item_id));
		?>
        <?php if ($model == 'Product'){ ?>
		<div class="form-group">
            <div class="pull-left"><b><?php echo __('Đánh giá sản phẩm') ?></b></div>
            <fieldset>
                <span class="star-cb-group">
                  <?php echo $this->Form->input('star',array('type'=>'radio','legend'=>false,'label'=>true,'options'=>array('5'=>'5','4'=>'4','3'=>'3','2'=>'2','1'=>'1'),'value'=>'5'));?>
                </span>
            </fieldset>
        </div>
    	<?php } ?>
        <div class="form-group">
			<?php echo $this->Form->input('description',array('type'=>'textarea','class'=>'form-control', 'placeholder' => 'Viết bình luận của bạn (Vui lòng gõ tiếng Việt có dấu)'));?>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-5">
				<div class="form-group">
					<?php echo $this->Form->input('name',array('class'=>'form-control', 'placeholder' => 'Họ và tên *'));?>
				</div>
			</div>
		    <div class="col-xs-12 col-sm-3">
				<div class="form-group">
					<?php echo $this->Form->input('phone',array('class'=>'form-control', 'placeholder' => 'Số điện thoại *'));?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="form-group">
					<?php echo $this->Form->button(__('Gửi câu hỏi'),array('class'=>'btn btn-default','onclick'=>'addComment(); return false'));?>
				</div>
			</div>
		</div>
		<?php echo $this->Form->end();?>

		<div id="loading_comment"><?php echo $this->Html->image('ajax-loading.gif',array('alt'=>'Loading...'))?></div>
	</div>
</section>
