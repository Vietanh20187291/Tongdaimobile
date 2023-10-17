<!-- start videos/view.ctp -->
<?php $item_video = $a_video_c['Video']?>
<article class="box_content read">
	<header class="title">
		<div class="title_right">
			<div class="title_center">
				<h1><span><?php echo $item_video['name']?></span></h1>
			</div> <!--  end .title_center -->
		</div> <!--  end .title_right -->
	</header> <!--  end .title -->

	<div class="des">
		<div class="video_play">
			<iframe width="560" height="315" src="http://www.youtube.com/embed/<?php echo $item_video['youtube'] ?>?rel=0" allowfullscreen></iframe>
		</div> <!--  end .video_play -->

		<?php echo $item_video['description'] ?>

		<?php if(!empty($oneweb_media['video']['rate'])){?>
		<div class="rate">
				<span><?php echo __('Đánh giá',true)?>: </span>
				<?php echo $this->element('frontend/c_rate',array(
																'item_id'=>$item_video['id'],
																'model'=>'Video',
																'star_rate'=>$item_video['star_rate'],
																'star_rate_count'=>$item_video['star_rate_count']
															));
				?>
		</div>
		<?php }?>

		<?php if(!empty($oneweb_web['social'])) echo $this->element('frontend/c_social')?>

		<?php if(!empty($a_other_videos_c)){?>
		<section class="related">
			<header><span class="title"><?php echo __('Liên quan',true)?></span></header>
			<?php echo $this->element('frontend/c_video',array('data'=>$a_other_videos_c))?>
		</section><!--  end .related -->
		<div class="clear"></div>
		<?php }?>

		<?php if(!empty($oneweb_media['video']['comment'])) echo $this->element('frontend/c_comment',array(
																								'item_id'=>$item_video['id'],
																								'model'=>'Video'
																							))
		?>

		<?php if(!empty($oneweb_media['video']['comment_face'])) echo $this->element('frontend/c_comment_face',array(
																								'url'=>$this->request->url,
																								'width'=>742
																						));
		?>
		<?php if(!empty($oneweb_media['video']['comment_google'])) echo $this->element('frontend/c_comment_google',array(
																								'url'=>$this->request->url,
																								'width'=>742
																						));
		?>
	</div> <!--  end .des -->

	<div class="top"></div>
	<div class="bottom"></div>
</article>
<!-- end videos/view.ctp -->