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

include('mm_create_path.inc');

if(update_access_allowed()){
print '<div style="margin-left: 50px;"><br><a href="cmsmigration.php"><font size="4">Back to main page</font></a></div>';
  $groupId =$_POST['groupid'];
  //echo "Group ID:";
  //echo $groupId;
  //Check the group ID exist
  $rows = db_query('SELECT * FROM {mm_tree} WHERE mmtid=:mmtid and node_info is NULL', array(':mmtid' => $groupId));
  $n = 0;
  
  foreach ($rows as $row) {
	$n=$n+1;
  }
  if($n>0)
  {
	$result = db_query('SELECT nid, title FROM {node}');
	//Get bluenog db connection
	$db = Database::getConnection('default', 'bluenog');
	$k=0;
	foreach ($result as $row) {
	//if($n<=0){
 	   //$node = node_load($row->nid);
	   //echo $node->nid;
	   //$title = '"'.$row->title.'"';
	   $title = trim($row->title);
	   
	   $mmtid = 0;
	   $result1= $db->query('SELECT p.mmtid FROM {content} n,{content_to_page} m, {page} p WHERE n.file_name =:file_name and n.content_id = m.bluenog_content_id and m.bluenog_page_id = p.bluenog_page_id', array(':file_name' => $title)) or die ("Error preparing statement to get content string");
	   foreach ($result1 as $row1) {
		$mmtid = $row1->mmtid;
		echo "Title: ".$title;
	    echo "<br>";
		echo  "MMTID:".$mmtid;
	    echo "<br>";
	   }
	   
	   if($mmtid>0){
			$result = db_query('SELECT * FROM {mm_node2tree} n WHERE n.nid = :nid and n.mmtid=:mmtid', array(':nid' => $row->nid, ':mmtid' => $mmtid));
   			$m = 0;
   			foreach ($result as $row) {
			  $m=$m+1;
   			}
			if($m==0){
				db_query('INSERT INTO {mm_node2tree} (nid, mmtid) VALUES(:nid, :mmtid)', array(':nid' => $row->nid, ':mmtid' => $mmtid));
				//db_query('INSERT INTO {mm_node_write} (nid, gid) VALUES(:nid, :gid)', array(':nid' => $row->nid, ':gid' => $groupId));
			}
		}
		$p =0;
		$result2= $db->query('SELECT * FROM {content} n WHERE n.file_name =:file_name', array(':file_name' => $title)) or die ("Error preparing statement to get content string");
		foreach ($result2 as $row2) {
		 $p++;
		}
		if($p>0){
			//check whether group assigned
			$rows3 = db_query('SELECT * FROM {mm_node_write} WHERE nid=:nid and gid=:gid', array(':nid' => $row->nid,':gid' => $groupId));
			$n3 = 0;
  
			foreach ($rows3 as $row3) {
				$n3=$n3+1;
			}
			if($n3 == 0){
				echo "Authorize this doc: ".$title."<br>";
				db_query('INSERT INTO {mm_node_write} (nid, gid) VALUES(:nid, :gid)', array(':nid' => $row->nid, ':gid' => $groupId));
			}
		}
		
     	//db_query('INSERT INTO {mm_tree_access}(mmtid,gid,mode) VALUES(:mmtid, :gid,:mode)', array(':mmtid' => $mmtid,':gid' => $groupId,':mode' => 'w'));
		$k++;
	//}
	}
	echo "Bluenog MM is done!";
	db_query('delete from {url_alias}');

  }else{
 	echo '<p>The Drupal Group ID you input is not validate!</p>';
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
