<html>
  <body>
    <form action="" method="post">
<?php

require_once('file:///C|/Users/BiffBaileyAdmin.BBIGBLDRSHP270F/AppData/Local/Temp/Temp1_recaptcha-php-1.11.zip/recaptcha-php-1.11/recaptchalib.php');

// Get a key from https://www.google.com/recaptcha/admin/create
$publickey = "";
$privatekey = "";

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;

# was there a reCAPTCHA response?
if ($_POST["recaptcha_response_field"]) {
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        if ($resp->is_valid) {
                echo "You got it!";
        } else {
                # set the error code so that we can display it
                $error = $resp->error;
        }
}
echo recaptcha_get_html($publickey, $error);
?>
    <br/>
    <input type="submit" value="submit" />
    </form>
  </body>
</html>
