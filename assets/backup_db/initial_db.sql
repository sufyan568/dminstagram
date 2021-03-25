CREATE TABLE IF NOT EXISTS `add_ons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `add_on_name` varchar(255) NOT NULL,
  `unique_name` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `installed_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
  `purchase_code` varchar(100) NOT NULL,
  `module_folder_name` varchar(255) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`unique_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `add_ons` (`id`, `add_on_name`, `unique_name`, `version`, `installed_at`, `update_at`, `purchase_code`, `module_folder_name`, `project_id`) VALUES
(1, 'Instagram Reply Enhancers', 'instagram_reply_enhancers', '1.0', '2021-02-02 17:08:59', '2021-02-02 17:08:59', '1100223400-instagram_reply_enhancers', 'instagram_reply_enhancers', 44);



CREATE TABLE IF NOT EXISTS `ad_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section1_html` longtext,
  `section1_html_mobile` longtext,
  `section2_html` longtext,
  `section3_html` longtext,
  `section4_html` longtext,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `announcement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '0 means all',
  `is_seen` enum('0','1') NOT NULL DEFAULT '0',
  `seen_by` text NOT NULL COMMENT 'if user_id = 0 then comma seperated user_ids',
  `last_seen_at` datetime NOT NULL,
  `color_class` varchar(50) NOT NULL DEFAULT 'primary',
  `icon` varchar(50) NOT NULL DEFAULT 'fas fa-bell',
  `status` enum('published','draft') NOT NULL DEFAULT 'draft',
  PRIMARY KEY (`id`),
  KEY `for_user_id` (`user_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `auto_comment_reply_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `auto_comment_template_id` int(11) NOT NULL,
  `time_zone` varchar(255) NOT NULL,
  `schedule_time` datetime NOT NULL,
  `campaign_name` varchar(255) NOT NULL,
  `post_id` varchar(200) NOT NULL,
  `page_info_table_id` int(11) NOT NULL,
  `page_name` mediumtext NOT NULL,
  `post_created_at` varchar(255) NOT NULL,
  `last_reply_time` datetime NOT NULL,
  `last_updated_at` datetime NOT NULL,
  `auto_comment_count` int(11) NOT NULL,
  `periodic_time` varchar(255) NOT NULL,
  `schedule_type` varchar(255) NOT NULL,
  `auto_comment_type` varchar(255) NOT NULL,
  `campaign_start_time` datetime NOT NULL,
  `campaign_end_time` datetime NOT NULL,
  `comment_start_time` time NOT NULL,
  `comment_end_time` time NOT NULL,
  `auto_private_reply_status` enum('0','1','2') NOT NULL DEFAULT '0',
  `auto_reply_done_info` longtext NOT NULL,
  `periodic_serial_reply_count` int(11) NOT NULL,
  `error_message` mediumtext NOT NULL,
  `post_description` longtext NOT NULL,
  `post_thumb` text NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  `social_media_type` enum('Facebook','Instagram') NOT NULL DEFAULT 'Facebook',
  `insta_media_url` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auto_comment_template_id` (`auto_comment_template_id`),
  KEY `auto_private_reply_status` (`auto_private_reply_status`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `auto_comment_reply_tb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `template_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auto_reply_comment_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `custom_page_builder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `page_description` longtext NOT NULL,
  `url` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FX_USER_ID` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `email_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `smtp_host` varchar(100) NOT NULL,
  `smtp_port` varchar(100) NOT NULL,
  `smtp_user` varchar(100) NOT NULL,
  `smtp_type` enum('Default','tls','ssl') NOT NULL DEFAULT 'Default',
  `smtp_password` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `email_template_management` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `template_type` varchar(255) NOT NULL,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'fas fa-folder-open',
  `tooltip` text NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `template_type` (`template_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `email_template_management` (`id`, `title`, `template_type`, `subject`, `message`, `icon`, `tooltip`, `info`) VALUES
(1, 'Signup Activation', 'signup_activation', '#APP_NAME# | Account Activation', '<p>To activate your account please perform the following steps :</p>\r\n<ol>\r\n<li>Go to this url : #ACTIVATION_URL#</li>\r\n<li>Enter this code : #ACCOUNT_ACTIVATION_CODE#</li>\r\n<li>Activate your account</li>\r\n</ol>', 'fas fa-skating', '#APP_NAME#,#ACTIVATION_URL#,#ACCOUNT_ACTIVATION_CODE#', 'When a new user open an account'),
(2, 'Reset Password', 'reset_password', '#APP_NAME# | Password Recovery', '<p>To reset your password please perform the following steps :</p>\r\n<ol>\r\n<li>Go to this url : #PASSWORD_RESET_URL#</li>\r\n<li>Enter this code : #PASSWORD_RESET_CODE#</li>\r\n<li>reset your password.</li>\r\n</ol>\r\n<h4>Link and code will be expired after 24 hours.</h4>', 'fas fa-retweet', '#APP_NAME#,#PASSWORD_RESET_URL#,#PASSWORD_RESET_CODE#', 'When a user forget login password'),
(3, 'Change Password', 'change_password', 'Change Password Notification', 'Dear #USERNAME#,<br/> \r\nYour <a href=\"#APP_URL#\">#APP_NAME#</a> password has been changed.<br>\r\nYour new password is: #NEW_PASSWORD#.<br/><br/> \r\nThank you,<br/>\r\n<a href=\"#APP_URL#\">#APP_NAME#</a> Team', 'fas fa-key', '#APP_NAME#,#APP_URL#,#USERNAME#,#NEW_PASSWORD#', 'When admin reset password of any user'),
(4, 'Subscription Expiring Soon', 'membership_expiration_10_days_before', 'Payment Alert', 'Dear #USERNAME#,\r\n<br/> Your account will expire after 10 days, Please pay your fees.<br/><br/>\r\nThank you,<br/>\r\n<a href=\"#APP_URL#\">#APP_NAME#</a> Team', 'fas fa-clock', '#APP_NAME#,#APP_URL#,#USERNAME#', '10 days before user subscription expires'),
(5, 'Subscription Expiring Tomorrow', 'membership_expiration_1_day_before', 'Payment Alert', 'Dear #USERNAME#,<br/>\r\nYour account will expire tomorrow, Please pay your fees.<br/><br/>\r\nThank you,<br/>\r\n<a href=\"#APP_URL#\">#APP_NAME#</a> Team', 'fas fa-stopwatch', '#APP_NAME#,#APP_URL#,#USERNAME#', '1 day before user subscription expires'),
(6, 'Subscription Expired', 'membership_expiration_1_day_after', 'Subscription Expired', 'Dear #USERNAME#,<br/>\r\nYour account has been expired, Please pay your fees for continuity.<br/><br/>\r\nThank you,<br/>\r\n<a href=\"#APP_URL#\">#APP_NAME#</a> Team', 'fas fa-user-clock', '#APP_NAME#,#APP_URL#,#USERNAME#', 'Subscription is already expired of a user'),
(7, 'Paypal Payment Confirmation', 'paypal_payment', 'Payment Confirmation', 'Congratulations,<br/> \r\nWe have received your payment successfully.<br/>\r\nNow you are able to use #PRODUCT_SHORT_NAME# system till #CYCLE_EXPIRED_DATE#.<br/><br/>\r\nThank you,<br/>\r\n<a href=\"#SITE_URL#\">#APP_NAME#</a> Team', 'fab fa-paypal', '#APP_NAME#,#CYCLE_EXPIRED_DATE#,#PRODUCT_SHORT_NAME#,#SITE_URL#', 'User pay through Paypal & gets confirmation'),
(8, 'Paypal New Payment', 'paypal_new_payment_made', 'New Payment Made', 'New payment has been made by #PAID_USER_NAME#', 'fab fa-cc-paypal', '#PAID_USER_NAME#', 'User pay through Paypal & admin gets notified'),
(9, 'Stripe Payment Confirmation', 'stripe_payment', 'Payment Confirmation', 'Congratulations,<br/>\r\nWe have received your payment successfully.<br/>\r\nNow you are able to use #PRODUCT_SHORT_NAME# system till #CYCLE_EXPIRED_DATE#.<br/><br/>\r\nThank you,<br/>\r\n<a href=\"#APP_URL#\">#APP_NAME#</a> Team', 'fab fa-stripe-s', '#APP_NAME#,#CYCLE_EXPIRED_DATE#,#PRODUCT_SHORT_NAME#,#SITE_URL#', 'User pay through Stripe & gets confirmation'),
(10, 'Stripe New Payment', 'stripe_new_payment_made', 'New Payment Made', 'New payment has been made by #PAID_USER_NAME#', 'fab fa-cc-stripe', '#PAID_USER_NAME#', 'User pay through Stripe & admin gets notified');



CREATE TABLE IF NOT EXISTS `facebook_rx_auto_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `facebook_rx_fb_user_info_id` int(11) NOT NULL,
  `post_type` enum('text_submit','link_submit','image_submit','video_submit') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text_submit',
  `campaign_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_group_user_id` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_or_group_or_user` enum('page','group','user') COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_or_group_or_user_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_preview_image` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_caption` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_url` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_thumb_url` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `og_action_type_id` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `og_object_id` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auto_share_post` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `auto_share_this_post_by_pages` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `auto_share_to_profile` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `auto_like_post` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `auto_private_reply` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `auto_private_reply_text` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `auto_private_reply_status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT 'taken by cronjob or not',
  `auto_private_reply_count` int(11) NOT NULL,
  `auto_private_reply_done_ids` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `auto_comment` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `auto_comment_text` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posting_status` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT 'pending,processing,completed',
  `post_id` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'fb post id',
  `post_url` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_updated_at` datetime NOT NULL,
  `schedule_time` datetime NOT NULL,
  `time_zone` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_auto_comment_cron_jon_status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT 'post''s auto comment is done by cron job',
  `post_auto_like_cron_jon_status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT 'post''s auto like is done by cron job',
  `post_auto_share_cron_jon_status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT 'post''s auto share is done by cron job',
  `error_mesage` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_child` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `parent_campaign_id` int(11) NOT NULL,
  `page_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ultrapost_auto_reply_table_id` int(11) NOT NULL,
  `is_autopost` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `repeat_times` int(11) NOT NULL,
  `time_interval` int(11) NOT NULL,
  `full_complete` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule_type` enum('now','later') COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_type` enum('facebook','instagram') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'facebook',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`facebook_rx_fb_user_info_id`),
  KEY `posting_status` (`posting_status`),
  KEY `dashboard` (`user_id`,`last_updated_at`),
  KEY `parent_campaign_id` (`parent_campaign_id`),
  KEY `page_group_user_id` (`page_group_user_id`),
  KEY `is_child` (`is_child`),
  KEY `schedule_type` (`schedule_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `facebook_rx_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(100) DEFAULT NULL,
  `api_id` varchar(250) DEFAULT NULL,
  `api_secret` varchar(250) DEFAULT NULL,
  `numeric_id` varchar(250) NOT NULL,
  `user_access_token` varchar(500) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `use_by` enum('only_me','everyone') NOT NULL DEFAULT 'only_me',
  `developer_access` enum('0','1') NOT NULL DEFAULT '0',
  `facebook_id` varchar(50) NOT NULL,
  `secret_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `facebook_rx_fb_group_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `facebook_rx_fb_user_info_id` int(11) NOT NULL,
  `group_id` varchar(200) NOT NULL,
  `group_cover` text,
  `group_profile` text,
  `group_name` varchar(200) DEFAULT NULL,
  `group_access_token` text NOT NULL,
  `add_date` date NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rx_fb_user_info_group` (`facebook_rx_fb_user_info_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `facebook_rx_fb_page_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `facebook_rx_fb_user_info_id` int(11) NOT NULL,
  `page_id` varchar(200) NOT NULL,
  `page_cover` text,
  `page_profile` text,
  `page_name` text,
  `username` varchar(255) NOT NULL,
  `page_access_token` text NOT NULL,
  `page_email` varchar(200) DEFAULT NULL,
  `add_date` date NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `auto_sync_lead` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT '0=disabled,1=enabled,2=processing,3=completed',
  `last_lead_sync` datetime NOT NULL,
  `next_scan_url` text NOT NULL,
  `current_lead_count` int(11) NOT NULL,
  `current_subscribed_lead_count` int(11) NOT NULL,
  `current_unsubscribed_lead_count` int(11) NOT NULL,
  `msg_manager` enum('0','1') NOT NULL DEFAULT '0',
  `bot_enabled` enum('0','1','2') NOT NULL DEFAULT '0',
  `started_button_enabled` enum('0','1') NOT NULL DEFAULT '0',
  `welcome_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `chat_human_email` varchar(250) NOT NULL,
  `no_match_found_reply` enum('enabled','disabled') NOT NULL DEFAULT 'disabled',
  `persistent_enabled` enum('0','1') NOT NULL DEFAULT '0',
  `enable_mark_seen` enum('0','1') NOT NULL DEFAULT '0',
  `enbale_type_on` enum('0','1') NOT NULL DEFAULT '0',
  `estimated_reach` varchar(50) NOT NULL,
  `last_estimaed_at` datetime NOT NULL,
  `review_status` enum('NOT SUBMITTED','PENDING','REJECTED','APPROVED','LIMITED') NOT NULL DEFAULT 'NOT SUBMITTED',
  `review_status_last_checked` datetime NOT NULL,
  `reply_delay_time` int(11) NOT NULL,
  `mail_service_id` text NOT NULL,
  `sms_api_id` int(11) NOT NULL,
  `sms_reply_message` text NOT NULL,
  `ice_breaker_status` enum('0','1') NOT NULL DEFAULT '0',
  `ice_breaker_questions` text NOT NULL,
  `email_api_id` varchar(100) NOT NULL,
  `email_reply_message` text NOT NULL,
  `email_reply_subject` text NOT NULL,
  `sequence_sms_api_id` int(11) NOT NULL,
  `sequence_sms_campaign_id` int(11) NOT NULL,
  `sequence_email_api_id` varchar(100) NOT NULL,
  `sequence_email_campaign_id` int(11) NOT NULL,
  `has_instagram` enum('0','1') NOT NULL DEFAULT '0',
  `instagram_business_account_id` varchar(100) NOT NULL,
  `insta_username` varchar(200) NOT NULL,
  `insta_followers_count` int(11) NOT NULL,
  `insta_media_count` int(11) NOT NULL,
  `insta_website` varchar(250) NOT NULL,
  `insta_biography` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  KEY `rx_fb_user_info` (`facebook_rx_fb_user_info_id`),
  KEY `user_id` (`user_id`,`page_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `facebook_rx_fb_user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facebook_rx_config_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `access_token` text NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `fb_id` varchar(200) NOT NULL,
  `add_date` date NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  `need_to_delete` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `fb_simple_support_desk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ticket_title` text NOT NULL,
  `ticket_text` longtext NOT NULL,
  `ticket_status` enum('1','2','3') CHARACTER SET latin1 NOT NULL DEFAULT '1' COMMENT '1=> Open. 2 => Closed, 3 => Resolved',
  `display` enum('0','1') NOT NULL DEFAULT '1',
  `support_category` int(11) NOT NULL,
  `last_replied_by` int(11) NOT NULL,
  `last_replied_at` datetime NOT NULL,
  `last_action_at` datetime NOT NULL COMMENT 'close resolve reopen etc',
  `ticket_open_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `support_category` (`support_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `fb_support_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `fb_support_category` (`id`, `category_name`, `user_id`, `deleted`) VALUES
(1, 'Billing', 1, '1'),
(2, 'Technical', 1, '0'),
(3, 'Query', 1, '0');



CREATE TABLE IF NOT EXISTS `fb_support_desk_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_reply_text` longtext NOT NULL,
  `ticket_reply_time` datetime NOT NULL,
  `reply_id` int(11) NOT NULL COMMENT 'ticket_id',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `forget_password` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `confirmation_code` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `success` int(11) NOT NULL DEFAULT '0',
  `expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `instagram_autoreply_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `autoreply_table_id` int(11) NOT NULL,
  `post_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply_type` enum('post','full','mention') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `comment_id` varchar(50) CHARACTER SET utf8 NOT NULL,
  `comment_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `commenter_name` varchar(120) CHARACTER SET utf8 NOT NULL,
  `commenter_id` varchar(50) CHARACTER SET utf8 NOT NULL,
  `comment_time` datetime NOT NULL,
  `reply_time` datetime NOT NULL,
  `comment_reply_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply_status_comment` text CHARACTER SET utf8 NOT NULL,
  `post_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` enum('0','1') CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `is_hidden` enum('0','1') CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `error_message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `hidden_comment_count` int(11) NOT NULL,
  `deleted_comment_count` int(11) NOT NULL,
  `auto_comment_reply_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comment_id` (`comment_id`),
  KEY `Autoreply_teable_id` (`autoreply_table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `instagram_reply_autoreply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facebook_rx_fb_user_info_id` int(11) NOT NULL,
  `autoreply_type` enum('post_autoreply','account_autoreply','mentions_autoreply') NOT NULL DEFAULT 'post_autoreply',
  `post_pause_play` enum('play','pause') NOT NULL DEFAULT 'play',
  `full_pause_play` enum('play','pause') NOT NULL,
  `mentions_pause_play` enum('play','pause') NOT NULL DEFAULT 'play',
  `auto_reply_campaign_name` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `page_info_table_id` int(11) NOT NULL,
  `page_name` text,
  `post_id` varchar(200) NOT NULL,
  `post_url` text NOT NULL,
  `media_url` text NOT NULL,
  `media_type` varchar(50) NOT NULL,
  `post_created_at` varchar(255) DEFAULT NULL,
  `post_description` longtext,
  `reply_type` varchar(200) NOT NULL,
  `report_type` enum('full','mention','post') NOT NULL DEFAULT 'post',
  `multiple_reply` enum('no','yes') NOT NULL,
  `nofilter_word_found_text` longtext NOT NULL,
  `auto_reply_text` longtext NOT NULL,
  `last_updated_at` datetime NOT NULL,
  `last_reply_time` datetime NOT NULL,
  `error_message` text NOT NULL,
  `hide_comment_after_comment_reply` enum('no','yes') NOT NULL,
  `is_delete_offensive` enum('hide','delete') NOT NULL,
  `offensive_words` longtext NOT NULL,
  `hidden_comment_count` int(11) NOT NULL,
  `deleted_comment_count` int(11) NOT NULL,
  `auto_comment_reply_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`page_info_table_id`,`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `instagram_reply_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auto_reply_campaign_name` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `reply_type` varchar(200) NOT NULL,
  `multiple_reply` enum('no','yes') NOT NULL,
  `nofilter_word_found_text` longtext NOT NULL,
  `auto_reply_text` longtext NOT NULL,
  `last_updated_at` datetime NOT NULL,
  `error_message` text NOT NULL,
  `hide_comment_after_comment_reply` enum('no','yes') NOT NULL,
  `is_delete_offensive` enum('hide','delete') NOT NULL,
  `offensive_words` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `login_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(100) DEFAULT NULL,
  `api_key` varchar(250) DEFAULT NULL,
  `google_client_id` text,
  `google_client_secret` varchar(250) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `module_access` varchar(255) NOT NULL,
  `have_child` enum('1','0') NOT NULL DEFAULT '0',
  `only_admin` enum('1','0') NOT NULL DEFAULT '1',
  `only_member` enum('1','0') NOT NULL DEFAULT '0',
  `add_ons_id` int(11) NOT NULL,
  `is_external` enum('0','1') NOT NULL DEFAULT '0',
  `header_text` varchar(255) NOT NULL,
  `is_menu_manager` enum('0','1') NOT NULL DEFAULT '0',
  `custom_page_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `menu` (`id`, `name`, `icon`, `url`, `serial`, `module_access`, `have_child`, `only_admin`, `only_member`, `add_ons_id`, `is_external`, `header_text`, `is_menu_manager`, `custom_page_id`) VALUES
(1, 'Dashboard', 'fas fa-fire', 'dashboard', 1, '', '0', '0', '0', 0, '0', '', '0', 0),
(2, 'System', 'fas fa-laptop-code', '', 9, '', '1', '1', '0', 0, '0', 'Administration', '0', 0),
(3, 'Subscription', 'fas fa-coins', '', 13, '', '1', '1', '0', 0, '0', '', '0', 0),
(4, 'Import Account', 'fas fa-cloud-download-alt', 'social_accounts/index', 5, '65', '0', '0', '0', 0, '0', '', '0', 0),
(11, 'Social Apps', 'fas fa-hands-helping', 'social_apps/index', 3, '', '0', '0', '1', 0, '0', '', '0', 0),
(12, 'Instagram Posting', 'fab fa-instagram', 'instagram_poster', 17, '296', '0', '0', '0', 0, '0', 'Posting Tools', '0', 0),
(13, 'Post Calendar', 'fas fa-calendar-alt', 'calendar', 19, '296', '0', '0', '0', 0, '0', '', '0', 0),
(14, 'Reply Template', 'fas fa-newspaper', 'instagram_reply/template_manager', 21, '278,279', '0', '0', '0', 0, '0', 'Comment Automation Tools', '0', 0),
(15, 'Comment Template', 'fas fa-comment-dots', 'comment_automation/comment_template_manager', 25, '278,279', '0', '0', '0', 0, '0', '', '0', 0),
(16, 'Create Campaign', 'fas fa-paper-plane', 'instagram_reply/get_account_lists', 29, '278,279', '0', '0', '0', 0, '0', '', '0', 0),
(17, 'Campaign Report', 'fas fa-chart-pie', 'instagram_reply/reports', 33, '278,279', '0', '0', '0', 0, '0', '', '0', 0),
(18, 'Hash Tag', 'fas fa-hashtag', 'instagram_reply/hashTag_search', 37, '267', '0', '0', '0', 0, '0', 'Search Tools', '0', 0);



CREATE TABLE IF NOT EXISTS `menu_child_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `module_access` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `have_child` enum('1','0') NOT NULL DEFAULT '0',
  `only_admin` enum('1','0') NOT NULL DEFAULT '1',
  `only_member` enum('1','0') NOT NULL DEFAULT '0',
  `is_external` enum('0','1') NOT NULL DEFAULT '0',
  `is_menu_manager` enum('0','1') NOT NULL DEFAULT '0',
  `custom_page_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `menu_child_1` (`id`, `name`, `url`, `serial`, `icon`, `module_access`, `parent_id`, `have_child`, `only_admin`, `only_member`, `is_external`, `is_menu_manager`, `custom_page_id`) VALUES
(1, 'Settings', 'admin/settings', 1, 'fas fa-sliders-h', '', 2, '0', '1', '0', '0', '0', 0),
(2, 'Social Apps', 'social_apps/index', 5, 'fas fa-hands-helping', '', 2, '0', '1', '0', '0', '0', 0),
(3, 'Cron Job', 'cron_job/index', 9, 'fas fa-clipboard-list', '', 2, '0', '1', '0', '0', '0', 0),
(4, 'Language Editor', 'multi_language/index', 13, 'fas fa-language', '', 2, '0', '1', '0', '0', '0', 0),
(5, 'Add-on Manager', 'addons/lists', 17, 'fas fa-plug', '', 2, '0', '1', '0', '0', '0', 0),
(6, 'Check Update', 'update_system/index', 21, 'fas fa-leaf', '', 2, '0', '1', '0', '0', '0', 0),
(7, 'Package Manager', 'payment/package_manager', 1, 'fas fa-shopping-bag', '', 3, '0', '1', '0', '0', '0', 0),
(8, 'User Manager', 'admin/user_manager', 5, 'fas fa-users', '', 3, '0', '1', '0', '0', '0', 0),
(9, 'Announcement', 'announcement/full_list', 9, 'far fa-bell', '', 3, '0', '1', '0', '0', '0', 0),
(10, 'Payment Accounts', 'payment/accounts', 13, 'far fa-credit-card', '', 3, '0', '1', '0', '0', '0', 0),
(11, 'Earning Summary', 'payment/earning_summary', 17, 'fas fa-tachometer-alt', '', 3, '0', '1', '0', '0', '0', 0),
(12, 'Transaction Log', 'payment/transaction_log', 27, 'fas fa-history', '', 3, '0', '1', '0', '0', '0', 0),
(13, 'Theme Manager', 'themes/lists', 19, 'fas fa-palette', '', 2, '0', '1', '0', '0', '0', 0),
(14, 'Menu Manager', 'menu_manager/index', 20, 'fas fa-bars', '', 2, '0', '1', '0', '0', '0', 0);



CREATE TABLE IF NOT EXISTS `menu_child_2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `module_access` varchar(255) NOT NULL,
  `parent_child` int(11) NOT NULL,
  `only_admin` enum('1','0') NOT NULL DEFAULT '1',
  `only_member` enum('1','0') NOT NULL DEFAULT '0',
  `is_external` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(250) DEFAULT NULL,
  `add_ons_id` int(11) NOT NULL,
  `extra_text` varchar(50) NOT NULL DEFAULT 'month',
  `limit_enabled` enum('0','1') NOT NULL DEFAULT '1',
  `bulk_limit_enabled` enum('0','1') NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `modules` (`id`, `module_name`, `add_ons_id`, `extra_text`, `limit_enabled`, `bulk_limit_enabled`, `deleted`) VALUES
(65, 'Facebook Accounts', 0, '', '1', '0', '0'),
(200, 'Facebook Pages', 0, '', '1', '0', '0'),
(251, 'Instagram Auto Comment Campaign', 0, '', '1', '0', '0'),
(267, 'Instagram Hash Tag Search', 0, 'month', '1', '0', '0'),
(278, 'Instagram Comment Hide/Delete/Mention & Full Account Reply', 16, '', '0', '0', '0'),
(279, 'Instagram Auto Comment Reply Campaigns', 0, 'month', '1', '0', '0'),
(296, 'Instagram Posting', 0, 'month', '1', '1', '0');



CREATE TABLE IF NOT EXISTS `native_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `api_key` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(250) NOT NULL,
  `module_ids` text NOT NULL,
  `monthly_limit` text,
  `bulk_limit` text,
  `price` varchar(20) NOT NULL DEFAULT '0',
  `validity` int(11) NOT NULL,
  `validity_extra_info` varchar(255) NOT NULL DEFAULT '1,M',
  `is_default` enum('0','1') NOT NULL DEFAULT '0',
  `visible` enum('0','1') NOT NULL DEFAULT '1',
  `highlight` enum('0','1') NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `package` (`id`, `package_name`, `module_ids`, `monthly_limit`, `bulk_limit`, `price`, `validity`, `validity_extra_info`, `is_default`, `visible`, `highlight`, `deleted`) VALUES
(1, 'Trial', '65,200,251,279,278,267,296', '{\"65\":\"2\",\"200\":\"5\",\"251\":\"30\",\"279\":\"30\",\"278\":\"0\",\"267\":\"30\",\"296\":\"30\"}', '{\"65\":\"0\",\"200\":\"0\",\"251\":\"0\",\"279\":\"0\",\"278\":\"0\",\"267\":\"0\",\"296\":\"5\"}', 'Trial', 7, '7,D', '1', '0', '0', '0');



CREATE TABLE IF NOT EXISTS `payment_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paypal_payment_type` enum('manual','recurring') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `paypal_mode` enum('live','sandbox') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'live',
  `stripe_secret_key` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_publishable_key` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `razorpay_key_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `razorpay_key_secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paystack_secret_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paystack_public_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mercadopago_public_key` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `mercadopago_access_token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `marcadopago_country` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mollie_api_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `manual_payment` enum('no','yes') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `manual_payment_instruction` mediumtext COLLATE utf8mb4_unicode_ci,
  `deleted` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `sslcommerz_store_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sslcommerz_store_password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sslcommers_mode` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senangpay_merchent_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `senangpay_secret_key` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `senangpay_mode` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instamojo_api_key` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `instamojo_auth_token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `instamojo_mode` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `xendit_secret_api_key` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `paypal_error_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `call_time` datetime DEFAULT NULL,
  `ipn_value` text,
  `error_log` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `transaction_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `verify_status` varchar(200) CHARACTER SET latin1 NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `paypal_email` varchar(200) CHARACTER SET latin1 NOT NULL,
  `receiver_email` varchar(200) CHARACTER SET latin1 NOT NULL,
  `country` varchar(100) CHARACTER SET latin1 NOT NULL,
  `payment_date` varchar(100) CHARACTER SET latin1 NOT NULL,
  `payment_type` varchar(100) CHARACTER SET latin1 NOT NULL,
  `transaction_id` varchar(150) CHARACTER SET latin1 NOT NULL,
  `paid_amount` float NOT NULL,
  `user_id` int(11) NOT NULL,
  `cycle_start_date` date NOT NULL,
  `cycle_expired_date` date NOT NULL,
  `package_id` int(11) NOT NULL,
  `stripe_card_source` text CHARACTER SET latin1 NOT NULL,
  `paypal_txn_type` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `transaction_history_manual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `transaction_id` varchar(150) NOT NULL,
  `paid_amount` varchar(255) NOT NULL,
  `paid_currency` char(4) NOT NULL,
  `additional_info` longtext NOT NULL,
  `filename` varchar(255) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `thm_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `update_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `files` text NOT NULL,
  `sql_query` text NOT NULL,
  `update_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `usage_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `usage_month` int(11) NOT NULL,
  `usage_year` year(4) NOT NULL,
  `usage_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(99) NOT NULL,
  `email` varchar(99) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `password` varchar(99) NOT NULL,
  `address` text NOT NULL,
  `user_type` enum('Member','Admin') NOT NULL,
  `status` enum('1','0') NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `purchase_date` datetime NOT NULL,
  `last_login_at` datetime NOT NULL,
  `activation_code` varchar(20) DEFAULT NULL,
  `expired_date` datetime NOT NULL,
  `bot_status` enum('0','1') NOT NULL DEFAULT '1',
  `package_id` int(11) NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  `brand_logo` text,
  `brand_url` text,
  `vat_no` varchar(100) DEFAULT NULL,
  `currency` enum('USD','AUD','CAD','EUR','ILS','NZD','RUB','SGD','SEK','BRL') NOT NULL DEFAULT 'USD',
  `time_zone` varchar(255) DEFAULT NULL,
  `company_email` varchar(200) DEFAULT NULL,
  `paypal_email` varchar(100) NOT NULL,
  `paypal_subscription_enabled` enum('0','1') NOT NULL DEFAULT '0',
  `last_payment_method` varchar(50) NOT NULL,
  `last_login_ip` varchar(25) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `password`, `address`, `user_type`, `status`, `add_date`, `purchase_date`, `last_login_at`, `activation_code`, `expired_date`, `bot_status`, `package_id`, `deleted`, `brand_logo`, `brand_url`, `vat_no`, `currency`, `time_zone`, `company_email`, `paypal_email`, `paypal_subscription_enabled`, `last_payment_method`, `last_login_ip`, `affiliate_id`) VALUES
(1, 'Admin', 'admin@gmail.com', '', '259534db5d66c3effb7aa2dbbee67ab0', '', 'Admin', '1', '2021-02-28 18:00:00', '0000-00-00 00:00:00', '2021-02-28 18:00:00', NULL, '0000-00-00 00:00:00', '0', 1, '0', '', NULL, NULL, 'USD', '', NULL, '', '0', '', '', 0);


CREATE TABLE IF NOT EXISTS `user_login_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(12) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(150) NOT NULL,
  `login_time` datetime NOT NULL,
  `login_ip` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `current` enum('1','0') NOT NULL DEFAULT '1',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `version` (`version`),
  KEY `Current` (`current`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `transaction_history_manual`
  ADD CONSTRAINT `thm_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;