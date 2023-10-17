<div id="show_post_related" >
<div class="row fix-safari">
    <div class="member_exps col-xs-12">
        <h3><span class="title title_text primary-color text-uppercase font-bold"><?php echo __('Bài viết liên quan') ?></span></h3>
        <div class="row auto-clear fix-safari">

            <?php
            if(!empty($data)){
                //Kich thước ảnh thumbnail
                $w = 332;
                $h = 265;
                // $full_size = $oneweb['size']['post'];
                // $h = intval($w*$full_size[1]/$full_size[0]);
                // print_r($oneweb);die();
                $count_item = count($data);
                    // for($j=0;$j<12;$j++){
                        // print_r($data);die();
                        foreach($data as $key => $value){
                        $item_post = $value['Post'];
                        $item_cate = $value['PostCategory'];
                        $url = array('controller'=>'posts','action'=>'index','lang'=>$item_post['lang'],'position'=>$item_cate['position']);

                        $tmp = explode(',', $item_cate['path']);
                        for($i=0;$i<count($tmp);$i++){
                            $url['slug'.$i]=$tmp[$i];
                        }
                       $url['slug'.count($tmp)] = $item_post['slug'];
                        $url['ext']='html';
                        $link_attr = array('title'=>$item_post['meta_title'],'target'=>$item_post['target'],'class'=>'name');
                        if($item_post['rel']!='dofollow') $link_attr['rel'] = $item_post['rel'];
                        $link_img_attr = array_merge($link_attr,array('escape'=>false));
                        $link_img_attr['class']='';
                        $link_more_attr['title'] = __('Read more',true);
                        $link_more_attr['class'] = 'readmore float_right';
                    ?>

                    <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 m-b-15">
                        <div class="image">
                            <?php
                            if ($item_post['user_id']!=14) {
                                echo $this->Html->link($this->OnewebVn->thumb('posts/' . $item_post['image'], array('alt' => $item_post['meta_title'], 'class' => 'img-responsive', 'width' => $w, 'height' => $h,'loading'=>'lazy')), $url, $link_img_attr);
                            }else {
                                echo $this->Html->link($this->html->image($item_post['image'], array('alt' => $item_post['meta_title'], 'class' => 'img-responsive','style'=>'height: 202px', 'width' => $w, 'height' => $h,'loading'=>'lazy')), $url, $link_img_attr);
                            }

                                ?>
                        </div>
                        <div style="margin-top:10px" class="name font-bold text-center m-t-15">
                            <?php
                            echo $this->Html->link($this->Text->truncate($item_post['name'],60,array('exact'=>false)),$url,$link_attr);
                            ?>
                        </div>
                    </div>

                    <?php
                    }}
            ?>
        </div>
    </div>
</div>
</div>
