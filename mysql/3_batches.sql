CREATE TABLE `batches` (
  `id` char(36) PRIMARY KEY,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `velo_payout_id` varchar(255) COLLATE utf8mb4_unicode_ci NULL,
  `velo_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;