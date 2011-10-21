SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `savage` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `savage` ;

-- -----------------------------------------------------
-- Table `savage`.`players`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `savage`.`players` ;

CREATE  TABLE IF NOT EXISTS `savage`.`players` (
  `playerid` INT NULL ,
  `playername` MEDIUMTEXT NULL ,
  PRIMARY KEY (`playerid`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `savage`.`matches`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `savage`.`matches` ;

CREATE  TABLE IF NOT EXISTS `savage`.`matches` (
  `matchid` INT NOT NULL ,
  `map` VARCHAR(45) NULL ,
  `date` VARCHAR(45) NULL ,
  `duration` VARCHAR(45) NULL ,
  `player_dmg_team1` VARCHAR(45) NULL ,
  `player_dmg_team2` VARCHAR(45) NULL ,
  `kills_team1` VARCHAR(45) NULL ,
  `kills_team2` VARCHAR(45) NULL ,
  `assists_team1` VARCHAR(45) NULL ,
  `assists_team2` VARCHAR(45) NULL ,
  `souls_team1` VARCHAR(45) NULL ,
  `souls_team2` VARCHAR(45) NULL ,
  `healed_team1` VARCHAR(45) NULL ,
  `healed_team2` VARCHAR(45) NULL ,
  `res_team1` VARCHAR(45) NULL ,
  `res_team2` VARCHAR(45) NULL ,
  `gold_team1` VARCHAR(45) NULL ,
  `gold_team2` VARCHAR(45) NULL ,
  `repaired_team1` VARCHAR(45) NULL ,
  `repaired_team2` VARCHAR(45) NULL ,
  `npc_team1` VARCHAR(45) NULL ,
  `npc_team2` VARCHAR(45) NULL ,
  `bd_team1` VARCHAR(45) NULL ,
  `bd_team2` VARCHAR(45) NULL ,
  `razed_team1` VARCHAR(45) NULL ,
  `razed_team2` VARCHAR(45) NULL ,
  `deaths_team1` VARCHAR(45) NULL ,
  `deaths_team2` VARCHAR(45) NULL ,
  `kd_team1` VARCHAR(45) NULL ,
  `kd_team2` VARCHAR(45) NULL ,
  PRIMARY KEY (`matchid`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `savage`.`stats`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `savage`.`stats` ;

CREATE  TABLE IF NOT EXISTS `savage`.`stats` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `matchid` VARCHAR(45) NULL ,
  `playerid` VARCHAR(45) NULL ,
  `exp` VARCHAR(45) NULL ,
  `dmg` VARCHAR(45) NULL ,
  `kills` VARCHAR(45) NULL ,
  `assists` VARCHAR(45) NULL ,
  `souls` VARCHAR(45) NULL ,
  `npc` VARCHAR(45) NULL ,
  `healed` VARCHAR(45) NULL ,
  `res` VARCHAR(45) NULL ,
  `gold` VARCHAR(45) NULL ,
  `repair` VARCHAR(45) NULL ,
  `bd` VARCHAR(45) NULL ,
  `razed` VARCHAR(45) NULL ,
  `deaths` VARCHAR(45) NULL ,
  `kd` VARCHAR(45) NULL ,
  `duration` VARCHAR(45) NULL ,
  `sf` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
