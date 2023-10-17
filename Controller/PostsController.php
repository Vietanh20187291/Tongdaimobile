<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 * @property Post $Post
 */
class PostsController extends AppController {

    public $helpers = array('CkEditor');
    public $components = array('Upload');
    private  $limit_admin = 50;
    private $limit = 27;

    /**
     * @Description : Điều hướng xem danh sách bài viết hay chi tiết bài viết
     *
     * @throws 	: NotFoundException
     * @param 	: string slug
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    public function index($slug=null) {
//     CakeLog::write('debug', "prefix: " . $prefix);
//     CakeLog::write('debug', "slug2: " . $slug);
//     CakeLog::write('debug', "lang: " . $lang);
//     CakeLog::write('debug', "key2: " . $key2);
//     CakeLog::write('debug', "val2: " . $val2);
        if($slug==null || empty($this->params['lang'])) throw new NotFoundException(__('Trang này không tồn tại',true));
        if(empty($this->params['ext'])){			//Điều hướng xem danh sách bài viết

            $this->_list($slug);
            $this->render('list');
        }else{ 										//Điều hướng xem chi tiết bài viết

            $this->_view($slug);
            $this->render('view');
        }
    }


    /**
     * @Description : Danh sách bài viết
     *
     * @throws 	: NotFoundException
     * @param 	: str $slug
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    private function _list($slug){
        $this->set('class','list_post');
        $oneweb_post = Configure::read('Post');

        $a_params = $this->params;
        $lang = $a_params['lang'];

        //Đọc thông tin danh mục
        $a_category = Cache::read("post_category_$slug",'oneweb');
        if(!$a_category){
            $a_category = $this->Post->PostCategory->find('first',array(
                'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'slug'=>$slug,'or'=>array(array('link'=>null),array('link'=>''))),
                'fields'=>array('id','name','lang','banner','banner_link','slug','path','description','position','meta_title','meta_keyword','meta_description','meta_robots'),
                'recursive'=>-1
            ));
            Cache::write("post_category_$slug",$a_category,'oneweb');
        }
        if(empty($a_category)) throw new NotFoundException(__('Trang này không tồn tại',true));
        $a_category = $a_category['PostCategory'];
        $a_ids = array($a_category['id']);		//Id của mục này và các mục con của nó


        //Tìm các danh mục con trực tiếp
        $a_child_direct_categories = $this->Post->PostCategory->find('all',array(
            'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'parent_id'=>$a_category['id']),
            'fields'=>array('id','name','slug','lang','path','meta_title','rel','target','link','image','status','position'),
            'order'=>array('lft'=>'asc','name'=>'asc'),
            'recursive'=>-1
        ));

        //Tìm tất cả id danh mục con, bao gồm cả danh mục ko trực tiếp
        if(!empty($oneweb_post['post_child']) && !empty($a_child_direct_categories)){		//Tồn tại danh mục con

            //Tìm id của các danh mục con (bao gồm cả trực tiếp và ko trực tiếp)
            $a_child_categories = $this->Post->PostCategory->children($a_category['id'],false,array('id','status','trash'));
            foreach ($a_child_categories as $val){
                $item_cate = $val['PostCategory'];
                if($item_cate['status'] && !$item_cate['trash']) $a_ids[] = $item_cate['id'];
            }
        }

        $a_conditions2 = array('post_category_id'=>$a_ids);
        foreach($a_ids as $val){
            $a_conditions2[] = array('category_other like'=>'%-'.$val.'-%');
        }

        //Ngay hien tai
        $date_current = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
        //Danh sách bài viết
        $this->paginate = array(
            'conditions'=>array('Post.lang'=>$lang,'Post.status'=>1,'Post.trash'=>0,'or'=>$a_conditions2,'Post.public <='=>$date_current),
            'contain'=>array('PostCategory'),
            'fields'=>array('Post.id','Post.user_id','Post.name','Post.meta_description','Post.lang','Post.slug','Post.meta_title','Post.rel','Post.target','Post.public','Post.image','Post.summary','Post.created',
                'PostCategory.slug','PostCategory.path','PostCategory.status','PostCategory.position'
            ),
            'order'=>array('Post.sort'=>'asc','Post.created'=>'desc','Post.name'=>'asc'),
            'page'=>(!empty($a_params['page'])?$a_params['page']:'1'),
            'limit'=>$this->limit
        );
        
        $a_posts = $this->paginate();
        $this->set('a_posts_c',$a_posts);
        $this->set('a_category_c',$a_category);
        $this->set('a_child_direct_categories',$a_child_direct_categories);

        //Breadcrumb
        $this->set('a_breadcrumb_c',$this->_getBreadcrumbCategory($a_category['id']));

        //SEO
        $this->set('title_for_layout',$a_category['meta_title']);
        $this->set('meta_keyword_for_layout',$a_category['meta_keyword']);
        $this->set('meta_description_for_layout',$a_category['meta_description']);
        $this->set('meta_robots_for_layout',$a_category['meta_robots']);

        //Canonical
        $a_canonical = array('controller'=>'posts','action' => 'index','lang'=>$lang,'position'=>$a_category['position']);
        $tmp = explode(',', $a_category['path']);
        for($i=0;$i<count($tmp);$i++){
            $a_canonical = array_merge($a_canonical,array('slug'.$i=>$tmp[$i]));
        }
        if(!empty($a_params['page']) && $a_params['page']>1) $a_canonical = array_merge($a_canonical,array('page'=>$a_params['page']));
        if(!empty($a_params['sort']) && !empty($a_params['direction'])) $a_canonical = array_merge($a_canonical,array('sort'=>$a_params['sort'],'direction'=>$a_params['direction']));
        $this->set('a_canonical',$a_canonical);
    }


    /**
     * @Description : Chi tiết bài viết
     *
     * @throws 	: NotFoundException
     * @param 	: str $slug
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    private function _view($slug){

// 		$this->set('column_right',false);		//Thiết lập loại bỏ cột phải
        $this->set('class','detail_post');
        $this->set('active_slideshow',false);

        $lang = $this->params['lang'];

        //Đọc nội dung bài viết
        $a_post = Cache::read("post_view_$slug",'oneweb');
        //Ngay hien tai
        // $date_current = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

		if(!$a_post){
			$this->Post->unbindModel(array(
				'belongsTo'=>array('User')
			));

			$a_post = $this->Post->find('first',array(
			'conditions'=>array('PostCategory.status'=>1,'PostCategory.trash'=>0,'Post.status'=>1,'Post.trash'=>0,'Post.slug'=>$slug),
			'fields'=>array('Post.id','Post.name','Post.slug','Post.meta_title','Post.meta_keyword','Post.meta_description','Post.meta_robots','Post.description','Post.image','Post.tag','Post.star_rate','Post.star_rate_count',
							'PostCategory.id','PostCategory.path','PostCategory.position'),
			'recursive'=>0
			));

			$a_post['Post']['tag'] = $this->_getSlugForTag($a_post['Post']['tag']);

			Cache::write("post_view_$slug",$a_post,'oneweb');
		}

        if(empty($a_post)) throw new NotFoundException(__('Trang này không tồn tại',true));
        //Tìm bài viết trước và sau bài viết hiện tại
        $a_ids = array($a_post['PostCategory']['id']);		//Id của danh mục này và các danh mục con của nó

        // $a_nextprev_post['prev'] = $this->Post->find('first',array(
        //     'conditions'=>array('Post.post_category_id'=>$a_ids,'Post.id <'=>$a_post['Post']['id'],'PostCategory.status'=>1,'PostCategory.trash'=>0,'Post.status'=>1,'Post.trash'=>0,'Post.slug NOT'=>$slug,'Post.lang'=>$lang),
        //     'fields'=>array('Post.id','Post.name','Post.slug','Post.meta_title','Post.meta_keyword','Post.meta_description','Post.meta_robots','Post.description','Post.image','Post.tag','Post.star_rate','Post.lang','Post.created',
        //         'PostCategory.id','PostCategory.name','PostCategory.path','PostCategory.position'),
        //     'recursive'=>0
        // ));
        // $a_nextprev_post['next'] = $this->Post->find('first',array(
        //     'conditions'=>array('Post.post_category_id'=>$a_ids,'Post.id >'=>$a_post['Post']['id'],'PostCategory.status'=>1,'PostCategory.trash'=>0,'Post.status'=>1,'Post.trash'=>0,'Post.slug NOT'=>$slug,'Post.lang'=>$lang),
        //     'fields'=>array('Post.id','Post.name','Post.slug','Post.meta_title','Post.meta_keyword','Post.meta_description','Post.meta_robots','Post.description','Post.image','Post.tag','Post.star_rate','Post.lang','Post.created',
        //         'PostCategory.id','PostCategory.name','PostCategory.path','PostCategory.position'),
        //     'recursive'=>0
        // ));
        // $this->set('a_nextprev_post',$a_nextprev_post);

        //Tìm bài viết liên quan (Liên quan theo danh mục)
        $a_related_posts = Cache::read("post_view_relate_$slug",'oneweb');

        if(!$a_related_posts){

            $a_child_categories = $this->Post->PostCategory->children($a_post['PostCategory']['id'],false,array('id','status','trash'));

            foreach ($a_child_categories as $val){
                $item_cate = $val['PostCategory'];
                if($item_cate['status'] && !$item_cate['trash']) $a_ids[] = $item_cate['id'];
            }
            $post_first = $a_post['Post']['id'] + 1;
            $post_last = $post_first + 21;
            $arr = range($post_first, $post_last);

            $a_related_posts = $this->Post->find('all',array(
                'conditions'=>array('Post.status'=>1,'Post.trash'=>0,'PostCategory.status'=>1,'PostCategory.trash'=>0,'Post.lang'=>$lang,'Post.id'=>$arr),
                'fields'=>array('Post.id','Post.user_id','Post.lang','Post.name','Post.slug','Post.meta_title','Post.rel','Post.image','Post.target',
                    'PostCategory.id','PostCategory.path','PostCategory.position',
                ),
                'order'=>'Post.created desc',
                'limit'=>18,
                'recursive'=>0
            ));
            Cache::write("post_view_relate_$slug",$a_related_posts,'oneweb');
        }

        $this->set('a_post_c',$a_post);
        if(!empty($a_post['Post']['image'])) {
            $this->set('og_image',$a_post['Post']['image']);
        }
        $this->set('a_related_posts_c',$a_related_posts);
        //Set ảnh chia sẻ
        $oneweb_post = Configure::read('Post');
        $path = realpath($oneweb_post['path']['post']).DS;		//Đường dẫn file ảnh
        if(!empty($a_post['Post']['image']) && file_exists($path.$a_post['Post']['image']))
            $this->set('og_image', '/images/posts/'.$a_post['Post']['image']);

        //Tăng lượt xem
        $this->_increaseView($a_post['Post']['id']);

        // Breadcrumb
        $a_breadcrumb = Cache::read("post_view_breadcrumb_$slug",'oneweb');
        if(!$a_breadcrumb){
            $a_breadcrumb = $this->_getBreadcrumbCategory($a_post['PostCategory']['id']);
            $a_breadcrumb[] = array(
                'name'=>$a_post['Post']['name'],
                'meta_title'=>$a_post['Post']['meta_title'],
                'url'=>''
            );
            Cache::write("post_view_breadcrumb_$slug",$a_breadcrumb,'oneweb');
        }
        $this->set('a_breadcrumb_c',$a_breadcrumb);

        //SEO
        $this->set('title_for_layout',$a_post['Post']['meta_title']);
        $this->set('meta_keyword_for_layout',$a_post['Post']['meta_keyword']);
        $this->set('meta_description_for_layout',$a_post['Post']['meta_description']);
        $this->set('meta_robots_for_layout',$a_post['Post']['meta_robots']);

        // Canonical
        $a_canonical = array('controller'=>'posts','action' => 'index','lang'=>$lang,'position'=>$a_post['PostCategory']['position']);
        $tmp = explode(',', $a_post['PostCategory']['path']);
        for($i=0;$i<count($tmp);$i++){
            $a_canonical = array_merge($a_canonical,array('slug'.$i=>$tmp[$i]));
        }

        $a_canonical = array_merge($a_canonical,array('slug'.count($tmp)=>$a_post['Post']['slug'],'ext'=>'html'));
        $this->set('a_canonical',$a_canonical);
    }


    /**
     * @Description : Lấy breadcrumb cho module _list, _view
     *
     * @param 	: int $id		-- ID của  danh mục cần lấy breadcrumb
     * @return 	: array
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    private function _getBreadcrumbCategory($id){
        $lang = $this->params['lang'];
        $a_path = $this->Post->PostCategory->getPath($id,'id,name,slug,meta_title,path,parent_id,link,position');

        $a_breadcrumb = array();
        foreach($a_path as $val){
            $item = $val['PostCategory'];

            if(empty($item['link'])){
                $url = array('controller'=>'posts','action' => 'index','lang'=>$lang, 'position' => $item['position']);
                $tmp = explode(',', $item['path']);
                for($i=0;$i<count($tmp);$i++){
                    $url = array_merge($url,array('slug'.$i=>$tmp[$i]));
                }
            } else $url = $item['link'];

            $children = array();
            if($item['parent_id']!=null){
                $a_children = $this->Post->PostCategory->find('all',array(
                    'conditions'=>array('parent_id'=>$item['parent_id'],'id !='=>$item['id'],'status'=>1,'trash'=>0,'lang'=>$lang),
                    'fields'=>array('id','name','meta_title','slug','path','parent_id','link','position'),
                    'order'=>'lft asc',
                    'recursive'=>-1
                ));

                foreach($a_children as $val2){
                    $item2 = $val2['PostCategory'];

                    if(empty($item2['link'])){
                        $url2 = array('controller'=>'posts','action' => 'index','lang'=>$lang);
                        $tmp2 = explode(',', $item2['path']);
                        for($i=0;$i<count($tmp2);$i++){
                            $url2 = array_merge($url2,array('slug'.$i=>$tmp2[$i]));
                        }
                    }else $url2 = $item2['link'];

                    $children[] = array(
                        'name'=>$item2['name'],
                        'meta_title'=>$item2['meta_title'],
                        'url'=>$url2
                    );
                }
            }

            $a_breadcrumb[] = array(
                'name'=>$item['name'],
                'meta_title'=>$item['meta_title'],
                'url'=>$url,
                'child'=>$children
            );
        }
        return $a_breadcrumb;
    }


    /********************************************************/
    /********************************************************/
    /********************** Admin ***************************/
    /********************************************************/
    /********************************************************/

