<?php 
 
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  header('Method Not Allowed', true, 405);
  echo "GET method requests are not accepted for this resource";
  exit;
}


  $oldfolder = $_POST['oldfolder'];
  $newfolder = $_POST['newfolder'];

  if (!file_exists($newfolder)) {
    rename($oldfolder, $newfolder);
    echo "success";
    exit;
} else {
    echo "failed";
}

?>