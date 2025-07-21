{* Template for browsing profile conditions on events *}

{if $action eq 1 or $action eq 2 or $action eq 8}
  {include file="CRM/Profilecondition/Form/ConditionEdit.tpl"}
{else}

  <div class="crm-content-block crm-profilecondition-browse">
    <div class="crm-submit-buttons">
      {include file="CRM/common/formButtons.tpl" location="top"}
    </div>

    <div class="help">
      {ts}Configure default values, visibility, and conditional logic for profile fields on this event registration form.{/ts}
    </div>

    {if $conditions}
      <div class="crm-results-block">
        <div class="crm-results-block-header">
          <h3>{ts}Profile Conditions for Event: {$eventTitle}{/ts}</h3>
        </div>

        <table class="selector row-highlight">
          <thead class="sticky">
          <tr>
            <th scope="col">{ts}Field{/ts}</th>
            <th scope="col">{ts}Condition Type{/ts}</th>
            <th scope="col">{ts}Value{/ts}</th>
            <th scope="col">{ts}Target Field{/ts}</th>
            <th scope="col">{ts}Action{/ts}</th>
            <th scope="col">{ts}Status{/ts}</th>
            <th scope="col">{ts}Weight{/ts}</th>
            <th scope="col">{ts}Actions{/ts}</th>
          </tr>
          </thead>
          <tbody>
          {foreach from=$conditions item=condition}
            <tr class="{cycle values="odd-row,even-row"} {if !$condition.is_active}disabled{/if}">
              <td class="profile-condition-field">
                <strong>{$condition.field_name}</strong>
                {if $condition.condition_type eq 'conditional'}
                  <div class="profile-condition-group conditional">
                    <small>{ts}Trigger Field{/ts}</small>
                  </div>
                {/if}
              </td>
              <td>
                <span class="profile-condition-type">
                  {if $condition.condition_type eq 'default_value'}
                    <i class="crm-i fa-edit"></i> {ts}Default Value{/ts}
                  {elseif $condition.condition_type eq 'visibility'}
                    <i class="crm-i fa-eye"></i> {ts}Visibility{/ts}
                  {elseif $condition.condition_type eq 'readonly'}
                    <i class="crm-i fa-lock"></i> {ts}Read Only{/ts}
                  {elseif $condition.condition_type eq 'conditional'}
                    <i class="crm-i fa-code-fork"></i> {ts}Conditional Logic{/ts}
                  {/if}
                </span>
              </td>
              <td>
                {if $condition.condition_type eq 'conditional'}
                  <code>
                    {ts}When{/ts} <strong>{$condition.field_name}</strong> =
                    <em>"{$condition.trigger_value}"</em>
                  </code>
                {else}
                  <span class="profile-condition-value">
                    {$condition.condition_value|truncate:50:"...":true}
                  </span>
                {/if}
              </td>
              <td>
                {if $condition.target_field}
                  <strong>{$condition.target_field}</strong>
                {else}
                  <span class="crm-no-data">-</span>
                {/if}
              </td>
              <td>
                {if $condition.action}
                  <span class="profile-condition-action action-{$condition.action}">
                    {if $condition.action eq 'show'}
                      <i class="crm-i fa-eye"></i> {ts}Show{/ts}
                    {elseif $condition.action eq 'hide'}
                      <i class="crm-i fa-eye-slash"></i> {ts}Hide{/ts}
                    {elseif $condition.action eq 'enable'}
                      <i class="crm-i fa-unlock"></i> {ts}Enable{/ts}
                    {elseif $condition.action eq 'disable'}
                      <i class="crm-i fa-lock"></i> {ts}Disable{/ts}
                    {/if}
                  </span>
                {else}
                  <span class="crm-no-data">-</span>
                {/if}
              </td>
              <td>
                {if $condition.is_active}
                  <span class="crm-status-enabled">
                    <i class="crm-i fa-check-circle"></i> {ts}Enabled{/ts}
                  </span>
                {else}
                  <span class="crm-status-disabled">
                    <i class="crm-i fa-times-circle"></i> {ts}Disabled{/ts}
                  </span>
                {/if}
              </td>
              <td>
                <span class="profile-condition-weight">{$condition.weight}</span>
              </td>
              <td>
                <span class="btn-slide crm-hover-button">
                  {ts}Actions{/ts}
                  <ul class="panel">
                    <li>
                      <a href="{crmURL p='civicrm/event/manage/profileconditions' q="action=update&id=`$eventId`&cid=`$condition.id`&reset=1"}"
                         class="action-item crm-hover-button" title="{ts}Edit{/ts}">
                        <i class="crm-i fa-pencil"></i> {ts}Edit{/ts}
                      </a>
                    </li>
                    <li>
                      <a href="{crmURL p='civicrm/event/manage/profileconditions' q="action=disable&id=`$eventId`&cid=`$condition.id`&reset=1"}"
                         class="action-item crm-hover-button"
                         title="{if $condition.is_active}{ts}Disable{/ts}{else}{ts}Enable{/ts}{/if}">
                        {if $condition.is_active}
                          <i class="crm-i fa-times"></i> {ts}Disable{/ts}
                        {else}
                          <i class="crm-i fa-check"></i> {ts}Enable{/ts}
                        {/if}
                      </a>
                    </li>
                    <li>
                      <a href="#" class="action-item crm-hover-button duplicate-condition"
                         data-condition-id="{$condition.id}" title="{ts}Duplicate{/ts}">
                        <i class="crm-i fa-copy"></i> {ts}Duplicate{/ts}
                      </a>
                    </li>
                    <li>
                      <a href="{crmURL p='civicrm/event/manage/profileconditions' q="action=delete&id=`$eventId`&cid=`$condition.id`&reset=1"}"
                         class="action-item crm-hover-button" title="{ts}Delete{/ts}"
                         onclick="return confirm('{ts escape="js"}Are you sure you want to delete this condition?{/ts}');">
                        <i class="crm-i fa-trash"></i> {ts}Delete{/ts}
                      </a>
                    </li>
                  </ul>
                </span>
              </td>
            </tr>
          {/foreach}
          </tbody>
        </table>

        <div class="crm-content-block-actions">
          <div class="action-link">
            <a href="{crmURL p='civicrm/event/manage/profileconditions' q="action=add&id=`$eventId`&reset=1"}"
               class="button crm-popup">
              <i class="crm-i fa-plus"></i> {ts}Add New Condition{/ts}
            </a>
          </div>
        </div>
      </div>
    {else}
      <div class="messages status no-popup">
        <div class="icon inform-icon"></div>
        <p>
          {ts}No profile conditions have been configured for this event registration form.{/ts}
        </p>
        <div class="action-link">
          <a href="{crmURL p='civicrm/event/manage/profileconditions' q="action=add&id=`$eventId`&reset=1"}"
             class="button">
            <i class="crm-i fa-plus"></i> {ts}Add Your First Condition{/ts}
          </a>
        </div>
      </div>
    {/if}

    <div class="crm-submit-buttons">
      {include file="CRM/common/formButtons.tpl" location="bottom"}
    </div>
  </div>

