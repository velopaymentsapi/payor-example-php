CREATE TABLE `users` (
  `id` char(36) PRIMARY KEY,
  `username` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_key` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `api_key` (`api_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO payor.users (id,username,password,api_key,is_active,created_at,updated_at) VALUES 
('e5d9a6b6-cc76-4832-9fd9-69b76fad7542','admin','$2y$10$CFXr3A.sjmM4EZKpScIYaernJxZ3amg0eoFgB0oThAH.vjupfbpTy','cd4898b3-167e-4f60-9832-242a41e2d0ba',true,'2019-03-28 18:16:37.710','2019-03-28 18:16:37.710')
;