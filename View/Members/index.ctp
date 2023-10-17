<article class="box_content">
        <div class="title">
			<div class="title_right">
				<div class="title_center">
				<header><h1><span><?php echo __('Thông tin thành viên', true);?></span></h1></header>
				</div> <!--  end .title_center -->
			</div> <!--  end .title_right -->
		</div>
		<div class="des">
			<?php 
				if(!empty($a_member_c['Member']['name'])){
			?>
	        <p class="mtm strong">Xin chào <?php echo $a_member_c['Member']['name']; ?>  </p>
	        <?php }?>
	        <p class="mbm mts">Trong mục quản lý tài khoản, bạn có thể xem các hoạt động gần đây của bạn cũng như quản lý thông tin tài khoản. Chọn một link bên dưới để xem hay chỉnh sửa thông tin.</p>
	        <section class="box_member_register">
	            <div class="unit left">
	                <div class="unit_bottom">
	                    <h4 class="ui-borderBottom pbs fsml">Địa chỉ email</h4>
	                    <?php if(!empty($a_member_c['Member']['email'])){?>
	                    <p class="ptm">
	                        <?php echo $a_member_c['Member']['email']; ?> - <a href="#">Thay đổi địa chỉ email</a><br>
	                    </p>
	                    <?php }?>
	                    <p class="mtm">
	                        <?php echo $this->Html->link(__('Thay đổi mật khẩu', true), array('controller'=>'members', 'action'=>'changePassword', 'lang'=>$lang), array('title'=>'Change Password'));?>
	                    </p>
	                    <?php echo $this->Html->link(__('Chỉnh sửa', true), array('controller'=>'members', 'action'=>'editAccount', 'lang'=>$lang), array('title'=>'edit', 'class'=>'edit'));?>
	                </div>
	            </div>
	             <div class="unit">
	                <div class="unit_bottom">
	                    <h4 class="ui-borderBottom pbs fsml">Địa chỉ liên hệ</h4>
	                    <p><?php echo $a_member_c['Member']['name']; ?> <br><span id="citycode"><?php echo $a_member_c['Member']['address'];?></span><br></p>
	                    <?php echo $this->Html->link(__('Chỉnh sửa', true), array('controller'=>'members', 'action'=>'changeAddress', 'lang'=>$lang), array('title'=>'edit', 'class'=>'edit'));?>
	                </div>
	            </div>
	        </section>
	        <div class="clear"></div>
	        <!-- 
	        <section class="box_member_register">
	            
	            <div class="unit left">
	                <div class="unit_bottom">
	                    <h4 class="ui-borderBottom pbs fsml">Bản tin </h4>
	                    <p class="ptm">Bạn đang nhận các email thông báo như sau: Marketing E-mail notifications</p>
	                    <?php //echo $this->Html->link(__('Chỉnh sửa', true), 'javascript:;', array('title'=>'edit', 'class'=>'edit'));?>
	                </div>
	            </div>
	        </section>
        	 -->
		</div><!-- End .des -->
	
</article> <!--  end .box_content -->