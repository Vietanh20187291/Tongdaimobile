<?php
App::uses('AppController', 'Controller');
/**
 * Contacts Controller
 *
 * @property Contact $Contact
 */
class ContactFormsController extends AppController {

	public $helpers = array('CkEditor');
	public $components = array('Recaptcha.Recaptcha');
	private  $limit_admin = 50;


	/**
	 * form đăng ký tư vấn
	 * @return [type] [description]
	 */
	public function registv() {
		$this->layout = 'frontend/template1/contact';
		$lang = $this->params['lang'];

		if ($this->request->is('post')) {
			$data = $this->request->data['ContactForm'];

			// Lọc ký tự đăc biệt
			if(!empty($data['name'])) $data['name'] = $this->Oneweb->htmlEncode($data['name']);
			if(!empty($data['phone'])) $data['phone'] = $this->Oneweb->htmlEncode($data['phone']);
			if(!empty($data['email'])) $data['email'] = $this->Oneweb->htmlEncode($data['email']);
			if(isset($data['address'])) $data['address'] = $this->Oneweb->htmlEncode($data['address']);
			if(isset($data['type'])) $data['type'] = $this->Oneweb->htmlEncode($data['type']);
			if(isset($data['message'])) $data['message'] = $this->Oneweb->htmlEncode($data['message']);
			if(isset($data['product_name'])) $data['product_name'] = $this->Oneweb->htmlEncode($data['product_name']);
			if(isset($data['product_id'])) $data['product_id'] = $this->Oneweb->htmlEncode($data['product_id']);


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
// debug($data);die;

			// Lưu vào CSDL
			$this->ContactForm->create();
			if($this->ContactForm->save($data)){

				// $this->_sendEmail($data);
				$this->Session->setFlash('<span>'.__('Cảm ơn Quý Khách đã gửi thông tin, chuyên viên Hinlet sẽ sớm liên hệ lại.').'</span>','default',array('class'=>'alert alert-success'));
				$this->redirect($this->referer());
			}else{
				$this->Session->setFlash('<span>'.__('Có lỗi, bạn vui lòng thử lại.').'</span>','default',array('class'=>'alert alert-danger'));
			}
			$this->redirect($this->referer());
		}


		$this->set('title_for_layout', 'Đăng ký tư vấn nhanh');
	}

	/**
	 * Form nhận quà tặng
	 * @return [type] [description]
	 */
	function gift()
	{
		$this->layout = 'frontend/template1/contact';
		$lang = $this->params['lang'];

		if ($this->request->is('post')) {
			$data = $this->request->data['ContactForm'];

			// Lọc ký tự đăc biệt
			if(!empty($data['name'])) $data['name'] = $this->Oneweb->htmlEncode($data['name']);
			if(!empty($data['phone'])) $data['phone'] = $this->Oneweb->htmlEncode($data['phone']);
			if(!empty($data['email'])) $data['email'] = $this->Oneweb->htmlEncode($data['email']);
			if(isset($data['address'])) $data['address'] = $this->Oneweb->htmlEncode($data['address']);
			if(isset($data['type'])) $data['type'] = $this->Oneweb->htmlEncode($data['type']);
			if(isset($data['message'])) $data['message'] = $this->Oneweb->htmlEncode($data['message']);
			if(isset($data['product_name'])) $data['product_name'] = $this->Oneweb->htmlEncode($data['product_name']);
			if(isset($data['product_id'])) $data['product_id'] = $this->Oneweb->htmlEncode($data['product_id']);


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


			// Lưu vào CSDL
			$this->ContactForm->create();
			if($this->ContactForm->save($data)){

				// $this->_sendEmail($data);
				$this->Session->setFlash('<span>'.__('Cảm ơn Quý Khách đã gửi thông tin, chuyên viên Hinlet sẽ sớm liên hệ lại.').'</span>','default',array('class'=>'alert alert-success'));
				$this->redirect($this->referer());
			}else{
				$this->Session->setFlash('<span>'.__('Có lỗi, bạn vui lòng thử lại.').'</span>','default',array('class'=>'alert alert-danger'));
			}
			$this->redirect($this->referer());
		}

		$this->set('title_for_layout', 'Nhận quà tặng');
	}

