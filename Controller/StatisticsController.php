<?php
App::uses('AppController', 'Controller');

class StatisticsController extends AppController {

	private $date_list = array('00:00:00','03:00:00','06:00:00','09:00:00','12:00:00','15:00:00','18:00:00','21:00:00','23:59:59');
	private $date_title = array('0h-3h','3h-6h','6h-9h','9h-12h','12h-15h','15h-18h','18h-21h','21h-24h');
	/**
	 * @Description :
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_index(){
	}


	public function admin_status_posts(){

	}
	public function admin_ajaxViewStatusPosts(){

		$this->layout = 'ajax';
		if (!$this->request->is('ajax')) throw new NotFoundException(__('Trang này không tồn tại', true));
		$start = $_POST['start'];
		$end = $_POST['end'];
		$st = explode('/', $start);
		$start = $st['1'] . '/' . $st['0'] . '/' . $st['2'];
		$en = explode('/', $end);
		$end = $en['1'] . '/' . $en['0'] . '/' . $en['2'];
		$date = $date_posted = $this->getDateRange($start, $end);
		$active_project = $non_active = array();
		$post_project = $total_post = array();
		$this->loadModel('ActivePost');
		$this->loadModel('Post');
//		$sum_not_status = $this->Post->find('count',array(
//			'conditions'=>array('Post.status'=>0,'Post.user_id = 49'),
//			'fields'=>array('Post.id')
//		));
		$count_post = "SELECT COUNT(id) AS count FROM posts Post WHERE  Post.trash = 0";
		$count_result = $this->Post->query($count_post);
		$count_query = $count_result[0]['0']['count'];
		$count_post_active = "SELECT COUNT(id) AS count FROM posts Post WHERE  Post.trash = 0 AND Post.status = 1";
		$count_result = $this->Post->query($count_post_active);
		$count_query_active = $count_result[0]['0']['count'];
		$con_lai= $count_query-$count_query_active;
//		var_dump($con_lai);exit();
		$this->ActivePost->create();
		$this->ActivePost->set('so_luong',$con_lai);
		$this->ActivePost->set('active_post',mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y')));
		$this->ActivePost->set('check',false);
		$this->ActivePost->save();

		$title_posted = array_values($date_posted);
		$this->set('title_posted', json_encode($title_posted));
		foreach ($date_posted as $key => $value) {

			$total_project_date_active=0;
			$val = explode('/', $value);

			$p_con_lai = $this->ActivePost->find('all', array(
				'fields'=>'so_luong',
				'conditions' => array('active_post BETWEEN ' . mktime(0, 0, 0, $val['1'], $val['0'], $val['2']) . ' AND ' . mktime(23, 59, 59, $val['1'], $val['0'], $val['2']),'check'=>0),
				'order' => array('id' => 'desc'),
			));
			if(empty($p_con_lai)){
				$total_non_active = 0;
			}else{
				$total_non_active= (int)$p_con_lai[0]['ActivePost']['so_luong'];
			}

			array_push($non_active, $total_non_active);

			$p_project_active = $this->ActivePost->find('all', array(
				'fields'=>'so_luong',
				'conditions' => array('active_post BETWEEN ' . mktime(0, 0, 0, $val['1'], $val['0'], $val['2']) . ' AND ' . mktime(23, 59, 59, $val['1'], $val['0'], $val['2']),'check'=>1),
			));

			foreach ($p_project_active as $sl){
				$total_project_date_active +=  $sl['ActivePost']['so_luong']; ;
			}


			array_push($active_project, $total_project_date_active);
		}

//		var_dump(json_encode($non_active));exit();

		$this->set('active_project', json_encode($active_project));
		$this->set('non_active', json_encode($non_active));

		$this->set('count_query', $count_query);
		$this->set('count_query_active', $count_query_active);

		//tổng số tin đăng trong 1 khoảng thời gian
		$this->set('sum_total_post', array_sum($active_project));
		$this->set('sum_non_active_post', $con_lai);
		//trung bình số tin đăng trong 1 khoảng thời gian
//            $this->set('average_total_post', array_sum($post_project) / count($date));
//            $this->set('average_total_post_active', array_sum($active_project) / count($date));
//		$this->set(compact('list_posts'));
	}

}
?>
