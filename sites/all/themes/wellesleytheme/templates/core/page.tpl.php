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


   <div id="wrapper">

        <div id="banner"> 
            <a class="mywellesley sprite-img" title="MyWellesley" href="https://my.wellesley.edu">MyWellesley</a>
            <a class="logo sprite-img" title="Wellesley College" href="/">Wellesley College</a>
  
            <li class="nav-search-wrap">
       <form name="gs" method="GET" action="http://search.wellesley.edu/search" target="_blank">
                   <input type="text" name="q" size="20" maxlength="256" value="Search" class="nav-search-field" 
                   onFocus="if(this.value == 'Search') this.value = ''" onBlur="if(this.value == '') this.value = 'Search'">
                 <!--  <input type="submit" name="btnG" value="GO" class="nav-search-button">-->
                 <input type="submit" name="btnG" value=" " class="nav-search-button">
					<input type="hidden" name="sort" value="date:D:L:d1">
                   <input type="hidden" name="output" value="xml_no_dtd">
                   <input type="hidden" name="oe" value="UTF-8">
                   <input type="hidden" name="ie" value="UTF-8">
                   <input type="hidden" name="client" value="default_frontend2">
                   <input type="hidden" name="proxystylesheet" value="default_frontend2">
                   <input type="hidden" name="site" value="default_collection">
                </form> 
            </li>

            
        </div>
	  <center>	
      


      
  <div id="global-navs">                
		<ul class="sf-menu">
			<li>
				<a title="About Wellesley College" href="http://web.wellesley.edu/web/AboutWellesley">About</a>
            	<ul>
					<li><a href="http://web.wellesley.edu/web/AboutWellesley/MissionandValues">Mission & Values</a></li>
					<li><a href="http://web.wellesley.edu/web/AboutWellesley/wellesleyfacts.psml">Wellesley Facts</a></li>
					<li><a href="http://web.wellesley.edu/web/AboutWellesley/OfficeofthePresident">President</a></li>
					<li><a href="http://web.wellesley.edu/web/AboutWellesley/TheCampus">The Campus</a></li>
					<li><a href="http://web.wellesley.edu/web/AboutWellesley/CollegeHistory">College History</a></li>
					<li><a href="http://web.wellesley.edu/web/AboutWellesley/SightsAndSounds">Sights & Sounds of Wellesley</a></li>
					<li><a href="http://web.wellesley.edu/web/AboutWellesley/VisitUs">Visit Us</a></li>
				</ul>
			</li>
			<li><a title="Admission & Financial Aid" href="http://web.wellesley.edu/web/Admission">Admission & Financial Aid</a>
            	<ul>
					<li><a href="http://web.wellesley.edu/web/Admission/OnlyWellesley">Only Wellesley</a></li>
					<li><a href="http://web.wellesley.edu/web/Admission/WellesleyIsAffordable">Wellesley Is Affordable</a></li>
					<li><a href="http://web.wellesley.edu/web/Dept/SFS">Financial Aid and Costs</a></li>
					<li><a href="http://web.wellesley.edu/web/Admission/GetToKnowUs">Get to Know Us</a></li>
					<li><a href="http://web.wellesley.edu/web/Admission/Apply">Apply</a></li>
					<li><a href="http://web.wellesley.edu/web/Admission/visit">Visit</a></li>
					<li><a href="http://web.wellesley.edu/web/Admission/contactus.psml">Contact Us</a></li>
				</ul>
            </li>
            <!--<a title="Academics" href="http://web.wellesley.edu/web/Academics" class="active">Academics</a>-->
			<li><a title="Academics" href="http://web.wellesley.edu/web/Academics">Academics</a>
				<ul>
					<li><a href="http://web.wellesley.edu/web/Academics/TheAcademicProgram">The Academic Program</a></li>
					<li><a href="http://web.wellesley.edu/web/Academics/deptsmajorprog.psml">Departments, Programs, & Majors</a></li>
					<li><a href="http://web.wellesley.edu/web/Academics/catalog.psml">Course Catalog</a></li>
					<li><a href="http://web.wellesley.edu/web/Academics/Faculty">Faculty</a></li>
					<li><a href="http://web.wellesley.edu/web/Academics/centers.psml">Academic Centers</a></li>
					<li><a href="http://web.wellesley.edu/web/Academics/AcademicAdvisingAndSupport">Academic Advising <br/>& Support</a></li>
					<li><a href="http://www.wellesley.edu/Registrar/">Registrar</a></li>
					<li><a href="http://web.wellesley.edu/web/Academics/convocation.psml">Convocation</a></li>
				</ul>
			</li>        
            <li><a title="Student Life" href="http://web.wellesley.edu/web/StudentLife">Student Life</a>
				<ul>
					<li><a href="http://web.wellesley.edu/web/StudentLife/TheHonorCode">The Honor Code</a></li>
					<li><a href="http://web.wellesley.edu/web/StudentLife/ResidenceLife">Residential Life</a></li>
					<li><a href="http://web.wellesley.edu/web/StudentLife/OurDiverseCommunity">Our Diverse Community</a></li>
					<li><a href="http://web.wellesley.edu/web/StudentLife/CampusLife">Campus Life</a></li>
					<li><a href="http://web.wellesley.edu/web/StudentLife/CampusLife/work.psml">Work, Service & Leadership</a></li>
                    <li><a href="http://web.wellesley.edu/web/StudentLife/HealthandWellness">Health & Wellness</a></li>
					<li><a href="http://web.wellesley.edu/web/StudentLife/BostonAndBeyond">Boston & Beyond</a></li>
				</ul>
			</li>
    		<li><a title="Athletics" href="http://web.wellesley.edu/web/Athletics">Athletics</a>
				<ul>
					<li><a href="http://web.wellesley.edu/web/Athletics/PhysicalEducation">Physical Education</a></li>
					<li><a href="http://www.wellesleyblue.com/">Athletics</a></li>
					<li><a href="http://web.wellesley.edu/web/Athletics/Recreation">Recreation</a></li>
					<li><a href="http://web.wellesley.edu/web/Athletics/facilitieshours.psml">Facilities & Hours</a></li>
					<li><a href="http://web.wellesley.edu/web/Athletics/mission.psml">PERA Mission</a></li>
				</ul>
			</li>
	
			<li>
				<a title="News" href="http://web.wellesley.edu/web/News">News</a>                
                <ul>
					<li><a href="http://web.wellesley.edu/web/News/media.psml">Online Newsroom</a></li>
					<li><a href="http://web.wellesley.edu/web/News/releases.psml">News Releases</a></li>
					<li><a href="http://web.wellesley.edu/web/AboutWellesley/OfficeofthePresident">Office of the President</a></li>
					<li><a href="http://web.wellesley.edu/web/News/newsarchive">News Archives</a></li>
			</ul>

			</li>	
    		<li><a title="Events" href="http://web.wellesley.edu/web/Events">Events</a>
				<ul>
					<li><a href="http://web.wellesley.edu/web/Events/artevents.psml">The Arts at Wellesley</a></li>
					<li><a href="http://itunes.wellesley.edu/">Wellesley on iTunes</a></li>
					<li><a href="https://events.wellesley.edu/eventscal.php">Campus Calendar</a></li>
					<li><a href="http://web.wellesley.edu/web/Events/EventPlanning">Event Planning</a></li>
				</ul>
			</li>
	
			<li>
				<a title="Administration" href="http://web.wellesley.edu/web/Administration">Administration</a>                
                <ul>
					<li><a href="http://web.wellesley.edu/web/Administration/commgroups.psml">Committees & Groups</a></li>
					<li><a href="http://web.wellesley.edu/web/Administration/offices.psml">Offices</a></li>
					<li><a href="http://web.wellesley.edu/web/Administration/policies.psml">Policies</a></li>
					<li><a href="http://web.wellesley.edu/web/Administration/working.psml">Working at Wellesley</a></li>
				</ul>

			</li>     

          
        	<li>
				<a title="Alumnae" href="http://web.wellesley.edu/web/Alumnae">Alumnae</a>                
                <ul>
					<li><a href="http://web.wellesley.edu/web/Alumnae/community.psml">Community</a></li>
					<li><a href="http://web.wellesley.edu/web/Alumnae/Groups">Classes, Clubs, & Groups</a></li>
					<li><a href="http://web.wellesley.edu/web/Alumnae/Awards">Awards</a></li>
					<li><a href="http://web.wellesley.edu/web/Alumnae/LifeLongLearning">Life-Long Learning</a></li>
					<li><a href="http://web.wellesley.edu/web/Alumnae/BenefitsandServices">Benefits and Services</a></li>
					<li><a href="http://web.wellesley.edu/web/Alumnae/WellesleyMagazine">Wellesley Magazine</a></li>
					<li><a href="http://web.wellesley.edu/web/Alumnae/Volunteer">Volunteer</a></li>
					<li><a href="http://web.wellesley.edu/web/Alumnae/About">About the Association</a></li>
					<li><a href="http://web.wellesley.edu/web/Alumnae/Events">Events & Reunion</a></li>
					<li><a href="http://web.wellesley.edu/web/Alumnae/Give">Give</a></li>
				</ul>

			</li>            
         
            
		</ul>
               
              
        	     
      </div>
      </center>
        
       <?php if ($page['full_width_section']): ?>

        <div id="full_width_section">
        <div class="section">
 
          <?php print render($page['full_width_section']); ?>
        </div> </div>
      <?php endif; ?>



      <?php if ($page['sidebar_first']): ?>
        <div id="leftcolumn"><div class="section">
         <!-- <h2>Academics</h2>-->
          <?php print render($page['sidebar_first']); ?>
        </div></div> <!-- /.section, /#sidebar-first -->
      <?php endif; ?>       
 
  
      <?php if ($page['highlighted']): ?><div id="highlighted"><?php print render($page['highlighted']); ?></div><?php endif; ?>
      <div id="maincontent">
        <?php print render($title_prefix); ?>
        <?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>
        <?php print render($title_suffix); ?>

        <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>
       	
		<?php print render($page['help']); ?>
        <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
	    <?php print $feed_icons; ?>
    	<?php print render($page['content']); ?>


      </div>


     <?php if ($page['sidebar_second']): ?>
        <div id="rightcolumn"><div class="section">
          <?php print render($page['sidebar_second']); ?>
        </div></div> <!-- /.section, /#sidebar-first -->
      <?php endif; ?>       
         
              
    
        
        <div class="clear"><!-- --></div>
 
 		
