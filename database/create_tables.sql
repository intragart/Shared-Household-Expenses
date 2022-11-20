USE `shared_household_expenses`;

CREATE TABLE `retailer` (
	`retailer_id` INT NOT NULL AUTO_INCREMENT,
	`retailer` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`retailer_id`),
	UNIQUE INDEX `retailer` (`retailer`)
);

CREATE TABLE `purchase` (
	`purchase_id` INT NOT NULL AUTO_INCREMENT,
	`timestamp` TIMESTAMP NOT NULL,
	`article` VARCHAR(255) NOT NULL,
	`date` DATE NOT NULL,
	`retailer_id` INT NOT NULL,
	PRIMARY KEY (`purchase_id`),
	CONSTRAINT `fk_retailer` FOREIGN KEY (`retailer_id`) REFERENCES `retailer` (`retailer_id`) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE `user` (
	`user_id` INT NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(255) NOT NULL,
	`pretty_name` VARCHAR(255) NULL DEFAULT NULL,
	`passwd_hash` VARCHAR(255) NOT NULL,
	`start_value` DECIMAL(20,2) NOT NULL DEFAULT '0.00',
	`account_active` SET('READ_WRITE','READ_ONLY','LOCKED','DEACTIVATED') NOT NULL DEFAULT 'READ_WRITE',
	PRIMARY KEY (`user_id`),
	UNIQUE INDEX `username` (`username`),
	UNIQUE INDEX `pretty_name` (`pretty_name`)
);

CREATE TABLE `contribution` (
	`purchase_id` INT NOT NULL,
	`contribution_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	`timestamp` TIMESTAMP NOT NULL,
	`amount` DECIMAL(20,2) NOT NULL,
	`currency` TINYTEXT NOT NULL DEFAULT 'EUR',
	`comment` VARCHAR(500) NULL DEFAULT NULL,
	PRIMARY KEY (`purchase_id`, `contribution_id`),
	CONSTRAINT `fk_purchase` FOREIGN KEY (`purchase_id`) REFERENCES `purchase` (`purchase_id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE ON DELETE RESTRICT
);
