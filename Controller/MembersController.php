<?php 
App::uses('AppController', 'Controller');;
class MembersController extends AppController{
	public $components = array('Oneweb','Recaptcha.Recaptcha');
	public $uses = array('Member');
	private  $limit_admin = 20;
	private $limit = 20;
	/*
		* @Description :
		* @param - string : 
		* @param - interger:
		* @param - array:
		* @return - array:
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function index(){
		if(!$this->Session->check('auth_member')){
			$this->Session->setFlash('<span>'.__('Bạn chưa đăng nhập vào hệ thống').'</span>','default',array('class'=>'error'));
			$this->redirect(array('controller'=>'pages', 'action'=>'home', 'lang'=>$this->request->params['lang']));
		}else{
			//noi dung trang quan ly thanh vien
			$a_session_member = $this->Session->read('auth_member');
			$a_member = $this->Member->find('first',array(
						'conditions'=>array('Member.email'=>$a_session_member['email'],'Member.status'=>1)
					));
			$this->set('a_member_c',$a_member);
		}
	}
	/*
		* @Description : thay doi tai khoan thanh vien
		* @param - string : 
		* @param - interger:
		* @param - array:
		* @return - array:
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function editAccount(){
		if(!$this->Session->check('auth_member')){
			$this->Session->setFlash('<span>'.__('Bạn chưa đăng nhập vào hệ thống').'</span>','default',array('class'=>'error'));
			$this->redirect(array('controller'=>'pages', 'action'=>'home', 'lang'=>$this->request->params['lang']));
		}else{
			$member = $this->Session->read('auth_member');
			if($this->request->is('post') || $this->request->is('put')){
				$this->request->data['Member']['name'] = $this->Oneweb->htmlEncode($this->request->data['Member']['name']);
				$this->request->data['Member']['birthday'] = mktime(0,0,0,$this->request->data['Member']['birthday']['month'],$this->request->data['Member']['birthday']['day'],$this->request->data['Member']['birthday']['year']);
				$this->request->data['Member']['gender'] = $this->Oneweb->htmlEncode($this->request->data['Member']['gender']);
				$this->request->data['Member']['id'] = $member['id'];
				if($this->Member->save($this->request->data)){
					$this->Session->setFlash('<span>'.__('Thay đổi thông tin thành công').'</span>','default',array('class'=>'success'));
					$this->redirect(array('controller'=>'members', 'action'=>'index', 'lang'=>$this->request->params['lang']));
				}
			}else{
			
				//Lay thong tin thanh ve thanh vien
				$a_member= $this->Member->read(null, $member['id']);
				$this->request->data['Member']['name'] = $a_member['Member']['name'];
				$this->request->data['Member']['gender'] = $a_member['Member']['gender'];
				$this->request->data['Member']['birthday'] = $a_member['Member']['birthday'];
			}
		}
	}
	/*
	* @Description :Thay doi dia chi
	* @param - string : 
	* @param - interger:
	* @param - array:
	* @return - array:
	* @Author : HuuQuynh - quynh@url.vn
	*/
	public function changeAddress(){
		if(!$this->Session->check('auth_member')){
			$this->Session->setFlash('<span>'.__('Bạn chưa đăng nhập vào hệ thống').'</span>','default',array('class'=>'error'));
			$this->redirect(array('controller'=>'pages', 'action'=>'home', 'lang'=>$this->request->params['lang']));
		}else{
			$member = $this->Session->read('auth_member');
			if($this->request->is('post') || $this->request->is('put')){
				$this->request->data['Member']['name'] = $this->Oneweb->htmlEncode($this->request->data['Member']['name']);
				$this->request->data['Member']['phone'] = $this->Oneweb->htmlEncode($this->request->data['Member']['phone']);
				$this->request->data['Member']['address'] = $this->Oneweb->htmlEncode($this->request->data['Member']['address']);
				$this->request->data['Member']['id'] = $member['id'];
				if($this->Member->save($this->request->data)){
					$this->Session->setFlash('<span>'.__('Thay đổi thông tin thành công').'</span>','default',array('class'=>'success'));
					$this->redirect(array('controller'=>'members', 'action'=>'index', 'lang'=>$this->request->params['lang']));
				}
			}else{
				$a_member = $this->Member->read(null,$member['id']);
				$this->request->data['Member']['name'] = $a_member['Member']['name'];
				$this->request->data['Member']['phone'] = $a_member['Member']['phone'];
				$this->request->data['Member']['address'] = $a_member['Member']['address'];
			}
		}
	}
	/*
	 * @Description :Đang nhap vao he thong
	* @Author : HuuQuynh - quynh@url.vn
	*/
	public function login(){
		$this->layout = false;
		$this->autoRender = false;
		$error = array();
		if(!empty($_POST['email']) || !empty($_POST['password'])){
			$email = $this->Oneweb->htmlEncode($_POST['email']);
			$password = $this->Oneweb->htmlEncode($_POST['password']);
			if(!$this->Oneweb->checkEmail($email)) $error['email'] = true;
			if(empty($error)){
				$data['email'] = $email;
				$data['password'] = $this->Auth->password($password);
				if($this->_authMember($data) != 'true') $error['error_data'] = true;
			}
		}else{
			$error['error_empty'] = true;
		}
		return json_encode($error);
		
	}
	/*
		* @Description :Kiem tra user
		* @param - string : 
		* @param - interger:
		* @param - array:$data
		* @return - bool: true or false
		* @Author : HuuQuynh - quynh@url.vn
		*/
	private function _authMember($data){
		$member = $this->Member->find('first',array(
				'conditions'=>array('password'=>$data['password'],'email'=>$data['email']),
				'fields'=>array('id','name','gender','email','password','created'),
		));
		if(!empty($member)){
			$this->Session->write('auth_member',$member['Member']);			//Luu session
			return true;
		}else return false;
	}
	
