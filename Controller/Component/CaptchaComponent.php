<?php  
App::import('Vendor','PhpCaptcha' ,array('file'=>'phpcaptcha/phpcaptcha.php')); 
         
class CaptchaComponent extends Component {
    var $controller; 
  
//    function startup( &$controller ) { 
//        $this->controller = &$controller; 
//    } 

    function image(){ 
         
        $imagesPath = APP . 'Vendor' . DS . 'phpcaptcha'.'/fonts/'; 
         
        $aFonts = array( 
            $imagesPath.'VeraBd.ttf', 
            $imagesPath.'VeraIt.ttf', 
            $imagesPath.'Vera.ttf' 
        ); 
         
        $oVisualCaptcha = new PhpCaptcha($aFonts, 183, 35); 
         
        $oVisualCaptcha->UseColour(true); 
//        $oVisualCaptcha->CaseInsensitive(false);
        //$oVisualCaptcha->SetOwnerText('Source: '.FULL_BASE_URL); 
        //$oVisualCaptcha->SetNumChars(6); 
        $oVisualCaptcha->Create(); 
    } 
     
    function audio(){ 
        $oAudioCaptcha = new AudioPhpCaptcha('/usr/bin/flite', '/tmp/'); 
        $oAudioCaptcha->Create(); 
    } 
     
    function check($userCode, $caseInsensitive = true){ 
        if ($caseInsensitive) { 
            $userCode = strtoupper($userCode); 
        } 
       
        if (!empty($_SESSION[CAPTCHA_SESSION_ID]) && $userCode == $_SESSION[CAPTCHA_SESSION_ID]) { 
            // clear to prevent re-use 
            unset($_SESSION[CAPTCHA_SESSION_ID]); 
             
            return true; 
        } 
        else return false; 
         
    } 
} 
?>