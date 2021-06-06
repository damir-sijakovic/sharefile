
CREATE TABLE `files` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `filename` TEXT,
  `key` TEXT,
  `expiresAt` DATETIME,
  PRIMARY KEY (`id`)  
) ENGINE=InnoDB;

CREATE TABLE sharefile (
	`id` INTEGER NOT NULL AUTO_INCREMENT,
    `databaseCreatedAt` DATETIME,
    PRIMARY KEY (`id`) 
);