    /**
     * @Description : Danh sách bài viết
     *
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    public function admin_index() {
        $lang = $this->Session->read('lang');
        $oneweb_post = Configure::read('Post');
        $a_conditions = array('Post.lang'=>$lang,'Post.trash'=>0);

        //Action (Xóa, kích hoạt, bỏ kích hoạt)
        if(!empty($_POST['action']) && !empty($_POST['chkid'])){
            switch ($_POST['action']){
                case 'active':
                    foreach ($_POST['chkid'] as $val){
                        $this->Post->id = $val;
                        $this->Post->set(array('status'=>1));
                        $this->Post->save();
                    }
                    $message = __('Bài viết đã được kích hoạt');
                    break;
                case 'unactive':
                    foreach ($_POST['chkid'] as $val){
                        $this->Post->id = $val;
                        $this->Post->set(array('status'=>0));
                        $this->Post->save();
                    }
                    $message = __('Bài viết đã được bỏ kích hoạt');
                    break;
                case 'trashes':
                    foreach ($_POST['chkid'] as $val){
                        $this->trashItem($val);
                    }
                    $message = __('Bài viết đã được xóa');
                    break;
            }
            $this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
        }

        if(!empty($_GET['category_id'])){	//Danh muc
            $this->request->data['Post']['category_id'] = $_GET['category_id'];

            $a_cate_ids = array($_GET['category_id']);

            if(!empty($oneweb_post['post_child']) || !empty($_GET['keyword'])){
                //Tìm các danh mục con
                $a_child_categories = $this->Post->PostCategory->children($_GET['category_id'],false,array('id'));
                foreach ($a_child_categories as $val) $a_cate_ids[] = $val['PostCategory']['id'];
            }

            $cate_conditions = array('post_category_id'=>$a_cate_ids);
            foreach($a_cate_ids as $val){
                $cate_conditions[] = array('category_other like'=>'%-'.$val.'-%');
            }

            $a_conditions = array_merge($a_conditions,array('or'=>$cate_conditions));
        }
        if(!empty($_GET['position'])){	//Vi tri hien thi
            $a_conditions = array_merge($a_conditions,array('pos_'.$_GET['position'].' !='=>0));
            $a_order = array('pos_'.$_GET['position']=>'asc');
        }else{
            $a_order = array('sort'=>'asc');
        }
        if(!empty($_GET['keyword']) && $_GET['keyword']!=__('Tìm kiếm')){	//Tu khoa
            $a_conditions = array_merge($a_conditions,array('Post.name like'=>'%'.$_GET['keyword'].'%'));
        }

        $a_order = array_merge($a_order,array('created'=>'desc'));

        $this->paginate = array(
            'conditions'=>$a_conditions,
            'contain'=>array('PostCategory','Comment.id','Comment.status','User'),
            'fields'=>array(
                'name','image','user_id','slug','view','post_category_id','category_other','sort','pos_1','pos_2','pos_3','pos_4','pos_5','pos_6','status','lang','created',
                'PostCategory.name','PostCategory.slug','PostCategory.path','PostCategory.position','PostCategory.status','User.name'
            ),
            'order'=>$a_order,
            'limit'=>$this->limit_admin
        );

        $a_posts = $this->paginate();
        $this->set('a_posts_c', $a_posts);

        $counter = $this->Post->find('count',array('conditions'=>$a_conditions,'recursive'=>-1));
        $this->set('counter_c',$counter);

        if(empty($_GET['keyword'])){
            //Tìm danh mục con trực tiếp
            $a_list_children = $this->Post->PostCategory->find('all',array(
                'conditions'=>array('trash'=>0,'parent_id'=>(!empty($_GET['category_id'])?$_GET['category_id']:null)),
                'fields'=>array('id','slug','name','status','counter'),
                'order'=>'lft asc',
                'recursive'=>-1
            ));
            $this->set('a_list_children_c',$a_list_children);
        }

        //Danh sach danh muc
        $a_post_categories = $this->Post->PostCategory->generateTreeList(array('lang'=>$lang,'trash'=>0));
        $this->set('a_post_categories_c',$a_post_categories);

        //Url hiện tại
        $current_url = urlencode($this->Oneweb->curPageURL());
        $this->set('current_url_c',$current_url);
    }

    /**
     * @Description : Thay đổi trạng thái
     *
     * @throws 	: NotFoundException
     * @return 	: string
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    public function admin_ajaxChangeStatus(){
        $this->layout = false;
        $this->autoRender = false;
        if(empty($_POST['field']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

        $return = $this->_changeStatus($_POST['field'], $_POST['id']);
        if($_POST['field']){	//Đếm vị trí hiển thị trong trg hợp nó là vị trí hiển thị
            $this->Post->recursive = -1;
            $a_post = $this->Post->read('pos_1,pos_2,pos_3,pos_4,pos_5,pos_6',$_POST['id']);
            $a_post = array_filter($a_post['Post']);

            $return = array_merge($return,array('count'=>count($a_post)));
        }

        return json_encode($return);
    }

    /**
     * @Description : Sắp xếp sp
     *
     * @throws 	: NotFoundException
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    function admin_ajaxChangeSort(){
        $this->layout = false;
        $this->autoRender = false;
        if(empty($_POST['val']) || empty($_POST['field']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

        $this->Post->id = $_POST['id'];
        $this->Post->set(array($_POST['field']=>$_POST['val']));
        $this->Post->save();
        $this->Session->write('modified',true);			//Thiết lập y/c xóa cache
    }


    /**
     * @Description : Thêm bài viết
     *
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    public function admin_add() {
        $lang = $this->Session->read('lang');

        if ($this->request->is('post')) {
            $oneweb_post = Configure::read('Post');
            $oneweb_seo = Configure::read('Seo');
            $data = $this->request->data['Post'];

            //Ảnh đại diện
            $file = $data['image'];
            $data['image'] = '';

            //Slug - meta title
            if($oneweb_seo){
                //Slug
                if(empty($data['slug'])) $data['slug'] = $data['name'];

                //Meta title
                if(empty($data['meta_title'])) $data['meta_title'] = $data['name'];
            }else{
                //Slug
                $data['slug'] = $data['name'];

                //Meta title
                $data['meta_title'] = $data['name'];
            }

            //Lấy danh sách slug đã tồn tại
            $a_all_slugs = $this->Post->find('list',array('conditions'=>array('lang'=>$lang),'fields'=>'slug'));

            $data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);

            //Ngay tao
            if(!empty($data['created'])){
                $data['created'] = mktime($data['created']['hour'],$data['created']['min'],0,$data['created']['month'],$data['created']['day'],$data['created']['year']);
            }else{
                $data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
            }

            //Ngay public
            if(!empty($data['public'])){
                $data['public'] = mktime($data['public']['hour'],$data['public']['min'],0,$data['public']['month'],$data['public']['day'],$data['public']['year']);
            }else{
                $data['public'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
            }
            //Tag
            if($oneweb_post['tag'] && !empty($data['tag'])){
                $data['tag'] = $this->_getTag($data['tag']);
            }

            //ID của các danh mục khác
            if(!empty($data['category_other'])){
                $data['category_other'] = '-'.implode('-', array_filter($data['category_other'])).'-';
            }

            $data['summary'] = nl2br($data['summary']);

            //Ngôn ngữ
            $data['lang'] = $lang;

            //User
            $admin = $this->Auth->user();
            $data['user_id'] = $admin['id'];

            $this->Post->create();
            if ($this->Post->save($data)) {
                $id = $this->Post->getLastInsertID();

                //Kiem tra tag
                if($oneweb_post['tag'] && !empty($data['tag'])){
                    $this->_checkTag($data['tag']);
                    $this->_setTagPriority($data['tag'], $id);
                }

                //Upload image

                $path = realpath($oneweb_post['path']['post']).DS;		//Đường dẫn file ảnh
                if(!empty($file['name'])){
                    $result = $this->Upload->upload($file, $path, null, array('type' => 'resizemax', 'size' => $oneweb_post['size']['post'], 'output' => 'jpg'));
                    if($result){
                        $image = $this->Upload->result;

                        //Luu ten anh vao ban ghi vua duoc them vao bang posts
                        $this->Post->id = $id;
                        $this->Post->set('image',$image);
                        $this->Post->save();
                    }else{
                        //Hien thi loi
                        $errors=$this->Upload->errors;
                        // piece together errors
                        if(is_array($errors)){ $errors = implode("<br />",$errors); }
                        $this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error'));
                        $this->redirect(array('action'=>'edit',$id));
                    }
                }

                $this->Session->setFlash('<span>'.__('Thêm mới thành công').'</span>','default',array('class'=>'success'));
                $this->Session->write('modified',true);			//Thiết lập y/c xóa cache
                if (!empty($_POST['save'])){
                    $this->redirect(array('action'=>'edit',$id));
                }elseif (!empty($_POST['save_add'])){
                    $this->redirect(array('action'=>'add'));
                }else $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
            }
        }
        //Danh sach danh muc
        $a_categories_c = $this->Post->PostCategory->generateTreeList(array('lang'=>$lang,'trash'=>0));

        $this->set(compact('a_categories_c', 'a_makers_c', 'a_taxes_c'));
    }

    /**
     * @Description : Sửa bài viết
     *
     * @throws NotFoundException
     * @param int $id
     * @return void
     * @Author Hoang Tuan Anh - tuananh@url.vn
     */
    public function admin_edit($id = null) {
        $this->Post->id = $id;
        if (!$this->Post->exists()) throw new NotFoundException(__('Invalid'));
        $lang = $this->Session->read('lang');

        if ($this->request->is('post') || $this->request->is('put')) {
            $oneweb_post = Configure::read('Post');
            $oneweb_seo = Configure::read('Seo');
            $data = $this->request->data['Post'];

            $this->Post->recursive = -1;
            $a_post = $this->Post->read('image,tag',$id);
            $a_post = $a_post['Post'];

            //Ảnh đại diện
            if(!empty($data['image']['name'])){		//Up ảnh khác
                $file = $data['image'];
            }
            $data['image'] = $a_post['image'];

            //Slug - meta title
            if($oneweb_seo){
                //Slug
                if(empty($data['slug'])) $data['slug'] = $data['name'];

                //Meta title
                if(empty($data['meta_title'])) $data['meta_title'] = $data['name'];
            }else{
                //Slug
                $data['slug'] = $data['name'];

                //Meta title
                $data['meta_title'] = $data['name'];
                $data['meta_keyword'] = '';
                $data['meta_description'] = '';
            }

            //Lấy danh sách slug đã tồn tại
            $a_all_slugs = $this->Post->find('list',array('conditions'=>array('lang'=>$lang,'id !='=>$id),'fields'=>'slug','recursive'=>-1));

            $data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);

            //Ngày sửa
            if(!empty($data['modified'])){
                $data['modified'] = mktime($data['modified']['hour'],$data['modified']['min'],0,$data['modified']['month'],$data['modified']['day'],$data['modified']['year']);
            }else{
                $data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
            }

            //Ngày public
            if(!empty($data['public'])){
                $data['public'] = mktime($data['public']['hour'],$data['public']['min'],0,$data['public']['month'],$data['public']['day'],$data['public']['year']);
            }else{
                $data['public'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
            }
            //Tag
            if($oneweb_post['tag'] && !empty($data['tag'])){
                $data['tag'] = $this->_getTag($data['tag']);
            }

            //ID của các danh mục khác
            if(!empty($data['category_other'])){
                $data['category_other'] = '-'.implode('-', array_filter($data['category_other'])).'-';
            }
            //ID của các danh mục khác
            if(!empty($data['product_category_other'])){
                $data['product_category_other'] = '-'.implode('-', array_filter($data['product_category_other'])).'-';
            }
            if ($this->Post->save($data)) {
                //Kiểm tra, cập nhật lại Tag
                if($oneweb_post['tag']){
                    $this->_checkTag($data['tag'],$a_post['tag']);
                    $this->_setTagPriority($data['tag'], $id);
                }

                $path = realpath($oneweb_post['path']['post']).DS;		//Đường dẫn file ảnh
                //Upload image
                if(!empty($file['name'])){
                    //Xóa ảnh cũ
                    if(!empty($a_post['image']) && file_exists($path.$a_post['image'])) unlink($path.$a_post['image']);

                    //Up ảnh mới
                    $result = $this->Upload->upload($file, $path, null, array('type' => 'resizemax', 'size' => $oneweb_post['size']['post'], 'output' => 'jpg'));
                    if($result){
                        $image = $this->Upload->result;

                        //Luu ten anh vao ban ghi vua duoc them vao bang posts
                        $this->Post->id = $id;
                        $this->Post->set('image',$image);
                        $this->Post->save();
                    }else{
                        //Hien thi loi
                        $errors=$this->Upload->errors;
                        // piece together errors
                        if(is_array($errors)){ $errors = implode("<br />",$errors); }
                        $this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error'));
                        $this->redirect($this->referer());
                    }
                }

                $this->Session->setFlash('<span>'.__('Thông tin đã được cập nhật').'</span>','default',array('class'=>'success'));
                $this->Session->write('modified',true);			//Thiết lập y/c xóa cache
                if (!empty($_POST['save'])){
                    $this->redirect($this->referer());
                }elseif (!empty($_POST['save_add'])){
                    $this->redirect(array('action'=>'add'));
                }else{
                    $url = (!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index');
                    $this->redirect($url);
                }
            } else {
                $this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
            }
        } else {
            $this->request->data = $this->Post->read(null, $id);
            $this->request->data['Post']['category_other'] = array_filter(explode('-', $this->request->data['Post']['category_other']));
        }

        //Danh sach danh muc
        $a_categories_c = $this->Post->PostCategory->generateTreeList(array('lang'=>$lang,'trash'=>0));
        $this->loadModel('Product');
        $a_categories_product_c = $this->Product->ProductCategory->generateTreeList(array('lang'=>$lang,'trash'=>0));


        $this->set(compact('a_categories_c'));
        $this->set(compact('a_categories_product_c'));
    }


    /**
     * @Description : Cho sản phẩm vào thùng rác
     *
     * @throws 	: NotFoundException
     * @param 	: int data
     * @return 	: array
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    public function admin_ajaxTrashItem(){
        $this->layout = false;
        $this->autoRender = false;

        if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
        return $this->trashItem($_POST['id']);
    }

    /**
     * @Description : Đưa sản phẩm vào thùng rac
     *
     * @throws 	: NotFoundException
     * @param 	: int data
     * @return 	: array
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    private function trashItem($id){
        //Thông tin sản phẩm
        $this->Post->recursive = -1;
        $a_post = $this->Post->read('id,name',$id);
        $item_post = $a_post['Post'];

        //Ghi vào bảng Trash
        $data['name'] = $item_post['name'];
        $data['item_id'] = $item_post['id'];
        $data['model'] = 'Post';
        $data['description'] = 'Bài viết';
        $data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

        $this->loadModel('Trash');
        $this->Trash->create();
        if($this->Trash->save($data)){
            $this->Post->id = $id;
            $this->Post->set(array('trash'=>1));
            if($this->Post->save()){
                $this->Session->write('modified',true);			//Thiết lập y/c xóa cache
                return true;
            }
        }
        return false;
    }

    /*
        * @Description :
        * @param - string :
        * @param - interger:
        * @param - array:
        * @return - array:
        * @Author : HuuQuynh - quynh@url.vn
        */
    public function admin_ajaxLoadTag(){
        $this->layout = false;
        $this->autoRender = false;
        if(!empty($this->params['named']['lang'])){ $lang = $this->params['named']['lang']; }
        else{
            $lang = 'vi';
        }
        if (empty($_GET['name_startsWith'])) exit ;
        $q = strtolower($_GET["name_startsWith"]);
        // remove slashes if they were magically added
        if (get_magic_quotes_gpc()) $q = stripslashes($q);

        $this->loadModel('Tag');
        $arr_tag = $this->Tag->find('all', array(
            'conditions'=>array('Tag.lang'=>$lang,'Tag.name like'=>'%'.$q.'%'),
            'fields'=>array('name','lang'),
            'recursive'=>-1
        ));
        $result = array();
        foreach ($arr_tag as $value) {
            $tag_name = $value['Tag'];
            $label = $tag_name['name'];
            if (strpos(strtolower($label), $q) !== false) {
                array_push($result, array("label"=>$label, "value" => strip_tags($tag_name['name'])));
            }
            if (count($result) > 11)
                break;
        }
        return json_encode($result);
    }
    /*
        * @Description :
        * @param - string :
        * @param - interger:
        * @param - array:
        * @return - array:
        * @Author : HuuQuynh - quynh@url.vn
        */
    public function admin_ajaxLoadPost(){
        $this->layout = false;
        $this->autoRender = false;
        if(!empty($this->params['named']['lang'])){ $lang = $this->params['named']['lang']; }
        else{
            $lang = 'vi';
        }
        if (empty($_GET['name_startsWith'])) exit ;
        $q = strtolower($_GET["name_startsWith"]);
        // remove slashes if they were magically added
        if (get_magic_quotes_gpc()) $q = stripslashes($q);


        $arr_post = $this->Post->find('all', array(
            'conditions'=>array('Post.lang'=>$lang,'Post.trash'=>0,'Post.name like'=>'%'.$q.'%'),
            'fields'=>array('name','lang', 'trash'),
            'recursive'=>-1
        ));

        $result = array();
        foreach ($arr_post as $value) {
            $post_name = $value['Post'];
            $label = $post_name['name'];
            if (strpos(strtolower($label), $q) !== false) {
                array_push($result, array("label"=>$label, "value" => strip_tags($post_name['name'])));
            }
            if (count($result) > 11)
                break;
        }
        return json_encode($result);
    }


    public function admin_import() {
        //Danh sach danh muc
        $a_categories_c = $this->Post->PostCategory->generateTreeList(array('lang'=>'vi','trash'=>0));

        $this->set(compact('a_categories_c', 'a_makers_c', 'a_taxes_c'));

    }

    public function admin_ActionImport() {
        
        $string = file_get_contents($this->request->data['Posts']['path_file']['tmp_name']);
        $this->loadModel('PostCategory');
        $category = $this->PostCategory->find('first',array('conditions'=>array('PostCategory.id'=>$this->request->data['Posts']['post_category_id']),'recursive'=>-1));
        $item_category = $category['PostCategory'];
        if(empty($item_category['link'])){
            $url_view = array('controller'=>'posts','action'=>'index','lang'=>$item_category['lang'],'position'=>$item_category['position']);
            $tmp = explode(',', $item_category['path']);
            for($i=0;$i<count($tmp);$i++){
                $url_view['slug'.$i]=$tmp[$i];
            }
            $url_view['admin'] = false;
        }else $url_view = $item_category['link'];
        $count_import = 0;
        $count_replace = 0;
        $count_error = 0;
        $list_error = [];
        foreach (json_decode($string) as $item){
            $data_import  = json_decode(json_encode($item), true);
            $oneweb_post = Configure::read('Post');
            $oneweb_seo = Configure::read('Seo');
            $data = [];
            $post_slug = $this->Post->find('first',array('conditions'=>array('Post.slug'=>$data_import['post_name']),'fields'=>array('id','slug','name')));
            preg_match('/<a [^>]*href=["|\']([^"|\']+)/i', $data_import['post_content'], $matches);
            // fix url của chủ đề 
            $link_url = '';
            for($i = 0; $i < count($tmp);$i++) {
                $link_url .= '/'.$tmp[$i];
            }
            $link_url .= '/'.$data_import['post_name'].'.html';
            if (empty($post_slug)){
                try {
                    //Slug - meta title
                    $data['name'] = $data_import['post_title'];
                    $data['post_category_id'] = $this->request->data['Posts']['post_category_id'];
                    $data['tag'] = "";
                    $data['target'] = "_self";
                    $data['summary'] = "";
                    $data['pos_1'] = "0";
                    $data['sort'] = "90";
                    $data['meta_description'] = $data_import['description'];
                    preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $data_import['post_content'], $image);
                    $data['image'] = $data_import['twitter_image'];
                    $data['meta_robots'] = "index,follow";
                    $data['rel'] = "dofollow";
                    preg_match('/<a [^>]*href=["|\']([^"|\']+)/i', $data_import['post_content'], $matches);
                    $data['description'] = str_replace($matches[1],$link_url,$data_import['post_content']);
                    $data['link_preconnect'] = $data_import['twitter_description'];
                    if($oneweb_seo){
                        //Slug
                        if(empty($data['slug'])) $data['slug'] = $data_import['post_name'];

                        //Meta title
                        if(empty($data['meta_title'])) $data['meta_title'] = $data_import['post_title'];
                    }else{
                        //Slug
                        $data['slug'] = $data_import['post_name'];

                        //Meta title
                        $data['meta_title'] = $data_import['post_title'];
                    }

                    //Lấy danh sách slug đã tồn tại
                    $a_all_slugs = $this->Post->find('list',array('conditions'=>array('lang'=>$lang),'fields'=>'slug'));

                    $data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);

                    //Ngay tao
                    if(!empty($data['created'])){
                        $data['created'] = mktime($data['created']['hour'],$data['created']['min'],0,$data['created']['month'],$data['created']['day'],$data['created']['year']);
                    }else{
                        $data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
                    }

                    //Ngay public
                    if(!empty($data['public'])){
                        $data['public'] = mktime($data['public']['hour'],$data['public']['min'],0,$data['public']['month'],$data['public']['day'],$data['public']['year']);
                    }else{
                        $data['public'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
                    }

                    //ID của các danh mục khác
                    if(!empty($data['category_other'])){
                        $data['category_other'] = '-'.implode('-', array_filter($data['category_other'])).'-';
                    }

                    $data['summary'] = nl2br($data['summary']);

                    //Ngôn ngữ
                    $data['lang'] = 'vi';
                    $data['status'] = "0";
                    $data['trash'] = "0";
                    //User
                    $data['user_id'] = '14';
                    $this->Post->create();
                    $this->Post->save($data);
                    $count_import++;
                } catch (Exception $e) {
                    $count_error++;
                    $list_error = array_merge($list_error,array($data_import['post_name']));
                    continue;
                }

            }else{
                try {
                    $this->Post->id = $post_slug['Post']['id'];
                    preg_match('/<a [^>]*href=["|\']([^"|\']+)/i', $data_import['post_content'], $matches);
                    $this->Post->set('description',str_replace($matches[1],$link_url,$data_import['post_content']));
                    $this->Post->set('link_preconnect',$data_import['twitter_description']);
                    $this->Post->set('image',$data_import['twitter_image']);
                    $this->Post->set('meta_description',$data_import['description']);
                    $this->Post->set('name',$data_import['post_title']);
                    $this->Post->set('status','0');
                    $this->Post->set('trash','0');
                    $this->Post->save();
                    $count_replace++;
                } catch (Exception $e) {
                    $count_error++;
                    $list_error = array_merge($list_error,array($data_import['post_name']));
                    continue;
                }
            }
        }
        $this->Session->write('error_list',$list_error);
        $this->Session->write('count_import',$count_import);
        $this->Session->write('count_replace',$count_replace);
        $this->Session->write('count_error',$count_error);
        $this->redirect(array('action'=>'index'));

    }

