-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema randevusaas
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema randevusaas
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `randevusaas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;
USE `randevusaas` ;

-- -----------------------------------------------------
-- Table `randevusaas`.`businesses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `randevusaas`.`businesses` ;

CREATE TABLE IF NOT EXISTS `randevusaas`.`businesses` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `timezone` VARCHAR(45) NOT NULL DEFAULT 'Europe/Istanbul',
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `randevusaas`.`appointments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `randevusaas`.`appointments` ;

CREATE TABLE IF NOT EXISTS `randevusaas`.`appointments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` INT UNSIGNED NOT NULL,
  `start_time` DATETIME NOT NULL COMMENT 'Always, UTC. So MySQL\'s date and time related functions cannot be used in queries!',
  `end_time` DATETIME NOT NULL,
  `status` ENUM('New', 'Approved', 'Set', 'Business Cancelled', 'Customer Cancelled', 'Customer Noshow', 'Rescheduled') NOT NULL DEFAULT 'New' COMMENT '\'New\': Customer created\\\\\\\\n\'Approved\': Customer created, business approved\\\\\\\\n\'Set\': Business created\\\\\\\\n\'Business Cancelled\': Business cancelled\\\\\\\\n\'Customer Cancelled: Customer cancelled\\\\\\\\n\'Customer Noshow\': Customer did not come\\\\\\\\n\'Rescheduled\': Rescheduled by agreement',
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `appointments_businesses_business_id_fk_idx` (`business_id` ASC) VISIBLE,
  CONSTRAINT `appointments_businesses_business_id_fk`
    FOREIGN KEY (`business_id`)
    REFERENCES `randevusaas`.`businesses` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `randevusaas`.`resources`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `randevusaas`.`resources` ;

CREATE TABLE IF NOT EXISTS `randevusaas`.`resources` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` INT UNSIGNED NOT NULL,
  `resource_type` VARCHAR(45) NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `resources_businesses_business_id_fk_idx` (`business_id` ASC) VISIBLE,
  CONSTRAINT `resources_businesses_business_id_fk`
    FOREIGN KEY (`business_id`)
    REFERENCES `randevusaas`.`businesses` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `randevusaas`.`appointments_resources`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `randevusaas`.`appointments_resources` ;

CREATE TABLE IF NOT EXISTS `randevusaas`.`appointments_resources` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `appointment_id` INT UNSIGNED NOT NULL,
  `resource_id` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `appointments_resources_resource_id_fk_idx` (`resource_id` ASC) VISIBLE,
  INDEX `resources_appointments_appointment_id_fk_idx` (`appointment_id` ASC) VISIBLE,
  CONSTRAINT `appointments_resources_resource_id_fk`
    FOREIGN KEY (`resource_id`)
    REFERENCES `randevusaas`.`resources` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `resources_appointments_appointment_id_fk`
    FOREIGN KEY (`appointment_id`)
    REFERENCES `randevusaas`.`appointments` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `randevusaas`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `randevusaas`.`users` ;

CREATE TABLE IF NOT EXISTS `randevusaas`.`users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` VARCHAR(255) NULL DEFAULT NULL,
  `status_message` VARCHAR(255) NULL DEFAULT NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `tcno` VARCHAR(11) NULL DEFAULT NULL,
  `gsm` VARCHAR(30) NOT NULL,
  `email` VARCHAR(255) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NOT NULL,
  `dogum_yili` SMALLINT(1) UNSIGNED NULL DEFAULT NULL,
  `tcnoverified` TINYINT(1) NOT NULL DEFAULT '0',
  `gsmverified` TINYINT(1) NOT NULL DEFAULT '0',
  `emailverified` TINYINT(1) NOT NULL DEFAULT '0',
  `language` VARCHAR(30) NULL DEFAULT 'tr',
  `superadmin` TINYINT NOT NULL DEFAULT 0,
  `last_active` DATETIME NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `gsm_UNIQUE` (`gsm` ASC) VISIBLE,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `randevusaas`.`appointments_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `randevusaas`.`appointments_users` ;

CREATE TABLE IF NOT EXISTS `randevusaas`.`appointments_users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `appointment_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `role` ENUM('expert', 'customer') NOT NULL DEFAULT 'customer',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `appointments_users_user_id_fk_idx` (`user_id` ASC) VISIBLE,
  INDEX `users_appointments_appointment_id_fk_idx` (`appointment_id` ASC) VISIBLE,
  CONSTRAINT `appointments_users_user_id_fk`
    FOREIGN KEY (`user_id`)
    REFERENCES `randevusaas`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `users_appointments_appointment_id_fk`
    FOREIGN KEY (`appointment_id`)
    REFERENCES `randevusaas`.`appointments` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `randevusaas`.`authidentities`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `randevusaas`.`authidentities` ;

CREATE TABLE IF NOT EXISTS `randevusaas`.`authidentities` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `type` ENUM('email_token', 'sms_otp', 'password', 'google', 'facebook', 'twitter') CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NOT NULL,
  `secret` VARCHAR(255) NOT NULL,
  `expires` DATETIME NULL DEFAULT NULL,
  `extra` MEDIUMTEXT NULL DEFAULT NULL,
  `force_reset` TINYINT(1) NOT NULL DEFAULT '0',
  `last_used_at` DATETIME NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `authKey` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  INDEX `user_id` (`user_id` ASC) INVISIBLE,
  CONSTRAINT `authidentities_users_user_id_fk`
    FOREIGN KEY (`user_id`)
    REFERENCES `randevusaas`.`users` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `randevusaas`.`logappointments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `randevusaas`.`logappointments` ;

