<?php
App::uses('AppController', 'Controller');
/**
 * Information Controller
 *
 * @property Information $Information
 */
class InformationController extends AppController {

	public $helpers = array('CkEditor');
	private  $limit_admin = 50;


	/**
	 * @Description : Xem nội dung trang thông tin
	 *
	 * @throws 	: NotFoundException
	 * @param 	: string $slug, $position
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function view($slug=null,$position=null) {
		$this->set('class','detail_infomation');

		if($slug==null || $position==null) throw new NotFoundException(__('Trang này không tồn tại',true));
		$lang = $this->params['lang'];
		if($slug == 'ban-do') {
			$this->ban_do($slug);
			$this->render('ban_do');
		}
		//Đọc nội dung trang thông tin
		$a_information = Cache::read("information_view_$slug",'oneweb');
		if(empty($a_information)){
			$a_information = $this->Information->find('first',array(
				'conditions'=>array('slug'=>$slug,'position'=>$position,'status'=>1,'trash'=>0,'lang'=>$lang,'or'=>array(array('link'=>null),array('link'=>''))),
				'recursive'=>-1
			));
			if(empty($a_information)) throw new NotFoundException(__('Trang này không tồn tại',true));
			Cache::write("information_view_$slug",$a_information,'oneweb');
		}
		$item_information = $a_information['Information'];

		//Tìm các trang thông tin liên quan
		$a_related_info = Cache::read("information_view_relate_$slug",'oneweb');
		if(!$a_related_info){
			$a_related_info = $this->Information->find('all',array(
					'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'slug !='=>$slug,'position'=>$position),
					'fields'=>array('id','name','lang','slug','parent_id','link','rel','target','position','meta_title'),
					'order'=>'sort asc',
					'recursive'=>-1
			));
			if(empty($a_related_info)) $a_related_info = 'empty';
			Cache::write("information_view_relate_$slug",$a_related_info,'oneweb');
		}
		if(!is_array($a_related_info)) $a_related_info=array();

		$this->set('a_information_c',$a_information);
		$this->set('a_related_info_c',$a_related_info);

		//Breadcrumb
		if($item_information['parent_id']!=null){
			$a_parent = array();
			$i = 0;
			do{
				$item = $a_related_info[$i]['Information'];
				if($item['id']==$item_information['parent_id'])
					$a_parent = $item;
				$i++;
			}while (empty($a_parent) && $i<count($a_related_info));

			$a_breadcrumb[] = $a_parent;
		}
		$a_breadcrumb[] = $item_information;

		$tmp = array();
		foreach($a_breadcrumb as $val){
			$url = array('controller'=>'information','action'=>'view','lang'=>$lang,'position'=>$val['position'],'slug'=>$val['slug']);
			$children = array();
			if($val['parent_id']!=null){
				foreach ($a_related_info as $val2){
					$item2 = $val2['Information'];
					$url = array('controller'=>'information','action'=>'view','lang'=>$lang,'position'=>$item2['position'],'slug'=>$item2['slug'],'ext'=>'html');

					if($item2['parent_id']==$val['parent_id'])
						$children[] = array(
										'name'=>$item2['name'],
										'meta_title'=>$item2['meta_title'],
										'url'=>$url,
									);
				}
			}
			$tmp[] = array(
							'name'=>$val['name'],
							'meta_title'=>$val['meta_title'],
							'url'=>$url,
							'child'=>$children
						);
		}
		$a_breadcrumb = $tmp;

		$this->set('a_breadcrumb_c',$a_breadcrumb);

		//SEO
		if ( ! empty($item_information['meta_title'])) $this->set('title_for_layout',$item_information['meta_title']);
		if ( ! empty($item_information['meta_keyword'])) $this->set('meta_keyword_for_layout',$item_information['meta_keyword']);
		if ( ! empty($item_information['meta_description'])) $this->set('meta_description_for_layout',$item_information['meta_description']);
		if ( ! empty($item_information['meta_robots'])) $this->set('meta_robots_for_layout',$item_information['meta_robots']);

		//Canonical
		$a_canonical = array('controller'=>'information','action' => 'view','lang'=>$lang,'position'=>$item_information['position'],'slug'=>$item_information['slug']);
        if(!empty($item_information['parent_id'])) $a_canonical = array_merge($a_canonical,array('ext'=>'html'));
		$this->set('a_canonical',$a_canonical);
	}

	function ban_do(){
		$this->set('class','detail_infomation');

		$lang = $this->params['lang'];
		//Đọc cấu hình
		$a_configs = $this->_getConfig('contact');
		$this->set('a_configs_c',$a_configs);

		//Breadcrumb
		$a_breadcrumb[] = array(
				'name'=>__('Bản đồ'),
				'meta_title'=>__('Bản đồ'),
				'url'=>'',
		);
		$this->set('a_breadcrumb_c',$a_breadcrumb);

		//SEO
		if ( ! empty($item_information['meta_title'])) $this->set('title_for_layout',$item_information['meta_title']);
		if ( ! empty($item_information['meta_keyword'])) $this->set('meta_keyword_for_layout',$item_information['meta_keyword']);
		if ( ! empty($item_information['meta_description'])) $this->set('meta_description_for_layout',$item_information['meta_description']);
		if ( ! empty($item_information['meta_robots'])) $this->set('meta_robots_for_layout',$item_information['meta_robots']);
	}

	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/

	/**
	 * @Description : Danh sách information
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_index() {
		$lang = $this->Session->read('lang');
		$a_conditions = array('Information.lang'=>$lang,'Information.trash'=>0,'Information.parent_id'=>null);

		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->Information->id = $val;
						$this->Information->set(array('status'=>1));
						$this->Information->save();
					}
					$message = __('Information đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->Information->id = $val;
						$this->Information->set(array('status'=>0));
						$this->Information->save();
					}
					$message = __('Information đã được bỏ kích hoạt');
					break;
				case 'trashes':
					foreach ($_POST['chkid'] as $val){
						$this->trashItem($val);
					}
					$message = __('Information đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}

		if(!empty($_GET['keyword']) && $_GET['keyword']!=__('Tìm kiếm')){	//Tu khoa
			$a_conditions = array_merge($a_conditions,array('Information.name like'=>'%'.$_GET['keyword'].'%'));
		}

		$this->Information->bindModel(array(
				'hasMany'=>array(
					'ChildInformation' => array(
					'className' => 'Information',
					'foreignKey' => 'parent_id',
					'dependent' => false,
					'conditions' => array('trash'=>0),
					'order' => 'sort asc',
				)
			)
		));

		$this->paginate = array(
			'conditions'=>$a_conditions,
			'fields'=>array('id','name','link','slug','sort','status','lang','link','position'),
			'contain'=>array('ChildInformation.id','ChildInformation.name','ChildInformation.position','ChildInformation.link','ChildInformation.slug','ChildInformation.sort','ChildInformation.status','ChildInformation.lang'),
			'order'=>array('sort'=>'asc'),
			'limit'=>$this->limit_admin
		);

		$a_information = $this->paginate();
		$this->set('a_information_c', $a_information);

		$counter = $this->Information->find('count',array('conditions'=>$a_conditions));
		$this->set('counter_c',$counter);

		//Tìm các bản ghi bị lỗi - Lỗi khi trang cha bị xóa, trang con vẫn còn
		$this->Information->bindModel(array(
			'belongsTo'=>array(
				'ParentInformation' => array(
					'className' => 'Information',
					'foreignKey' => 'parent_id',
					'conditions' => array('ParentInformation.trash'=>0),
					'fields' => '',
					'order' => ''
				)
			)
		));
		$a_information_error = $this->Information->find('all',array(
			'conditions'=>array('Information.parent_id !='=>null,'Information.trash'=>0),
			'fields'=>array('id','name','link','slug','sort','status','lang','position','ParentInformation.id'),
			'recursive'=>0
		));
		$tmp = '';
		foreach($a_information_error as $val){
			if($val['ParentInformation']['id']==null) $tmp[] = $val['Information'];
		};
		$a_information_error = $tmp;
		$this->set('a_information_error_c',$a_information_error);

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
	 */
	public function admin_ajaxChangeStatus(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		$return = $this->changeStatusInformation($_POST['id'],null,true);
		return json_encode($return);
	}


