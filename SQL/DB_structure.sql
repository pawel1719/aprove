#CREATE DATABASE `approvals_data`  DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE approvals_data;

#DROP TABLE IF EXISTS `users`;
-- ----------------------------------------------
/* TABLES USERS */
-- ----------------------------------------------
CREATE TABLE IF NOT EXISTS `users`
(
	`ID` INT NOT NULL  AUTO_INCREMENT,
	`IDHash` VARCHAR(100) NOT NULL UNIQUE,
    `Email` VARCHAR(60) NOT NULL UNIQUE,        # Email as a login
    `Password` VARCHAR(100) NOT NULL,
    `Salt` VARCHAR(150) NOT NULL,
    `Permission` INT(1) NOT NULL DEFAULT 3,		#PERMISION FOR USERS IN JSON
    `PasswordCreadtedAt` DATETIME NULL,
    `LastLoginAt` DATETIME NULL,
    `CreatedAt` DATETIME NOT NULL,				#CREATED AT DATATIME
    `UpdatedAt` DATETIME,						#UPDATED AT DATATIME
    `IsBlocked` SMALLINT NOT NULL DEFAULT 0,	#BLOCKED ACCOUNT FROM GIVEN WRONG PASSWORD
    `BlockedAt` DATETIME NULL,					#TIME BLOCKED ACCOUNT
    `BlockedTo` DATETIME NULL,					#TIME BLOCKED ACCOUNT
    `InvalidAttemptCounter` INT NOT NULL DEFAULT 0,	#INVALID PASSWORD COUNTER - count attempts enter wrong password
    `CounterCorrectLogin` INT NOT NULL,			#COUNTER SUCCESS LOGIN
    `CounterIncorretLogin` INT NOT NULL,  		#COUNTER BAD LOGIN
    `IdPermission` INT NOT NULL,
    PRIMARY KEY(`ID`)
)ENGINE = InnoDB;


-- ----------------------------------------------
/* TABLES USERS_DATA */
-- ----------------------------------------------
CREATE TABLE IF NOT EXISTS `permission`
(
    `ID` INT NOT NULL AUTO_INCREMENT,
    `Name` VARCHAR(25) NOT NULL,
    `KeyPermission` TEXT NOT NULL,
    PRIMARY KEY(`ID`)
)ENGINE = InnoDB;

-- ----------------------------------------------
/* TABLES USERS_DATA */
-- ----------------------------------------------
/* Information about users */
/* Z poziomu panelu admina beda ustawiane pola wymagane i czy jest widoczne */
CREATE TABLE IF NOT EXISTS `users_data`
(
	`ID` INT NOT NULL AUTO_INCREMENT,
    `IDUsers` INT NOT NULL UNIQUE,
    `IDusersDataConfig` INT NOT NULL,
    `AgreeDateOfBirth` SMALLINT,
    `DateOfBirth` DATE,
    `DateOfBirthUpdatedAt` DATETIME,
    `AgreeCityOfBirth` SMALLINT,
    `CityOfBirth` VARCHAR(30) NULL,
    `CityOfBirthCreatedAt` DATETIME,
    `CityOfBirthUpdatedAt` DATETIME,
	`AgreeFirstName` SMALLINT,
    `FirstName` VARCHAR(30) NOT NULL,
    `FirstNameUpdatedAt` DATETIME,
    `AgreeMiddleName` SMALLINT,
    `MiddleName` VARCHAR(30),				#DRUGIE IMIE
    `MiddleNameUpdatedAt` DATETIME,
    `AgreeLastName` SMALLINT,
    `LastName` VARCHAR(40) NOT NULL,
    `LastNameUpdatedAt` DATETIME,
    `AgreeFamilyName` SMALLINT,
    `FamilyName` VARCHAR(40) NULL,			#NAZWISKO RODOWE
    `FamilyNameUpdatedAt` DATETIME,
    `AgreePhoneNumber` SMALLINT,
    `PhoneNumber` VARCHAR(15),
    `PhoneNumberCreatedAt` DATETIME,
    `PhoneNumberUpdatedAt` DATETIME,
    `Gender` SMALLINT NOT NULL,
    `AgreePESEL` SMALLINT,
    `PESEL` VARCHAR(11),
    `PESELCreatedAt` DATETIME,
    `PESELUpdatedAt` DATETIME,
    `AgreeIdentificationCard` SMALLINT,
    `IdentificationCard` VARCHAR(9) NULL,		#NUMER DOWODU OSOBISTEGO
    `IdentificationCardCreatedAt` DATETIME,
    `IdentificationCardUpdatedAt` DATETIME,
    `ExpirationDateNoPersonalCard` DATE,
    `ExpirationDateNoPersonalCardCreatedAt` DATETIME,
    `ExpirationDateNoPersonalCardUpdatedAt` DATETIME,
    `AgreeDataLiving` SMALLINT,
    `CityOfLiving` VARCHAR(30),
    `CityOfLivingCreatedAt` DATETIME,
    `CityOfLivingUpdatedAt` DATETIME,
    `StreetOfLiving` VARCHAR(50),
    `StreetOfLivingCreatedAt` DATETIME,
    `StreetOfLivingUpdatedAt` DATETIME,
    `NoHouseOfLiving` VARCHAR(10),
    `NoHouseOfLivingCreatedAt` DATETIME,
    `NoHouseOfLivingUpdatedAt` DATETIME,
    `NoFlatOfLiving` VARCHAR(10),
    `NoFlatOfLivingCreatedAt` DATETIME,
    `NoFlatOfLivingUpdatedAt` DATETIME,
    `AgreeDataCorrespondence` SMALLINT,
    `CityOfCorrespondence` VARCHAR(30),
    `CityOfCorrespondenceCreatedAt` DATETIME,
    `CityOfCorrespondenceUpdatedAt` DATETIME,
    `StreetOfCorrespondence` VARCHAR(50),
    `StreetOfCorrespondenceCreatedAt` DATETIME,
    `StreetOfCorrespondenceUpdatedAt` DATETIME,
    `NoHouseOfCorrespondence` VARCHAR(10),
    `NoHouseOfCorrespondenceCreatedAt` DATETIME,
    `NoHouseOfCorrespondenceUpdatedAt` DATETIME,
    `NoFlatOfCorrespondence` VARCHAR(10),
    `NoFlatOfCorrespondenceCreatedAt` DATETIME,
    `NoFlatOfCorrespondenceUpdatedAt` DATETIME,
    `AgreeCompanyName` SMALLINT,
    `CompanyName` VARCHAR(100),
    `CompanyNameCreatedAt` DATETIME,
    `CompanyNameUpdatedAt` DATETIME,
    `AgreeWorkPosition` SMALLINT,
    `WorkPosition` VARCHAR(70),			#STANOWISKO
    `WorkPositionCreatedAt` DATETIME,
    `WorkPositionUpdatedAt` DATETIME,
    `DateCreatedRecord` DATETIME,
    PRIMARY KEY(`ID`)
)ENGINE = InnoDB;


