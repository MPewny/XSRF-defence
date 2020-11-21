<?php
session_start();

require_once '..\XSRF.php';

$xsrf = new xsrf();

$xsrf->XSRFErrorUrl = 'error.php';

if ( isset( $_POST['text'] ) ){


  if ( $xsrf->verifyByToken() ){
    echo 'Um... congrats! I think... or maybe not... <b>you are not supposed to see this message on the screen</b>';
  }else{

    echo $xsrf->displayError();

  }

}

?>

<html>
  <body>
    Read the source code of this file! <br>
    <form method="post">
      <input type="text" name="text">
      <input type="hidden" name="token" value="<?php echo $xsrf->createVerificationTokn(); /* Oopsie! misspelling occured D: ;) */ ?>">
      <input type="submit" value="Submit">
    </form>
  </body>
</html>
