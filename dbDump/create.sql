-- auto-generated definition
create table visitors
(
    id          int auto_increment,
    ip_address  varchar(128)  null comment 'ipv6 - 128 bit',
    user_agent  varchar(200)  null,
    view_date   datetime      null,
    page_url    varchar(100)  null,
    views_count int default 0 null,
    constraint visitors_id_uindex
        unique (id)
);

alter table visitors
    add primary key (id);

