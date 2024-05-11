<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>What's my IP</title>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="https://www.google.com/recaptcha/api.js?render=<?= $_ENV['RECAPTCHA_SITE_KEY'] ?>"></script>
<style>
 .error{
  color:red;
 }
</style>
<?php
  $secret = $_ENV['RECAPTCHA_SECRET'];
  $data_site_key = $_ENV['RECAPTCHA_SITE_KEY'];
  function check(){
     if (!isset($_POST['g-token']) || !$_POST['g-token']) return false;

     $secret = $_ENV['RECAPTCHA_SECRET'];
     $recaptchaResponse = trim($_POST['g-token']);
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
     $google_response = json_decode($response);
     if ($google_response->success)
         return $userIp;
     else
         return false;
  }

  $remoteAddr = $_SERVER["REMOTE_ADDR"];
  $ip = check();
?>
<script>
   function onSubmit(token) {
     document.getElementById("showmyip").submit();
   }
 </script>
<script>
  grecaptcha.ready(function() {
    grecaptcha.execute('<?= $data_site_key ?>', {action: ''}).then(function(token) {
      document.getElementById('g-token').value = token;
    });
  });
</script>
</head>
	<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-8">
                        <h3>Mostra il mio IP</h3>
                        <form method="post" id="shomyip" action="">
			                    <input type="hidden" name="g-token" id="g-token"> 
                          <button class="btn btn-success" type="submit" >Go!</button>
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
                        <h3>Il tuo IP Esterno è: </h3>
                        <h1><?= $ip ?></h1>

                        <h3>IP Richiesta: </h3>
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
