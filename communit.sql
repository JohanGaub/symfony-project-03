-- MySQL Script generated by MySQL Workbench
-- lun. 22 mai 2017 14:36:14 CEST
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema communit
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema communit
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `communit` DEFAULT CHARACTER SET utf8 ;
USE `communit` ;

-- -----------------------------------------------------
-- Table `communit`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `communit`.`user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `roles` LONGTEXT NOT NULL,
  `is_active` TINYINT(1) NOT NULL,
  `token` VARCHAR(255) NULL,
  `token_limit_date` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `communit`.`user_profile`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `communit`.`user_profile` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(255) NOT NULL,
  `lastname` VARCHAR(255) NOT NULL,
  `date_of_birth` DATETIME NOT NULL,
  `phone` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `communit`.`company`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `communit`.`company` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `addresse` VARCHAR(255) NOT NULL,
  `town` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NOT NULL,
  `siret` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `communit`.`user_technical_evolution`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `communit`.`user_technical_evolution` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `note` INT NULL,
  `comment` LONGTEXT NULL,
  `type` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `communit`.`technical_evolution`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `communit`.`technical_evolution` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `sum_up` LONGTEXT NOT NULL,
  `content` LONGTEXT NOT NULL,
  `reason` VARCHAR(255) NOT NULL,
  `status` VARCHAR(255) NOT NULL,
  `origin` VARCHAR(255) NOT NULL,
  `expected_delay` DATETIME NOT NULL,
  `creation_date` DATETIME NOT NULL,
  `update_date` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `communit`.`product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `communit`.`product` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` LONGTEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `communit`.`faq`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `communit`.`faq` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `sum_up` LONGTEXT NOT NULL,
  `content` LONGTEXT NOT NULL,
  `upload` MEDIUMBLOB NULL,
  `creation_date` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `communit`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `communit`.`category` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `tag` VARCHAR(255) NOT NULL,
  `description` LONGTEXT NOT NULL,
  `type` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `communit`.`documentation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `communit`.`documentation` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `sum_up` LONGTEXT NOT NULL,
  `content` LONGTEXT NOT NULL,
  `creation_date` DATETIME NOT NULL,
  `update_date` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