-- ----------------------------------------------
/* TABLES USERS_DATA_CONFIG */
-- ----------------------------------------------
CREATE TABLE IF NOT EXISTS `users_data_config`
(
    `ID` INT NOT NULL AUTO_INCREMENT,
    `IsRequierdCityOfBirth` SMALLINT DEFAULT 1,
    `VisibleCityOfBirth` SMALLINT DEFAULT 1,
    `IsRequierdMiddleName` SMALLINT DEFAULT 1,
    `VisibleMiddleName` SMALLINT DEFAULT 1,
    `IsRequierdFamilyName` SMALLINT DEFAULT 1,
    `IsRequierdPhoneNumber` SMALLINT DEFAULT 1,
    `VisiblePhoneNumber` SMALLINT DEFAULT 1,
    `IsRequierdGender` SMALLINT DEFAULT 1,
    `VisibleGender` SMALLINT DEFAULT 1,
    `IsRequierdPESEL` SMALLINT DEFAULT 1,
    `VisiblePESEL` SMALLINT DEFAULT 1,
    `IsRequierdIdentificationCard` SMALLINT DEFAULT 1,
    `VisibleIdentificationCard` SMALLINT DEFAULT 1,
    `IsRequierdExpirationDateNoPersonalCard` SMALLINT DEFAULT 1,
    `VisibleExpirationDateNoPersonalCard` SMALLINT DEFAULT 1,
    `IsRequierdLivingAdress` SMALLINT DEFAULT 1,
    `VisibleLivingAdress` SMALLINT DEFAULT 1,
    `IsRequierdCorrespondenceAdress` SMALLINT DEFAULT 1,
    `VisibleCorrespondenceAdress` SMALLINT DEFAULT 1,
    `IsRequierdCompanyName` SMALLINT DEFAULT 1,
    `VisibleCompanyName` SMALLINT DEFAULT 1,
    `IsRequierdWorkPosition` SMALLINT DEFAULT 1,
    `VisibleWorkPosition` SMALLINT DEFAULT 1,
    PRIMARY KEY(`ID`)
)ENGINE = InnoDB;

