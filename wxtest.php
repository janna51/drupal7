<?php

define('DRUPAL_ROOT', '/var/www/html');
ini_set('display_errors', 1); 
error_reporting(E_ALL);
require 'includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

//load the weather node
$node = node_load(633);

//for safety, verify the title.  if it's not what we expect, fail instantly.
if($node->title !== "Weather at Wellesley") {
  echo "test";
  die();
}

else {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'http://wx.wellesley.edu/wx2/drupalwx.php');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $page = curl_exec($ch);
  curl_close($ch);

  //echo "body: " . $node->body['und'][0]['value'] . "<br>";
  $node->body['und'][0]['value'] = $page;

//  echo $page;
  node_save($node);
}


?>
