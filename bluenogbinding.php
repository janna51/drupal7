<?php
include_once "config.php";

if (defined('ORACLE_SRV')) {

	//MySql get bluenog content
	$sql = "select * from content";
	$stmt1 = $mysql_link->query($sql) or die ("Error preparing statement to get uri string");
	/* execute prepared statement */ 
	//$stmt1->execute() or die ("Error executing statement to get uri string"); 
	$n=0;
	$file = "";
	$content_id=0;
	while ($row = $stmt1->fetch_array(MYSQLI_BOTH)) {
		//if($n<=10)
		//{
			$file = $row["uri_string"];
			$pos = strpos($file,"/content");
			$file = trim(substr($file,$pos));
      		echo $file;
			echo "<br>";
			$uri_id = $row["uri_id"]; 
			echo $uri_id;
			echo "<br>";
			$content_id = $row["content_id"];
		
	
	
	
	
	//Find the value in fragment_pref_value table
  	$sql = "SELECT * FROM bluenogice.fragment_pref_value where value='".$file."'";
  	//echo $sql;
  	//echo "<br>";
 	$stmt = oci_parse($oracle_link,$sql) or die ("Error in parsing SQL");

  	oci_execute($stmt, OCI_DEFAULT) or die ("Error in exectuing lookup sql!"); 

	$nrows = oci_fetch_all($stmt, $results, null, null,OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);

	// "From fragment_pref_value table:<br>";
  	//echo "$nrows rows fetched<br>\n";
 	//var_dump($results);
	
	if ($nrows > 0) {   
   		for ($i = 0; $i < $nrows; $i++) {
         	$pref_id =$results[$i]['PREF_ID'];
			//echo $pref_id;
			//Find value in fragment_value table
			$sql = "SELECT * FROM bluenogice.fragment_pref where PREF_ID=".$pref_id ;
 			$stmt = oci_parse($oracle_link,$sql) or die ("Error in parsing SQL");

  			oci_execute($stmt, OCI_DEFAULT) or die ("Error in exectuing lookup sql!"); 

			$nrows1 = oci_fetch_all($stmt, $results1, null, null,OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);

			//echo "<br>From fragment_pref table:<br>";
  			//echo "$nrows1 rows fetched<br>\n";
 			//var_dump($results1);

			if ($nrows1 > 0) {   
   				for ($i = 0; $i < $nrows1; $i++) {
         			$fragment_id =$results1[$i]['FRAGMENT_ID'];
					//echo $pref_id;
					//Find value in fragement table
					$sql = "SELECT * FROM bluenogice.fragment where FRAGMENT_ID=".$fragment_id." and name='profile::articleViewer'";;

					$stmt = oci_parse($oracle_link,$sql) or die ("Error in parsing SQL");

					oci_execute($stmt, OCI_DEFAULT) or die ("Error in exectuing lookup sql!"); 

					$nrows2 = oci_fetch_all($stmt, $results2, null, null,OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);

					echo "<br>From fragment table:<br>";
					echo "$nrows2 rows fetched<br>\n";
					//var_dump($results2);
					//Find the parent_id
					if ($nrows2 > 0) {   
						for ($i = 0; $i < $nrows2; $i++) {
							$parent_id =$results2[$i]['PARENT_ID'];
							//echo $pref_id;
							//Find value in fragement table
							$sql = "SELECT * FROM bluenogice.fragment where FRAGMENT_ID=".$parent_id;

							$stmt = oci_parse($oracle_link,$sql) or die ("Error in parsing SQL");

							oci_execute($stmt, OCI_DEFAULT) or die ("Error in exectuing lookup sql!"); 

							$nrows3 = oci_fetch_all($stmt, $results3, null, null,OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);

							echo "<br>From fragment table(Parent):<br>";
							echo "$nrows3 rows fetched<br>\n";
							//var_dump($results3);
							//Find value in page table
							if ($nrows3 > 0) {   
								for ($i = 0; $i < $nrows3; $i++) {
									$page_id =$results3[$i]['PAGE_ID'];
									$sql1 = "insert into content_to_page(bluenog_content_id,bluenog_page_id,bluenog_uri_id) values(?,?,?)";

									$stmt2 = $mysql_link->prepare($sql1) or die ("Error preparing statement to insert to content_to_page table");
									$stmt2->bind_param('iii',$content_id,$page_id,$uri_id);
									$stmt2->execute() or die ("Error executing statement to insert to content_to_page table");
									$stmt2->close();
									//echo $pref_id;
									/*
									//Find value in page table
									$sql = "SELECT * FROM bluenogice.page where PAGE_ID=".$page_id;

									$stmt = oci_parse($oracle_link,$sql) or die ("Error in parsing SQL");

									oci_execute($stmt, OCI_DEFAULT) or die ("Error in exectuing lookup sql!"); 

									$nrows4 = oci_fetch_all($stmt, $results4, null, null,OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);

									echo "<br>From Page table:<br>";
									echo "$nrows4 rows fetched<br>\n";
									var_dump($results4);
									echo "<br><br>";
									*/
								}
							} else {
								echo "No data found<br />\n";
							}      
							echo "<br><br>";			
						}
					} else {
						echo "No data found<br />\n";
					}      

					echo "<br><br>";

			
				}
			} else {
				echo "No data found<br />\n";
			}      
			echo "<br><br>";			
   		}
	} else {
   		echo "No data found<br />\n";
	}      

	echo "<br><br>";
	//}
	//$n++;
}
echo "Bluenog page and content binding is done!";
			
/*	

	$sql = "SELECT * FROM bluenogice.fragment where FRAGMENT_ID=71867";
 	$stmt = oci_parse($oracle_link,$sql) or die ("Error in parsing SQL");

  	oci_execute($stmt, OCI_DEFAULT) or die ("Error in exectuing lookup sql!"); 

	$nrows = oci_fetch_all($stmt, $results, null, null,OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);

	echo "From fragment table:<br>";
  	echo "$nrows rows fetched<br>\n";
 	var_dump($results);

	echo "<br><br>";

	$sql = "SELECT * FROM bluenogice.fragment where name like '%featuredContent%'";
 	$stmt = oci_parse($oracle_link,$sql) or die ("Error in parsing SQL");

  	oci_execute($stmt, OCI_DEFAULT) or die ("Error in exectuing lookup sql!"); 

	$nrows = oci_fetch_all($stmt, $results, null, null,OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);

	echo "From fragment table:<br>";
  	echo "$nrows rows fetched<br>\n";
 	var_dump($results);

	echo "<br><br>";

$sql = "SELECT * FROM bluenogice.fragment_pref where fragment_id=55287";
 	$stmt = oci_parse($oracle_link,$sql) or die ("Error in parsing SQL");

  	oci_execute($stmt, OCI_DEFAULT) or die ("Error in exectuing lookup sql!"); 

	$nrows = oci_fetch_all($stmt, $results, null, null,OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);

	echo "From fragment_pref table:<br>";
  	echo "$nrows rows fetched<br>\n";
 	var_dump($results);

if ($nrows > 0) {   
   		for ($i = 0; $i < $nrows; $i++) {
         		$pref_id =$results[$i]['PREF_ID'];
echo "<br>";
			echo $pref_id;
echo "<br>";
			$sql = "SELECT * FROM bluenogice.fragment_pref_value where PREF_ID=".$pref_id ;
 			$stmt = oci_parse($oracle_link,$sql) or die ("Error in parsing SQL");

  			oci_execute($stmt, OCI_DEFAULT) or die ("Error in exectuing lookup sql!"); 

			$nrows1 = oci_fetch_all($stmt, $results1, null, null,OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);

			echo "From fragment_pref_value table:<br>";
  			echo "$nrows1 rows fetched<br>\n";
 			var_dump($results1);

			echo "<br><br>";
   		}
	} else {
   		echo "No data found<br />\n";
	}
	echo "<br><br>";



	$sql = "SELECT * FROM bluenogice.page where PAGE_ID=29963";
 	$stmt = oci_parse($oracle_link,$sql) or die ("Error in parsing SQL");

  	oci_execute($stmt, OCI_DEFAULT) or die ("Error in exectuing lookup sql!"); 

	$nrows = oci_fetch_all($stmt, $results, null, null,OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);

	echo "From page table:<br>";
  	echo "$nrows rows fetched<br>\n";
 	var_dump($results);

	echo "<br><br>";

	$sql = "SELECT * FROM bluenogice.folder where FOLDER_ID=5701";
 	$stmt = oci_parse($oracle_link,$sql) or die ("Error in parsing SQL");

  	oci_execute($stmt, OCI_DEFAULT) or die ("Error in exectuing lookup sql!"); 

	$nrows = oci_fetch_all($stmt, $results, null, null,OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);

	echo "From folder table:<br>";
  	echo "$nrows rows fetched<br>\n";
 	var_dump($results);

	echo "<br><br>";
*/
$stmt1->close();
oci_free_statement($stmt);
}

?>


