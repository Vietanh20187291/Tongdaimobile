<?php
App::uses('AppController', 'Controller');
/**
 * Faqs Controller
 *
 * @property Faq $Faq
 */
class FaqsController extends AppController {
	
	public $helpers = array('CkEditor');
	private  $limit_admin = 50;



	/**
	 * @Description : Hỏi đáp
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function view() {
		$this->set('class','list_faq');
		
		$lang = $this->params['lang'];
		
		//Danh sách faqs
		$this->Faq->FaqCategory->bindModel(array(
			'hasMany'=>array(
				'Faq' => array(
					'className' => 'Faq',
					'foreignKey' => 'faq_category_id',
					'dependent' => true,
					'conditions' => array('status'=>1,'trash'=>0,'lang'=>$lang),
					'order' => array('sort'=>'asc','question'=>'asc'),
				)
			)
		));
		$a_faqs = $this->Faq->FaqCategory->find('all',array(
			'conditions'=>array('lang'=>$lang,'status'=>1,'trash'=>0),
			'order'=>array('sort'=>'asc','name'=>'asc')
		));
		
		//Faq thường gặp
		$a_most_faqs = $this->Faq->find('all',array(
			'conditions'=>array('Faq.lang'=>$lang,'Faq.status'=>1,'Faq.trash'=>0,'Faq.most'=>1,'FaqCategory.status'=>1,'FaqCategory.trash'=>0),
			'fields'=>array('id','question','slug','FaqCategory.id'),
			'order'=>array('Faq.sort'=>'asc','Faq.question'=>'asc'),
			'recursive'=>0
		));
		
		$this->set('a_faqs_c',$a_faqs);
		$this->set('a_most_faqs_c',$a_most_faqs);
		
		//Đọc cấu hình
		$a_configs = $this->_getConfig('faq');
		$this->set('a_configs_c',$a_configs);
		
		//Breadcrumb
		$a_breadcrumb[] = array(
								'name'=>__('Hỏi đáp'),
								'meta_title'=>__('Hỏi đáp'),
								'url'=>'',
							);
		$this->set('a_breadcrumb_c',$a_breadcrumb);
		
		//SEO
		$this->set('title_for_layout',$a_configs['meta_title']);
		$this->set('meta_keyword_for_layout',$a_configs['meta_keyword']);
		$this->set('meta_description_for_layout',$a_configs['meta_description']);
		$this->set('meta_robots_for_layout',$a_configs['meta_robots']);
		
		//Canonical
		$a_canonical = array('controller'=>'faqs','action' => 'view','lang'=>$lang);
		$this->set('a_canonical',$a_canonical);
	}


	
	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/
	
	/**
	 * @Description : Danh sách FAQs
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_index() {
		$lang = $this->Session->read('lang');
		$a_conditions = array('Faq.lang'=>$lang,'Faq.trash'=>0);
		
		
		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->Faq->id = $val;
						$this->Faq->set(array('status'=>1));
						$this->Faq->save();
					}
					$message = __('FAQs đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->Faq->id = $val;
						$this->Faq->set(array('status'=>0));
						$this->Faq->save();
					}
					$message = __('FAQs đã được bỏ kích hoạt');
					break;
				case 'trashes':
					foreach ($_POST['chkid'] as $val){
						$this->trashItem($val);
					}
					$message = __('FAQs đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success')); 
		}
		
		if(!empty($_GET['category_id'])){	//Danh muc
			$this->request->data['Faq']['category_id'] = $_GET['category_id'];
			$a_conditions = array_merge($a_conditions,array('faq_category_id'=>$_GET['category_id']));
		}
		if(!empty($_GET['keyword']) && $_GET['keyword']!=__('Tìm kiếm')){	//Tu khoa
			$a_conditions = array_merge($a_conditions,array('Faq.question like'=>'%'.$_GET['keyword'].'%'));
		}
		
		
		$this->paginate = array(
			'conditions'=>$a_conditions,
			'fields'=>array(
							'id','question','answer','sort','most','status','lang','created',
							'FaqCategory.id','FaqCategory.name','FaqCategory.trash','FaqCategory.status'
						),
			'order'=>array('FaqCategory.sort'=>'asc','Faq.sort'=>'asc'),
			'limit'=>$this->limit_admin,
			'recursive'=>0
		);
		
		$a_faqs = $this->paginate();
		$this->set('a_faqs_c', $a_faqs);
		
		$counter = $this->Faq->find('count',array('conditions'=>$a_conditions,'recursive'=>-1));
		$this->set('counter_c',$counter);
		
		//Danh sach danh muc
		$a_list_categories = $this->Faq->FaqCategory->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0),'order'=>'sort asc'));
		$this->set('a_list_categories_c',$a_list_categories);
		
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
		
		return json_encode($return);
	}
	
	/**
	 * @Description : Sắp xếp FAQs
	 *
	 * @throws 	: NotFoundException
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	function admin_ajaxChangeSort(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['val']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		
		$this->Faq->id = $_POST['id'];
		$this->Faq->set(array('sort'=>$_POST['val']));
		$this->Faq->save();
		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
	}
	
	
	/**
	 * @Description : Thêm FAQs
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		$lang = $this->Session->read('lang');
		if ($this->request->is('post')) {
			$data = $this->request->data['Faq'];
			$oneweb_seo = Configure::read('Seo');
			$data['question'] = nl2br($data['question']);
			
			//Slug
			if($oneweb_seo) if(empty($data['slug'])) $data['slug'] = $data['question'];
			else $data['slug'] = $data['question'];
			
			//Lấy danh sách slug đã tồn tại
			$a_all_slugs = $this->Faq->find('list',array('conditions'=>array('lang'=>$lang),'fields'=>'slug'));
			
			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);
			
			
			//Ngay tao
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			//Sắp xếp
			if(empty($data['sort'])) $data['sort'] = $this->Faq->find('count',array('conditions'=>array('lang'=>$lang),'recursive'=>-1))+1;
			
			//Ngôn ngữ
			$data['lang'] = $lang;
			$this->Faq->create();
			if ($this->Faq->save($data)) {
				$id = $this->Faq->getLastInsertID();
				
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
		$a_list_categories = $this->Faq->FaqCategory->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0)));
		$this->set('a_list_categories_c',$a_list_categories);
		$this->set(compact('a_categories_c', 'a_makers_c', 'a_taxes_c'));
	}

	/**
	 * @Description : Sửa FAQs
	 *
	 * @throws NotFoundException
	 * @param int $id
	 * @return void
	 * @Author Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_edit($id = null) {
		$this->Faq->id = $id;
		if (!$this->Faq->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['Faq'];
			
			//Slug
			if($oneweb_seo) if(empty($data['slug'])) $data['slug'] = $data['question'];
			else $data['slug'] = $data['question'];
			
			//Lấy danh sách slug đã tồn tại
			$a_all_slugs = $this->Faq->find('list',array('conditions'=>array('lang'=>$lang,'id !='=>$id),'fields'=>'slug','recursive'=>-1));
			
			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);
			
			//Sắp xếp
			$this->Faq->recursive = -1;
			$a_faq = $this->Faq->read('sort',$id);
			$a_faq = $a_faq['Faq'];
			if(empty($data['sort'])) $data['sort'] = $a_faq['sort'];
			
			//Ngày sửa
			$data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			if ($this->Faq->save($data)) {
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
			$this->request->data = $this->Faq->read(null, $id);
			$this->request->data['Faq']['question'] = str_replace(array('<br />','<br>'), '', $this->request->data['Faq']['question']);
		}
		
		//Danh sach danh muc
		$a_list_categories = $this->Faq->FaqCategory->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0)));
		$this->set('a_list_categories_c',$a_list_categories);
		
		$this->set(compact('a_categories_c'));
	}
	
	
	/**
	 * @Description : Cho faq vào thùng rác
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
	 * @Description : Đưa faq vào thùng rac
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int data
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function trashItem($id){
		//Thông tin
		$this->Faq->recursive = -1;
		$a_faq = $this->Faq->read('id,question',$id);
		$item_faq = $a_faq['Faq'];
		
		//Ghi vào bảng Trash
		$data['name'] = $item_faq['question'];
		$data['item_id'] = $item_faq['id'];
		$data['model'] = 'Faq';
		$data['description'] = 'Faq';
		$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

		$this->loadModel('Trash');
		$this->Trash->create();
		if($this->Trash->save($data)){
			$this->Faq->id = $id;
			$this->Faq->set(array('trash'=>1));
			if($this->Faq->save()){
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				return true;
			}
		}
		return false;
	}
}
