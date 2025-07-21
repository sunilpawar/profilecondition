-- Schema for Profile Conditions Extension

CREATE TABLE IF NOT EXISTS `civicrm_profile_condition` (
                                                         `id` int unsigned NOT NULL AUTO_INCREMENT,
                                                         `entity_type` varchar(64) NOT NULL COMMENT 'Type of entity (contribution_page, event)',
  `entity_id` int unsigned NOT NULL COMMENT 'ID of the entity',
  `profile_id` int unsigned NULL COMMENT 'Profile ID if specific to a profile',
  `field_name` varchar(255) NOT NULL COMMENT 'Field name in the profile',
  `condition_type` varchar(32) NOT NULL COMMENT 'Type of condition (default_value, visibility, readonly, conditional)',
  `condition_value` text NULL COMMENT 'Value for the condition (JSON for complex conditions)',
  `target_field` varchar(255) NULL COMMENT 'Target field for conditional logic',
  `trigger_value` text NULL COMMENT 'Value that triggers the condition',
  `action` varchar(32) NULL COMMENT 'Action to take (show, hide, enable, disable)',
  `is_active` tinyint DEFAULT 1 COMMENT 'Is this condition active?',
  `weight` int DEFAULT 0 COMMENT 'Ordering weight',
  `created_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `index_entity` (`entity_type`, `entity_id`),
  INDEX `index_field` (`field_name`),
  INDEX `index_active` (`is_active`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Add foreign key constraints
ALTER TABLE `civicrm_profile_condition`
  ADD CONSTRAINT FK_civicrm_profile_condition_profile_id
    FOREIGN KEY (`profile_id`) REFERENCES `civicrm_uf_group` (`id`)
      ON DELETE CASCADE;
