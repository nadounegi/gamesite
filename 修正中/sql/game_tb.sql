CREATE table game_tb(
    id int not null auto_increment,
    type_no int not null,
    category_no int not null,
    game_title varchar(50) not null,
    game_date date not null,
    game_description text(100) not null,
    delete_ku char(1) not null,
    insert_at datetime not null,
    update_at datetime not null,

    primary key(
        id
    )
);