	/**
	* @Description	: Thay đổi trang thái danh mục
	*
	* @thows	: NotFoundException
	* @param	: int	$id, $status = 1 or 0
	* @param	: boolean $return=true: trả lại mảng dữ liệu sdung khi dùng ajax để gọi
	* @return	: boolean	$return
	* @Author	: Hoang Tuan Anh - tuananh@url.vn
	*/
	private function changeStatusInformation($id=null,$status=null,$return=false){
		if($id==null) throw new NotFoundException(__('Invalid'));

		//Kiểm tra trạng thái hiện tại
		$this->Information->recursive = -1;
		$a_information = $this->Information->read('parent_id,status',$id);
		$a_information = $a_information['Information'];

		if($status==null) $status = ($a_information['status'])?0:1;
		$a_ids = array($id);		//Mang id cần set trạng thái

		if(empty($a_information['parent_id'])){		//Trg hợp là mục cha
			//Tìm các mục con của nó
			$a_child_information = $this->Information->find('all',array('conditions'=>array('parent_id'=>$id),'fields'=>array('id','status')));

			foreach ($a_child_information as $val){
				$a_ids[] = $val['Information']['id'];
			}
		}else{			//Tr hợp là mục con
			if($status) $a_ids[] = $a_information['parent_id'];
		}
		for($i=0;$i<count($a_ids);$i++){
			$this->Information->id = $a_ids[$i];
			$this->Information->set(array('status'=>$status));
			$this->Information->save();
		}

		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache

		if($return){
			$tmp['cl'] = ($status)?'active':'unactive';
			$tmp['id'] = $a_ids;
			return $tmp;
		}
	}

