CREATE TABLE `pvpgn_d2ladder` (
  `charname` varchar(16) NOT NULL default '',
  `title` varchar(16) default NULL,
  `level` int(4) default '1',
  `class` varchar(10) default NULL,
  `experience` float(8,0) default NULL,
  `rank` int(11) default NULL,
  `type` char(2) default 'SC',
  `dead` varchar(5) default 'ALIVE',
  `game` varchar(4) NOT NULL default '',
  PRIMARY KEY  (`charname`),
  KEY `experience` (`experience`)
);