<div id="breadcrumbs"><?php print $breadcrumb ?><?php if (!empty($title)): print "<div class=\"breadcrumb-title\">&nbsp; $title</div>"; endif; ?></div>
           
 	
        <div id="footer-menu-wrap">
        	<ul id="footer-menu">
                <li>Copyright © Trustees of Wellesley College</li> | 
                <li><a href="https://career.wellesley.edu/applicants/jsp/shared/frameset/Frameset.jsp?time=1250171671124">Work at Wellesley</a></li>  | 
                <li><a href="http://web.wellesley.edu/web/Alumnae/Give/gift.psml">Make a Gift</a> | 	
                <li><a href="http://web.wellesley.edu/web/Dept/LT/About/Policies/dmca.psml">Terms of Use</a></li>	 | 
                <li><a href="http://web.wellesley.edu/web/Info/privacy.psml">Privacy</a></li>	 | 
                <li><a href="http://www.wellesley.edu/Directory/KeyContacts/contactsmain.html">Key Contacts</a></li>	 | 	
                <li><a href="http://www.wellesley.edu/Library/Digitech/Handbook/access.html">Web Accessibility</a></li> | 		
                <li><a href="http://www.wellesley.edu/CwisIndex/cwisindex.html">Site Index</a></li>		 | 
                <li><a href="mailto:CMS-Feedback@wellesley.edu">Feedback</a></li>	 | 
                <li><a href="user/">Webmaster</a></li>		
           	</ul>
			
             <div class="footer-address">
			Wellesley College	106 Central Street – Wellesley, MA 02481	(781) 283-1000
            </div>
        </div>
  <?php if ($page['footer']): ?>

  <?php print render($page['footer']); ?>        
        
  <?php endif; ?>
        
	
	
 
    
    
    
    <?php if ($messages): ?>
      <div id="console" class="clearfix"><?php print $messages; ?></div>
    <?php endif; ?>


  
