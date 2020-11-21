<?php
session_start();

require_once '..\XSRF.php';

$xsrf = new xsrf();

if ( isset( $_POST['text'] ) ){


  if ( $xsrf->verifyBySum() ){
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
      <input type="hidden" name="token" value="<?php echo $xsrf->createVerificationSum(); ?>">
      <input type="submit" value="Submit">
    </form>
  </body>
</html>