	/**
	 * Form đăng ký tham gia sự kiện
	 * @return [type] [description]
	 */
	function event()
	{
		$this->layout = 'frontend/template1/contact';
		$lang = $this->params['lang'];

		if ($this->request->is('post')) {
			$data = $this->request->data['ContactForm'];

			// Lọc ký tự đăc biệt
			if(!empty($data['name'])) $data['name'] = $this->Oneweb->htmlEncode($data['name']);
			if(!empty($data['phone'])) $data['phone'] = $this->Oneweb->htmlEncode($data['phone']);
			if(!empty($data['email'])) $data['email'] = $this->Oneweb->htmlEncode($data['email']);
			if(isset($data['address'])) $data['address'] = $this->Oneweb->htmlEncode($data['address']);
			if(isset($data['type'])) $data['type'] = $this->Oneweb->htmlEncode($data['type']);
			if(isset($data['message'])) $data['message'] = $this->Oneweb->htmlEncode($data['message']);
			if(isset($data['product_name'])) $data['product_name'] = $this->Oneweb->htmlEncode($data['product_name']);
			if(isset($data['product_id'])) $data['product_id'] = $this->Oneweb->htmlEncode($data['product_id']);


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

			// Lưu vào CSDL
			$this->ContactForm->create();
			if($this->ContactForm->save($data)){

				// $this->_sendEmail($data);
				$this->Session->setFlash('<span>'.__('Cảm ơn Quý Khách đã gửi thông tin, chuyên viên Hinlet sẽ sớm liên hệ lại.').'</span>','default',array('class'=>'alert alert-success'));
				$this->redirect($this->referer());
			}else{
				$this->Session->setFlash('<span>'.__('Có lỗi, bạn vui lòng thử lại.').'</span>','default',array('class'=>'alert alert-danger'));
			}
			$this->redirect($this->referer());
		}

		$this->set('title_for_layout', 'Đăng ký tham gia sự kiện');
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
		try {
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
		} catch (Exception $e){
			$this->log($e->getMessage());
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
		$a_conditions = array('type'=>'registv');
		if(!empty($_GET['type'])) $a_conditions = array('type'=>$_GET['type']);
		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'view':
					foreach ($_POST['chkid'] as $val){
						$this->ContactForm->id = $val;
						$this->ContactForm->set(array('view'=>1));
						$this->ContactForm->save();
					}
					$message = __('Đơn hàng đã thiết lập đã đọc');
					break;
				case 'unview':
					foreach ($_POST['chkid'] as $val){
						$this->ContactForm->id = $val;
						$this->ContactForm->set(array('view'=>0));
						$this->ContactForm->save();
					}
					$message = __('Đơn hàng đã thiết lập chưa đọc');
					break;
				case 'del':
					foreach ($_POST['chkid'] as $val){
						$this->ContactForm->delete($val);
					}
					$message = __('Đơn hàng đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}

		if(!empty($_GET['keyword'])){	//Tu khoa
			$a_conditions = array_merge($a_conditions,array('or'=>array(
																'ContactForm.name like'=>'%'.$_GET['keyword'].'%',
																'email like'=>'%'.$_GET['keyword'].'%',
																'phone like'=>'%'.$_GET['keyword'].'%'
															)));
		}


		$this->paginate = array(
			'conditions'=>$a_conditions,
			'order'=>array('ContactForm.created'=>'desc','ContactForm.name'=>'asc'),
			'limit'=>$this->limit_admin
		);

		$a_contacts = $this->paginate();
		$this->set('a_contacts_c', $a_contacts);

		$counter = $this->ContactForm->find('count',array('conditions'=>$a_conditions,'recursive'=>-1));
		$this->set('counter_c',$counter);


		$total = 0;
		$this->set('total_contact_s',$total);


		//Set đã thông báo chuông
		$this->ContactForm->updateAll(array('alarm'=>1),array('alarm'=>0));

		//Url hiện tại
		$current_url = urlencode($this->Oneweb->curPageURL());
		$this->set('current_url_c',$current_url);
	}

	public function admin_getcode() {

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

		if($this->ContactForm->delete($_POST['id'])) return true;
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
		$this->ContactForm->id = $id;
		if (!$this->ContactForm->exists()) throw new NotFoundException(__('Invalid'));

		$a_contact = $this->ContactForm->read(null, $id);
		$this->set('a_contact_c',$a_contact);

		//Set da doc
		$this->ContactForm->id = $id;
		$this->ContactForm->set(array('view'=>1));
		$this->ContactForm->save();

	}
}