<!--THE HIDE/SHOW FLOATING MENU CODE STARTS -->

   <!--DISPLAY FULL FOOTER MENU-->
	<div id="bottom-menu" style="display:none">
        <center>
      <div class="inner-bottom-menu">
        <div class="col">
            <ul>
                <center><li>SHORTCUTS</li></center>
                <li><a href="http://web.wellesley.edu/web/Admission/Apply"/>Apply</a></li>
                <li><a href="http://web.wellesley.edu/web/Admission/WellesleyIsAffordable"/>Afford</a></li>
                <li><a href="http://web.wellesley.edu/web/Academics/deptsmajorprog.psml"/>Majors</a></li>
                <li><a href="http://web.wellesley.edu/web/Academics/catalog.psml"/>Courses</a></li>
            </ul>
        </div>
        <div class="col-middle">
            <ul>
 	            <li><a href="http://www.wellesley.edu/CWS/"/>Internships</a></li>
                <li><a href="http://www.wellesley.edu/CampusMaps/"/>Map</a></li>
                <li><a href="http://web.wellesley.edu/web/AboutWellesley/VisitUs"/>Visit</a></li>
                <li><a href="http://web.wellesley.edu/web/Alumnae/Give/WaystoGive"/>Give</a></li>
            </ul>
        </div>
        <div class="col">
        <img src="http://new.wellesley.edu/sites/all/themes/wellesleytheme/image/footer_logo.png" alt="Wellesley College logo" />        </div>
        <div class="col-middle">
            <ul>
                <li><a href="http://web.wellesley.edu/web/Dept/LT"/>Library &amp; Technology</a></li>
                <li><a href="http://www.wellesley.edu/Directory/"/>Directory</a></li>
                <li><a href="http://www.wellesley.edu/cgi-bin/cal.pl"/>Calendars</a></li>
                <li><a href="http://www.wellesley.edu/Registrar/">Registrar</a></li>
            </ul>
        </div> 
         <div class="col-last">
           <ul>
           		<br/>
                <li><a href="http://www.wellesley.edu/albright/index2.html"/>Albright Institute</a></li>
                <li><a href="http://www.davismuseum.wellesley.edu/"/>Davis Museum</a></li>
                <li><a href="http://www.newhouse-center.org/"/>Newhouse Center</a></li>
                <li><a href="http://www.wcwonline.org/"/>Centers for Women</a></li>
           </ul>
                       


        </div>
         
         <!--THIS DIV BUTTON HIDES/MINIMIZES THE BOTTOM-MENU-->
         <div id="minimize_menu" ><img src="http://new.wellesley.edu/sites/all/themes/wellesleytheme/image/hide.png" alt="Hide" /></div>
                        
       
		</center>
	</div>
	
 
 <!--DISPLAY SMALL MINIMIZED bottom-menu-->
 
 <div id="maximize_menu" style="display:none"><!--IF A USER CLICKS THIS DIV AREA, THE FULL bottom-menu MENU WILL MAXIMIZE/SHOW-->
	<div id="min_bottom_menu"><!--THIS IS THE SMALL MINIMIZED bottom-menu-->
        <center>
        
    
          <div class="inner-bottom-menu">
            <div class="col">
               <ul>
                    <center><li>SHORTCUTS</li></center>
               </ul>
            </div>
           
            <div class="col">
                <img src="http://new.wellesley.edu/sites/all/themes/wellesleytheme/image/footer_logo.png" alt="Wellesley College logo" width="35px" />        
            </div>
          
            <div class="col-last">
                <ul>
                    <li> 
                        <img src="http://new.wellesley.edu/sites/all/themes/wellesleytheme/image/show.png" alt="Show" />
                    </li>
                </ul>
            </div>
         </div>  
 
 
		</center>
	</div><!--END min_bottom_menu-->
 </div> <!--END maximized_menu-->
   
   
   <!--SHOW/HIDE BOTTOM FIXED FLOATING MENU SCRIPT-->

        <!--WHEN THE SHOW BUTTON IS CLICKED, THE BIG bottom-menu SHOWS AND THE SMALL bottom-menu HIDES-->
        <script>

									    // R// RR - 03/15/2012 Added Get_Cookie and Set_Cookie functions