CREATE TABLE IF NOT EXISTS `randevusaas`.`logappointments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_type` ENUM('created', 'updated', 'resource_added', 'resource_deleted', 'customer_added', 'customer_deleted', 'expert_added', 'expert_deleted') NOT NULL,
  `event` JSON NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `randevusaas`.`logbusinesses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `randevusaas`.`logbusinesses` ;

CREATE TABLE IF NOT EXISTS `randevusaas`.`logbusinesses` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_type` ENUM('created', 'updated', 'resource_added', 'resource_updated', 'customer_added', 'customer_updated', 'expert_added', 'expert_updated', 'rule_added', 'rule_updated', 'service_added', 'service_updated') NOT NULL,
  `event` JSON NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `randevusaas`.`logins`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `randevusaas`.`logins` ;

CREATE TABLE IF NOT EXISTS `randevusaas`.`logins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(255) NOT NULL,
  `user_agent` VARCHAR(255) NULL DEFAULT NULL,
  `id_type` VARCHAR(255) NOT NULL,
  `identifier` VARCHAR(255) NOT NULL,
  `user_id` INT UNSIGNED NULL DEFAULT NULL,
  `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `success` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `id_type_identifier` (`id_type` ASC, `identifier` ASC) VISIBLE,
  INDEX `user_id` (`user_id` ASC) VISIBLE,
  CONSTRAINT `logins_users_user_id_fk`
    FOREIGN KEY (`user_id`)
    REFERENCES `randevusaas`.`users` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `randevusaas`.`rules`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `randevusaas`.`rules` ;

CREATE TABLE IF NOT EXISTS `randevusaas`.`rules` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `rules_businesses_business_id_fk_idx` (`business_id` ASC) VISIBLE,
  CONSTRAINT `rules_businesses_business_id_fk`
    FOREIGN KEY (`business_id`)
    REFERENCES `randevusaas`.`businesses` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `randevusaas`.`services`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `randevusaas`.`services` ;

CREATE TABLE IF NOT EXISTS `randevusaas`.`services` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Preset services given to customers. Used for fast appointment setting.',
  `business_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  `resource_type` VARCHAR(45) NULL DEFAULT NULL,
  `expert_type` VARCHAR(45) NULL DEFAULT NULL,
  `duration` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name` (`name` ASC) VISIBLE,
  INDEX `services_businesses_business_id_fk_idx` (`business_id` ASC) VISIBLE,
  CONSTRAINT `services_businesses_business_id_fk`
    FOREIGN KEY (`business_id`)
    REFERENCES `randevusaas`.`businesses` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `randevusaas`.`users_businesses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `randevusaas`.`users_businesses` ;

CREATE TABLE IF NOT EXISTS `randevusaas`.`users_businesses` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `business_id` INT UNSIGNED NOT NULL,
  `role` ENUM('admin', 'secretary', 'expert', 'customer') NOT NULL DEFAULT 'customer',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `users_businesses_business_id_fk_idx` (`business_id` ASC) VISIBLE,
  INDEX `businesses_users_user_id_fk_idx` (`user_id` ASC) INVISIBLE,
  UNIQUE INDEX `users_businesses_unique` (`user_id` ASC, `business_id` ASC) VISIBLE,
  CONSTRAINT `businesses_users_user_id_fk`
    FOREIGN KEY (`user_id`)
    REFERENCES `randevusaas`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `users_businesses_business_id_fk`
    FOREIGN KEY (`business_id`)
    REFERENCES `randevusaas`.`businesses` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `randevusaas`.`permissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `randevusaas`.`permissions` ;

