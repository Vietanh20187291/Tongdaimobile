<?php
App::uses('AppController', 'Controller');

class SeoLinksController extends AppController {

	public $components = array('Paginator');
	private $limit_admin = 50;

	public function admin_index() {
		$a_conditions = array();

		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->SeoLink->id = $val;
						$this->SeoLink->set(array('status'=>1));
						$this->SeoLink->save();
					}
					$message = __('Đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->SeoLink->id = $val;
						$this->SeoLink->set(array('status'=>0));
						$this->SeoLink->save();
					}
					$message = __('Đã được bỏ kích hoạt');
					break;
				case 'del':
					foreach ($_POST['chkid'] as $val){
						$this->SeoLink->delete($val);
					}
					$message = __('Đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}

		if(!empty($_GET['keyword']) && $_GET['keyword']!=__('Tìm kiếm')){	//Tu khoa
			$a_conditions = array_merge($a_conditions,array('SeoLink.link like'=>'%'.str_replace(' ','-',$_GET['keyword']).'%'));
		}

		if(!empty($_GET['model'])) {
			$a_conditions = array_merge($a_conditions,array('SeoLink.model like'=>$_GET['model']));
		}
		if(!empty($_GET['name'])) {
			$a_conditions = array_merge($a_conditions,array('SeoLink.name like'=>'%'.$_GET['name'].'%'));
		}
		$this->paginate = array(
			'conditions'=>$a_conditions,
			'order'=>array('modified'=>'desc'),
			'limit'=>$this->limit_admin
		);
		$a_seo_links = $this->paginate();
		$this->set('a_seo_links_c', $a_seo_links);

		$counter = $this->SeoLink->find('count',array('conditions'=>$a_conditions));
		$this->set('counter_c',$counter);

		//Url hiện tại
		$current_url = urlencode($this->Oneweb->curPageURL());
		$this->set('current_url_c',$current_url);
	}

	public function admin_add() {

		if ($this->request->is('post')) {
			$data = $this->request->data['SeoLink'];
			//Ngay tao
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

			//Ngôn ngữ
			$data['status'] = $data['status'];

			$this->SeoLink->create();
			if ($this->SeoLink->save($data)) {
				$id = $this->SeoLink->getLastInsertID();
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
	public function admin_edit($id = null) {
		$this->SeoLink->id = $id;
		if (!$this->SeoLink->exists()) throw new NotFoundException(__('Invalid'));

		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data['SeoLink'];
			//Đọc thông tin
			$data['content'] = trim($data['content']);
			if ($this->SeoLink->save($data)) {
				$this->Session->setFlash('<span>'.__('Thông tin đã được cập nhật').'</span>','default',array('class'=>'success'));
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache

				//hiển thị lại text mô tả
				if($this->Cookie->check('cookie_hidden_description')) {
		      $cookie_hidden_description = $this->Cookie->read('cookie_hidden_description');
		      foreach ($cookie_hidden_description as $key => $value) {
		      	if(in_array($data['link'], $cookie_hidden_description))  unset($cookie_hidden_description[$key]);
		      }
		      $this->Cookie->write('cookie_hidden_description', $cookie_hidden_description, '+1 week');
		    }

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
		}
		$this->request->data = $this->SeoLink->read(null, $id);
// 		$this->request->data['SeoLink']['content'] = trim(strip_tags(str_replace(array('<br />','<br>'), '', $this->request->data['SeoLink']['content'])));
	}

	public function admin_ajaxChangeStatus(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['field']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		$return = $this->_changeStatus($_POST['field'], $_POST['id']);
		return json_encode($return);
	}

	public function admin_ajaxDeleteItem(){
		$this->layout = false;
		$this->autoRender = false;

		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		return $this->SeoLink->delete($_POST['id']);
	}
}
