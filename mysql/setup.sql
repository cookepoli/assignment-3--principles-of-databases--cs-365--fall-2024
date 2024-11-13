\W

DROP DATABASE IF EXISTS student_passwords;

CREATE DATABASE `student_passwords` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;

USE student_passwords;

DROP USER IF EXISTS 'passwords_user'@'localhost';

CREATE USER 'passwords_user'@'localhost';
GRANT ALL PRIVILEGES ON student_passwords.* to 'passwords_user'@'localhost';


SET block_encryption_mode = 'aes-256-cbc';
SET @key_str = UNHEX(SHA2('my secret passphrase', 256));
SET @init_vector = RANDOM_BYTES(16);

-- Create Schema

CREATE TABLE IF NOT EXISTS website (
    website_id      SMALLINT        AUTO_INCREMENT,
    website_name    VARCHAR(128)    NOT NULL,
    website_url     VARCHAR(128)    NOT NULL,
    PRIMARY KEY (website_id)
);
CREATE TABLE IF NOT EXISTS user (
    user_id         SMALLINT        AUTO_INCREMENT,
    first_name      VARCHAR(32)     NOT NULL,
    last_name       VARCHAR(32)     NOT NULL,
    email           VARCHAR(320)    NOT NULL,
    PRIMARY KEY (user_id)
);
CREATE TABLE IF NOT EXISTS account (
    account_id      SMALLINT        AUTO_INCREMENT,
    website_id      SMALLINT,
    user_id         SMALLINT,
    username        VARCHAR(32)     NOT NULL,
    password        VARBINARY(512)  NOT NULL,
    creation_time   DATETIME        NOT NULL,
    comment         VARCHAR(256)    NOT NULL,
    PRIMARY KEY (account_id),
    FOREIGN KEY (website_id) REFERENCES website(website_id),
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);

-- Insert values into database

INSERT INTO website
VALUES
  (0, "Blackboard", "https://blackboard.hartford.edu/"),
  (0, "Hawkmail","https://outlook.office.com/"),
  (0, "Wordpress", "http://wordpress.com/"),
  (0, "GMail", "https://mail.google.com/"),
  (0, "Discord", "http://discord.com/"),
  (0, "Epic Games", "http://store.epicgames.com/"),
  (0, "Instacart", "http://www.instacart.com/"),
  (0, "Uber", "http://www.uber.com/"),
  (0, "The Washington Post", "http://www.washingtonpost.com/"),
  (0, "Khan Academy", "http://www.khanacademy.org/");

INSERT INTO user
VALUES
  (0, "Alex", "Cooke-Politikos", "cookepoli@hartford.edu"),
  (0, "Alex", "Cooke-Politikos",  "cookepoli@hartford.edu"),
  (0, "John", "Smith",  "johnnys@gmail.com"),
  (0, "John", "Smith",  "johnnys@gmail.com"),
  (0, "Dylan", "Falco",  "dylan.falco@msn.net"),
  (0, "Alex", "Cooke-Politikos",  "cookepoli@hartford.edu"),
  (0, "George", "Washington",  "george.washington@uswh.gov"),
  (0, "Lewis", "Hamilton",  "7timewdc@gmail.com"),
  (0, "Will", "Lewis",  "will.lewis@washingtonpost.com"),
  (0, "Timmy", "Smith",  "timmys@gmail.com");

INSERT INTO account
VALUES
  (0,1,1, "cookepoli",AES_ENCRYPT("iloveuhart", @key_str, @init_vector), '2019-09-01 12:00:00', "Use this for school things."),
  (0,2,2,"cookepoli",AES_ENCRYPT("iloveuhart", @key_str, @init_vector), '2019-09-01 12:00:00', "School email."),
  (0,3,3, "jsmith17",AES_ENCRYPT("password", @key_str, @init_vector), '2020-08-21 13:21:56', "Personal website."),
  (0,4,4,"johnnys", AES_ENCRYPT("aBcD1234", @key_str, @init_vector), '2010-02-15 08:52:34', "Personal email."),
  (0,5,5,"dfalcon72", AES_ENCRYPT("iL0V3Uh4r7", @key_str, @init_vector), '2017-05-27 18:42:30', "Used for gaming."),
  (0,6,6, "cookiepolitics",AES_ENCRYPT("physics2024", @key_str, @init_vector), '2021-07-17 21:00:17', "Game library."),
  (0,7,7,"gwashington",AES_ENCRYPT("f1rs7pr3s1d3n71776@", @key_str, @init_vector), '1776-07-04 07:00:00', "Deliver groceries to the White House."),
  (0,8,8, "lh44",AES_ENCRYPT("fastdriver1985", @key_str, @init_vector), '2022-12-11 03:40:21', "Got to drive fast."),
  (0,9,9, "willlewisceo",AES_ENCRYPT("wapo2133124", @key_str, @init_vector), '2023-11-05 06:57:00', "The only news outlet."),
  (0,10,10,"tsmith18",AES_ENCRYPT("tsmithm4th", @key_str, @init_vector), '2024-10-01 11:57:56',"Math help website.");
