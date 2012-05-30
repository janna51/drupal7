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
				<a href="/about">About</a>
            	<ul>
					<li><a href="/about/missionandvalues">Mission & Values</a></li>
					<li><a href="/about/wellesleyfacts">Wellesley Facts</a></li>
					<li><a href="/about/president">President</a></li>
					<li><a href="/about/campus">The Campus</a></li>
					<li><a href="/about/collegehistory">College History</a></li>
					<li><a href="/about/sightsandsounds">Sights & Sounds of Wellesley</a></li>
					<li><a href="/about/visit">Visit Us</a></li>
				</ul>
			</li>
			<li><a href="/admission">Admission & Financial Aid</a>
            	<ul>
					<li><a href="/admission/onlywellesley">Only Wellesley</a></li>
					<li><a href="/admission/affordable">Wellesley Is Affordable</a></li>
					<li><a href="/admission/finaid">Financial Aid and Costs</a></li>
					<li><a href="/admission/knowus">Get to Know Us</a></li>
					<li><a href="/admission/apply">Apply</a></li>
					<li><a href="/admission/visitus">Visit</a></li>
					<li><a href="/admission/contactus">Contact Us</a></li>
				</ul>
            </li>
			<li><a href="/academics">Academics</a>
				<ul>
					<li><a href="/academics/theacademicprogram">The Academic Program</a></li>
					<li><a href="/academics/deptsmajorprog">Departments, Programs, & Majors</a></li>
					<li><a href="/academics/catalog">Course Catalog</a></li>
					<li><a href="/academics/faculty">Faculty</a></li>
					<li><a href="/academics/centers">Academic Centers</a></li>
					<li><a href="/academics/advising">Academic Advising <br/>& Support</a></li>
					<li><a href="http://www.wellesley.edu/registrar/">Registrar</a></li>
					<li><a href="/academics/convocation">Convocation</a></li>
				</ul>
			</li>        
            <li><a href="/studentlife">Student Life</a>
				<ul>
					<li><a href="/studentlife/aboutus/honor">The Honor Code</a></li>
					<li><a href="/studentlife/reslife">Residential Life</a></li>
					<li><a href="/studentlife/diverse">Our Diverse Community</a></li>
					<li><a href="/studentlife/campus">Campus Life</a></li>
					<li><a href="/studentlife/work">Work, Service & Leadership</a></li>
                    <li><a href="/studentlife/health">Health & Wellness</a></li>
					<li><a href="/studentlife/beyond">Boston & Beyond</a></li>
				</ul>
			</li>
    		<li><a href="/athletics">Athletics</a>
				<ul>
					<li><a href="/athletics/physicaleducation">Physical Education</a></li>
					<li><a href="/athletics/varsitysports">Athletics</a></li>
					<li><a href="/athletics/recreation">Recreation</a></li>
					<li><a href="/athletics/facilitieshours">Facilities & Hours</a></li>
					<li><a href="/athletics/athleticsmission">PERA Mission</a></li>
				</ul>
			</li>
	
			<li>
				<a href="/news">News</a>                
                <ul>
					<li><a href="/news/stories">News Stories</a></li>
					<!--<li><a href="/news/stories/stories2011">News Archives</a></li>-->
					<li><a href="/news/journalists">For Journalists</a></li>
					<li><a href="/news/wps">The Women in Public Service Project</a></li>
			</ul>

			</li>	
    		<li><a href="/events">Events</a>
				<ul>
					<li><a href="/events/artevents">The Arts at Wellesley</a></li>
					<li><a href="http://itunes.wellesley.edu/">Wellesley on iTunes</a></li>
					<li><a href="https://events.wellesley.edu/calendar">Campus Calendar</a></li>
					<li><a href="/events/eventplanning">Event Planning</a></li>
					<li><a href="/events/commencement">Commencement</a></li>
				</ul>
			</li>
	
			<li>
				<a href="/administration">Administration</a>                
                <ul>
					<li><a href="/administration/committees">Committees & Groups</a></li>
					<li><a href="/administration/offices">Offices</a></li>
					<li><a href="/administration/policies">Policies</a></li>
					<li><a href="/administration/working">Working at Wellesley</a></li>
				</ul>

			</li>     

          
        	<li>
				<a href="/alumnae">Alumnae</a>                
                <ul>
					<li><a href="/alumnae/community">Community</a></li>
					<li><a href="/alumnae/groups">Classes, Clubs, & Groups</a></li>
					<li><a href="/alumnae/awards">Awards</a></li>
					<li><a href="/alumnae/lifelonglearning">Life-Long Learning</a></li>
					<li><a href="/alumnae/benefitsandservices">Benefits and Services</a></li>
					<li><a href="/alumnae/wellesleymagazine">Wellesley Magazine</a></li>
					<li><a href="/alumnae/volunteer">Volunteer</a></li>
					<li><a href="/alumnae/about">About the Association</a></li>
					<li><a href="/alumnae/events">Events & Reunion</a></li>
					<li><a href="/alumnae/give">Give</a></li>
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


     
         
              
    
        
        <div class="clear"><!-- --></div>
 
 		
<div id="breadcrumbs"><?php print $breadcrumb ?><?php if (!empty($title)): print "<div class=\"breadcrumb-title\">&nbsp; $title</div>"; endif; ?></div>
           
 	
        <div id="footer-menu-wrap">
        	<ul id="footer-menu">
                     <li>Copyright © Trustees of Wellesley College | </li>
                <li><a href="/administration/working/">Work at Wellesley</a> | </li>  
                <li><a href="/alumnae/give/">Make a Gift</a> | </li>  	
                <li><a href="/info/dmca">Terms of Use</a> | </li>  
                <li><a href="/info/privacy">Privacy</a> | </li>  
                <li><a href="/info/keycontacts">Key Contacts</a> | </li>  	
                <li><a href="/info/access">Web Accessibility</a> | </li>  		
                <li><a href="mailto:webmaster@wellesley.edu">Feedback</a> | </li>	  
                <li><a href="/info/webmaster">Webmaster</a></li>		
	
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
                <li><a href="/admission/apply"/>Apply</a></li>
                <li><a href="/admission/affordable"/>Afford</a></li>
                <li><a href="/academics/deptsmajorprog.psml"/>Majors</a></li>
                <li><a href="/academics/catalog"/>Courses</a></li>
            </ul>
        </div>
        <div class="col-middle">
            <ul>
 	            <li><a href="http://www.wellesley.edu/CWS/"/>Internships</a></li>
                <li><a href="http://www.wellesley.edu/CampusMaps/"/>Map</a></li>
                <li><a href="/about/visit"/>Visit</a></li>
                <li><a href="/alumnae/give"/>Give</a></li>
            </ul>
        </div>
        <div class="col">
        <img src="/sites/all/themes/WellesleyStandard/image/footer_logo.png" alt="Wellesley College logo" />        </div>
        <div class="col-middle">
            <ul>
                <li><a href="http://new.wellesley.edu/lts"/>Library &amp; Technology</a></li>
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
         <div id="minimize_menu" ><img src="/sites/all/themes/NoLeftMenu/image/hide.png" alt="Hide" /></div>
                        
       
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
                <img src="/sites/all/themes/NoLeftMenu/image/footer_logo.png" alt="Wellesley College logo" width="35px" />        
            </div>
          
            <div class="col-last">
                <ul>
                    <li> 
                        <img src="/sites/all/themes/NoLeftMenu/image/show.png" alt="Show" />
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
     
    
    
