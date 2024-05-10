<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>What's my IP</title>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src='https://www.google.com/recaptcha/api.js'></script>
<style>
 .error{
  color:red;
 }
</style>
<?php
  function check(){
    if (!$_POST['g-recaptcha-response']) return false;

    $secret = getenv('RECAPTCHA_SECRET');
    $data_site_key = getenv('RECAPTCHA_SITE_KEY');
  	$recaptchaResponse = trim($_POST['g-recaptcha-response']);
  	$userIp='';

  	if ($_SERVER["HTTP_X_FORWARDED_FOR"] != "") {
  		$userIp = $_SERVER["HTTP_X_FORWARDED_FOR"];
  	} else {
  		$userIp = $_SERVER["REMOTE_ADDR"];
  	}

  	$url="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$recaptchaResponse."&remoteip=".$userIp;

  	$ch = curl_init();
  	curl_setopt($ch, CURLOPT_URL, $url);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  	$response = curl_exec($ch);
  	curl_close($ch);

    if (json_decode($response, true))
      return $userIp;
    else
      return false;
  }

  $remoteAddr = $_SERVER["REMOTE_ADDR"];
  $ip = check();
?>
</head>
	<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-8">
                        <h3>Mostra il mio IP</h3>
                        <div class="error"><strong><?= $flashSuccess ?></strong></div>
                        <form method="post" action="">
                          <div class="g-recaptcha" data-sitekey="<?= $data_site_key ?>"></div></br>
                          <button class="btn btn-success" type="submit">Go!</button>
                        </form>
                      </div>
                </div>
            </div>
            <?php
              if ($ip != false):
            ?>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-8">
                        <h3>Il tuo IP è: </h3>
                        <h1><?= $ip ?></h1>

                        <h3>Richiesta IP: </h3>
                        <h1><?= $remoteAddr ?></h1>
                      </div>
                </div>
            </div>
            <?php
              endif;
            ?>
        </div>
    </div>
	</body>
</html>
