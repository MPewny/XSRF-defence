<?php

class xsrf {

    private $error = "No errors yet! Let it stay thay way ;)";

    public $XSRFErrorUrl = 'xsrf-err.php'; // Change to your prefered error display URL

    public static function createVerificationToken( int $size = 8 ){

      $token = "xsrf-";

      $data = random_bytes( $size );

      $data = bin2hex( $data );

      $token .= $data;

      $_SESSION['token'] = $token;

      return $token;
    }

    public static function createVerificationSum(){

       $_SESSION['xsrfSalt'] = random_bytes( 8 );

       $domain = $_SERVER['SERVER_NAME'];
       $UA = $_SERVER['HTTP_USER_AGENT'];
       $sum = $domain . $UA . $_SESSION['xsrfSalt'];

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

    public function verifyBySource( $expectedUrl  ){

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

      public function verifyByToken(){

         if(!isset($_POST['token'])){

            $this->error = "No token created";

            unset( $_POST['token'], $_SESSION['token'] );

            return false;

          }elseif( ! isset ( $_SESSION['token'] ) ){

            $this->error = "Token for this request doesn't exist on the server";

            return false;

          }elseif( $_POST['token'] !== $_SESSION['token'] ){

            $this->error = "Token invalid";

            unset( $_POST['token'], $_SESSION['token'] );

            return false;

          }

         unset( $_POST['token'], $_SESSION['token'] );

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
