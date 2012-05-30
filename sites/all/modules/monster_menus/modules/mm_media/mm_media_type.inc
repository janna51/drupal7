$data = array(
  'bundles' => array(
    'mm_media' => (object) array(
      'type' => 'mm_media',
      'name' => 'File Upload',
      'base' => 'node_content',
      'description' => 'Upload images, PDFs, audio and other media files (after uploading files, you can insert them in articles).',
      'help' => '',
      'has_title' => '1',
      'title_label' => 'Title',
      'custom' => '1',
      'modified' => '1',
      'locked' => '0',
      'orig_type' => 'mm_media',
      'module' => 'node',
      'disabled' => '0',
      'disabled_changed' => FALSE,
      'bc_entity_type' => 'node',
    ),
  ),
  'fields' => array(
    'body' => array(
      'entity_types' => array(
        0 => 'node',
      ),
      'translatable' => '1',
      'settings' => array(),
      'indexes' => array(
        'format' => array(
          0 => 'format',
        ),
      ),
      'storage' => array(
        'type' => 'field_sql_storage',
        'settings' => array(),
        'module' => 'field_sql_storage',
        'active' => '1',
        'details' => array(
          'sql' => array(
            'FIELD_LOAD_CURRENT' => array(
              'field_data_body' => array(
                'value' => 'body_value',
                'summary' => 'body_summary',
                'format' => 'body_format',
              ),
            ),
            'FIELD_LOAD_REVISION' => array(
              'field_revision_body' => array(
                'value' => 'body_value',
                'summary' => 'body_summary',
                'format' => 'body_format',
              ),
            ),
          ),
        ),
      ),
      'field_name' => 'body',
      'type' => 'text_with_summary',
      'module' => 'text',
      'active' => '1',
      'locked' => '0',
      'cardinality' => '1',
      'deleted' => '0',
      'columns' => array(
        'value' => array(
          'type' => 'text',
          'size' => 'big',
          'not null' => FALSE,
        ),
        'summary' => array(
          'type' => 'text',
          'size' => 'big',
          'not null' => FALSE,
        ),
        'format' => array(
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
        ),
      ),
      'bundles' => array(
        'node' => array(
          0 => 'alert',
          1 => 'course_assign',
          2 => 'course_links',
          3 => 'crs_guide',
          4 => 'date',
          5 => 'gallery',
          6 => 'gradebook',
          7 => 'mm_calendar',
          8 => 'mm_event',
          9 => 'portal_page',
          10 => 'quiz_instance',
          11 => 'quiz_tool',
          12 => 'roster',
          13 => 'rss_page',
          14 => 'story',
          15 => 'viewref',
          16 => 'webform',
          17 => 'aa',
          18 => 'article',
          19 => 'audio',
          20 => 'course',
          21 => 'course_content',
          22 => 'course_desc',
          23 => 'course_noncourse',
          24 => 'course_xlist',
          25 => 'forum',
          26 => 'testtype',
          27 => 'subpglist',
          28 => 'new_media',
          29 => 'mm_media',
        ),
      ),
    ),
    'field_mm_media_tags' => array(
      'translatable' => '0',
      'entity_types' => array(),
      'settings' => array(
        'allowed_values' => array(
          0 => array(
            'vocabulary' => 'vocabulary_1',
            'parent' => '0',
          ),
        ),
      ),
      'storage' => array(
        'type' => 'field_sql_storage',
        'settings' => array(),
        'module' => 'field_sql_storage',
        'active' => '1',
        'details' => array(
          'sql' => array(
            'FIELD_LOAD_CURRENT' => array(
              'field_data_field_mm_media_tags' => array(
                'tid' => 'field_mm_media_tags_tid',
              ),
            ),
            'FIELD_LOAD_REVISION' => array(
              'field_revision_field_mm_media_tags' => array(
                'tid' => 'field_mm_media_tags_tid',
              ),
            ),
          ),
        ),
      ),
      'foreign keys' => array(
        'tid' => array(
          'table' => 'taxonomy_term_data',
          'columns' => array(
            'tid' => 'tid',
          ),
        ),
      ),
      'indexes' => array(
        'tid' => array(
          0 => 'tid',
        ),
      ),
      'field_name' => 'field_mm_media_tags',
      'type' => 'taxonomy_term_reference',
      'module' => 'taxonomy',
      'active' => '1',
      'locked' => '0',
      'cardinality' => '1',
      'deleted' => '0',
      'columns' => array(
        'tid' => array(
          'type' => 'int',
          'unsigned' => TRUE,
          'not null' => FALSE,
        ),
      ),
      'bundles' => array(
        'node' => array(
          0 => 'mm_media',
        ),
      ),
    ),
    'field_multimedia' => array(
      'translatable' => '0',
      'entity_types' => array(),
      'settings' => array(),
      'storage' => array(
        'type' => 'field_sql_storage',
        'settings' => array(),
        'module' => 'field_sql_storage',
        'active' => '1',
        'details' => array(
          'sql' => array(
            'FIELD_LOAD_CURRENT' => array(
              'field_data_field_multimedia' => array(
                'fid' => 'field_multimedia_fid',
                'title' => 'field_multimedia_title',
                'data' => 'field_multimedia_data',
              ),
            ),
            'FIELD_LOAD_REVISION' => array(
              'field_revision_field_multimedia' => array(
                'fid' => 'field_multimedia_fid',
                'title' => 'field_multimedia_title',
                'data' => 'field_multimedia_data',
              ),
            ),
          ),
        ),
      ),
      'foreign keys' => array(),
      'indexes' => array(
        'fid' => array(
          0 => 'fid',
        ),
      ),
      'field_name' => 'field_multimedia',
      'type' => 'media',
      'module' => 'media',
      'active' => '1',
      'locked' => '0',
      'cardinality' => '-1',
      'deleted' => '0',
      'columns' => array(
        'fid' => array(
          'type' => 'int',
          'unsigned' => TRUE,
          'not null' => FALSE,
        ),
        'title' => array(
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
        ),
        'data' => array(
          'type' => 'text',
          'not null' => FALSE,
          'size' => 'big',
          'serialize' => TRUE,
        ),
      ),
      'bundles' => array(
        'node' => array(
          0 => 'mm_media',
        ),
      ),
    ),
  ),
  'instances' => array(
    'body' => array(
      0 => array(
        'label' => 'Caption',
        'widget' => array(
          'weight' => '2',
          'type' => 'text_textarea_with_summary',
          'module' => 'text',
          'active' => 1,
          'settings' => array(
            'rows' => '20',
            'summary_rows' => 5,
          ),
        ),
        'settings' => array(
          'text_processing' => '1',
          'display_summary' => 1,
          'file_extensions' => '',
          'user_register_form' => FALSE,
        ),
        'display' => array(
          'default' => array(
            'label' => 'hidden',
            'type' => 'text_default',
            'weight' => '1',
            'settings' => array(),
            'module' => 'text',
          ),
          'teaser' => array(
            'label' => 'hidden',
            'type' => 'text_summary_or_trimmed',
            'weight' => '0',
            'settings' => array(
              'trim_length' => 600,
            ),
            'module' => 'text',
          ),
        ),
        'required' => 0,
        'description' => '',
        'default_value' => NULL,
        'field_name' => 'body',
        'entity_type' => 'node',
        'bundle' => 'mm_media',
        'deleted' => '0',
      ),
    ),
    'field_mm_media_tags' => array(
      0 => array(
        'label' => 'Tags',
        'widget' => array(
          'weight' => '3',
          'type' => 'taxonomy_autocomplete',
          'module' => 'taxonomy',
          'active' => 0,
          'settings' => array(
            'size' => 60,
            'autocomplete_path' => 'taxonomy/autocomplete',
          ),
        ),
        'settings' => array(
          'file_extensions' => '',
          'user_register_form' => FALSE,
        ),
        'display' => array(
          'default' => array(
            'label' => 'inline',
            'type' => 'taxonomy_term_reference_link',
            'weight' => '2',
            'settings' => array(),
            'module' => 'taxonomy',
          ),
          'teaser' => array(
            'type' => 'hidden',
            'label' => 'above',
            'settings' => array(),
            'weight' => 0,
          ),
        ),
        'required' => 0,
        'description' => '',
        'default_value' => NULL,
        'field_name' => 'field_mm_media_tags',
        'entity_type' => 'node',
        'bundle' => 'mm_media',
        'deleted' => '0',
      ),
    ),
    'field_multimedia' => array(
      0 => array(
        'label' => 'Upload',
        'widget' => array(
          'weight' => '1',
          'type' => 'media_generic',
          'module' => 'media',
          'active' => 1,
          'settings' => array(
            'allowed_types' => array(
              'audio' => 'audio',
              'image' => 'image',
              'video' => 'video',
              'default' => 'default',
            ),
            'allowed_schemes' => array(
              'flickr' => 'flickr',
              'youtube' => 'youtube',
              'public' => 'public',
              'private' => 'private',
            ),
            'progress_indicator' => 'throbber',
          ),
        ),
        'settings' => array(
          'file_extensions' => 'ab1 abi aif aiff app avi csv dcr dmg doc docx flv gif gz html jar jp2 jpeg jpg kml kmz m4a m4v mov mp3 mp4 mpeg mpg mxd mxp nb nbp pdb pdf pmf png ppsx ppt pptx prs psd rtf seq sit swf tar txt wav webarchive wma wmv wpd wpf xls xlsx xml zip',
          'user_register_form' => FALSE,
        ),
        'display' => array(
          'default' => array(
            'label' => 'hidden',
            'type' => 'media',
            'weight' => '0',
            'settings' => array(
              'file_view_mode' => 'media_original',
            ),
            'module' => 'media',
          ),
          'teaser' => array(
            'label' => 'above',
            'type' => 'hidden',
            'weight' => '0',
            'settings' => array(),
          ),
        ),
        'required' => 0,
        'description' => '',
        'field_name' => 'field_multimedia',
        'entity_type' => 'node',
        'bundle' => 'mm_media',
        'deleted' => '0',
      ),
    ),
  ),
);
