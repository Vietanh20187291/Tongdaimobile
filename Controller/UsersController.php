<?php
class UsersController extends AppController {

	public $components = array('Oneweb','Recaptcha.Recaptcha');
	public $ad_limit = 20;

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('admin_login');
		$this->Auth->allow('admin_forgetPassword');
		$this->Auth->allow('admin_confirmResetPassword');
	}

	/*
	* @Description	: Đăng nhập
	*
	* @Author	: Hoang Tuan Anh - tuananh@url.vn
	*/
	public function admin_login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
// 				$data = $this->request->data['User'];
// 				$admin = $this->Auth->user();

// 				if($data['remember']){			//Ghi nhớ tài khoản
// 					$this->Cookie->name = 'oneweb';
// 					$this->Cookie->time = '10 days';
// 					$this->Cookie->path = '/bakers/preferences/';
// 					$this->Cookie->domain = 'example.com';
// 					$this->Cookie->secure = true;  // i.e. only sent if using secure HTTPS
// 					$this->Cookie->key = 'kjfdljiou39099083kjjklj@#^&!*&*&$^&*&98hkjfhdjk';
// 					$this->Cookie->httpOnly = true;

// 					$this->Cookie->write('auth_remember', $admin, false, 3600);
// 				}

				$url = $this->Auth->redirect();
				$url = ($url=='/')?array('controller'=>'pages','action'=>'index'):$url;
				$this->redirect($url);
			} else {
				$this->Session->setFlash(__('Thông tin truy cập không đúng'));
			}
		}
	}

	/*
		* @Description :
		* @param - string :
		* @param - interger:
		* @param - array:
		* @return - array:
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function admin_forgetPassword(){
		$this->layout = 'backend/login';
		if ($this->request->is('post')) {
			if ($this->Recaptcha->verify()) {
				$this->request->data['User']['email']=$this->Oneweb->htmlEncode($this->request->data['User']['email']);
				$data = $this->request->data['User'];
				if($this->User->validates(array('fieldList'=>array('email')))){
					$a_user = $this->User->find('first',array(	'conditions'=>array('email'=>$data['email']),
							'fields'=>array('id','password','email')
					));
					if(!empty($a_user)){
						$this->User->id = $a_user['User']['id'];
						$token = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						$this->User->set(array('token'=>$token));
						if($this->User->save()){
							$a_user['User']['token'] = $token;
							$this->_sendEmailResset($a_user);
							$this->Session->setFlash('<span>'.__('Thông tin xác nhận đã được gửi tới Email, bạn vui lòng kiểm tra lại Email').'</span>','default',array('class'=>'success'));
						}else $this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
					}else{
						$this->Session->setFlash('<span>'.__('Email này chưa được đăng ký').'</span>','default',array('class'=>'error'));
					}
					$this->redirect($this->referer());
				}

			}else {
				// display the raw API error
				$this->Session->setFlash('<span>'.$this->Recaptcha->error.'</span>','default',array('class'=>'error'));
			}
		}
	}
	/*
		* @Description :
		* @param - string :
		* @param - interger:
		* @param - array:
		* @return - array:
		* @Author : HuuQuynh - quynh@url.vn
		*/
	private function _sendEmailResset($data = null){
		if($data==null) throw new NotFoundException(__('Trang này không tồn tại',true));

		$a_configs = $this->_getConfigUser('email');
		App::uses('CakeEmail', 'Network/Email');
		$config = array();
		if($a_configs['smtp_transport']=='Smtp'){
			$config = array(
					'host' 		=> 	$a_configs['smtp_host'],
					'port' 		=> 	$a_configs['smtp_port'],
					'timeout' 	=> 	30,
					'username' 	=> 	$a_configs['smtp_username'],
					'password' 	=> 	$a_configs['smtp_password'],
					'template'	=>	'contact',
					'layout'	=>	'index',
					'sender'	=>	$a_configs['smtp_username']
			);
		}

		$config = array_merge($config,array(
				'transport'=>$a_configs['smtp_transport'],
				'emailFormat'=>'html',
				'template'=>'user_reset_password',
				'layout'=>'index'
		));

// 		debug($a_configs);die;

		//Gửi cho nguoi dung
		$Email = new CakeEmail();
		$Email->config(array_merge($config,array(
				'from'=>array($a_configs['contact'] => $_SERVER['HTTP_HOST']),
				'replyTo'=>$a_configs['contact'],
				'to'=>$data['User']['email'],
				'subject'=>$a_configs['user_subject'],
				'viewVars'=>array('data'=>$data['User'],'config'=>$a_configs,'admin'=>false)
		)));
		$Email -> send();
	}

	private function _getConfigUser($prefix){
		$lang = $this->Session->read('lang');
		$result = Cache::read('config_'.$prefix.'_'.$lang,'oneweb');
		if(!$result){
			$a_configs = $this->Config->find('all',array('conditions'=>array('name like'=>$prefix.'_%')));

			$result = array();
			foreach ($a_configs as $val){
				$item = $val['Config'];
				$key = substr($item['name'], stripos($item['name'], '_')+1);

				if(@unserialize($item['value'])==false){			//Gía trị ko là chuỗi đặc biệt
					$result[$key] = $item['value'];
				}else{												//Giá trị là chuỗi đặc biệt
					$tmp = unserialize($item['value']);
					$result[$key] = $tmp[$lang];
				}
			}

			Cache::write('config_'.$prefix.'_'.$lang,$result,'oneweb');
		}
		return $result;
	}
	/*
		* @Description :
		* @param - string :
		* @param - interger:
		* @param - array:
		* @return - array:
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function admin_confirmResetPassword($user_id=null,$token=null){
		$this->layout = 'backend/login';
		if($user_id==null || $token==null) throw new NotFoundException(__('Trang này không tồn tại',true));
		$date_current = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));		//Ngay hien tai

		//Kiểm tra request đên có phải trong 24h từ khi y/c thay đổi ko
		if($date_current-$token>(24*60*60)) throw new NotFoundException(__('Trang này không tồn tại',true));

		//Kiểm tra xem có tồn tại thành viên này ko
		$a_user = $this->User->find('first',array('conditions'=>array('id'=>$user_id,'token'=>$token),'fields'=>array('id'),'fields'=>'id','recursive'=>-1));
		$success = false;
		if(!empty($a_user)){
			$this->User->id = $a_user['User']['id'];
			$this->User->set(array('token'=>null));
			if($this->User->save()){
				if($this->_resetPass($a_user['User']['id'])){
					$success = true;
				}
			}else{
				$this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
			}

		}

		$this->set('success_c',$success);
	}
	/*
		* @Description :
		* @param - string :
		* @param - interger:
		* @param - array:
		* @return - array:
		* @Author : HuuQuynh - quynh@url.vn
		*/
	private function _resetPass($id=null){
		if($id==null) throw new NotFoundException(__('Trang này không tồn tại',true));
		//Set lai mat khau
		$pass_new = substr(rand(1000000000, 9999999999), 1,10);
		$pass_encode = $this->Auth->password($pass_new);
		$this->User->id = $id;
		$this->User->set(array('password'=>$pass_encode));

		if($this->User->save()){
			$a_data = $this->User->read(null,$id);
			//Gui mail
			$a_configs = $this->_getConfigUser('email');
			App::uses('CakeEmail', 'Network/Email');
			$config = array();
			if($a_configs['smtp_transport']=='Smtp'){
				$config = array(
						'host' 		=> 	$a_configs['smtp_host'],
						'port' 		=> 	$a_configs['smtp_port'],
						'timeout' 	=> 	30,
						'username' 	=> 	$a_configs['smtp_username'],
						'password' 	=> 	$a_configs['smtp_password'],
						'template'	=>	'contact',
						'layout'	=>	'index',
						'sender'	=>	$a_configs['smtp_username']
				);
			}

			$config = array_merge($config,array(
					'transport'=>$a_configs['smtp_transport'],
					'emailFormat'=>'html',
					'template'=>'us_reset_password',
					'layout'=>'index'
			));

			//Gửi cho khách hàng
			$Email = new CakeEmail();
			$Email->config(array_merge($config,array(
					'from'=>array($a_configs['contact'] => $_SERVER['HTTP_HOST']),
					'replyTo'=>$a_configs['contact'],
					'to'=>$a_data['User']['email'],
					'subject'=>$a_configs['user_subject'],
					'viewVars'=>array('data'=>$a_data,'pass_new_c'=>$pass_new,'config'=>$a_configs,'admin'=>false)
			)));
			$Email -> send();
			return true;
		}else{
			return false;
		}
	}

	/*
	* @Description	: Đăng xuất
	*
	* @Author	: Hoang Tuan Anh - tuananh@url.vn
	*/
	public function admin_logout() {
		$this->_deleteCache();

		$this->redirect($this->Auth->logout());
	}


	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/

	/**
	 * @Description : Danh sách tài khoản
	 *
	 * @throws 	: NotFoundException
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_index() {
		$admin = $this->Auth->user();
		if ($admin['role'] != 'admin') throw new NotFoundException(__('Trang này không tồn tại',true));

		$this->User->recursive = 0;
		$this->paginate = array(
			'order'=>'name asc',
			'limit'=>$this->ad_limit
		);
		$this->set('a_users_c', $this->paginate());

		//Url hiện tại
		$current_url = urlencode($this->Oneweb->curPageURL());
		$this->set('current_url_c',$current_url);
	}


	/**
	 * @Description : Thêm tài khoản
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		$admin = $this->Auth->user();
		if ($admin['role'] != 'admin') throw new NotFoundException(__('Trang này không tồn tại',true));

		if ($this->request->is('post')) {
			$data = $this->request->data['User'];

			//Kiểm tra tài khoản đã tồn tại chưa
			$check_username = $this->User->find('count',array('conditions'=>array('username'=>$data['username'],'email'=>$data['email'])));

			$error = false;
			if(!empty($check_username)){
				$this->Session->setFlash('<span>'.__('Tài khoản hoặc Email đã có người sử dụng').'</span>','default',array('class'=>'error'));
				$error = true;
			}elseif($data['password']!=$data['password_confirm']){
				$this->Session->setFlash('<span>'.__('Sai mật khẩu xác nhận').'</span>','default',array('class'=>'error'));
				$error = true;
			}

			if(!$error){
				$data['password'] = $this->Auth->password($data['password']);
				$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

				if ($data['group_id'] == 1) $data['role'] = 'admin';
				else $data['role'] = 'staff';

				$this->User->create();
				if ($this->User->save($data)) {
					$id = $this->User->getLastInsertID();

					$this->Session->setFlash('<span>'.__('Thêm mới thành công').'</span>','default',array('class'=>'success'));

					if (isset($_POST['save'])){
						$this->redirect(array('action'=>'edit',$id));
					}elseif (isset($_POST['save_exit'])){
						$this->redirect(array('action'=>'index'));
					}
				} else {
					$this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));

				}
			}
		}
	}

	/**
	 * @Description : Sửa tài khoản
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_edit($id = null) {
		$admin = $this->Auth->user();
		if ($admin['role'] != 'admin') throw new NotFoundException(__('Trang này không tồn tại',true));

		$this->User->id = $id;
		if (!$this->User->exists()) throw new NotFoundException(__('Invalid user'));

		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data['User'];
			$a_update_fields = array(
								'name'=>$data['name'],
								'group_id'=>$data['group_id'],
								'modified'=>mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'))
							);
			if(!empty($data['password'])){
				$data['password'] = $this->Auth->password($data['password']);
				$a_update_fields = array_merge($a_update_fields,array('password'=>$data['password']));
			}
			if(!empty($data['group_id'])){
				$a_update_fields = array_merge($a_update_fields,array('group_id'=>$data['group_id']));

				if ($data['group_id'] == 1) $data['role'] = 'admin';
				else $data['role'] = 'staff';
				$a_update_fields = array_merge($a_update_fields,array('role'=>$data['role']));
			}
            $a_update_fields = array_merge($a_update_fields,array('pos_1'=>$data['pos_1']));
            $a_update_fields = array_merge($a_update_fields,array('pos_2'=>$data['pos_2']));
            $a_update_fields = array_merge($a_update_fields,array('pos_3'=>$data['pos_3']));
            $a_update_fields = array_merge($a_update_fields,array('pos_4'=>$data['pos_4']));

            $this->User->id = $id;
			$this->User->set($a_update_fields);
			if ($this->User->save()) {
				$this->Session->setFlash('<span>'.__('Thông tin đã được cập nhật').'</span>','default',array('class'=>'success'));

				if (isset($_POST['save'])){
					$this->redirect($this->referer());
				}elseif (isset($_POST['save_exit'])){
					$url = (!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index');
					$this->redirect($url);
				}
			} else {
				$this->Session->setFlash('<span>'.__('có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
			unset($this->request->data['User']['password']);
		}
	}

	/**
	 * @Description :
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
		public function admin_myAcount(){
		$a_my_acount = $this->Auth->user();
		if(empty($a_my_acount)) throw new NotFoundException(__('Invalid'));

		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data['User'];

			$update_fields = array('name'=>$data['name']);
			$error = false;
			if(!empty($data['password'])){
				$password_old = $this->Auth->password($data['password_old']);
				$check_pass = $this->User->find('count',array('conditions'=>array('password'=>$password_old,'id'=>$a_my_acount['id'])));	//Kiem tra pass nhap cu co trung khong
				if(!empty($check_pass)){
					if($data['password']!=$data['password_confirm']){
						$this->Session->setFlash('<span>'.__('Sai mật khẩu xác nhận').'</span>','default',array('class'=>'error'));
						$error = true;
					}else{
						$data['password'] = $this->Auth->password($data['password']);
						$update_fields = array_merge($update_fields,array('password'=>$data['password']));
					}
				}else{
					$this->Session->setFlash('<span>'.__('Bạn nhập sai mật khẩu cũ').'</span>','default',array('class'=>'error'));
					$error = true;
				}
			}

			if(!$error){
				$this->User->id = $a_my_acount['id'];
				$this->User->set($update_fields);
				if($this->User->save()){
					$this->Session->setFlash('<span>'.__('Thông tin đã được cập nhật').'</span>','default',array('class'=>'success'));
				}else{
					$this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
				}
			}
		}

		$this->request->data['User'] = $a_my_acount;
	}

	/**
	 * @Description : Xóa tài khoản
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_delete($id = null) {
		$admin = $this->Auth->user();
		if ($admin['role'] != 'admin') throw new NotFoundException(__('Trang này không tồn tại',true));

		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * @Description : Xóa user sdung ajax
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	function admin_ajaxDeleteItem(){
		$this->layout = false;
		$this->autoRender = false;

		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid user'));
		if($this->User->delete($_POST['id'])) return true;
		else return false;
	}



}
?>
