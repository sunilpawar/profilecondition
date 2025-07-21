<?php

namespace Civi\Api4\Action\ProfileCondition;

use Civi\Api4\Generic\Result;
use Civi\Api4\Generic\AbstractAction;

/**
 * Get available fields for profile conditions based on entity type and ID.
 *
 * @method string getEntityType()
 * @method $this setEntityType(string $entityType)
 * @method int getEntityId()
 * @method $this setEntityId(int $entityId)
 */
class GetAvailableFields extends AbstractAction {

  /**
   * Entity type (contribution_page or event)
   *
   * @var string
   * @required
   */
  protected $entityType;

  /**
   * Entity ID
   *
   * @var int
   * @required
   */
  protected $entityId;

  /**
   * @param Result $result
   * @return Result
   */
  public function _run(Result $result) {
    $this->_validateParams();

    $fields = \CRM_Profilecondition_BAO_ProfileCondition::getAvailableFields(
      $this->entityType,
      $this->entityId
    );

    foreach ($fields as $fieldName => $fieldLabel) {
      $result[] = [
        'field_name' => $fieldName,
        'field_label' => $fieldLabel,
        'field_type' => $this->_getFieldType($fieldName),
      ];
    }

    return $result;
  }

  /**
   * Validate required parameters
   *
   * @throws \API_Exception
   */
  private function _validateParams() {
    if (empty($this->entityType)) {
      throw new \API_Exception('Entity type is required');
    }

    if (empty($this->entityId)) {
      throw new \API_Exception('Entity ID is required');
    }

    $validEntityTypes = ['contribution_page', 'event'];
    if (!in_array($this->entityType, $validEntityTypes)) {
      throw new \API_Exception('Invalid entity type. Must be one of: ' . implode(', ', $validEntityTypes));
    }
  }

  /**
   * Get field type for a given field name
   *
   * @param string $fieldName
   * @return string
   */
  private function _getFieldType($fieldName) {
    // Get field info from CiviCRM's field metadata
    try {
      // Check if it's a custom field
      if (strpos($fieldName, 'custom_') === 0) {
        $customFieldId = substr($fieldName, 7);
        $customField = \CRM_Core_BAO_CustomField::getCustomFieldMetaData($customFieldId);
        if ($customField) {
          return $customField['data_type'];
        }
      }

      // Check core fields
      $coreFields = [
        'first_name' => 'String',
        'last_name' => 'String',
        'email' => 'String',
        'phone' => 'String',
        'street_address' => 'String',
        'city' => 'String',
        'state_province' => 'Select',
        'country' => 'Select',
        'postal_code' => 'String',
        'gender_id' => 'Select',
        'birth_date' => 'Date',
        'do_not_email' => 'Boolean',
        'do_not_phone' => 'Boolean',
        'is_opt_out' => 'Boolean',
      ];

      if (isset($coreFields[$fieldName])) {
        return $coreFields[$fieldName];
      }

    }
    catch (\Exception $e) {
      // Log error but continue
      \Civi::log()->warning('Could not determine field type for: ' . $fieldName);
    }

    return 'String'; // Default fallback
  }

  /**
   * Get available entity types
   *
   * @return array
   */
  public static function getEntityTypes() {
    return [
      'contribution_page' => 'Contribution Page',
      'event' => 'Event',
    ];
  }
}
