CREATE table category_tb(
  id int not null auto_increment,
  category_no int not null,
  category_name varchar(50) not null,
  delete_ku char(1) not null,
  insert_at datetime not null,
  update_at datetime not null,

  primary key(
    id
  )
);