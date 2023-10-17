<?php
App::uses('AppController', 'Controller');
/**
 * Sitemaps Controller
 *
 * @property Support $Support
 */
class SitemapsController extends AppController {
	public $limit_ad = 50;
	public $uses = array();

	public function beforeFilter() {
		parent::beforeFilter();
		$admin = $this->Auth->user();
		if ($admin['role'] != 'admin') throw new NotFoundException(__('Trang này không tồn tại',true));
	}
	/**
	 * @Description : Sitemap HTML
	 *
	 * @throws 	: NotFoundException
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function html(){
		$lang = $this->params['lang'];

		//SEO
		$this->set('title_for_layout',__('Sơ đồ web'));

		//Canonical
		$a_canonical = array('controller'=>'sitemaps','action' => 'html','lang'=>$lang,'ext'=>'html');
		$this->set('a_canonical',$a_canonical);
	}


	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/

	/**
	 * @Description : Tạo file robots.txt
	 *
	 * @throws 	: NotFoundException
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_robots(){
		$curr_dir = getcwd();

		if ($this->request->is('post') || $this->request->is('put')) {
			$ft=fopen("$curr_dir/robots.txt",'w+')or exit(__('Không tìm thấy file cần mở !',true));
			$f=$this->request->data['Robot']['description']; 		//Khai báo nội dung của file
			fwrite($ft,$f); 										//Thực hiện ghi nội dung vào file
			fclose($ft);
			$this->redirect($this->referer());
		}else{
			$this->request->data['Robot']['description'] = file_get_contents("".$curr_dir."/robots.txt");
		}
	}
}
