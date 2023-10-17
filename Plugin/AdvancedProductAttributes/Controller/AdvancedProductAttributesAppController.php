<?php
App::uses('AppController', 'Controller');

class AdvancedProductAttributesAppController extends AppController {
	// public $helpers = array('Amp.HtmlAmp');

	function beforeFilter(){
		parent::beforeFilter();
    $controller = $this->params['controller'];
    $action = $this->params['action'];
	}
}