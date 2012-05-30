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


<style>
.profile-portrait {
	display: block;
    float: right;
    margin-left: 12px;
    position: relative;
	width: 300px;
 }
 
 
.profile-name{
	font-family: Georgia, "Times New Roman", Times, serif;
	color: #002776;
	font-weight: normal;
	font-size: 22px;
	line-height: 24px;
 }

.profile-position-title {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-weight: normal;
	font-size: 16px;;
	line-height: 20px;
 }
 
 .profile-content {
    float: left;
    width: 612px !important;
}


</style>


<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php print $user_picture; ?>

  <?php print render($title_prefix); ?>
  <?php if (!$page): ?>
  
  
  <?php if (!empty($title)): ?>
    <h2<?php print $title_attributes; ?>><?php print $title; ?></h2>
  <?php endif; ?>
  <?php endif; ?>

  <?php print render($title_suffix); ?>

  <?php if ($display_submitted): ?>
    <div class="submitted">
      <?php print $submitted; ?>
    </div>
  <?php endif; ?>

  <div class="content"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
		  hide($content['field_profilefirst']);
		  hide($content['field_profilelast']);
		  
		  hide($content['field_profilepostitle']);
		  hide($content['field_profileheadline2']);
		  
hide($content['field_profilecv']);
                  hide($content['field_profileemail']);
		  hide($content['field_profilephone']);
		  hide($content['field_profiledept']);
		  hide($content['field_profileprograms']);
hide($content['field_profileofficehrs']);
		  hide($content['field_profileofficeloc']);
      


 
	print "<div class=\"profile-portrait\">";
		
		  print render($content['field_profileportrait']);

// RR 03/28/2012 Show CV only if not empty

if ($field_profilecv[0] != NULL) {
  $cv_url = $field_profilecv[0]['uri'];
  $cv_url = preg_replace('/public:\/\//','http://new.wellesley.edu/sites/default/files/',$cv_url);
		  print 
		    "<a href=\"$cv_url" 
		    . "\">\n"
		    ."Curriculum Vitae" . "</a><br><br>\n";
 }
                  print "<a href=\"mailto:" .
		  $field_profileemail[0]['value'] . "\">\n";
		  print $field_profileemail[0]['value']. "</a><br><br>\n";
		  print $field_profilephone[0]['value']. "<br>\n";
		  print $field_profiledept[0]['value']. "<br>\n";
		  print $field_profileprograms[0]['value']. "<br>\n";
		  print $field_profileofficehrs[0]['value']. "\n";
		  print $field_profileofficeloc[0]['value']. "\n";
		 

      print "<br><hr><br></div>";

	    print "<span class=\"profile-name\">".$field_profilefirst[0]['value'] . " " . $field_profilelast[0]['value'] . "</span><br>\n";
		print "<span class=\"profile-position-title\">" . $field_profilepostitle[0]['value'] . "</span><br><br>\n";
	
	

      print render($content);      
      
      
    ?>
  </div>
  <br/>

  <?php print render($content['links']); ?>


?>
</div>
