CREATE table type_tb(
  id int not null auto_increment,
  type_no int not null,
  type_name varchar(50) not null,
  delete_ku char(1) not null,
  insert_at datetime not null,
  update_at datetime not null,

  primary key(
    id
  )
);

