<div style="padding-top: 50px">
    <p>Hệ thống đang có <?php echo $counts ?> bài viết. Với mỗi request 10k bài viết. Bạn chọn số bài tương ứng từ thấp đến cao để cập nhật</p>
    <?php
    echo $this->Form->create('Pages',array('type'=>'file','url'=>array('action'=>'ActionAddUrl'),'inputDefaults'=>array('label'=>false,'div'=>false)));
    ?>
    <tr>
        <th><?php echo $this->Form->label('page_num',__('Chọn page',true))?><span class="im">*</span></th>
        <td>
            <?php
            echo $this->Form->input('page_num',array('type'=>'select','options'=>$numbers,'empty'=>__('Chọn page',true),'class'=>'medium','required'=>true));
            ?>
        </td>
    </tr>
    <?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?>
    <?php echo $this->Form->end();?>
</div>

