<?php

class xsrf {

    public string $error;

    public string $XSRFErrorUrl = 'xsrf-err.php'; // Change to your prefered error display URL

    function CreateVerificationSum(){

       $_SESSION['xsrfSalt'] = rand( 1 , 9999 );

       $domain = $_SERVER['SERVER_NAME'];
       $UA = $_SERVER['HTTP_USER_AGENT'];
       $form = $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
       $sum = $domain . $UA . $form . $_SESSION['xsrfSalt'];
       $sum = md5($token);

       return $sum;

    }

    function VerifyByDomain( $domain ){

       $validDomain = ( $domain ) ? $domain : $_SERVER['SERVER_NAME'];
       $requestDomain = parse_url( $_SERVER['HTTP_REFERER'] , PHP_URL_HOST);

          if( $validDomain =! $requestDomain ){

           $this->error = "Request domain invalid" ;

           return false;
          }

        return true;

      }

    function VerifyBySource( $expectedUrl ){

       $reqURL = $_SERVER['HTTP_REFERER'];

       $this->error = "Request referer URL invalid. Expected request source:". $expectedUrl ;

       return false;

      }

    public function VerifyBySum(){

       $salt = $_SESSION['xsrfSalt'];
       $domain = $_SERVER['SERVER_NAME'];
       $UA = $_SERVER['HTTP_USER_AGENT'];
       $form = $_SERVER['HTTP_REFERER'];
       $token = $domain.$UA.$form. $salt;

       $token = md5($token);

       if(!isset($_POST['token']) || !isset($_GET['token'])){

        $this->error = "No token created";

        return false;

       }elseif( $_POST['token'] =! $token && $_GET['token'] =! $token ){

        $this->error = "Token invalid";

        return false;

       }

       unset($_SESSION['xsrfSalt']);

       return true;
      }

    public function DisplayError(){

     $_SESSION['xsrfError'] = $this->error;

     header("Location:" . $XSRFErrorUrl);

    }

}
?>
