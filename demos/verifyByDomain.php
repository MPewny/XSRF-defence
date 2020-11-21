<?php
session_start();

require_once '..\XSRF.php';

$xsrf = new xsrf();

if ( isset( $_POST['text'] ) ){

  $expectedUrl = $_SERVER['SERVER_NAME'];


  if ( $xsrf->verifyBySource( $expectedDomain ) ){
    echo 'Success! You sent: '. $_POST['text'] .' <br>';
  }else{

    echo $xsrf->ErrorMessage();

  }

}

?>

<html>
  <body>
    <form method="post">
      <input type="text" name="text">
      <input type="submit" value="Submit">
    </form>
  </body>
</html>
