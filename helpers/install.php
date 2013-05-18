<?php

/*
 * This file just installs the database and tables
 */

// Connect to database
require('dataBase.php');
$database = new dataBase("localhost","amckinlay","mong00s31","schedulesnap");
$database->connect();
echo "Connected";



// Create Rota
$mysql = 'CREATE TABLE rota(
    RotaID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    RotaName varchar(255) NOT NULL,
    RotaType ENUM("week","block"),
    RotaOwner varchar(255) NOT NULL)';
$database->sql($mysql);

$mysql = 'CREATE TABLE strip(
    StripID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    RotaID int NOT NULL,
    StripDescrip varchar(255))';
$database->sql($mysql);

$mysql = 'CREATE TABLE block(
    BlockID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    RotaID int NOT NULL,
    BlockDescrip varchar(255))';
$database->sql($mysql);



// Create users
$mysql = 'CREATE TABLE user(
    UserName varchar(255) NOT NULL PRIMARY KEY,
    Name varchar(255) NOT NULL,
    PassHash varchar(255))';
$database->sql($mysql);



// Create attribute - Not sure if the name is a good PK for this one
$mysql = 'CREATE TABLE attribute(
    AttributeName varchar(255) NOT NULL PRIMARY KEY,
    AttributeValue varchar(255) NOT NULL)';
$database->sql($mysql);


// Create M-M tables
$mysql = 'CREATE TABLE rotaUser(
    RotaID int NOT NULL,
    UserName varchar(255) NOT NULL,
    CONSTRAINT pkgrid PRIMARY KEY (UserName,RotaID))';
$database->sql($mysql);
$mysql = 'CREATE TABLE userAttribute(
    UserName varchar(255) NOT NULL,
    AttributeName varchar(255) NOT NULL,
    CONSTRAINT pkgrid PRIMARY KEY (UserName,AttributeName))';
$database->sql($mysql);

$mysql = 'CREATE TABLE blockEntry(
    UserName varchar(255) NOT NULL,
    BlockID int NOT NULL,
    StripID int NOT NULL,
    Time varchar(255),
    CONSTRAINT pkgrid PRIMARY KEY (BlockID,StripID,UserName))';
$database->sql($mysql);


/*
 * We need to create this user currently for the displaying of rotas to work
 * correctly.
 */

$mysql = 'INSERT INTO user (username, name)
                VALUES (" ", " ")';
$database->sql($mysql);

?>
