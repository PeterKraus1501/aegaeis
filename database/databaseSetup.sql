--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `id` bigint(20) NOT NULL,
  `email` varchar(64) NOT NULL COMMENT 'email to communicate with user e.g. new password',
  `password` varchar(64) NOT NULL,
  `encryptedPassword` char(32) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL COMMENT 'In-game name of the user',
  `lastLogin` datetime DEFAULT NULL,
  `ip` char(20) DEFAULT NULL,
  `applicationKey` char(32) DEFAULT NULL COMMENT 'a key which grants access to application',
  `applicationKeyValidUntil` datetime DEFAULT NULL COMMENT 'key is valid until'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