CREATE TABLE IF NOT EXISTS `randevusaas`.`permissions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `business_id` INT UNSIGNED NOT NULL,
  `permission` JSON NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `permissions_users_user_id_fk_idx` (`user_id` ASC) INVISIBLE,
  INDEX `permissions_businesses_business_id_fk_idx` (`business_id` ASC) INVISIBLE,
  CONSTRAINT `permissions_users_user_id_fk`
    FOREIGN KEY (`user_id`)
    REFERENCES `randevusaas`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `permissions_businesses_business_id_fk`
    FOREIGN KEY (`business_id`)
    REFERENCES `randevusaas`.`businesses` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `randevusaas`.`logusers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `randevusaas`.`logusers` ;

CREATE TABLE IF NOT EXISTS `randevusaas`.`logusers` (
  `id` INT UNSIGNED NOT NULL,
  `event_type` ENUM('created', 'updated', 'deleted', 'auth_added') NOT NULL,
  `event` JSON NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `randevusaas`.`businesses`
-- -----------------------------------------------------
START TRANSACTION;
USE `randevusaas`;
INSERT INTO `randevusaas`.`businesses` (`id`, `name`, `timezone`, `created_at`, `updated_at`, `deleted_at`) VALUES (1, 'Dental Dent', 'Europe/Istanbul', NULL, NULL, NULL);
INSERT INTO `randevusaas`.`businesses` (`id`, `name`, `timezone`, `created_at`, `updated_at`, `deleted_at`) VALUES (2, 'Super Dent', 'Europe/Istanbul', NULL, NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `randevusaas`.`resources`
-- -----------------------------------------------------
START TRANSACTION;
USE `randevusaas`;
INSERT INTO `randevusaas`.`resources` (`id`, `business_id`, `resource_type`, `created_at`, `updated_at`, `deleted_at`) VALUES (1, 1, 'Muayene', DEFAULT, NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `randevusaas`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `randevusaas`;
INSERT INTO `randevusaas`.`users` (`id`, `status`, `status_message`, `first_name`, `last_name`, `tcno`, `gsm`, `email`, `dogum_yili`, `tcnoverified`, `gsmverified`, `emailverified`, `language`, `superadmin`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES (1, NULL, NULL, 'Umut', 'Demirhan', '23416086000', '+905330338197', 'umut@kariyerfora.com', 1977, false, false, false, NULL, 1, NULL, NULL, NULL, NULL);
INSERT INTO `randevusaas`.`users` (`id`, `status`, `status_message`, `first_name`, `last_name`, `tcno`, `gsm`, `email`, `dogum_yili`, `tcnoverified`, `gsmverified`, `emailverified`, `language`, `superadmin`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES (2, NULL, NULL, 'Hüseyin', 'Mumay', NULL, '+905445868624', 'ideametrik@gmail.com', 1982, false, false, false, NULL, 1, NULL, NULL, NULL, NULL);
INSERT INTO `randevusaas`.`users` (`id`, `status`, `status_message`, `first_name`, `last_name`, `tcno`, `gsm`, `email`, `dogum_yili`, `tcnoverified`, `gsmverified`, `emailverified`, `language`, `superadmin`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES (3, NULL, NULL, 'Burhan', 'Çalhan', NULL, '+905057958150', 'calhan.bur@gmail.com', 1981, false, false, false, NULL, 1, NULL, NULL, NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `randevusaas`.`users_businesses`
-- -----------------------------------------------------
START TRANSACTION;
USE `randevusaas`;
INSERT INTO `randevusaas`.`users_businesses` (`id`, `user_id`, `business_id`, `role`, `created_at`, `deleted_at`) VALUES (1, 1, 1, 'admin', DEFAULT, NULL);
INSERT INTO `randevusaas`.`users_businesses` (`id`, `user_id`, `business_id`, `role`, `created_at`, `deleted_at`) VALUES (2, 1, 2, 'customer', DEFAULT, NULL);
INSERT INTO `randevusaas`.`users_businesses` (`id`, `user_id`, `business_id`, `role`, `created_at`, `deleted_at`) VALUES (3, 2, 1, 'customer', DEFAULT, NULL);
INSERT INTO `randevusaas`.`users_businesses` (`id`, `user_id`, `business_id`, `role`, `created_at`, `deleted_at`) VALUES (4, 3, 1, 'customer', DEFAULT, NULL);
INSERT INTO `randevusaas`.`users_businesses` (`id`, `user_id`, `business_id`, `role`, `created_at`, `deleted_at`) VALUES (5, 3, 2, 'customer', DEFAULT, NULL);

COMMIT;

