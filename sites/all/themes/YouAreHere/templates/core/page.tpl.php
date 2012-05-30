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
        <div id="topnav">
<img src="/sites/all/themes/YouAreHere/image/all-subnav.jpg" width="1600" alt="Eat Navigation" /></div><!--end topnav-->
    		

      
   

  
	
        
       <?php if ($page['full_width_section']): ?>

        <div id="full_width_section">
        <div class="section">
 
          <?php print render($page['full_width_section']); ?>
        </div> </div>
      <?php endif; ?>


 
  
       <div id="content">
       
           
            
          
	
       
        <?php print render($title_prefix); 



?>
        <?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>
        <?php print render($title_suffix); ?>

        <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>
       	
		<?php print render($page['help']); ?>
        <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
	    <?php print $feed_icons; ?>
    	<?php print render($page['content']); ?>


      </div>

      <?php if ($page['sidebar_first']): ?>

	  <?php 
	      // RR 03/28/2012
	      if (!$logged_in) {
		$my_temp = $page['sidebar_first']['monster_menus_1']['#markup'];	    
		$my_temp = preg_replace('/\s*\(\s*hidden\s*\)/i','',$my_temp);

		$page['sidebar_first']['monster_menus_1']['#markup'] = $my_temp;
	      }
?>
        <div id="leftcolumn"><div class="section">
          <?php print render($page['sidebar_first']); ?>
            
      
      
        </div></div> <!-- /.section, /#sidebar-first -->
      <?php endif; ?>   
      
      
	  
	       
 
    
        
   <!--     <div class="clear"></div>-->
 
 		

        
	
	
 
    
    
    
    <?php if ($messages): ?>
      <div id="console" class="clearfix"><?php print $messages; ?></div>
    <?php endif; ?>

</div><!--end wrapper-->
  
<!--THE HIDE/SHOW FLOATING MENU CODE STARTS -->

   <!--DISPLAY FULL FOOTER MENU-->
	<div id="bottom-menu" style="display:none">
        <center>
<!-- <div class="inner-bottom-menu">-->
        <div class="col">
            <ul>
                <li style="padding:5px 0px">YOU ARE HERE:</li>
                <li><a href="/youarehere/eat">Eat</a></li>
                <li><a href="/youarehere/sleep">Sleep</a></li>
                <li><a href="/youarehere/wander">Wander</a></li>
                <li><a href="/youarehere/like">Like</a></li>
              	<li><a href="/youarehere/learn">Learn</a></li>
          		<li><a href="/youarehere/renew">Renew</a></li>
            </ul>
        </div>
        <div class="col-middle">
            <ul>
            	<li style="padding:5px 0px">LINKS</li>
 	            <li><a href="http://www.wellesley.edu/CWS/">Hours</a></li>
                <li><a href="http://www.wellesley.edu/CampusMaps/">Menu</a></li>
                <li><a href="http://new.wellesley.edu/about/visit">Events</a></li>
             
            </ul>
        </div>
        <div class="col-logo">
        	<a href="/">
        		<img src="/sites/all/themes/YouAreHere/image/footer_logo.png" alt="Wellesley College logo" />  
            </a>      
        </div>
        <div class="col-middle">
           	<ul>
            	<li style="padding-bottom:5px">---</li>
           	</ul>     
        </div> 
         <div class="col-last">
            <ul>
            	<li style="padding-bottom:5px">---</li>
           	</ul> 
            
            <!--THIS DIV BUTTON HIDES/MINIMIZES THE BOTTOM-MENU-->
         <div id="minimize_menu" ><img src="/sites/all/themes/YouAreHere/image/hide.png" alt="Hide" /></div>
         
        </div>
         
                        
       
		
	<!--</div>-->
    </center>
    </div>
	
 
 <!--DISPLAY SMALL MINIMIZED bottom-menu-->
 
 <div id="maximize_menu" style="display:none"><!--IF A USER CLICKS THIS DIV AREA, THE FULL bottom-menu MENU WILL MAXIMIZE/SHOW-->
	<div id="min_bottom_menu"><!--THIS IS THE SMALL MINIMIZED bottom-menu-->
        <center>
        
    
          <!--<div class="inner-bottom-menu">-->
            <div class="col">
               <ul>
                	<li style="padding:5px 0px">YOU ARE HERE:</li>
               </ul>
            </div>
           
            <div class="col">
                <img src="/sites/all/themes/YouAreHere/image/footer_logo.png" alt="Wellesley College logo" width="35px" />        
            </div>
          
            <div class="col-last">
                <ul>
                    <li> 
                        <img src="/sites/all/themes/YouAreHere/image/show.png" alt="Show" />
                    </li>
                </ul>
            </div>
         <!--</div>  -->
 
 
		</center>
	</div><!--END min_bottom_menu-->
 </div> <!--END maximized_menu-->
  

   
   <!--SHOW/HIDE BOTTOM FIXED FLOATING MENU SCRIPT-->

        <!--WHEN THE SHOW BUTTON IS CLICKED, THE BIG bottom-menu SHOWS AND THE SMALL bottom-menu HIDES-->
        <script type="text/javascript">

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
       <script type="text/javascript">

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
  
     	<script type="text/javascript">
			 (function ($){$('#reshallfacts').hide();
			  })(jQuery);
			 (function ($){$('.reshalllives').hide();
			  })(jQuery);
			
			
            (function ($) {$(".thefacts").click(function () {
              $("#reshallfacts").slideToggle("normal");
			});})(jQuery);
              

            (function ($) {$("#reshall").click(function () {
             /* $("#reshallfacts").slideToggle("normal");*/
			  $(".reshalllived").slideToggle("normal");
			   $(".reshalllives").slideToggle("normal");
              
	       // RR - 03/15/2012 add wellesley_menu cookie - turn on
	       Set_Cookie("wellesley_menu","on","",'/','','');             
            });})(jQuery);

            /*(function ($) {$("#minimize_menu").click(function () {
              $("#bottom-menu").fadeToggle("fast","linear");
              $("#min_bottom_menu").slideToggle("normal");
              $("#bottom-menu").hide();
              $("#maximize_menu").show();

	       // RR - 03/15/2012 add wellesley_menu cookie - turn off
	      Set_Cookie("wellesley_menu","off","",'/','','');
            });})(jQuery);*/
        
        </script>

  
        
                
                       
        <!--WHEN THE HIDE BUTTON IS CLICKED, THE BIG bottom-menu HIDES AND THE SMALL bottom-menu SHOWS-->
       <script type="text/javascript">

            (function ($) {$("#maximize_menu").click(function () {
              $("#bottom-menu").slideToggle("normal");
               $("#min_bottom_menu").fadeToggle("fast", "linear");
	       // RR - 03/15/2012 add wellesley_menu cookie - turn on
	       Set_Cookie("wellesley_menu","on","",'/','','');             
            });})(jQuery);

            (function ($) {$("#minimize_menu").click(function () {
              $("#bottom-menu").fadeToggle("fast","linear");
              $("#min_bottom_menu").slideToggle("normal");
              $("#bottom-menu").hide();
              $("#maximize_menu").show();

	       // RR - 03/15/2012 add wellesley_menu cookie - turn off
	      Set_Cookie("wellesley_menu","off","",'/','','');
            });})(jQuery);
        
        </script>

	<!--END OF BOTTOM-MENU SCRIPT-->

    <!--TOGGLE SCRIPT-->
	<script type="text/javascript">
    (function ($){$('.reveal').hide();
     $('.toggle').click(function(){
      var t = $(this);
      t.parent().find('.reveal').toggle("fast");
      
	  
      
     })
    })(jQuery);
    

   <!--END TOGGLE SCRIPT-->
     


