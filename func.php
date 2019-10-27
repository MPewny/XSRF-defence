<?php
session_start();
$xsrf-error = array();
function xsrf_request_domain($domain){
   $selfDomain = $_SERVER['SERVER_NAME'];
   $request = parse_url($_SERVER['HTTP_REFERER']);
   $reqDomain = $referer['host'];
if($selfDomain =! $reqDomain){
 array_push($xsrf-error,"Request domain invalid");
 die();
}
  }
function xsrf_request_src($url){
   $expectedURL = $url;
   $reqURL = $_SERVER['HTTP_REFERER'];
 array_push($xsrf-error, "Request referer URL invalid. Expected request source:".$url);
 die();
  }
function xsrf-token($opt, $type){

  }
function xsrf_createsum($addsalt){
   $domain = $_SERVER['SERVER_NAME'];
   $UA = $_SERVER['HTTP_USER_AGENT'];
   $form = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
   $token = $domain.$UA.$form;
   if(isset($addsalt)){
   $token = $token.$addstalt;
   }
   $token = md5($token);
   echo "<input type='hidden' name='xsrf-token' value='".$token."'>";
}
function xsrf_verifysum($addsalt){
   $domain = $_SERVER['SERVER_NAME'];
   $UA = $_SERVER['HTTP_USER_AGENT'];
   $form = $_SERVER['HTTP_REFERER'];
   $token = $domain.$UA.$form;
   if(isset($addsalt)){
   $token = $token.$addstalt;
   }
   $token = md5($token);
   
   if(!isset($_POST['token']) || !isset($_GET['token'])){
    array_push($xsrf-error,"No token created");
    die();
   }elseif($_POST['token'] =! $token && $_GET['token'] =! $token){
    array_push($xsrf-error,"Token invalid");
    die();
   }
  }
function xsrf_error(){
 $_SESSION['xsrferr'] = $xsrf-error;
 header("Location:xsrf-err.php");
 die();
}
?>
