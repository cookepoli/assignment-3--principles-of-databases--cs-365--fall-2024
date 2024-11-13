<?php

const DBNAME = "student_passwords";
const DBHOST = "localhost";
const DBUSER = "passwords_user";
CONST BLOCK_ENCRYPTION_MODE = "aes-256-cbc";
const KEY_STR = '3848510D1461EC58';
const INIT_VECTOR = '7D5D001D126491F2';

define("NOTHING_FOUND",  0);
define("SEARCH",         1);
define("UPDATE",         2);
define("INSERT",         3);
define("DELETE",         4);
