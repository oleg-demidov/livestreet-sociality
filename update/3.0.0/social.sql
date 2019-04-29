CREATE TABLE `prefix_sociality_social` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `profile_url` varchar(255) DEFAULT NULL,
  `social_id` bigint(11) NOT NULL,
  `social_type` enum('Vkontakte','Odnoklassniki','Facebook','Twitter','Instagram','Mailru','Google','Yandex','GitHub','Yahoo','LinkedIn','Live') NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `date_received` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_expire` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_type` (`social_id`,`social_type`),
  KEY `social_id` (`social_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