	/*
	* @Description :Thoat khoi he thong
	* @Author : HuuQuynh - quynh@url.vn
	*/
	public function logout(){
		$this->Session->delete('auth_member');
		$this->redirect(array('controller'=>'pages', 'action'=>'home', 'lang'=>$this->request->params['lang']));
	}
	/*
	* @Description :Đang ky thanh vien
	* @Author : HuuQuynh - quynh@url.vn
	*/
	public function registration(){
		if($this->request->is('post')){
			$this->Member->set($this->request->data);
			if($this->Member->validates(array('fieldList' => array('name', 'email','password')))){
				if($this->_checkEmail($this->request->data['Member']['email']) == 'true'){ 
					$this->Session->setFlash('<span>'.__('Email đã được sử dụng').'</span>','default',array('class'=>'error'));
				}else{
					$this->request->data['Member']['name'] = $this->Oneweb->htmlEncode($this->request->data['Member']['name']);
					$this->request->data['Member']['email'] = $this->Oneweb->htmlEncode($this->request->data['Member']['email']);
					$this->request->data['Member']['birthday'] = mktime(0,0,0,$this->request->data['Member']['birthday']['month'],$this->request->data['Member']['birthday']['day'],$this->request->data['Member']['birthday']['year']);
					$this->request->data['Member']['password'] = $this->Auth->password($this->request->data['Member']['password']);
					$this->request->data['Member']['gender'] = $this->Oneweb->htmlEncode($this->request->data['Member']['gender']);
					$this->request->data['Member']['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
					$this->request->data['Member']['status'] = 1;
					$this->request->data['Member']['trash'] = 0;
					//Get Ip or Proxy
					if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
						$this->request->data['Member']['ip'] = $_SERVER["HTTP_X_FORWARDED_FOR"];
						$this->request->data['Member']['proxy'] = $_SERVER['REMOTE_ADDR'];
					}else{
						$this->request->data['Member']['ip'] = $_SERVER['REMOTE_ADDR'];
						$this->request->data['Member']['proxy'] = '';
					}
					$this->Member->create();
					if($this->Member->save($this->request->data)){
						$id = $this->Member->getLastInsertID();
						$this->request->data['Member']['id'] = $id;
						$this->Session->write('Member.register',$this->request->data);
						$this->Session->write('auth_member', $this->request->data['Member']);
						$this->_sendEmail();
						$this->Session->setFlash('<span>'.__('Bạn đã đăng ký thành công').'</span>','default',array('class'=>'success'));
						$this->redirect(array('controller'=>'members','action'=>'index', 'lang'=>$this->request->params['lang']));
					}else{
						$this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
						
					}
				}
			}else{
				$this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
			}
			
		}
	}
	
