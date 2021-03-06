CREATE TABLE Orders(
	id int auto_increment,
	user_id int,
	total_price int,
	payment_method varchar(30) not null,
	shipping_address varchar(100) not null,
    modified    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update current_timestamp,
    created     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	primary key (id),
	foreign key (user_id) references Users(id)
)