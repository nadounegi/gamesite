CREATE table game_user_tb(
  user_id int auto_increment,
  user_name varchar(255)not null,
  user_password varchar(255)not null,
  user_email varchar(255)not null,
  delete_ku char(1) not null,
  insert_at datetime not null,
  update_at datetime not null,

  primary key(
    user_id
  )
);