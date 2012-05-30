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
	print '<div style="margin-left: 50px;"><br><a href="cmsmigration.php"><font size="4">Back to main page</font></a></div>';
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
	    if($content_type == 'article'){
			$file = $row["file_name"];
			echo $file;
			echo "<br>";
			$headline = $row["headline"];
			if($headline == NULL){
				$headline="";
			}
			//echo $headline;
			//echo "<br>";

			$des= $row["fullDescription"];
			//echo $des;
			//echo "<br>";
			

			$image = $row["image"];
			if($image!=NULL){
				$image = trim(substr($image,9));
				//echo $image;
				//echo "<br>";
			}
			$metadata = $row["metadata"];
			if($metadata==NULL){				
				$metadata="";
			}
			$metadata = addslashes($metadata);
			//print "Metadata: ".$metadata."<br>";
			//print "End of metadata<br>";
			$node = new stdClass(); // We create a new node object
			$node->type = "articlehistorical"; // Or any other content type you want
			$node->title = $file;
			$node->language = LANGUAGE_NONE; // Or any language code if Locale module is enabled. More on this below *
			$node->uid = 1; // Or any id you wish
			//$node->path = array('alias' => 'your node path'); // Setting a node path
			node_object_prepare($node); // Set some default values.
			//echo "node created!";
			

			// Let's add full description field			
			$node->field_articlehiststory[$node->language][0]['value'] = $des;
			$node->field_articlehiststory[$node->language][0]['format'] = 'full_html'; // If field has a format, you need to define it. Here we define a default filtered_html format for a body field
			//echo "Full description added!";
			// Let's add headline field			
			$node->field_articlehistheadline[$node->language][0]['value'] = $headline;
			//$metadata = '<script type="text/javascript">Shadowbox.init({players:  ["img" , "flv", "swf", "qt"] });  </script>';
			//echo "Metadata: ".$metadata."<br>";
			//$node->field_articlehistmetadata[$node->language][0]['value'] = $metadata;

			// Some file on our system
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
					$node->field_articlehistimage[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
				}else{
					echo "Image: ".$image." doesn't exist!";
				}
			}
			//save node
			$node = node_submit($node); // Prepare node for a submit
			// node_save($node); // After this call we'll get a nid
			node_unpublish_action($node);
			node_save($node);
			echo "Articlehistorical node saved!<br>";
		}

		if($content_type == 'fact'){
			$file = $row["file_name"];			
			echo $file;
			echo "<br>";
			

			$title1 = $row["shortTitle"];
			if($title1==NULL){				
				$title1="";
			}
			$title2 = $row["shortTitleSecond"];
			if($title2==NULL){				
				$title2="";
			}
			$title = $title1." ".$title2;
			

			$des= $row["factDescription"];
			//echo $des;
			//echo "<br>";
			$image = $row["image"];
			if($image!=NULL){
				$image = trim(substr($image,9));
				//echo $image;
				//echo "<br>";
			}
			$source = $row["source"];
			if($source == NULL){
				$source = "";
			}
			$tags = $row["tags"];
			$tagArray = array();
			if($tags!=NULL){
			  if(stripos($tagArray,",")===false){
				$tagArray[0] = $tags;
				
			  }else{
			  	$tagArray = explode(",", $tags);
			  }
			}
			

			$type = $row["topicType"];
			if($type==NULL){
				$type = "";
			}
			//echo $type;
			//echo "<br>";
			$node = new stdClass(); // We create a new node object
			$node->type = "fact"; // Or any other content type you want
			$node->title = $file;
			$node->language = LANGUAGE_NONE; // Or any language code if Locale module is enabled. More on this below *		
			$node->uid = 1; // Or any id you wish
			node_object_prepare($node); // Set some default values.
			//echo "node created!";	
			if (count($tagArray)>0){
				foreach ($tagArray as $elem) {
					$elem = trim($elem);
					$term = taxonomy_tid($elem,1);//need attention
					$node->field_facttags[$node->language][]['tid'] = $term ;
				}
			}
			$node->field_facttext[$node->language][0]['value'] = $des;
			$node->field_facttext[$node->language][0]['format'] = 'full_html'; // If field has a format, you need to define it. Here we define a default filtered_html format for a body field
			//echo "Fact text added!";		
			$node->field_facttitle2[$node->language][0]['value'] = $title;
			//echo "Fact title added!";
			$node->field_factsource[$node->language][0]['value'] = $source;
			//echo "Fact source added!";
			$node->field_facttype[$node->language][0]['value'] = $type;
			//echo "Fact type added!";
			// Some file on our system
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
					$node->field_factimage[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
				}else{
					echo "Image: ".$image." doesn't exist!";
				}
			}
			//save node
			$node = node_submit($node); // Prepare node for a submit
			node_unpublish_action($node);
			node_save($node); // After this call we'll get a nid
			echo "Fact node saved!<br>";
			

		}
		
		


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
		

		if($content_type == 'profile'){
			$file = $row["file_name"];
			echo $file;
			echo "<br>";
			$fName = $row["firstName"];
			$lName = $row["lastName"];
			$lNameCollege = $row["lastNameAtCollege"];
			if($lNameCollege==NULL){
				$lNameCollege = "";
			}
			$gradYear = $row["graduationYear"];
			if($gradYear==NULL){
				$gradYear = "";
			}
			$gradYearAlum = $row["graduationYearAlum"];
			if($gradYearAlum==NULL){
				$gradYearAlum = "";
			}
			$occupation = $row["occupation"];
			if($occupation==NULL){
				$occupation = "";
			}
			$jobTitle = $row["title"];
			if($jobTitle==NULL){
				$jobTitle = "";
			}
			$jobTitleStaff = $row["titleStaff"];
			if($jobTitleStaff==NULL){
				$jobTitleStaff = "";
			}
			$jobTitleExpert = $row["titleExpert"];
			if($jobTitleExpert==NULL){
				$jobTitleExpert = "";
			}
			$officeHours = $row["officeHours"];
			if($officeHours==NULL){
				$officeHours = "";
			}
			$officeLocation = $row["officeLocation"];
			if($officeLocation==NULL){
				$officeLocation = "";
			}
			$officeLocationExpert = $row["officeLocationExpert"];
			if($officeLocationExpert==NULL){
				$officeLocationExpert = "";
			}
			$headline = $row["headlineHTML"];
			

			$major = $row["major"];
			if($major==NULL){
				$major = "";
			}
			$majorAlum = $row["majorAlum"];
			if($majorAlum==NULL){
				$majorAlum = "";
			}
			$department = $row["department"];
			if($department==NULL){
				$department = "";
			}
			$departmentStaff = $row["departmentStaff"];
			if($departmentStaff==NULL){
				$departmentStaff = "";
			}
			$departmentExpert = $row["departmentExpert"];
			if($departmentExpert==NULL){
				$departmentExpert = "";
			}
			$schoolProgram = $row["schoolProgram"];
			if($schoolProgram==NULL){
				$schoolProgram = "";
			}
			$schoolProgramExpert = $row["schoolProgramExpert"];
			if($schoolProgramExpert==NULL){
				$schoolProgramExpert = "";
			}
			$homeCity = $row["homeCity"];
			if($homeCity==NULL){
				$homeCity = "";
			}
			$homeState = $row["homeState"];
			if($homeState==NULL){
				$homeState = "";
			}
			$homeCountry = $row["homeCountry"];
			if($homeCountry==NULL){
				$homeCountry = "";
			}
			$currentCity = $row["currentCity"];
			if($currentCity==NULL){
				$currentCity = "";
			}
			$currentState = $row["currentState"];
			if($currentState==NULL){
				$currentState = "";
			}
			$currentCountry = $row["currentCountry"];
			if($currentCountry==NULL){
				$currentCountry = "";
			}
			$image = $row["image"];
			if($image!=NULL){
				$image = trim(substr($image,9));
				//echo $image;
				//echo "<br>";
			}
			$portrait = $row["portrait"];
			if($portrait!=NULL){
				$portrait = trim(substr($portrait,9));
				//echo $portrait;
				//echo "<br>";
			}
			$shortDes = $row["shortDescription"];
			if($shortDes==NULL){
				$shortDes = "";
			}
			$longDes = $row["longdescription"];
			if($longDes==NULL){
				$longDes = "";
			}
			$academicWork = $row["academicWork"];
			if($academicWork==NULL){
				$academicWork = "";
			}
			$currentProject = $row["currentProject"];
			if($currentProject==NULL){
				$currentProject = "";
			}
			$teaching = $row["teaching"];
			if($teaching==NULL){
				$teaching = "";
			}
			$coaching = $row["coaching"];
			if($coaching==NULL){
				$coaching = "";
			}
			$favLinksHeader = $row["favLinksHeader"];
			if($favLinksHeader==NULL){
				$favLinksHeader = "";
			}
			$favLinks = $row["favLinks"];
			if($favLinks==NULL){
				$favLinks = "";
			}
			$otherInfoHeader = $row["otherInfoHeader"];
			if($otherInfoHeader==NULL){
				$otherInfoHeader = "";
			}
			$otherInfo = $row["otherInfo"];
			if($otherInfo==NULL){
				$otherInfo = "";
			}
			$cv = $row["cv"];
			if($cv!=NULL){
				$cv = trim(substr($cv,9));
			}
			$mediaBio = $row["mediaBio"];
			if($mediaBio!=NULL){
				$mediaBio  = trim(substr($mediaBio ,9));
			}
			$link = $row["link"];
			if($link==NULL){
				$link = "";
			}
			$tags = $row["tags"];			
			$tagArray = array();
			if($tags!=NULL){
			  if(stripos($tagArray,",")===false){
				$tagArray[0] = $tags;
				
			  }else{
			  	$tagArray = explode(",", $tags);
			  }
			}
			echo count($tagArray);
			$phone = $row["phone"];
			if($phone==NULL){
				$phone = "";
			}
			$email = $row["email"];
			if($email==NULL){
				$email = "";
			}
			$bannerId= $row["bannerId"];
			if($bannerId==NULL){
				$bannerId = "";
			}
			$profileType = $row["profileType"];
			

			if($profileType=='Faculty'){			
				$node = new stdClass(); // We create a new node object
				$node->type = "facultyprofile"; // Or any other content type you want
				$node->title = $file;
				$node->language = LANGUAGE_NONE; // Or any language code if Locale module is enabled. More on this below *		
				$node->uid = 1; // Or any id you wish
				node_object_prepare($node); // Set some default values.
				//echo "node created!";
				$node->field_profilefirst[$node->language][0]['value'] = $fName;
				$node->field_profilelast[$node->language][0]['value'] = $lName;

				// Some file on our system
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
						$node->field_profilewide[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
					}else{
						echo "Image: ".$image." doesn't exist!";
					}
				}
				if($portrait!=NULL){
					$file_path = drupal_realpath('/var/www/html/sites/default/files/assets'.$portrait); // Create a File object
					if (file_exists($file_path)) {
						$file = (object) array(
							  'uid' => 1,
							  'uri' => $file_path,
							  'filemime' => file_get_mimetype($filepath),
							  'status' => 1,
					 	); 

						$file = file_copy($file, 'public://'); // Save the file to the root of the files directory. You can specify a subdirectory, for example, 'public://images' 
						$node->field_profileportrait[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
					}else{
						echo "Image: ".$image." doesn't exist!";
					}
				}
				$node->field_profileheadline2[$node->language][0]['value'] = $headline;
				$node->field_profileheadline2[$node->language][0]['format'] = 'full_html';
				$node->field_profilelongdesc[$node->language][0]['value'] = $longDes;
				$node->field_profilelongdesc[$node->language][0]['format'] = 'full_html'; // If field has a format, you need to define it. Here we define a default filtered_html format for a body field		
				$node->field_profileshortdesc[$node->language][0]['value'] = $shortDes;
				$node->field_profileshortdesc[$node->language][0]['format'] = 'full_html';
				$node->field_profileemail[$node->language][0]['value'] = $email;
				$node->field_profilephone[$node->language][0]['value'] = $phone;
				

				if (count($tagArray)>0){
					foreach ($tagArray as $elem) {
						$elem = trim($elem);
						$term = taxonomy_tid($elem,1);//need attention
						$node->field_profiletag[$node->language][]['tid'] = $term ;
					}
				}
				$node->field_profilelink2[$node->language][0]['value'] = $link;
				$node->field_profilebanner[$node->language][0]['value'] = $bannerId;
				$node->field_profilepostitle[$node->language][0]['value'] = $jobTitle;
				$node->field_profileprograms[$node->language][0]['value'] = $schoolProgram;
				$node->field_profiledept[$node->language][0]['value'] = $department;
				$node->field_profileofficehrs[$node->language][0]['value'] = $officeHours;
				$node->field_profileofficeloc[$node->language][0]['value'] = $officeLocation;
				$node->field_profilefavlinkshead[$node->language][0]['value'] = $favLinksHeader;
				$node->field_profilefavlinks[$node->language][0]['value'] = $favLinks;
				$node->field_profilefavlinks[$node->language][0]['format'] = 'full_html';
/*
				$node->field_profilecreative[$node->language][0]['value'] = $academicWork;
				$node->field_profilecreative[$node->language][0]['format'] = 'full_html';
				$node->field_profileresearch[$node->language][0]['value'] = $currentProject;
				$node->field_profileresearch[$node->language][0]['format'] = 'full_html';
				$node->field_profileteaching[$node->language][0]['value'] = $teaching;
				$node->field_profileteaching[$node->language][0]['format'] = 'full_html';
				$node->field_profilecoaching[$node->language][0]['value'] = $coaching;
				$node->field_profilecoaching[$node->language][0]['format'] = 'full_html';

				$node->field_profileotherinfohead[$node->language][0]['value'] = $otherInfoHeader;
				$node->field_profileotherinfo[$node->language][0]['value'] = $otherInfo;
				$node->field_profileotherinfo[$node->language][0]['format'] = 'full_html';
*/
				if($cv!=NULL){
					$file_path = drupal_realpath('/var/www/html/sites/default/files/assets'.$cv); // Create a File object
					if (file_exists($file_path)) {
						$file = (object) array(
							  'uid' => 1,
							  'uri' => $file_path,
							  'filemime' => file_get_mimetype($filepath),
							  'status' => 1,
							  'display'=>1,
  							  'description'=>'',
					 	); 

						$file = file_copy($file, 'public://'); // Save the file to the root of the files directory. You can specify a subdirectory, for example, 'public://images' 
						$node->field_profilecv[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
					}else{
						echo "Image: ".$image." doesn't exist!";
					}
				}
				if($mediaBio!=NULL){
					$file_path = drupal_realpath('/var/www/html/sites/default/files/assets'.$mediaBio); // Create a File object
					if (file_exists($file_path)) {
						$file = (object) array(
							  'uid' => 1,
							  'uri' => $file_path,
							  'filemime' => file_get_mimetype($filepath),
							  'status' => 1,
							  'display'=>1,
  							  'description'=>'',
					 	); 

						$file = file_copy($file, 'public://'); // Save the file to the root of the files directory. You can specify a subdirectory, for example, 'public://images' 
						$node->field_profilemediabio[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
					}else{
						echo "Image: ".$image." doesn't exist!";
					}
				}
				//save node
				$node = node_submit($node); // Prepare node for a submit
				node_unpublish_action($node);
				node_save($node); // After this call we'll get a nid
				echo "Faculity profile basic info node saved!<br>";
				
				//save special fields to Basic page
				$file = substr($content_title,0);
				//AcademicWork
				if($academicWork != ""){
					$node = new stdClass(); // We create a new node object
					$node->type = "page"; // Or any other content type you want
					$file = $file."/academicwork";
					//$file = "academicwork";
					$node->title = $file;
					$node->language = LANGUAGE_NONE; // Or any language code if Locale module is enabled. More on this below *		
					$node->uid = 1; // Or any id you wish
					node_object_prepare($node); // Set some default values.				
					$node->body[$node->language][0]['value'] = $academicWork;
					$node->body[$node->language][0]['format'] = 'full_html';
			
					//save node
					$node = node_submit($node); // Prepare node for a submit
					node_unpublish_action($node);
					node_save($node); // After this call we'll get a nid
					echo "Faculty Academic Work node saved!";
				}
				//CurrentProject
				if($currentProject != ""){
					$node = new stdClass(); // We create a new node object
					$node->type = "page"; // Or any other content type you want
					$file = $file."/currentproject";
					//$file = "currentproject";
					$node->title = $file;
					$node->language = LANGUAGE_NONE; // Or any language code if Locale module is enabled. More on this below *		
					$node->uid = 1; // Or any id you wish
					node_object_prepare($node); // Set some default values.				
					$node->body[$node->language][0]['value'] = $currentProject;
					$node->body[$node->language][0]['format'] = 'full_html';
			
					//save node
					$node = node_submit($node); // Prepare node for a submit
					node_unpublish_action($node);
					node_save($node); // After this call we'll get a nid
					echo "Faculty Current Project node saved!";
				}
				
				//Teaching
				if($teaching != ""){
					$node = new stdClass(); // We create a new node object
					$node->type = "page"; // Or any other content type you want
					$file = $file."/teaching";
					//$file = "teaching";
					$node->title = $file;
					$node->language = LANGUAGE_NONE; // Or any language code if Locale module is enabled. More on this below *		
					$node->uid = 1; // Or any id you wish
					node_object_prepare($node); // Set some default values.				
					$node->body[$node->language][0]['value'] = $teaching;
					$node->body[$node->language][0]['format'] = 'full_html';
			
					//save node
					$node = node_submit($node); // Prepare node for a submit
					node_unpublish_action($node);
					node_save($node); // After this call we'll get a nid
					echo "Faculty Teaching node saved!<br>";
				}
				
				//Coaching
				if($coaching != ""){
					$node = new stdClass(); // We create a new node object
					$node->type = "page"; // Or any other content type you want
					$file = $file."/coaching";
					//$file = "teaching";
					$node->title = $file;
					$node->language = LANGUAGE_NONE; // Or any language code if Locale module is enabled. More on this below *		
					$node->uid = 1; // Or any id you wish
					node_object_prepare($node); // Set some default values.				
					$node->body[$node->language][0]['value'] = $coaching;
					$node->body[$node->language][0]['format'] = 'full_html';
			
					//save node
					$node = node_submit($node); // Prepare node for a submit
					node_unpublish_action($node);
					node_save($node); // After this call we'll get a nid
					echo "Faculty Coaching node saved!<br>";
				}
				
				//Coaching
				if($otherInfo != ""){
					$node = new stdClass(); // We create a new node object
					$node->type = "page"; // Or any other content type you want
					$file = $file."/".$otherInfoHeader;
					//$file = $otherInfoHeader;
					$node->title = $file;
					$node->language = LANGUAGE_NONE; // Or any language code if Locale module is enabled. More on this below *		
					$node->uid = 1; // Or any id you wish
					node_object_prepare($node); // Set some default values.				
					$node->body[$node->language][0]['value'] = $coaching;
					$node->body[$node->language][0]['format'] = 'full_html';
			
					//save node
					$node = node_submit($node); // Prepare node for a submit
					node_unpublish_action($node);
					node_save($node); // After this call we'll get a nid
					echo "Faculty Other Info node saved!<br>";
				}
				
				
				

			}
			

			if($profileType=='Expert'){			
				$node = new stdClass(); // We create a new node object
				$node->type = "expertprofile"; // Or any other content type you want
				$node->title = $file;
				$node->language = LANGUAGE_NONE; // Or any language code if Locale module is enabled. More on this below *		
				$node->uid = 1; // Or any id you wish
				node_object_prepare($node); // Set some default values.
				//echo "node created!";
				$node->field_profilefirst[$node->language][0]['value'] = $fName;
				$node->field_profilelast[$node->language][0]['value'] = $lName;
				$node->field_profilepostitle[$node->language][0]['value'] = $jobTitleExpert;
				$node->field_profileprograms[$node->language][0]['value'] = $schoolProgramExpert;
				$node->field_profiledept[$node->language][0]['value'] = $departmentExpert;
				$node->field_profileofficeloc[$node->language][0]['value'] = $officeLocationExpert;
				// Some file on our system
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
						$node->field_profilewide[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
					}else{
						echo "Image: ".$image." doesn't exist!";
					}
				}
				if($portrait!=NULL){
					$file_path = drupal_realpath('/var/www/html/sites/default/files/assets'.$portrait); // Create a File object
					if (file_exists($file_path)) {
						$file = (object) array(
							  'uid' => 1,
							  'uri' => $file_path,
							  'filemime' => file_get_mimetype($filepath),
							  'status' => 1,
					 	); 

						$file = file_copy($file, 'public://'); // Save the file to the root of the files directory. You can specify a subdirectory, for example, 'public://images' 
						$node->field_profileportrait[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
					}else{
						echo "Image: ".$image." doesn't exist!";
					}
				}
				$node->field_profileheadline2[$node->language][0]['value'] = $headline;
				$node->field_profileheadline2[$node->language][0]['format'] = 'full_html';
				$node->field_profilelongdesc[$node->language][0]['value'] = $longDes;
				$node->field_profilelongdesc[$node->language][0]['format'] = 'full_html'; // If field has a format, you need to define it. Here we define a default filtered_html format for a body field		
				$node->field_profileshortdesc[$node->language][0]['value'] = $shortDes;
				$node->field_profileshortdesc[$node->language][0]['format'] = 'full_html';
				$node->field_profileemail[$node->language][0]['value'] = $email;
				$node->field_profilephone[$node->language][0]['value'] = $phone;
				

				if (count($tagArray)>0){
					foreach ($tagArray as $elem) {
						$elem = trim($elem);
						$term = taxonomy_tid($elem,1);//need attention
						$node->field_profiletag[$node->language][]['tid'] = $term ;
					}
				}
				$node->field_profilelink2[$node->language][0]['value'] = $link;
				$node->field_profilebanner[$node->language][0]['value'] = $bannerId;
				

				


				//save node
				$node = node_submit($node); // Prepare node for a submit
				node_unpublish_action($node);
				node_save($node); // After this call we'll get a nid
				echo "node saved!<br>";
			}
			

			if($profileType=='Student'){			
				$node = new stdClass(); // We create a new node object
				$node->type = "studentprofile"; // Or any other content type you want
				$node->title = $file;
				$node->language = LANGUAGE_NONE; // Or any language code if Locale module is enabled. More on this below *		
				$node->uid = 1; // Or any id you wish
				node_object_prepare($node); // Set some default values.
				//echo "node created!";
				$node->field_profilefirst[$node->language][0]['value'] = $fName;
				$node->field_profilelast[$node->language][0]['value'] = $lName;
				$node->field_profilegradyr[$node->language][0]['value'] = $gradYear;
				$node->field_profilemajor[$node->language][0]['value'] = $major;
				$node->field_profilehomecity[$node->language][0]['value'] = $homeCity;
				$node->field_profilehomestate[$node->language][0]['value'] = $homeState;
				$node->field_profilehomecountry[$node->language][0]['value'] = $homeCountry;
				// Some file on our system
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
						$node->field_profilewide[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
					}else{
						echo "Image: ".$image." doesn't exist!";
					}
				}
				if($portrait!=NULL){
					$file_path = drupal_realpath('/var/www/html/sites/default/files/assets'.$portrait); // Create a File object
					if (file_exists($file_path)) {
						$file = (object) array(
							  'uid' => 1,
							  'uri' => $file_path,
							  'filemime' => file_get_mimetype($filepath),
							  'status' => 1,
					 	); 

						$file = file_copy($file, 'public://'); // Save the file to the root of the files directory. You can specify a subdirectory, for example, 'public://images' 
						$node->field_profileportrait[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
					}else{
						echo "Image: ".$image." doesn't exist!";
					}
				}
				$node->field_profileheadline2[$node->language][0]['value'] = $headline;
				$node->field_profileheadline2[$node->language][0]['format'] = 'full_html';
				$node->field_profilelongdesc[$node->language][0]['value'] = $longDes;
				$node->field_profilelongdesc[$node->language][0]['format'] = 'full_html'; // If field has a format, you need to define it. Here we define a default filtered_html format for a body field		
				$node->field_profileshortdesc[$node->language][0]['value'] = $shortDes;
				$node->field_profileshortdesc[$node->language][0]['format'] = 'full_html';
				$node->field_profileemail[$node->language][0]['value'] = $email;
				$node->field_profilephone[$node->language][0]['value'] = $phone;
				

				if (count($tagArray)>0){
					foreach ($tagArray as $elem) {
						$elem = trim($elem);
						$term = taxonomy_tid($elem,1);//need attention
						$node->field_profiletag[$node->language][]['tid'] = $term ;
					}
				}
				$node->field_profilelink2[$node->language][0]['value'] = $link;
				$node->field_profilebanner[$node->language][0]['value'] = $bannerId;
				

				


				//save node
				$node = node_submit($node); // Prepare node for a submit
				node_unpublish_action($node);
				node_save($node); // After this call we'll get a nid
				echo "node saved!<br>";
			}
			

			if($profileType=='Alumnae'){			
				$node = new stdClass(); // We create a new node object
				$node->type = "alumprofile"; // Or any other content type you want
				$node->title = $file;
				$node->language = LANGUAGE_NONE; // Or any language code if Locale module is enabled. More on this below *		
				$node->uid = 1; // Or any id you wish
				node_object_prepare($node); // Set some default values.
				//echo "node created!";
				$node->field_profilefirst[$node->language][0]['value'] = $fName;
				$node->field_profilelast[$node->language][0]['value'] = $lName;
				$node->field_profilegradyr[$node->language][0]['value'] = $gradYearAlum;
				$node->field_profilemajor[$node->language][0]['value'] = $majorAlum;
				$node->field_profilenamecollege[$node->language][0]['value'] = $lNameCollege;
				$node->field_profileoccupation[$node->language][0]['value'] = $occupation;
				$node->field_profilecurrcity[$node->language][0]['value'] = $currentCity;
				$node->field_profilecurrstate[$node->language][0]['value'] = $currentState;
				$node->field_profilecurrcountry[$node->language][0]['value'] = $currentCountry;
				// Some file on our system
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
						$node->field_profilewide[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
					}else{
						echo "Image: ".$image." doesn't exist!";
					}
				}
				if($portrait!=NULL){
					$file_path = drupal_realpath('/var/www/html/sites/default/files/assets'.$portrait); // Create a File object
					if (file_exists($file_path)) {
						$file = (object) array(
							  'uid' => 1,
							  'uri' => $file_path,
							  'filemime' => file_get_mimetype($filepath),
							  'status' => 1,
					 	); 

						$file = file_copy($file, 'public://'); // Save the file to the root of the files directory. You can specify a subdirectory, for example, 'public://images' 
						$node->field_profileportrait[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
					}else{
						echo "Image: ".$image." doesn't exist!";
					}
				}
				$node->field_profileheadline2[$node->language][0]['value'] = $headline;
				$node->field_profileheadline2[$node->language][0]['format'] = 'full_html';
				$node->field_profilelongdesc[$node->language][0]['value'] = $longDes;
				$node->field_profilelongdesc[$node->language][0]['format'] = 'full_html'; // If field has a format, you need to define it. Here we define a default filtered_html format for a body field		
				$node->field_profileshortdesc[$node->language][0]['value'] = $shortDes;
				$node->field_profileshortdesc[$node->language][0]['format'] = 'full_html';
				$node->field_profileemail[$node->language][0]['value'] = $email;
				$node->field_profilephone[$node->language][0]['value'] = $phone;
				

				if (count($tagArray)>0){
					foreach ($tagArray as $elem) {
						$elem = trim($elem);
						$term = taxonomy_tid($elem,1);//need attention
						$node->field_profiletag[$node->language][]['tid'] = $term ;
					}
				}
				$node->field_profilelink2[$node->language][0]['value'] = $link;
				$node->field_profilebanner[$node->language][0]['value'] = $bannerId;
				

				


				//save node
				$node = node_submit($node); // Prepare node for a submit
				node_unpublish_action($node);
				node_save($node); // After this call we'll get a nid
				echo "node saved!<br>";
			}
			

			if($profileType=='Staff'){			
				$node = new stdClass(); // We create a new node object
				$node->type = "staffprofile"; // Or any other content type you want
				$node->title = $file;
				$node->language = LANGUAGE_NONE; // Or any language code if Locale module is enabled. More on this below *		
				$node->uid = 1; // Or any id you wish
				node_object_prepare($node); // Set some default values.
				//echo "node created!";
				$node->field_profilefirst[$node->language][0]['value'] = $fName;
				$node->field_profilelast[$node->language][0]['value'] = $lName;
				$node->field_profilejobtitle[$node->language][0]['value'] = $jobTitleStaff;
				

				// Some file on our system
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
						$node->field_profilewide[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
					}else{
						echo "Image: ".$image." doesn't exist!";
					}
				}
				if($portrait!=NULL){
					$file_path = drupal_realpath('/var/www/html/sites/default/files/assets'.$portrait); // Create a File object
					if (file_exists($file_path)) {
						$file = (object) array(
							  'uid' => 1,
							  'uri' => $file_path,
							  'filemime' => file_get_mimetype($filepath),
							  'status' => 1,
					 	); 

						$file = file_copy($file, 'public://'); // Save the file to the root of the files directory. You can specify a subdirectory, for example, 'public://images' 
						$node->field_profileportrait[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
					}else{
						echo "Image: ".$image." doesn't exist!";
					}
				}
				$node->field_profileheadline2[$node->language][0]['value'] = $headline;
				$node->field_profileheadline2[$node->language][0]['format'] = 'full_html';
				$node->field_profilelongdesc[$node->language][0]['value'] = $longDes;
				$node->field_profilelongdesc[$node->language][0]['format'] = 'full_html'; // If field has a format, you need to define it. Here we define a default filtered_html format for a body field		
				$node->field_profileshortdesc[$node->language][0]['value'] = $shortDes;
				$node->field_profileshortdesc[$node->language][0]['format'] = 'full_html';
				$node->field_profileemail[$node->language][0]['value'] = $email;
				$node->field_profilephone[$node->language][0]['value'] = $phone;
				

				if (count($tagArray)>0){
					foreach ($tagArray as $elem) {
						$elem = trim($elem);
						$term = taxonomy_tid($elem,1);//need attention
						$node->field_profiletag[$node->language][]['tid'] = $term ;
					}
				}
				$node->field_profilelink2[$node->language][0]['value'] = $link;
				$node->field_profilebanner[$node->language][0]['value'] = $bannerId;
				$node->field_profiledept[$node->language][0]['value'] = $departmentExpert;

				


				//save node
				$node = node_submit($node); // Prepare node for a submit
				node_unpublish_action($node);
				node_save($node); // After this call we'll get a nid
				echo "node saved!<br>";
			}
		}

		
		if($content_type == 'news'){
			$file = $row["file_name"];			
			echo $file;
			echo "<br>";
			
			$headline = $row["headline"];
			$bylinedate = $row["bylineDate"];
			//echo "By line date: ".$bylinedate;
			// "<br>";
			//$temp_date = strtotime($bylinedate)-date("Z")-3600;
			$temp_date = strtotime($bylinedate);
			//$formated_date = date("Y-m-d\TH:i:s",$temp_date);
			$formated_date = date("Y-m-d",$temp_date);
			echo "Formated date: ".$formated_date;
			echo "<br>";

			$image = $row["image"];
			if($image!=NULL){
				$image = trim(substr($image,9));
				//echo $image;
				//echo "<br>";
			}
			
			$shortDescription= $row["shortDescriptionHTML"];
			if($shortDescription==NULL){				
				$shortDescription="";
			}
			//$shortDescription = addslashes($shortDescription);

//$shortDescription="test";
			echo "Short Description: ".$shortDescription;
			echo "<br>";
			
			$longDescription= $row["longDescriptionHTML"];
			if($longDescription==NULL){				
				$longDescription="";
			}
			
			$metadata = $row["metadataNews"];
			if($metadata==NULL){				
				$metadata="";
			}
			$metadata = addslashes($metadata);

			$multimedia = $row["multimedia"];
			if($multimedia == NULL){
				$multimedia = "";
			}
			$tags = $row["tags"];
			$tagArray = array();
			if($tags!=NULL){
			  if(stripos($tagArray,",")===false){
				$tagArray[0] = $tags;
				
			  }else{
			  	$tagArray = explode(",", $tags);
			  }
			}
			
			$topicType= $row["topicType"];
			if($topicType==NULL){
				$topicType = "";
			}
			//echo $topicType.";";
			//echo "<br>";
			$node = new stdClass(); // We create a new node object
			$node->type = "news"; // Or any other content type you want
			$node->title = $file;
			$node->language = LANGUAGE_NONE; // Or any language code if Locale module is enabled. More on this below *		
			$node->uid = 1; // Or any id you wish
			node_object_prepare($node); // Set some default values.
			//echo "node created!";	
			if (count($tagArray)>0){
				foreach ($tagArray as $elem) {
					$elem = trim($elem);
					$term = taxonomy_tid($elem,1);//need attention
					$node->field_newstag[$node->language][]['tid'] = $term ;
				}
			}
			
			//echo "Fact text added!";		
			$node->field_newsheadline[$node->language][0]['value'] = $headline;
			// For date
			//$node->field_newsdate[$node->language][0][value] = "2011-05-25T10:35:58";
			$node->field_newsdate[$node->language][0]['value'] = $formated_date;
			//echo "Fact title added!";
			$node->field_newsshortdesc[$node->language][0]['value'] = $shortDescription;
			$node->field_newsshortdesc[$node->language][0]['format'] = 'full_html'; // If field has a format, you need to define it. Here we define a default filtered_html format for a body field
			$node->field_newsfullstory[$node->language][0]['value'] = $longDescription;
			$node->field_newsfullstory[$node->language][0]['format'] = 'full_html'; // If field has a format, you need to define it. Here we define a default filtered_html format for a body field
			//$node->field_newsmetadata[$node->language][0]['value'] = $metadata;
			$node->field_newslink[$node->language][0]['value'] = $multimedia;
			//echo "Fact source added!";
			$node->field_newstopictype[$node->language][0]['value'] = $topicType;
			//echo "Fact type added!";
			// Some file on our system
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
					$node->field_newsimage[LANGUAGE_NONE][0] = (array)$file; //associate the file object with the image field:
				}else{
					echo "Image: ".$image." doesn't exist!";
				}
			}
			//save node
			$node = node_submit($node); // Prepare node for a submit
			node_unpublish_action($node);
			node_save($node); // After this call we'll get a nid
			echo "News node saved!<br>";
		}
	  }	else{
		//echo "Node existed!<br>";
	  }
		$n++;
		//echo $n."<br>";
	 // }
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














