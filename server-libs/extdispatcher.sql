CREATE TABLE `accounts` (
  `email` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pass` varchar(65) COLLATE utf8mb4_bin NOT NULL,
  `level` tinyint NOT NULL DEFAULT '0',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

ALTER TABLE `accounts`
  ADD PRIMARY KEY (`email`);