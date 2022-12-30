/*
Shared Household Expenses
Copyright (C) 2023  Marco Weingart

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

USE `shared_household_expenses`;

CREATE TABLE IF NOT EXISTS `retailer` (
	`retailer_id` INT(11) NOT NULL AUTO_INCREMENT,
	`retailer` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	PRIMARY KEY (`retailer_id`) USING BTREE,
	UNIQUE INDEX `retailer` (`retailer`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;

CREATE TABLE IF NOT EXISTS `purchase` (
	`purchase_id` INT(11) NOT NULL AUTO_INCREMENT,
	`timestamp_created` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
	`timestamp_updated` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	`article` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`date` DATE NOT NULL,
	`retailer_id` INT(11) NOT NULL,
	PRIMARY KEY (`purchase_id`) USING BTREE,
	INDEX `fk_retailer` (`retailer_id`) USING BTREE,
	CONSTRAINT `fk_retailer` FOREIGN KEY (`retailer_id`) REFERENCES `shared_household_expenses`.`retailer` (`retailer_id`) ON UPDATE CASCADE ON DELETE RESTRICT
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;

CREATE TABLE IF NOT EXISTS `user` (
	`user_id` INT(11) NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`pretty_name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`passwd_hash` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`start_value` DECIMAL(20,2) NOT NULL DEFAULT '0.00',
	`account_status` SET('READ_WRITE','READ_ONLY','LOCKED','DEACTIVATED') NOT NULL DEFAULT 'READ_WRITE' COLLATE 'utf8mb4_unicode_ci',
	PRIMARY KEY (`user_id`) USING BTREE,
	UNIQUE INDEX `username` (`username`) USING BTREE,
	UNIQUE INDEX `pretty_name` (`pretty_name`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;

CREATE TABLE IF NOT EXISTS `contribution` (
	`purchase_id` INT(11) NOT NULL,
	`contribution_id` INT(11) NOT NULL,
	`user_id` INT(11) NOT NULL,
	`timestamp_created` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
	`timestamp_updated` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	`amount` DECIMAL(20,2) NOT NULL,
	`currency` TINYTEXT NOT NULL DEFAULT 'EUR' COLLATE 'utf8mb4_unicode_ci',
	`comment` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	PRIMARY KEY (`purchase_id`, `contribution_id`) USING BTREE,
	INDEX `fk_user` (`user_id`) USING BTREE,
	CONSTRAINT `fk_purchase` FOREIGN KEY (`purchase_id`) REFERENCES `shared_household_expenses`.`purchase` (`purchase_id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `shared_household_expenses`.`user` (`user_id`) ON UPDATE CASCADE ON DELETE RESTRICT
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;