-- ----------------------------------------------
/* TABLES PASSWORD */
-- ----------------------------------------------
/* Tables for last three passwords */
CREATE TABLE IF NOT EXISTS `password`
(
	`ID` INT NOT NULL AUTO_INCREMENT,
    `IDUsers` INT NOT NULL,				#REALTION TO TABLES USERS
    `Password` VARCHAR(150) NOT NULL,
    `CratedAt` DATETIME NOT NULL,		#DATA WHEN PASWWORD WAS CREATED
    `ChangedAt` DATETIME NOT NULL		#DATA WHEN PASSWORD WAS UPDATED
    PRIMARY KEY(`ID`)
)ENGINE=InnoDB;


-- ---------------------------------------------
-- TABLES CONNECTIONS
-- ----------------------------------------------
/* Conection blocked and whitelist connections */
CREATE TABLE IF NOT EXISTS `connestions`
(
	`ID` INT NOT NULL AUTO_INCREMENT,
    `IPAddress` VARCHAR(28) NULL,			#IPv4 OR IPv6
    `Port` VARCHAR(10) NULL,			    #IPv4 OR IPv6
    `Device` VARCHAR(255),
    `Url` VARCHAR(255),
    `Blocked` SMALLINT NULL DEFAULT 0,		#BLOCKED ACCESS TO APP
    `Logged` SMALLINT NULL,
    `CreatedAt` DATETIME NULL,				#DATA CREATED
    PRIMARY KEY(`ID`)
)ENGINE=InnoDB;


-- ---------------------------------------------
-- TABLES logs
-- ----------------------------------------------
/* Save information about action in app */
CREATE TABLE IF NOT EXISTS `agreement_history`
(
    `ID` INT NOT NULL AUTO_INCREMENT,
    `IPAddress` VARCHAR(28),
    `Port` VARCHAR(28),
    `Devices` VARCHAR(255),     #WEBSITE APP
    `Request` VARCHAR(50),
    `WebAddress` VARCHAR(200),
    `IDUser` VARCHAR(255),
    `IDAgreements` VARCHAR(255),
    `AnswerAgree` SMALLINT,
    `CreatedAt` DATETIME NOT NULL,
    PRIMARY KEY(`ID`)
)ENGINE = InnoDB;

-- ---------------------------------------------
-- TABLES AGREEMENTS CONFIGURATION
-- ----------------------------------------------
CREATE TABLE IF NOT EXISTS `agreements_configuration`
(
    `ID` INT NOT NULL AUTO_INCREMENT,
    `AgreementGuid` VARCHAR(100) NOT NULL,
    `Title` VARCHAR(150) NOT NULL,
    `Content` TEXT,
    `Attachment` TEXT,
    `Version` INT NOT NULL,
    `IsActived` SMALLINT NOT NULL, #Actived agreements when is false we cant entrence to adreement
    `DateStart` DATE NOT NULL,
    `DateEnd` DATE NOT NULL,
    `CreatedBy` INT NOT NULL,   #RELATION WITH users
    `CreateAt` DATETIME NOT NULL,
    `UpdatedBy` INT,            #RELATION WITH users
    `UpdatedAt` DATETIME,
    PRIMARY KEY(`ID`)
)ENGINE = InnoDB;

-- ---------------------------------------------
-- TABLES AGREEMENTS
-- ----------------------------------------------
CREATE TABLE IF NOT EXISTS `agreements`
(
    `ID` INT NOT NULL AUTO_INCREMENT,
    `IDUsers` INT NOT NULL,
    `IDagreementsConfiguration` INT NOT NULL,
    `AccessGuid` VARCHAR(130),
    `Password` VARCHAR(130),
    `PasswordValidity` DATETIME NOT NULL,   #TIME LIVE PASSWORD - 24H
    `AttemptLogin` INT(2) NULL,
    `AcceptAgreement` INT(1) NULL,
    `DataAccept` DATETIME,
    `IPAddress` VARCHAR(28),
    `Port` VARCHAR(10),
    `Device` VARCHAR(255),
    `HashToAgrremnetForUser` VARCHAR(255),
    `AddedBy` INT NOT NULL,
    `AddedAt` DATETIME,
    PRIMARY KEY(`ID`)
)ENGINE = InnoDB;


-- ---------------------------------------------
-- TABLES SENDED EMAILS
-- ----------------------------------------------
CREATE TABLE IF NOT EXISTS `sended_email`
(
    `ID` INT NOT NULL AUTO_INCREMENT,
    `From` VARCHAR(100) NULL,
    `To` VARCHAR(100) NULL,
    `To_CC` VARCHAR(100) NULL,
    `To_BCC` VARCHAR(100) NULL,
    `Header` TEXT,
    `Subject` VARCHAR(255),
    `Body` TEXT,
    `Attachment` VARCHAR(100),
    `Errors` VARCHAR(255),
    `DateSend` DATETIME,
    `IdUser` INT,
    PRIMARY KEY(`ID`)
)ENGINE = InnoDB;
