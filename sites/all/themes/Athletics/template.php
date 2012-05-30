<?php

function Athletics_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

    $output .= '<div class="breadcrumb">' . implode(' ', $breadcrumb) . '</div>';
    return $output;
  }
}

function Athletics_active_menu($where) {

	$path = explode('/', request_path());
	
	//0 means root level, check e.g. 'about' matches $where
 	if (isset($path[0]) && $where == $path[0]){
		return ' class="active"';
		
		}else{
			return ' ';
	}	
}