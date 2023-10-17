<?php
App::uses('AppController', 'Controller');
/**
 * Orders Controller
 *
 * @property Order $Order
 */
class OrdersController extends AppController {

	public $helpers = array('CkEditor');
	private  $limit_admin = 50;
	private $from_email = 'iic@nuce.edu.vn';

	/**
	 * @Description : Show giỏ hàng
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function ajaxShowCart(){
		$this->layout = 'ajax';

		if(!$this->request->is('ajax'))  throw new NotFoundException(__('Trang này không tồn tại',true));

		$result = $this->getDetailCart();
		if(!empty($result)) {
			$this->set('a_products_cart',$result['detail']);
			$this->set('total_cart',$result['total']);
			$this->set('surcharge',$result['surcharge']);
		}
	}

	/**
	 * @Description : Lấy thông tin chi tiết đơn hàng
	 *
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function getDetailCart(){
		$lang = $this->params['lang'];

		$a_products = $this->Session->read("Order_$lang");
		$result = array();
		if(!empty($a_products)){
			$a_ids = array();
			foreach($a_products as $val) $a_ids[] = $val['id'];

			$this->loadModel('Product');
			$this->loadModel('ProductAttribute');
			$a_list_products = $this->Product->find('all',array(
					'conditions'=>array('status'=>1,'lang'=>$lang,'id'=>$a_ids,'trash'=>0),
					'fields'=>array('id','name','image','price','discount','price_new','discount_unit','promotion','quantity','count_buyed'),
					'recursive'=>-1
			));

			$total = 0;
			foreach($a_list_products as $key=>$val){
				$item = $val['Product'];
				$qty = 1;
				$color = '';
				$size = '';
				foreach($a_products as $val2)
					if($val2['id'] == $item['id']) {
						$qty = $val2['qty'];
						if(!empty($val2['color'])) {
							$color = $val2['color'];
						}
						if(!empty($val2['size'])) {
							$size = $val2['size'];
							$product_size = $this->ProductAttribute->ProductSize->find('first', array(
								'conditions'=>array('size'=>$size)
							));
							$a_list_products[$key]['ProductSize'] = $product_size['ProductSize'];

							$product_attribute = $this->ProductAttribute->find('first', array(
								'conditions'=>array('product_id'=>$item['id'], 'product_color_id'=>$product_color['ProductColor']['id'], 'product_size_id'=>$product_size['ProductSize']['id'])
							));
							$a_list_products[$key]['ProductAttribute'] = $val2['size'];
						}
					}
				$a_list_products[$key]['Product']['qty'] = $qty;
				$a_list_products[$key]['Product']['color'] = $color;
				$a_list_products[$key]['Product']['size'] = $size;
				//Tính giá
				if($item['price_new']) $price = $item['price_new'];
				else $price = $item['price'];
// 				if(!empty($item['discount'])){
// 					if($item['discount_unit'])	$price = $price-($price*$item['discount']/100);		//Giảm giá theo %
// 					else $price = $price - $item['discount'];										//Giảm số tiền nhập vao
// 				}

				$total+=($price*$qty);
			}

			if($this->Session->check('order_detail.surcharge'))
				$surcharge = $this->Session->read('order_detail');
			else  {
//				if($total < 600000) $surcharge['surcharge'] = 30000;
					 $surcharge['surcharge'] = 0;
			}
			$result = array(
					'detail'=>!empty($a_list_products)?$a_list_products:'0',
					'total'=>!empty($total)?($total+$surcharge['surcharge']):0,
					'surcharge'=>$surcharge['surcharge']
				);
		}
		return $result;
	}

	/**
	 * @Description : Đưa sản phẩm vào giỏ hàng
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function ajaxAddToCart(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id']) || empty($_POST['qty'])) throw new NotFoundException(__('Trang này không tồn tại',true));
		$id = $_POST['id'];
		$qty = $_POST['qty'];
		$add = (empty($_POST['add']))?false:$_POST['add'];
		$lang = $this->params['lang'];

		$a_products = array();
		if($this->Session->check("Order_$lang")) $a_products = $this->Session->read("Order_$lang");
		//lấy số lượng có trong kho
		$this->loadModel('Product');
		$quantity = $this->Product->find('first',array(
				'conditions'=>array('status'=>1,'trash'=>0,'id'=>$id),
				'fields'=>array('quantity'),
				'recursive'=>-1
				));
		$flag = false;
		$total_qty = 0;
		for($i=0;$i<count($a_products);$i++){
			if($a_products[$i]['id']==$id) {
				if($add=='true' && $a_products[$i]['qty']<$quantity['Product']['quantity']){
					$a_products[$i]['qty'] = ++$a_products[$i]['qty'];
				}else{
					$a_products[$i]['qty'] = $qty;
				}
// 				$a_products[$i]['qty'] = ($add=='true')?(++$a_products[$i]['qty']):$qty;
				$flag = true;
			}
			$total_qty+=$a_products[$i]['qty'];
		}

		if(!$flag){
			if($_POST['color'] != 'undefined') $a_products[] = array('id'=>$id,'qty'=>$qty, 'color'=>$_POST['color'], 'size'=>$_POST['size']);
			else $a_products[] = array('id'=>$id,'qty'=>$qty);
			$total_qty+=$qty;
		}
		$this->Session->write("Order_$lang",$a_products);
		return $total_qty;
	}

	public function ajaxFastOrder(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id']) || empty($_POST['qty'])) throw new NotFoundException(__('Trang này không tồn tại',true));
		$id = $_POST['id'];
		$qty = $_POST['qty'];
		$add = (empty($_POST['add']))?false:$_POST['add'];
		$lang = $this->params['lang'];

		$a_products = array();
		//lấy số lượng có trong kho
		$this->loadModel('Product');
		$quantity = $this->Product->find('first',array(
				'conditions'=>array('status'=>1,'trash'=>0,'id'=>$id),
				'fields'=>array('quantity'),
				'recursive'=>-1
				));
		$flag = false;
		$total_qty = 0;
		for($i=0;$i<count($a_products);$i++){
			if($a_products[$i]['id']==$id) {
				if($add=='true' && $a_products[$i]['qty']<$quantity['Product']['quantity']){
					$a_products[$i]['qty'] = ++$a_products[$i]['qty'];
				}else{
					$a_products[$i]['qty'] = $qty;
				}
// 				$a_products[$i]['qty'] = ($add=='true')?(++$a_products[$i]['qty']):$qty;
				$flag = true;
			}
			$total_qty+=$a_products[$i]['qty'];
		}

		if(!$flag){
			$a_products[] = array('id'=>$id,'qty'=>$qty);
			$total_qty+=$qty;
		}
		$this->Session->write("Order_$lang",$a_products);
		return $total_qty;
	}


	/**
	 * @Description : Xóa sản phẩm khỏi giỏ hàng
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function ajaxDelProCart(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Trang này không tồn tại',true));
		$id = $_POST['id'];
		$lang = $this->params['lang'];

		$a_products = $this->Session->read("Order_$lang");

		$tmp = array();
		$total_qty = 0;
		foreach ($a_products as $val)
			if($val['id']!=$id) {
				$tmp[] = $val;
				$total_qty+=$val['qty'];
			}

		$a_products = $tmp;

		if(!empty($a_products)) $this->Session->write("Order_$lang",$a_products);
		else $this->Session->delete("Order_$lang");
		return $total_qty;
	}


	/**
	 * @Description :	Lấy thông tin khách hàng
	 *
	 * @params 	:
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function info(){
		$lang = $this->params['lang'];
		if(!$this->Session->check("Order_$lang")) throw new NotFoundException(__('Trang này không tồn tại',true));

		$order_info = $this->getDetailCart();
		if(empty($order_info))  $this->redirect(array('controller'=>'pages','action'=>'home','lang'=>$lang));
		$this->set('order_info_c',$order_info);

		$a_config_info = $this->_getConfig('order');
		$this->set('a_config_info', $a_config_info);
		if ($this->request->is('post')) {
			$csrf_token = $this->Session->read('order_csrf_token');
			$data = $this->request->data['Order'];

			if($csrf_token == $data['csrfToken']) {
				$this->Session->delete('order_csrf_token');
				//Loại bỏ ký tự đặc biệt
				if (isset($data['name'])) $data['name'] = $this->Oneweb->htmlEncode($data['name']);
				if (isset($data['phone'])) $data['phone'] = $this->Oneweb->htmlEncode($data['phone']);
				if (isset($data['email'])) $data['email'] = $this->Oneweb->htmlEncode($data['email']);
				if (isset($data['address'])) $data['address'] = $this->Oneweb->htmlEncode($data['address']);
				if (isset($data['message'])) $data['message'] = $this->Oneweb->htmlEncode($data['message']);
				if (isset($data['message'])) $data['message'] = $this->Oneweb->htmlEncode($data['message']);
				if (isset($data['method_payment'])) $data['method_payment'] = $this->Oneweb->htmlEncode($data['method_payment']);
				if (isset($data['bank_info'])) $data['bank_info'] = $this->Oneweb->htmlEncode($data['bank_info']);

				// $this->Order->set($data);
				// if($this->Order->validates()){
					$this->Session->write("OrderCustomer_$lang",$data);

					$a_customer = $this->Session->read("OrderCustomer_$lang");		//Thông tin khách hàng
					$this->set('a_customer_c',$a_customer);

					$order_info = $this->getDetailCart();
					if(empty($order_info))  $this->redirect(array('controller'=>'pages','action'=>'home','lang'=>$lang));
					$data = array_merge($data,$a_customer);

					//transaction_code
					$time = date('H')*60*60+date('i')*60+date('s');
					$before_time = 5-strlen($time);
					if($before_time>0){
						for($i=0; $i<$before_time;$i++){
							$time = '0'.$time;
						}
					}
					$data['transaction_code'] = date('dmy').$time;

					//Lấy id của danh mục đơn hàng được thiết lập mặc định
					$a_category = $this->Order->OrderCategory->find('first',array(
						'conditions'=>array('set_default'=>1),
						'order'=>'sort asc',
						'recursive'=>-1
					));

					$data['order_category_id'] = (!empty($a_category))?$a_category['OrderCategory']['id']:'';

					$data['member_id'] = 0;		//Id của thành viên đã đặt hàng
					//Nội dung đơn hàng
					$data['content'] = serialize($order_info['detail']);

//					if($order_info['total'] < 600000) $surcharge = 30000;
//					else
                    $surcharge = 0;
					$data['surcharge'] = $surcharge;
					$data['total'] = $order_info['total'];
					$data['view'] = 0;
					$data['alarm'] = 0;
					$data['status'] = 'Pending';
					$data['unit_default'] = 'VNĐ';

					//Get Ip or Proxy
					if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
						$data['ip'] = $_SERVER["HTTP_X_FORWARDED_FOR"];
						$data['proxy'] = $_SERVER['REMOTE_ADDR'];
					}else{
						$data['ip'] = $_SERVER['REMOTE_ADDR'];
						$data['proxy'] = '';
					}

					$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
					// debug($data);die;
					// debug($this->Order->save($data));die;
					$this->Order->create();
					if($this->Order->save($data)){
						$id = $this->Order->getLastInsertID();
						$order = $this->Order->findById($id);

						//trừ số lượng
						$this->loadModel('ProductAttribute');
						$this->loadModel('Product');
						foreach ($order_info['detail'] as $key => $value) {
							if(!empty($value['ProductColor'])) {
								$attribute = $this->ProductAttribute->find('first', array(
									'conditions'=>array('product_id'=>$value['Product']['id'], 'product_color_id'=>$value['ProductColor']['id'], 'product_size_id'=>$value['ProductSize']['id']),
									'recursive'=>-1
								));
								if(!empty($attribute)) {
									$this->ProductAttribute->id = $attribute['ProductAttribute']['id'];
									$this->ProductAttribute->set('qty', $attribute['ProductAttribute']['qty']-$value['Product']['qty']);
									$this->ProductAttribute->save();
								}
							}

							//Tăng số người mua
							$count_buyed = $value['Product']['count_buyed']+1;
							$this->Product->id=$value['Product']['id'];
							$this->Product->saveField('count_buyed', $count_buyed);
						}
						$this->Session->write('order_detail', $order['Order']);
						$this->_sendEmail($data);
						$this->redirect(array('action'=>'thanks','lang'=>$lang,'ext'=>'html'));
					// }else{
					// 	$this->Session->setFlash('<span>Có lỗi, bạn vui lòng thử lại</span>','default',array('class'=>'error'));
					// }


				} else {
					$this->Session->setFlash('<span>Có lỗi, bạn vui lòng thử lại</span>','default',array('class'=>'error'));
				}
			}

		}
    $csrfToken = hash('sha256', strtotime(date('m/d/Y H:i:s')));
    $this->Session->write('order_csrf_token', $csrfToken);
    $this->set('csrfToken', $csrfToken);

		//Breadcrumb
		$a_breadcrumb[] = array(
								'name'=>__('Thông tin giao hàng'),
								'meta_title'=>__('Thông tin giao hàng'),
								'url'=>'',
							);
		$this->set('a_breadcrumb_c',$a_breadcrumb);

		//SEO
		$this->set('title_for_layout',__('Thông tin giao hàng'));
	}

	/**
	 * @Description : Trang cám ơn sau khi đặt hàng
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function thanks(){
		$lang = $this->params['lang'];

		//if (empty($this->Session->read("Order_$lang"))) throw new NotFoundException(__('Invalid'));

		//Đọc thông tin cấu hình
		$a_product_configs = $this->_getConfig('product');
		$this->set('a_product_configs_c',$a_product_configs);
		$this->set('order_detail', $this->Session->read('order_detail'));
		$order_info = $this->getDetailCart();
		$this->set('order_info_c', $order_info);

		//Xóa session
		$this->Session->delete("OrderCustomer_$lang");
		$this->Session->delete("Order_$lang");
		$this->Session->delete('order_detail');

		//Breadcrumb
		$a_breadcrumb[] = array(
								'name'=>__('Thông tin giao hàng'),
								'meta_title'=>__('Thông tin giao hàng'),
								'url'=>'javascript:;',
							);
		$a_breadcrumb[] = array(
								'name'=>__('Xác nhận'),
								'meta_title'=>__('Xác nhận'),
								'url'=>'javascript:;',
							);
		$a_breadcrumb[] = array(
								'name'=>__('Thành công'),
								'meta_title'=>__('Thành công'),
								'url'=>'',
							);
		$this->set('a_breadcrumb_c',$a_breadcrumb);

		//SEO
		$this->set('title_for_layout',__('Thành công'));
	}

	public function ajaxAddSighted(){
		$this->layout = false;
		$this->autoRender = false;
		$lang = $this->params['lang'];
		if(!$this->Session->check("Order_$lang")) throw new NotFoundException(__('Trang này không tồn tại',true));
		$a_products = $this->Session->read("Order_$lang");
		foreach($a_products as $key=>$val){
			if($val['id'] == $_POST['id']){
				if($_POST['sighted'] == 'sighted_l'){
					$a_products[$key]['sighted_l'] = $_POST['value'];
				}else{
					$a_products[$key]['sighted_r'] = $_POST['value'];
				}
			}
		}
		$this->Session->write("Order_$lang",$a_products);
	}
	/**
	 * @Description : Gửi email đến quản trị và khách hàng
	 *
	 * @param 	: array $data
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _sendEmail($data){
		$lang = $this->params['lang'];

		//Đọc cấu hình
		$a_configs = $this->_getConfig('email');

		App::uses('CakeEmail', 'Network/Email');
		try {
				$config = array();
				if(strtolower($a_configs['smtp_transport']) == 'smtp'){
					$config = array(
						'host' 		=> 	$a_configs['smtp_host'],
						'port' 		=> 	$a_configs['smtp_port'],
						'timeout' 	=> 	30,
						'username' 	=> 	$a_configs['smtp_username'],
						'password' 	=> 	$a_configs['smtp_password'],
						'sender'	=>	$a_configs['smtp_username']
					);
				};

			$config = array_merge($config,array(
												'transport'=>$a_configs['smtp_transport'],
												'emailFormat'=>'html',
												'template'=>'order',
												'layout'=>'index'
											));
			$a_configs['product'] = explode(',',$a_configs['product']);
			foreach ($a_configs['product'] as $key => $value) {
				$a_configs['product'][$key] = trim($value);
			}

			//Gửi cho quản trị
			if(!empty($data['email'])){
				$Email = new CakeEmail();
				$Email->config(array_merge($config,array(
													'from'=>array($this->from_email => $_SERVER['HTTP_HOST']),
													// 'replyTo'=>$data['email'],
													// 'to'=>$a_configs['product'],
													'bcc'=>$a_configs['product'],
													'subject'=>$a_configs['product_subject'].' - '.__('Đơn hàng số',true).': '.$data['transaction_code'],
													'viewVars'=>array('data'=>$data,'config'=>$a_configs,'admin'=>true)
												)));

				$Email -> send();
			};

			//Gửi cho khách hàng
			if ( ! empty($data['email'])) {
				$Email2 = new CakeEmail();
				$Email2->config(array_merge($config,array(
												'from'=>array($this->from_email => $_SERVER['HTTP_HOST']),
												// 'replyTo'=>$a_configs['product'],
												'to'=>$data['email'],
												'subject'=>$a_configs['product_subject'].' - '.__('Đơn hàng số',true).': '.$data['transaction_code'],
												'viewVars'=>array('data'=>$data,'config'=>$a_configs,'admin'=>false)
											)));

				$Email2 -> send();
			}
		} catch (Exception $e){
			$this->log($e->getMessage());
		}
	}


	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/

	/**
	 * @Description : Danh sách Đơn hàng
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_index() {
		$a_conditions = array();

		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'view':
					foreach ($_POST['chkid'] as $val){
						$this->Order->id = $val;
						$this->Order->set(array('view'=>1));
						$this->Order->save();
					}
					$message = __('Đơn hàng đã thiết lập đã đọc');
					break;
				case 'unview':
					foreach ($_POST['chkid'] as $val){
						$this->Order->id = $val;
						$this->Order->set(array('view'=>0));
						$this->Order->save();
					}
					$message = __('Đơn hàng đã thiết lập chưa đọc');
					break;
				case 'del':
					foreach ($_POST['chkid'] as $val){
						$this->Order->delete($val);
					}
					$message = __('Đơn hàng đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}

		if(!empty($_GET['category_id'])){	//Danh muc
			$this->request->data['Order']['category_id'] = $_GET['category_id'];
			$a_conditions = array_merge($a_conditions,array('order_category_id'=>$_GET['category_id']));
		}
		if(!empty($_GET['keyword']) && $_GET['keyword']!=__('Mã giao dịch, Họ tên, Email, Phone')){	//Tu khoa
			$a_conditions = array_merge($a_conditions,array('or'=>array(
																'Order.name like'=>'%'.$_GET['keyword'].'%',
																'transaction_code like'=>'%'.$_GET['keyword'].'%',
																'email like'=>'%'.$_GET['keyword'].'%',
																'phone like'=>'%'.$_GET['keyword'].'%'
															)));
		}

		if(!empty($_GET['status'])){	//Trạng thái đơn hàng
			$a_conditions = array_merge($a_conditions,array('Order.status like'=>'%'.$_GET['status'].'%'));
		}

		if(!empty($_GET['method'])){	//Thanh toán
			$a_conditions = array_merge($a_conditions,array('Order.method_payment like'=>'%'.$_GET['method'].'%'));
		}

		if(!empty($_GET['start']) && !empty($_GET['end'])){		//Ngày tạo
			$start = mktime(0,0,0,date('m',$_GET['start']),date('d',$_GET['start']),date('Y',$_GET['start']));
			$end = mktime(23,59,59,date('m',$_GET['end']),date('d',$_GET['end']),date('Y',$_GET['end']));
			$a_conditions = array_merge($a_conditions,array('Order.created >='=>$start,'Order.created <='=>$end));
		}

		$this->paginate = array(
			'conditions'=>$a_conditions,
			'fields'=>array(
							'id','transaction_code','name','email','phone','view','method_payment','status','created','content','unit_payment',
							'OrderCategory.id','OrderCategory.name'
						),
			'order'=>array('Order.created'=>'desc','Order.name'=>'asc'),
			'limit'=>$this->limit_admin
		);

		$a_orders = $this->paginate();
		$this->set('a_orders_c', $a_orders);

		$counter = $this->Order->find('count',array('conditions'=>$a_conditions,'recursive'=>-1));
		$this->set('counter_c',$counter);

		//Danh sach danh muc - list
		$this->Order->OrderCategory->bindModel(array(
			'hasMany'=>array(
				'Order' => array(
								'className' => 'Order',
								'foreignKey' => 'order_category_id',
								'dependent' => false,
								'fields' => 'id',
							)
						)
		));
		$a_list_categories = $this->Order->OrderCategory->find('all',array('order'=>'sort asc'));
		$a_list_categories_c = array();		//Danh sach ra noi dung
		$a_list_categories_s = array();		//Danh sach ra sidebar

		$total = 0;
		foreach($a_list_categories as $val){
			$item_order = $val['Order'];
			$item_cate = $val['OrderCategory'];
			$a_list_categories_s[$item_cate['id']] = $item_cate['name'].' ('.count($item_order).')';
			$a_list_categories_c[$item_cate['id']] = $item_cate['name'];
			$total+=count($item_order);
		}
		$this->set('a_list_categories_c',$a_list_categories_c);
		$this->set('a_list_categories_s',$a_list_categories_s);
		$this->set('total_order_s',$total);

		//Set đã thông báo chuông
		$this->Order->updateAll(array('alarm'=>1),array('alarm'=>0));

		//Url hiện tại
		$current_url = urlencode($this->Oneweb->curPageURL());
		$this->set('current_url_c',$current_url);
	}

	/**
	 * @Description : Thiết lập danh mục cho đơn hàng
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxSetCategory(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['order_id']) || empty($_POST['cate_id'])) throw new NotFoundException(__('Invalid'));

		$this->Order->id = $_POST['order_id'];
		$this->Order->set(array('order_category_id'=>$_POST['cate_id']));
		if($this->Order->save()) return true;
		else return false;
	}


	/**
	 * @Description : Xóa Đơn hàng sdung ajax
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxDeleteItem() {
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		if($this->Order->delete($_POST['id'])) return true;
		else return false;
	}



	/**
	 * @Description : Xem Đơn hàng
	 *
	 * @throws NotFoundException
	 * @param int $id
	 * @return void
	 * @Author Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_view($id = null) {
		$this->Order->id = $id;
		if (!$this->Order->exists()) throw new NotFoundException(__('Invalid'));

		//Set da doc
		$this->Order->id = $id;
		$this->Order->set(array('view'=>1));
		$this->Order->save();

		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data['Order'];

			if ($this->Order->save($data)) {
				$this->Session->setFlash('<span>'.__('Thông tin đã được cập nhật').'</span>','default',array('class'=>'success'));

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

		$a_order = $this->Order->read(null, $id);

		$this->set('a_order_c',$a_order);


		//Danh sach danh muc - list
		$this->Order->OrderCategory->bindModel(array(
			'hasMany'=>array(
				'Order' => array(
								'className' => 'Order',
								'foreignKey' => 'order_category_id',
								'dependent' => false,
								'fields' => 'id',
							)
						)
		));
		$a_list_categories = $this->Order->OrderCategory->find('all',array('order'=>'sort asc'));
		$a_list_categories_c = array();		//Danh sach ra noi dung
		$a_list_categories_s = array();		//Danh sach ra sidebar

		$total = 0;
		foreach($a_list_categories as $val){
			$item_order = $val['Order'];
			$item_cate = $val['OrderCategory'];
			$a_list_categories_s[$item_cate['id']] = $item_cate['name'].' ('.count($item_order).')';
			$a_list_categories_c[$item_cate['id']] = $item_cate['name'];
			$total+=count($item_order);
		}
		$this->set('a_list_categories_c',$a_list_categories_c);
		$this->set('a_list_categories_s',$a_list_categories_s);
		$this->set('total_order_s',$total);

		$this->set(compact('a_categories_c'));
	}
	/*
		* @Description :
		* @param - string :
		* @param - interger:
		* @param - array:
		* @return - array:
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function admin_ajaxLoadOrder(){
		$this->layout = false;
		$this->autoRender = false;
		if(!empty($this->params['named']['lang'])){ $lang = $this->params['named']['lang']; }
		else{
			$lang = 'vi';
		}
		if (empty($_GET['name_startsWith'])) exit ;
		$q = strtolower($_GET["name_startsWith"]);
		// remove slashes if they were magically added
		if (get_magic_quotes_gpc()) $q = stripslashes($q);


		$arr_order = $this->Order->find('all', array(
				'conditions'=>array('or'=>array(array('Order.name like'=>'%'.$q.'%'),array('Order.transaction_code like'=>'%'.$q.'%'),array('Order.email like'=>'%'.$q.'%'),array('Order.phone like'=>'%'.$q.'%'))),
				'fields'=>array('name','transaction_code','email', 'phone'),
				'recursive'=>-1
		));

		$result = array();
		foreach ($arr_order as $value) {
			$order_item = $value['Order'];
			$label = $order_item['name'].'-'.$order_item['transaction_code'].'-'.$order_item['email'].'-'.$order_item['phone'];
			if (strpos(strtolower($label), $q) !== false) {
				array_push($result, array("label"=>$label, "value" => strip_tags($order_item['name'])));
			}
			if (count($result) > 11)
				break;
		}
		return json_encode($result);
	}
}
