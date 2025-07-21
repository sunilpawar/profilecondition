<?php

use CRM_Profilecondition_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Profilecondition_Upgrader extends CRM_Extension_Upgrader_Base {

  /**
   * Example: Work with entities usually not available during the install step.
   *
   * This method can be used for any post-install tasks. For example, if a step
   * of your installation depends on accessing an entity that is itself
   * created during the installation (e.g., a setting or a managed entity), do
   * that here to avoid order of operation problems.
   */
  public function postInstall(): void {
    $customFieldId = CRM_Core_BAO_CustomField::getCustomFieldID('test_field', 'test_field_group');
    if ($customFieldId) {
      CRM_Core_BAO_CustomField::setIsActive($customFieldId, TRUE);
    }
  }

  /**
   * Example: Run an external SQL script when the module is uninstalled.
   */
  public function uninstall(): void {
    // Clean up any remaining data
    CRM_Core_DAO::executeQuery("DELETE FROM civicrm_profile_condition");
  }

  /**
   * Example: Run a simple query when a module is enabled.
   */
  public function enable(): void {
    CRM_Core_DAO::executeQuery("UPDATE civicrm_profile_condition SET is_active = 1");
  }

  /**
   * Example: Run a simple query when a module is disabled.
   */
  public function disable(): void {
    CRM_Core_DAO::executeQuery("UPDATE civicrm_profile_condition SET is_active = 0");
  }

  /**
   * Example: Run a couple simple queries.
   *
   * @return TRUE on success
   * @throws Exception
   */
  public function upgrade_1001(): bool {
    $this->ctx->log->info('Applying update 1001');

    // Add new column for profile_id if it doesn't exist
    if (!CRM_Core_BAO_SchemaHandler::checkIfFieldExists('civicrm_profile_condition', 'profile_id')) {
      CRM_Core_DAO::executeQuery("
        ALTER TABLE civicrm_profile_condition
        ADD COLUMN profile_id int unsigned NULL COMMENT 'Profile ID if specific to a profile'
        AFTER entity_id
      ");

      // Add foreign key constraint
      CRM_Core_DAO::executeQuery("
        ALTER TABLE civicrm_profile_condition
        ADD CONSTRAINT FK_civicrm_profile_condition_profile_id
        FOREIGN KEY (profile_id) REFERENCES civicrm_uf_group(id)
        ON DELETE CASCADE
      ");
    }

    return TRUE;
  }

  /**
   * Example: Add new field for conditional logic enhancements.
   *
   * @return TRUE on success
   * @throws Exception
   */
  public function upgrade_1002(): bool {
    $this->ctx->log->info('Applying update 1002');

    // Add new columns for enhanced conditional logic
    $columns = [
      'target_field' => "varchar(255) NULL COMMENT 'Target field for conditional logic'",
      'trigger_value' => "text NULL COMMENT 'Value that triggers the condition'",
      'action' => "varchar(32) NULL COMMENT 'Action to take (show, hide, enable, disable)'"
    ];

    foreach ($columns as $columnName => $definition) {
      if (!CRM_Core_BAO_SchemaHandler::checkIfFieldExists('civicrm_profile_condition', $columnName)) {
        CRM_Core_DAO::executeQuery("
          ALTER TABLE civicrm_profile_condition
          ADD COLUMN {$columnName} {$definition}
        ");
      }
    }

    return TRUE;
  }

  /**
   * Example: Add weight column for ordering conditions.
   *
   * @return TRUE on success
   * @throws Exception
   */
  public function upgrade_1003(): bool {
    $this->ctx->log->info('Applying update 1003');

    // Add weight column if it doesn't exist
    if (!CRM_Core_BAO_SchemaHandler::checkIfFieldExists('civicrm_profile_condition', 'weight')) {
      CRM_Core_DAO::executeQuery("
        ALTER TABLE civicrm_profile_condition
        ADD COLUMN weight int DEFAULT 0 COMMENT 'Ordering weight'
      ");

      // Update existing records with incremental weights
      CRM_Core_DAO::executeQuery("
        UPDATE civicrm_profile_condition
        SET weight = id * 10
        WHERE weight IS NULL OR weight = 0
      ");
    }

    return TRUE;
  }

  /**
   * Example: Add indexes for better performance.
   *
   * @return TRUE on success
   * @throws Exception
   */
  public function upgrade_1004(): bool {
    $this->ctx->log->info('Applying update 1004');

    // Add indexes if they don't exist
    $indexes = [
      'index_entity' => ['entity_type', 'entity_id'],
      'index_field' => ['field_name'],
      'index_active' => ['is_active']
    ];

    foreach ($indexes as $indexName => $columns) {
      $columnsList = implode(', ', $columns);
      CRM_Core_DAO::executeQuery("
        CREATE INDEX IF NOT EXISTS {$indexName}
        ON civicrm_profile_condition ({$columnsList})
      ");
    }

    return TRUE;
  }

  /**
   * Example: Add created_date and modified_date columns.
   *
   * @return TRUE on success
   * @throws Exception
   */
  public function upgrade_1005(): bool {
    $this->ctx->log->info('Applying update 1005');

    // Add timestamp columns
    $columns = [
      'created_date' => "timestamp DEFAULT CURRENT_TIMESTAMP",
      'modified_date' => "timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    ];

    foreach ($columns as $columnName => $definition) {
      if (!CRM_Core_BAO_SchemaHandler::checkIfFieldExists('civicrm_profile_condition', $columnName)) {
        CRM_Core_DAO::executeQuery("
          ALTER TABLE civicrm_profile_condition
          ADD COLUMN {$columnName} {$definition}
        ");
      }
    }

    return TRUE;
  }
}
