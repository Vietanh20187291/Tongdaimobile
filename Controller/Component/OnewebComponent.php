<?php
class OnewebComponent extends Component {
	
	/**
	 * @Description : Loại bỏ dấu
	 *
	 * @param 	: string $str
	 * @return 	: string
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function boDau($str) {
		$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
		$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
		$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
		$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
		$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
		$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
		$str = preg_replace("/(đ)/", 'd', $str);
			 
		$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
		$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
		$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
		$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
		$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
		$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
		$str = preg_replace("/(Đ)/", 'D', $str);
		//$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
		return strtolower($str);	//chuyen doi ve in thuong
	}
	
	
	/**
	 * @Description : Định dạng lại slug, nếu slug đã tồn tại trong csld thì thêm số vào đằng sau
	 *
	 * @param 	: string $slug: chuối slug cần định dạng
	 * @param	: array $slug_compare : mảng slug đã tồn tại.
	 * @return 	: string
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function slug($slug,$slug_compare=null){
		$slug = Inflector::slug($this->bodau($slug),'-');
		if(!empty($slug_compare)){
			$i=1;
			$tmp=$slug;
			while (in_array($slug, $slug_compare)) {
				$slug=$tmp.$i;
				$i++;
			}
		}
		return $slug;
	}	
	
	
	/**
	 * @Description : Loại bỏ các thẻ đặc biệt
	 *
	 * @param 	: string $str
	 * @return 	: string
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function htmlEncode($str) {
		$str = preg_replace('#&(?!\#[0-9]+;)#si', '&amp;', $str); // Fix & but allow unicode
		$str = str_replace("<script>","",$str);
		$str = str_replace("</script>","",$str);
		$str = str_replace("<","&lt;",$str);
		$str = str_replace(">","&gt;",$str);
		$str = str_replace("\"","&quot;",$str);
		$str = str_replace("  ", "&nbsp;&nbsp;", $str);
		return $str;
	}
	
	/**
	 * @Description : Lấy URL hiện tại
	 *
	 * @return 	: string
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function curPageURL() {
		$pageURL = 'http';
		if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on"){
			$pageURL .= "s";
		}
		$pageURL .= "://";
		if($_SERVER["SERVER_PORT"] != "80"){
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}else{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	/**
	 * @Description : Kiểm tra email có đúng định dang email không
	 *
	 * @param 	: string $email
	 * @return 	: string
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function checkEmail($email) {
		if (strlen($email) == 0) return false;
		if (eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$', $email)) return true;
		return false;
	} 
	
	/**
	 * @Description : Sinh chuỗi ngâu nhiên
	 *
	 * @param 	: int $length
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	function randString( $length ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
		$str = '';
		$size = strlen( $chars );
		for( $i = 0; $i < $length; $i++ ) {
			$str .= $chars[ rand( 0, $size - 1 ) ];
		}
	
		return strtoupper($str);
	}
       
}
?>