create table user
(
    id         integer unsigned not null
        constraint user_pk
            primary key autoincrement,
    name       varchar(100) default null,
    created_at date         default CURRENT_TIMESTAMP,
    email      varchar(100)     not null,
    password   varchar(200) default null
);

create unique index user_email_uindex
    on user (email);
