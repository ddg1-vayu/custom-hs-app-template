CREATE TABLE `admin_access_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `http_cookie` varchar(512) DEFAULT NULL,
  `remote_address` varchar(255) NOT NULL,
  `remote_port` int(11) NOT NULL,
  `ua_platform` varchar(512) DEFAULT NULL,
  `ua_version` varchar(512) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `action` varchar(512) NOT NULL,
  `comment` longtext DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE `api_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hub_portal_id` int(11) DEFAULT 0,
  `api_origin` varchar(255) DEFAULT NULL,
  `curl_url` mediumtext DEFAULT NULL,
  `curl_payload` longtext DEFAULT NULL,
  `curl_method` enum('GET', 'POST', 'PATCH', 'PUT', 'DELETE') DEFAULT NULL,
  `curl_response` longtext DEFAULT NULL,
  `curl_http_code` varchar(50) DEFAULT NULL,
  `curl_type` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 ROW_FORMAT = DYNAMIC;

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
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE `uploads` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(512) DEFAULT NULL,
  `file_path` text NOT NULL,
  `file_type` varchar(512) DEFAULT NULL,
  `file_size` bigint(24) NOT NULL,
  `file_checksum` text DEFAULT NULL,
  `uploaded` timestamp NULL DEFAULT current_timestamp(),
  `last_modified` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `username` varchar(32) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(512) NOT NULL,
  `added` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE `webhooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hub_portal_id` int(11) DEFAULT 0,
  `source` varchar(255) NOT NULL,
  `payload` longtext DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `status` enum('0', '1') NOT NULL DEFAULT '0' COMMENT '0 - not processed, 1 - processed',
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
