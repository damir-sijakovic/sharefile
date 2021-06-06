DROP DATABASE IF EXISTS ds_sharefile;
CREATE DATABASE ds_sharefile; 
DROP USER IF EXISTS 'ds_sharefile'@'localhost';
CREATE USER 'ds_sharefile'@'localhost' IDENTIFIED BY 'ds_sharefile';
GRANT ALL ON ds_sharefile.* TO 'ds_sharefile'@'localhost';
USE ds_sharefile;