{/if}

{* JavaScript for enhanced functionality *}
<script type="text/javascript">
  {literal}
  CRM.$(function($) {
    // Handle duplicate condition functionality
    $('.duplicate-condition').on('click', function(e) {
      e.preventDefault();
      var conditionId = $(this).data('condition-id');

      CRM.confirm({
        title: {/literal}'{ts escape="js"}Duplicate Condition{/ts}'{literal},
        message: {/literal}'{ts escape="js"}This will create a copy of the selected condition. You can then modify the duplicate as needed.{/ts}'{literal}
      }).on('crmConfirm:yes', function() {
        // Make AJAX call to duplicate the condition
        CRM.api4('ProfileCondition', 'get', {
          where: [['id', '=', conditionId]]
        }).then(function(result) {
          if (result.length > 0) {
            var condition = result[0];
            delete condition.id; // Remove ID so it creates new record
            condition.field_name = condition.field_name + '_copy';
            condition.weight = condition.weight + 1;

            CRM.api4('ProfileCondition', 'create', {
              values: condition
            }).then(function() {
              CRM.alert({/literal}'{ts escape="js"}Condition duplicated successfully{/ts}'{literal}, {/literal}'{ts escape="js"}Success{/ts}'{literal}, 'success');
              location.reload();
            });
          }
        });
      });
    });

    // Add sortable functionality for weight ordering
    if ($('table.selector tbody tr').length > 1) {
      $('table.selector tbody').sortable({
        handle: '.profile-condition-weight',
        cursor: 'move',
        update: function(event, ui) {
          var newOrder = [];
          $(this).find('tr').each(function(index) {
            var conditionId = $(this).find('.duplicate-condition').data('condition-id');
            if (conditionId) {
              newOrder.push({
                id: conditionId,
                weight: (index + 1) * 10
              });
            }
          });

          // Update weights via API
          CRM.api4('ProfileCondition', 'save', {
            records: newOrder
          }).then(function() {
            CRM.alert({/literal}'{ts escape="js"}Order updated{/ts}'{literal}, '', 'success');
          });
        }
      }).disableSelection();

      // Add visual indicator for sortable rows
      $('table.selector tbody tr').addClass('sortable-row');
      $('.profile-condition-weight').attr('title', {/literal}'{ts escape="js"}Drag to reorder{/ts}'{literal}).css('cursor', 'move');
    }
  });
  {/literal}
</script>

{* Enhanced CSS for better styling *}
<style type="text/css">
  {literal}
  .profile-condition-field strong {
    color: #2c5aa0;
  }

  .profile-condition-type i {
    margin-right: 4px;
  }

  .profile-condition-value {
    font-family: monospace;
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
    border: 1px solid #e9ecef;
  }

  .profile-condition-action i {
    margin-right: 4px;
  }

  .profile-condition-action.action-show { color: #28a745; }
  .profile-condition-action.action-hide { color: #dc3545; }
  .profile-condition-action.action-enable { color: #007bff; }
  .profile-condition-action.action-disable { color: #6c757d; }

  .sortable-row {
    transition: background-color 0.2s;
  }

  .sortable-row:hover {
    background-color: #f8f9fa;
  }

  .ui-sortable-helper {
    background: white;
    border: 1px solid #007cba;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  }

  .crm-no-data {
    color: #6c757d;
    font-style: italic;
  }

  code {
    background: #f1f3f4;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 0.9em;
    border: 1px solid #e8eaed;
  }

  code strong {
    color: #1a73e8;
  }

  code em {
    color: #137333;
  }

  .crm-content-block-actions {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #e8eaed;
  }
  {/literal}
</style>
