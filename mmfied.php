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
	echo "<table width='800' border='1' align='center' cellpadding='0' cellspacing='0'>";
  	echo "<tr><th>MM Trees</th><th>Node ID</th><th>Tree ID</th><th>Status</th></tr>";
	$result = db_query('SELECT nid, title FROM {node}');

	foreach ($result as $row) {
 	   //$node = node_load($row->nid);
	   //echo $node->nid;
	   $pos = strpos($row->title, '**');
//echo $pos;
	   if ($pos!==false) {
 		$items = array(new MMCreatePathCat(array('mmtid' => mm_home_mmtid())));
		//echo $node->path;
		$tempTitle = "Migration-".substr($row->title,2);
 		$path = explode('-', $tempTitle);
 		//echo "Node title: ".$row->title;
 		//echo "<br>";

		echo "<tr><td>";
		$n="";
 		foreach ($path as $elem) {
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
		/*
		  if (mm_create_path($items)) {
		  	$mmtid1 = $items[count($items) - 1]->mmtid;
		  	echo "MM ID: ".$mmtid1;
		  	echo "<br>";
		  }
*/
				 //echo $elem;
		 //echo "<br>";
 	 	}
		echo "</td>";
		//$tem = count($items);
		//echo $tem;
		//echo "<br>";
 		if (mm_create_path($items)) {

			$count=count($items);
			$mmtidList = "";
			for ($i = 2; $i <$count; $i++) {

				$mmtid1 = $items[$i]->mmtid;
				$mmtidList = $mmtidList.$mmtid1 ."<br>";
				$result1 = db_query('SELECT * FROM {mm_tree_access} n WHERE n.mmtid=:mmtid and n.gid=:gid', array(':mmtid' => $mmtid1,':gid' => $groupId));
   				$m = 0;
   				foreach ($result1 as $row1) {
			  		$m=$m+1;
   				}
				if($m==0){
					db_query('INSERT INTO {mm_tree_access}(mmtid,gid,mode) VALUES(:mmtid, :gid,:mode)', array(':mmtid' => $mmtid1,':gid' => $groupId,':mode' => 'w'));
				}

			}
   			$mmtid = $items[count($items) - 1]->mmtid;
			echo "<td>".$row->nid."</td>";
			//echo "<br>";
			echo "<td>".$mmtidList."</td>";
			//echo "<br>";
   			$result = db_query('SELECT * FROM {mm_node2tree} n WHERE n.nid = :nid and n.mmtid=:mmtid', array(':nid' => $row->nid, ':mmtid' => $mmtid));
   			$n = 0;
   			foreach ($result as $row) {
			  $n=$n+1;
   			}
			//echo "Record count:".$n;
			//echo "<br>";
   			if($n==0)
   			{
     				db_query('INSERT INTO {mm_node2tree} (nid, mmtid) VALUES(:nid, :mmtid)', array(':nid' => $row->nid, ':mmtid' => $mmtid));
     				db_query('INSERT INTO {mm_node_write} (nid, gid) VALUES(:nid, :gid)', array(':nid' => $row->nid, ':gid' => $groupId));
     				//db_query('INSERT INTO {mm_tree_access}(mmtid,gid,mode) VALUES(:mmtid, :gid,:mode)', array(':mmtid' => $mmtid,':gid' => $groupId,':mode' => 'w'));
     				echo "<td><b>Record created!</b></td></tr>";
     				//echo "<br>";
     				//echo "<br>";
   			}else{
				echo "<td><b>Record existed!</b></td></tr>";
				//echo "<br>";
       			//echo "<br>";
   			}
 		}
	   }
	}
	echo "</table>";
	db_query('delete from {url_alias}');
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

	//Clean node title
	$result = db_query('SELECT nid, title FROM {node}');

	foreach ($result as $row) {
		$title  = $row->title;
		$nid = $row->nid;
		$position = strpos($title, '**');
	   	if ($position!==false) {
//echo "Title: " . $title.", Nid: ".$nid."<br>";
			$title = substr($title,2);
			//$pos = strpos($title,'-');
			//$title = substr($title,$pos+1);
			db_query('UPDATE {node} set title=:title where nid=:nid',array(':title' => $title,':nid' => $nid));
		}
	}

	//Clean node title
	$result = db_query('SELECT nid, title FROM {node_revision}');

	foreach ($result as $row) {
		$title  = $row->title;
		$nid = $row->nid;
		$position = strpos($title, '**');
	   	if ($position!==false) {
//echo "Title: " . $title.", Nid: ".$nid."<br>";
			$title = substr($title,2);
			//$pos = strpos($title,'-');
			//$title = substr($title,$pos+1);
			db_query('UPDATE {node_revision} set title=:title where nid=:nid',array(':title' => $title,':nid' => $nid));
		}
	}



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
