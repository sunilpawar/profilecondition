# Profile Conditions Extension (com.skvare.profilecondition)

## Overview

The Profile Conditions extension for CiviCRM allows you to set default values, control field visibility, make fields read-only, and create conditional logic for profile fields on contribution pages and event registration forms.

## Features

- **Default Values**: Set default values for any profile field
- **Field Visibility**: Show or hide fields based on conditions
- **Read-Only Fields**: Make specific fields non-editable
- **Conditional Logic**: Show/hide or enable/disable fields based on the values of other fields
- **Entity Support**: Works with both Contribution Pages and Events
- **Weight-Based Ordering**: Control the order in which conditions are processed
- **Easy Management**: User-friendly interface to manage all conditions

## Requirements

- CiviCRM 5.74 or higher
- PHP 7.4 or higher

## Installation

### Manual Installation
1. Download the extension
2. Extract to your CiviCRM extensions directory
3. Navigate to **Administer » System Settings » Extensions**
4. Find "Profile Conditions" and click **Install**

### Command Line Installation
```bash
cv ext:download com.skvare.profilecondition
cv ext:enable com.skvare.profilecondition
```

## Usage

### For Contribution Pages

1. Navigate to **Contributions » Manage Contribution Pages**
2. Click **Configure** for your contribution page
3. Go to the **Profile Conditions** tab
4. Click **Add New Condition**
5. Configure your condition settings

### For Events

1. Navigate to **Events » Manage Events**
2. Click **Configure** for your event
3. Go to the **Profile Conditions** tab
4. Click **Add New Condition**
5. Configure your condition settings

## Condition Types

### 1. Default Value
Sets a default value for a field when the form loads.

**Example**: Set default country to "United States"
- Field Name: `country`
- Condition Type: `Default Value`
- Condition Value: `1228` (Country ID for US)

### 2. Visibility
Controls whether a field is visible or hidden.

**Example**: Hide a field by default
- Field Name: `custom_field_1`
- Condition Type: `Visibility`
- Condition Value: `0` (0 = hidden, 1 = visible)

### 3. Read Only
Makes a field non-editable while still displaying its value.

**Example**: Make email field read-only
- Field Name: `email`
- Condition Type: `Read Only`
- Condition Value: `1` (1 = read-only, 0 = editable)

### 4. Conditional Logic
Show/hide or enable/disable fields based on the value of another field.

**Example**: Show "Other" text field when "Other" is selected from a dropdown
- Field Name: `interest_type` (trigger field)
- Condition Type: `Conditional Logic`
- Target Field: `other_interest` (field to be affected)
- Trigger Value: `Other`
- Action: `Show`

## API Usage

The extension provides API4 entities for programmatic access:

### Get Profile Conditions
```php
$conditions = \Civi\Api4\ProfileCondition::get()
  ->addWhere('entity_type', '=', 'contribution_page')
  ->addWhere('entity_id', '=', 1)
  ->addWhere('is_active', '=', TRUE)
  ->execute();
```

### Create a New Condition
```php
$condition = \Civi\Api4\ProfileCondition::create()
  ->addValue('entity_type', 'contribution_page')
  ->addValue('entity_id', 1)
  ->addValue('field_name', 'first_name')
  ->addValue('condition_type', 'default_value')
  ->addValue('condition_value', 'John')
  ->addValue('is_active', TRUE)
  ->execute();
```

### Get Available Fields
```php
$fields = \Civi\Api4\ProfileCondition::getAvailableFields()
  ->setEntityType('contribution_page')
  ->setEntityId(1)
  ->execute();
```

## JavaScript Events

The extension triggers custom JavaScript events that you can listen to:

```javascript
// When a condition is evaluated
$(document).on('profilecondition:evaluated', function(event, condition, result) {
    console.log('Condition evaluated:', condition, 'Result:', result);
});

// When a field is shown/hidden
$(document).on('profilecondition:visibility_changed', function(event, fieldName, isVisible) {
    console.log('Field', fieldName, 'visibility changed to:', isVisible);
});

// When a field is enabled/disabled
$(document).on('profilecondition:state_changed', function(event, fieldName, isEnabled) {
    console.log('Field', fieldName, 'state changed to:', isEnabled);
});
```

## Advanced Configuration

### Custom Field Types

The extension automatically detects field types and applies appropriate logic. Supported field types include:

- Text fields
- Select dropdowns (single and multi-select)
- Radio buttons
- Checkboxes
- Date fields
- Boolean fields
- Custom fields

### Weight and Ordering

Conditions are processed in order of their weight (lowest first). This is important when multiple conditions affect the same field.

### Profile-Specific Conditions

You can create conditions that apply to specific profiles or to all profiles used by an entity. Leave the Profile ID empty to apply to all profiles.

## Troubleshooting

### Conditions Not Working

1. **Check Profile Association**: Ensure the profile is properly associated with your contribution page or event
2. **Verify Field Names**: Field names must match exactly (case-sensitive)
3. **Check JavaScript**: Open browser developer tools to check for JavaScript errors
4. **Weight Conflicts**: Ensure condition weights don't create conflicts

### Performance Issues

1. **Limit Conditions**: Too many conditions can impact form loading time
2. **Optimize Triggers**: Use specific trigger values rather than complex patterns
3. **Cache Clearing**: Clear CiviCRM caches after making changes

### Debug Mode

Enable debug mode by adding this to your browser console:
```javascript
window.ProfileConditions.debugMode = true;
```

## Hooks

The extension provides several hooks for customization:

### hook_civicrm_profilecondition_fields
Modify available fields for conditions:
```php
function myextension_civicrm_profilecondition_fields(&$fields, $entityType, $entityId) {
    // Add custom fields or modify existing ones
    $fields['my_custom_field'] = 'My Custom Field Label';
}
```

### hook_civicrm_profilecondition_evaluate
Modify condition evaluation:
```php
function myextension_civicrm_profilecondition_evaluate(&$result, $condition, $formValues) {
    // Custom condition evaluation logic
    if ($condition['field_name'] == 'special_field') {
        $result = custom_evaluation_logic($condition, $formValues);
    }
}
```

## Support

- **Documentation**: [GitHub Wiki](https://github.com/skvare/com.skvare.profilecondition/wiki)
- **Issues**: [GitHub Issues](https://github.com/skvare/com.skvare.profilecondition/issues)
- **Support**: [Contact Skvare](mailto:info@skvare.com)

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

## License

This extension is licensed under [AGPL-3.0](LICENSE.txt).

## Credits

Developed by [Skvare LLC](https://skvare.com)

## Changelog

### Version 1.0.0
- Initial release
- Support for default values, visibility, read-only, and conditional logic
- Support for contribution pages and events
- API4 integration
- Weight-based condition ordering

---

For more information, visit the [extension page](https://github.com/skvare/com.skvare.profilecondition).
