<?php
App::uses('AppController', 'Controller');
/**
 * Configs Controller
 *
 * @property Config $Config
 */
class ConfigsController extends AppController {

	public $helpers = array('CkEditor');
	public $uses = array('Config','Currency');


	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/

	public function beforeFilter() {
		parent::beforeFilter();
		$admin = $this->Auth->user();
		if ($admin['role'] != 'admin') throw new NotFoundException(__('Trang này không tồn tại',true));
	}
	/**
	 * @Description : Cấu hình site
	 *
	 * @return void
	 * @Author Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_edit() {
		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data;
            
			$error = false;
			foreach($data as $key=>$val){
				if($key!='save'){
					if($key=='Config'){		//Cấu hình chung cho cả 2 ngôn ngữ
						foreach ($val as $key2=>$val2){
							$name = $key2;
							$value = $val2;
							$this->Config->update($name, $value);
						}
					}else{					//Cấu hình riêng cho từng ngôn ngữ
						$name = $key;
						$value = serialize($val);
						$this->Config->update($name, $value);
					}
				}
			}

			$this->Session->setFlash('<span>'.__('Thông tin đã được cập nhật').'</span>','default',array('class'=>'success'));
			$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
			$this->redirect($this->referer());
		} else {
			$a_configs = $this->Config->find('all');
			$tmp2 = array();
			foreach($a_configs as $key=>$val){
				$item = $val['Config'];
				if(@unserialize($item['value'])) $tmp[$item['name']] = unserialize($item['value']);
				else $tmp2 = array_merge($tmp2,array($item['name']=>$item['value']));
			}
			$tmp['Config'] = $tmp2;

			//Lấy đơn vị tiền
			$a_currencies = $this->Currency->find('list');
			$this->set('a_currencies_c',$a_currencies);

			$this->request->data = $tmp;
		}
	}
}
