<div id="column_right">
  <!-- tab -->
  <div id="action_top">
    <ul class="tabs">
        <li><a href="#tab1">Lấy mã nhúng</a></li>
      </ul> <!-- end .tabs -->

      <ul class="action_top_2">
        <li><?php echo $this->Html->link('&nbsp;',(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('title'=>__('Thoát',true),'class'=>'exit','escape'=>false))?></li>
      </ul> <!-- end .action_top_2 -->
  </div> <!--  end #action_top -->

  <div id="content">
    <div class="tab_container">
      <div id="tab1" class="tab_content">
        <table class="add column1">
          <tr>
            <td>
              <label for="registv"><b><?php echo __('Form đăng ký tư vấn',true) ?></b></label>
              <?php echo $this->Form->input('registv', array('type'=>'textarea', 'value'=>'<iframe src="'.$this->Html->url('/', true).'frm-dang-ky-tu-van.html'.'" width="320" height="425" frameborder="0" marginheight="0" marginwidth="0">Đang tải…</iframe>', 'readOnly'=>true, 'rows'=>10, 'cols'=>80, 'class'=>'form-control', 'label'=>false))?>
            </td>
            <td>
              <label for="registv"><b><?php echo __('Form nhận quà tặng',true) ?></b></label>
              <?php echo $this->Form->input('registv', array('type'=>'textarea', 'value'=>'<iframe src="'.$this->Html->url('/', true).'frm-nhan-qua-tang.html'.'" width="320" height="425" frameborder="0" marginheight="0" marginwidth="0">Đang tải…</iframe>', 'readOnly'=>true, 'rows'=>10, 'cols'=>80, 'class'=>'form-control', 'label'=>false))?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="registv"><b><?php echo __('Form đăng ký tham gia sự kiện',true) ?></b></label>
              <?php echo $this->Form->input('registv', array('type'=>'textarea', 'value'=>'<iframe src="'.$this->Html->url('/', true).'frm-tham-gia-su-kien.html'.'" width="320" height="425" frameborder="0" marginheight="0" marginwidth="0">Đang tải…</iframe>', 'readOnly'=>true, 'rows'=>10, 'cols'=>80, 'class'=>'form-control', 'label'=>false))?>
            </td>
            <td></td>
          </tr>
        </table>
      </div> <!-- end #tab1 -->

      <ul class="submit">
        <li><?php echo $this->Html->link(__('Thoát',true),(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('class'=>'exit'))?></li>
      </ul> <!-- end .submit -->

    </div> <!-- end .tab_container -->

  </div> <!--  end #content -->
</div> <!--  end #column_right -->