<!--TOGGLE SCRIPT WITH IMAGE-->
	
 
  
    (function ($){$('.reveal2').hide();
	
     $('.toggle2').click(function(){
      var t = $(this);
      t.parent().find('.reveal2').toggle("fast", function(){
            // determine which image to use if hidden div is hidden after the toggling is done
            var imgsrc = ($(this).is(':hidden')) ? '/sites/all/themes/YouAreHere/image/downarrow.png' : '/sites/all/themes/YouAreHere/image/uparrow.png';
            // update image src
            t.attr('src', imgsrc );      
        });
      
	  
      
     })
    })(jQuery);
    
	
	
   <!--END TOGGLE SCRIPT-->
         
   
 <!--TOGGLE SCRIPT WITH IMAGE-->

	
 
  
    (function ($){$('.reveal2').hide();
	
     $('.toggle2').click(function(){
      var t = $(this);
      t.parent().find('.reveal2').toggle("fast", function(){
            // determine which image to use if hidden div is hidden after the toggling is done
            var imgsrc = ($(this).is(':hidden')) ? '/sites/all/themes/YouAreHere/image/downarrow.png' : '/sites/all/themes/YouAreHere/image/uparrow.png';
            // update image src
            t.attr('src', imgsrc );      
        });
      
	  
      
     })
    })(jQuery);
    
	
	
   <!--END TOGGLE SCRIPT WITH IMAGE-->
   

  
   <!--TOGGLE SCRIPT WITH DOTS-->
	
    (function ($){$('.reveal3').hide();
     $('.toggle3').click(function(){
      var t = $(this);
      t.parent().find('.reveal3').toggle("fast");
       t.parent().find('.toggle3').hide();
	  
	  
      
     })
    })(jQuery);
    


  (function ($) {$('.toggleclose').click(function () {
      var t = $(this);
      t.parents().find('.reveal3').toggle("fast");
       t.parents('.toggle3').show();
	 
	  
	 
	 
    });})(jQuery);

   <!--END TOGGLE SCRIPT-->     
   
    
<!--DAILY SHOT TOGGLE-->

    (function ($) {$("#show_caption").hide(); 

    $("#min_caption").click(function () {
     
	  $("#show_caption").fadeToggle("fast", "linear");
	  $("#min_caption").hide(); 
	
	  
    });})(jQuery);


     (function ($) {$("#show_caption").click(function () {
     
	   $("#min_caption").fadeToggle("fast", "linear");
	   $("#show_caption").hide();

	 
    });})(jQuery);
</script>
 
