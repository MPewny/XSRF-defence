<?php

class xsrf {

    private $error = "No errors yet! Let it stay thay way ;)";

    public $XSRFErrorUrl = ''; // You can change it to your prefered default error display URL

    public static function createVerificationToken( int $size = 8 ){

      $token = "xsrf-";

      $data = random_bytes( $size );

      $data = bin2hex( $data );

      $token .= $data;

      $_SESSION['token'] = $token;

      return $token;
    }

    public static function createVerificationSum(){

        $salt = random_bytes( 8 );

        $salt = bin2hex( $salt );

        $domain = $_SERVER['SERVER_NAME'];

        $UA = $_SERVER['HTTP_USER_AGENT'];

        $sum = $domain . $UA . $salt;

        $_SESSION['xsrfSalt'] = $salt;

        $sum = md5($sum);

        return $sum;

    }

    public function verifyByDomain( $domain = false ){

       $validDomain = ( $domain ) ? $domain : $_SERVER['SERVER_NAME'];
       $requestDomain = parse_url( $_SERVER['HTTP_REFERER'] , PHP_URL_HOST );

          if( $validDomain !== $requestDomain ){

           $this->error = "Request domain is invalid";

           return false;
          }


        return true;

      }

    public function verifyBySource( $expectedUrl = false  ){

      if ( ! $expectedUrl ){
        $this->error = "Expected URL has not been given.";

        return false;
      }

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

    public function verifyBySum(){

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

      public function verifyByToken( $toVerify = 0 ){

         if(!$toVerify){

            $this->error = "No token created";

            unset( $toVerify, $_SESSION['token'] );

            return false;

          }elseif( ! isset ( $_SESSION['token'] ) ){

            $this->error = "Token for this request doesn't exist on the server";

            return false;

          }elseif( $toVerify !== $_SESSION['token'] ){

            $this->error = "Token invalid";

            unset( $toVerify, $_SESSION['token'] );

            return false;

          }

         unset( $toVerify, $_SESSION['token'] );

         return true;
        }

        public function displayError(){

         $_SESSION['xsrfError'] = $this->error;

         include ( $this->XSRFErrorUrl );

         die;

        }

        public function errorMessage(){

          $result = ($this->error) ? $this->error : "There is no error massage.";

         return $result;

        }

}
?>