	/*
	* @Description :Kiem tra mail thanh vien
	* @param - string : email
	* @return - bool:true or false
	* @Author : HuuQuynh - quynh@url.vn
	*/
	private function _checkEmail($email=null){
		if($email==null) throw new NotFoundException(__('Trang này không tồn tại',true));
		$check = $this->Member->find('count',array('conditions'=>array('email'=>$email)));
		if(!empty($check)) return true;
		else return false;
	}
	/*
		* @Description :Ham gui mail dang ky thanh vien
		* @param - string : 
		* @Author : HuuQuynh - quynh@url.vn
		*/
	private function _sendEmail(){
		if(!$this->Session->check('Member.register')) throw new NotFoundException(__('Trang này không tồn tại',true));
	
		$a_configs = $this->_getConfig('email');
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
		$data = $this->Session->read('Member.register');
		
		$config = array_merge($config,array(
				'transport'=>$a_configs['smtp_transport'],
				'emailFormat'=>'html',
				'template'=>'registration',
				'layout'=>'index'
		));
		
		//Gửi cho khách hàng
		$Email = new CakeEmail();
		$Email->config(array_merge($config,array(
				'from'=>array($a_configs['member'] => $_SERVER['HTTP_HOST']),
				'replyTo'=>$a_configs['member'],
				'to'=>$data['Member']['email'],
				'subject'=>$a_configs['member_subject'],
				'viewVars'=>array('data'=>$data['Member'],'config'=>$a_configs,'admin'=>false)
		)));
		
		$Email -> send();
	}
	
