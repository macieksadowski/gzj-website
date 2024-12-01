CREATE TABLE `setlists_entries`
(
	`id` Int(11) NOT NULL AUTO_INCREMENT,
    `order` Int UNSIGNED NOT NULL,
    `song_id` bigint(20) unsigned NOT NULL,
    `ev_id` Int(11) UNIQUE NOT NULL,
    primary key (`id`),
    foreign key (song_id) references songs(id),
    foreign key (ev_id) references events(ev_id),
    constraint uk_ev_order unique(`ev_id`, `order`)
);