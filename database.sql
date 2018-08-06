CREATE TABLE `users` (
	`id` VARCHAR(255) NOT NULL COMMENT 'Unique id',
	`fullname` VARCHAR(50) NOT NULL COMMENT 'First and Last Name',
	`gender` VARCHAR(6) NOT NULL COMMENT 'male or female',
	`username` INT(20) NOT NULL,
	`hashed_password` VARCHAR(255) NOT NULL,
	`created_at` DATETIME NOT NULL,
	`modified_at` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
);