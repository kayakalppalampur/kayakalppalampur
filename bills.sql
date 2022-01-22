
CREATE TABLE `patient_bills` (
  `id` int(10) UNSIGNED NOT NULL,
  `bill_no` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
