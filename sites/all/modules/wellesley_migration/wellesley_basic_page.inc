<?php

class WellesleyBasicPageMigration extends Migration {
  public function __construct() {
    parent::__construct();
    
    $this->description = t('Migrate basic WELLESLEY pages');
    
    $source_fields = array(
      'nid' => t('The node ID of the page'),
      'linked_files' => t('The set of linked files'),
      'right_side_images' => t('The set of images that previously appeared on the side'),
    );
   //echo WELLESLEY_MIGRATION_DATABASE_NAME;
   //clean up body content first
   db_query("update field_data_body set body_value = (replace(body_value, '/d6import', ''))");

    $query = Database::getConnection('default', 'for_migration')
      ->select(WELLESLEY_MIGRATION_DATABASE_NAME .'.node', 'n')
      ->fields('n', array('nid', 'vid', 'type', 'language', 'title', 'uid', 'status', 'created', 'changed', 'comment', 'promote', 'moderate', 'sticky', 'tnid', 'translate'))
      ->condition('n.type', 'page', '=');
    $query->join(WELLESLEY_MIGRATION_DATABASE_NAME .'.node_revisions', 'nr', 'n.vid = nr.vid');
    $query->addField('nr', 'body');
    $query->addField('nr', 'teaser');
    $query->join(WELLESLEY_MIGRATION_DATABASE_NAME .'.users', 'u', 'n.uid = u.uid');
    $query->addField('u', 'name');
	$query->join(WELLESLEY_MIGRATION_DATABASE_NAME .'.url_alias', 'url', 'n.nid = substring(url.src,6)');
    $query->addField('url', 'dst');
    $query->orderBy('n.changed');
    
    $this->highwaterField = array(
      'name' => 'changed', // Column to be used as highwater mark
      'alias' => 'n',           // Table alias containing that column
    );
    
    $this->source = new MigrateSourceSQL($query, $source_fields);
    $this->destination = new MigrateDestinationNode('page');
    
    $this->map = new MigrateSQLMap($this->machineName,
      array(
        'nid' => array(
          'type' => 'int',
          'unsigned' => TRUE,
          'not null' => TRUE,
          'description' => 'D6 Unique Node ID',
          'alias' => 'n',
        )
      ),
      MigrateDestinationNode::getKeySchema(),
      'for_migration'
    );
    
    // Make the mappings
    $this->addFieldMapping('title', 'title');
	
    $this->addFieldMapping('is_new')->defaultValue(TRUE);
    $this->addFieldMapping('uid', 'uid');
    $this->addFieldMapping('revision')->defaultValue(TRUE);
    $this->addFieldMapping('revision_uid', 'uid');
    $this->addFieldMapping('created', 'created');
    $this->addFieldMapping('changed', 'changed');
    $this->addFieldMapping('status', 'status');
    $this->addFieldMapping('promote', 'promote');
    $this->addFieldMapping('sticky', 'sticky');
    $this->addFieldMapping('comment', 'comment');
    $this->addFieldMapping('language')->defaultValue('und');
	
	//$this->addFieldMapping('path', 'dst');
    //$this->addFieldMapping('path')->issueGroup(t('DNM'));
    $this->addFieldMapping('pathauto_perform_alias')->defaultValue('0');
	
	
    // Since the excerpt is mapped via an argument, add a null mapping so it's
    // not flagged as unmapped
    //$this->addFieldMapping(NULL, 'excerpt');
    $body_arguments = MigrateTextFieldHandler::arguments(NULL, filter_default_format());
    $this->addFieldMapping('body', 'body')
      ->arguments($body_arguments);

    $body_arguments = MigrateTextFieldHandler::arguments(NULL, filter_default_format());
    //$this->addFieldMapping('field_content_teaser', 'teaser')
      //->arguments($body_arguments);
    
    //$associated_file_arguments = MigrateFileFieldHandler::arguments(NULL, 'file_copy', FILE_EXISTS_RENAME);
    
    //$this->addFieldMapping('field_content_associated_images', 'right_side_images')
      //->arguments($associated_file_arguments);
      
    //$this->addFieldMapping('field_content_linked_files', 'linked_files')
      //->arguments($associated_file_arguments);
    
    $this->addFieldMapping(NULL, 'name');
    $this->addFieldMapping(NULL, 'vid');
    $this->addFieldMapping(NULL, 'type');
    $this->addFieldMapping(NULL, 'language');
    $this->addFieldMapping(NULL, 'moderate');
    $this->addFieldMapping(NULL, 'tnid');
    $this->addFieldMapping(NULL, 'translate');
  }
  
  public function prepareRow($current_row) {
    $current_row->uid = $current_row->revision_uid = wellesley_migration_retrieve_uid($current_row->name);
    //$current_row->right_side_images = wellesley_migration_retrieve_right_side_images($current_row->nid);
    //$current_row->linked_files = wellesley_migration_retrieve_linked_files($current_row->nid);

    return TRUE;
  }
}