	/**
	 * @Description : Sắp xếp information
	 *
	 * @throws 	: NotFoundException
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	function admin_ajaxChangeSort(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['val']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		$this->Information->id = $_POST['id'];
		$this->Information->set(array('sort'=>$_POST['val']));
		$this->Information->save();
		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
	}


	/**
	 * @Description : Thêm information
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		$lang = $this->Session->read('lang');

		if ($this->request->is('post')) {
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['Information'];

			//Slug - meta title
			if($oneweb_seo){
				//Slug
				if(empty($data['slug'])) $data['slug'] = $data['name'];

				//Meta title
				if(empty($data['meta_title'])) $data['meta_title'] = $data['name'];
			}else{
				//Slug
				$data['slug'] = $data['name'];

				//Meta title
				$data['meta_title'] = $data['name'];
			}

			//Lấy danh sách slug đã tồn tại
			$a_all_slugs = $this->Information->find('list',array('conditions'=>array('lang'=>$lang),'fields'=>'slug'));

			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);

			//position
			if(!empty($data['parent_id'])){			//Thiết lập position theo cha của nó
				$a_information_parent = $this->Information->read('position',$data['parent_id']);
				$data['position']= $a_information_parent['Information']['position'];
			}elseif(empty($data['position'])){		//Thiết lập position mới - position này tự sinh sẽ được cấp phát từ >100
				$position = 100;
				$flag = true;
				do{					//Kiểm tra position này đã tồn tại chứa, nếu đã tồn tại tăng thêm 1.
					$check = $this->Information->find('count',array('conditions'=>array('position'=>$position),'recursive'=>-1));
					if(!$check) $flag = false;
					else $position++;
				}while($flag);
				$data['position'] = $position;
			}

			//Ngôn ngữ
			$data['lang'] = $lang;
			$this->Information->create();
			if ($this->Information->save($data)) {
				$id = $this->Information->getLastInsertID();

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

		//Danh sách mục cha
		$a_information = $this->Information->find('list',array('conditions'=>array('lang'=>$lang,'parent_id'=>null,'trash'=>0),'recursive'=>-1));
		$this->set('a_information_c',$a_information);
	}

	/**
	 * @Description : Sửa information
	 *
	 * @throws NotFoundException
	 * @param int $id
	 * @return void
	 * @Author Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_edit($id = null) {
		$this->Information->id = $id;
		if (!$this->Information->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');

		if ($this->request->is('post') || $this->request->is('put')) {
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['Information'];

			//Slug - meta title
			if($oneweb_seo){
				//Slug
				if(empty($data['slug'])) $data['slug'] = $data['name'];

				//Meta title
				if(empty($data['meta_title'])) $data['meta_title'] = $data['name'];
			}else{
				//Slug
				$data['slug'] = $data['name'];

				//Meta title
				$data['meta_title'] = $data['name'];
				$data['meta_keyword'] = '';
				$data['meta_description'] = '';
			}

			//Lấy danh sách slug đã tồn tại
			$a_all_slugs = $this->Information->find('list',array('conditions'=>array('lang'=>$lang,'id !='=>$id),'fields'=>'slug','recursive'=>-1));

			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);

			//position
			if(!empty($data['parent_id'])){
				$a_information_parent = $this->Information->read('position',$data['parent_id']);
				$data['position']= $a_information_parent['Information']['position'];
			}else{
				//Trường hợp đang là mục con, thiết lập thành mục cha
				if(!empty($data['parent_id_old'])) $data['position'] = 0;
			}

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
			$this->request->data = $this->Information->read(null, $id);
		}

		//Danh sách mục cha
		$a_information = $this->Information->find('list',array('conditions'=>array('lang'=>$lang,'parent_id'=>null,'trash'=>0),'recursive'=>-1));
		$this->set('a_information_c',$a_information);
	}

// 	/**
// 	 * @Description : Đưa page vào thùng rác sdung ajax
// 	 *
// 	 * @throws 	: NotFoundException
// 	 * @return 	: boolean
// 	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
// 	 */
// 	public function admin_ajaxDeleteItem() {
// 		$this->layout = false;
// 		$this->autoRender = false;
// 		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

// 		$a_ids = array($_POST['id']);
// 		$a_information = $this->Information->find('first',array('conditions'=>array('id'=>$_POST['id']),'fields'=>array('id'),'contain'=>array('ChildInformation.id')));

// 		if(!empty($a_information['ChildInformation'])){
// 			foreach($a_information['ChildInformation'] as $val){
// 				$a_ids[] = $val['id'];
// 			}
// 		}
// 		if($this->Information->delete($_POST['id'])){
// 			return json_encode($a_ids);
// 		}else return false;
// 	}



