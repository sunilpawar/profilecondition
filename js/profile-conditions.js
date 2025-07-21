/**
 * JavaScript for Profile Conditions Extension
 * Handles conditional field logic on contribution pages and event registration forms
 */
(function($, CRM, ts) {
  'use strict';

  var ProfileConditions = {
    conditions: [],
    fieldCache: {},

    /**
     * Initialize the profile conditions
     */
    init: function() {
      // Get conditions from CiviCRM variables
      if (CRM.vars && CRM.vars.profilecondition && CRM.vars.profilecondition.conditions) {
        this.conditions = CRM.vars.profilecondition.conditions;
        this.setupConditions();
      }
    },

    /**
     * Setup all conditions
     */
    setupConditions: function() {
      var self = this;

      // Wait for document ready
      $(document).ready(function() {
        self.cacheFields();
        self.bindEvents();
        self.evaluateAllConditions();
      });
    },

    /**
     * Cache field elements for better performance
     */
    cacheFields: function() {
      var self = this;

      $.each(this.conditions, function(index, condition) {
        // Cache trigger field
        var triggerField = self.findFieldElement(condition.field_name);
        if (triggerField.length) {
          self.fieldCache[condition.field_name] = triggerField;
        }

        // Cache target field
        if (condition.target_field) {
          var targetField = self.findFieldElement(condition.target_field);
          if (targetField.length) {
            self.fieldCache[condition.target_field] = targetField;
          }
        }
      });
    },

    /**
     * Find field element by name (handles various CiviCRM field name patterns)
     */
    findFieldElement: function(fieldName) {
      var selectors = [
        '[name="' + fieldName + '"]',
        '[name="' + fieldName + '[]"]',
        '[name*="[' + fieldName + ']"]',
        '#' + fieldName,
        '.' + fieldName,
        '[data-crm-custom="' + fieldName + '"]'
      ];

      var element = $();
      $.each(selectors, function(index, selector) {
        element = $(selector);
        if (element.length) {
          return false; // Break the loop
        }
      });

      return element;
    },

    /**
     * Get field container (the .crm-section that contains the field)
     */
    getFieldContainer: function(fieldName) {
      var field = this.fieldCache[fieldName] || this.findFieldElement(fieldName);
      if (field.length) {
        var container = field.closest('.crm-section, .form-item, .form-group');
        if (container.length) {
          return container;
        }
        // Fallback: look for parent with field name in class
        return field.closest('[class*="' + fieldName + '"]');
      }
      return $();
    },

    /**
     * Bind change events to trigger fields
     */
    bindEvents: function() {
      var self = this;

      $.each(this.conditions, function(index, condition) {
        var triggerField = self.fieldCache[condition.field_name];
        if (triggerField && triggerField.length) {
          triggerField.on('change keyup blur', function() {
            self.evaluateCondition(condition);
          });
        }
      });
    },

    /**
     * Evaluate all conditions on page load
     */
    evaluateAllConditions: function() {
      var self = this;

      $.each(this.conditions, function(index, condition) {
        self.evaluateCondition(condition);
      });
    },

    /**
     * Evaluate a single condition
     */
    evaluateCondition: function(condition) {
      var triggerField = this.fieldCache[condition.field_name];
      if (!triggerField || !triggerField.length) {
        return;
      }

      var triggerValue = this.getFieldValue(triggerField);
      var shouldTrigger = this.compareValues(triggerValue, condition.trigger_value);

      this.executeAction(condition, shouldTrigger);
    },

    /**
     * Get the value of a field (handles different field types)
     */
    getFieldValue: function(field) {
      var fieldType = field.prop('type') || field.prop('tagName').toLowerCase();

      switch (fieldType) {
        case 'radio':
          return field.filter(':checked').val() || '';

        case 'checkbox':
          if (field.length > 1) {
            // Multiple checkboxes
            var values = [];
            field.filter(':checked').each(function() {
              values.push($(this).val());
            });
            return values;
          } else {
            // Single checkbox
            return field.is(':checked') ? field.val() : '';
          }

        case 'select':
        case 'select-one':
        case 'select-multiple':
          var selected = field.val();
          return $.isArray(selected) ? selected : [selected];

        default:
          return field.val() || '';
      }
    },

    /**
     * Compare trigger value with condition value
     */
    compareValues: function(triggerValue, conditionValue) {
      // Handle arrays (for multi-select and checkboxes)
      if ($.isArray(triggerValue)) {
        return $.inArray(conditionValue, triggerValue) !== -1;
      }

      // Handle string comparison
      return String(triggerValue) === String(conditionValue);
    },

    /**
     * Execute the action based on condition result
     */
    executeAction: function(condition, shouldTrigger) {
      var targetContainer = this.getFieldContainer(condition.target_field);
      if (!targetContainer || !targetContainer.length) {
        return;
      }

      switch (condition.action) {
        case 'show':
          if (shouldTrigger) {
            this.showField(targetContainer);
          } else {
            this.hideField(targetContainer);
          }
          break;

        case 'hide':
          if (shouldTrigger) {
            this.hideField(targetContainer);
          } else {
            this.showField(targetContainer);
          }
          break;

        case 'enable':
          if (shouldTrigger) {
            this.enableField(targetContainer);
          } else {
            this.disableField(targetContainer);
          }
          break;

        case 'disable':
          if (shouldTrigger) {
            this.disableField(targetContainer);
          } else {
            this.enableField(targetContainer);
          }
          break;
      }
    },

    /**
     * Show a field
     */
    showField: function(container) {
      container.removeClass('profile-condition-hidden').show();
      container.find('input, select, textarea').prop('disabled', false);
    },

    /**
     * Hide a field
     */
    hideField: function(container) {
      container.addClass('profile-condition-hidden').hide();
      // Clear values when hiding
      container.find('input[type="text"], textarea').val('');
      container.find('input[type="checkbox"], input[type="radio"]').prop('checked', false);
      container.find('select').prop('selectedIndex', 0);
    },

    /**
     * Enable a field
     */
    enableField: function(container) {
      container.removeClass('profile-condition-disabled');
      container.find('input, select, textarea').prop('disabled', false);
    },

    /**
     * Disable a field
     */
    disableField: function(container) {
      container.addClass('profile-condition-disabled');
      container.find('input, select, textarea').prop('disabled', true);
    },

    /**
     * Debug function to log condition evaluation
     */
    debug: function(message, data) {
      if (window.console && console.log) {
        console.log('[ProfileConditions] ' + message, data || '');
      }
    }
  };

  // Initialize when DOM is ready
  $(document).ready(function() {
    ProfileConditions.init();
  });

  // Also initialize on AJAX complete (for dynamic content)
  $(document).ajaxComplete(function() {
    setTimeout(function() {
      ProfileConditions.init();
    }, 100);
  });

  // Expose for debugging
  window.ProfileConditions = ProfileConditions;

})(CRM.$, CRM, CRM.ts('com.skvare.profilecondition'));
