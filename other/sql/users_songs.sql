CREATE TABLE `users_songs` (
  `user_id` int(11) NOT NULL,
  `song_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`song_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8