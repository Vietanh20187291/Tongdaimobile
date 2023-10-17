<?php
App::uses('AppModel', 'Model');
/**
 * Config Model
 *
 */
class Config extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'email';

/**
 * Validation rules
 *
 * @var array
 */
	
	/**
	 * @Description : Lưu cấu hình vào bảng dữ liệu
	 *
	 * @throws 	: NotFoundException
	 * @param 	: varchar: $name, $value
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function update($name,$value){
		$sql = "UPDATE configs SET value='$value' WHERE name='$name'";
		$result = $this->query($sql);
		return $result;
	}
}
