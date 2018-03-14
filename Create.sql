create database Video;

use video;

create table User (
	Id int not null auto_increment,
    Username char(25),
    Password char(25),
    Icon char(125),
    Beschreibung text,
    primary key(Id));

create table Genres (
	Id int not null auto_increment,
    Genre varchar(125),
    primary key (Id)
);

create table Medien (
	Id int not null auto_increment,
    Name char(225),
    UserFk int not null,
    primary key(Id),
    foreign key(UserFk) references User(Id)
);

create table MedienGenres (
	Id int not null auto_increment,
    MedienFk int not null,
	GenresFk int not null,
    primary key(Id),
    foreign key(MedienFk) references Medien(Id),
    foreign key(GenresFk) references Genres(Id)
);