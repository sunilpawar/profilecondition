<?php

/**
 * Business Access Object for Profile Conditions
 */
class CRM_Profilecondition_BAO_ProfileCondition extends CRM_Profilecondition_DAO_ProfileCondition {

  /**
   * Create a new ProfileCondition based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Profilecondition_DAO_ProfileCondition|NULL
   */
  public static function create($params) {
    $className = 'CRM_Profilecondition_DAO_ProfileCondition';
    $entityName = 'ProfileCondition';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }

  /**
   * Apply profile conditions to a form
   *
   * @param CRM_Core_Form $form
   * @param string $entityType
   * @param int $entityId
   */
  public static function applyConditions(&$form, $entityType, $entityId) {
    $conditions = self::getConditions($entityType, $entityId);

    foreach ($conditions as $condition) {
      switch ($condition['condition_type']) {
        case 'default_value':
          self::applyDefaultValue($form, $condition);
          break;
        case 'visibility':
          self::applyVisibility($form, $condition);
          break;
        case 'readonly':
          self::applyReadonly($form, $condition);
          break;
      }
    }
  }

  /**
   * Apply conditions specific to profile fields
   *
   * @param CRM_Core_Form $form
   * @param string $entityType
   * @param int $entityId
   * @param string $profileName
   */
  public static function applyProfileConditions(&$form, $entityType, $entityId, $profileName) {
    // Get profile ID
    $profileId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_UFGroup', $profileName, 'id', 'name');

    $conditions = self::getConditions($entityType, $entityId, $profileId);

    foreach ($conditions as $condition) {
      switch ($condition['condition_type']) {
        case 'default_value':
          self::applyDefaultValue($form, $condition);
          break;
        case 'visibility':
          self::applyVisibility($form, $condition);
          break;
        case 'readonly':
          self::applyReadonly($form, $condition);
          break;
      }
    }
  }

  /**
   * Get conditions for an entity
   *
   * @param string $entityType
   * @param int $entityId
   * @param int $profileId
   * @return array
   */
  public static function getConditions($entityType, $entityId, $profileId = NULL) {
    $dao = new CRM_Profilecondition_DAO_ProfileCondition();
    $dao->entity_type = $entityType;
    $dao->entity_id = $entityId;
    $dao->is_active = 1;

    if ($profileId) {
      $dao->whereAdd("(profile_id = $profileId OR profile_id IS NULL)");
    }

    $dao->orderBy('weight ASC, id ASC');
    $dao->find();

    $conditions = [];
    while ($dao->fetch()) {
      $conditions[] = [
        'id' => $dao->id,
        'field_name' => $dao->field_name,
        'condition_type' => $dao->condition_type,
        'condition_value' => $dao->condition_value,
        'target_field' => $dao->target_field,
        'trigger_value' => $dao->trigger_value,
        'action' => $dao->action,
        'weight' => $dao->weight,
      ];
    }

    return $conditions;
  }

  /**
   * Get conditional logic for JavaScript
   *
   * @param string $entityType
   * @param int $entityId
   * @return array
   */
  public static function getConditionalLogic($entityType, $entityId) {
    $dao = new CRM_Profilecondition_DAO_ProfileCondition();
    $dao->entity_type = $entityType;
    $dao->entity_id = $entityId;
    $dao->condition_type = 'conditional';
    $dao->is_active = 1;
    $dao->find();

    $conditions = [];
    while ($dao->fetch()) {
      $conditions[] = [
        'field_name' => $dao->field_name,
        'target_field' => $dao->target_field,
        'trigger_value' => $dao->trigger_value,
        'action' => $dao->action,
      ];
    }

    return $conditions;
  }

  /**
   * Apply default value to a field
   *
   * @param CRM_Core_Form $form
   * @param array $condition
   */
  private static function applyDefaultValue(&$form, $condition) {
    $fieldName = $condition['field_name'];
    $defaultValue = $condition['condition_value'];

    if ($form->elementExists($fieldName)) {
      $element = $form->getElement($fieldName);

      // Handle different field types
      switch ($element->getType()) {
        case 'select':
        case 'radio':
          $form->setDefaults([$fieldName => $defaultValue]);
          break;
        case 'checkbox':
          $checkboxValues = json_decode($defaultValue, TRUE);
          if (is_array($checkboxValues)) {
            $form->setDefaults([$fieldName => $checkboxValues]);
          }
          break;
        case 'text':
        case 'textarea':
        default:
          $form->setDefaults([$fieldName => $defaultValue]);
          break;
      }
    }
  }

