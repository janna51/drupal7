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
  //$groupId =$_POST['groupid'];
  //echo "Group ID:";
  //echo $groupId;
  //Check the group ID exist
  $db = Database::getConnection('default', 'bluenog');
  $sql = "select * from page";
  $result= $db->query($sql) or die ("Error preparing statement to get uri string");

  //foreach($result as $record){
		//echo $record->path;
		//echo "<br>";
  //}
	//echo "get db connection!";
  foreach ($result as $row) {
		$title = $row->path;
		$pageId = $row->page_id;
		echo $pageId;
		echo "<br>";
 		$items = array(new MMCreatePathCat(array('mmtid' => mm_home_mmtid())));
 		$path = explode('/', $title);

		$n="";
 		foreach ($path as $elem) {
		  $elem = strtolower($elem);
		  if($elem == "file"||$elem == "system"||$elem == "search"||$elem == "members"||$elem == "help"){
			$elem = $elem ."page";
		  }

   		  if ($n=="")
   		  {
			$n=$elem;
   		  }else{
			$n=$n."||".$elem;
   		  }
		  echo "MM tree name: ".$n;
		  echo "<br>";
		  echo "MM tree alias: ".$elem;
		  echo "<br>";
   		  $items[] = new MMCreatePathCat(array(  
     			'name' => $n,
     			'alias' =>strtolower($elem)
   		  ));
 	 	}		
 		if (mm_create_path($items)) {
   			$mmtid = $items[count($items) - 1]->mmtid;
			echo $mmtid;
			echo "<br>";
			//$sql1="'UPDATE {page} set mmtid=:mmtid where page_id=:page_id',array(':mmtid' => $mmtid,':page_id' => $pageId)";
			//echo $sql1;
			//echo "<br>";
			$db->query('UPDATE {page} set mmtid=:mmtid where page_id=:page_id',array(':mmtid' => $mmtid,':page_id' => $pageId)) or die ("Error to update mmtid in page table");			
 		}
  }
	//clean up the page name
    $records = db_query('SELECT mmtid,name FROM {mm_tree}');
	foreach ($records as $row) {
  		$name = $row->name;
  		$mmtid = $row->mmtid;
  		$nameArray = explode("||",$name);
  		if(count($nameArray)>1)
  		{
    			$realName = $nameArray[count($nameArray)-1];
    			db_query('UPDATE {mm_tree} set name=:name where mmtid=:mmtid',array(':name' => $realName,':mmtid' => $mmtid));
    			//echo $realName;
    			//echo "<br>";
  		}
  	}
	
	echo "Bluenog page tree is created!";
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
