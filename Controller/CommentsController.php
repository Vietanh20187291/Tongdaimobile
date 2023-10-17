<?php
App::uses('AppController', 'Controller');
/**
 * Comments Controller
 *
 * @property Comment $Comment
 */
class CommentsController extends AppController {

	public $helpers = array('CkEditor');
	private $limit_admin = 50;
	private $limit = 4;

	/**
	 * @Description : Danh sách comment
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function ajaxComment(){
		$this->Captcha = $this->Components->load('Captcha');

		$this->layout = 'ajax';

		if($this->request->is('post')){
			$a_conditions = array('Comment.status'=>1,'Comment.item_id'=>$_POST['id'],'Comment.model'=>$_POST['model'],'Comment.parent_id'=>null);
			$limit = $this->limit;

			$this->Comment->unbindModel(array('belongsTo'=>array('ParentComment','Member')));
			$this->Comment->bindModel(array(
				'hasMany'=>array(
							'ChildComment' => array(
							'className' => 'Comment',
							'foreignKey' => 'parent_id',
							'dependent' => true,
							'conditions' => array('status'=>1),
							'fields' => '',
							'order' => '',
							'limit' => '',
							'offset' => '',
							'exclusive' => '',
							'finderQuery' => '',
							'counterQuery' => ''
						)
					)
			));
			$a_comments = $this->Comment->find('all',array(
				'conditions'=>$a_conditions,
				'page'=>$_POST['page'],
				'limit'=>$limit,
				'order'=>array('created'=>'desc'),
				'recursive'=>1
			));

			//Tổng số page
			$total_comment = $this->Comment->find('count',array(
				'conditions'=>$a_conditions,
				'recursive'=>-1
			));
			$a_page['total'] = ceil($total_comment/$limit);
			$a_page['current'] = $_POST['page'];

			$this->set('a_page_c',$a_page);
			$this->set('a_comments_c',$a_comments);
			$this->set('total_comment_c',$total_comment);
		}else throw new NotFoundException(__('Trang này không tồn tại',true));
	}


	/**
	 * @Description : Thêm comment
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function ajaxAddComment(){
		$this->layout = false;
		$this->autoRender = false;

		if ($this->request->is('post')) {
			$data = $this->request->data['Comment'];
			$a_errors['error'] = true;
			$a_errors['empty'] = '';

			if(empty($data['description'])) $a_errors['empty'] = __('Bạn chưa nhập câu hỏi',true);
			//elseif(empty($data['phone'])) $a_errors['empty'] = 'Bạn chưa nhập số điện thoại';
			elseif(empty($data['name'])) $a_errors['empty'] = __('Bạn chưa nhập tên',true);elseif(empty($data['phone']))$a_errors['empty'] = __('Bạn chưa nhập SDT',true);
			if(empty($a_errors['empty'])){
				$lang = $this->params['lang'];
				$data['lang'] = $lang;
				$data['status'] = 0;
				$data['view'] = 0;
				$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
				$data['description'] = nl2br($data['description']);

				//Get Ip or Proxy
				if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
					$data['ip'] = $_SERVER["HTTP_X_FORWARDED_FOR"];
					$data['proxy'] = $_SERVER['REMOTE_ADDR'];
				}else{
					$data['ip'] = $_SERVER['REMOTE_ADDR'];
					$data['proxy'] = '';
				}

				if(!empty($data['parent_id'])){								//Trường hợp trả lời commnet - ko y/c nhập captcha
					$a_answer_comment_ids = array($data['parent_id']);
					if($this->Session->check('Comment.'.$data['model'].'.id')){
						$a_answer_comment_session_ids = $this->Session->read('Comment.'.$data['model'].'.id');
						if(in_array($data['parent_id'], $a_answer_comment_session_ids)) $check = false;
						else $a_answer_comment_ids = array_merge($a_answer_comment_ids,$a_answer_comment_session_ids);
					}

					$this->Comment->id = $data['parent_id'];
					$this->Comment->saveField('modified', mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
				}

				$this->Comment->create();
				if($this->Comment->save($data)){
					if(!empty($data['parent_id']) && !empty($a_answer_comment_ids)) $this->Session->write('Comment.'.$data['model'].'.id',$a_answer_comment_ids);
					$a_errors['error'] = false;
				}
			}
			return json_encode($a_errors);
		}
	}

	/**
	 * @Description : Lấy form trả lời câu hỏi
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function ajaxAnswerComment(){
		$this->layout = 'ajax';

		if($this->request->is('post')){
			$id = $_POST['id'];

			$a_comment = $this->Comment->read('id,model,item_id',$id);

			if(empty($a_comment)) throw new NotFoundException(__('Trang này không tồn tại',true));
			$this->set('a_comment_c',$a_comment);

		}else throw new NotFoundException(__('Trang này không tồn tại',true));
	}


	/**
	 * @Description :
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function ajaxLike(){
		$this->layout = false;
		$this->autoRender = false;

		if ($this->request->is('post')) {
			//Đọc like cũ
			$id = $_POST['id'];
			$this->Comment->recursive = -1;
			$a_comment = $this->Comment->read('id,like,model',$id);
			$item_comment = $a_comment['Comment'];

			$check = true;
			$a_like_comment_ids = array($id);
			if($this->Session->check('Comment.'.$item_comment['model'].'.like')){
				$a_like_comment_session_ids = $this->Session->read('Comment.'.$item_comment['model'].'.like');
				if(in_array($id, $a_like_comment_session_ids)) $check = false;
				else $a_like_comment_ids = array_merge($a_like_comment_ids,$a_like_comment_session_ids);
			}

			if($check){
				//Tăng like lên 1
				$like = $item_comment['like']+1;
				$this->Comment->id = $id;
				$this->Comment->set(array('like'=>$like));
				if($this->Comment->save()){
					$this->Session->write('Comment.'.$item_comment['model'].'.like',$a_like_comment_ids);
					return $like;
				}else return false;
			}else return false;
		}else throw new NotFoundException(__('Trang này không tồn tại',true));
		return false;
	}

	public function captchaImage(){
		$this->Captcha = $this->Components->load('Captcha');
		$this->layout = false;
		$this->autoRender = false;
				$this->Captcha->image();
		}
	public function ajaxGetRateComment(){
        $this->layout = 'ajax';
        if(!$this->request->is('ajax'))  throw new NotFoundException(__('Trang này không tồn tại',true));
        $lang = $this->params['lang'];
        $product_id =  $_POST['product_id'];
        $model = $_POST['model'];
        $conditions = array('Comment.item_id'=>$product_id,'Comment.status'=>1,'Comment.model'=>$model,'Comment.star >'=>0);
        $rate_comment = $this->Comment->find('all',array(
                'conditions'=>$conditions,
                'recursive'=>-1
        ));
        //Tổng số comment
        $sum_rate_comment = $this->Comment->find('count',array(
                'conditions'=>$conditions,
                'recursive'=>-1
        ));
        $total_rate_point = 0;
        $rate[1] = $rate[2] = $rate[3] = $rate[4] = $rate[5] = 0;
        foreach ($rate_comment as $key => $value) {
            $total_rate_point += $value['Comment']['star'];
            $rate[$value['Comment']['star']] ++;
        }
        $this->set(compact('sum_rate_comment','total_rate_point','rate'));
    }

	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/

	/**
	 * @Description : Danh sách comment
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_index() {
		$lang = $this->Session->read('lang');
		$a_conditions = array('Comment.lang'=>$lang);

		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'view':
					foreach ($_POST['chkid'] as $val){
						$this->Comment->id = $val;
						$this->Comment->set(array('view'=>1));
						$this->Comment->save();
					}
					$message = __('Bình luận đã được thiết lập đã đọc');
					break;
				case 'unview':
					foreach ($_POST['chkid'] as $val){
						$this->Comment->id = $val;
						$this->Comment->set(array('view'=>0));
						$this->Comment->save();
					}
					$message = __('Bình luận đã được thiết lập chưa đọc');
					break;
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->Comment->id = $val;
						$this->Comment->set(array('status'=>1));
						$this->Comment->save();
					}
					$message = __('Bình luận đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->Comment->id = $val;
						$this->Comment->set(array('status'=>0));
						$this->Comment->save();
					}
					$message = __('Bình luận đã được bỏ kích hoạt');
					break;
				case 'delete':
					foreach ($_POST['chkid'] as $val){
						$this->Comment->delete($val);
					}
					$message = __('Bình luận đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}
		if(!empty($_GET['keyword']) && $_GET['keyword']!=__('Họ tên, Email')){	//Tu khoa
			$a_conditions = array_merge($a_conditions,array(
				'or'=>array('Comment.name like'=>'%'.$_GET['keyword'].'%',
							'Comment.email like'=>'%'.$_GET['keyword'].'%'
				)
			));
		}

		if(!empty($_GET['model'])) $a_conditions = array_merge($a_conditions,array('Comment.model'=>$_GET['model']));

		$this->paginate = array(
			'conditions'=>$a_conditions,
			'fields'=>array('Comment.*','Member.id','Member.name'),
			'order'=>array('modified'=>'desc'),
			'limit'=>$this->limit_admin
		);

		$a_comments = $this->paginate();
		$this->set('a_comments_c', $a_comments);

		$a_conditions2 = array();
		foreach($a_conditions as $key=>$val) if($key!='Comment.parent_id') $a_conditions2[$key] = $val;
		$counter = $this->Comment->find('count',array('conditions'=>$a_conditions2,'recursive'=>-1));
		$this->set('counter_c',$counter);

		//Url hiện tại
		$current_url = urlencode($this->Oneweb->curPageURL());
		$this->set('current_url_c',$current_url);
	}


	/**
	 * @Description : Thêm comment
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		if(empty($_GET['parent_id'])) throw new NotFoundException(__('Invalid',true));

		if ($this->request->is('post')) {
			$lang = $this->Session->read('lang');
			$data = $this->request->data['Comment'];

			$data['lang'] = $lang;
      $user = $this->Auth->user();
			//Ngay tao
			$data['created'] = mktime($data['created']['hour'],$data['created']['min'],0,$data['created']['month'],$data['created']['day'],$data['created']['year']);

			//Get Ip or Proxy
			if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
				$data['ip'] = $_SERVER["HTTP_X_FORWARDED_FOR"];
				$data['proxy'] = $_SERVER['REMOTE_ADDR'];
			}else{
				$data['ip'] = $_SERVER['REMOTE_ADDR'];
				$data['proxy'] = '';
			}
      $data['user_id'] = $user['id'];

			if(empty($data['like'])) $data['like'] = 0;

			$data['view'] = 1;

			$this->Comment->create();
			if ($this->Comment->save($data)) {
				$id = $this->Comment->getLastInsertID();
				$this->Session->setFlash('<span>'.__('Thêm mới thành công').'</span>','default',array('class'=>'success'));
				$this->Comment->id = $_GET['parent_id'];
				    $this->Comment->saveField('modified', mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
				if (isset($_POST['save'])){
					$this->redirect(array('action'=>'edit',$id));
				}elseif (isset($_POST['save_add'])){
					$this->redirect(array('action'=>'add','?'=>array('parent_id'=>$_GET['parent_id'])));
				}elseif (isset($_POST['save_exit'])){
					$this->redirect(array('action'=>'index'));
				}
			} else {
				$this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
			}
		}

		//Đọc nội dung comment
		$id = $_GET['parent_id'];
		$this->Comment->recursive = -1;
		$a_comment = $this->Comment->read('',$id);
		if(empty($a_comment)) throw new NotFoundException(__('Invalid',true));
		$this->set('a_comment_c',$a_comment);
	}



	/**
	 * @Description : Sửa comment
	 *
	 * @throws NotFoundException
	 * @param int $id
	 * @return void
	 * @Author Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_edit($id = null) {
		$this->Comment->id = $id;
		if (!$this->Comment->exists()) throw new NotFoundException(__('Invalid'));

		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data['Comment'];

			//Ngay tao
			$data['created'] = mktime($data['created']['hour'],$data['created']['min'],0,$data['created']['month'],$data['created']['day'],$data['created']['year']);

			if ($this->Comment->save($data)) {
				$this->Session->setFlash('<span>'.__('Thông tin đã được cập nhật').'</span>','default',array('class'=>'success'));

				if (isset($_POST['save'])){
					$this->redirect($this->referer());
				}elseif (isset($_POST['save_add'])){
					$this->redirect(array('action'=>'add'));
				}elseif (isset($_POST['save_exit'])){
					$url = (!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index');
					$this->redirect($url);
				}
			} else {
				$this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
			}
		} else {
			//Set trạng thái đã xem comment này
			$this->Comment->id = $id;
			$this->Comment->set(array('view'=>1));
			$this->Comment->save();


			$this->Comment->recursive = -1;
			$this->request->data = $this->Comment->read(null, $id);
		}

		if(!empty($this->request->data['Comment']['parent_id'])){
			$a_comment = $this->Comment->read(null,$this->request->data['Comment']['parent_id']);
			$this->set('a_comment_c',$a_comment);
		}
	}


	/**
	 * @Description :	Redirect ra trang ngoài tương ứng với vị trí comment
	 *
	 * @params 	:
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_view(){
		if(empty($_GET['model']) || empty($_GET['id'])) throw new NotFoundException(__('Invalid',true));

		$model = $_GET['model'];
		$id = $_GET['id'];
		$this->loadModel($model);

		$url = array();
		if($model=='Product'){						//Sản phẩm
			$a_product = $this->$model->find('first',array(
				'conditions'=>array('Product.id'=>$id),
				'fields'=>array('id','slug','lang'),
				'contain'=>array('ProductCategory.path'),
				'recursive'=>0
			));

			$item_product = $a_product['Product'];
			$item_cate 	  = $a_product['ProductCategory'];

			$url = array('controller'=>'products','action'=>'index','lang'=>$item_product['lang']);
			$tmp = explode(',', $item_cate['path']);
			for($j=0;$j<count($tmp);$j++){
				$url['slug'.$j]=$tmp[$j];
			}
			$url['slug'.count($tmp)] = $item_product['slug'];
			$url['ext']='html';
		}elseif($model=='Post'){					//Bài viết
			$a_post = $this->$model->find('first',array(
				'conditions'=>array('Post.id'=>$id),
				'fields'=>array('id','slug','lang'),
				'contain'=>array('PostCategory.path','PostCategory.position'),
				'recursive'=>0
			));

			$item_post = $a_post['Post'];
			$item_cate 	  = $a_post['PostCategory'];

			$url = array('controller'=>'posts','action'=>'index','lang'=>$item_post['lang'],'position'=>$item_cate['position']);
			$tmp = explode(',', $item_cate['path']);
			for($j=0;$j<count($tmp);$j++){
				$url['slug'.$j]=$tmp[$j];
			}
			$url['slug'.count($tmp)] = $item_post['slug'];
			$url['ext']='html';
		}elseif($model=='Gallery'){
			$a_gallery = $this->$model->find('first',array(
				'conditions'=>array('Gallery.id'=>$id),
				'fields'=>array('id','slug','lang','GalleryCategory.slug'),
				'recursive'=>0
			));
			$item_gallery = $a_gallery['Gallery'];
			$item_cate = $a_gallery['GalleryCategory'];
			$url = array('controller'=>'galleries','action'=>'index','lang'=>$item_gallery['lang'],'slug0'=>$item_cate['slug'],'slug1'=>$item_gallery['slug'],'ext'=>'html');
		}elseif($model=='Video'){
			$a_video = $this->$model->find('first',array(
				'conditions'=>array('Video.id'=>$id),
				'fields'=>array('id','slug','lang','VideoCategory.slug'),
				'recursive'=>0
			));
			$item_video = $a_video['Video'];
			$item_cate = $a_video['VideoCategory'];
			$url = array('controller'=>'videos','action'=>'index','lang'=>$item_video['lang'],'slug0'=>$item_cate['slug'],'slug1'=>$item_video['slug'],'ext'=>'html');
		}

		if(empty($url))die(__('Error',true));

		$url['admin'] = false;
		$this->redirect($url);
	}



	/**
	 * @Description : Xóa comment sdung ajax
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxDeleteItem() {
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		if($this->Comment->delete($_POST['id'])) return true;
		else return false;
	}



	/**
	 * @Description : Thay đổi trạng thái comment
	 *
	 * @throws 	: NotFoundException
	 * @return 	: string
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxChangeStatus(){
		$this->layout = false;
		$this->autoRender = false;
		$model = $this->modelClass;

		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		$return = $this->_changeStatus('status',$_POST['id']);

		return json_encode($return);
	}







	/**
	 * @Description : Lấy ra comment tương ứng với bài viết
	 *
	 * @throws 	: NotFoundException
	 * @return 	: html
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxComment(){
		$this->layout = 'ajax';

		if(empty($_POST['item_id'])) throw new NotFoundException(__('Invalid'));

		$item_id = $_POST['item_id'];
		$model = $_POST['model'];

		//Lấy toàn bộ comment của bài viết này
		$this->Comment->unbindModel(array(
										'belongsTo'=>array('ParentComment')
									));
		$this->Comment->bindModel(array(
										'belongsTo'=>array(
												'Member' => array(
												'className' => 'Member',
												'foreignKey' => 'member_id',
												'conditions' => '',
												'fields' => array('id','name','gender','email'),
												'order' => ''
											))
									)
								);
		$a_comments = $this->Comment->find('all',array(
					'conditions'=>array('Comment.item_id'=>$item_id,'Comment.model'=>$model,'Comment.parent_id'=>null),
					'order'=>'Comment.created desc'
				)
			);

		//Thiết lập đã xem comment
		$this->Comment->updateAll(array('view'=>1),array('Comment.item_id'=>$item_id,'Comment.model'=>$model));

		$this->set('a_comments_c',$a_comments);
		$this->set('item_id_c',$item_id);
		$this->set('model_c',$model);
	}


	/**
	 * @Description : Sửa lại comment
	 *
	 * @throws 	: NotFoundException
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxEditComment(){
		$this->layout = 'ajax';
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		//Doc noi dung
		$this->request->data = $this->Comment->read('Comment.*,Member.id,Member.name,Member.email',$_POST['id']);
		$this->request->data['Comment']['description'] = trim(strip_tags(str_replace(array('<br />','<br>'), '', $this->request->data['Comment']['description'])));
	}

	/**
	 * @Description : Cập nhật lại comment sau khi ấn submit
	 *
	 * @throws 	: NotFoundException
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxEditCommentStep2(){
		$this->layout = false;
		$this->autoRender = false;
		$error = false;
		if(!empty($this->request->data['Comment'])){
			$data = $this->request->data['Comment'];

			$data['description'] = nl2br($data['description']);
			if(	empty($data['name'])
				|| empty($data['description'])
				|| empty($data['email'])
				|| !$this->Oneweb->checkEmail($data['email'])
			) $error = true;

			if($this->Comment->save($data)) $error = true;
			else $error=false;
		}
		return $error;
	}


	/**
	 * @Description : Thêm comment
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxAddComment(){
		$this->layout = false;
		$this->autoRender = false;

		if ($this->request->is('post')) {
			$data = $this->request->data['Comment'];
			$a_errors['error'] = true;
			$a_errors['empty'] = '';

			if(empty($data['name'])) $a_errors['empty'] = 'tên';
			elseif(empty($data['email']) || (!empty($data['email']) && !$this->Oneweb->checkEmail($data['email']))) $a_errors['empty'] = 'email';
			elseif(empty($data['description'])) $a_errors['empty'] = 'nhận xét';
			if(empty($a_errors['empty'])){
				$lang = $this->Session->read('lang');
				$data['lang'] = $lang;
				$data['status'] = 1;
				$data['view'] = 1;
				$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
				$data['description'] = nl2br($data['description']);

				//Get Ip or Proxy
				if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
					$data['ip'] = $_SERVER["HTTP_X_FORWARDED_FOR"];
					$data['proxy'] = $_SERVER['REMOTE_ADDR'];
				}else{
					$data['ip'] = $_SERVER['REMOTE_ADDR'];
					$data['proxy'] = '';
				}

				$this->Comment->create();
				if($this->Comment->save($data)){
					$a_errors['error'] = false;
				}
			}
			return json_encode($a_errors);
		}
	}


	/**
	 * @Description : Xóa comment
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxDelComment(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		if($this->Comment->delete($_POST['id'])) return true;
		else return false;
	}
}
