<?php
App::uses('AppController', 'Controller');
/**
 * Banners Controller
 *
 * @property Banner $Banner
 */
class PollsController extends AppController {
	
	private  $limit_admin = 50;
	public $uses = array('Poll','PollQuestion');
	/*
		* @Description :
		* @param - string : 
		* @param - interger:
		* @param - array:
		* @return - array:
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function ajaxAddPoll(){
		$this->layout = false;
		$this->autoRender = false;
		if($this->Session->check('Poll')==true){
			// Lấy ra thời gian người dùng click bình chọn tiếp
			$time_new = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			$result_time = $time_new - $this->Session->read('Poll');
			if($result_time<100){
				$pending = 100 - $result_time;
				echo round(($pending/60),2);
			}else{
				$this->Session->delete('Poll');
				echo "0.1";
			}
		}else{
			$time = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			$this->Session->write('Poll',$time);			// Lưu Session thời gian người dùng click bình chọn
				
			$ques_ids = array();
			$ans_ids = array();
			$i=0;
			
			foreach ($_POST as $key=>$val){
				if($i>0){
					$ques_ids[] = $key;		// Gán mảng id của câu hỏi
					$ans_ids[]=$val;		// Gan mảng id của câu trả lời
				}
				$i++;
			}
			// lay ra so luot binh chon cho cau tra loi nay va luu vao trong DB
			foreach ($ans_ids as $key_ans=>$val_ans){
				$this->loadModel('Poll');
				$count_ques = $this->Poll->read('number',$val_ans);
				$count_ques = $count_ques['Poll']['number'] + 1;
				$this->Poll->id = $val_ans;
				$this->Poll->set('number',$count_ques);
				$this->Poll->save();
			}
				
			// Luu lai tong so luot binh chon cho cau hoi ma nguoi dung co tick cho cau tra loi
			foreach ($ques_ids as $key_ques=>$val_ques){
				$this->loadModel('PollQuestion');
				$total_number = $this->PollQuestion->read('total',$val_ques);
				$this->PollQuestion->id = $val_ques;
				$this->PollQuestion->set('total',$total_number['PollQuestion']['total']+1);
				$this->PollQuestion->save();
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
	public function ajaxResultPoll(){
		$this->layout = 'ajax';
		if(!$this->request->is('ajax'))  throw new NotFoundException(__('Trang này không tồn tại',true));
		
		$lang = $this->params['lang'];
		// Lấy ra id và ten cua cau hoi
		$this->loadModel('PollQuestion');
		$a_poll_ques = $this->PollQuestion->find('all',array('conditions'=>array('status'=>1,'lang'=>$lang),'recursive'=>-1,'fields'=>array('id','name','total')));
		$a_id_poll = array();
		foreach ($a_poll_ques as $val_poll){
			// Gan lai mang id cua cau hoi
			$a_id_poll[] = $val_poll['PollQuestion']['id'];
		}
		// Tim tat ca cac cau tra loi tuong ung cac cau hoi tren
		$this->loadModel('Poll');
		$a_poll_questions = $this->Poll->find('all',array('conditions'=>array('status'=>1,'lang'=>$lang,'poll_question_id'=>$a_id_poll),'fields'=>array('description','poll_question_id','id','number'),'recursive'=>-1));
		$i=0;
		foreach ($a_poll_questions as $val_poll_question){
			$j=0;
			foreach ($a_poll_ques as $val_poll){
				if($a_poll_questions[$i]['Poll']['poll_question_id'] == $a_poll_ques[$j]['PollQuestion']['id']){
					$total_answer =$a_poll_ques[$j]['PollQuestion']['total'];
					$phantram = ($a_poll_questions[$i]['Poll']['number']/$total_answer)*100;
					$a_poll_questions[$i]['Poll']['phantram'] = $phantram;
					// Gan mang cau tra loi vao mang cau hoi tuong ung
					$a_poll_ques[$j]['Poll'][] = $a_poll_questions[$i];
				}
				$j++;
			}
			$i++;
		}
		$this->set('a_poll_ques_c',$a_poll_ques);
	}
	
	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/
	
	/*
		* @Description :
		* @param - string : 
		* @param - interger:
		* @param - array:
		* @return - array:
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function admin_index(){
		
		$lang = $this->Session->read('lang');
		$a_conditions = array('PollQuestion.lang'=>$lang);
		
		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $id){
						$a_info_ans = $this->Poll->read(array('number','poll_question_id'),$id);
						// Lấy ra tổng số phiều bầu của câu hỏi chưa câu trả lời này
						$total_ans_for_ques = $this->PollQuestion->read('total',$a_info_ans['Poll']['poll_question_id']);
						// Lấy ra trạng thái trước đó của câu trả lời
						$status_old = $this->Poll->read('status',$id);
						// Tính lại tổng số phiếu bầu cho câu hỏi chứa câu trả lời này
						if($status_old['Poll']['status']==1){
							$total_new = $total_ans_for_ques['PollQuestion']['total'];
						}elseif($status_old['Poll']['status']==0){
							$total_new = $total_ans_for_ques['PollQuestion']['total'] + $a_info_ans['Poll']['number'];
						}
						// Lưu lại số tổng số phiếu bầu cho câu hỏi
						$this->PollQuestion->id = $a_info_ans['Poll']['poll_question_id'];
						$this->PollQuestion->set('total',$total_new);
						$this->PollQuestion->save();
						// Lưu lại trạng thái kích hoạt của câu trả lời
						$this->Poll->id=$id;
						$this->Poll->set('status',1);
						$this->Poll->save();
					}
					$message = __('Information đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $id){
						$a_info_ans = $this->Poll->read(array('number','poll_question_id'),$id);
						// Lấy ra tổng số phiều bầu của câu hỏi chưa câu trả lời này
						$total_ans_for_ques = $this->PollQuestion->read('total',$a_info_ans['Poll']['poll_question_id']);
						// Lấy ra trạng thái trước đó của câu trả lời
						$status_old = $this->Poll->read('status',$id);
						// Tính lại tổng số phiếu bầu cho câu hỏi chứa câu trả lời này
						$total_new = $total_ans_for_ques['PollQuestion']['total'] - $a_info_ans['Poll']['number'];
						// Lưu lại số tổng số phiếu bầu cho câu hỏi
						$this->PollQuestion->id = $a_info_ans['Poll']['poll_question_id'];
						$this->PollQuestion->set('total',$total_new);
						$this->PollQuestion->save();
						// Lưu lại trạng thái kích hoạt của câu trả lời
						$this->Poll->id=$id;
						$this->Poll->set('status',0);
						$this->Poll->save($this->data);
					}
					$message = __('PollQuestion đã được bỏ kích hoạt');
					break;
				case 'trashes':
					foreach ($_POST['chkid'] as $val){
						$this->trashItem($val);
					}
					$message = __('PollQuestion đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}
		
		$this->loadModel('PollQuestion');
		$this->loadModel('Poll');
		$this->paginate = array(
					'conditions'=>array($a_conditions),
					'limit'=>20,'page'=>1,
					'order'=>array('Poll.id'=>"desc")
					);
		$this->set('polls', $this->paginate('Poll'));
		
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
	public function admin_ajaxDeleteItem() {
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		$id = $_POST['id'];
		$this->loadModel('Poll');
		$this->loadModel('PollQuestion');
		// Lấy ra số phiếu bầu và id của câu hỏi chứa câu trả lời này
		$a_info_ans = $this->Poll->read(array('number','poll_question_id'),$id);
		// Lấy ra tổng số phiều bầu của câu hỏi chưa câu trả lời này
		$total_ans_for_ques = $this->PollQuestion->read('total',$a_info_ans['Poll']['poll_question_id']);
		// Tính lại tổng số phiếu bầu cho câu hỏi chưa câu trả lời này
		$total_new = $total_ans_for_ques['PollQuestion']['total'] - $a_info_ans['Poll']['number'];
		if ($this->Poll->delete($id)) {
			// Lưu lại tổng số phiếu bầu cho câu hỏi chưa câu trả lời này
			$this->PollQuestion->id = $a_info_ans['Poll']['poll_question_id'];
			$this->PollQuestion->set('total',$total_new);
			$this->PollQuestion->save();
			return true;
		}else return false;
		
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
			
			if(empty($this->request->data['Poll']['sort'])){
				$this->request->data['Poll']['sort'] = $this->Poll->find('count',array('conditions'=>array('Poll.lang'=>$this->lang,'Poll.poll_question_id'=>$this->request->data['Poll']['poll_question_id'])))+1;
			}
			$this->request->data['Poll']['lang'] = $lang;
			//Ngôn ngữ
			$this->Poll->create();
			if ($this->Poll->save($this->request->data)) {
				$id = $this->Poll->getLastInsertID();
		
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
		$this->loadModel('PollQuestion');
		$a_poll_question = $this->PollQuestion->find('list', array('conditions'=>array('PollQuestion.lang'=>$lang,'PollQuestion.status'=>1)));
		$this->set('a_poll_question_c',$a_poll_question);
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
		$this->Poll->id = $id;
		if (!$this->Poll->exists()) throw new NotFoundException(__('Invalid'));
		
		$lang = $this->Session->read('lang');
		if ($this->request->is('post') || $this->request->is('put')) {
			if(empty($this->request->data['Poll']['sort'])){
				$this->request->data['Poll']['sort'] = $this->Poll->find('count',array('conditions'=>array('Poll.lang'=>$this->lang,'Poll.poll_question_id'=>$this->request->data['Poll']['poll_question_id'])))+1;
			}
			$this->request->data['Poll']['lang'] = $lang;
			if ($this->Poll->save($this->request->data)) {
		
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
			$this->request->data = $this->Poll->read(null, $id);
		}
		$this->loadModel('PollQuestion');
		$a_poll_question = $this->PollQuestion->find('list', array('conditions'=>array('PollQuestion.lang'=>$lang,'PollQuestion.status'=>1)));
		$this->set('a_poll_question_c',$a_poll_question);
		
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
		
		$this->loadModel('Poll');
		$this->loadModel('PollQuestion');
		$status = $this->Poll->read('status',$_POST['id']);
		// Lấy ra số phiếu bầu chọn và id của câu hỏi chứa câu trả lời này
		$a_info_ans = $this->Poll->read(array('number','poll_question_id'),$_POST['id']);
		// Lấy ra tổng số phiều bầu của câu hỏi chưa câu trả lời này
		$total_ans_for_ques = $this->PollQuestion->read('total',$a_info_ans['Poll']['poll_question_id']);
		if($status['Poll']['status']==1){
			$total_new = $total_ans_for_ques['PollQuestion']['total'] - $a_info_ans['Poll']['number'];
		}else{
			$total_new = $total_ans_for_ques['PollQuestion']['total'] + $a_info_ans['Poll']['number'];
		}
		// Lưu lại tổng  số phiếu bầu của câu hỏi chứa câu trả lời
		$this->PollQuestion->id = $a_info_ans['Poll']['poll_question_id'];
		$this->PollQuestion->set('total',$total_new);
		$this->PollQuestion->save();
		
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
	
		$this->Poll->id = $_POST['id'];
		$this->Poll->set(array('sort'=>$_POST['val']));
		$this->Poll->save();
		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
	}
	
	
}
