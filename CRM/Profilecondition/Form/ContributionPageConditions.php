<?php

use CRM_Profilecondition_ExtensionUtil as E;

/**
 * Form controller class for managing profile conditions on contribution pages
 */
class CRM_Profilecondition_Form_ContributionPageConditions extends CRM_Event_Form_ManageEvent {

  /**
   * The contribution page ID
   *
   * @var int
   */
  public $_id;

  /**
   * The action being performed
   *
   * @var int
   */
  public $_action;

  /**
   * The condition ID being edited
   *
   * @var int
   */
  protected $_conditionId;

  /**
   * Set variables up before form is built.
   */
  public function preProcess() {
    $this->_id = CRM_Utils_Request::retrieve('id', 'Positive', $this, TRUE);
    $this->_action = CRM_Utils_Request::retrieve('action', 'String', $this, FALSE, 'browse');
    $this->_conditionId = CRM_Utils_Request::retrieve('cid', 'Positive', $this, FALSE);
    CRM_Core_Error::debug_var('ContributionPageConditions preProcess', [
      'id' => $this->_id,
      'action' => $this->_action,
      'conditionId' => $this->_conditionId,
    ]);
    $this->assign('contributionPageId', $this->_id);
    $this->assign('action', $this->_action);

    // Get contribution page title
    $contributionPage = civicrm_api3('ContributionPage', 'getsingle', ['id' => $this->_id]);
    $this->assign('contributionPageTitle', $contributionPage['title']);

    $this->setPageTitle(E::ts('Profile Conditions - %1', [1 => $contributionPage['title']]));
  }

  /**
   * Build the form object.
   */
  public function buildForm() {
    if ($this->_action == CRM_Core_Action::BROWSE) {
      $this->buildBrowseForm();
    }
    else {
      $this->buildEditForm();
    }

    parent::buildForm();
  }

  /**
   * Build the browse form
   */
  private function buildBrowseForm() {
    $conditions = CRM_Profilecondition_BAO_ProfileCondition::getConditions('contribution_page', $this->_id);
    $this->assign('conditions', $conditions);

    // Add action buttons
    $this->addButtons([
      [
        'type' => 'next',
        'name' => E::ts('Add New Condition'),
        'isDefault' => TRUE,
        'js' => ['onclick' => "location.href='" .
          CRM_Utils_System::url('civicrm/admin/contribute/profileconditions',
            "reset=1&action=add&id={$this->_id}") . "'; return false;"],
      ],
      [
        'type' => 'cancel',
        'name' => E::ts('Done'),
      ],
    ]);
  }

  /**
   * Build the add/edit form
   */
  private function buildEditForm() {
    // Get available fields
    $fields = CRM_Profilecondition_BAO_ProfileCondition::getAvailableFields('contribution_page', $this->_id);
    CRM_Core_Error::debug_var('ContributionPageConditions preProcess', [
      'id' => $this->_id,
      'action' => $this->_action,
      'conditionId' => $this->_conditionId,
    ]);
    $this->add('select', 'field_name', E::ts('Field Name'), $fields, TRUE);

    // Condition types
    $conditionTypes = CRM_Profilecondition_BAO_ProfileCondition::getConditionTypes();
    $this->add('select', 'condition_type', E::ts('Condition Type'), $conditionTypes, TRUE);

    // Condition value
    $this->add('text', 'condition_value', E::ts('Condition Value'));

    // For conditional logic
    $this->add('select', 'target_field', E::ts('Target Field'), $fields);
    $this->add('text', 'trigger_value', E::ts('Trigger Value'));

    $actions = CRM_Profilecondition_BAO_ProfileCondition::getConditionActions();
    $this->add('select', 'action', E::ts('Action'), $actions);

    // Active status
    $this->add('checkbox', 'is_active', E::ts('Is Active?'));

    // Weight
    $this->add('text', 'weight', E::ts('Weight'));

    // Add validation rules
    $this->addFormRule(['CRM_Profilecondition_Form_ContributionPageConditions', 'formRule']);

    $this->addButtons([
      [
        'type' => 'next',
        'name' => E::ts('Save'),
        'isDefault' => TRUE,
      ],
      [
        'type' => 'cancel',
        'name' => E::ts('Cancel'),
      ],
    ]);

    // If editing, set defaults
    if ($this->_conditionId) {
      $defaults = [];
      $dao = new CRM_Profilecondition_DAO_ProfileCondition();
      $dao->id = $this->_conditionId;
      if ($dao->find(TRUE)) {
        $defaults = $dao->toArray();
      }
      $this->setDefaults($defaults);
    }
    else {
      $this->setDefaults(['is_active' => 1, 'weight' => 0]);
    }
  }

  /**
   * Global validation rules for the form.
   *
   * @param array $values
   *
   * @return array
   */
  public static function formRule($values) {
    $errors = [];

    // Validate conditional logic fields
    if ($values['condition_type'] == 'conditional') {
      if (empty($values['target_field'])) {
        $errors['target_field'] = E::ts('Target field is required for conditional logic.');
      }
      if (empty($values['trigger_value'])) {
        $errors['trigger_value'] = E::ts('Trigger value is required for conditional logic.');
      }
      if (empty($values['action'])) {
        $errors['action'] = E::ts('Action is required for conditional logic.');
      }
    }

    return $errors;
  }

  /**
   * Process the form submission.
   */
  public function postProcess() {
    if ($this->_action == CRM_Core_Action::BROWSE) {
      return;
    }

    $values = $this->exportValues();

    $params = [
      'entity_type' => 'contribution_page',
      'entity_id' => $this->_id,
      'field_name' => $values['field_name'],
      'condition_type' => $values['condition_type'],
      'condition_value' => $values['condition_value'] ?? NULL,
      'target_field' => $values['target_field'] ?? NULL,
      'trigger_value' => $values['trigger_value'] ?? NULL,
      'action' => $values['action'] ?? NULL,
      'is_active' => !empty($values['is_active']),
      'weight' => $values['weight'] ?? 0,
    ];

    if ($this->_conditionId) {
      $params['id'] = $this->_conditionId;
    }

    CRM_Profilecondition_BAO_ProfileCondition::create($params);

    CRM_Core_Session::setStatus(E::ts('Profile condition has been saved.'), E::ts('Saved'), 'success');

    $url = CRM_Utils_System::url('civicrm/admin/contribute/profileconditions',
      "reset=1&action=browse&id={$this->_id}");
    CRM_Utils_System::redirect($url);
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    $elementNames = [];
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }
}
