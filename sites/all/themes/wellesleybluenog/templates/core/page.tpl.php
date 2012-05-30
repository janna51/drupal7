<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 */
?>
<div id="page-bar"></div>

  <div id="page">

    <div id="bookmark">
    
        <a href="http://web.wellesley.edu/" class="a-img" >
            <img alt="Wellesley College" src="http://web.wellesley.edu/decorations/layout/common-images/w-logo.jpg">
        </a> 

		<div id="bookmark-menu">

    	<?php if ($main_menu || $secondary_menu): ?>
			<div id="main-menu-wrap">
      			<div class="section">
        			<?php print theme('links__system_main_menu', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu') )); ?>
        		</div>
      		</div> <!-- /.section, /#navigation -->
    	<?php endif; ?>      



		
      <?php if ($page['sidebar_first']): ?>
        <div id="sidebar-first" class="column sidebar"><div class="section">
          <?php print render($page['sidebar_first']); ?>
        </div></div> <!-- /.section, /#sidebar-first -->
      <?php endif; ?>
		
      <?php if ($page['left_quicklinks']): ?>
        <div class="module-links">
          <?php print render($page['left_quicklinks']); ?>
        </div> <!-- /.section, /#module-links -->
      <?php endif; ?>
		
        </div>

		<div class="module-addr">
                Wellesley College<br>
                106 Central Street<br>
                Wellesley, MA 02481<br>
                (781) 283-1000
        </div>
            
           
    </div><!-- /#bookmark -->

    <div id="content-wrapper">
    
        
   			<div id="tool-nav-wrap">
				
				<ul id="tool-nav">

                    <li><a href="/">Home</a></li>
                    
                    <li><a href="https://my.wellesley.edu" target="_blank">MyWellesley</a></li>
                    <li><a href="http://web.wellesley.edu/web/Dept/LT">Library &amp; Technology</a></li>
                    <li><a href="http://www.wellesley.edu/Directory/" target="_blank">Directory</a></li>
                    <li><a href="http://www.wellesley.edu/cgi-bin/cal.pl" target="_blank">Calendar</a></li>
                    <li><a href="http://www.wellesley.edu/Registrar/" target="_blank">Registrar</a></li>



                    <li style="margin-left: 10px;"><!--was 20px--><a href="http://search.wellesley.edu/search?entqr=0&access=p&ud=1&sort=date%3AD%3AL%3Ad1&output=xml_no_dtd&site=default_collection&ie=UTF-8&oe=UTF-8&client=default_frontend&proxystylesheet=default_frontend&ip=149.130.166.20&proxycustom=%3CADVANCED/%3E">Advanced Search</a></li>

                    
                    <li class="nav-search-wrap">
                        <form name="gs" method="GET" action="http://search.wellesley.edu/search" target="_blank">
                           <input type="text" name="q" size="20" maxlength="256" value="Search" class="nav-search-field" 
                           onFocus="if(this.value == 'Search') this.value = ''" onBlur="if(this.value == '') this.value = 'Search'">
                           <input type="submit" name="btnG" value="GO" class="nav-search-button">
                           <input type="hidden" name="sort" value="date:D:L:d1">
                           <input type="hidden" name="output" value="xml_no_dtd">
                           <input type="hidden" name="oe" value="UTF-8"><input type="hidden" name="ie" value="UTF-8">
                           <input type="hidden" name="client" value="default_frontend2">
                           <input type="hidden" name="proxystylesheet" value="default_frontend2">

                           <input type="hidden" name="site" value="default_collection">
                        </form> 
                    </li>



										
				</ul>
				
			</div>
           
 
 
 			<div id="top-menu-wrap">
				
				<ul id="top-menu">
					<li><a class="top-menu-about" href="http://web.wellesley.edu/web/AboutWellesley">About Wellesley</a></li>
					<li><a class="top-menu-admission" href="http://web.wellesley.edu/web/Admission">Admission &amp; Financial Aid</a></li>
					<li><a class="top-menu-academics currentmenu" href="http://web.wellesley.edu/web/Academics">Academics</a></li>
					<li><a class="top-menu-studentlife" href="http://web.wellesley.edu/web/StudentLife">Student Life</a></li>
					<li><a class="top-menu-athletics" href="http://web.wellesley.edu/web/Athletics">Athletics</a></li>
					<li><a class="top-menu-news" href="http://web.wellesley.edu/web/News">News</a></li>
					<li><a class="top-menu-events" href="http://web.wellesley.edu/web/Events">Events</a></li>
					<li><a class="top-menu-administration" href="http://web.wellesley.edu/web/Administration">Administration</a></li>
					<li><a class="top-menu-alumnae" href="http://web.wellesley.edu/web/Alumnae">Alumnae</a></li>
				</ul>
				
			</div>
	  

         
    

          
     	
      
      <div id="content">
      <div class="column-wide">
      
   <!--   <div id="main-wrapper"><div id="main" class="clearfix">-->

      <!--<div id="content" class="column"><div class="section">-->
        <?php if ($page['highlighted']): ?><div id="highlighted"><?php print render($page['highlighted']); ?></div><?php endif; ?>
        <a id="main-content"></a>
        <?php print render($title_prefix); ?>
        <?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>
        <?php print render($title_suffix); ?>
        <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>
        <?php print render($page['help']); ?>
        <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
        
        <?php print $feed_icons; ?>
      <!--</div></div>  /.section, /#content -->

        <?php print render($page['content']); ?>


    <!-- </div></div>  /#main, /#main-wrapper -->
    
      
      </div><!-- /column-wide -->

      <?php if ($page['sidebar_second']): ?>
        <div class="column-sidebar">
          <?php print render($page['sidebar_second']); ?>
        </div> <!-- /.section, /#column-sidebar -->
      <?php endif; ?>

      
      </div><!-- /#content -->
      
      
    <div id="footer">
    
      
 
  <?php print render($page['footer']); ?>

     
      Copyright &copy; Trustees of Wellesley College<br />   
        <a href="https://career.wellesley.edu/" target="_blank">Work at Wellesley</a>
        <a href="http://web.wellesley.edu/web/Alumnae/Give/gift.psml" target="_blank">Make a Gift</a>
        <a href="http://web.wellesley.edu/web/Dept/LT/About/Policies/dmca.psml">Terms of Use</a>
    
        <a href="http://web.wellesley.edu/web/Info/privacy.psml">Privacy</a>
        <a href="http://www.wellesley.edu/Directory/KeyContacts/contactsmain.html" target="_blank">Key Contacts</a>
        <a href="http://www.wellesley.edu/Library/Digitech/Handbook/access.html" target="_blank">Web Accessibility</a>
        <a href="http://www.wellesley.edu/CwisIndex/cwisindex.html" target="_blank">Site Index</a>
        <a href="mailto:CMS-Feedback@wellesley.edu">Feedback</a>
		<a href="user/">Webmaster</a>


    </div> <!-- /#footer -->

                
    </div><!-- /#content wrapper -->
    



    <div class="clear"></div>
   
    <?php if ($messages): ?>
      <div id="console" class="clearfix"><?php print $messages; ?></div>
    <?php endif; ?>



  </div> <!-- /#page -->
