CREATE TABLE Products(
	id int auto_increment,
	name varchar(30) not null unique,
	category varchar(30) not null,
	description text,
	quantity int default 0,
	price int default 99999,
	user_id int,
	visibility tinyint default 0,
    modified    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update current_timestamp,
    created     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	primary key (id),
	FOREIGN KEY (user_id) REFERENCES Users (id)
)