	/**
	 * @Description : Đưa page vào thùng rác sdung ajax
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxTrashItem() {
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		return $this->trashItem($_POST['id']);
	}

	/**
	 * @Description : Đưa page vào thùng rác
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int data
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function trashItem($id){
		$a_child_ids = array();
		$a_ids = array($id);
		$this->Information->bindModel(array(
			'hasMany'=>array(
				'ChildInformation' => array(
					'className' => 'Information',
					'foreignKey' => 'parent_id',
					'dependent' => false,
					'conditions' => array('trash'=>0),
				)
			)
		));
		$a_information = $this->Information->find('first',array('conditions'=>array('id'=>$id,'trash'=>0),'fields'=>array('id','name'),'contain'=>array('ChildInformation.id')));

		//Đưa vào thùng rác
		if(!empty($a_information)){
			if(!empty($a_information['ChildInformation'])){
				foreach($a_information['ChildInformation'] as $val){
					$a_child_ids[] = $val['id'];
					$a_ids[] = $val['id'];
				}
			}

			$data['name'] = $a_information['Information']['name'];
			$data['item_id'] = $a_information['Information']['id'];
			$data['model'] = 'Information';
			$data['child_id'] = implode(',', $a_child_ids);
			$data['child_model'] = 'Information';
			$description = 'Trang thông tin';
			if(!empty($a_child_ids)) $description.=' (Có '.(count($a_child_ids)).' trang con)';

			$data['description'] = $description;
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

			$this->loadModel('Trash');
			$this->Trash->create();
			if($this->Trash->save($data)){
				$this->Information->updateAll(array('Information.trash'=>1),array('Information.id'=>$a_ids));
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				return json_encode($a_ids);;
			}
		}

		return false;
	}
}
