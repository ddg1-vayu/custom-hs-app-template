CREATE TABLE `api_logs` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`hub_portal_id` int(11) DEFAULT NULL,
	`api_origin` varchar(255) DEFAULT NULL,
	`curl_url` text DEFAULT NULL,
	`curl_payload` longtext DEFAULT NULL,
	`curl_method` enum('DELETE', 'GET', 'HEAD', 'OPTIONS', 'PATCH', 'POST', 'PUT') DEFAULT NULL,
	`curl_response` longtext DEFAULT NULL,
	`curl_http_code` int(11) DEFAULT NULL,
	`curl_type` varchar(255) NOT NULL,
	`file_name` varchar(255) NOT NULL,
	`timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
	PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE `app_installs` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`hub_portal_id` int(11) NOT NULL,
	`install_code` varchar(255) NOT NULL,
	`refresh_token` varchar(255) NOT NULL,
	`access_token` varchar(350) NOT NULL,
	`installed` timestamp NOT NULL DEFAULT current_timestamp(),
	`last_installed` timestamp NOT NULL DEFAULT current_timestamp(),
	`token_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	`status` enum('Active', 'Inactive') NOT NULL DEFAULT 'Active',
	PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE `billing_details` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`hub_portal_id` int(11) DEFAULT NULL,
	`first_name` varchar(255) NOT NULL,
	`last_name` varchar(255) NOT NULL,
	`email` varchar(255) NOT NULL,
	`phone_number` varchar(16) NOT NULL,
	`company` varchar(255) NOT NULL,
	`country` varchar(255) NOT NULL,
	`subscription_name` enum('Monthly', 'Yearly') NOT NULL DEFAULT 'Monthly',
	`subscription_amount` varchar(255) DEFAULT NULL,
	`demo_start` date NOT NULL DEFAULT '0000-00-00',
	`demo_end` date NOT NULL DEFAULT '0000-00-00',
	`billing_status` enum('Active', 'Cancelled', 'Inactive', 'Trialing') NOT NULL DEFAULT 'Inactive',
	`billing_start` date DEFAULT NULL,
	`billing_end` date DEFAULT NULL,
	`customer_id` varchar(255) DEFAULT NULL,
	`subscription_id` varchar(255) DEFAULT NULL,
	`payment_link` varchar(255) DEFAULT NULL,
	`billing_email` varchar(255) DEFAULT NULL,
	`billing_name` varchar(255) DEFAULT NULL,
	`billing_address` varchar(255) DEFAULT NULL,
	`billing_city` varchar(255) DEFAULT NULL,
	`billing_state` varchar(255) DEFAULT NULL,
	`billing_country` varchar(255) DEFAULT NULL,
	`billing_postal_code` varchar(16) DEFAULT NULL,
	`last_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	`timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
	PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE `invoice_details` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`customer_id` varchar(255) NOT NULL,
	`subscription_id` varchar(255) NOT NULL,
	`pay_status` varchar(255) NOT NULL DEFAULT 'Paid',
	`amount_due` varchar(32) DEFAULT NULL,
	`amount_paid` varchar(32) DEFAULT NULL,
	`amount_remaining` varchar(32) DEFAULT NULL,
	`period_start` datetime NOT NULL,
	`period_end` datetime NOT NULL,
	`timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
	PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE `webhooks` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`source` varchar(255) DEFAULT NULL,
	`payload` longtext DEFAULT NULL,
	`type` varchar(255) NOT NULL,
	`file` varchar(255) NOT NULL,
	`timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
	PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE `user_access_logs` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`user_id` int(11) NOT NULL,
	`user` varchar(24) NOT NULL,
	`platform` varchar(255) NOT NULL,
	`ip_address` varchar(255) NOT NULL,
	`login` timestamp NOT NULL DEFAULT current_timestamp(),
	`logout` timestamp NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE `registered_users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`first_name` varchar(255) DEFAULT NULL,
	`last_name` varchar(255) DEFAULT NULL,
	`role` varchar(255) DEFAULT NULL,
	`username` varchar(32) NOT NULL,
	`email` varchar(255) NOT NULL,
	`password` varchar(512) NOT NULL,
	`added` timestamp NOT NULL DEFAULT current_timestamp(),
	`updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	`last_login` timestamp NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
