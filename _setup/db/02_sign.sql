use signex;

create table if not exists sign
(
    id         int unsigned auto_increment
        primary key,
    user       int unsigned  null,
    hash       varchar(300)  null,
    content    varchar(1000) null,
    created_at datetime      null,
    file       varchar(100)  null,
    constraint sign_user_fk
        foreign key (user) references user (id)
);
