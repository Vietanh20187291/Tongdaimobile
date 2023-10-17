<div style="padding-top: 50px">
    <?php
    echo $this->Form->create('Posts',array('type'=>'file','url'=>array('action'=>'ActionImport'),'inputDefaults'=>array('label'=>false,'div'=>false)));
    echo $this->Form->input('path_file',array('type'=>'file'));
    ?>
    <tr>
        <th><?php echo $this->Form->label('post_category_id',__('Danh mục',true))?><span class="im">*</span></th>
        <td>
            <?php
            echo $this->Form->input('post_category_id',array('type'=>'select','options'=>$a_categories_c,'empty'=>__('Chọn danh mục',true),'class'=>'medium','required'=>true));
            ?>
        </td>
    </tr>
    <?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?>
    <?php echo $this->Form->end();?>
</div>
