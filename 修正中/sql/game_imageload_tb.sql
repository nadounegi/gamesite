create table game_imageload_tb(
  id int auto_increment,
  games_no int not null,
  folder_name varchar(255) not null,
  file_name varchar(255) not null,
  delete_ku char(1) not null,
  insert_at datetime not null,
  update_at datetime not null,

  primary key(
    id
  )
);
