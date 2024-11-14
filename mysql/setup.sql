\W

DROP DATABASE IF EXISTS student_passwords;

CREATE DATABASE `student_passwords` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;

USE student_passwords;

DROP USER IF EXISTS 'passwords_user'@'localhost';

CREATE USER 'passwords_user'@'localhost';
GRANT ALL PRIVILEGES ON student_passwords.* to 'passwords_user'@'localhost';


SET block_encryption_mode = 'aes-256-cbc';
SET @key_str = '3848510D1461EC58';
SET @init_vector = '7D5D001D126491F2';

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
CREATE TABLE IF NOT EXISTS registersfor (
    website_id      SMALLINT,
    user_id         SMALLINT,
    username        VARCHAR(32)     NOT NULL,
    password        VARBINARY(512)  NOT NULL,
    creation_time   DATETIME        NOT NULL,
    comment         VARCHAR(256)    NOT NULL,
    PRIMARY KEY (website_id, user_id),
    FOREIGN KEY (website_id)
      REFERENCES website(website_id)
      ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (user_id)
      REFERENCES user(user_id)
      ON UPDATE CASCADE ON DELETE CASCADE
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

INSERT INTO registersfor
VALUES
  (1,1, "cookepoli", AES_ENCRYPT("iloveuhart", @key_str, @init_vector), '2019-09-01 12:00:00', "Use this for school things."),
  (2,2,"cookepoli", AES_ENCRYPT("iloveuhart", @key_str, @init_vector), '2019-09-01 12:00:00', "School email."),
  (3,3, "jsmith17", AES_ENCRYPT("password", @key_str, @init_vector), '2020-08-21 13:21:56', "Personal website."),
  (4,4,"johnnys", AES_ENCRYPT("aBcD1234", @key_str, @init_vector), '2010-02-15 08:52:34', "Personal email."),
  (5,5,"dfalcon72", AES_ENCRYPT("iL0V3Uh4r7", @key_str, @init_vector), '2017-05-27 18:42:30', "Used for gaming."),
  (6,6, "cookiepolitics", AES_ENCRYPT("physics2024", @key_str, @init_vector), '2021-07-17 21:00:17', "Game library."),
  (7,7,"gwashington", AES_ENCRYPT("f1rs7pr3s1d3n71776@", @key_str, @init_vector), '1776-07-04 07:00:00', "Deliver groceries to the White House."),
  (8,8, "lh44", AES_ENCRYPT("fastdriver1985", @key_str, @init_vector), '2022-12-11 03:40:21', "Got to drive fast."),
  (9,9, "willlewisceo", AES_ENCRYPT("wapo2133124", @key_str, @init_vector), '2023-11-05 06:57:00', "The only news outlet."),
  (10,10,"tsmith18", AES_ENCRYPT("tsmithm4th", @key_str, @init_vector), '2024-10-01 11:57:56', "Math help website.");
