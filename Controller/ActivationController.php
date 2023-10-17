<?php
App::uses('AppController', 'Controller');

class ActivationController extends AppController {
    public function kichhoatbaiviet() {


        $this->layout = false; // Không sử dụng layout
        $this->autoRender  = false;
        $this->loadModel('ActivePost');
        $this->loadModel('Post');
        if (isset($this->request->query['kichhoatbaiviet'])) {

            $number_of_posts = $this->Config->find('first', array(
                'conditions' => array('name' => 'number_of_posts'),
                'fields' => array('value'), 

            ));
            
//            $yesterday = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));

            $posts = $this->Post->find('all', array(
                'conditions' => array(
                    'Post.status' => 0,
//                    'Post.created >=' => $yesterday
                ),
                'fields' => array('Post.id', 'Post.status'),
                'limit' => $number_of_posts['Config']['value'],
                'order' => array('Post.id' => 'ASC'),
                'recursive' => -1
            ));
//var_dump($posts);exit();
            $success = 0;
            if ($posts) {
                foreach ($posts as $post) {
                    $this->Post->id = $post['Post']['id'];
                    echo $post['Post']['id'];
                    $post['Post']['status'] = 1;
                    $check = $this->Post->save($post);
                    if($check) $success++;
                    $this->Post->clear();
                }
            }
            $this->ActivePost->create();
            $this->ActivePost->set('so_luong', $success);
            $this->ActivePost->set('active_post', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y')));
            $this->ActivePost->set('check', true);
            $this->ActivePost->save();

            $p_con_lai = $this->ActivePost->find('first', array(
                'fields' => 'so_luong',
                'conditions' => array('check' => 0),
                'order' => array('id' => 'desc'),
            ));

            $so_luong=(int)$p_con_lai['ActivePost']['so_luong'] - (int)$number_of_posts['Config']['value'];
            if ($so_luong <= 0 ){
                $so_luong =0;
            }
//            var_dump($p_con_lai);exit();
            $this->ActivePost->create();
            $this->ActivePost->set('so_luong', $so_luong);
            $this->ActivePost->set('active_post', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y')));
            $this->ActivePost->set('check', false);
            $this->ActivePost->save();

            echo 'Số lượng bài viết kích hoạt'.$success.'<br>';
            echo 'Số lượng bài viết không thành công'.(count($posts)-$success);


        } else {
            throw new NotFoundException(__('Trang này không tồn tại',true));
        }
    }
}
