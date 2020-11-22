<?php
session_start();

require_once '..\XSRF.php';

$xsrf = new xsrf();

if ( isset( $_POST['text'] ) ){

  $expectedUrl = $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];


  if ( $xsrf->verifyBySource( $expectedUrl ) ){
    echo 'Success! You sent: '. $_POST['text'] .' <br>';
  }else{

    echo $xsrf->errorMessage();

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
