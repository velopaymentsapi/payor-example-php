CREATE TABLE `payments` (
  `id` char(36) PRIMARY KEY,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payee_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `memo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `velo_source_account` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `velo_status` varchar(255) COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX batch_ind (batch_id),
  FOREIGN KEY (batch_id) REFERENCES batches(id),
  INDEX payee_ind (payee_id),
  FOREIGN KEY (payee_id) REFERENCES payees(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;