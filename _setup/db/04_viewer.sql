use signex;

create table if not exists viewer
(
    id         int unsigned auto_increment
        primary key,
    sign       int unsigned                       null,
    email      varchar(100)                       null,
    code       varchar(10)                        null,
    created_at datetime default CURRENT_TIMESTAMP null,
    constraint viewer_sign_fk
        foreign key (sign) references sign (id)
);