  /**
   * Apply visibility condition to a field
   *
   * @param CRM_Core_Form $form
   * @param array $condition
   */
  private static function applyVisibility(&$form, $condition) {
    $fieldName = $condition['field_name'];
    $isVisible = ($condition['condition_value'] == '1');

    if ($form->elementExists($fieldName) && !$isVisible) {
      $element = $form->getElement($fieldName);
      $element->freeze();

      // Add CSS to hide the field
      CRM_Core_Resources::singleton()->addStyle("
        .crm-section.{$fieldName}-section { display: none !important; }
      ");
    }
  }

  /**
   * Apply readonly condition to a field
   *
   * @param CRM_Core_Form $form
   * @param array $condition
   */
  private static function applyReadonly(&$form, $condition) {
    $fieldName = $condition['field_name'];
    $isReadonly = ($condition['condition_value'] == '1');

    if ($form->elementExists($fieldName) && $isReadonly) {
      $element = $form->getElement($fieldName);
      $element->freeze();

      // Add visual styling for readonly fields
      CRM_Core_Resources::singleton()->addStyle("
        .crm-section.{$fieldName}-section input,
        .crm-section.{$fieldName}-section select,
        .crm-section.{$fieldName}-section textarea {
          background-color: #f5f5f5;
          border: 1px solid #ddd;
          cursor: not-allowed;
        }
      ");
    }
  }

  /**
   * Delete a ProfileCondition
   *
   * @param int $id
   * @return bool
   */
  public static function del($id) {
    $dao = new CRM_Profilecondition_DAO_ProfileCondition();
    $dao->id = $id;
    if ($dao->find(TRUE)) {
      $dao->delete();
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get available fields for a given entity
   *
   * @param string $entityType
   * @param int $entityId
   * @return array
   */
  public static function getAvailableFields($entityType, $entityId) {
    $fields = [];

    // Get profiles associated with the entity
    $profiles = self::getEntityProfiles($entityType, $entityId);

    foreach ($profiles as $profileId) {
      $profileFields = CRM_Core_BAO_UFGroup::getFields($profileId, FALSE, CRM_Core_Action::VIEW);
      foreach ($profileFields as $fieldName => $fieldInfo) {
        $fields[$fieldName] = $fieldInfo['title'] ?? $fieldName;
      }
    }

    return $fields;
  }

  /**
   * Get profiles associated with an entity
   *
   * @param string $entityType
   * @param int $entityId
   * @return array
   */
  private static function getEntityProfiles($entityType, $entityId) {
    $profiles = [];

    if ($entityType == 'contribution_page') {
      // Get profiles from contribution page
      $dao = CRM_Core_DAO::executeQuery("
        SELECT uf_group_id FROM civicrm_uf_join
        WHERE entity_table = 'civicrm_contribution_page'
        AND entity_id = %1
        AND is_active = 1
      ", [1 => [$entityId, 'Integer']]);

      while ($dao->fetch()) {
        $profiles[] = $dao->uf_group_id;
      }
    }
    elseif ($entityType == 'event') {
      // Get profiles from event
      $dao = CRM_Core_DAO::executeQuery("
        SELECT uf_group_id FROM civicrm_uf_join
        WHERE entity_table = 'civicrm_event'
        AND entity_id = %1
        AND is_active = 1
      ", [1 => [$entityId, 'Integer']]);

      while ($dao->fetch()) {
        $profiles[] = $dao->uf_group_id;
      }
    }

    return $profiles;
  }
  
  /**
   * Get available entity types for profile conditions
   *
   * @return array
   */
  public static function getEntityTypes() {
    return [
      'contribution_page' => E::ts('Contribution Page'),
      'event' => E::ts('Event'),
    ];
  }

  /**
   * Get available condition types
   *
   * @return array
   */
  public static function getConditionTypes() {
    return [
      'default_value' => E::ts('Default Value'),
      'visibility' => E::ts('Visibility'),
      'readonly' => E::ts('Read Only'),
      'conditional' => E::ts('Conditional Logic'),
    ];
  }

  /**
   * Get available actions for conditional logic
   *
   * @return array
   */
  public static function getConditionActions() {
    return [
      'show' => E::ts('Show'),
      'hide' => E::ts('Hide'),
      'enable' => E::ts('Enable'),
      'disable' => E::ts('Disable'),
    ];
  }
}
