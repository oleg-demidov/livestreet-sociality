CREATE TABLE IF NOT EXISTS `prefix_sociality_social` ( 
    `id` INT(11) NOT NULL AUTO_INCREMENT , 
    `user_id` INT(11) NOT NULL , 
    `profile_url` VARCHAR(255) NULL DEFAULT NULL ,
    `social_id` BIGINT(11) NOT NULL , 
    `social_type` ENUM('Vkontakte','Odnoklassniki','Facebook','Twitter','Instagram','Mailru','Google','Yandex','GitHub','Steam','Yahoo','LinkedIn','Live') NOT NULL , 
    `token` VARCHAR(255) NULL , 
    `date_received` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    `date_expire` INT(11) NULL , 
    PRIMARY KEY (`id`), 
    INDEX (`social_id`), 
    INDEX (`user_id`),
    UNIQUE `id_type` (`social_id`, `social_type`)
) 
ENGINE = InnoDB
DEFAULT CHARSET =utf8;