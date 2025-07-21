{* Template for editing/adding profile conditions *}

<div class="crm-content-block">
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="top"}
  </div>

  <div class="help">
    {if $action eq 1}
      {ts}Add a new profile condition to control field behavior.{/ts}
    {else}
      {ts}Edit the profile condition.{/ts}
    {/if}
  </div>

  <table class="form-layout-compressed">
    <tr class="crm-profilecondition-form-block-field_name">
      <td class="label">{$form.field_name.label}</td>
      <td>{$form.field_name.html}
        <div class="description">{ts}Select the field to apply conditions to.{/ts}</div>
      </td>
    </tr>

    <tr class="crm-profilecondition-form-block-condition_type">
      <td class="label">{$form.condition_type.label}</td>
      <td>{$form.condition_type.html}
        <div class="description">{ts}Choose the type of condition to apply.{/ts}</div>
      </td>
    </tr>

    <tr class="crm-profilecondition-form-block-condition_value condition-value-row">
      <td class="label">{$form.condition_value.label}</td>
      <td>{$form.condition_value.html}
        <div class="description condition-value-help">
          <span class="default-value-help">{ts}Enter the default value for this field.{/ts}</span>
          <span class="visibility-help">{ts}Enter 1 to show the field, 0 to hide it.{/ts}</span>
          <span class="readonly-help">{ts}Enter 1 to make the field read-only, 0 to make it editable.{/ts}</span>
        </div>
      </td>
    </tr>

    <tr class="crm-profilecondition-form-block-target_field conditional-row">
      <td class="label">{$form.target_field.label}</td>
      <td>{$form.target_field.html}
        <div class="description">{ts}Select the field that will be affected by this condition.{/ts}</div>
      </td>
    </tr>

    <tr class="crm-profilecondition-form-block-trigger_value conditional-row">
      <td class="label">{$form.trigger_value.label}</td>
      <td>{$form.trigger_value.html}
        <div class="description">{ts}Enter the value that will trigger this condition.{/ts}</div>
      </td>
    </tr>

    <tr class="crm-profilecondition-form-block-action conditional-row">
      <td class="label">{$form.action.label}</td>
      <td>{$form.action.html}
        <div class="description">{ts}Choose what action to take when the condition is met.{/ts}</div>
      </td>
    </tr>

    <tr class="crm-profilecondition-form-block-is_active">
      <td class="label">{$form.is_active.label}</td>
      <td>{$form.is_active.html}
        <div class="description">{ts}Uncheck to disable this condition without deleting it.{/ts}</div>
      </td>
    </tr>

    <tr class="crm-profilecondition-form-block-weight">
      <td class="label">{$form.weight.label}</td>
      <td>{$form.weight.html}
        <div class="description">{ts}Controls the order in which conditions are processed. Lower numbers are processed first.{/ts}</div>
      </td>
    </tr>
  </table>

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
</div>

{* JavaScript to handle condition type changes *}
<script type="text/javascript">
  {literal}
  CRM.$(function($) {
    var conditionType = $('#condition_type');
    var conditionalRows = $('.conditional-row');
    var conditionValueRow = $('.condition-value-row');
    var conditionValueHelp = $('.condition-value-help span');

    function toggleFieldsByConditionType() {
      var selectedType = conditionType.val();

      // Hide all help text first
      conditionValueHelp.hide();

      if (selectedType === 'conditional') {
        // Show conditional logic fields
        conditionalRows.show();
        conditionValueRow.hide();
      } else {
        // Hide conditional logic fields
        conditionalRows.hide();
        conditionValueRow.show();

        // Show appropriate help text
        if (selectedType === 'default_value') {
          $('.default-value-help').show();
        } else if (selectedType === 'visibility') {
          $('.visibility-help').show();
        } else if (selectedType === 'readonly') {
          $('.readonly-help').show();
        }
      }
    }

    // Initial setup
    toggleFieldsByConditionType();

    // Handle condition type changes
    conditionType.on('change', toggleFieldsByConditionType);
  });
  {/literal}
</script>

{* Add CSS for better styling *}
<style type="text/css">
  {literal}
  .conditional-row {
    display: none;
  }

  .condition-value-help span {
    display: none;
    color: #666;
    font-style: italic;
  }

  .form-layout-compressed td.label {
    vertical-align: top;
    padding-top: 8px;
  }

  .description {
    font-size: 0.9em;
    color: #666;
    margin-top: 4px;
  }
  {/literal}
</style>
