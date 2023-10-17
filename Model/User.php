<?php
// app/Model/User.php
App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {
	public $name = 'User';
	public $validate = array(
		'name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
//				'message' => 'A username is required'
			)
		),
		'email' => array(
				'required' => array(
						'rule' => array('notEmpty'),
						//				'message' => 'A username is required'
				),
				'email'=>array(
						'rule'=> array('email'),
// 						'message' => 'Email không đúng định dạng',
				)
		),
		'username' => array(
			'required' => array(
				'rule' => array('notEmpty'),
//				'message' => 'A username is required'
			)
		),
		'password' => array(
			'required' => array(
				'rule' => array('notEmpty'),
//				'message' => 'A password is required'
			)
		)
	);
}