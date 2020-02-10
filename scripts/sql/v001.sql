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

CREATE TABLE `cb_legislation_revogation` ( 
    `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    `mode` TINYINT(1) NOT NULL,
    `revoked_document_id` INT(5) UNSIGNED NOT NULL,
    `substitute_document_id` INT(5) UNSIGNED NOT NULL,
    `description` VARCHAR(500) NOT NULL,
    PRIMARY KEY  (`id`)
) ENGINE = InnoDB;

CREATE TABLE `cb_legislation_attachment` ( 
    `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    `document_id` INT(5) UNSIGNED NOT NULL,
    `file_id` INT(5) UNSIGNED NOT NULL,
    PRIMARY KEY  (`id`)
) ENGINE = InnoDB;

CREATE TABLE `cb_legislation_label` ( 
    `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    `document_id` INT(5) UNSIGNED NOT NULL,
    `label_id` INT(5) UNSIGNED NOT NULL,
    PRIMARY KEY  (`id`)
) ENGINE = InnoDB;

CREATE VIEW `cb_legislation_view_document` AS 
    SELECT 
        `cb_legislation_document`.*, 
        (
            SELECT GROUP_CONCAT(`cb_legislation_attachment`.`file_id`)
            FROM  `cb_legislation_attachment`
            WHERE `cb_legislation_attachment`.`document_id` = `cb_legislation_document`.`id`
        )  AS `attachement_id`,
        (
            SELECT JSON_ARRAYAGG(JSON_OBJECT(
                'description', `cb_legislation_revogation`.`description`,
                'id', `cb_legislation_revogation`.`substitute_document_id`,
                'mode', `cb_legislation_revogation`.`mode`,
                'revoked_document_id', `cb_legislation_revogation`.`revoked_document_id`,
                'substitute_document_id', `cb_legislation_revogation`.`substitute_document_id`
            ))
            FROM  `cb_legislation_revogation`
            WHERE `cb_legislation_revogation`.`revoked_document_id` = `cb_legislation_document`.`id`
        )  AS `substitutes`,
        (
            SELECT JSON_ARRAYAGG(JSON_OBJECT(
                'description', `cb_legislation_revogation`.`description`,
                'id', `cb_legislation_revogation`.`substitute_document_id`,
                'mode', `cb_legislation_revogation`.`mode`,
                'revoked_document_id', `cb_legislation_revogation`.`revoked_document_id`,
                'substitute_document_id', `cb_legislation_revogation`.`substitute_document_id`
            ))
            FROM  `cb_legislation_revogation`
            WHERE `cb_legislation_revogation`.`substitute_document_id` = `cb_legislation_document`.`id`
        )  AS `repeals`,
        (
            SELECT GROUP_CONCAT(`cb_legislation_label`.`label_id`)
            FROM  `cb_legislation_label`
            WHERE `cb_legislation_label`.`document_id` = `cb_legislation_document`.`id`
        )  AS `label_id`
    FROM `cb_legislation_document`

