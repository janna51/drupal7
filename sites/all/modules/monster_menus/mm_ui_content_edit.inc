<?php

/**
 * @file
 * User interface routines for copying/moving MM content
 */

function mm_ui_content_edit($form, &$form_state, $item, $mmtid, $is_group, $is_new, $is_search = FALSE) {
  global $user;

  $x = mm_ui_strings($is_group);
  $all_menus = user_access('administer all menus');
  $ilist = drupal_map_assoc(array(1, 2, 3, 4, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 75, 100));

  $form['path'] = array(
    '#type' => 'value',
    '#value' => $mmtid
  );

  $owner = isset($form_state['post']['owner']) ? $form_state['post']['owner'] : (isset($item->uid) ? $item->uid : '');

  if (!$is_new && user_access('see create/modify times')) {
    // this code correctly handles legacy tree nodes without creation dates/users
    if (!empty($item->ctime)) {
      $x['@ctime'] = format_date($item->ctime, 'medium');
      $x['!cuser'] = mm_ui_uid2name($item->cuid, TRUE);
    }

    if (!empty($item->mtime)) {
      $x['@mtime'] = format_date($item->mtime, 'medium');
      $x['!muser'] = mm_ui_uid2name($item->muid, TRUE);
    }

    if (isset($x['@ctime'])) $msg = t('This @thing was created by !cuser on @ctime.', $x);

    if (isset($x['@mtime']) && (!isset($x['@ctime']) || $x['@mtime'] != $x['@ctime']))
      if (!empty($msg)) $msg .= ' ' . t('It was last modified by !muser on @mtime.', $x);
      else $msg = t('This @thing was last modified by !muser on @mtime.', $x);

    if (isset($msg) && $msg) {
      $form['moddate'] = array(
        '#type' => 'markup',
        '#markup' => $msg
      );
    }
  }

  if ($is_group) {
    $form['is_group'] = array(
      '#type' => 'value',
      '#value' => TRUE
    );
  }

  if ($is_new) {
    $form['is_new'] = array(
      '#type' => 'value',
      '#value' => TRUE
    );
  }
  else {
    $form['weight'] = array(
      '#type' => 'value',
      '#value' => $item->weight
    );
  }

  $flags_not_admin = _mm_ui_content_flags_not_admin($item, $all_menus);

  _mm_ui_form_array_merge($form, 'settings_perms', array(
    '#type' => 'fieldset',
    '#title' => t('Permissions'),
    '#collapsible' => TRUE, '#collapsed' => FALSE,
  ));

  if ($flags_not_admin['limit_write']) {
    $form['settings_perms']['message'] = array('#type' => 'item', '#markup' => t('<p>You are not allowed to modify the first column of the permissions.</p>'));
  }

  if (_mm_menu_access_solver($mmtid)) {
    module_load_include('inc', 'monster_menus', 'mm_ui_solver');
    mm_static('solver_mm_list_callback', TRUE, $mmtid);
    $dummy = array();
    $form['settings_perms']['solver_form'] = mm_ui_solver_form(array(), $dummy);
    $form['settings_perms']['solver_form']['#prefix'] = '<div style="display: none" id="solver-form">';
    $form['settings_perms']['solver_form']['#suffix'] = '<div id="mm-solver-table"></div></div>';
    $title = t('Solve permissions issues');
    $form['settings_perms']['mm-solver-link'] = array(
      '#type' => 'link',
      '#weight' => -1,
      '#prefix' => '<div id="mm-solver-link">',
      '#suffix' => '</div>',
      '#title' => $title,
      '#href' => "mm/$mmtid/settings/solver",
      '#options' => array(
        'fragment' => 'TB_inline?height=400&width=500&inlineId=solver-form',
        'attributes' => array(
          'class' => array('thickbox'),
          'title' => $title,
        ),
      ),
    );
  }

  $types = array(
    MM_PERMS_WRITE => isset($item->flags['limit_write']) ?
      array(
        t('Change @thing settings', $x),
        'If checked, !class can change this !thingpos settings.',
      ) :
      array(
        t('Delete/&#8203;change settings', $x),
        'If checked, !class can delete this @thing or change its settings.',
      ),
    MM_PERMS_SUB => array(
      t('Append @subthings', $x),
      'If checked, !class can append @subthings to this @thing.',
    ),
    MM_PERMS_APPLY => array(
      t('Add content', $x),
      'If checked, !class can add content to this @thing.',
    ),
    MM_PERMS_READ => $is_group ?
      array(
        t('See group members'),
        'If checked, !class can see the members of this group.',
      ) :
      array(
        t('Read', $x),
        'If checked, !class can read this @thing.',
      )
  );
  if ($is_group) unset($types[MM_PERMS_APPLY]);

  $default_modes = array();
  if (!isset($form_state['post']['group_r_everyone']) && isset($item->default_mode)) {
    $default_modes = explode(',', $item->default_mode);
  }

  $users = $groups = array();

  if (!$is_search) {
    // individual users
    if (isset($form_state['post']['all_values_user'])) {
      $form_state['post']['path'] = $mmtid;
      list($groups, $users, $dummy) = _mm_ui_form_parse_perms($form_state['post'], FALSE);
    }
    else {
      $gids = array();
      $select = db_select('mm_tree', 't');
      $select->join('mm_tree_access', 'a', 'a.mmtid = t.mmtid');
      $select->fields('a', array('gid'))
        ->condition('a.gid', 0, '<')
        ->condition('a.mmtid', $item->mmtid);
      $result = $select->execute();
      foreach ($result as $r) {
        $gids[] = $r->gid;
      }

      if ($gids) {
        $users_in_groups = mm_content_get_users_in_group($gids, NULL, FALSE, 0);
        if (!is_null($users_in_groups)) {
          foreach ($users_in_groups as $uid => $usr) {
            if (is_numeric($uid) && $uid >= 0) {
              $select = db_select('mm_group', 'g');
              $select->join('mm_tree_access', 'a', 'a.gid = g.gid');
              $select->addExpression('GROUP_CONCAT(a.mode)', 'modes');
              $select->condition('a.gid', 0, '<')
                ->condition('g.gid', $gids)
                ->condition('g.uid', $uid);
              $r = $select->execute()->fetchObject();
              if ($r) {
                $users[$uid]['modes'] = explode(',', $r->modes);
                $users[$uid]['name'] = $usr;
              }
            }
          }
        }
      }

      $temp_perms = mm_content_get_perms($item->mmtid, FALSE, TRUE, TRUE);
      $allowed = array();
      foreach (array(MM_PERMS_WRITE, MM_PERMS_SUB, MM_PERMS_APPLY, MM_PERMS_READ) as $mode) {
        foreach ($temp_perms[$mode]['groups'] as $gid => $name) {
          if (!isset($allowed[$gid])) {
            $allowed[$gid] = !$is_new || mm_content_user_can($gid, MM_PERMS_APPLY);
            if ($allowed) {
              $members = mm_content_get_users_in_group($gid, '<br />', FALSE, 20, TRUE);
              if ($members == '') $members = t('(none)');
              $groups[$gid]['name'] = mm_content_expand_name($name);
              $groups[$gid]['members'] = $members;
            }
          }
          $groups[$gid]['modes'][] = $mode;
        }
      }
    }
  }

  mm_ui_content_edit_permissions($form['settings_perms'], $types, $default_modes, $groups, $users, $owner, $is_search, $flags_not_admin['limit_write'], $x);

  if (!$is_search) {
    $node_prop_desc = '';
    if (user_access('propagate page perms')) {
      $form['settings_perms']['propagate'] = array(
        '#type' => 'checkbox',
        '#title' => t('Copy these permissions to all @subthings of this @thing', $x),
        '#default_value' => FALSE,
        '#description' => t('If this option is checked, the permissions will be copied to all @subthings of this one that you have permission to change.', $x),
      );
      $node_prop_desc = ' ' . t('If the option above is also checked, permissions will be copied to the content on all @subthings.', $x);
    }

    $form['settings_perms']['node_propagate'] = array(
      '#type' => 'checkbox',
      '#title' => t('Copy these permissions to all content on this @thing', $x),
      '#access' => !$is_group && user_access('propagate node perms'),
      '#default_value' => FALSE,
      '#description' => t('If this option is checked, the permissions will be copied to all pieces of content on this @thing that you have permission to change.', $x) . $node_prop_desc,
    );
  }   // !$is_search

  $form['settings_perms']['hover'] = array(
    '#type' => 'value',
    '#value' => $is_new ? '' : $item->hover,
  );

  $form['additional_settings'] = array(
    '#type' => 'vertical_tabs',
  );

  _mm_ui_add_summary_js($form);   // Initialize summaries.
  if (isset($item->flags['limit_name']) && !$all_menus) {
    $form['additional_settings']['settings_general']['name'] = array(
      '#type' => 'value',
      '#value' => $item->name,
    );
  }
  else {
    $form['additional_settings']['settings_general'] = array(
      '#type' => 'fieldset',
      '#title' => t('General settings'),
      '#description' => t('General settings for this page'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );
    _mm_ui_add_summary_js($form['additional_settings']['settings_general'], 'settings_general');
    $form['additional_settings']['settings_general']['name'] = array(
      '#type' => 'textfield',
      '#title' => $is_group ? t('Group name') : ($mmtid == 1 || !$is_new && (!empty($item->parent) && $item->parent == 1) ? t('Site name') : t('Page name')),
      '#default_value' => isset($item->name) ? $item->name : '',
      '#required' => TRUE,
      '#size' => 40,
      '#maxlength' => 128,
      '#description' => $is_group ? '' : t('The name that appears in menus.'),
    );
  }

  if ($is_group) {
    $form['members'] = array(
      '#type' => 'fieldset',
      '#title' => t('Group members'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );

    if (!$is_new && user_access('administer permissions')) {
      $roles = array();
      $select = db_select('mm_role2group', 'r2');
      $select->join('role', 'r', 'r.rid = r2.rid');
      $select->fields('r', array('rid', 'name'))
        ->condition('r2.gid', $item->mmtid);
      if ($result = $select->execute()) {
        foreach ($result as $r) {
          $roles[] = l($r->name, "admin/people/permissions/roles/edit/$r->rid") . ' (' . l(t('permissions'), "admin/people/permissions/$r->rid") . ')';
        }

        if ($roles) {
          $form['members']['warning'] = array(
            '#type' => 'item',
            '#markup' => t('This group\'s members will be added to !plur !list.', array('!plur' => (count($roles) == 1 ? t('the role') : t('these roles:') . ' '), '!list' => implode(', ', $roles)))
          );
        }
      }
    }

    if (mm_content_is_vgroup($item->mmtid)) {
      $form['members']['#description'] = t('Enter two portions of a SQL statement that returns the user IDs (uids) of the users in the group:<br />' .
          '<code>SELECT <font color="green"><u>ColumnName</u></font> AS uid <font color="green"><u>FROM TableName WHERE Condition</u></font></code>');
      if ($is_new) {
        $data = array(
          'field' => t('ColumnName'),
          'qfrom' => t('FROM TableName WHERE Condition'),
        );
      }
      else {
        $select = db_select('mm_group', 'g');
        $select->join('mm_vgroup_query', 'v', 'g.vgid = v.vgid');
        $select->fields('v')
          ->condition('g.gid', $item->mmtid);
        $data = $select->execute()->fetchAssoc();
        if ($data) {
          $form['vgid'] = array(
            '#type' => 'value',
            '#value' => $data['vgid'],
          );

          $msgs = array(
            MM_VGROUP_DIRTY_NEXT_CRON => t('This group will be regenerated during the next cron run.'),
            MM_VGROUP_DIRTY_FAILED => t('This group has been marked as potentially corrupt, and must be examined before it will be regenerated.'),
            MM_VGROUP_DIRTY_REDO => t('This group was previously marked as potentially corrupt, but will be regenerated during the next cron run.'),
          );
          if (isset($msgs[$data['dirty']])) {
            drupal_set_message($msgs[$data['dirty']], $data['dirty'] == MM_VGROUP_DIRTY_FAILED ? 'error' : 'warning');
          }
        }
      }

      $form['members']['qfield'] = array(
        '#type' => 'textfield',
        '#title' => t('Column to select'),
        '#default_value' => isset($data['field']) ? $data['field'] : '',
        '#size' => 40,
        '#maxlength' => 40,
        '#description' => t('The name of the database column (or a constant value) to SELECT; if blank, the group will not contain any users'),
      );
      $form['members']['qfrom'] = array(
        '#type' => 'textarea',
        '#title' => t('FROM clause'),
        '#default_value' => $data['qfrom'],
        '#rows' => 4,
        '#wysiwyg' => FALSE,
        '#description' => t('The FROM portion of the SELECT statement; can be blank'),
      );
      if (module_exists('token')) {
        $form['members']['tokens'] = array(
          '#type' => 'fieldset',
          '#title' => t('Tokens'),
          '#collapsible' => TRUE,
          '#collapsed' => TRUE,
          array (
            '#theme' => 'token_tree',
            '#token_types' => array('mm_tree'),
            '#global_types' => FALSE,
          ),
        );
      }
    }
    else {    // normal group
      $token = drupal_get_token(MM_LARGE_GROUP_TOKEN . $mmtid . ($is_new ? 'new' : ''));
      $form['mm_form_token'] = array('#value' => $token);
      mm_static('ui_content_edit_js', TRUE, $mmtid, $token, 'members', $is_new);
      _mm_ui_userlist_setup($is_search ? array() : NULL, $form['members'], 'members', t('Members:'), FALSE, t('Choose the members of this group.'), '', !$is_search);
      unset($form['members']['members']['#description']);
    }
  }
  elseif (isset($item->flags['limit_alias']) && !$all_menus || ($is_new ? $mmtid == 1 : $item->parent == 1)) {      // !$is_group
    $form['additional_settings']['settings_general']['alias'] = array(
      '#type' => 'value',
      '#value' => $item->alias,
    );
  }
  else {
    $form['additional_settings']['settings_general']['alias'] = array(
      '#type' => 'machine_name',
      '#title' => t('URL name'),
      '#machine_name' => array(
        'exists' => 'mm_ui_machine_name_exists',
        'source' => array('additional_settings', 'settings_general', 'name'),
        'label' => t('URL name'),
        'replace_pattern' => '[^-.a-z0-9_]+',
        'error' => t('The URL name must contain only lowercase letters, numerals, hyphens, periods and underscores.'),
      ),
      '#default_value' => isset($item->alias) ? $item->alias : '',
      '#required' => (!isset($item->is_user_home) || !$item->is_user_home) && !$all_menus,
      '#size' => 20,
      '#maxlength' => 128,
      '#description' => t('The name that will be used in the Web address of the page. ' .
        'Make this a shortened version of the Page name, using only lowercase letters, ' .
        'numerals, hyphens, periods and underscores.'),
    );
  }

  if (isset($form['additional_settings']['settings_general']['alias'])) {
    $form['additional_settings']['settings_general']['alias_name'] = array(
      '#type' => 'hidden',
      '#attributes' => array('class' => array('mm-alias-name')),
      '#value' => isset($item->alias) ? $item->alias : '',
    );
  }

  if ($all_menus) {
    $form['additional_settings']['flags'] = array(
      '#type' => 'fieldset',
      '#title' => t('Flags'),
      '#description' => t('Attributes used in special queries; only administrators can edit this list'),
    );
    _mm_ui_add_summary_js($form['additional_settings']['flags'], 'flags');

    $predefined = mm_ui_flags_info();
    foreach ($predefined as $module => $list) {
      if (count($predefined) > 1 || $is_search) {
        $form['additional_settings']['flags'][$module] = array(
          '#type' => 'fieldset',
          '#title' => $module,
        );
        $form['additional_settings']['flags'][$module]['title'] = array(
          '#type' => 'markup',
          '#markup' => "<h2>{$module}</h2>",
        );
      }
      ksort($list);
      $weight = 1;
      foreach ($list as $flag => $elem) {
        if (!isset($elem['#title'])) {
          $elem['#title'] = $flag;
        }
        $elem['#weight'] = $elem['#type'] == 'checkbox' ? $weight : $weight + 100;
        if ($elem['#type'] == 'textfield') {
          $elem['#maxlength'] = 255;
        }
        $elem['#default_value'] = $elem['#type'] == 'checkbox' ? isset($item->flags[$flag]) : (isset($item->flags[$flag]) ? $item->flags[$flag] : '');
        $elem['#prefix'] = "<div class='container-inline'>";
        $elem['#suffix'] = '&nbsp;&nbsp;&nbsp;' .
          theme('tooltip', array(
            'text' => t('help'),
            'title' => $elem['#title'],
            'tip' => $elem['#description']
          )) . '</div>';
        $elem['#attributes'] = array('class' => array('flag-checkbox'));
        unset($elem['#description']);
        $form['additional_settings']['flags'][$module]["flag_$flag"] = $elem;

        unset($item->flags[$flag]);
        $weight++;
      }
    }

    $free_flags = array();
    foreach ($item->flags as $flag => $data)
      if (!empty($data)) $free_flags[] = "$flag=$data";
      else $free_flags[] = $flag;

    $form['additional_settings']['flags']['free_flags'] = array(
      '#type' => 'textarea',
      '#title' => t('Others'),
      '#default_value' => join("\n", $free_flags),
      '#wysiwyg' => FALSE,
      '#weight' => 200,
      '#rows' => max(count($free_flags) + 1, 2),
      '#description' => t('A free-form list of attributes, one per line. Can either be <code>name</code> or <code>name=value</code>.'),
    );
  }

  if (!$is_group) {
    $theme[''] = t('(use parent\'s theme)');
    $all_themes = array();
    $allowed_themes = mm_content_resolve_cascaded_setting('allowed_themes', $item->mmtid, $theme_at, $theme_parent, $is_new);
    $desc_add = '';
    foreach (list_themes() as $t) {
      if ($t->status) {
        if ($is_search || !count($allowed_themes) || array_search($t->name, $allowed_themes) !== FALSE)
          $theme[$t->name] = $t->name;
        elseif ($all_menus) {
          $theme[$t->name] = $t->name . ' *';
          $desc_add = ' ' . t('Themes in the list ending with * are only available to administrators, based on the current settings.');
        }
        if ($all_menus) $all_themes[$t->name] = $t->name;
      }
    }
    natcasesort($theme);

    if (count($theme) > 2 || $all_menus) {
      $form['additional_settings']['theme'] = array(
        '#type' => 'fieldset',
        '#title' => t('Theme'),
      );
      _mm_ui_add_summary_js($form['additional_settings']['theme'], 'theme');
    }

    if (count($theme) > 2) {
      $form['additional_settings']['theme']['theme'] = array(
        '#type' => 'select',
        '#title' => t('Theme for this @thing and its children', $x),
        '#default_value' => isset($item->theme) ? $item->theme : '',
        '#options' => $theme,
        '#description' => t('The theme will be applied to this @thing and its @subthings, unless overridden at a lower level.', $x) . $desc_add,
      );
    }
    else {
      $form['additional_settings']['theme']['theme'] = array(
        '#type' => 'value',
        '#value' => $item->theme,
      );
    }

    if ($all_menus) {
      $desc = t('This option is only available to administrators.');
      if (!$theme_parent) {
        $desc .= ' ' . t('No parents of this @thing have theme limits, so if you deselect all themes here, non-admin users will be able to choose any theme in the list.', $x);
      }
      else {
        $ptree = mm_content_get($theme_parent);
        $link = l(mm_content_expand_name($ptree->name), mm_content_get_mmtid_url($ptree->mmtid));
        $desc .= ' ' . t('To inherit the settings of the parent, !parent, deselect all themes here.',
            array('!parent' => $link));
      }
      natcasesort($all_themes);
      $form['additional_settings']['theme']['allowed_themes'] = array(
        '#type' => 'select',
        '#title' => t('Allowed themes for this @thing and its children', $x),
        '#multiple' => TRUE,
        '#size' => 5,
        '#default_value' => !$is_new && $theme_at == $item->mmtid ? $allowed_themes : array(),
        '#options' => $all_themes,
        '#description' => $desc,
      );
    }

    $form['additional_settings']['menu'] = array(
      '#type' => 'fieldset',
      '#title' => t('Menu and layout'),
    );
    _mm_ui_add_summary_js($form['additional_settings']['menu'], 'menu');

    $menu_vals = array(
      'bid' => -1,
      'max_depth' => -1,
      'max_parents' => -1,
      'allow_reorder' => -1,
    );
    if (!$is_new) {
      $result = db_select('mm_tree_block', 'b')
        ->fields('b', array('bid', 'max_depth', 'max_parents'))
        ->condition('b.mmtid', $item->mmtid)
        ->range(0, 1)
        ->execute();
      if ($r = $result->fetchAssoc()) $menu_vals = $r;
      $allow_reorder = mm_content_resolve_cascaded_setting('allow_reorder', $item->mmtid, $reorder_at, $reorder_parent);
      if ($reorder_at == $item->mmtid) {
        $menu_vals['allow_reorder'] = $allow_reorder;
      }
      elseif (!isset($reorder_at) && ($item->mmtid == 1 || $item->mmtid == mm_home_mmtid())) {
        $menu_vals['allow_reorder'] = 0;
      }
      else {
        $menu_vals['allow_reorder'] = -1;
      }
    }

    if (!$flags_not_admin['limit_hidden']) {
      $form['additional_settings']['menu']['hide_menu'] = array(
        '#type' => 'checkbox',
        '#title' => t('Don\'t show this @thing in the menu', $x),
        '#description' => t('If checked, this page will not be listed in the navigation menu but can still be accessed directly using its URL address.'),
        '#default_value' => isset($item->hidden) ? $item->hidden : FALSE,
      );
    }
    else {
      $form['additional_settings']['menu']['hide_menu'] = array('#type' => 'value',
          '#value' => $item->hidden);
    }

    if ($flags_not_admin['limit_location']) {
      $form['additional_settings']['menu']['menu_start'] = array('#type' => 'value',
          '#value' => $menu_vals['bid']);
    }
    else {
      $blocks = array(-1 => t('Standard page'));
      $help =   array(-1 => t('Creates a new page and the menu item that points to the page. The menu item will be indented and listed alphabetically under the parent menu item in the left navigation menu.'));
      foreach (mm_content_get_blocks(TRUE) as $bid => $b) {
        $blocks[$bid] = $b['info'];
        $help[$bid] =   $b['help'];
      }

      $form['additional_settings']['menu']['menu_start'] = array(
        '#type' => $is_search ? 'select' : 'mm_help_radios',
        '#title' => t('Location on screen'),
        '#default_value' => isset($blocks[$menu_vals['bid']]) ? $menu_vals['bid'] : mm_ui_mmlist_key0($blocks),
        '#attributes' => array('class' => array('settings-menu-start')),
        '#options' => $blocks,
        '#help' => $help,
      );
    }

    $form['additional_settings']['menu']['max_depth'] = array(
      '#type' => 'select',
      '#title' => t('Max. number of child levels to display'),
      '#default_value' => $menu_vals['max_depth'],
      '#attributes' => array('class' => array('settings-max-depth')),
      '#options' => _mm_ui_levels()
    );
    if ($all_menus) {
      $form['additional_settings']['menu']['max_parents'] = array(
        '#type' => 'select',
        '#title' => t('Max. number of parent levels to display'),
        '#default_value' => $menu_vals['max_parents'],
        '#attributes' => array('class' => array('settings-max-parents')),
        '#options' => _mm_ui_levels(),
        '#description' => t('As the user gets deeper down in the menu tree, higher-level entries will be removed. This keeps a deeply-nested menu from getting too indented. This setting is inherited by any @subthings.', $x)
      );
      $form['additional_settings']['menu']['allow_reorder'] = array(
        '#type' => 'select',
        '#title' => t('Allow the menu and its children to be reordered'),
        '#default_value' => $menu_vals['allow_reorder'],
        '#options' => array(-1 => t('(inherit from parent page)'), 1 => t('Yes'), 0 => t('No')),
        '#description' => t('Administrators always have the ability to reorder menus.'),
      );
    }
    else {
      $form['additional_settings']['menu']['max_parents'] = array(
        '#type' => 'value',
        '#value' => $menu_vals['max_parents'],
      );
    }

    if (user_access('show/hide post information')) {
      $form['additional_settings']['defaults']['node_info'] = array(
        '#type' => 'select',
        '#title' => t('Default attribution style'),
        '#default_value' => isset($item->node_info) ? $item->node_info : '',
        '#options' => _mm_ui_node_info_values($form['additional_settings']),
        '#description' => t('Unless disabled by an administrator, this value will be the default for all new content added to this page. You can change this behavior in the <em>Appearance</em> settings of the content.'),
      );
    }
    elseif (!$is_search) {
      $form['additional_settings']['defaults']['node_info'] = array(
        '#type' => 'value',
        '#value' => $item->node_info,
      );
    }

    drupal_add_js(array('monster_menus' => array('comment_enabled' => module_exists('comment'))), 'setting');

    if (!$is_group && module_exists('comment')) {
      if (variable_get('mm_finegrain_comment_readability', FALSE)) {
        $comments_readable = $is_new ? '' : mm_content_get_cascaded_settings($item->mmtid, 'comments_readable');
        $form['additional_settings']['defaults']['comments_readable'] = array(
          '#type' => 'select',
          '#title' => t('Who can read comments by default'),
          '#default_value' => $comments_readable,
          '#options' => _mm_ui_comment_read_setting_values(t('(inherit from parent page)')),
          '#description' => t('This value will be the default for all new content added to this page. You can change this behavior in the <em>Comment settings</em> of the content.'),
        );
      }
      $form['additional_settings']['defaults']['comment'] = array(
        '#type' => 'select',
        '#title' => t('Who can add comments by default'),
        '#access' => user_access('enable/disable comments') || user_access('administer comments'),
        '#default_value' => isset($item->comment) ? $item->comment : '',
        '#options' => _mm_ui_comment_write_setting_values(),
        '#description' => t('Unless disabled by an administrator, this value will be the default for all new content added to this page. You can change this behavior in the <em>Comment settings</em> of the content.'),
      );
    }

    if (isset($form['additional_settings']['defaults'])) {
      $form['additional_settings']['defaults'] = array_merge($form['additional_settings']['defaults'], array(
        '#type' => 'fieldset',
        '#title' => t('Defaults'),
      ));
      _mm_ui_add_summary_js($form['additional_settings']['defaults'], 'defaults');
    }

    $form['additional_settings']['appearance'] = array(
      '#type' => 'fieldset',
      '#title' => t('Appearance'),
    );
    _mm_ui_add_summary_js($form['additional_settings']['appearance'], 'appearance');
    $form['additional_settings']['appearance']['previews'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show only the summaries ("teasers") of all contents'),
      '#default_value' => isset($item->previews) ? $item->previews : '',
    );

    if (variable_get('mm_enable_rss', FALSE)) {
      $form['additional_settings']['appearance']['rss'] = array(
        '#type' => 'checkbox',
        '#title' => t('Enable the RSS feed for this page'),
        '#default_value' => !empty($item->rss) ? $item->rss : '',
      );
    }
    else {
      $form['additional_settings']['appearance']['rss'] = array(
        '#type' => 'value',
        '#value' => isset($item->rss) ? $item->rss : '',
      );
    }

    $nodes_per_page = $is_new ? '' : mm_content_get_cascaded_settings($item->mmtid, 'nodes_per_page');
    if (empty($nodes_per_page) && !$is_new && $item->mmtid == mm_home_mmtid()) $nodes_per_page = variable_get('default_nodes_main', 10);
    $form['additional_settings']['appearance']['nodes_per_page'] = array(
      '#type' => 'select',
      '#title' => t('Pieces of content to display at one time'),
      '#default_value' => $nodes_per_page,
      '#options' => array('' => t('(inherit from parent page)')) + $ilist + array(0 => t('(display all content immediately)'), -2 => t('(display all content as needed)')),
      '#description' => t('If more than this number of pieces of content is present, pagination controls will be displayed.'),
    );

    if ($all_menus) {
      $form['additional_settings']['settings_node_types'] = array(
        '#type' => 'fieldset',
        '#title' => t('Allowed content types'),
      );
      $form['additional_settings']['settings_node_types']['title'] = array(
        '#markup' => '<h3>' . t('Allowed content types for this @thing and its children', $x) . '</h3>',
      );
      _mm_ui_add_summary_js($form['additional_settings']['settings_node_types'], 'settings_node_types');

      $all_types = array();
      $allowed_node_types = mm_content_resolve_cascaded_setting('allowed_node_types', $item->mmtid, $types_at, $types_parent, $is_new);
      $types_inherit = $is_new || $types_at != $item->mmtid || count($allowed_node_types) == 0;
      foreach (node_type_get_types() as $t)
        $all_types[$t->type] = $t->name;
      natcasesort($all_types);

      $desc = t('This option is only available to administrators.');
      if (!$types_parent) {
        $cb_desc = t('<font color="red">No parents of this @thing have node types defined, so unless you select something in the list below, only administrators will be able to add content.</font>', $x);
        $form['additional_settings']['settings_node_types']['#collapsed'] = FALSE;
      }
      else {
        $ptree = mm_content_get($types_parent);
        $link = l(mm_content_expand_name($ptree->name), mm_content_get_mmtid_url($ptree->mmtid));
        $cb_desc = t('If checked, the settings of the parent, !parent, will be inherited',
            array('!parent' => $link));
        $desc .= ' ' . t('If you deselect all node types here and do not check the Inherit option, only admin users will be able to add new content.');
      }

      $form['additional_settings']['settings_node_types']['allowed_node_types_inherit'] = array(
        '#type' => 'checkbox',
        '#title' => t('Inherit from parent @thing', $x),
        '#default_value' => $types_inherit,
        '#description' => $cb_desc,
        '#attributes' => array('class' => array('settings-node-types')),
      );
      $form['additional_settings']['settings_node_types']['allowed_node_types'] = array(
        '#type' => 'select',
        '#title' => t('Node types'),
        '#multiple' => TRUE,
        '#size' => 10,
        '#default_value' => $types_at == $item->mmtid ? $allowed_node_types : array(),
        '#options' => $all_types,
        '#description' => $desc,
      );
    }

    if (user_access('create archives')) {
      $form['additional_settings']['settings_archive'] = array(
        '#type' => 'fieldset',
        '#title' => t('Archive'),
      );
      _mm_ui_add_summary_js($form['additional_settings']['settings_archive'], 'settings_archive');
      $item->frequency = 'month';
      $item->main_nodes = 10;
      $item->archive_mmtid = array();
      if (!$is_new) {
        $tree = mm_content_get($mmtid, MM_GET_ARCHIVE);
        if (isset($tree->archive_mmtid)) {
          $item = (object)array_merge((array)$item, (array)$tree);
          $archive = mm_content_get($item->archive_mmtid);
          $item->archive_mmtid = array($item->archive_mmtid => mm_content_expand_name($archive->name));
        }
      }

      if (isset($tree->archive_mmtid) && $tree->archive_mmtid == $mmtid) {
        $atree = mm_content_get($tree->main_mmtid);
        $x['!link'] = l(mm_content_expand_name($atree->name), mm_content_get_mmtid_url($atree->mmtid) . '/settings');
        $form['additional_settings']['settings_archive']['#description'] = t('This @thing is an archive for !link. To change the archival settings, visit that @thing.', $x);
      }
      else {
        $form['additional_settings']['settings_archive']['#description'] = t('Contents past a certain age will be automatically moved to a secondary page, where they are organized by date.');
        $have_archive = count($item->archive_mmtid);
        $form['additional_settings']['settings_archive']['archive'] = array(
          '#type' => 'checkbox',
          '#title' => t('Use an archive'),
          '#default_value' => $have_archive,
        );
        $form['additional_settings']['settings_archive']['inner'] = array(
          '#prefix' => '<div class="settings-archive" style="display: none">',
          '#suffix' => '</div>',
        );
        $form['additional_settings']['settings_archive']['inner']['frequency'] = array(
          '#type' => 'select',
          '#title' => t('Frequency'),
          '#options' => array(
            'day' => t('daily'), 'week' => t('weekly'), 'month' => t('monthly'),
            'year' => t('yearly')),
          '#default_value' => $item->frequency,
        );
        $form['additional_settings']['settings_archive']['inner']['main_nodes'] = array(
          '#type' => 'select',
          '#title' => t('Pieces of content to show on the main page'),
          '#default_value' => $item->main_nodes,
          '#description' => t('At least this many posts will be shown, even if they have been archived.'),
          '#options' => $ilist,
        );
        $path_mmtids = mm_content_get_parents_with_self($item->mmtid);
        $form['additional_settings']['settings_archive']['inner']['archive_mmtid'] = array(
          '#type' => 'mm_fake_required',
          '#mm_orig_type' => 'mm_catlist',
          '#mm_list_selectable' => MM_PERMS_WRITE,
          '#mm_list_popup_start' => implode('/', $path_mmtids),
          '#mm_list_browser' => 'mm-browser-load',
          '#process' => array('_mm_ui_process_mmlist'),
          '#title' => t('Location of archive:'),
          '#description' => t('Choose the page to contain the archived contents. It must already exist.'),
          '#mm_list_min' => 1,
          '#mm_list_max' => 1,
          '#default_value' => $item->archive_mmtid,
        );
      }
    }   // user_access('create archives')
  }   // !$is_group

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => $is_new ? ($mmtid == 1 ? t('Create site', $x) : t('Create @subthing', $x)) : t('Save settings'),
  );
  if (!$is_search) mm_static('edit_cat', TRUE);

  if (!empty($_POST) && form_get_errors()) {
    drupal_set_message(t('The settings have not been saved because of the errors.'), 'error');
  }

  return $form;
}

/**
 * Validate the form for editing or creating a tree entry
 */
function mm_ui_content_edit_validate($form, &$form_state) {
  $form_vals =& $form_state['values'];
  $is_new = isset($form_vals['is_new']);

  $mmtid = $form_vals['path'];
  if (!is_numeric($mmtid)) {
    form_set_error('', t('Bad data'));
    return;
  }

  $x = mm_ui_strings($is_group = mm_content_is_group($mmtid));

  if (!mm_content_user_can($mmtid, $is_new ? MM_PERMS_SUB : MM_PERMS_WRITE)) {
    $message = $is_new ?
      'You do not have permission to add @subthings to this @thing' :
      'You do not have permission to modify this @thing';
    watchdog('mm', $message, $x, WATCHDOG_WARNING, $form_vals['path']);
    form_set_error('', t($message, $x));
    return;
  }

  $test_mmtid = $mmtid;
  if (!$is_new) $test_mmtid = mm_content_get_parent($mmtid);

  if (!_mm_ui_validate_entry($mmtid, $test_mmtid, $form_vals, $is_new, $is_new))
      return;

  if ($is_group)
    if (mm_content_is_vgroup($mmtid)) {
      if (trim($form_vals['qfield']) != '') {
        $q = 'SELECT ' . trim($form_vals['qfield']) . ' AS uid';
        if (trim($form_vals['qfrom']) != '') $q .= ' ' . trim($form_vals['qfrom']);
        try {
          db_query($q);
        }
        catch (Exception $e) {
          form_set_error('members', t('There was an error testing the query.<br /><strong>Query:</strong> @query<br /><strong>Error:</strong> @error',
              array('@query' => $q, '@error' => $e->getMessage())));
        }
      }
    }
    elseif (isset($form_vals['members']))
        _mm_ui_verify_userlist($form_vals['members'], 'members');

  if (user_access('administer all menus'))
      _mm_ui_verify_userlist($form_vals['owner'], 'owner');

  list($form_vals['all_values_group'], $form_vals['all_values_user'], $form_vals['default_modes']) = _mm_ui_form_parse_perms($form_vals, TRUE);

  if (!empty($form_vals['archive'])) {
    $archive_mmtid = mm_ui_mmlist_key0($form_vals['archive_mmtid']);
    // We're using a "fake_required", so this test is necessary
    if (!isset($archive_mmtid)) {
      form_set_error('archive_mmtid', t('The archive location is required.'));
    }
    elseif (!mm_content_user_can($archive_mmtid, MM_PERMS_WRITE)) {
      form_set_error('archive_mmtid', t('You do not have permission to modify the @thing you chose for the archive.', $x));
    }
    else {
      $tree = mm_content_get($archive_mmtid, MM_GET_ARCHIVE);
      if (isset($tree->archive_mmtid))
        if ($tree->archive_mmtid == $archive_mmtid && ($is_new || $tree->main_mmtid != $mmtid)) {
          form_set_error('archive_mmtid', t('The @thing you chose for the archive is already the archive for another @thing.', $x));
        }
        elseif ($tree->main_mmtid == $archive_mmtid) {
          form_set_error('archive_mmtid', t('The @thing you chose for the archive already contains an archive. Please choose a different @thing.', $x));
        }
      $content = mm_content_get_nids_by_mmtid($archive_mmtid, 1);
      if (count($content)) {
        form_set_error('archive_mmtid', t('The @thing you chose for the archive already has contents. Before it can become an archive, you must remove the contents.', $x));
      }
    }
  }

  if (isset($form_vals['menu_start']) && $form_vals['menu_start'] != -1) {
    $blocks = mm_content_get_blocks(TRUE);
    $parent = mm_content_get_parent($mmtid);
    $bid = $form_vals['menu_start'];
    if (is_null($parent) || $bid != -1 && !isset($blocks[$bid])) {
      form_set_error('menu_start', t('The <em>Location on screen</em> you chose is not valid.', $x));
    }
    elseif ($blocks[$bid]['show_node_contents'] && $form['additional_settings']['menu']['menu_start']['#default_value'] != $bid && !mm_content_user_can($parent, MM_PERMS_WRITE)) {
      form_set_error('menu_start', t('The <em>Location on screen</em> you chose would change the appearance of this @thing\'s parent. You do not have edit/delete permission for the parent @thing.', $x));
    }
  }
}

/**
 * Process the form for editing or creating a tree entry
 */
function mm_ui_content_edit_submit($form, &$form_state) {
  global $user;

  $form_vals =& $form_state['values'];
  $mmtid = $form_vals['path'];
  $x = mm_ui_strings($is_group = mm_content_is_group($mmtid));
  $is_new = isset($form_vals['is_new']);
  $alias = $is_group || $mmtid == mm_home_mmtid() && !$is_new ? '' : trim($form_vals['alias']);
  $name = trim($form_vals['name']);

  if ($is_group) {
    // Currently, groups always have MM_PERMS_APPLY for everyone. Change this if
    // you want to only allow some users to apply a group to something's
    // permissions.
    $form_vals['all_values_group'][MM_PERMS_APPLY] = $form_vals['all_values_user'][MM_PERMS_APPLY] = array();
    if (!in_array(MM_PERMS_APPLY, $form_vals['default_modes'])) $form_vals['default_modes'][] = MM_PERMS_APPLY;
  }
  $perms = array();
  foreach (array(MM_PERMS_WRITE, MM_PERMS_SUB, MM_PERMS_APPLY, MM_PERMS_READ) as $m) {
    $perms[$m]['groups'] = isset($form_vals['all_values_group'][$m]) ? $form_vals['all_values_group'][$m] : array();
    $perms[$m]['users']  = isset($form_vals['all_values_user'][$m]) ? $form_vals['all_values_user'][$m] : array();
  }

  $flags = array();
  // Get the current cascaded settings.
  $cascaded = $is_new ? array() : mm_content_get_cascaded_settings($mmtid);
  $owner = $user->uid;
  if (user_access('administer all menus')) {
    $owner = $form_vals['owner'];

    $predefined = module_invoke_all('mm_tree_flags');
    foreach ($predefined as $flag => $elem) {
      $flag_name = "flag_$flag";
      if (!empty($form_vals[$flag_name])) {
        $flags[$flag] = $elem['#type'] == 'checkbox' ? '' : trim($form_vals[$flag_name]);
      }
    }

    if (!empty($form_vals['free_flags'])) {
      foreach (explode("\n", $form_vals['free_flags']) as $f) {
        $f = trim($f);
        if (!empty($f)) {
          if (preg_match('/^\s*(.*?)\s*=\s*(.*?)\s*$/', $f, $data))
            $flags[$data[1]] = $data[2];
          else $flags[trim($f)] = '';
        }
      }
    }

    $cascaded['allowed_node_types'] = array();
    if (empty($form_vals['allowed_node_types_inherit'])) {
      $cascaded['allowed_node_types'] = isset($form_vals['allowed_node_types']) && count($form_vals['allowed_node_types']) ? $form_vals['allowed_node_types'] : array('');  // intentionally empty
    }
  }
  elseif (!$is_new) {
    if ($tree = mm_content_get($mmtid, MM_GET_FLAGS)) {  // read old flags for item
      _mm_ui_is_user_home($tree);
      $flags = $tree->flags;
    }
  }

  // Merge cascaded settings not already handled above
  foreach (mm_content_get_cascaded_settings() as $setting_name => $desc)
    if ($setting_name != 'allowed_node_types')
      if (!isset($desc['user_access']) || user_access($desc['user_access']))
        if (isset($form_vals[$setting_name]))
          $cascaded[$setting_name] = $form_vals[$setting_name];

  $md = isset($form_vals['max_depth']) ? $form_vals['max_depth'] : -1;
  $mp = isset($form_vals['max_parents']) ? $form_vals['max_parents'] : -1;
  if (isset($form_vals['menu_start'])) {
    $ms = (int)$form_vals['menu_start'];
    if (!$ms && ($md != -1 || $mp != -1)) $ms = -1;
  }
  else {
    $ms = '';
    $md = $mp = -1;
  }

  $params = array(
    'name'                 => $name,
    'alias'                => $alias,
    'default_mode'         => implode(',', $form_vals['default_modes']),
    'uid'                  => $owner,
    'weight'               => isset($form_vals['weight']) && empty($form_vals['hide_menu']) ? $form_vals['weight'] : 0,
    'theme'                => isset($form_vals['theme']) ? $form_vals['theme'] : '',
    'cascaded'             => $cascaded,
    'perms'                => $perms,
    'recurs_perms'         => !empty($form_vals['propagate']),
    'propagate_node_perms' => $form_vals['node_propagate'],
    'flags'                => $flags,
    'hover'                => $form_vals['hover'],
    'menu_start'           => $ms,
    'max_depth'            => $md,
    'max_parents'          => $mp,
    'rss'                  => isset($form_vals['rss']) ? $form_vals['rss'] : '',
    'node_info'            => isset($form_vals['node_info']) ? $form_vals['node_info'] : NULL,
    'previews'             => !empty($form_vals['previews']),
    'hidden'               => !empty($form_vals['hide_menu']),
    'comment'              => isset($form_vals['comment']) ? $form_vals['comment'] : '',
  );
  if ($is_group) {
    if (isset($form_vals['members'])) {
      $params['members'] = array_keys($form_vals['members']);
    }
    $params['large_group_form_token'] = isset($form_vals['members-use-large-group']) && $form_vals['members-use-large-group'] == 'yes' ? $form['mm_form_token']['#value'] : '';
    $params['qfield'] = $qfield = isset($form_vals['qfield']) ? trim($form_vals['qfield']) : '';
    $params['qfrom'] = isset($form_vals['qfrom']) ? trim($form_vals['qfrom']) : '';
  }

  mm_module_invoke_all_array('mm_content_edit_submit_alter', array($is_new, $mmtid, &$params));

  $mmtid = mm_content_insert_or_update($is_new, $mmtid, $params);
  if (empty($mmtid)) return;

  if ($is_group) {
    if ($qfield != '' && mm_content_is_vgroup($mmtid)) {
      drupal_set_message(t('The members of this virtual group will be updated during the next cron run.'));
    }
  }
  elseif (user_access('create archives')) {
    db_delete('mm_archive')
      ->condition('main_mmtid', $mmtid)
      ->execute();
    if (!empty($form_vals['archive'])) {
      $archive_mmtid = mm_ui_mmlist_key0($form_vals['archive_mmtid']);
      if ($archive_mmtid) {
        db_insert('mm_archive')
          ->fields(array(
            'main_mmtid' => $mmtid,
            'archive_mmtid' => $archive_mmtid,
            'frequency' => $form_vals['frequency'],
            'main_nodes' => $form_vals['main_nodes'],
          ))
          ->execute();
        // Don't allow custom ordering
        db_delete('mm_node_reorder')
          ->condition('mmtid', $mmtid)
          ->execute();
      }
    }
  }

  if ($is_new) {
    if (!$is_group && in_array(MM_PERMS_READ, $form_vals['default_modes'])) {
      $x['!link'] = l(t('click here to change the settings'), "mm/$mmtid/settings");
      drupal_set_message(t('<div id="public-warning">The @subthing was successfully created. Note that it is publicly viewable. To make adjustments to who can read it, !link.</div>', $x));
    }
    else {
      drupal_set_message(t('The @subthing was successfully created.', $x));
    }
    $form_state['redirect'] = $form_vals['path'] == 1 && _mm_menu_access_all_admin() ? 'admin/mm/sites' : "mm/$mmtid";
  }
  else {
    if (!$is_group && in_array(MM_PERMS_READ, $form_vals['default_modes'])) {
      $x['!link'] = l(t('click here to change the settings'), "mm/$mmtid/settings");
      drupal_set_message(t('<div id="public-warning">The settings for this @thing have been saved. Note that it is publicly viewable. To make adjustments to who can read it, !link.</div>', $x));
    }
    else {
      drupal_set_message(t('The settings for this @thing have been saved.', $x));
    }
    mm_content_clear_caches($mmtid);
  }
}

function mm_ui_content_edit_permissions(&$form, $types, $default_modes, $groups, $users, $owner = NULL, $is_search = FALSE, $limit_write = FALSE, $x = array()) {
  $form['table']['#theme'] = 'mm_ui_permissions';
  $x['!class'] = t('everyone');
  $all_menus = user_access('administer all menus');
  $checks = array();
  foreach (array_keys($types) as $type) {
    $checks[] = $type == MM_PERMS_WRITE && !$all_menus ? NULL : count($default_modes) && in_array($type, $default_modes);
    $checks[] = FALSE;
  }
  $form['table']['everyone'] = array(
    '#type' => 'value',
    '#value' => array(
      'title' => t('Everyone'),
      'types' => $types,
      'headings' => TRUE,
    ),
  );
  $form['table']['everyone'][] = _mm_ui_perms_table_row('group', 'everyone', t('All users'), '', NULL, $types, $x, $checks);

  if (!$is_search) {
    $form['table']['indiv'] = array(
      '#type' => 'value',
      '#value' => array(
        'title' => t('Individuals'),
        'types' => $types,
        'action' => mm_ui_add_user_subform($form, 'settings-perms-indiv-add', t('add'), t('User(s) to add to permissions:'), t('Add users to permissions'), 'Drupal.MMSettingsPermsAddUsers'),
      ),
    );
    if (!empty($owner)) {
      list($name, $msg, $owner_readonly) = mm_ui_owner_desc($form, $x, $owner, $is_search);
      $x['!class'] = t('this user');
      $form['table']['indiv'][] = _mm_ui_perms_table_row(
        'user',
        'owner',
        t('<span class="settings-perms-owner-prefix">Owner: </span><span class="settings-perms-owner-name">!name</span>', array('!name' => $name)),
        $msg,
        $owner_readonly ? NULL : mm_ui_add_user_subform($form, 'settings-perms-indiv-owner', t('change'), t('Owner:'), t('Change the owner'), 'Drupal.MMSettingsPermsOwner', $owner, $name),
        $types,
        $x,
        array(TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE)
      );
    }

    $checks = array(TRUE, FALSE, TRUE, FALSE, TRUE, FALSE, TRUE, FALSE); // defaults for new row when no users are listed

    $delete_link = '<a href="#" title="' . t('Remove this user') . '" onclick="return Drupal.MMSettingsPermsDelete(this)">' . t('delete') . '</a>';
    $all_values_user = '';
    foreach ($users as $uid => $data) {
      $checks = array();
      foreach (array_keys($types) as $type) {
        if ($checked = in_array($type, $data['modes'])) {
          $all_values_user .= $type . $uid;
        }
        $checks[] = $checked;
        $checks[] = $type == MM_PERMS_WRITE && $limit_write;
      }
      $name = array(
        array('#type' => 'item', '#markup' => $data['name']),
      );
      $form['table']['indiv'][] = _mm_ui_perms_table_row('user', $uid, $name, '', $checks[0] && $limit_write ? '' : $delete_link, $types, $x, $checks);
    }

    // Empty row to be used when adding new users
    if ($limit_write) {
      $checks[0] = FALSE;
      $checks[1] = TRUE;
    }
    $form['table']['indiv'][] = _mm_ui_perms_table_row('user', 'new', '', '', $delete_link, $types, $x, $checks);
    if (!empty($owner) && !$owner_readonly) {
      $form['owner'] = array('#type' => 'hidden', '#default_value' => $owner);
    }

    $form['all_values_user'] = array(
      '#type' => 'hidden',
      '#default_value' => $all_values_user,   // default value, in case JS is disabled
    );
    if ($limit_write) {
      // Tell the JS code that it needs to act differently
      $form['limit_write_not_admin'] = array('#type' => 'hidden');
    }

    $form['table']['groups'] = array(
      '#type' => 'value',
      '#value' => array(
        'title' => t('Groups'),
        'types' => $types,
        'action' => '<a href="#" title="' . t('Add a group') . '" onclick="return Drupal.MMSettingsPermsAddGroup()">' . t('add') . '</a>',
      )
    );

    $delete_link = '<a href="#" title="' . t('Remove this group') . '" onclick="return Drupal.MMSettingsPermsDelete(this)">' . t('delete') . '</a>';
    $x['!class'] = t('the users in this group');
    $elem = array(
      array(
        '#type' => 'fieldset',
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
        '#title' => 'temp title',
        array(
          '#type' => 'item',
          '#markup' => 'temp members',
        ),
      )
    );
    $all_values_group = '';
    foreach ($groups as $gid => $data) {
      $checks = array();
      foreach (array_keys($types) as $type) {
        if ($checked = in_array($type, $data['modes'])) {
          $all_values_group .= $type . $gid;
        }
        $checks[] = $checked;
        $checks[] = $type == MM_PERMS_WRITE && $limit_write;
      }
      $elem[0]['#title'] = $data['name'];
      $elem[0][0]['#markup'] = (mm_content_user_can($gid, MM_PERMS_WRITE) ? l(t('Edit this group'), mm_content_get_mmtid_url($gid) . '/settings') . '<br />' : '') . $data['members'];
      $form['table']['groups'][] = _mm_ui_perms_table_row('group', $gid, $elem, '', $checks[0] && $limit_write ? '' : $delete_link, $types, $x, $checks);
    }

    // Empty row to be used when adding new groups
    if ($limit_write) {
      $checks[0] = FALSE;
      $checks[1] = TRUE;
    }
    else {
      $checks[0] = $checks[1] = FALSE;
    }
    $elem[0][0]['#id'] = 'mm-permissions-group-new';
    $form['table']['groups'][] = _mm_ui_perms_table_row('group', 'new', $elem, '', $delete_link, $types, $x, $checks);

    $form['all_values_group'] = array(
      '#type' => 'hidden',
      '#default_value' => $all_values_group,   // default value, in case JS is disabled
    );
  }
}

function _mm_ui_content_flags_not_admin($item, $all_menus) {
  $mm_flags = monster_menus_mm_tree_flags();
  foreach ($mm_flags as $flag => $val) {
    $flags_not_admin[$flag] = isset($item->flags[$flag]) && !$all_menus;
  }
  return $flags_not_admin;
}
