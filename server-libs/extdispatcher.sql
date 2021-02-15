CREATE TABLE `accounts` (
  `email` varchar(42) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pass` varchar(65) COLLATE utf8mb4_bin NOT NULL,
  `level` tinyint NOT NULL DEFAULT '0',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

ALTER TABLE `accounts`
  ADD PRIMARY KEY (`email`);



CREATE TABLE `calls` (
  `id` varchar(24) COLLATE utf8mb4_bin NOT NULL,
  `caller_session_id` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `dispatcher_email` varchar(42) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `caller_ip` varchar(40) COLLATE utf8mb4_bin NOT NULL,
  `location` varchar(100) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('POLICE','AMBULANCE','FIRE_SERVICE','') COLLATE utf8mb4_bin NOT NULL,
  `offer` json DEFAULT NULL,
  `answer` json DEFAULT NULL,
  `caller_candidates` json DEFAULT NULL,
  `dispatcher_candidates` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

ALTER TABLE `calls`
  ADD PRIMARY KEY (`id`);


CREATE TABLE `resetpass` (
  `id` varchar(24) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(42) COLLATE utf8mb4_general_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

ALTER TABLE `resetpass`
  ADD PRIMARY KEY (`id`,`email`);