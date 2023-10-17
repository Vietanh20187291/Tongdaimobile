<?php
App::uses('AppController', 'Controller');

class AmpAppController extends AppController {
	public $helpers = array('Amp.HtmlAmp');

	function beforeFilter(){
		parent::beforeFilter();
    $controller = $this->params['controller'];
    $action = $this->params['action'];
    $this->layout = 'default';
		// $this->loadModel('Currency');
		// $a_currencies = $this->Currency->find('all',array(
		// 		'conditions'=>array('status'=>1),
		// 		'order'=>'name asc'
		// ));
		$a_site_info = $this->_getConfig('site');
    $this->set(compact('controller','action', 'a_site_info'));
	}

	// Lấy slug của tag
	function _getSlugForTag($tag){
		$a_tags = $this->_getTag($tag,'arr');

		$tmp = array();
		foreach($a_tags as $val){
			$tag = $this->_getIdOfTag($val);
			if ( ! empty($tag['Tag'])) {
				$tag = $tag['Tag'];
				$tmp[] = array(
					'name'				=> $this->capitalFirstLetterVietnamese($val),
					'id'					=> $tag['id'],
					'slug'				=> $tag['slug'],
					'meta_title'	=> $val
				);
			}
		}
		return $tmp;
	}
}