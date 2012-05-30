<?php

/**
 * @file
 * Views interface for Monster Menus
 */

// Modified version of workflow_views_handler_field_sid, without the workflow
// name at the beginning of the output
class mm_views_handler_field_sid extends views_handler_field {
  function render($values) {
    if (empty($values->{$this->field_alias})) {
      return t('N/A');
    }

    $states = &drupal_static(__FUNCTION__);
    if (!isset($states)) {
      $result = db_select('workflow_states', 's')
        ->fields('s', array('sid', 'state'))
        ->condition('s.status', 1)
        ->orderBy('s.sid')
        ->execute();
      foreach ($result as $data) {
        $states[$data->sid] = $data->state;
      }
    }
    $output = $states[$values->{$this->field_alias}];
    if (empty($output)) {
      $output = t('(unknown)');
    }
    return check_plain($output);
  }
}
