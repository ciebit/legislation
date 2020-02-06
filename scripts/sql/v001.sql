CREATE TABLE `cb_legislation_document` ( 
    `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    `type` VARCHAR(200) NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `description` VARCHAR(500) NOT NULL,
    `date_time` DATETIME NOT NULL,
    `slug` VARCHAR(300) NOT NULL,
    `number` INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `status` TINYINT(1) NOT NULL DEFAULT '1' ,
    PRIMARY KEY  (`id`)
) ENGINE = InnoDB;

