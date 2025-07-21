{* Template for browsing profile conditions *}

{if $action eq 1 or $action eq 2 or $action eq 8}
  {include file="CRM/Profilecondition/Form/ConditionEdit.tpl"}
{else}

  <div class="crm-content-block">
    <div class="crm-submit-buttons">
      {include file="CRM/common/formButtons.tpl" location="top"}
    </div>

    <div class="help">
      {ts}Configure default values, visibility, and conditional logic for profile fields on this contribution page.{/ts}
    </div>

    {if $conditions}
      <div class="crm-results-block">
        <div class="crm-results-block-header">
          <h3>{ts}Profile Conditions{/ts}</h3>
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
              <td>{$condition.field_name}</td>
              <td>
                {if $condition.condition_type eq 'default_value'}{ts}Default Value{/ts}
                {elseif $condition.condition_type eq 'visibility'}{ts}Visibility{/ts}
                {elseif $condition.condition_type eq 'readonly'}{ts}Read Only{/ts}
                {elseif $condition.condition_type eq 'conditional'}{ts}Conditional Logic{/ts}
                {/if}
              </td>
              <td>
                {if $condition.condition_type eq 'conditional'}
                  {ts}When{/ts} {$condition.field_name} = {$condition.trigger_value}
                {else}
                  {$condition.condition_value|truncate:50}
                {/if}
              </td>
              <td>{$condition.target_field}</td>
              <td>
                {if $condition.action eq 'show'}{ts}Show{/ts}
                {elseif $condition.action eq 'hide'}{ts}Hide{/ts}
                {elseif $condition.action eq 'enable'}{ts}Enable{/ts}
                {elseif $condition.action eq 'disable'}{ts}Disable{/ts}
                {/if}
              </td>
              <td>
                {if $condition.is_active}
                  <span class="crm-status-enabled">{ts}Enabled{/ts}</span>
                {else}
                  <span class="crm-status-disabled">{ts}Disabled{/ts}</span>
                {/if}
              </td>
              <td>{$condition.weight}</td>
              <td>
                <span class="btn-slide crm-hover-button">
                  {ts}Actions{/ts}
                  <ul class="panel">
                    <li>
                      <a href="{crmURL p='civicrm/admin/contribute/profileconditions' q="action=update&id=`$contributionPageId`&cid=`$condition.id`&reset=1"}"
                         class="action-item crm-hover-button" title="{ts}Edit{/ts}">
                        {ts}Edit{/ts}
                      </a>
                    </li>
                    <li>
                      <a href="{crmURL p='civicrm/admin/contribute/profileconditions' q="action=disable&id=`$contributionPageId`&cid=`$condition.id`&reset=1"}"
                         class="action-item crm-hover-button" title="{if $condition.is_active}{ts}Disable{/ts}{else}{ts}Enable{/ts}{/if}">
                        {if $condition.is_active}{ts}Disable{/ts}{else}{ts}Enable{/ts}{/if}
                      </a>
                    </li>
                    <li>
                      <a href="{crmURL p='civicrm/admin/contribute/profileconditions' q="action=delete&id=`$contributionPageId`&cid=`$condition.id`&reset=1"}"
                         class="action-item crm-hover-button" title="{ts}Delete{/ts}"
                         onclick="return confirm('{ts escape="js"}Are you sure you want to delete this condition?{/ts}');">
                        {ts}Delete{/ts}
                      </a>
                    </li>
                  </ul>
                </span>
              </td>
            </tr>
          {/foreach}
          </tbody>
        </table>
      </div>
    {else}
      <div class="messages status no-popup">
        <div class="icon inform-icon"></div>
        {ts}No profile conditions have been configured for this contribution page.{/ts}
      </div>
    {/if}

    <div class="crm-submit-buttons">
      {include file="CRM/common/formButtons.tpl" location="bottom"}
    </div>
  </div>

{/if}

{* Add CSS for styling *}
<style type="text/css">
  {literal}
  .profile-condition-disabled {
    opacity: 0.6;
    pointer-events: none;
  }

  .profile-condition-hidden {
    display: none !important;
  }

  .crm-status-enabled {
    color: #5cb85c;
    font-weight: bold;
  }

  .crm-status-disabled {
    color: #d9534f;
    font-weight: bold;
  }

  table.selector tbody tr.disabled {
    opacity: 0.6;
  }

  table.selector tbody tr.disabled td {
    text-decoration: line-through;
  }
  {/literal}
</style>
