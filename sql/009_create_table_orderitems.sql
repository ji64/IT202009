CREATE TABLE OrderItems(
	id int auto_increment,
	order_id int,
    product_id int,
	quantity int,
	price int,
    modified    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update current_timestamp,
    created     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	primary key (id),
	foreign key (order_id) references Orders(id),
	foreign key (product_id) references Products(id)
)