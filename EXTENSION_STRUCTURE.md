# CiviCRM Profile Conditions Extension - Complete File Structure

This document shows the complete file structure for the `com.skvare.profilecondition` extension.

```
com.skvare.profilecondition/
├── README.md                                      # Extension documentation
├── EXTENSION_STRUCTURE.md                         # This file
├── LICENSE.txt                                    # License file (AGPL-3.0)
├── info.xml                                       # Extension metadata
├── profilecondition.php                           # Main extension file
├── profilecondition.civix.php                     # Generated civix functions
├── composer.json                                  # Composer configuration
├── phpunit.xml.dist                              # PHPUnit configuration
│
├── CRM/                                           # CRM classes directory
│   └── Profilecondition/
│       ├── BAO/
│       │   └── ProfileCondition.php              # Business logic class
│       ├── DAO/
│       │   └── ProfileCondition.php              # Data access object
│       ├── Form/
│       │   ├── ContributionPageConditions.php    # Contribution page form
│       │   └── EventConditions.php               # Event form
│       ├── Upgrader.php                          # Database upgrade handler
│       └── ExtensionUtil.php                     # Extension utilities
│
├── Civi/                                          # API4 classes directory
│   └── Api4/
│       ├── ProfileCondition.php                  # API4 entity
│       └── Action/
│           └── ProfileCondition/
│               └── GetAvailableFields.php        # Custom API4 action
│
├── css/                                           # Stylesheets
│   └── profile-conditions.css                    # Main stylesheet
│
├── js/                                            # JavaScript files
│   └── profile-conditions.js                     # Conditional logic handler
│
├── sql/                                           # Database files
│   └── auto_install.sql                          # Installation schema
│
├── templates/                                     # Smarty templates
│   └── CRM/
│       └── Profilecondition/
│           └── Form/
│               ├── ContributionPageConditions.tpl # Browse template
│               ├── EventConditions.tpl           # Event browse template
│               └── ConditionEdit.tpl              # Edit form template
│
├── tests/                                         # Test files
│   └── phpunit/
│       ├── bootstrap.php                         # Test bootstrap
│       └── CRM/
│           └── Profilecondition/
│               └── BAO/
│                   └── ProfileConditionTest.php  # Unit tests
│
└── xml/                                           # XML configuration
    └── Menu/
        └── profilecondition.xml                   # Menu definitions
```

## Key Files Explanation

### Core Extension Files

- **info.xml**: Contains extension metadata, dependencies, and configuration
- **profilecondition.php**: Main extension file with hooks and initialization
- **profilecondition.civix.php**: Auto-generated file with standard CiviCRM extension functions
- **composer.json**: PHP dependencies and autoloading configuration

### Database Layer

- **CRM/Profilecondition/DAO/ProfileCondition.php**: Data access object for database operations
- **CRM/Profilecondition/BAO/ProfileCondition.php**: Business logic layer with methods for applying conditions
- **sql/auto_install.sql**: Database schema for the `civicrm_profile_condition` table

### User Interface

- **CRM/Profilecondition/Form/ContributionPageConditions.php**: Form controller for contribution pages
- **CRM/Profilecondition/Form/EventConditions.php**: Form controller for events
- **templates/**: Smarty templates for the user interface
- **css/profile-conditions.css**: Styling for admin interface and field conditions
- **js/profile-conditions.js**: Client-side conditional logic implementation

### API Layer

- **Civi/Api4/ProfileCondition.php**: API4 entity definition
- **Civi/Api4/Action/ProfileCondition/GetAvailableFields.php**: Custom API4 action

### Configuration

- **xml/Menu/profilecondition.xml**: Menu item definitions for CiviCRM navigation
- **CRM/Profilecondition/ExtensionUtil.php**: Utility functions and constants

### Testing

- **tests/phpunit/**: PHPUnit test suite
- **phpunit.xml.dist**: PHPUnit configuration
- **tests/phpunit/bootstrap.php**: Test environment setup

## Database Schema

The extension creates one main table:

### `civicrm_profile_condition`

```sql
CREATE TABLE civicrm_profile_condition (
  id int unsigned NOT NULL AUTO_INCREMENT,
  entity_type varchar(64) NOT NULL,           -- 'contribution_page' or 'event'
  entity_id int unsigned NOT NULL,            -- ID of contribution page or event
  profile_id int unsigned NULL,               -- Specific profile (NULL = all profiles)
  field_name varchar(255) NOT NULL,           -- Field to apply condition to
  condition_type varchar(32) NOT NULL,        -- Type: default_value, visibility, readonly, conditional
  condition_value text NULL,                  -- Value for the condition
  target_field varchar(255) NULL,             -- For conditional logic
  trigger_value text NULL,                    -- Value that triggers condition
  action varchar(32) NULL,                    -- Action: show, hide, enable, disable
  is_active tinyint DEFAULT 1,                -- Is condition active
  weight int DEFAULT 0,                       -- Processing order
  created_date timestamp DEFAULT CURRENT_TIMESTAMP,
  modified_date timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY index_entity (entity_type, entity_id),
  KEY index_field (field_name),
  KEY index_active (is_active)
);
```

## Permissions

The extension uses these CiviCRM permissions:

- **administer CiviCRM**: Full access to all functionality
- **access CiviContribute** + **edit contributions**: Manage contribution page conditions
- **access CiviEvent** + **edit all events**: Manage event conditions
- **access CiviCRM**: Basic read access

## Hooks Used

The extension implements these CiviCRM hooks:

- `hook_civicrm_tabset`: Add tabs to contribution pages and events
- `hook_civicrm_buildForm`: Apply conditions to forms
- `hook_civicrm_buildProfile`: Apply profile-specific conditions
- `hook_civicrm_pageRun`: Add JavaScript for conditional logic
- `hook_civicrm_entityTypes`: Register the ProfileCondition entity

## Installation Process

1. Extension files are copied to CiviCRM extensions directory
2. `sql/auto_install.sql` creates the database table
3. Extension is registered with CiviCRM
4. Menu items are added to navigation
5. Hooks are registered and active

## Development Workflow

1. Modify PHP classes in `CRM/` or `Civi/` directories
2. Update templates in `templates/` directory
3. Modify JavaScript in `js/` directory
4. Update CSS in `css/` directory
5. Run tests: `phpunit`
6. Clear CiviCRM caches
7. Test functionality in CiviCRM interface

This structure follows CiviCRM extension best practices and provides a solid foundation for maintaining and extending the functionality.