// this function gets the cookie, if it exists
// don't use this, it's weak and does not handle some cases
// correctly, this is just to maintain legacy information

function Get_Cookie( name ) {
  
  var start = document.cookie.indexOf( name + "=" );
  var len = start + name.length + 1;
  if ( ( !start ) &&
       ( name != document.cookie.substring( 0, name.length ) ) )
    {
      return null;
    }
  if ( start == -1 ) return null;
  var end = document.cookie.indexOf( ";", len );
  if ( end == -1 ) end = document.cookie.length;
  return unescape( document.cookie.substring( len, end ) );
}
	

function Set_Cookie( name, value, expires, path, domain, secure )
{
  // set time, it's in milliseconds
  var today = new Date();
  today.setTime( today.getTime() );
  
  /*
   if the expires variable is set, make the correct
   expires time, the current script below will set
   it for x number of days, to make it for hours,
   delete * 24, for minutes, delete * 60 * 24
  */
  if ( expires )
    {
      expires = expires * 1000 * 60 * 60 * 24;
    }
  var expires_date = new Date( today.getTime() + (expires) );
  
  document.cookie = name + "=" +escape( value ) +
    ( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
    ( ( path ) ? ";path=" + path : "" ) +
    ( ( domain ) ? ";domain=" + domain : "" ) +
    ( ( secure ) ? ";secure" : "" );
}
        
        </script>

        <!--ON PAGE LOAD, THIS SCRIPT MAKES SURE THE SMALL bottom-menu IS MINIMIZED-->
        <script>

	  // RR - 03/15/2012 On load check if the cookie for welelsley_menu is off
	var cookie_value = Get_Cookie('wellesley_menu');

// If off, then hide the bottom-menu

if (cookie_value == 'off') {
  var div = document.getElementById('bottom-menu');
  div.style.display = 'none';
  var div = document.getElementById('maximize_menu');
  div.style.display = 'block';
 }
 else {
    var div = document.getElementById('maximize_menu');
    div.style.display = 'none';
    var div = document.getElementById('bottom-menu');
    div.style.display = 'block';   
 }

		  
        </script>
        
                
                       
        <!--WHEN THE HIDE BUTTON IS CLICKED, THE BIG bottom-menu HIDES AND THE SMALL bottom-menu SHOWS-->
        <script>

            (function ($) {$("#maximize_menu").click(function () {
              $("#bottom-menu").slideToggle("normal");
               $("#min_bottom_menu").fadeToggle("fast", "linear");
	       // RR - 03/15/2012 add wellesley_menu cookie - turn on
	       Set_Cookie("wellesley_menu","on",30,'/','','');             
            });})(jQuery);

            (function ($) {$("#minimize_menu").click(function () {
              $("#bottom-menu").fadeToggle("fast","linear");
              $("#min_bottom_menu").slideToggle("normal");
              $("#bottom-menu").hide();
              $("#maximize_menu").show();

	       // RR - 03/15/2012 add wellesley_menu cookie - turn off
	      Set_Cookie("wellesley_menu","off",30,'/','','');
            });})(jQuery);
        
        </script>

	<!--END OF BOTTOM-MENU SCRIPT-->

    <!--TOGGLE SCRIPT-->
	<script>
    (function ($){$('.reveal').hide();
     $('.toggle').click(function(){
      var t = $(this);
      t.parent().find('.reveal').toggle("fast");
      
      
     })
    })(jQuery);
    
</script>
   <!--END TOGGLE SCRIPT-->
     
    
    
