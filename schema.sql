create database if not exists database_registos;
use database_registos;


create table users (
	id int unique auto_increment primary key,
    username varchar(50) not null unique,
    password varchar(255) not null
);