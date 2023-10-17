<!-- start posts/list.ctp -->
<article class="row">
<div class="container mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar">
<?php echo $this->element('frontend/s_post_category'); // Có thể là khai báo sử dụng sidebar ?>
            </div>
        </div>
        <div class="col-md-9">
            <div class="content">
	<div class="col-xs-12">
		<div class="bg_white clearfix">
		<?php
			$flag = true;			// Ko có danh mục con và mô tả trên đầu trang
			if(!empty($a_category_c['description']) || !empty($a_child_direct_categories)) $flag=false;			//Có danh mục con hoặc mô tả trên đầu trang
		?>

		<?php if(!empty($oneweb_post['category_banner']) && !empty($a_category_c['banner'])){
			$banner_size = $oneweb_post['size']['category_banner'];
		?>
		<div class="banner">
			<?php
				$banner = $this->Html->image('images/post_categories/'.$a_category_c['banner'],array('alt'=>$a_category_c['name'],'width'=>$banner_size[0],'height'=>$banner_size[1]));
				if(!empty($a_category_c['banner_link'])) $banner = $this->Html->link($banner,$a_category_c['banner_link'],array('title'=>$a_category_c['name'],'target'=>'_blank','rel'=>'nofollow','escape'=>false));
				echo $banner;
			?>
		</div>
		<?php }?>

		<?php if(!$flag){?>
		<div class="box_info_page">
			<header class="title">
				<h1><span class="title font-weight-bold">Tin Tức</span></h1>
			</header>

			<div class="des">
				<?php echo $a_category_c['description']?>
			</div>
		</div>
		<?php }?>

		<?php if(!empty($a_posts_c)){?>
		<div class="box_content list_post">
			<?php if($flag){?>
					<header class="title">
						<h1>Tin Tức >></h1>
					</header>
			<?php }?>

			<div class="row">
				<?php
				echo $this->element('frontend/c_post',array(
														'data'		=> $a_posts_c,
														'class' 	=> 'post col-xs-12',
														'limit' 	=> 400,
														'datetime' 	=> false,
														'w'			=> 400,
														'zc'		=> 1
													));
				?>

				<div class="clear"></div>
                <div class="paginator">
                    <?php
                    $url = array('controller' => 'pages', 'action' => 'home', 'lang' => $lang);
                    $this->Paginator->options(array(
                        'url' => $url
                    ));

                    echo $this->Paginator->counter(array('format' => '<span class="page">%page%/%pages%</span>'));
                    echo $this->Paginator->first('<<', array('separator' => false, 'title' => __('Trang đầu', true)));
                    echo $this->Paginator->numbers(array('separator' => false, 'modulus' => 7, 'class' => 'number'));
                    echo $this->Paginator->last('>>', array('separator' => false, 'title' => __('Trang cuối', true)));
                    ?>
                </div>
			</div>
		</div>
		<?php }?>
		</div>
		</div>
		</div>
	</div>
</article>
<!-- end posts/list.ctp -->