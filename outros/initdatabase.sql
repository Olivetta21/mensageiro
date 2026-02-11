
-- User and Login
create table users (
     id serial primary key,
     name varchar(255) not null,
     email varchar(255) not null unique,
     password varchar(255) not null,
     created_at timestamp default current_timestamp
 );

 create table access_tokens (
     id serial primary key,
     user_id integer references users(id),
     token varchar(255) not null unique,
     created_at timestamp default current_timestamp,
     expires_at timestamp not null
 );

create table user_logins (
    id serial primary key,
    user_id integer references users(id),
    login_time timestamp default current_timestamp,
    ip_address varchar(255) not null
);

-- Messages
  -- User to User
create table contacts_linked (
    id serial primary key,
    user_id_a integer references users(id),
    user_id_b integer references users(id),
    created_at timestamp default current_timestamp
);

create table messages (
    id serial primary key,
    contact_linked_id integer references contacts_linked(id),
    sender_id integer references users(id),
    content text not null,
    created_at timestamp default current_timestamp
);
  -- Group Messages
create table group (
    id serial primary key,
    name varchar(255) not null,
    created_at timestamp default current_timestamp
);

create table group_members (
    id serial primary key,
    group_id integer references group(id),
    user_id integer references users(id),
    created_at timestamp default current_timestamp
);

create table group_messages (
    id serial primary key,
    group_id integer references group(id),
    sender_id integer references users(id),
    content text not null,
    created_at timestamp default current_timestamp
);