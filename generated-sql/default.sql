
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- cj__missions
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cj__missions`;

CREATE TABLE `cj__missions`
(
    `ID` CHAR(64) NOT NULL,
    `type` VARCHAR(250) NOT NULL,
    `date` DATE NOT NULL,
    `name` VARCHAR(250) NOT NULL,
    `start` TIME NOT NULL,
    `arrival` VARCHAR(250),
    `end` TIME NOT NULL,
    `code` FLOAT,
    `confirmed` TINYINT(1) DEFAULT 0 NOT NULL,
    `user_id` INTEGER DEFAULT 0 NOT NULL,
    PRIMARY KEY (`ID`),
    INDEX `FK__cj__users` (`user_id`),
    CONSTRAINT `FK__cj__users`
        FOREIGN KEY (`user_id`)
        REFERENCES `cj__users` (`ID`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- cj__users
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cj__users`;

CREATE TABLE `cj__users`
(
    `ID` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `password` VARCHAR(50) NOT NULL,
    `mail` VARCHAR(100) NOT NULL,
    `missions` INTEGER DEFAULT 0 NOT NULL,
    `session` VARCHAR(50),
    `validity` TINYINT(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY (`ID`),
    INDEX `name` (`name`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
