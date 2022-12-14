-- Active: 1670210508622@@db.ethereallab.app@3306@rs2392
CREATE TABLE IF NOT EXISTS `Users` (
    `id` INT NOT NULL AUTO_INCREMENT
    ,`username` VARCHAR(100) NOT NULL
    ,`first` VARCHAR(100) NOT NULL
    ,`last` VARCHAR(100) NOT NULL
    ,`password` VARCHAR(60) NOT NULL
    ,`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ,PRIMARY KEY (`id`)
    ,UNIQUE (`username`)
)