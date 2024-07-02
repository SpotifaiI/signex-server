use signex;

create table if not exists user
(
    id         int unsigned auto_increment
        primary key,
    name       varchar(100)                       null,
    email      varchar(100)                       not null,
    password   varchar(200)                       not null,
    created_at datetime default CURRENT_TIMESTAMP null,
    constraint user_email_uindex
        unique (email)
);
