<?php

namespace Civi\Api4;

use Civi\Api4\Generic\DAOEntity;

/**
 * ProfileCondition entity.
 *
 * Provided by the Profile Conditions extension.
 *
 * @package Civi\Api4
 */
class ProfileCondition extends DAOEntity {

  /**
   * @param bool $checkPermissions
   * @return Action\ProfileCondition\Get
   */
  public static function get($checkPermissions = TRUE) {
    return (new Action\ProfileCondition\Get(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return Action\ProfileCondition\Save
   */
  public static function save($checkPermissions = TRUE) {
    return (new Action\ProfileCondition\Save(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return Action\ProfileCondition\Create
   */
  public static function create($checkPermissions = TRUE) {
    return (new Action\ProfileCondition\Create(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return Action\ProfileCondition\Update
   */
  public static function update($checkPermissions = TRUE) {
    return (new Action\ProfileCondition\Update(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return Action\ProfileCondition\Delete
   */
  public static function delete($checkPermissions = TRUE) {
    return (new Action\ProfileCondition\Delete(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return Action\ProfileCondition\Replace
   */
  public static function replace($checkPermissions = TRUE) {
    return (new Action\ProfileCondition\Replace(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return Generic\BasicGetFieldsAction
   */
  public static function getFields($checkPermissions = TRUE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function () {
      return [
        [
          'name' => 'id',
          'data_type' => 'Integer',
          'title' => 'ID',
          'required' => FALSE,
          'readonly' => TRUE,
        ],
        [
          'name' => 'entity_type',
          'data_type' => 'String',
          'title' => 'Entity Type',
          'required' => TRUE,
          'options' => [
            'contribution_page' => 'Contribution Page',
            'event' => 'Event',
          ],
        ],
        [
          'name' => 'entity_id',
          'data_type' => 'Integer',
          'title' => 'Entity ID',
          'required' => TRUE,
        ],
        [
          'name' => 'profile_id',
          'data_type' => 'Integer',
          'title' => 'Profile ID',
          'required' => FALSE,
          'fk_entity' => 'UFGroup',
        ],
        [
          'name' => 'field_name',
          'data_type' => 'String',
          'title' => 'Field Name',
          'required' => TRUE,
        ],
        [
          'name' => 'condition_type',
          'data_type' => 'String',
          'title' => 'Condition Type',
          'required' => TRUE,
          'options' => [
            'default_value' => 'Default Value',
            'visibility' => 'Visibility',
            'readonly' => 'Read Only',
            'conditional' => 'Conditional Logic',
          ],
        ],
        [
          'name' => 'condition_value',
          'data_type' => 'Text',
          'title' => 'Condition Value',
          'required' => FALSE,
        ],
        [
          'name' => 'target_field',
          'data_type' => 'String',
          'title' => 'Target Field',
          'required' => FALSE,
        ],
        [
          'name' => 'trigger_value',
          'data_type' => 'Text',
          'title' => 'Trigger Value',
          'required' => FALSE,
        ],
        [
          'name' => 'action',
          'data_type' => 'String',
          'title' => 'Action',
          'required' => FALSE,
          'options' => [
            'show' => 'Show',
            'hide' => 'Hide',
            'enable' => 'Enable',
            'disable' => 'Disable',
          ],
        ],
        [
          'name' => 'is_active',
          'data_type' => 'Boolean',
          'title' => 'Is Active',
          'required' => FALSE,
          'default_value' => TRUE,
        ],
        [
          'name' => 'weight',
          'data_type' => 'Integer',
          'title' => 'Weight',
          'required' => FALSE,
          'default_value' => 0,
        ],
        [
          'name' => 'created_date',
          'data_type' => 'Timestamp',
          'title' => 'Created Date',
          'required' => FALSE,
          'readonly' => TRUE,
        ],
        [
          'name' => 'modified_date',
          'data_type' => 'Timestamp',
          'title' => 'Modified Date',
          'required' => FALSE,
          'readonly' => TRUE,
        ],
      ];
    }))->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return Action\ProfileCondition\GetFields
   */
  public static function checkAccess($checkPermissions = TRUE) {
    return (new Generic\CheckAccessAction(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @return array
   */
  public static function permissions() {
    return [
      'meta' => ['access CiviCRM'],
      'default' => [
        // Admins can do anything
        ['administer CiviCRM'],
        // Regular users need specific permissions
        'OR',
        ['access CiviContribute', 'edit contributions'],
        ['access CiviEvent', 'edit all events'],
      ],
      'get' => [
        // Anyone with basic access can view
        ['access CiviCRM'],
        'OR',
        ['access CiviContribute'],
        ['access CiviEvent'],
      ],
      'delete' => [
        // Only admins can delete
        ['administer CiviCRM'],
      ],
    ];
  }

  /**
   * Get entity title
   *
   * @return string
   */
  protected static function getEntityTitle() {
    return 'Profile Condition';
  }

  /**
   * Get BAO class
   *
   * @return string
   */
  public static function getBAOClass() {
    return 'CRM_Profilecondition_BAO_ProfileCondition';
  }
}
