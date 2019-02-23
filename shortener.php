<?php 

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  header('Method Not Allowed', true, 405);
  echo "GET method requests are not accepted for this resource";
  exit;
}
function generateRandomString($length = 4) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function curl_get_contents($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

$secretKey = "SECRET_KEY_HERE";
$responseKey = $_POST['g-recaptcha-response'];
$url = "https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$responseKey;

$response = curl_get_contents($url);
$response = json_decode($response);

if ($response->success){
  $iban = $_POST['iban'];
  $folder = generateRandomString();

  while (file_exists(getcwd().'/'.$folder)) {
      $folder = generateRandomString();
  }
  mkdir($folder, 0755, true);

  $newFileName = './'.$folder.'/index.html';
  $newFileContent = '<!DOCTYPE html>

  <html>
  <head>
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-134253870-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag(\'js\', new Date());

    gtag(\'config\', \'UA-134253870-1\');
  </script>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IBAN RUN</title>
  <meta name="description" content="IBAN Shortener">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <!--<link href="/resources/css/bootstrap.min.css" rel="stylesheet">-->
  <link href="/resources/css/font-awesome.min.css" rel="stylesheet">
  <link href="/resources/css/main.css" rel="stylesheet">

  <link rel="apple-touch-icon" sizes="180x180" href="/resources/img/icons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/resources/img/icons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/resources/img/icons/favicon-16x16.png">
  <link rel="manifest" href="/resources/img/icons/site.webmanifest">
  <link rel="mask-icon" href="/resources/img/icons/safari-pinned-tab.svg" color="#5bbad5">
  <link rel="shortcut icon" href="/resources/img/icons/favicon.ico">
  <meta name="msapplication-TileColor" content="#ffc40d">
  <meta name="msapplication-config" content="/resources/img/icons/browserconfig.xml">
  <meta name="theme-color" content="#ffffff">
  </head>
  <body>

  <!--main-->
  <section class="ibanScreen">
    <div class="container">
        <div class="col-md-12 col-sm-12"> 
          <!--logo-->
          <div class="logo"><a href="https://iban.run"><img class="logoShort" src="/resources/img/ibanruntransparent.png" alt="ibanrunlogo"></a></div>
          <!--logo end--> 
        </div>
        <div class="col-md-12" style="margin-top: 50px;"> 
          <!--sub-form-->
          <div class="sub-form text-center">
            <div class="row">
              <strong id="iban" style="color: black; font-size: 30px; overflow-wrap: break-word;">'.$iban.'</strong><br><br>
              <button id="copy" class="btn-copy-clipboard" data-clipboard-target="#iban">Copy to Clipboard</button>
            </div>
          </div>
          <!--sub-form end--> 
          
        </div>
    </div>
  </section>
  <!--main end--> 

  <iframe name="hiddenFrame" class="hide"></iframe>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
  <script src="/resources/js/main.js"></script> 
  <script type="text/javascript">
    var userLang = navigator.language || navigator.userLanguage; 
    if(userLang === "tr-TR"){
      $("#copy").text("Kopyala");
    }
    var btn = document.getElementById(\'copy\');
      var clipboard = new ClipboardJS(\'.btn-copy-clipboard\');
      clipboard.on(\'success\', function(e) {
          console.log(e);
          gtag(\'event\', \'copy_iban\', {\'event_category\': \'ButtonClick\'});
      });
      clipboard.on(\'error\', function(e) {
          console.log(e);
      });

    $(document).ready(function(){
     if(userLang === "tr-TR"){
      $(\'.btn-copy-clipboard\').tooltip({title: "Kopyalandı!", trigger: "click", placement: "bottom"}); 
      }else{
      $(\'.btn-copy-clipboard\').tooltip({title: "Copied!", trigger: "click", placement: "bottom"}); 
      }
    });
  </script>
  </body>
  </html>';

  if (file_put_contents($newFileName, $newFileContent) !== false) {
      echo $folder;
  } else {
      echo "folderfailed";
  }
}else{
  echo "captchafailed";
}







?>