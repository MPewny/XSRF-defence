<?php
session_start();

require_once '..\XSRF.php';

$xsrf = new xsrf();

if ( isset( $_POST['text'] ) ){


  if ( $xsrf->verifyByToken() ){
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
      <input type="hidden" name="token" value="<?php echo $xsrf->createVerificationToken( 4 ); ?>">
      <input type="submit" value="Submit">
    </form>
  </body>
</html>
