SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

create table `admins`(
  `s_no` integer primary key  AUTO_INCREMENT,
  `email` varchar(256) unique,
  `username` varchar(256) unique,
  `password` text,
  `superAdmin` varchar(4)
) ENGINE = InnoDB ;


create table `allusers`(
  `s_no` integer primary key  AUTO_INCREMENT,
  `email` varchar(256) unique,
  `username` varchar(256) unique,
  `password` text,
  `token` text,
  `status` text,
  `fullname` text,
  `dob` text,
  `userdesc` text,
  `DP` text,
  `show_profile` text NULL,
  `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  unique(`username`)
) ENGINE = InnoDB ;

create table `notifications`(
  `s_no` integer primary key  AUTO_INCREMENT,
  `username` varchar(256),
  `q_sno` integer DEFAULT NULL,
  `a_sno` integer DEFAULT NULL,
  `r_sno` integer DEFAULT NULL,
  `q_like_sno` integer DEFAULT NULL,
  `a_like_sno` integer DEFAULT NULL,
  `res_username` varchar(256) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  foreign key (`username`) references allusers(`username`)
  ON DELETE cascade,
  foreign key (`res_username`) references allusers(`username`)
  ON DELETE cascade,
  foreign key (`q_sno`) references question(`q_sno`)
  ON DELETE cascade,
  foreign key (`a_sno`) references answers(`a_sno`)
  ON DELETE cascade,
  foreign key (`r_sno`) references replies(`r_sno`)
  ON DELETE cascade,
  foreign key (`q_like_sno`) references question_like(`like_sno`)
  ON DELETE cascade,
  foreign key (`a_like_sno`) references answer_like(`like_sno`)
  ON DELETE cascade
) ENGINE = InnoDB ;

create table `categories`(
  `s_no` integer primary key  AUTO_INCREMENT,
  `category` text
) ENGINE = InnoDB ;

create table `contactus`(
  `s_no` integer primary key  AUTO_INCREMENT,
  `name` text,
  `email` text,
  `message` text,
  `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB ;

create table `question`(
  `q_sno` integer primary key  AUTO_INCREMENT,
  `email` text,
  `username` varchar(256),
  `title` text,
  `titledesc` text,
  `category` text,
  `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  foreign key (`username`) references allusers(`username`)
  ON DELETE cascade
) ENGINE = InnoDB;

create table `question_like`(
  `like_sno` integer primary key  AUTO_INCREMENT,
  `q_sno` integer,
  `askedby` text,
  `likedby` text,
  `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  foreign key (`q_sno`) references question(`q_sno`)
  ON DELETE cascade
);

create table `answers`(
  `a_sno` integer primary key  AUTO_INCREMENT,
  `q_sno` integer,
  `qCategory` text,
  `email` text,
  `username` text,
  `answer` text,
  `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  foreign key (`q_sno`) references question(`q_sno`)
  ON DELETE cascade
);

create table `answer_like`(
  `like_sno` integer primary key  AUTO_INCREMENT,
  `q_sno` integer,
  `a_sno` integer,
  `answeredby` text,
  `likedby` text,
  `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  foreign key (`q_sno`) references question(`q_sno`)
  ON DELETE cascade,
  foreign key (`a_sno`) references answers(`a_sno`)
  ON DELETE cascade
);

create table `replies`(
  `r_sno` integer primary key  AUTO_INCREMENT,
  `q_sno` integer,
  `a_sno` integer,
  `q_category` text,
  `email` text,
  `username` text,
  `reply` text,
  `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  foreign key (`q_sno`) references question(`q_sno`)
  ON DELETE cascade,
  foreign key (`a_sno`) references answers(`a_sno`)
  ON DELETE cascade
);


CREATE TABLE `report_ques` ( 
  `report_sno` integer NOT NULL primary key AUTO_INCREMENT ,
  `q_sno` integer NOT NULL ,  
  `q_ref_sno` integer NOT NULL , -- for foreign key  
  `q_user` TEXT NOT NULL ,  
  `q_category` TEXT NOT NULL ,  
  `r_user` varchar(256) NOT NULL ,  
  `r_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,  
  UNIQUE  `q_sno` (`q_sno`, `r_user`),
  foreign key (`q_ref_sno`) references question(`q_sno`) ON DELETE cascade
) ENGINE = InnoDB;

CREATE TABLE `report_ans` ( 
  `report_sno` integer NOT NULL primary key AUTO_INCREMENT ,
  `q_sno` integer NOT NULL ,  
  `a_sno` integer NOT NULL ,  
  `a_ref_sno` integer NOT NULL , -- for foreign key  
  `a_user` TEXT NOT NULL ,  
  `q_category` TEXT NOT NULL ,  
  `r_user` varchar(256) NOT NULL ,  
  `r_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,  
  UNIQUE  `a_sno` (`a_sno`, `r_user`),
  foreign key (`q_sno`) references question(`q_sno`) ON DELETE cascade,
  foreign key (`a_ref_sno`) references answers(`a_sno`) ON DELETE cascade
) ENGINE = InnoDB;

CREATE TABLE `report_reply` ( 
  `report_sno` integer NOT NULL primary key AUTO_INCREMENT ,
  `q_sno` integer NOT NULL ,  
  `a_sno` integer NOT NULL ,  
  `r_sno` integer NOT NULL ,  
  `r_ref_sno` integer NOT NULL , -- for foreign key  
  `reply_user` TEXT NOT NULL ,  
  `q_category` TEXT NOT NULL ,  
  `report_user` varchar(256) NOT NULL ,  
  `report_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,  
  UNIQUE  `r_sno` (`r_sno`, `report_user`),
  foreign key (`q_sno`) references question(`q_sno`) ON DELETE cascade,
  foreign key (`r_ref_sno`) references replies(`r_sno`) ON DELETE cascade,
  foreign key (`a_sno`) references answers(`a_sno`) ON DELETE cascade
) ENGINE = InnoDB;


CREATE TABLE `view_user` ( 
  `u_sno` integer NOT NULL primary key AUTO_INCREMENT ,
  `username` varchar(256) NOT NULL unique ,  
  `email` varchar(256) NOT NULL unique,  
  `dob` TEXT NOT NULL ,  
  `DP` TEXT NOT NULL ,  
  `userdesc` TEXT NOT NULL,  
  `score` integer NOT NULL,
  `show` TEXT NOT NULL
) ENGINE = InnoDB;


-- password is adminadmin 
-- you are advised to assign another account as super Admin and delete this account for safety purpose
INSERT into `admins` (`username`,`email`,`password`,`superAdmin`) Values ("test","test@test.com","$2y$10$SKg2nYGUuaGaGRg5oAJBie6GplBXlJktqmSHAuyBoQecQrclR1RPK","yes");
INSERT INTO `allusers` (`email`,`username`,`password`,`token`,`status`,`fullname`,`userdesc`,`DP`,`show_profile`) VALUES ("test@test.com", "test", "$2y$10$SKg2nYGUuaGaGRg5oAJBie6GplBXlJktqmSHAuyBoQecQrclR1RPK", "0", "active", "Test", "you are advised to assign another account as super Admin and delete this account for safety purpose", "noDP.jpg", "public");



INSERT INTO `categories` (`category`) VALUES('others');