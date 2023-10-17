<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = array();

    public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('admin_addUrl_new');
		$this->Auth->allow('admin_NewSiteMap');
		// $this->Auth->allow('admin_confirmResetPassword');
	}

    /**
     * @Description : Trang chu
     *
     * @throws 	: NotFoundException
     * @param 	: int id
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    public function home(){
        $this->set('class','home');
 		$this->set('column_left',true);		//Thiết lập loại bỏ cột phải

        $oneweb_product = Configure::read('Product');
        $oneweb_post = Configure::read('Post');
        $oneweb_banner =Configure::read('Banner');
        $lang = $this->params['lang'];
			$a_post_categories = Cache::read('post_categories_sidebar_'.$lang,'oneweb');

				if(!$a_post_categories) {
					$a_post_categories = $this->Post->PostCategory->find('all',array(
						'contain'=>array('ChildPostCategory'=>array('conditions'=>array('ChildPostCategory.status'=>1, 'ChildPostCategory.trash'=>0))),
						'conditions'=>array('PostCategory.lang'=>$lang,'PostCategory.status'=>1,'PostCategory.trash'=>0, 'PostCategory.parent_id'=>NULL),
						'fields'=>array('slug','meta_title','name','lang','id','path','rel','parent_id','target','link','position'),
						'order'=>array('PostCategory.lft'=>'asc'),
					));
					Cache::write('post_categories_sidebar_'.$lang,$a_post_categories,'oneweb');
				}

				$this->set('a_post_categories_s',$a_post_categories);
        /*** SẢN PHẨM ****/
        if(!empty($oneweb_product['enable'])){

     /*** BÀI VIẾT ****/
 // Lấy tất cả các post category
    $a_category = $this->Post->PostCategory->find('all', array(
        'conditions' => array(
            'status' => 1,
            'trash' => 0,
            'lang' => $lang
        ),
        'fields' => array('id', 'name', 'slug', 'lang', 'path', 'meta_title', 'rel', 'target', 'link', 'image', 'status', 'position'),
        'order' => array('lft' => 'asc', 'name' => 'asc'),
        'recursive' => -1
    ));

    // Kiểm tra xem có dữ liệu post category hay không
    if (empty($a_category)) {
        throw new NotFoundException(__('Không có post category nào.', true));
    }

    // Truyền dữ liệu post category vào view
    $this->set('a_category_post', $a_category);

// $a_posts = $this->Post->find('all', array(
//     'conditions' => array('Post.status' => 1, 'Post.trash' => 0),
//     'contain' => array('PostCategory'),
//     'fields' => array('Post.id', 'Post.user_id', 'Post.name', 'Post.meta_description', 'Post.lang', 'Post.slug', 'Post.meta_title', 'Post.rel', 'Post.target', 'Post.public', 'Post.image', 'Post.summary', 'Post.created','Post.summary','Post.post_category_id', 'PostCategory.slug', 'PostCategory.path', 'PostCategory.status', 'PostCategory.position'),
//     'order' => array('Post.sort' => 'asc', 'Post.created' => 'desc', 'Post.name' => 'asc'),
//     'page' => (!empty($a_params['page']) ? $a_params['page'] : '1'),
//     'limit' => 10,
// ));

$a_category = $this->Post->PostCategory->findById(1);


 //Danh sách tất cả các bài viết
$this->paginate = array(
    'conditions' => array('Post.status' => 1, 'Post.trash' => 0),
    'contain' => array('PostCategory'),
    'fields' => array('Post.id', 'Post.user_id', 'Post.name', 'Post.meta_description', 'Post.lang', 'Post.slug', 'Post.meta_title', 'Post.rel', 'Post.target', 'Post.public', 'Post.image', 'Post.summary', 'Post.created','Post.summary','Post.post_category_id', 'PostCategory.slug', 'PostCategory.path', 'PostCategory.status', 'PostCategory.position'),
    'order' => array('Post.sort' => 'asc', 'Post.created' => 'desc', 'Post.name' => 'asc'),
    'page' => (!empty($a_params['page']) ? $a_params['page'] : '1'),
    'limit' => 10,
);

    $a_posts = $this->paginate('Post');
    $this->set('a_posts_c', $a_posts);

  $this->set('a_posts', $a_posts);

        $this->loadModel('Product');
        $this->loadModel('ProductCategory');

        // Lấy các danh mục được chọn ra trang chủ
        $a_category_feature = Cache::read('a_category_feature_vip_'.$lang,'oneweb');


        $this->set('a_category_feature', $a_category_feature);

        $this->productViewedShow();//hiển thị sản phẩm đã xem
        //Đọc cấu hình, giới thiệu trang chủ
        $a_configs = $this->_getConfig('home');
        $this->set('a_configs_c',$a_configs);


        //quảng cáo dưới slide
        if(!empty($oneweb_banner['display'][11])) $this->set('a_partner_11',$this->_banner('pos_11'));
        //SEO
        if (! empty($a_configs['meta_title'])) $this->set('title_for_layout',$a_configs['meta_title']);
        if (! empty($a_configs['meta_keyword'])) $this->set('meta_keyword_for_layout',$a_configs['meta_keyword']);
        if (! empty($a_configs['meta_description'])) $this->set('meta_description_for_layout',$a_configs['meta_description']);
        if (! empty($a_configs['meta_robots'])) $this->set('meta_robots_for_layout',$a_configs['meta_robots']);

        //Canonical
        $a_canonical = array('controller'=>'pages','action'=>'home','lang'=>$lang);
        $this->set('a_canonical',$a_canonical);


    }
}




    /**
     * @Description : Đánh giá
     *
     * @throws 	: NotFoundException
     * @param 	: int id
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    public function ajaxStarRate(){
        if(empty($_POST['id']) || empty($_POST['val']) || empty($_POST['model'])) throw new NotFoundException(__('Trang này không tồn tại',true));
        $this->layout = false;
        $this->autoRender = false;
        $model = $_POST['model'];

        $this->loadModel($model);
        $a_item = $this->$model->read('star_rate,star_rate_count',$_POST['id']);
        $a_item = $a_item[$model];

        $star_rate_new = round(($a_item['star_rate']*$a_item['star_rate_count']+$_POST['val'])/($a_item['star_rate_count']+1),1);

        $this->$model->id = $_POST['id'];
        $this->$model->set(array(
            'star_rate'=>$star_rate_new,
            'star_rate_count'=>$a_item['star_rate_count']+1,
        ));
        if($this->$model->save()){
            $a_ids = array($_POST['id']);				//Những id đã rate;
            if($this->Session->check('Rate.'.$model)) $a_ids = array_merge($a_ids,$this->Session->read('Rate.'.$model));
            $a_ids = array_unique($a_ids);
            $this->Session->write('Rate.'.$model,$a_ids);
        }

        return round($star_rate_new*100/5);
    }

    /**
     * @Description : Chọn đơn vị tiền hiển thị
     *
     * @throws 	: NotFoundException
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    public function currency(){
        $lang = $this->params['lang'];
        $id = $this->request->data['Currency']['currency'];
        $this->Session->write("Currency_$lang.id",$id);
        $this->redirect($this->referer());
    }


    /**
     * @Description : Thống kê truy cập
     *
     * @throws 	: NotFoundException
     * @param 	: int id
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    public function ajaxCounter(){
        $this->layout = 'ajax';

        $this->loadModel('Counter');
        $this->loadModel('CounterValue');
        $this->loadModel('CounterIp');

        // ip-protection in seconds
        $counter_expire = 600;

        $ignore = false;
        $time_now = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
        //Lay thong tin
        $res = $this->CounterValue->find('first');

        // fill when empty
        if (empty($res)){
            $data['CounterValue']['id'] = 1;
            $data['CounterValue']['day_id']='" . date("z") . "';
            $data['CounterValue']['day_value']='1';
            $data['CounterValue']['yesterday_id']='" . (date("z")-1) . "';
            $data['CounterValue']['yesterday_value']='0';
            $data['CounterValue']['week_id']='" . date("W") . "';
            $data['CounterValue']['week_value']='1';
            $data['CounterValue']['month_id']='" . date("n") . "';
            $data['CounterValue']['month_value']='1';
            $data['CounterValue']['year_id']='" . date("Y") . "';
            $data['CounterValue']['year_value']='1';
            $data['CounterValue']['all_value']='1';
            $data['CounterValue']['record_date']=$time_now;
            $data['CounterValue']['record_value']='1';

            $this->CounterValue->create();
            $this->CounterValue->save($data);
            $ignore = true;
        }

        $row = $res['CounterValue'];

        $day_id = $row['day_id'];
        $day_value = $row['day_value'];
        $yesterday_id = $row['yesterday_id'];
        $yesterday_value = $row['yesterday_value'];
        $week_id = $row['week_id'];
        $week_value = $row['week_value'];
        $month_id = $row['month_id'];
        $month_value = $row['month_value'];
        $year_id = $row['year_id'];
        $year_value = $row['year_value'];
        $all_value = $row['all_value'];
        $record_date = $row['record_date'];
        $record_value = $row['record_value'];

        $counter_agent = (isset($_SERVER['HTTP_USER_AGENT'])) ? addslashes(trim($_SERVER['HTTP_USER_AGENT'])) : "";

        $counter_time = time();
        $counter_ip = trim(addslashes($_SERVER['REMOTE_ADDR']));

        // ignorore some bots
        if (substr_count($counter_agent, "bot") > 0)
            $ignore = true;

        // delete free ips
        if ($ignore == false)
        {
            $this->CounterIp->deleteAll("$time_now-visit > $counter_expire");
        }

        // check for entry
        if ($ignore == false){
            $res = $this->CounterIp->find('first',array('conditions'=>array('ip'=>$counter_ip)));

            if (empty($res)){
                // insert
                $data['CounterIp']['ip'] = $counter_ip;
                $data['CounterIp']['visit']=$time_now;
                $this->CounterIp->create();
                $this->CounterIp->save($data);
            }else{
                $ignore = true;
                $this->CounterIp->updateAll(array('visit'=>$time_now),array('ip'=>$counter_ip));
            }
        }

        // online?
        $online = $this->CounterIp->find('count');

        // add counter
        if ($ignore == false){
            // yesterday
            if ($day_id == (date("z")-1)){
                $yesterday_value = $day_value;
                $yesterday_id = (date("z")-1);
            }else{
                if ($yesterday_id != (date("z")-1)){
                    $yesterday_value = 0;
                    $yesterday_id = date("z")-1;
                }
            }

            // day
            if ($day_id == date("z")){
                $day_value++;
            }else{
                $day_value = 1;
                $day_id = date("z");
            }

            // week
            if ($week_id == date("W")) {
                $week_value++;
            }else{
                $week_value = 1;
                $week_id = date("W");
            }

            // month
            if ($month_id == date("n")){
                $month_value++;
            }else {
                $month_value = 1;
                $month_id = date("n");
            }

            // year
            if ($year_id == date("Y")){
                $year_value++;
            }else {
                $year_value = 1;
                $year_id = date("Y");
            }

            // all
            $all_value++;

            // neuer record?
            if ($day_value > $record_value){
                $record_value = $day_value;
                $record_date = date("Y-m-d H:i:s");
            }

            // speichern und aufräumen
            $data['CounterValue']['id']=1;
            $data['CounterValue']['day_id']=$day_id;
            $data['CounterValue']['day_value']=$day_value;
            $data['CounterValue']['yesterday_id']=$yesterday_id;
            $data['CounterValue']['yesterday_value']=$yesterday_value;
            $data['CounterValue']['week_id']=$week_id;
            $data['CounterValue']['week_value']=$week_value;
            $data['CounterValue']['month_id']=$month_id;
            $data['CounterValue']['month_value']=$month_value;
            $data['CounterValue']['year_id']=$year_id;
            $data['CounterValue']['year_value']=$year_value;
            $data['CounterValue']['all_value']=$all_value;
            $data['CounterValue']['record_date']=$record_date;
            $data['CounterValue']['record_value']=$record_value;
            $this->CounterValue->save($data);
        }

        $a_counters = array(
            'online'=>$online,
            'today'=>$day_value,
            'yesterday'=>$yesterday_value,
            'week'=>$week_value,
            'month'=>$month_value,
            'year'=>$year_value,
            'total'=>$all_value,
            'record'=>$record_value,
            'record_date'=>$record_date
        );
        $this->set('a_counters_c',$a_counters);
    }


    /********************************************************/
    /********************************************************/
    /********************** Admin ***************************/
    /********************************************************/
    /********************************************************/

    /**
     * @Description : Trang chủ (Liệt kê các tính năng, thống kê)
     *
     * @throws 	: NotFoundException
     * @param 	: int id
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    public function admin_index(){
        $oneweb_product = Configure::read('Product');
        $oneweb_post = Configure::read('Post');
        $oneweb_contact = Configure::read('Contact');
        $oneweb_media = Configure::read('Media');
        $oneweb_banner = Configure::read('Banner');
        $oneweb_web = Configure::read('Web');
        $oneweb_faq = Configure::read('Faq');
        $oneweb_support = Configure::read('Support');

        $lang = $this->Session->read('lang');

        $admin = $this->Auth->user();
        if ($admin['role'] == 'staff') $this->redirect(array('controller'=>'posts','action'=>'index','admin'=>true));

        // lấy thống kê hiện thị trong admin
        //$this->loadModel('Counter');
        $this->loadModel('CounterValue');
        $this->loadModel('CounterIp');
        $a_counters = $this->CounterValue->find('all',array(
            'recursive'=>-1
        ));

        $a_counter_ip = $this->CounterIp->find('count',array(
            'recursive'=>-1
        ));
        $this->set('a_counter_ip_c',$a_counter_ip);
        $this->set('a_counters_c',$a_counters);
        //Sản phẩm
        if(!empty($oneweb_product['enable'])){
            $this->loadModel('Product');
            $product_count = $this->Product->find('count',array(
                'conditions'=>array('lang'=>$lang,'trash'=>0),
                'recursive'=>-1
            ));
            $product_cate_count = $this->Product->ProductCategory->find('count',array(
                'conditions'=>array('lang'=>$lang,'trash'=>0),
                'recursive'=>-1
            ));
            $product_maker_count = $this->Product->ProductMaker->find('count',array(
                'conditions'=>array('lang'=>$lang,'trash'=>0),
                'recursive'=>-1
            ));

            $this->set('product_count_c',$product_count);
            $this->set('product_cate_count_c',$product_cate_count);
            $this->set('product_maker_count_c',$product_maker_count);

            if(!empty($oneweb_product['tax'])){
                $product_tax_count = $this->Product->ProductTax->find('count',array(
                    'conditions'=>array('lang'=>$lang),
                    'recursive'=>-1
                ));
                $this->set('product_tax_count_c',$product_tax_count);
            }

            if(!empty($oneweb_product['currency'])){
                $this->loadModel('Currency');
                $product_currency_count = $this->Currency->find('count');
                $this->set('product_currency_count_c',$product_currency_count);
            }

            //Thống kê đơn hàng
            if(!empty($oneweb_product['order'])){
                $this->loadModel('Order');
                $count_order =  $this->Order->find('count');
                $count_order_new =  $this->Order->find('count',array('conditions'=>array('view'=>0)));
                $this->set('count_order_c',$count_order);
                $this->set('count_order_new_c',$count_order_new);
            }
        }

        if($oneweb_contact['enable']){
            //Thống kê liên hệ
            $this->loadModel('Contact');
            $count_contact =  $this->Contact->find('count');
            $count_contact_new =  $this->Contact->find('count',array('conditions'=>array('view'=>0)));
            $this->set('count_contact_c',$count_contact);
            $this->set('count_contact_new_c',$count_contact_new);
        }

        //Trang thông tin
        $this->loadModel('Information');
        $this->Information->unbindModel(array('belongsTo'=>array('ParentInformation')));
        $this->Information->bindModel(array('hasMany'=>array(
            'ChildInformation' => array(
                'className' => 'Information',
                'foreignKey' => 'parent_id',
                'dependent' => true,
                'conditions' => array('trash'=>0),
                'fields' => array('id'),
            )
        )));
        $a_information = $this->Information->find('all',array(
            'conditions'=>array('Information.parent_id'=>null,'Information.lang'=>$lang,'Information.trash'=>0),
            'fields'=>array('Information.id','Information.name'),
            'order'=>'Information.sort asc',
            'limit'=>5,
            'recursive'=>1
        ));
        $this->set('a_information_c',$a_information);

        //Video
        if(!empty($oneweb_media['video']['enable'])){
            $this->loadModel('Video');
            $video_counter = $this->Video->find('count',array(
                'conditions'=>array('lang'=>$lang),
                'recursive'=>-1
            ));

            $this->set('video_counter_c',$video_counter);
        }

        //Document
        if(!empty($oneweb_media['document']['enable'])){
            $this->loadModel('Document');
            $document_counter = $this->Document->find('count',array(
                'conditions'=>array('lang'=>$lang,'trash'=>0),
                'recursive'=>-1
            ));
            $this->set('document_counter_c',$document_counter);
        }

        //Gallery
        if(!empty($oneweb_media['gallery']['enable'])){
            $this->loadModel('Gallery');
            $gallery_counter = $this->Gallery->find('count',array(
                'conditions'=>array('lang'=>$lang,'trash'=>0),
                'recursive'=>-1
            ));
            $this->set('gallery_counter_c',$gallery_counter);
        }

        //Banner
        if(!empty($oneweb_banner['enable'])){
            $this->loadModel('Banner');
            $banner_counter = $this->Banner->find('count',array(
                'conditions'=>array('lang'=>$lang,'trash'=>0),
                'recursive'=>-1
            ));
            $this->set('banner_counter_c',$banner_counter);
        }

        //Bình luận
        if(!empty($oneweb_web['comment'])){
            $this->loadModel('Comment');
            //Sản phẩm
            if(!empty($oneweb_product['comment'])){
                $comment_product_count = $this->Comment->find('count',array(
                    'conditions'=>array('lang'=>$lang,'model'=>'Product'),
                    'recursive'=>-1
                ));
                $this->set('comment_product_count_c',$comment_product_count);
            }

            //Bài viết
            if(!empty($oneweb_post['comment'])){
                $comment_post_count = $this->Comment->find('count',array(
                    'conditions'=>array('lang'=>$lang,'model'=>'Post'),
                    'recursive'=>-1
                ));
                $this->set('comment_post_count_c',$comment_post_count);
            }

            //Hình ảnh
            if(!empty($oneweb_media['gallery']['comment'])){
                $comment_gallery_count = $this->Comment->find('count',array(
                    'conditions'=>array('lang'=>$lang,'model'=>'Gallery'),
                    'recursive'=>-1
                ));
                $this->set('comment_gallery_count_c',$comment_gallery_count);
            }

            //Video
            if(!empty($oneweb_media['video']['comment'])){
                $comment_video_count = $this->Comment->find('count',array(
                    'conditions'=>array('lang'=>$lang,'model'=>'Video'),
                    'recursive'=>-1
                ));
                $this->set('comment_video_count_c',$comment_video_count);
            }

            //Tất cả
            $comment_count = $this->Comment->find('count',array(
                'conditions'=>array('lang'=>$lang),
                'recursive'=>-1
            ));
            $this->set('comment_count_c',$comment_count);
        }

        //Faq
        if(!empty($oneweb_faq['enable'])){
            $this->loadModel('Faq');
            $faq_count = $this->Faq->find('count',array(
                'conditions'=>array('lang'=>$lang,'trash'=>0),
                'recursive'=>-1
            ));
            $faq_cate_count = $this->Faq->FaqCategory->find('count',array(
                'conditions'=>array('lang'=>$lang,'trash'=>0),
                'recursive'=>-1
            ));

            $this->set('faq_count_c',$faq_count);
            $this->set('faq_cate_count_c',$faq_cate_count);
        }

        //Hỗ trợ trực tuyến
        if(!empty($oneweb_support['enable'])){
            $this->loadModel('Support');
            $support_count = $this->Support->find('count');
            $this->set('support_count_c',$support_count);
        }
    }


    /**
     * @Description : Kiểm tra xem có đơn hàng hoặc liên hệ mới
     *
     * @throws 	: NotFoundException
     * @return 	: string
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    public function admin_ajaxCheck(){
        $this->layout = false;
        $this->autoRender = false;
        $oneweb_product = Configure::read('Product');
        $oneweb_contact = Configure::read('Contact');
        $oneweb_web = Configure::read('Web');

        $result = array();
        $result['product'] = 0;
        $result['post'] = 0;
        $result['comment'] = 0;

        //Kiểm tra đơn hàng mới
        if(!empty($oneweb_product['enable']) && !empty($oneweb_product['order'])){
            $this->loadModel('Order');
            //Đơn hàng chưa đọc
            $result['product'] = $this->Order->find('count',array('conditions'=>array('view'=>0)));

            //Đơn hàng mới - thông báo âm thanh
            $count_order_new = $this->Order->find('count',array('conditions'=>array('alarm'=>0)));
        }else $count_order_new = 0;

        //Kiểm tra liên hệ mới
        if(!empty($oneweb_contact['enable'])){
            $this->loadModel('Contact');
            //Liên hệ chưa đọc
            $result['post'] = $this->Contact->find('count',array('conditions'=>array('view'=>0)));

            //Liên hệ mới - Thông báo âm thanh
            $count_contact_new = $this->Contact->find('count',array('conditions'=>array('alarm'=>0)));
        }else $count_contact_new = 0;

        //Kiểm tra comment mới
        if(!empty($oneweb_web)){
            $this->loadModel('Comment');
            $result['comment'] = $this->Comment->find('count',array('conditions'=>array('view'=>0),'recursive'=>-1));
        }

        if($count_order_new>0 || $count_contact_new>0) $result['sound'] = true;
        else $result['sound'] = false;

        if($this->Session->check('modified')) $result['modified'] = true;		//Kiểm tra xem có cần phải xóa cache ko
        else  $result['modified'] = false;

        return json_encode($result);
    }


    /**
     * @Description : Thay đổi ngôn ngữ
     *
     * @throws 	: NotFoundException
     * @param 	: int id
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    public function admin_changeLanguage(){
        $url = array('action'=>'index');
        if($this->request->is('post')){
            $data = $this->request->data['Page'];
            $this->Session->write('lang',$data['lang']);
        }
        $this->redirect($url);
    }

    /**
     * @Description : Xóa cache
     *
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    public function admin_delCache(){
        $oneweb_product = Configure::read('Product');
        $oneweb_post = Configure::read('Post');
        $oneweb_faq = Configure::read('Faq');
        $oneweb_media = Configure::read('Media');
        $oneweb_sitemap = Configure::read('Sitemap.xml');

        $this->loadModel('Information');
        $this->loadModel('Banner');
        $this->loadModel('Product');
        $this->loadModel('Post');
        $this->loadModel('Gallery');
        $this->loadModel('Video');
        $this->loadModel('Document');
        $this->loadModel('Faq');

        //Thống kê
        if(!empty($oneweb_product['enable'])) $this->counterProduct();
        if(!empty($oneweb_product['maker'])) $this->counterProductMaker();
        if(!empty($oneweb_post['enable'])) $this->counterPost();
        if(!empty($oneweb_media['gallery']['enable'])) $this->counterGallery();
        if(!empty($oneweb_media['video']['enable'])) $this->counterVideo();
        if(!empty($oneweb_media['document']['enable'])) $this->counterDocument();
        if(!empty($oneweb_faq['enable'])) $this->counterFaq();

        //Tạo lại Sitemap XML
        if(!empty($oneweb_sitemap)) $this->sitemapXml();

        //Ktra và xóa các bản ghi bị lỗi (lỗi khi đã xóa ở thùng rác nhưng chưa được xóa ở bảng của nó)
        //Chú ý: mới làm xóa csdl, chưa xóa được ảnh - Tạm thời vậy đã
        $this->repair();

        //Xóa cache
        $this->_deleteCache();

        $this->Session->delete('modified');

        $this->Session->setFlash('<span>'.__('Cache đã được xóa',true).'</span>','default',array('class'=>'success'));
        $this->redirect($this->referer());
    }


    /**
     * @Description : Ktra và xóa các bản ghi bị lỗi (lỗi khi đã xóa ở thùng rác nhưng chưa được xóa ở bảng của nó)
     *
     * @throws 	: NotFoundException
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    private function repair(){
        $oneweb_product = Configure::read('Product');
        $oneweb_post = Configure::read('Post');
        $oneweb_information = Configure::read('Information');
        $oneweb_media = Configure::read('Media');
        $oneweb_banner = Configure::read('Banner');
        $oneweb_faq = Configure::read('Faq');

        //**** Sản phẩm ****/
        if(!empty($oneweb_product['enable'])){
            //Danh mục
            $a_product_categories = $this->Product->ProductCategory->find('all',array(
                'conditions'=>array('trash'=>1),
                'fields'=>'id',
                'recursive'=>-1
            ));

            foreach($a_product_categories as $val){
                $item = $val['ProductCategory'];
                if(!$this->checkTrash('ProductCategory', $item['id'])) $this->Product->ProductCategory->delete($item['id']);
            }

            //Hãng sản xuất
            $a_product_makers = $this->Product->ProductMaker->find('all',array(
                'conditions'=>array('trash'=>1),
                'fields'=>'id',
                'recursive'=>-1
            ));

            foreach($a_product_makers as $val){
                $item = $val['ProductMaker'];
                if(!$this->checkTrash('ProductMaker', $item['id'])) $this->Product->ProductMaker->delete($item['id']);
            }

            //Sản phẩm
            $a_products = $this->Product->find('all',array(
                'conditions'=>array('trash'=>1),
                'fields'=>'id',
                'recursive'=>-1
            ));

            foreach($a_products as $val){
                $item = $val['Product'];
                if(!$this->checkTrash('Product', $item['id'])) $this->Product->delete($item['id']);
            }
        }

        //**** Bài viết ****/
        if(!empty($oneweb_post['enable'])){
            //Danh mục
            $a_post_categories = $this->Post->PostCategory->find('all',array(
                'conditions'=>array('trash'=>1),
                'fields'=>'id',
                'recursive'=>-1
            ));

            foreach($a_post_categories as $val){
                $item = $val['PostCategory'];
                if(!$this->checkTrash('PostCategory', $item['id'])) $this->Post->PostCategory->delete($item['id']);
            }

            //Bài viết
            $a_posts = $this->Post->find('all',array(
                'conditions'=>array('trash'=>1),
                'fields'=>'id',
                'recursive'=>-1
            ));

            foreach($a_posts as $val){
                $item = $val['Post'];
                if(!$this->checkTrash('Post', $item['id'])) $this->Post->delete($item['id']);
            }
        }

        //**** Thông tin ****/
        if(!empty($oneweb_information['enable'])){
            $a_information = $this->Information->find('all',array(
                'conditions'=>array('trash'=>1),
                'fields'=>'id',
                'recursive'=>-1
            ));

            foreach($a_information as $val){
                $item = $val['Information'];
                if(!$this->checkTrash('Information', $item['id'])) $this->Information->delete($item['id']);
            }
        }


        //**** Hình ảnh ****/
        if(!empty($oneweb_media['gallery']['enable'])){
            //Danh mục
            $a_gallery_categories = $this->Gallery->GalleryCategory->find('all',array(
                'conditions'=>array('trash'=>1),
                'fields'=>'id',
                'recursive'=>-1
            ));

            foreach($a_gallery_categories as $val){
                $item = $val['GalleryCategory'];
                if(!$this->checkTrash('GalleryCategory', $item['id'])) $this->Gallery->GalleryCategory->delete($item['id']);
            }

            //ảnh
            $a_galleries = $this->Gallery->find('all',array(
                'conditions'=>array('trash'=>1),
                'fields'=>'id',
                'recursive'=>-1
            ));

            foreach($a_galleries as $val){
                $item = $val['Gallery'];
                if(!$this->checkTrash('Gallery', $item['id'])) $this->Gallery->delete($item['id']);
            }
        }


        //**** Video ****/
        if(!empty($oneweb_media['video']['enable'])){
            //Danh mục
            $a_video_categories = $this->Video->VideoCategory->find('all',array(
                'conditions'=>array('trash'=>1),
                'fields'=>'id',
                'recursive'=>-1
            ));

            foreach($a_video_categories as $val){
                $item = $val['VideoCategory'];
                if(!$this->checkTrash('VideoCategory', $item['id'])) $this->Video->VideoCategory->delete($item['id']);
            }

            //ảnh
            $a_videos = $this->Video->find('all',array(
                'conditions'=>array('trash'=>1),
                'fields'=>'id',
                'recursive'=>-1
            ));

            foreach($a_videos as $val){
                $item = $val['Video'];
                if(!$this->checkTrash('Video', $item['id'])) $this->Video->delete($item['id']);
            }
        }


        //**** Document ****/
        if(!empty($oneweb_media['document']['enable'])){
            //Danh mục
            $a_document_categories = $this->Document->DocumentCategory->find('all',array(
                'conditions'=>array('trash'=>1),
                'fields'=>'id',
                'recursive'=>-1
            ));

            foreach($a_document_categories as $val){
                $item = $val['DocumentCategory'];
                if(!$this->checkTrash('DocumentCategory', $item['id'])) $this->Document->DocumentCategory->delete($item['id']);
            }

            //ảnh
            $a_documents = $this->Document->find('all',array(
                'conditions'=>array('trash'=>1),
                'fields'=>'id',
                'recursive'=>-1
            ));

            foreach($a_documents as $val){
                $item = $val['Document'];
                if(!$this->checkTrash('Document', $item['id'])) $this->Document->delete($item['id']);
            }
        }


        //**** Banner ****/
        if(!empty($oneweb_banner['enable'])){
            $a_banners = $this->Banner->find('all',array(
                'conditions'=>array('trash'=>1),
                'fields'=>'id',
                'recursive'=>-1
            ));

            foreach($a_banners as $val){
                $item = $val['Banner'];
                if(!$this->checkTrash('Banner', $item['id'])) $this->Banner->delete($item['id']);
            }
        }


        //**** FAQs ****/
        if(!empty($oneweb_faq['enable'])){
            //Danh mục
            $a_faq_categories = $this->Faq->FaqCategory->find('all',array(
                'conditions'=>array('trash'=>1),
                'fields'=>'id',
                'recursive'=>-1
            ));

            foreach($a_faq_categories as $val){
                $item = $val['FaqCategory'];
                if(!$this->checkTrash('FaqCategory', $item['id'])) $this->Faq->FaqCategory->delete($item['id']);
            }

            //faq
            $a_faqs = $this->Faq->find('all',array(
                'conditions'=>array('trash'=>1),
                'fields'=>'id',
                'recursive'=>-1
            ));

            foreach($a_faqs as $val){
                $item = $val['Faq'];
                if(!$this->checkTrash('Faq', $item['id'])) $this->Faq->delete($item['id']);
            }
        }
    }

    /**
     * @Description : Kiểm tra đối tượng đã bị xóa khỏi bảng trashes chưa
     *
     * @throws 	: NotFoundException
     * @param 	: string $model
     * @param	: int $item_id
     * @return 	: boolean
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    private function checkTrash($model,$item_id){
        $this->loadModel('Trash');
        $count = $this->Trash->find('count',array(
            'conditions'=>array('model'=>$model,'item_id'=>$item_id)
        ));
        return !empty($count)?true:false;
    }


    /**
     * @Description : Thống kê lại sản phẩm  -- Hàm này cần phải được chạy mỗi khi có sự thay đổi mới.
     *
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    private function counterProduct(){

        //Danh sách danh mục
        $a_categories = $this->Product->ProductCategory->find('all',array(
            'conditions'=>array('trash'=>0),
            'fields'=>array('id','status'),
            'recursive'=>-1
        ));

        foreach($a_categories as $val){
            $item = $val['ProductCategory'];

            $total = 0;								//Tổng danh mục
            $active = 0;							//Tổng danh mục được kích hoạt
            $pro_total = 0;							//Tổng sản phẩm
            $pro_active = 0;						//Tổng sản phẩm được kích hoạt
            $a_ids = array($item['id']);			//ID của danh mục hiện tại và các danh mục con của nó

            $a_children = $this->Product->ProductCategory->children($item['id'],false,'id,status,trash');

            foreach($a_children as $val2){
                $item2 = $val2['ProductCategory'];
                if(!$item2['trash']){
                    $total++;
                    if($item2['status']) $active++;
                    $a_ids[] = $item2['id'];
                }
            }

            $conditions = array('product_category_id'=>$a_ids);
            foreach($a_ids as $val2){
                $conditions[] = array('category_other like'=>'%-'.$val2.'-%');
            }

            $pro_total = $this->Product->find('count',array('conditions'=>array('trash'=>0,'or'=>$conditions),'recursive'=>-1));
            $pro_active = $this->Product->find('count',array('conditions'=>array('trash'=>0,'status'=>1,'or'=>$conditions),'recursive'=>-1));

            $a_counters = array(
                'cate'=>$total,
                'cate_active'=>$active,
                'item'=>$pro_total,
                'item_active'=>$pro_active
            );

            $this->Product->ProductCategory->id = $item['id'];
            $this->Product->ProductCategory->set('counter',serialize($a_counters));
            $this->Product->ProductCategory->save();
        }
    }

    /**
     * @Description : Thống kê sản phẩm ở product_maker
     *
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    private function counterProductMaker(){
        $a_categories = $this->Product->ProductMaker->find('all',array(
            'conditions'=>array('trash'=>0),
            'fields'=>array('id'),
            'recursive'=>-1
        ));

        foreach($a_categories as $val){
            $item = $val['ProductMaker'];

            $this->Product->recursive = -1;
            $total = $this->Product->find('count',array(
                'conditions'=>array('trash'=>0,'product_maker_id'=>$item['id']),
            ));

            $active = $this->Product->find('count',array(
                'conditions'=>array('trash'=>0,'status'=>1,'product_maker_id'=>$item['id'])
            ));

            $this->Product->ProductMaker->id = $item['id'];
            $this->Product->ProductMaker->set('counter',serialize(array('item'=>$total,'item_active'=>$active)));
            $this->Product->ProductMaker->save();
        }
    }

    /**
     * @Description : Thống kê lại bài viết  -- Hàm này cần phải được chạy mỗi khi có sự thay đổi mới.
     *
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    private function counterPost(){

        //Danh sách danh mục
        $a_categories = $this->Post->PostCategory->find('all',array(
            'conditions'=>array('trash'=>0),
            'fields'=>array('id','status'),
            'recursive'=>-1
        ));

        foreach($a_categories as $val){
            $item = $val['PostCategory'];

            $total = 0;								//Tổng danh mục
            $active = 0;							//Tổng danh mục được kích hoạt
            $post_total = 0;						//Tổng bài viết
            $post_active = 0;						//Tổng bài viết được kích hoạt
            $a_ids = array($item['id']);			//ID của danh mục hiện tại và các danh mục con của nó

            $a_children = $this->Post->PostCategory->children($item['id'],false,'id,status,trash');

            foreach($a_children as $val2){
                $item2 = $val2['PostCategory'];
                if(!$item2['trash']){
                    $total++;
                    if($item2['status']) $active++;
                    $a_ids[] = $item2['id'];
                }
            }

            $conditions = array('post_category_id'=>$a_ids);
            foreach($a_ids as $val2){
                $conditions[] = array('category_other like'=>'%-'.$val2.'-%');
            }

            $post_total = $this->Post->find('count',array('conditions'=>array('trash'=>0,'or'=>$conditions),'recursive'=>-1));
            $post_active = $this->Post->find('count',array('conditions'=>array('trash'=>0,'status'=>1,'or'=>$conditions),'recursive'=>-1));

            $a_counters = array(
                'cate'=>$total,
                'cate_active'=>$active,
                'item'=>$post_total,
                'item_active'=>$post_active
            );

            $this->Post->PostCategory->id = $item['id'];
            $this->Post->PostCategory->set('counter',serialize($a_counters));
            $this->Post->PostCategory->save();
        }
    }

    /**
     * @Description : Thống kê gallery
     *
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    private function counterGallery(){
        $a_categories = $this->Gallery->GalleryCategory->find('all',array(
            'conditions'=>array('trash'=>0),
            'fields'=>array('id'),
            'recursive'=>-1
        ));

        foreach($a_categories as $val){
            $item = $val['GalleryCategory'];

            $this->Gallery->recursive = -1;
            $total = $this->Gallery->find('count',array(
                'conditions'=>array('trash'=>0,'or'=>array(array('gallery_category_id'=>$item['id']),array('category_other like'=>'%-'.$item['id'].'-%'))),
            ));

            $active = $this->Gallery->find('count',array(
                'conditions'=>array('trash'=>0,'status'=>1,'or'=>array(array('gallery_category_id'=>$item['id']),array('category_other like'=>'%-'.$item['id'].'-%')))
            ));

            $this->Gallery->GalleryCategory->id = $item['id'];
            $this->Gallery->GalleryCategory->set('counter',serialize(array('item'=>$total,'item_active'=>$active)));
            $this->Gallery->GalleryCategory->save();
        }
    }

    /**
     * @Description : Thống kê video
     *
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    private function counterVideo(){
        $a_categories = $this->Video->VideoCategory->find('all',array(
            'conditions'=>array('trash'=>0),
            'fields'=>array('id'),
            'recursive'=>-1
        ));

        foreach($a_categories as $val){
            $item = $val['VideoCategory'];

            $this->Video->recursive = -1;
            $total = $this->Video->find('count',array(
                'conditions'=>array('trash'=>0,'or'=>array(array('video_category_id'=>$item['id']),array('category_other like'=>'%-'.$item['id'].'-%'))),
            ));

            $active = $this->Video->find('count',array(
                'conditions'=>array('trash'=>0,'status'=>1,'or'=>array(array('video_category_id'=>$item['id']),array('category_other like'=>'%-'.$item['id'].'-%')))
            ));

            $this->Video->VideoCategory->id = $item['id'];
            $this->Video->VideoCategory->set('counter',serialize(array('item'=>$total,'item_active'=>$active)));
            $this->Video->VideoCategory->save();
        }
    }

    /**
     * @Description : Thống kê document
     *
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    private function counterDocument(){
        $a_categories = $this->Document->DocumentCategory->find('all',array(
            'conditions'=>array('trash'=>0),
            'fields'=>array('id'),
            'recursive'=>-1
        ));

        foreach($a_categories as $val){
            $item = $val['DocumentCategory'];

            $this->Document->recursive = -1;
            $total = $this->Document->find('count',array(
                'conditions'=>array('trash'=>0,'or'=>array(array('document_category_id'=>$item['id']),array('category_other like'=>'%-'.$item['id'].'-%'))),
            ));

            $active = $this->Document->find('count',array(
                'conditions'=>array('trash'=>0,'status'=>1,'or'=>array(array('document_category_id'=>$item['id']),array('category_other like'=>'%-'.$item['id'].'-%')))
            ));

            $this->Document->DocumentCategory->id = $item['id'];
            $this->Document->DocumentCategory->set('counter',serialize(array('item'=>$total,'item_active'=>$active)));
            $this->Document->DocumentCategory->save();
        }
    }

    /**
     * @Description : Thống kê faq
     *
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    private function counterFaq(){
        $a_categories = $this->Faq->FaqCategory->find('all',array(
            'conditions'=>array('trash'=>0),
            'fields'=>array('id'),
            'recursive'=>-1
        ));

        foreach($a_categories as $val){
            $item = $val['FaqCategory'];

            $this->Faq->recursive = -1;
            $total = $this->Faq->find('count',array(
                'conditions'=>array('trash'=>0,'faq_category_id'=>$item['id']),
            ));

            $active = $this->Faq->find('count',array(
                'conditions'=>array('trash'=>0,'status'=>1,'faq_category_id'=>$item['id'])
            ));

            $this->Faq->FaqCategory->id = $item['id'];
            $this->Faq->FaqCategory->set('counter',serialize(array('item'=>$total,'item_active'=>$active)));
            $this->Faq->FaqCategory->save();
        }
    }


    /**
     * @Description : Tạo sitemap xml
     *
     * @throws 	: NotFoundException
     * @param 	: int id
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    private function sitemapXml(){
        $oneweb['language'] = Configure::read('Language');
        $oneweb['product'] = Configure::read('Product');
        $oneweb['post'] = Configure::read('Post');
        $oneweb['media'] = Configure::read('Media');
        $oneweb['faq'] = Configure::read('Faq');
        $oneweb['contact'] = Configure::read('Contact');

        $lastmod = date('Y-m-d');

        //Bài viết
        $a_posts_count = $this->Post->find('all',array(
            'conditions'=>array('Post.status'=>1,'Post.trash'=>0,'PostCategory.status'=>1,'PostCategory.trash'=>0),
            'fields'=>array('Post.slug','PostCategory.slug'),
            'contain'=>array('PostCategory.slug','PostCategory.path','PostCategory.position'),
        ));
        $count_page = ceil(Count($a_posts_count) /10000) ;
        $last_page = $count_page + 1 ;
        $offset = 1;
        foreach($oneweb['language'] as $key=>$val){
            // Khởi tạo xml

            $xmldoc = new DOMDocument("1.0","utf-8");
            $xmldoc->formatOutput = true;
            // Khởi tạo node gốc
            $root = $xmldoc->createElement("urlset");
            $urlset_attr = $xmldoc->createAttribute("xmlns");
            $urlset_attr->value = 'http://www.sitemaps.org/schemas/sitemap/0.9';

            $root->appendChild($urlset_attr);
            $xmldoc->appendChild($root);
            //*** Trang chu ****/
            $url_home = Router::url(array('controller' => 'pages','action'=>'home','lang'=>$key,'admin'=>false), true);
            $this->_xml($root,$xmldoc,$url_home,1.0,'always',$lastmod);

            //*** Trang thông tin ***/
            $a_information = $this->Information->find('all',array(
                    'conditions'=>array('status'=>1,'lang'=>$key,'trash'=>0),'recursive'=>-1,
                    'fields'=>array('slug','link','position','parent_id'))
            );

            foreach($a_information as $val2){
                $item = $val2['Information'];
                if(empty($item['link'])){
                    $url_information = array('controller'=>'information','action'=>'view','lang'=>$key,'position'=>$item['position'],'slug'=>$item['slug'],'admin'=>false);
                    if(!empty($item['parent_id'])) $url_information['ext']='html';

                    $url_information = Router::url($url_information,true);

                    $this->_xml($root, $xmldoc, $url_information,0.6,'monthly',$lastmod);
                }
            }

            //*** Trang sản phẩm ***/
            if(!empty($oneweb['product']['enable'])){
                //Danh mục
                $a_product_categories = $this->Product->ProductCategory->find('all',array(
                    'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$key),
                    'fields'=>array('slug','path','link'),
                    'recursive'=>-1
                ));
                foreach($a_product_categories as $val2){
                    $item = $val2['ProductCategory'];
                    if(empty($item['link'])){
                        $url_pro_category = array('controller'=>'products','action' => 'index','lang'=>$key,'admin'=>false);
                        $tmp = explode(',', $item['path']);
                        for($i=0;$i<count($tmp);$i++){
                            $url_pro_category = array_merge($url_pro_category,array('slug'.$i=>$tmp[$i]));
                        }
                        $url_pro_category = Router::url($url_pro_category,true);

                        $this->_xml($root, $xmldoc, $url_pro_category,0.3,'weekly',$lastmod);
                    }
                }

                //Hãng sản xuất
                if(!empty($oneweb['product']['maker'])){
                    $a_pro_makers = $this->Product->ProductMaker->find('all',array(
                        'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$key),
                        'fields'=>array('slug','link'),
                        'recursive'=>-1
                    ));

                    foreach($a_pro_makers as $val2){
                        $item = $val2['ProductMaker'];
                        $url_pro_maker = Router::url(array('controller'=>'products','action'=>'maker','lang'=>$key,'slug'=>$item['slug'],'admin'=>false),true);
                        $this->_xml($root, $xmldoc, $url_pro_maker,0.3,'weekly',$lastmod);
                    }
                }

                //Sản phẩm
                $a_products = $this->Product->find('all',array(
                    'conditions'=>array('Product.status'=>1,'Product.trash'=>0,'ProductCategory.status'=>1,'ProductCategory.trash'=>0),
                    'fields'=>array('Product.slug','ProductCategory.slug'),
                    'contain'=>array('ProductCategory.slug','ProductCategory.path')
                ));

                foreach($a_products as $val2){
                    $item_product = $val2['Product'];
                    $item_cate = $val2['ProductCategory'];

                    $url_product = array('controller'=>'products','action'=>'index','lang'=>$key);
                    $tmp = explode(',', $item_cate['path']);
                    for($j=0;$j<count($tmp);$j++){
                        $url_product['slug'.$j]=$tmp[$j];
                    }
                    $url_product['slug'.count($tmp)] = $item_product['slug'];
                    $url_product['ext']='html';
                    $url_product['admin'] = false;

                    $url_product = Router::url($url_product,true);
                    $this->_xml($root, $xmldoc, $url_product,0.6,'monthly',$lastmod);
                }
            }


            //*** Bài viết ***/
            if(!empty($oneweb['post']['enable'])){
                //Danh mục
                $a_post_categories = $this->Post->PostCategory->find('all',array(
                    'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$key),
                    'fields'=>array('slug','path','link','position'),
                    'recursive'=>-1
                ));
                foreach($a_post_categories as $val2){
                    $item = $val2['PostCategory'];
                    if(empty($item['link'])){
                        $url_post_category = array('controller'=>'posts','action' => 'index','position'=>$item['position'],'lang'=>$key,'admin'=>false);
                        $tmp = explode(',', $item['path']);
                        $url_post_category = array_merge($url_post_category,array('slug0'=>$tmp[0]));

                        if(count($tmp) > 1  ) {
                            $url_post_category = array_merge($url_post_category,array('slug1'=>$tmp[count($tmp)-1]));
                        }

                        $url_post_category = Router::url($url_post_category,true);

                        $this->_xml($root, $xmldoc, $url_post_category,0.3,'weekly',$lastmod);
                    }
                }
            }


            //*** Hình ảnh ***/
            if(!empty($oneweb['media']['gallery']['enable'])){
                //Danh mục gốc
                $this->_xml($root, $xmldoc, Router::url(array('controller'=>'galleries','action'=>'index','lang'=>$key,'admin'=>false),true),0.3,'weekly',$lastmod);

                //Danh mục
                $a_gallery_categories = $this->Gallery->GalleryCategory->find('all',array(
                    'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$key),
                    'fields'=>array('slug'),
                    'recursive'=>-1
                ));
                foreach($a_gallery_categories as $val2){
                    $item = $val2['GalleryCategory'];
                    $this->_xml($root, $xmldoc, Router::url(array('controller'=>'galleries','action'=>'index','lang'=>$key,'slug0'=>$item['slug'],'admin'=>false),true),0.3,'weekly',$lastmod);
                }

                //Ảnh
                $a_galleries = $this->Gallery->find('all',array(
                    'conditions'=>array('Gallery.lang'=>$key,'Gallery.status'=>1,'Gallery.trash'=>0,'GalleryCategory.status'=>1,'GalleryCategory.trash'=>0),
                    'fields'=>array('Gallery.id','Gallery.slug','GalleryCategory.slug','GalleryCategory.status'),
                    'recursive'=>0
                ));

                foreach($a_galleries as $val2){
                    $item_gallery = $val2['Gallery'];
                    $item_gallery_category = $val2['GalleryCategory'];

                    $this->_xml($root, $xmldoc, Router::url(array('controller'=>'galleries','action'=>'index','lang'=>$key,'slug0'=>$item_gallery_category['slug'],'slug1'=>$item_gallery['slug'],'ext'=>'html','admin'=>false),true),0.6,'monthly',$lastmod);
                }
            }


            //*** Video ***/
            if(!empty($oneweb['media']['video']['enable'])){
                //Danh mục gốc
                $this->_xml($root, $xmldoc, Router::url(array('controller'=>'videos','action'=>'index','lang'=>$key,'admin'=>false),true),0.3,'weekly',$lastmod);

                //Danh mục
                $a_video_categories = $this->Video->VideoCategory->find('all',array(
                    'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$key),
                    'fields'=>array('slug'),
                    'recursive'=>-1
                ));
                foreach($a_video_categories as $val2){
                    $item = $val2['VideoCategory'];
                    $this->_xml($root, $xmldoc, Router::url(array('controller'=>'videos','action'=>'index','lang'=>$key,'slug0'=>$item['slug'],'admin'=>false),true),0.3,'weekly',$lastmod);
                }

                //Video
                $a_videos = $this->Video->find('all',array(
                    'conditions'=>array('Video.lang'=>$key,'Video.status'=>1,'Video.trash'=>0,'VideoCategory.status'=>1,'VideoCategory.trash'=>0),
                    'fields'=>array('Video.id','Video.slug','VideoCategory.slug','VideoCategory.status'),
                    'recursive'=>0
                ));

                foreach($a_videos as $val2){
                    $item_video = $val2['Video'];
                    $item_video_category = $val2['VideoCategory'];

                    $this->_xml($root, $xmldoc, Router::url(array('controller'=>'videos','action'=>'index','lang'=>$key,'slug0'=>$item_video_category['slug'],'slug1'=>$item_video['slug'],'ext'=>'html','admin'=>false),true),0.6,'monthly',$lastmod);
                }
            }


            //*** Document ***/
            if(!empty($oneweb['media']['document']['enable'])){
                //Danh mục gốc
                $this->_xml($root, $xmldoc, Router::url(array('controller'=>'documents','action'=>'index','lang'=>$key,'admin'=>false),true),0.3,'weekly',$lastmod);

                //Danh mục
                $a_document_categories = $this->Document->DocumentCategory->find('all',array(
                    'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$key),
                    'fields'=>array('slug'),
                    'recursive'=>-1
                ));
                foreach($a_document_categories as $val2){
                    $item = $val2['DocumentCategory'];
                    $this->_xml($root, $xmldoc, Router::url(array('controller'=>'documents','action'=>'view','lang'=>$key,'slug_cate'=>$item['slug'],'admin'=>false),true),0.6,'monthly',$lastmod);
                }
            }


            //*** Hỏi đáp ***/
            if(!empty($oneweb['faq']['enable'])) $this->_xml($root, $xmldoc, Router::url(array('controller'=>'faqs','action'=>'view','lang'=>$key,'admin'=>false),true),0.6,'monthly',$lastmod);

            //*** Liên hệ ***/
            if(!empty($oneweb['contact']['enable'])) $this->_xml($root, $xmldoc, Router::url(array('controller'=>'contacts','action'=>'index','lang'=>$key,'ext'=>'html','admin'=>false),true),0.3,'weekly',$lastmod);
            $xmldoc->save("../webroot/sitemap_1".".xml");

            for($i = 2; $i <= $count_page+1; $i++){
                if($i > 2){
                    $offset +=10000;
                }
                $a_posts = $this->Post->find('all',array(
                    'conditions'=>array('Post.status'=>1,'Post.trash'=>0,'PostCategory.status'=>1,'PostCategory.trash'=>0),
                    'fields'=>array('Post.slug','PostCategory.slug','Post.link_sitemap'),
                    'contain'=>array('PostCategory.slug','PostCategory.path','PostCategory.position'),
                    'order'=>array('Post.id'=>'asc'),
                    'limit'=>10000,
                    'offset' => $offset
                ));
                $xmldoc = new DOMDocument("1.0","utf-8");
                $xmldoc->formatOutput = true;
                // Khởi tạo node gốc
                $root = $xmldoc->createElement("urlset");
                $urlset_attr = $xmldoc->createAttribute("xmlns");
                $urlset_attr->value = 'http://www.sitemaps.org/schemas/sitemap/0.9';

                $root->appendChild($urlset_attr);
                $xmldoc->appendChild($root);
                foreach($a_posts as $val2){
//                    $item_post = $val2['Post'];
//                    $item_cate = $val2['PostCategory'];
//
//                    $url_post = array('controller'=>'posts','action'=>'index','lang'=>$key,'position'=>$item_cate['position']);
//                    $tmp = explode(',', $item_cate['path']);
//                    for($j=0;$j<count($tmp);$j++){
//                        $url_post['slug'.$j]=$tmp[$j];
//                        break;
//                    }
//                    $url_post['slug1'] = $item_post['slug'];
//                    $url_post['ext']='html';
//                    $url_post['admin'] = false;

                    $url_post = $val2['Post']['link_sitemap'];
                    if (!empty($url_post)){
                        $this->_xml($root, $xmldoc, $url_post,0.6,'monthly',$lastmod);
                    }
                }
                $xmldoc->save("../webroot/sitemap_$i.xml");
            }

        }

        $xmldoc = new DOMDocument("1.0","utf-8");
        $xmldoc->formatOutput = true;
        // Khởi tạo node gốc
        $root = $xmldoc->createElement("sitemapindex");
        $urlset_attr = $xmldoc->createAttribute("xmlns");
        $urlset_attr->value = 'http://www.sitemaps.org/schemas/sitemap/0.9';

        $root->appendChild($urlset_attr);
        $xmldoc->appendChild($root);
        for ($i = 1;$i <= $count_page+1;$i++){
            $this->_xml_sitemapindex($root, $xmldoc,'https://memart.vn/sitemap_'.$i.'.xml',0.6,'monthly',$lastmod);
        }
        $xmldoc->save("../webroot/all-sitemap.xml");
    }

    /**
     * @Description : Tạo xml
     *
     * @throws 	: NotFoundException
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    function _xml($root,$xmldoc,$url,$pri=null,$cha=null,$las=null){
        $elem = $xmldoc->createElement("url");
        $loc = $xmldoc->createElement("loc");
        $loc->appendChild($xmldoc->createTextNode($url));
        $elem->appendChild($loc);
        // priority
        if(!empty($pri)){
            $priority = $xmldoc->createElement("priority");
            $priority->appendChild($xmldoc->createTextNode($pri));
            $elem->appendChild($priority);
        }
        //frequency
        if(!empty($cha)){
            $frequency = $xmldoc->createElement("changefreq");
            $frequency->appendChild($xmldoc->createTextNode($cha));
            $elem->appendChild($frequency);
        }
        //lastmod
        if(!empty($las)){
            $lastmod = $xmldoc->createElement("lastmod");
            $lastmod->appendChild($xmldoc->createTextNode($las));
            $elem->appendChild($lastmod);
        }

        //kết thúc việc tạo node url
        $root->appendChild($elem);
    }

    /**
     * @Description : ThêmURL
     *
     * @throws 	: NotFoundException
     * @Author 	: Vu Hoai Nam
     */
    public function admin_addUrl(){
        $this->loadModel('Post');
        $counts = $this->Post->find('count', array(
            'conditions'=>array('Post.status'=>1,'Post.trash'=>0,'PostCategory.status'=>1,'PostCategory.trash'=>0),
            'fields' => 'Post.id',
            'contain'=>array('PostCategory.slug','PostCategory.path','PostCategory.position'),
        ));
        $page_size = ceil($counts /10000);
        $this->set('page_size',$page_size);
        $this->set('counts',$counts);
        $values = range(2, $page_size+1, 1);
        $array = array_combine($values, $values);
        $this->set('numbers',$array);
    }

    public function admin_ActionAddUrl(){
        $page = $this->request->data['Pages']['page_num'];
        $key = 'vi';
        $offset = 0;
        if(($page-2)>=1){
            $offset = $page*10000;
        }
        $this->loadModel('Post');
        $a_posts = $this->Post->find('all',array(
            'conditions'=>array('Post.status'=>1,'Post.trash'=>0,'PostCategory.status'=>1,'PostCategory.trash'=>0),
            'fields'=>array('Post.slug','PostCategory.slug','id'),
            'contain'=>array('PostCategory.slug','PostCategory.path','PostCategory.position'),
            'order'=>array('Post.id'=>'asc'),
            'limit'=>10000,
            'offset' => $offset
        ));
        foreach($a_posts as $val2){
            $item_post = $val2['Post'];
            $item_cate = $val2['PostCategory'];

            $url_post = array('controller'=>'posts','action'=>'index','lang'=>$key,'position'=>$item_cate['position']);
            $tmp = explode(',', $item_cate['path']);
            for($j=0;$j<count($tmp);$j++){
                $url_post['slug'.$j]=$tmp[$j];
                break;
            }
            $url_post['slug1'] = $item_post['slug'];
            $url_post['ext']='html';
            $url_post['admin'] = false;

            $url_post = Router::url($url_post,true);
            $this->Post->id = $item_post['id'];
            $this->Post->set('link_sitemap',$url_post);
            $this->Post->save();
        }
        $this->Session->setFlash('<span>'.__('Thành công',true).'</span>','default',array('class'=>'success'));
        $this->redirect($this->referer());
    }

    /**
     * @Description : ThêmURL
     *
     * @throws 	: NotFoundException
     * @Author 	: Vu Hoai Nam
     */
    public function admin_addUrl_new(){
        $this->layout = false; // Không sử dụng layout
        $this->autoRender = false;
        $count_add_link = 0;
        $count_error = 0;
        $list_error = [];
        $key = 'vi';
        $this->loadModel('Post');
        $a_posts = $this->Post->find('all',array(
            'conditions'=>array('Post.trash'=>0,'PostCategory.status'=>1,'PostCategory.trash'=>0,'Post.link_sitemap IS NULL'),
            'fields'=>array('Post.slug','PostCategory.slug','id'),
            'contain'=>array('PostCategory.slug','PostCategory.path','PostCategory.position'),
            'order'=>array('Post.id'=>'asc'),
        ));

        foreach($a_posts as $val2){
            try {
                $item_post = $val2['Post'];
                $item_cate = $val2['PostCategory'];

                $url_post = array('controller'=>'posts','action'=>'index','lang'=>$key,'position'=>$item_cate['position']);
                $tmp = explode(',', $item_cate['path']);
                for($j=0;$j<=count($tmp);$j++){
                    if ($j==count($tmp)){
                        $url_post['slug'.$j] = $item_post['slug'];
                    }else{
                        $url_post['slug'.$j]=$tmp[$j];
                    }
                }
                $url_post['ext']='html';
                $url_post['admin'] = false;

                $url_post = Router::url($url_post,true);
                $this->Post->id = $item_post['id'];
                $this->Post->set('link_sitemap',$url_post);
                $this->Post->save();
                $count_add_link++;
            }catch (Exception $e){
                $count_error++;
                $list_error = array_merge($list_error,array($item_post['slug']));
            }

        }
        // $this->Session->setFlash('<span>'.__('Thành công',true).'</span>','default',array('class'=>'success'));
        // $this->Session->write('count_add_link',$count_add_link);
        // $this->Session->write('count_error',$count_error);
        // $this->Session->write('error_list',$list_error);
        echo 'thêm add bài viết thành công<br />';
        echo 'số lượng link'.$count_add_link.'<br />';
        echo 'số lượng lỗi'.$count_error.'<br />';
        foreach ($list_error as $error) {
            echo 'số lượng link lỗi '.$error.'<br />';
        }

        // $this->redirect(array('controller'=>'Posts','action'=>'index'));
    }
    public function admin_NewSiteMap(){

        $this->layout = false; // Không sử dụng layout
        $this->autoRender = false;
//        $oneweb['product'] = Configure::read('Product');
//        $oneweb['post'] = Configure::read('Post');
//        $oneweb['faq'] = Configure::read('Faq');
//        $oneweb['media'] = Configure::read('Media');
        $oneweb['sitemap'] = Configure::read('Sitemap.xml');

        $this->loadModel('Information');
        $this->loadModel('Banner');
        $this->loadModel('Product');
        $this->loadModel('Post');
        $this->loadModel('Gallery');
        $this->loadModel('Video');
        $this->loadModel('Document');
        $this->loadModel('Faq');
        //Tạo lại Sitemap XML
        if(!empty($oneweb['sitemap'])) $this->sitemapXml();

        echo "thành công";
        // $this->Session->setFlash('<span>'.__('Thành Công',true).'</span>','default',array('class'=>'success'));
        // $this->redirect($this->referer());
    }

    function _xml_sitemapindex($root,$xmldoc,$url,$pri=null,$cha=null,$las=null){
        $elem = $xmldoc->createElement("sitemap");
        $loc = $xmldoc->createElement("loc");
        $loc->appendChild($xmldoc->createTextNode($url));
        $elem->appendChild($loc);
        // priority

        //kết thúc việc tạo node url
        $root->appendChild($elem);
    }
}

