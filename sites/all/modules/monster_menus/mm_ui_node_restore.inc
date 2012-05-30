<?php

/**
 * @file
 * User interface routines for restoring nodes from the recycle bin
 */

/**
 * Confirm that the user wants to restore a node from the recycle bin
 *
 * @param $mmtid
 *   MM tree ID of the page containing the node to restore
 * @param $node
 *   Node to restore
 * @param $return
 *   Optional tree ID of the entry to return the user to when done
 * @return
 *   The HTML code for the results
 */
function mm_ui_node_restore_confirm($form, &$form_state, $mmtid, $node) {
  if (!_mm_ui_recycle_page_list($node, $names, $msg, TRUE)) {
    $form['error'] = array('#markup' => $msg);
    return $form;
  }
  $form['nid'] = array('#type' => 'value', '#value' => $node->nid);
  $form['return'] = array('#type' => 'value', '#value' => mm_content_get_parent($mmtid));

  return confirm_form($form,
      t('Are you sure you want to restore this content?'),
      !empty($_GET['destination']) ? $_GET['destination'] : mm_content_get_mmtid_url($mmtid),
      $msg, t('Restore'), t('Cancel'));
}

/**
 * Execute node restoration
 */
function mm_ui_node_restore_confirm_submit($form, &$form_state) {
  if ($form_state['values']['confirm'] && ($node = node_load($form_state['values']['nid'])) && $node->nid) {
    db_delete('mm_recycle')
      ->condition('type', 'node')
      ->condition('id', $node->nid)
      ->execute();
    db_delete('mm_node2tree')
      ->condition('nid', $node->nid)
      ->execute();
    foreach ($node->recycle_from_mmtids as $mmtid) {
      if ($mmtid) {
        db_insert('mm_node2tree')
          ->fields(array('nid' => $node->nid, 'mmtid' => $mmtid))
          ->execute();
      }
      watchdog('mm', "Restored node=$node->nid to mmtid=$mmtid", array());
    }

    foreach ($node->recycle_bins as $bin) {
      $err = mm_content_delete_bin($bin);
      if (is_string($err)) {
        drupal_set_message(t($err));
        return;
      }
    }

    if ($form_state['values']['return']) {
      mm_redirect_to_mmtid($form_state['values']['return']);
    }
  }
}
