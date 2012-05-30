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
	print '<div style="margin-left: 50px;"><br><a href="fixfacility.php"><font size="4">Back to main page</font></a></div>';
	if (!$mysql_link = new mysqli ('endeavour.wellesley.edu', 'gwang', 'l0lersKat35', 'bluenog'))
      		die ("We're sorry, database connection failed. ");
	$mysql_link->set_charset("utf8");
	echo 'Connected successfully<br>';
	$sql = "select * from content";
	$stmt1 = $mysql_link->query($sql) or die ("Error preparing statement to get content info");
	/* execute prepared statement */ 
	//$stmt1->execute() or die ("Error executing statement to get uri string"); 
	$n=0;
	$file = "";
	$content_id=0;
	if (!$mysql_link_drupal = new mysqli ('endeavour.wellesley.edu', 'gwang', 'l0lersKat35', 'drupal'))
      		die ("We're sorry, database connection failed. ");
	$mysql_link_drupal->set_charset("utf8");
	while ($row = $stmt1->fetch_array(MYSQLI_BOTH)) {
	  //check the record exist
	  $content_title = $row["file_name"];
	  echo $row["file_name"]."<br>";
	  $content_title =  addslashes($content_title);

      	  $sql2 = "select * from node where title = '".$content_title."'";
         //echo $sql2;
	  $stmt2 = $mysql_link_drupal->query($sql2) or die ("Error preparing statement to get node info");
	  $exist = 0;
	  while ($row1 = $stmt2->fetch_array(MYSQLI_BOTH)) {
	  	$exist =  $exist+1;
	  }

	  //echo "Record count: ".$exist."<br>";
	  //if($n<=2)
	  //{
		//$file = $row["uri_string"];
		//echo $file;
	  if($exist==0){
	    $content_type = $row["type"];
//echo  $content_type."<br>";

		if($content_type == 'facilities'){
			$file = $row["file_name"];			
			echo $file;
			echo "<br>";
			

			$name = $row["name"];
			

			$type = $row["facilitiesType"];
			if($type==NULL){				
				$type="";
			}
			//echo "test";
			$image = $row["image"];
			if($image!=NULL){
				$image = trim(substr($image,9));
				//echo $image;
				//echo "<br>";
			}
			$headline = $row["headline"];
			if($headline==NULL){				
				$headline="";
			}
			

			$shortDes= $row["shortDescriptionHTML"];
			if($shortDes==NULL){				
				$shortDes="";
			}
			$longDes= $row["longdescription"];
			if($longDes==NULL){				
				$longDes="";
			}
			//echo $des;
			//echo "<br>";
			

			$link = $row["link"];
			if($link == NULL){
				$link = "";
			}
			//$link=addslashes($link);
			$tags = $row["tags"];
			$tagArray = array();
			if($tags!=NULL){
			  if(stripos($tagArray,",")===false){
				$tagArray[0] = $tags;
				
			  }else{
			  	$tagArray = explode(",", $tags);
			  }
			}
			

			$code = $row["code"];
			if($code == NULL){
				$code = "";
			}
			$location = $row["location"];
			if($location == NULL){
				$location = "";
			}
			$phone = $row["phone"];
			if($phone == NULL){
				$phone = "";
			}
			$node = new stdClass(); // We create a new node object
			$node->type = "facility"; // Or any other content type you want
			$node->title = $file;
			$node->language = LANGUAGE_NONE; // Or any language code if Locale module is enabled. More on this below *		
			$node->uid = 1; // Or any id you wish
			node_object_prepare($node); // Set some default values.
			//echo "node created!";
			

			$node->field_facilitytype[$node->language][0]['value'] = $type;
			if($image!=NULL){
				$file_path = drupal_realpath('/var/www/html/sites/default/files/assets'.$image); // Create a File object
				if (file_exists($file_path)) {
					$file = (object) array(
							  'uid' => 1,
							  'uri' => $file_path,
							  'filemime' => file_get_mimetype($filepath),
							  'status' => 1,
					 ); 

					$file = file_copy($file, 'public://'); // Save the file to the root of the files directory. You can specify a subdirectory, for example, 'public://images' 
					$node->field_facilityimage[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
				}else{
					echo "Image: ".$image." doesn't exist!";
				}
			}
				

			$node->field_facilityname[$node->language][0]['value'] = $name;
			$node->field_facilityheadline[$node->language][0]['value'] = $headline;
			$node->field_facilitylongdesc[$node->language][0]['value'] = $longDes;
			$node->field_facilitylongdesc[$node->language][0]['format'] = 'full_html'; // If field has a format, you need to define it. Here we define a default filtered_html format for a body field
			$node->field_facilityshortdesc[$node->language][0]['value'] = $shortDes;
			$node->field_facilityshortdesc[$node->language][0]['format'] = 'full_html';
			//echo "Fact text added!";		
			$node->field_facilitylink[$node->language][0]['value'] = $link;
			//echo "Fact title added!";
			if (count($tagArray)>0){
				foreach ($tagArray as $elem) {
					$elem = trim($elem);
					$term = taxonomy_tid($elem,1);//need attention
					$node->field_facilitytags[$node->language][]['tid'] = $term ;
				}
			}
			$node->field_facilitycode[$node->language][0]['value'] = $code;
			//echo "Fact source added!";
			$node->field_facilitylocation[$node->language][0]['value'] = $location;
			$node->field_facilityphone[$node->language][0]['value'] = $phone;
			

			//echo "Fact type added!";
			// Some file on our system
			

			//save node
			$node = node_submit($node); // Prepare node for a submit
			echo "prepared<br>";
			node_unpublish_action($node);
			// After this call we'll get a nid
			node_save($node); // After this call we'll get a nid
			echo "Facility node saved!<br>";
		}
	  }else{
		//echo "Node existed!<br>";
	  }
		$n++;
	}
	echo '<br>Node creation is done!<br>';
	mysql_close($mysql_link);
	mysql_close($mysql_link_drupal);

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

function taxonomy_tid($tname, $vid) {
  $tid = false;
  $taxo = new stdClass();
  $taxo = taxonomy_get_term_by_name($tname);
  if (!$taxo) {
    $taxo = new stdClass();
    $taxo->name = $tname;
    $taxo->vid = $vid; 
    print_r($taxo);
    echo "<br>";
    $status = taxonomy_term_save($taxo); 
    $taxo = taxonomy_get_term_by_name($tname);
  }

  foreach($taxo as $ind=>$valor) {
    if ($valor->vid = $vid) $tid = $valor->tid;
  }
  return $tid;
}

?>














