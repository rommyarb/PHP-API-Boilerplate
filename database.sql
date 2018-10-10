CREATE TABLE `users` (
	`id` INT(12) NOT NULL AUTO_INCREMENT,
	`fullname` VARCHAR(50) NOT NULL COMMENT 'First and Last Name',
	`gender` VARCHAR(6) NOT NULL COMMENT 'male or female',
	`birthdate` DATETIME,
	`motto` VARCHAR(40),
	`pic` VARCHAR(100) COMMENT 'profile picture',
	`username` VARCHAR(20) NOT NULL,
	`hashed_password` VARCHAR(255) NOT NULL,
	`role` INT(1) NOT NULL DEFAULT 2 COMMENT 'admin role = 1, user role = 2, or you can change it',
	`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`modified_at` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- CREATE TABLE 'kelas' (
-- 	`id` INT(12) NOT NULL AUTO_INCREMENT,
-- 	`judul` VARCHAR(30) NOT NULL,
-- 	`deskripsi` VARCHAR(60),
-- 	`pic` VARCHAR(100),
-- 	`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
-- 	`modified_at` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
-- 	PRIMARY KEY (`id`)
-- ) ENGINE = InnoDB;

-- CREATE TABLE 'materi' (
-- 	`id` INT(12) NOT NULL AUTO_INCREMENT,
-- 	`id_kelas` INT(12),
-- 	`judul` VARCHAR(20) NOT NULL,
-- 	`deskripsi` VARCHAR(60),
-- 	`isi` 
-- 	`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
-- 	`modified_at` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
-- 	PRIMARY KEY (`id`)
-- ) ENGINE = InnoDB;