<?php
App::uses('AppController', 'Controller');
/**
 * Newsletters Controller
 *
 * @property Newsletter $Newsletter
 */
class NewslettersController extends AppController {
	
	private  $limit_admin = 50;
	public $uses = array('Newsletter','Contact','Order');

	/*
	* @Description	: Ghi email khach hang vao csdl
	* 
	* @return 	: void
	* @Author	: Hoang Tuan Anh - tuananh@url.vn
	*/
	public function ajaxSaveEmail(){
		$this->layout = false;
		$this->autoRender = false;
		$txt = '';
		if(empty($_POST)) throw new NotFoundException(__('Trang này không tồn tại',true));
		
		$data = $this->request->data['Newsletter'];
		
		if(!empty($data['email'])){
			$data['email']=$this->Oneweb->htmlEncode($data['email']);
			
			$check = $this->Oneweb->checkEmail($data['email']);		//Ktra định dạng Email
			
			if($check){
				//Ktra Email này đăng ký chưa
				$check_email = $this->Newsletter->find('first',array('conditions'=>array('email'=>$data['email']),'fields'=>array('id')));
				if(empty($check_email)){
					$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
					//Get Ip or Proxy
					if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
						$data['ip'] = $_SERVER["HTTP_X_FORWARDED_FOR"];
						$data['proxy'] = $_SERVER['REMOTE_ADDR'];
					}else{
						$data['ip'] = $_SERVER['REMOTE_ADDR'];
						$data['proxy'] = '';
					}
					$this->Newsletter->create();
					if ($this->Newsletter->save($this->data)) {
						$txt = __('Email đã được đăng ký thành công');
					}
				}else{
					$txt = __('Email này đã được đăng ký');
				}
			}else{
				$txt = __('Sai định đạng Email');
			}
		}else{
			$txt = __('Bạn chưa nhập Email');
		}
		
