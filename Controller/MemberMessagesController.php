<?php 
App::uses('AppController', 'Controller');;
class MemberMessagesController extends AppController{
	private  $limit_admin = 20;
	private $limit = 20;
	
	/*********************************************************/
	/***************************Admin******************************/
	/*********************************************************/
	/*
	* @Description :list
	* @param - string : 
	* @param - interger:
	* @param - array:
	* @return - array:
	* @Author : HuuQuynh - quynh@url.vn
	*/
	public function admin_index(){
		$lang = $this->Session->read('lang');
		$a_conditions = array();
		
		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->MemberMessage->id = $val;
						$this->MemberMessage->set(array('status'=>1));
						$this->MemberMessage->save();
					}
					$message = __('Bình luận đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->MemberMessage->id = $val;
						$this->MemberMessage->set(array('status'=>0));
						$this->MemberMessage->save();
					}
					$message = __('Bình luận đã được bỏ kích hoạt');
					break;
				case 'delete':
					foreach ($_POST['chkid'] as $val){
						$this->MemberMessage->delete($val);
					}
					$message = __('Bình luận đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}
		if(!empty($_GET['keyword']) && $_GET['keyword']!=__('Tiêu đề')){	//Tu khoa
			$a_conditions = array_merge($a_conditions,array(
					'or'=>array('MemberMessage.title like'=>'%'.$_GET['keyword'].'%'
					)
			));
		}
		
		if(!empty($_GET['model'])) $a_conditions = array_merge($a_conditions,array('Comment.model'=>$_GET['model']));
		$this->paginate = array(
				'conditions'=>$a_conditions,
				'fields'=>array('MemberMessage.member_receive','MemberMessage.title','MemberMessage.message','MemberMessage.status','MemberMessage.created','MemberMessage.id'),
				'order'=>array('created'=>'desc','status'=>'asc'),
				'limit'=>$this->limit_admin
		);
		
		$a_member_message = $this->paginate();
		$this->set('a_member_message_c', $a_member_message);
		
		//Url hiện tại
		$current_url = urlencode($this->Oneweb->curPageURL());
		$this->set('current_url_c',$current_url);
	}
	/*
	* @Description :them
	* @param - string : 
	* @param - interger:
	* @param - array:
	* @return - array:
	* @Author : HuuQuynh - quynh@url.vn
	*/
	public function admin_add(){
		if ($this->request->is('post')) {
			$data = $this->request->data['MemberMessage'];
			$lang = $this->Session->read('lang');
			$data['lang'] = $lang;
			//Ngay tao
			$data['created'] = mktime($data['created']['hour'],$data['created']['min'],0,$data['created']['month'],$data['created']['day'],$data['created']['year']);
			$this->MemberMessage->create();
			if ($this->MemberMessage->save($data)) {
				$id = $this->MemberMessage->getLastInsertID();
				$this->Session->setFlash('<span>'.__('Thêm mới thành công').'</span>','default',array('class'=>'success'));
		
				if (isset($_POST['save'])){
					$this->redirect(array('action'=>'edit',$id));
				}elseif (isset($_POST['save_add'])){
					$this->redirect(array('action'=>'add'));
				}elseif (isset($_POST['save_exit'])){
					$this->redirect(array('action'=>'index'));
				}
			} else {
				$this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
			}
		}
	}
	/*
		* @Description :Sua
		* @param - string : 
		* @param - interger:$id
		* @param - array:
		* @return - array:void
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function admin_edit($id = null) {
		$this->MemberMessage->id = $id;
		if (!$this->MemberMessage->exists()) throw new NotFoundException(__('Invalid'));
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data['MemberMessage'];
			
			//Ngay tao
			$data['created'] = mktime($data['created']['hour'],$data['created']['min'],0,$data['created']['month'],$data['created']['day'],$data['created']['year']);
			
			if ($this->MemberMessage->save($data)) {
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
			$this->MemberMessage->recursive = -1;
			$this->request->data = $this->MemberMessage->read(null, $id);
			
		}
		
	}
	/*
	* @Description :Xoa su dung ajax
	* @param - string : 
	* @param - interger:
	* @param - array:
	* @return - array:boolean
	* @Author : HuuQuynh - quynh@url.vn
	*/
	public function admin_ajaxDeleteItem() {
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
	
		if($this->MemberMessage->delete($_POST['id'])) return true;
		else return false;
	}
	/*
	* @Description :
	* @param - string : 
	* @param - interger:
	* @param - array:
	* @return - array:
	* @Author : HuuQuynh - quynh@url.vn
	*/
	public function admin_ajaxChangeStatus(){
		$this->layout = false;
		$this->autoRender = false;
		$model = $this->modelClass;
	
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
	
		$return = $this->_changeStatus('status',$_POST['id']);
	
		return json_encode($return);
	}
	/*
		* @Description :
		* @param - string : 
		* @param - interger:
		* @param - array:
		* @return - array:
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function admin_ajaxLoadMember(){
		if (empty($_GET['name_startsWith'])) exit ;
		$q = strtolower($_GET["name_startsWith"]);
		// remove slashes if they were magically added
		if (get_magic_quotes_gpc()) $q = stripslashes($q);
		$this->layout = false;
		$this->autoRender = false;
		$this->loadModel('Member');
		$this->Member->recursive = -1;
		$a_member =$this->Member->find('all',array('conditions'=>array(),'fields'=>array('email'),'order'=>array('created'=>'desc')));
		$arr_data = array();
		$arr_data[0] = '*';
		foreach($a_member as $val){
			$arr_data[] = $val['Member']['email'];
		}
		return json_encode($arr_data);
	}
	
}
?>