<?php

/**
 * Data Access Object for ProfileCondition entity
 */
class CRM_Profilecondition_DAO_ProfileCondition extends CRM_Core_DAO {

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_profile_condition';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique ProfileCondition ID
   *
   * @var int
   */
  public $id;

  /**
   * Type of entity (contribution_page, event)
   *
   * @var string
   */
  public $entity_type;

  /**
   * ID of the entity
   *
   * @var int
   */
  public $entity_id;

  /**
   * Profile ID if specific to a profile
   *
   * @var int
   */
  public $profile_id;

  /**
   * Field name in the profile
   *
   * @var string
   */
  public $field_name;

  /**
   * Type of condition (default_value, visibility, readonly, conditional)
   *
   * @var string
   */
  public $condition_type;

  /**
   * Value for the condition (JSON for complex conditions)
   *
   * @var string
   */
  public $condition_value;

  /**
   * Target field for conditional logic
   *
   * @var string
   */
  public $target_field;

  /**
   * Value that triggers the condition
   *
   * @var string
   */
  public $trigger_value;

  /**
   * Action to take (show, hide, enable, disable)
   *
   * @var string
   */
  public $action;

  /**
   * Is this condition active?
   *
   * @var bool
   */
  public $is_active;

  /**
   * Ordering weight
   *
   * @var int
   */
  public $weight;

  /**
   * Created date
   *
   * @var datetime
   */
  public $created_date;

  /**
   * Modified date
   *
   * @var timestamp
   */
  public $modified_date;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_profile_condition';
    parent::__construct();
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => CRM_Profilecondition_ExtensionUtil::ts('ID'),
          'description' => CRM_Profilecondition_ExtensionUtil::ts('Unique ProfileCondition ID'),
          'required' => TRUE,
          'where' => 'civicrm_profile_condition.id',
          'table_name' => 'civicrm_profile_condition',
          'entity' => 'ProfileCondition',
          'bao' => 'CRM_Profilecondition_DAO_ProfileCondition',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => '1.0',
        ],
        'entity_type' => [
          'name' => 'entity_type',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => CRM_Profilecondition_ExtensionUtil::ts('Entity Type'),
          'description' => CRM_Profilecondition_ExtensionUtil::ts('Type of entity (contribution_page, event)'),
          'required' => TRUE,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_profile_condition.entity_type',
          'table_name' => 'civicrm_profile_condition',
          'entity' => 'ProfileCondition',
          'bao' => 'CRM_Profilecondition_DAO_ProfileCondition',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'callback' => 'CRM_Profilecondition_BAO_ProfileCondition::getEntityTypes',
          ],
          'add' => '1.0',
        ],
        'entity_id' => [
          'name' => 'entity_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => CRM_Profilecondition_ExtensionUtil::ts('Entity ID'),
          'description' => CRM_Profilecondition_ExtensionUtil::ts('ID of the entity'),
          'required' => TRUE,
          'where' => 'civicrm_profile_condition.entity_id',
          'table_name' => 'civicrm_profile_condition',
          'entity' => 'ProfileCondition',
          'bao' => 'CRM_Profilecondition_DAO_ProfileCondition',
          'localizable' => 0,
          'html' => [
            'type' => 'EntityRef',
          ],
          'add' => '1.0',
        ],
        'profile_id' => [
          'name' => 'profile_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => CRM_Profilecondition_ExtensionUtil::ts('Profile ID'),
          'description' => CRM_Profilecondition_ExtensionUtil::ts('Profile ID if specific to a profile'),
          'where' => 'civicrm_profile_condition.profile_id',
          'table_name' => 'civicrm_profile_condition',
          'entity' => 'ProfileCondition',
          'bao' => 'CRM_Profilecondition_DAO_ProfileCondition',
          'localizable' => 0,
          'FKClassName' => 'CRM_Core_DAO_UFGroup',
          'html' => [
            'type' => 'EntityRef',
          ],
          'add' => '1.0',
        ],
        'field_name' => [
          'name' => 'field_name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => CRM_Profilecondition_ExtensionUtil::ts('Field Name'),
          'description' => CRM_Profilecondition_ExtensionUtil::ts('Field name in the profile'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_profile_condition.field_name',
          'table_name' => 'civicrm_profile_condition',
          'entity' => 'ProfileCondition',
          'bao' => 'CRM_Profilecondition_DAO_ProfileCondition',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => '1.0',
        ],
        'condition_type' => [
          'name' => 'condition_type',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => CRM_Profilecondition_ExtensionUtil::ts('Condition Type'),
          'description' => CRM_Profilecondition_ExtensionUtil::ts('Type of condition'),
          'required' => TRUE,
          'maxlength' => 32,
          'size' => CRM_Utils_Type::MEDIUM,
          'where' => 'civicrm_profile_condition.condition_type',
          'table_name' => 'civicrm_profile_condition',
          'entity' => 'ProfileCondition',
          'bao' => 'CRM_Profilecondition_DAO_ProfileCondition',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'callback' => 'CRM_Profilecondition_BAO_ProfileCondition::getConditionTypes',
          ],
          'add' => '1.0',
        ],
        'is_active' => [
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => CRM_Profilecondition_ExtensionUtil::ts('Is Active'),
          'description' => CRM_Profilecondition_ExtensionUtil::ts('Is this condition active?'),
          'where' => 'civicrm_profile_condition.is_active',
          'default' => '1',
          'table_name' => 'civicrm_profile_condition',
          'entity' => 'ProfileCondition',
          'bao' => 'CRM_Profilecondition_DAO_ProfileCondition',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => '1.0',
        ],
        'weight' => [
          'name' => 'weight',
          'type' => CRM_Utils_Type::T_INT,
          'title' => CRM_Profilecondition_ExtensionUtil::ts('Weight'),
          'description' => CRM_Profilecondition_ExtensionUtil::ts('Ordering weight'),
          'where' => 'civicrm_profile_condition.weight',
          'default' => '0',
          'table_name' => 'civicrm_profile_condition',
          'entity' => 'ProfileCondition',
          'bao' => 'CRM_Profilecondition_DAO_ProfileCondition',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => '1.0',
        ],
      ];
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'profile_condition', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'profile_condition', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [
      'index_entity' => [
        'name' => 'index_entity',
        'field' => [
          0 => 'entity_type',
          1 => 'entity_id',
        ],
        'localizable' => FALSE,
        'sig' => 'civicrm_profile_condition::0::entity_type::entity_id',
      ],
      'index_field' => [
        'name' => 'index_field',
        'field' => [
          0 => 'field_name',
        ],
        'localizable' => FALSE,
        'sig' => 'civicrm_profile_condition::0::field_name',
      ],
      'index_active' => [
        'name' => 'index_active',
        'field' => [
          0 => 'is_active',
        ],
        'localizable' => FALSE,
        'sig' => 'civicrm_profile_condition::0::is_active',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }
}
