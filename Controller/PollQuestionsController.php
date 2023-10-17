<?php
App::uses('AppController', 'Controller');
/**
 * Banners Controller
 *
 * @property Banner $Banner
 */
class PollQuestionsController extends AppController {
	
	private  $limit_admin = 50;
	
	
	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/
	
	public function admin_index(){
		$lang = $this->Session->read('lang');
		$a_conditions = array('PollQuestion.lang'=>$lang);
		
		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->PollQuestion->id = $val;
						$this->PollQuestion->set(array('status'=>1));
						$this->PollQuestion->save();
					}
					$message = __('PollQuestion đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->PollQuestion->id = $val;
						$this->PollQuestion->set(array('status'=>0));
						$this->PollQuestion->save();
					}
					$message = __('PollQuestion đã được bỏ kích hoạt');
					break;
				case 'delete':
					foreach ($_POST['chkid'] as $val){
						$this->PollQuestion->delete($val);
					}
					$message = __('PollQuestion đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}
		$this->loadModel('PollQuestion');
		
		//end action checkall
		$this->PollQuestion->recursive = 1;
		$this->paginate = array(
				'conditions'=>array('PollQuestion.lang'=>$lang),
				'limit'=>5,
				'order'=>array("PollQuestion.sort"=>"asc")
		);
		$a_pollquestion_a_c = $this->paginate('PollQuestion');
// 			debug($a_pollquestion_a_c);
		$this->set(compact('a_pollquestion_a_c'));
		
		//Url hiện tại
		$current_url = urlencode($this->Oneweb->curPageURL());
		$this->set('current_url_c',$current_url);
	}
	/*
		* @Description :
		* @param - string : 
		* @param - interger:
		* @param - array:
		* @return - array:
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function admin_add(){
		$lang = $this->Session->read('lang');
		
		if ($this->request->is('post')) {
			$oneweb_seo = Configure::read('Seo');
			
			//sort
			if(empty($this->request->data['PollQuestion']['sort'])){
				$this->request->data['PollQuestion']['sort'] = $this->PollQuestion->find('count',array('conditions'=>array('lang'=>$lang)))+1;
			}
			$data = $this->request->data['PollQuestion'];
			//Ngôn ngữ
			$data['lang'] = $lang;
			$this->PollQuestion->create();
			if ($this->PollQuestion->save($data)) {
				$id = $this->PollQuestion->getLastInsertID();
		
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
		
	}
	/*
		* @Description :
		* @param - string : 
		* @param - interger:
		* @param - array:
		* @return - array:
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function admin_edit($id=null){
		$this->PollQuestion->id = $id;
		if (!$this->PollQuestion->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['PollQuestion'];
				
			if ($this->Information->save($data)) {
		
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
			$this->request->data = $this->PollQuestion->read(null, $id);
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
	public function admin_ajaxDeleteItem() {
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		
		if($this->PollQuestion->delete($_POST['id'])) return true;
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
		if(empty($_POST['field']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		$return = $this->_changeStatus($_POST['field'], $_POST['id']);
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
	function admin_ajaxChangeSort(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['val']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
	
		$this->PollQuestion->id = $_POST['id'];
		$this->PollQuestion->set(array('sort'=>$_POST['val']));
		$this->PollQuestion->save();
		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
	}
	
}
