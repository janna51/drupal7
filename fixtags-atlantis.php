<?php
/**
 * @file
 * The PHP page that serves all page requests on a Drupal installation.
 *
 * The routines here dispatch control to the appropriate handler, which then
 * prints the appropriate page.
 *
 * All Drupal code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 */
/**
 * Root directory of Drupal installation.
 */
define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
error_reporting(E_ALL);
ini_set('display_errors', '1');

if(update_access_allowed()){	
	if (!$mysql_link_drupal = new mysqli ('endeavour.wellesley.edu', 'gwang', 'l0lersKat35', 'drupal'))
      		die ("We're sorry, database connection failed. ");
	$mysql_link_drupal->set_charset("utf8");
	$sql = "SELECT nid as node_id FROM node WHERE type = 'news'";
	$stmt = $mysql_link_drupal->query($sql) or die ("Error preparing statement to get node info");
	$k = 0;
	while ($row = $stmt->fetch_array(MYSQLI_BOTH)) {
	//if($k<=1){
		//check the record exist
		$node_id = $row["node_id"];
		echo "node id: ".$node_id."<br>";
		$node=node_load($node_id);
		//if($node_id==1032)
		//{
		$node_type = $node->type;
		echo "node type: ".$node->type."<br>";
		$node_type = $node->title;
		echo "node title: ".$node->title."<br>";
		$newsdate = $node->field_newsdatenew[$node->language][0]['value'];
		$node->field_newsdate[$node->language][0]['value'] = $newsdate;
		//$node->field_newsdatebluenog[$node->language][0]['value'] = $formateddate1;
		//save node
		//$node = node_submit($node);
		$node = node_save($node); // Prepare node for a submit
		//$k++;
		//}
	}
}else{
  echo update_access_denied_page();
}

function update_access_denied_page() {
  drupal_add_http_header('Status', '403 Forbidden');
  watchdog('access denied', 'mm.php', NULL, WATCHDOG_WARNING);
  drupal_set_title('Access denied');
  return '<p>Access denied. You are not authorized to access this page. Log in using either an account with the <em>administer</em> permission or the site maintenance account (the account you created during installation).'; 
}

/**
 * Determines if the current user is allowed to run update.php.
 *
 * @return
 *   TRUE if the current user should be granted access, or FALSE otherwise.
 */
function update_access_allowed() {
  global $update_free_access, $user;

  // Allow the global variable in settings.php to override the access check.
  if (!empty($update_free_access)) {
    return TRUE;
  }
  // Calls to user_access() might fail during the Drupal 6 to 7 update process,
  // so we fall back on requiring that the user be logged in as user #1.
  try {
    require_once DRUPAL_ROOT . '/' . drupal_get_path('module', 'user') . '/user.module';
    return user_access('administer software updates');
  }
  catch (Exception $e) {
    return ($user->uid == 1);
  }
}

?>





