		return $txt;
	}
	
	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/
	
	/**
	 * @Description : 
	 *
	 * @return 	: 
	 * @Author 	: Phuong Hoang - phuong@url.vn
	 */
	public function admin_exportEmail(){
		if(!empty($_POST['export'])){
			$item = $_POST['data']['Newsletter'];
			
			$item['from_date'] = !empty($item['from_date'])?$item['from_date']:'1/1/1';
			$item['to_date'] = !empty($item['to_date'])?$item['to_date']:date('n/j/Y');
			$f_date = explode('/',$item['from_date']);
			$t_date = explode('/',$item['to_date']);
			
			$from_time = mktime(0,0,0,$f_date[0],$f_date[1],$f_date[2]);   //mktime(hour,minute,second,month,day,year);
			$to_time = mktime(23,59,59,$t_date[0],$t_date[1],$t_date[2]);
			
		
			if($item['Newsletters']=='0' && $item['Contact']=='0' && $item['Order']=='0' ){
				$this->set('errorMessage', __('Yêu cầu chọn Modules',true)); 
        		$this->render(); 
			}else{
				if($item['type_file'] == 'email1,email2,email3,...'){
					$email_newsletter = array();
					$email_contact = array();
					$email_order = array();
					if($item['Newsletters'] == 'Newsletter'){
						$email_newsletter = $this->Newsletter->find('all',array(
							'fields'=>'DISTINCT email',
							'conditions'=>array('created >='=>$from_time,'created <='=>$to_time)
						));
						$tmp=array();
						foreach ($email_newsletter as $val){
							$tmp[]=strtolower($val['Newsletter']['email']);
						}
						$email_newsletter = $tmp;
						
					}
					if($item['Contact'] == 'Contact'){
						$email_contact = $this->Contact->find('all',array(
							'fields'=>array('DISTINCT email'),
							'conditions'=>array('created >='=>$from_time,'created <='=>$to_time),
							'order'=>'created asc',
							'recursive'=>-1
						));
						$tmp=array();
						foreach ($email_contact as $val){
							$tmp[]=strtolower($val['Contact']['email']);
						}
						$email_contact = $tmp;
					}
					if($item['Order'] == 'Order'){
						$email_order = $this->Order->find('all',array(
							'fields'=>array('DISTINCT email'),
							'conditions'=>array('created >='=>$from_time,'created <='=>$to_time),
							'order'=>'created asc',
							'recursive'=>-1
						));
						$tmp=array();
						foreach ($email_order as $val){
							$tmp[]=strtolower($val['Order']['email']);
						}
						$email_order = $tmp;
					}
					// gop cac mang email lai
					$a_merge_emails = array_merge($email_newsletter,$email_contact,$email_order);
				
					//loai bo trung lap
					$a_merge_emails = array_unique($a_merge_emails);
					if(empty($a_merge_emails)){
						$this->Session->setFlash('<span>'.__('Không tồn tại Email nào').'</span>','default',array('class'=>'error'));
					}else{
						//Sap xep
						sort($a_merge_emails);
						
						$str = "";
						$count = count($a_merge_emails);
						$j=1;
						for($i=0;$i<$count;$i++){
							if($i<($count-1)){
								$str.=$a_merge_emails[$i].",";
								if($j==30){
									$str.="\r\n";
									$j=1;
								}
							}else{
								$str.=$a_merge_emails[$i];
							}
							$j++;
						}
						$a_merge_emails = $str;
						$this->_exportEmail($a_merge_emails);
					}
					
				 	$this->redirect($this->referer());
				}elseif($item['type_file'] == 'Firstname,Lastname,Email'){
					$email_newsletter = array();
					$email_contact = array();
					$email_order = array();
					if($item['Newsletters'] == 'Newsletter'){
						$email_newsletter = $this->Newsletter->find('all',array(
							'fields'=>array('DISTINCT email'),
							'conditions'=>array('created >='=>$from_time,'created <='=>$to_time)
						));
					
						$tmp=array();
						foreach ($email_newsletter as $val){
							if(empty($val['Newsletter']['name'])){
								$tmp[]=",,".strtolower($val['Newsletter']['email']);
							}else{
								$val['Newsletter']['name'] = str_replace(',', ' ', $val['Newsletter']['name']);
								$tmp[]=$val['Newsletter']['name'].",,".strtolower($val['Newsletter']['email']);
							}
						}
						$email_newsletter = $tmp;
						
					}
					if($item['Contact'] == 'Contact'){
						$email_contact = $this->Contact->find('all',array(
							'fields'=>array('DISTINCT email','name'),
							'conditions'=>array('created >='=>$from_time,'created <='=>$to_time),
							'order'=>'created asc',
							'recursive'=>-1
						));
						$tmp=array();
						foreach ($email_contact as $val){
							if(empty($val['Contact']['name'])){
								$tmp[]=",,".strtolower($val['Contact']['email']);
							}else{
								$val['Contact']['name'] = str_replace(',', ' ', $val['Contact']['name']);
								$tmp[]=$val['Contact']['name'].",,".strtolower($val['Contact']['email']);
							}
						}
						$email_contact = $tmp;
						
					}
					if($item['Order'] == 'Order'){
						$email_order = $this->Order->find('all',array(
							'fields'=>array('DISTINCT email','name'),
							'conditions'=>array('created >='=>$from_time,'created <='=>$to_time),
							'order'=>'created asc',
							'recursive'=>-1
						));
						$tmp=array();
						foreach ($email_order as $val){
							if(empty($val['Order']['name'])){
								$tmp[]=",,".strtolower($val['Order']['email']);
							}else{
								$val['Order']['name'] = str_replace(',', ' ', $val['Order']['name']);
								$tmp[]=$val['Order']['name'].",,".strtolower($val['Order']['email']);
							}
						}
						$email_order = $tmp;
					
					}
					// gop cac mang email lai
					$a_merge_emails = array_merge($email_newsletter,$email_contact,$email_order);
				
					//loai bo trung lap
					$a_merge_emails = array_unique($a_merge_emails);
					
					
					if(empty($a_merge_emails)){
						$this->Session->setFlash('<span>'.__('Không tồn tại Email nào').'</span>','default',array('class'=>'error'));
					}else{
						sort($a_merge_emails);						
						$limit = count($a_merge_emails);
						$tmp = array($a_merge_emails[0]);
						
						
						for($i=1;$i<$limit;$i++){
							$email1=explode(',', $a_merge_emails[$i]);						
							$email1=$email1[2];
							
							
							$flag = 1;
							$j=0;
							
							//Kiem tra xem email hien tai da ton tai trong mang tmp chua,neu co thi nhay qua
							do{
								$email2=explode(',', $tmp[$j]);
								$email2=$email2[2];
								if($email1==$email2){
									$flag=0;
									break;
								}
								$j++;
							}while($j<count($tmp));
							
							if($flag == 1){
								$tmp[]=$a_merge_emails[$i];
							}
							
						}
						
						$a_merge_emails = $tmp;
						
						//Sap xep
						sort($a_merge_emails);
					
						
						$str = 'Firstname,Lastname,Email'."\r\n";
						$count = count($a_merge_emails);
						for($i=0;$i<$count;$i++){
							if($i<($count-1)){
								$str.=$a_merge_emails[$i]."\r\n";
							}else{
								$str.=$a_merge_emails[$i];
							}
						}
						$a_merge_emails = $str;
						$this->_exportEmail($a_merge_emails);
					}
					
				 	$this->redirect($this->referer());
				
				}
				
			}
		}
	}
	
	public function _exportEmail($all_emails){
		$this->layout = '';
		$this->autoRender = '';
		header("Content-Type: plain/text");
		header("Content-Disposition: Attachment; filename=Export-email-".date('d-m-Y').".txt");
		header("Pragma: no-cache");
		echo $all_emails;
	}
	
	/**
	 * @Description : Danh sách Newsletter
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_index() {
		
		$lang = $this->Session->read('lang');
		$a_conditions = array();
		
		
		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'delete':
					foreach ($_POST['chkid'] as $val){
						$this->Newsletter->delete($val);
					}
					$message = __('Email đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success')); 
		}
		
		if(!empty($_GET['keyword']) && $_GET['keyword']!=__('Email')){	//Tu khoa
			$a_conditions = array_merge($a_conditions,array('Newsletter.email like'=>'%'.$_GET['keyword'].'%'));
		}
		
		
		$this->paginate = array(
			'conditions'=>$a_conditions,
			'contain'=>array('NewsletterCategory','NewsletterComment.id'),
			'fields'=>array('id','email','ip','proxy','created'),
			'order'=>array('created'=>'desc','sort'=>'asc'),
			'limit'=>$this->limit_admin
		);
		
		$a_newsletters = $this->paginate();
		$this->set('a_newsletters_c', $a_newsletters);
		
		$counter = $this->Newsletter->find('count',array('conditions'=>$a_conditions));
		$this->set('counter_c',$counter);
		
		//Url hiện tại
		$current_url = urlencode($this->Oneweb->curPageURL());
		$this->set('current_url_c',$current_url);
	}
	
	
	/**
	 * @Description : Xóa Newsletter sdung ajax
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxDeleteItem() {
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		
		if($this->Newsletter->delete($_POST['id'])) return true;
		else return false;
	}
}
