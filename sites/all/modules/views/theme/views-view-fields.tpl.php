<?php
/**
 * @file views-view-fields.tpl.php
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */


?>

<?php 
  // RR 04/03/2012 Since there is no easy way to reference a named field,
  // see if newslink exists, if so, save the content as replace link.

$replace_link = "";
foreach ($fields as $id => $field) {
  
  if ($id == "field_newslink") {
    $replace_link = $field->content;
  }
}
?>

<?php foreach ($fields as $id => $field): ?>
<?php 

  //  RR 04/03/2012 Skip printing the link
  if ($id == "field_newslink") {
    continue;
  }

//  RR 04/03/2012 If the replace_link is not null and the field is title,
// field_newsimage or field_eventimage
// then replace the url.

 if (trim($replace_link) != "") {
   if ( (strtolower($id) == "title") ||
	(strtolower($id) == "field_newsimage") ||
	(strtolower($id) == "field_eventimage")
	)
     {
	
     $temp_content = $field->content;
     $regexp = "<a\s[^>]*href=(\"??)([^\">]*?)\\1[^>\s]*>(.*)<\/a>";
     // RR 04/03/2012 Extract the URL from the current field and see if it exists
     if (preg_match("/$regexp/siU", $temp_content, $matches)) {
       $match_re = preg_replace("/\//","\/",$matches[2]);

       // RR 04/03/2012 - Save it for replacement.

       $src_string = array("/$match_re/");

       // RR 04/03/2012 - Extract the URL from replace_link and 
       // if it exists, replace the string.
       if (preg_match("/$regexp/siU", $replace_link, $matches)) {
	 $target_string = array($matches[2]);
	 $field->content = preg_replace($src_string,$target_string,$temp_content);
       }
     }
   }
 }
?>
  <?php if (!empty($field->separator)): ?>
    <?php print $field->separator; ?>
  <?php endif; ?>

  <?php
    /*  RR - 03/20/2012 Commented this out.
    <?php print $field->content; ?>
    

     RR 03/20/2012 Take the content and pick up all the 
     Links through regular expression match.
    */
  ?>

  <?php $temp_content = $field->content;
  $regexp = "<a\s[^>]*href=(\"??)([^\">]*?)\\1[^>\s]*>(.*)<\/a>";
  if(preg_match_all("/$regexp/siU", $temp_content, $matches)) {
    // $matches[2] = array of link addresses
    // $matches[3] = array of link text - including HTML code
    // We will collect source URLs and Target URLs in these arrays
    $src_string = array();
    $target_string = array();
    
    // Loop through each url matched.

    foreach ($matches[2] as $match) {
      // If the url has /facstaff/ or /faculty/ in it,
      // it is deemed a URL for change.
      if ( (preg_match("/\/facstaff\//",$match) > 0) ||
	   (preg_match("/\/fac(.*)staff\//",$match) > 0) ||
	   (preg_match("/\/faculty\//",$match) > 0)
	   )
	{
	  $match1 = preg_replace('/\/node\/[0-9\s]*/','',$match);
	  $match_re = preg_replace("/\//","\/",$match);
	  array_push($src_string,"/$match_re/");
	  array_push($target_string,"$match1");
	}
    }
    $temp_content = preg_replace($src_string,$target_string,$temp_content);
  }

?>
  <?php print $field->wrapper_prefix; ?>
    <?php 
print $field->label_html; ?>

    <?php print $temp_content; ?>
  <?php print $field->wrapper_suffix; ?>
<?php endforeach; ?>
