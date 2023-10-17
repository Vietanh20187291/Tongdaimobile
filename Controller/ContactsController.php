<?php
App::uses('AppController', 'Controller');
/**
 * Contacts Controller
 *
 * @property Contact $Contact
 */
class ContactsController extends AppController {

	public $helpers = array('CkEditor');
	public $components = array('Recaptcha.Recaptcha');
	private  $limit_admin = 50;


	/**
	 * @Description : Trang liên hệ
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function index(){
		
		$this->set('class','contact');
		$lang = $this->params['lang'];

		if ($this->request->is('post')) {
			if ($this->Recaptcha->verify()) {
				$data = $this->request->data['Contact'];

				//Lọc ký tự đăc biệt
				if (isset($data['subject'])) {
					$data['subject'] = $this->Oneweb->htmlEncode($data['subject']);
				} else $data['subject'] = __('Liên hệ người dùng');
				if (isset($data['name'])) $data['name'] = $this->Oneweb->htmlEncode($data['name']);
				if (isset($data['email'])) $data['email'] = $this->Oneweb->htmlEncode($data['email']);
				if (isset($data['address'])) $data['address'] = $this->Oneweb->htmlEncode($data['address']);
				if (isset($data['phone'])) $data['phone'] = $this->Oneweb->htmlEncode($data['phone']);
				if (isset($data['message'])) $data['message'] = $this->Oneweb->htmlEncode($data['message']);

				$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

				//Get Ip or Proxy
			if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
				$data['ip'] = $_SERVER["HTTP_X_FORWARDED_FOR"];
				$data['proxy'] = $_SERVER['REMOTE_ADDR'];
			}else{
				$data['ip'] = $_SERVER['REMOTE_ADDR'];
				$data['proxy'] = '';
			}

			$data['view'] = 0;
			$data['alarm'] = 0;

			//Lấy id đàu tiên của danh mục liên hệ
			$a_category = $this->Contact->ContactCategory->find('first',array(
				'conditions'=>array('set_default'=>1),
				'fields'=>array('id'),
				'order'=>'sort asc'
			));

			$data['contact_category_id'] = (!empty($a_category))?$a_category['ContactCategory']['id']:'1';

			//Lưu vào CSDL
			$this->Contact->create();
			if($this->Contact->save($data)){

				$this->_sendEmail($data);
				$this->Session->setFlash('<span>'.__('Cảm ơn bạn đã liên hệ với chung tôi, chúng tôi sẽ sớm trả lời bạn.',true).'</span>','default',array('class'=>'success'));
				$this->redirect($this->referer());
			}else{
				$this->Session->setFlash('<span>'.__('Có lỗi, bạn vui lòng thử lại.',true).'</span>','default',array('class'=>'error'));
			}

			} else {
				// display the raw API error
				$this->Session->setFlash('<span>'.$this->Recaptcha->error.'</span>','default',array('class'=>'error'));
			}
		}

		//Đọc cấu hình
		$a_configs = $this->_getConfig('contact');
		$this->set('a_configs_c',$a_configs);

		//Breadcrumb
		$a_breadcrumb[] = array(
								'name'=>__('Liên hệ'),
								'meta_title'=>__('Liên hệ'),
								'url'=>'',
							);
		$this->set('a_breadcrumb_c',$a_breadcrumb);

		//SEO
		if ( ! empty($a_configs['meta_title'])) $this->set('title_for_layout',$a_configs['meta_title']);
		if ( ! empty($a_configs['meta_keyword'])) $this->set('meta_keyword_for_layout',$a_configs['meta_keyword']);
		if ( ! empty($a_configs['meta_description'])) $this->set('meta_description_for_layout',$a_configs['meta_description']);
		if ( ! empty($a_configs['meta_robots'])) $this->set('meta_robots_for_layout',$a_configs['meta_robots']);
		//Canonical
		$a_canonical = array('controller'=>'contacts','action' => 'index','lang'=>$lang,'ext'=>'html');
		$this->set('a_canonical',$a_canonical);
	}

	// Liên hệ tư vấn tại trang chi tiết sản phẩm
	public function request_support() {
		$lang = $this->params['lang'];

		if ($this->request->is('post')) {
			$data = $this->request->data['Contact'];

			// Lọc ký tự đăc biệt
			if(!empty($data['subject']))
				$data['subject'] = $this->Oneweb->htmlEncode($data['subject']);
			else
				$data['subject'] = 'Yêu cầu tư vấn';
			if(!empty($data['name']))
				$data['name'] = $this->Oneweb->htmlEncode($data['name']);
			else
				$data['name'] = 'Không tên';
			if(isset($data['email']))
				$data['email'] = $this->Oneweb->htmlEncode($data['email']);
			else
				$data['email'] = '';
			if(isset($data['message']))
				$data['message'] = $this->Oneweb->htmlEncode($data['message']);
			else
				$data['message'] = '';
			if(!empty($data['phone']))
				$data['phone'] = $this->Oneweb->htmlEncode($data['phone']);

			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

			//Get Ip or Proxy
			if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
				$data['ip'] = $_SERVER["HTTP_X_FORWARDED_FOR"];
				$data['proxy'] = $_SERVER['REMOTE_ADDR'];
			}else{
				$data['ip'] = $_SERVER['REMOTE_ADDR'];
				$data['proxy'] = '';
			}

			$data['view'] = 0;
			$data['alarm'] = 0;

			//Lấy id đàu tiên của danh mục liên hệ
			$a_category = $this->Contact->ContactCategory->find('first',array(
				'conditions'=>array('set_default'=>1),
				'fields'=>array('id'),
				'order'=>'sort asc'
			));

			$data['contact_category_id'] = (!empty($a_category))?$a_category['ContactCategory']['id']:'1';

			// Lưu vào CSDL
			$this->Contact->create();
			if($this->Contact->save($data)){

				$this->_sendEmail($data);
				$this->Session->setFlash('<span>'.__('Cảm ơn bạn đã gửi yêu cầu, chúng tôi sẽ sớm liên hệ để tư vấn bạn.').'</span>','default',array('class'=>'success'));
				$this->redirect($this->referer());
			}else{
				$this->Session->setFlash('<span>'.__('Có lỗi, bạn vui lòng thử lại.').'</span>','default',array('class'=>'error'));
			}
			$this->redirect($this->referer());
		}
	}

	/**
	 * @Description : Gửi email đến quản trị và khách hàng
	 *
	 * @param 	: array $data
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _sendEmail($data){
		$lang = $this->params['lang'];

		//Đọc cấu hình
		$a_configs = $this->_getConfig('email');

		App::uses('CakeEmail', 'Network/Email');

		$config = array();
		if(strtolower($a_configs['smtp_transport']) == 'smtp'){
			$config = array(
				'host' 		=> 	$a_configs['smtp_host'],
				'port' 		=> 	$a_configs['smtp_port'],
				'timeout' 	=> 	30,
				'username' 	=> 	$a_configs['smtp_username'],
				'password' 	=> 	$a_configs['smtp_password'],
				'sender'	=>	$a_configs['smtp_username']
			);
		};

		$config = array_merge($config,array(
											'transport'=>$a_configs['smtp_transport'],
											'emailFormat'=>'html',
											'template'=>'contact',
											'layout'=>'index'
										));

		//Gửi cho quản trị
		if(!empty($data['email'])){
			$Email = new CakeEmail();
			$Email->config(array_merge($config,array(
												'from'=>array($a_configs['contact'] => $_SERVER['HTTP_HOST']),
												'replyTo'=>$data['email'],
												'to'=>$a_configs['contact'],
												'subject'=>$data['subject'],
												'viewVars'=>array('data'=>$data,'config'=>$a_configs,'admin'=>true)
											)));

			$Email -> send();
		};

		if(!empty($data['email'])){
			//Gửi cho khách hàng
			$Email2 = new CakeEmail();
			$Email2->config(array_merge($config,array(
											'from'=>array($a_configs['contact'] => $_SERVER['HTTP_HOST']),
											'replyTo'=>$a_configs['contact'],
											'to'=>$data['email'],
											'subject'=>$a_configs['contact_subject'],
											'viewVars'=>array('data'=>$data,'config'=>$a_configs,'admin'=>false)
										)));

			$Email2 -> send();
		}
	}



	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/

	/**
	 * @Description : Danh sách liên hệ
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_index() {
		$a_conditions = array();

		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'view':
					foreach ($_POST['chkid'] as $val){
						$this->Contact->id = $val;
						$this->Contact->set(array('view'=>1));
						$this->Contact->save();
					}
					$message = __('Đơn hàng đã thiết lập đã đọc');
					break;
				case 'unview':
					foreach ($_POST['chkid'] as $val){
						$this->Contact->id = $val;
						$this->Contact->set(array('view'=>0));
						$this->Contact->save();
					}
					$message = __('Đơn hàng đã thiết lập chưa đọc');
					break;
				case 'del':
					foreach ($_POST['chkid'] as $val){
						$this->Contact->delete($val);
					}
					$message = __('Đơn hàng đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}

		if(!empty($_GET['category_id'])){	//Danh muc
			$this->request->data['Contact']['category_id'] = $_GET['category_id'];
			$a_conditions = array_merge($a_conditions,array('contact_category_id'=>$_GET['category_id']));
		}
		if(!empty($_GET['keyword']) && $_GET['keyword']!=__('Tiêu đề, Họ tên, Email, Phone')){	//Tu khoa
			$a_conditions = array_merge($a_conditions,array('or'=>array(
																'Contact.name like'=>'%'.$_GET['keyword'].'%',
																'subject like'=>'%'.$_GET['keyword'].'%',
																'email like'=>'%'.$_GET['keyword'].'%',
																'phone like'=>'%'.$_GET['keyword'].'%'
															)));
		}


		$this->paginate = array(
			'conditions'=>$a_conditions,
			'fields'=>array(
							'id','subject','name','email','phone','view','created',
							'ContactCategory.id','ContactCategory.name'
						),
			'order'=>array('Contact.created'=>'desc','Contact.name'=>'asc'),
			'limit'=>$this->limit_admin
		);

		$a_contacts = $this->paginate();
		$this->set('a_contacts_c', $a_contacts);

		$counter = $this->Contact->find('count',array('conditions'=>$a_conditions,'recursive'=>-1));
		$this->set('counter_c',$counter);

		//Danh sach danh muc - list
		$this->Contact->ContactCategory->bindModel(array(
			'hasMany'=>array(
				'Contact' => array(
								'className' => 'Contact',
								'foreignKey' => 'contact_category_id',
								'dependent' => false,
								'fields' => 'id',
							)
						)
		));
		$a_list_categories = $this->Contact->ContactCategory->find('all',array('order'=>'sort asc'));
		$a_list_categories_c = array();		//Danh sach ra noi dung
		$a_list_categories_s = array();		//Danh sach ra sidebar

		$total = 0;
		foreach($a_list_categories as $val){
			$item_contact = $val['Contact'];
			$item_cate = $val['ContactCategory'];
			$a_list_categories_s[$item_cate['id']] = $item_cate['name'].' ('.count($item_contact).')';
			$a_list_categories_c[$item_cate['id']] = $item_cate['name'];
			$total+=count($item_contact);
		}
		$this->set('a_list_categories_c',$a_list_categories_c);
		$this->set('a_list_categories_s',$a_list_categories_s);
		$this->set('total_contact_s',$total);


		//Set đã thông báo chuông
		$this->Contact->updateAll(array('alarm'=>1),array('alarm'=>0));

		//Url hiện tại
		$current_url = urlencode($this->Oneweb->curPageURL());
		$this->set('current_url_c',$current_url);
	}

	/**
	 * @Description : Thiết lập danh mục cho đơn hàng
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxSetCategory(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['contact_id']) || empty($_POST['cate_id'])) throw new NotFoundException(__('Invalid'));

		$this->Contact->id = $_POST['contact_id'];
		$this->Contact->set(array('contact_category_id'=>$_POST['cate_id']));
		if($this->Contact->save()) return true;
		else return false;
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
	 * @Description : Xóa liên hệ sdung ajax
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxDeleteItem() {
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		if($this->Contact->delete($_POST['id'])) return true;
		else return false;
	}

	/**
	 * @Description : Sửa liên hệ
	 *
	 * @throws NotFoundException
	 * @param int $id
	 * @return void
	 * @Author Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_view($id = null) {
		$this->Contact->id = $id;
		if (!$this->Contact->exists()) throw new NotFoundException(__('Invalid'));

		$a_contact = $this->Contact->read(null, $id);
		$this->set('a_contact_c',$a_contact);

		//Set da doc
		$this->Contact->id = $id;
		$this->Contact->set(array('view'=>1));
		$this->Contact->save();

		//Danh sach danh muc - list
		$this->Contact->ContactCategory->bindModel(array(
			'hasMany'=>array(
				'Contact' => array(
								'className' => 'Contact',
								'foreignKey' => 'contact_category_id',
								'dependent' => false,
								'fields' => 'id',
							)
						)
		));
		$a_list_categories = $this->Contact->ContactCategory->find('all',array('order'=>'sort asc'));
		$a_list_categories_c = array();		//Danh sach ra noi dung
		$a_list_categories_s = array();		//Danh sach ra sidebar

		$total = 0;
		foreach($a_list_categories as $val){
			$item_contact = $val['Contact'];
			$item_cate = $val['ContactCategory'];
			$a_list_categories_s[$item_cate['id']] = $item_cate['name'].' ('.count($item_contact).')';
			$a_list_categories_c[$item_cate['id']] = $item_cate['name'];
			$total+=count($item_contact);
		}
		$this->set('a_list_categories_c',$a_list_categories_c);
		$this->set('a_list_categories_s',$a_list_categories_s);
		$this->set('total_contact_s',$total);

		$this->set(compact('a_categories_c'));
	}
}
