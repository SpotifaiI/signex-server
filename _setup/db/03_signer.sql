use signex;

create table if not exists signer
(
    id        int unsigned auto_increment
        primary key,
    sign      int unsigned                       null,
    hash      varchar(200)                       null,
    signed_at datetime default CURRENT_TIMESTAMP null,
    email     varchar(100)                       null,
    code      varchar(10)                        null,
    constraint signer_sign_fk
        foreign key (sign) references sign (id)
);
