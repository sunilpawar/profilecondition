<?php

require_once 'profilecondition.civix.php';

use CRM_Profilecondition_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function profilecondition_civicrm_config(&$config): void {
  _profilecondition_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function profilecondition_civicrm_install(): void {
  _profilecondition_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function profilecondition_civicrm_enable(): void {
  _profilecondition_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_tabset().
 *
 * Add Profile Conditions tab to Contribution Pages and Events.
 */
function profilecondition_civicrm_tabset($tabsetName, &$tabs, $context) {
  if ($tabsetName == 'civicrm/admin/contribute' && !empty($context['contribution_page_id'])) {
    $tabs['profileconditions'] = [
      'title' => E::ts('Profile Conditions'),
      'link' => CRM_Utils_System::url('civicrm/admin/contribute/profileconditions',
        "reset=1&action=browse&id={$context['contribution_page_id']}"),
      'valid' => TRUE,
      'active' => TRUE,
      'current' => FALSE,
    ];
    CRM_Core_Error::debug_var('profilecondition_civicrm_tabset tab', $tabs['profileconditions']);
  }

  if ($tabsetName == 'civicrm/event/manage' && !empty($context['event_id'])) {
    $tabs['profileconditions'] = [
      'title' => E::ts('Profile Conditions'),
      'link' => CRM_Utils_System::url('civicrm/event/manage/profileconditions',
        "reset=1&action=browse&id={$context['event_id']}"),
      'valid' => TRUE,
      'active' => TRUE,
      'current' => FALSE,
    ];
  }
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * Apply profile conditions to forms.
 */
function profilecondition_civicrm_buildForm($formName, &$form) {
  // Apply conditions to contribution pages
  if ($formName == 'CRM_Contribute_Form_Contribution_Main' ||
    $formName == 'CRM_Event_Form_Registration_Register') {

    $entityType = ($formName == 'CRM_Contribute_Form_Contribution_Main') ? 'contribution_page' : 'event';
    $entityId = ($formName == 'CRM_Contribute_Form_Contribution_Main') ?
      $form->_id : $form->_eventId;

    if ($entityId) {
      CRM_Profilecondition_BAO_ProfileCondition::applyConditions($form, $entityType, $entityId);
    }
  }
}

/**
 * Implements hook_civicrm_buildProfile().
 *
 * Apply conditions to profile fields.
 */
function profilecondition_civicrm_buildProfile($profileName, &$form) {
  // Get the entity context
  $entityType = NULL;
  $entityId = NULL;

  if (property_exists($form, '_id') && get_class($form) == 'CRM_Contribute_Form_Contribution_Main') {
    $entityType = 'contribution_page';
    $entityId = $form->_id;
  }
  elseif (property_exists($form, '_eventId') &&
    strpos(get_class($form), 'CRM_Event_Form_Registration') === 0) {
    $entityType = 'event';
    $entityId = $form->_eventId;
  }

  if ($entityType && $entityId) {
    CRM_Profilecondition_BAO_ProfileCondition::applyProfileConditions($form, $entityType, $entityId, $profileName);
  }
}

/**
 * Implements hook_civicrm_pageRun().
 *
 * Add JavaScript for conditional logic.
 */
function profilecondition_civicrm_pageRun(&$page) {
  $pageName = get_class($page);
  if ($pageName == 'CRM_Contribute_Page_ContributionPage' ||
    $pageName == 'CRM_Event_Page_EventInfo') {
    $entityType = ($pageName == 'CRM_Contribute_Page_ContributionPage') ? 'contribution_page' : 'event';
    $entityId = ($pageName == 'CRM_Contribute_Page_ContributionPage') ?
      $page->getvar('_id'): $page->getvar('_eventId');
    CRM_Core_Error::debug_var('pageRun Entity Type', $entityType);
    CRM_Core_Error::debug_var('pageRun Entity ID', $entityId);
    if ($entityId) {
      $conditions = CRM_Profilecondition_BAO_ProfileCondition::getConditionalLogic($entityType, $entityId);
      if (!empty($conditions)) {
        CRM_Core_Resources::singleton()
          ->addScriptFile('com.skvare.profilecondition', 'js/profile-conditions.js')
          ->addVars('profilecondition', ['conditions' => $conditions]);
      }
    }
  }
}
