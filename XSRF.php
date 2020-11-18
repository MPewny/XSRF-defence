<?php

class xsrf {

    private $error = "No errors yet! Let it stay thay way ;)";

    public $XSRFErrorUrl = 'xsrf-err.php'; // Change to your prefered error display URL

    public static function CreateVerificationToken( $length = 8 ){

      $token = "xsrf-";

      for ($i = 0; $i < $length; $i++ ){ // Yes. I could've called it for examlpe "incrementation" or smth... but it would be profanation I guess..

        $character = rand( 0, 9 );

        $token .= $character;

      }

      $_SESSION['token'] = $token;

      return $token;
    }

    public static function CreateVerificationSum(){

       $_SESSION['xsrfSalt'] = rand( 10 , 9999 );

       $domain = $_SERVER['SERVER_NAME'];
       $UA = $_SERVER['HTTP_USER_AGENT'];
       $sum = $domain . $UA . $_SESSION['xsrfSalt'];

       $sum = md5($sum);

       return $sum;

    }

    public function VerifyByDomain( $domain = false ){

       $validDomain = ( $domain ) ? $domain : $_SERVER['SERVER_NAME'];
       $requestDomain = parse_url( $_SERVER['HTTP_REFERER'] , PHP_URL_HOST );

          if( $validDomain !== $requestDomain ){

           $this->error = "Request domain is invalid";

           return false;
          }


        return true;

      }

    public function VerifyBySource( $expectedUrl  ){

      $requestURL = $_SERVER['HTTP_REFERER'];

      $expectedUrl =
      ( substr( $expectedUrl, 0, 7 ) === "http://" || substr( $expectedUrl, 0, 8 ) === "https://" ) ?
      $expectedUrl :
      parse_url( $requestURL, PHP_URL_SCHEME ) . "://" . $expectedUrl;


      if ( $requestURL !== $expectedUrl ){
         $this->error = "Request referer URL is invalid.";

         return false;
       }

       return true;
      }

    public function VerifyBySum(){

       $salt = $_SESSION['xsrfSalt'];
       $domain = $_SERVER['SERVER_NAME'];
       $UA = $_SERVER['HTTP_USER_AGENT'];
       $sum = $domain . $UA . $salt;

       $sum = md5($sum);

       if(!isset($_POST['token'])){

        $this->error = "No token created";

        return false;

      }elseif( $_POST['token'] !== $sum ){

        $this->error = "Token invalid";

        return false;

       }

       unset($_SESSION['xsrfSalt']);

       return true;
      }

      public function VerifyByToken(){

         if(!isset($_POST['token'])){

            $this->error = "No token created";

            unset( $_POST['token'], $_SESSION['token'] );

            return false;

          }elseif( $_POST['token'] !== $_SESSION['token'] ){

            $this->error = "Token invalid";

            unset( $_POST['token'], $_SESSION['token'] );

            return false;

          }

         unset( $_POST['token'], $_SESSION['token'] );

         return true;
        }

        public function DisplayError(){

         $_SESSION['xsrfError'] = $this->error;

         include ( $this->XSRFErrorUrl );

         die;

        }

        public function ErrorMessage(){

          $result = ($this->error) ? $this->error : "There is no error massage.";

         return $result;

        }

}
?>