    public function admin_ajaxDeleteSession(){
        $this->Session->delete('error_list');
        $this->Session->delete('count_import');
        $this->Session->delete('count_replace');
        $this->Session->delete('count_error');
        $this->Session->delete('count_add_link');
    }

    public function admin_chinhlaibaiviet(){
        // lấy id post_category là test 
        $this->loadModel('PostCategory');
        $category = $this->PostCategory->find('first',array('conditions'=>array('PostCategory.slug'=>'blog'),'recursive'=>-1));
        // lấy danh sách danh mục bài viết post có tên là test 
        $posts = $this->Post->find('all',array(
            'conditions'=>array('Post.post_category_id'=>$category['PostCategory']['id'],'Post.status'=>0),
            'fields'=>array('id','description','slug')
        ));
        $tmp = explode(',', $category['PostCategory']['path']);



        foreach($posts as $post) {
            $link_url = '';
            for($i = 0; $i < count($tmp);$i++) {
                $link_url .= '/'.$tmp[$i];
            }
            $link_url .= '/'.$post['Post']['slug'].'.html';
            print_r($link_url);
            $des = $post['Post']['description'];

            $pattern = '/<a[^>]+href="([^"]+\.html)">/';

            // Tìm tất cả các kết quả phù hợp
            preg_match_all($pattern, $des, $matches);
            print_r($matches[1][0]);
            // Duyệt qua từng kết quả và in nội dung trong thuộc tính 'href'
            $post['Post']['description'] = str_replace($matches[1][0],$link_url,$des);
            $this->Post->id = $post['Post']['id'];
            $this->Post->set('description',str_replace($matches[1][0],$link_url,$des));
            $this->Post->save();
        }

    }

}



