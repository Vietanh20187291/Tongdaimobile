<?php 
	lockIpHtaccess();

	require_once('recaptchalib.php');
		
	//Get a key from https://www.google.com/recaptcha/admin/create
	$publickey = "6LdA3N4SAAAAAGJFllhzBxNor1JmFC930awk-EGR";
	$privatekey = "6LdA3N4SAAAAAO1gASRbjwF7Y0inhVdsYtwbO2qx";
	
	# the response from reCAPTCHA
	$resp = null;
	# the error code from reCAPTCHA, if any
	$error = null;
	
	# was there a reCAPTCHA response?
	if (!empty($_POST["recaptcha_response_field"]) && $_POST["recaptcha_response_field"]) {
	        $resp = recaptcha_check_answer ($privatekey,
	                                        $_SERVER["REMOTE_ADDR"],
	                                        $_POST["recaptcha_challenge_field"],
	                                        $_POST["recaptcha_response_field"]);
	
	        if ($resp->is_valid) {
               //Get Ip or Proxy
				if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
					$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
				}else{
					$ip = $_SERVER['REMOTE_ADDR'];
				}
				$list = file('log_ip.log',FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
				
				//Xóa ip đã ghi ở file log_ip.log
				$tmp = '';
				foreach($list as $val){
					$item = explode('-', $val);
					if(trim($item[0])!=$ip) $tmp.=(trim($item[0]).(!empty($item[1])?trim($item[1]):'')."\n");
				}
				$log = fopen('log_ip.log','w+'); 

				fwrite($log, $tmp); 
				fclose($log);
				
				//Xóa ip đã ghi ở file log2_ip.log
				$list2 = file('log2_ip.log',FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
				$tmp = '';
				foreach($list2 as $val){
					if(trim($val)!=$ip) $tmp.=$val;
				}
				$log2 = fopen('log2_ip.log','w+'); 

				fwrite($log2, $tmp); 
				fclose($log2);
				
				//Xóa ip đã ghi ở file lock_ip.log
				$list_lock = file('lock_ip.log',FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
				
				//Xóa ip đã ghi ở file log_ip.log
				$tmp = '';
				foreach($list_lock as $val){
					if($val!=$ip && $val!='all') $tmp.=($val."\n");
				}
				$log_lock = fopen('lock_ip.log','w+'); 
					
				fwrite($log_lock, $tmp); 
				fclose($log_lock);
	        	
				if($_SERVER['HTTP_HOST']=='localhost'){
					header( 'Location: http://localhost/oneweb.vn' ) ;
				}else{
					header( 'Location: http://'.$_SERVER['HTTP_HOST'] ) ;
				}
				exit;
	        } else {
	                # set the error code so that we can display it
	                $error = $resp->error;
	        }
	}
?>
<html>
  <body>
    <form action="" method="post">
	<?php
		echo recaptcha_get_html($publickey, $error);
	?>
    <br/>
    <input type="submit" value="submit" />
    </form>
  </body>
</html>


<?php 
	/*
	* @Description	: Khóa ip sdung .htaccess
	* 
	* @param	: array 
	* @param	: string
	* @param	: int
	* @return	: array
	* @Author	: Hoang Tuan Anh - tuananh@url.vn
	*/
	function lockIpHtaccess(){
		$n = 20;		//Số lượng truy cập từ ip này nếu quá $n sẽ bị khóa bằng htaccess
		$lock = fopen('.htaccess','a'); 
		
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$in = $ip;
		$log = fopen('log2_ip.log','a'); 
		
		fwrite($log, $in."\n"); 
		fclose($log);
		
		$list = file('log2_ip.log',FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$count = 0;
		foreach($list as $val) if($val==$in) $count++;
		if($count>$n){
			$in = "Deny from ".$_SERVER['REMOTE_ADDR']."\n";
			$lock = fopen('.htaccess','a'); 
			fwrite($lock, $in); 
			fclose($lock);
		}
	}
?>