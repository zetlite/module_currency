create table if not exists custom_currency
(
    ID int(11) not null auto_increment,
    TIMESTAMP_X timestamp not null default current_timestamp on update current_timestamp,
    DATE_CREATE datetime,
    CODE varchar(50) null,
    COURSE varchar(255) not null,
    PRIMARY KEY(ID)
);