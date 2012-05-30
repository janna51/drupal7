<?php

/**
 * @file
 * MM Share Widget helper class
 */

module_load_include('inc', 'mm_share_widget', 'mm_share_widget.class');

class mm_share_widget extends share_widget {
  var $module = 'mm_share_widget';

  function visible($node, $teaser, $page = TRUE) {
    $num = db_select('mm_share_widget', 'w')
      ->condition('w.vid', $node->vid)
      ->condition('w.share_id', $this->share_id)
      ->countQuery()->execute()->fetchField();
    $visible = parent::visible($node, $teaser, $page) && $num > 0;
    if ($visible) {
      $settings[] = array(
        'nid' => $node->nid,
        'shareID' => $this->share_id,
        'widget' => $this->render($node, 'content')
      );
      drupal_add_js(array('share' => $settings), 'setting');
    }
    return $visible;
  }
}
