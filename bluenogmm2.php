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

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Drupal Group ID Input form</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<br>
<br>
<div style="margin-left: 50px;"><br><a href="fixfacility.php"><font size="4">Back to main page</font></a></div>
<table width="450" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="79" align="center" bgcolor="#000000">
	  <div align="center"><font color="#FFFFFF" size="+2"></font>
        <table width="100%" border="0" cellspacing="0" cellpadding="15">
          <tr>
            <td>
				<div align="center">
					<font color="#FFFFFF" size="+2">Wellesley College Website Migration</font>
				</div>
			</td>
          </tr>
        </table>
      </div>
	</td>
  </tr>
  <tr>
    <td bgcolor="#D5DCFF">
	  <div align="center"> 
        <form name="login" method="post" action="bluenogmmfied2.php">       
		<br>
          Please input the Drupal Group ID for the migration:
          <input type="text" size="15" name="groupid">
        <br>
          <input type="submit" value="Submit" name="submit">
        </form>        
      </div>
	</td>
  </tr>
  <tr> 
    <td bgcolor="#000000">&nbsp;</td>
  </tr>
</table>

</body>
</html>

<?php
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

?>