	/*
	 * @Description :Lay lại mat khau dang nhap
	* @Author : HuuQuynh - quynh@url.vn
	*/
	public function forgetPassword(){
		if($this->request->is('post')){
			if ($this->Recaptcha->verify()) {
				$this->request->data['Member']['email']=$this->Oneweb->htmlEncode($this->request->data['Member']['email']);
				$data = $this->request->data['Member'];
				if($this->Member->validates(array('fieldList'=>array('email')))){
					$a_member = $this->Member->find('first',array(	'conditions'=>array('email'=>$data['email']),
							'fields'=>array('id','password','email')
					));
					if(!empty($a_member)){
						$this->Member->id = $a_member['Member']['id'];
						$token = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						$this->Member->set(array('token'=>$token));
						if($this->Member->save()){
							$a_member['Member']['token'] = $token;
							$this->_sendEmailResset($a_member);
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
		* @Description :Gui thu resset mat khau
		* @param - void : 
		* @Author : HuuQuynh - quynh@url.vn
		*/
	private function _sendEmailResset($data = null){
		if($data==null) throw new NotFoundException(__('Trang này không tồn tại',true));
		$lang = $this->request->params['lang'];
		$a_configs = $this->_getConfig('email');
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
				'template'=>'member_reset_password',
				'layout'=>'index'
		));
		
		//Gửi cho khách hàng
		$Email = new CakeEmail();
		$Email->config(array_merge($config,array(
				'from'=>array($a_configs['member'] => $_SERVER['HTTP_HOST']),
				'replyTo'=>$a_configs['member'],
				'to'=>$data['Member']['email'],
				'subject'=>$a_configs['member_subject'],
				'viewVars'=>array('data'=>$data['Member'],'config'=>$a_configs,'admin'=>false, 'lang'=>$lang)
		)));
		$Email -> send();
	}
	/*
		* @Description :Xac nhan lai mat khau da resset
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function confirmResetPassword($member_id=null,$token=null){
		if($member_id==null || $token==null) throw new NotFoundException(__('Trang này không tồn tại',true));
		$date_current = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));		//Ngay hien tai
		
		//Kiểm tra request đên có phải trong 24h từ khi y/c thay đổi ko
		if($date_current-$token>(24*60*60)) throw new NotFoundException(__('Trang này không tồn tại',true));
		
		//Kiểm tra xem có tồn tại thành viên này ko
		$a_member = $this->Member->find('first',array('conditions'=>array('id'=>$member_id,'token'=>$token),'fields'=>array('id'),'fields'=>'id','recursive'=>-1));
		$success = false;
		if(!empty($a_member)){
			$this->Member->id = $a_member['Member']['id'];
			$this->Member->set(array('token'=>null));
			if($this->Member->save()){
				if($this->_resetPass($a_member['Member']['id'])){
					$success = true;
				}
			}else{
				$this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
			}
			
		}
		
		$this->set('success_c',$success);
	}
	/*
		* @Description :Lay mat khau theo randon rui gui thu cho thanh vien
		* @param  : $id
		* @return : true or false
		* @Author : HuuQuynh - quynh@url.vn
		*/
	private function _resetPass($id=null){
		if($id==null) throw new NotFoundException(__('Trang này không tồn tại',true));
		$lang = $this->request->params['lang'];
		//Set lai mat khau
		$pass_new = substr(rand(1000000000, 9999999999), 1,10);
		$pass_encode = $this->Auth->password($pass_new);
		$this->Member->id = $id;
		$this->Member->set(array('password'=>$pass_encode));
		
		if($this->Member->save()){
			$a_data = $this->Member->read(null,$id);
			//Gui mail
			$a_configs = $this->_getConfig('email');
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
					'template'=>'reset_password',
					'layout'=>'index'
			));
			
			//Gửi cho khách hàng
			$Email = new CakeEmail();
			$Email->config(array_merge($config,array(
					'from'=>array($a_configs['member'] => $_SERVER['HTTP_HOST']),
					'replyTo'=>$a_configs['member'],
					'to'=>$a_data['Member']['email'],
					'subject'=>$a_configs['member_subject'],
					'viewVars'=>array('data'=>$a_data,'pass_new_c'=>$pass_new,'config'=>$a_configs,'admin'=>false, 'lang'=>$lang)
			)));
			$Email -> send();
			return true;
		}else{
			return false;
		}
	}
	/*
		* @Description :Thay doi mat khau
		* @param - void : 
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function changePassword(){
		if(!$this->Session->check('auth_member')){
			$this->Session->setFlash('<span>'.__('Bạn chưa đăng nhập vào hệ thống').'</span>','default',array('class'=>'error'));
			$this->redirect(array('controller'=>'pages', 'action'=>'home', 'lang'=>$this->request->params['lang']));
		}else{
			$a_member = $this->Session->read('auth_member');
			$error = array();
			if($this->request->is('post') || $this->request->is('put')){
				$this->request->data['Member']['password'] = $this->Oneweb->htmlEncode($this->request->data['Member']['password']);
				$this->request->data['Member']['re_password'] = $this->Oneweb->htmlEncode($this->request->data['Member']['re_password']);
				$this->request->data['Member']['password_new'] = $this->Oneweb->htmlEncode($this->request->data['Member']['password_new']);
				$this->request->data['Member']['re_password_new'] = $this->Oneweb->htmlEncode($this->request->data['Member']['re_password_new']);
				if($this->request->data['Member']['password'] != $this->request->data['Member']['re_password']) $error['re_password']=true;
				if($this->request->data['Member']['password_new'] != $this->request->data['Member']['re_password_new']) $error['re_password_new']=true;
				if(strlen($this->request->data['Member']['password_new']) <6) $error['strlen_password_new'] = true;
				if(empty($this->request->data['Member']['password_new'])) $error['empty_password_new']=true;
				
				$member = $this->Member->find('first',array(
							'conditions'=>array('Member.password'=>$this->Auth->password($this->request->data['Member']['password']), 'Member.email'=>$a_member['email'])
						));
				if(empty($member)) $error['wrong_password'] = true;
				if(empty($error)){
					$data['Member']['id'] = $member['Member']['id'];
					$data['Member']['password'] = $this->Auth->password($this->request->data['Member']['password_new']); 
					if($this->Member->save($data)){
						$this->Session->delete('auth_member');
						$this->Session->setFlash('<span>'.__('Thay đổi thông tin thành công').'</span>','default',array('class'=>'success'));
						$this->redirect($this->referer());
					}else{
						$this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
					}
				}else{
					$this->set('a_errors_c',$error);
					$this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
				}
			}
		}
	}
	
	/*
		* @Description :Lich su giao dich
		* @param - string : 
		* @param - interger:
		* @param - array:
		* @return - void:
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function historyPayment(){
		if(!$this->Session->check('auth_member')){
			$this->Session->setFlash('<span>'.__('Bạn chưa đăng nhập vào hệ thống').'</span>','default',array('class'=>'error'));
			$this->redirect(array('controller'=>'pages', 'action'=>'home', 'lang'=>$this->request->params['lang']));
		}else{
			$a_members = $this->Session->read('auth_member');
			$this->loadModel('Order');
			$a_order = $this->Order->find('all', array(
					'conditions'=>array('member_id'=> $a_members['id']),
					'fields'=>array('id','method_payment','transaction_code','created','total','unit_payment','rate','member_id')
					));
			$this->set('a_order_c',$a_order);
			
		}
		
	}
	/*
		* @Description :Chi tiet giao dich
		* @param - string : $id
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function detailHistoryPayment($id=null){
		if(!$this->Session->check('auth_member')){
			$this->Session->setFlash('<span>'.__('Bạn chưa đăng nhập vào hệ thống').'</span>','default',array('class'=>'error'));
			$this->redirect(array('controller'=>'pages', 'action'=>'home', 'lang'=>$this->request->params['lang']));
		}else{
			$this->loadModel('Order');
			$this->Order->id = $id;
			if (!$this->Order->exists()) throw new NotFoundException(__('Invalid'));
			$a_order = $this->Order->read(null, $id);
			$this->set('a_order_c',$a_order);
		}
		
	}
	/*
		* @Description :Quan ly thu
		* @param - string : 
		* @param - interger:
		* @param - array:
		* @return - void:
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function managementNotice(){
		if(!$this->Session->check('auth_member')){
			$this->Session->setFlash('<span>'.__('Bạn chưa đăng nhập vào hệ thống').'</span>','default',array('class'=>'error'));
			$this->redirect(array('controller'=>'pages', 'action'=>'home', 'lang'=>$this->request->params['lang']));
		}else{
			$a_members = $this->Session->read('auth_member');
			$this->loadModel('MemberMessage');
			
			$a_message = $this->MemberMessage->find('all', array(
					'conditions'=>array( 'or'=>array('member_receive'=>'*,','MemberMessage.member_receive like '=> '%'.$a_members['email'].',%'),'MemberMessage.created >'=>$a_members['created'], 'MemberMessage.status'=>1),
					'fields'=>array('id','member_receive','title','message','view','created','member_message_read'),
					'order'=>'created desc'
			));
			$this->set('a_message_c',$a_message);
				
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
	public function detailNotice($id=null){
		if(!$this->Session->check('auth_member')){
			$this->Session->setFlash('<span>'.__('Bạn chưa đăng nhập vào hệ thống').'</span>','default',array('class'=>'error'));
			$this->redirect(array('controller'=>'pages', 'action'=>'home', 'lang'=>$this->request->params['lang']));
		}else{
			$this->loadModel('MemberMessage');
			$this->MemberMessage->id = $id;
			if (!$this->MemberMessage->exists()) throw new NotFoundException(__('Invalid'));
			$a_members = $this->Session->read('auth_member');
			
			//Cap nhat id vao bang thanh vien
			$this->MemberMessage->recursive=-1;
			$message_read = $this->MemberMessage->find('first',array(
						'conditions'=>array('MemberMessage.id'=>$id),
						'fields'=>array('member_message_read')
					));
			$a_data = '';
			if(!empty($message_read['MemberMessage']['member_message_read'])){
				$flag = false;
				$a_member_info = explode('-',$message_read['MemberMessage']['member_message_read']);
				foreach($a_member_info as $val){
					if($val == $a_members['id']) $flag = true;
				}
				if($flag==false){
					$a_data = $message_read['MemberMessage']['member_message_read'].implode('-', array($a_members['id'])).'-';
				}
			}else{
				$a_data =implode('-', array($a_members['id'])).'-';
			}
			if(!empty($a_data)){
				$this->MemberMessage->id = $id;
				$this->MemberMessage->set(array('member_message_read'=>$a_data));
				$this->MemberMessage->save();
			}
			
			$a_notice = $this->MemberMessage->read(null, $id);
			$this->set('a_notice_c',$a_notice);
		}
	
	}
	
	/*********************************************************/
	/***************************Admin******************************/
	/*********************************************************/
	/**
	* @Description : Thay đổi trạng thái
	*
	* @throws 	: NotFoundException
	* @return 	: string
	* @Author 	: HuuQuynh - quynh@url.vn
	*/
	public function admin_index(){
		$lang = $this->Session->read('lang');
		$a_conditions = array('Member.trash'=>0);
		
		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->Member->id = $val;
						$this->Member->set(array('status'=>1));
						$this->Member->save();
					}
					$message = __('Sản phẩm đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->Member->id = $val;
						$this->Member->set(array('status'=>0));
						$this->Member->save();
					}
					$message = __('Sản phẩm đã được bỏ kích hoạt');
					break;
				case 'trashes':
					foreach ($_POST['chkid'] as $val){
						$this->trashItem($val);
					}
					$message = __('Sản phẩm đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}
		if(!empty($_GET['keyword']) && $_GET['keyword']!=__('Tìm kiếm')){	//Tu khoa
			$a_conditions = array_merge($a_conditions,array('Member.name like'=>'%'.$_GET['keyword'].'%'));
		}
		
		$a_order = 'created desc';
		$this->paginate = array(
				'conditions'=>$a_conditions,
				'fields'=>array(
						'id','name','email','created','status'
				),
				'order'=>$a_order,
				'limit'=>$this->limit_admin
		);
		
		
		$a_members = $this->paginate();
		$this->set('a_members_c', $a_members);
		
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
	 * @Edit    : Huu Quynh - Quynh@url.vn
	 */
	public function admin_ajaxChangeStatus(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['field']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		$return = $this->_changeStatus($_POST['field'], $_POST['id']);
		if($_POST['field']){	//Đếm vị trí hiển thị trong trg hợp nó là vị trí hiển thị
			$this->Product->recursive = -1;
			$a_member = $this->Member->read('id',$_POST['id']);
			$a_member = array_filter($a_member['Member']);
				
			$return = array_merge($return,array('count'=>count($a_member)));
		}
		return json_encode($return);
	}
	
	/**
	 * @Description : Cho sản phẩm vào thùng rác
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int data
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 * @Edit    : Huu Quynh - Quynh@url.vn
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
		$this->Member->recursive = -1;
		$a_member = $this->Member->read('id,name',$id);
		$member = $a_member['Member'];
	
		//Ghi vào bảng Trash
		$data['name'] = $member['name'];
		$data['item_id'] = $member['id'];
		$data['model'] = 'Member';
		$data['description'] = 'Thành Viên';
		$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

		$this->loadModel('Trash');
		$this->Trash->create();
		if($this->Trash->save($data)){
			$this->Member->id = $id;
			$this->Member->set(array('trash'=>1));
			if($this->Member->save()) return true;
		}
		return false;
	}
	
	/**
	 * @Description : Sửa thông tin thành viên
	 *
	 * @throws NotFoundException
	 * @param int $id
	 * @return void
	 * @Author HuuQuynh - quynh@url.vn
	 */
	public function admin_edit($id = null) {
		$this->Member->id = $id;
		if (!$this->Member->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');
	
		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data['Member'];
			//Ngày sửa
			$data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			if ($this->Member->save($data)) {
	
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
			$this->request->data = $this->Member->read(null, $id);
		}
		
	
	
	}
	
}
?>