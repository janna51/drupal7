<?php

/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct url of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type, i.e., "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode, e.g. 'full', 'teaser'...
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined, e.g. $node->body becomes $body. When needing to access
 * a field's raw values, developers/themers are strongly encouraged to use these
 * variables. Otherwise they will have to explicitly specify the desired field
 * language, e.g. $node->body['en'], thus overriding any language negotiation
 * rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 */
?>
<?php
if ($teaser) {
  // node is being displayed as a teaser
  // Anything here will show up when the teaser of the post is viewed in your taxonomies or front page

 hide($content['field_newsheadline']);
	  hide($content['field_newsdatenew']);
	  hide($content['field_newsfullstory']);
	
	  hide($content['field_newsimage']);
	  hide($content['field_newsshortdesc']);
	    hide($content['links']);
	  if (!$logged_in) {
	    hide($content['links']);
	  }
	  else {
	    hide($content['links']['#links']['node-readmore']);
	  }
	  print "<span style=\"float: left; margin: 5px 10px 10px 0px;\">";
	  print render($content['field_newsimage']);
	  print "</span>";
	  print "<div class=\"listnewsdetails\">";
	  
	  // RR 03/29/2012 As per cpong
	  // If the field_newslink is not empty use it as the link.
	  
	  $target = "";

	  if (!$field_newslink[0]) {
	    $link_to_add = $field_newslink['und'][0]['url'];
	    $parts = split("/",$link_to_add);
	    if (preg_match('/wellesley.edu/i',$parts[2]) == 0) {
	      $target = " target=_wnews ";
	    }
	  }
	  if (trim($link_to_add) == "") {
	    $link_to_add = $node_url;
	    $target = "";
	  }

	print "<h3><a $target href=\"";
	print $link_to_add;
	print "\">";
	print $title;
	print "</a></h3>";
	print render($content);
	print $field_newsshortdesc[0]['value'];
	
	
	
	print "<br></div>";
} else {
//full story
?>


<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

 

  <div class="content"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
	  hide($content['field_newsheadline']);
	  hide($content['field_newsdatenew']);
	  hide($content['field_newsfullstory']);
	   hide($content['field_newsimage']);
	  
	   print render($content);
	  print $field_newsdatenew[0]['value']. "<br>\n";
	  print "<h3>".$field_newsheadline[0]['value']. "</h3>\n";
	  print "<span class=\"ImgRightTopAlign\">";
	  print render($content['field_newsimage']);
	  print "</span>";
	  print $field_newsfullstory[0]['value']. "<br>\n";

     
    ?>
  </div>
  
<?php


/**
* fieldlabels used by news: 
* field_newsheadline
* field_newsdate
* field_newsimage
* field_newsfullstory
* field_newslink
* 
*/
?>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); ?>

</div>
<?php
} 
?>