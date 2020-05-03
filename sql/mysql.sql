CREATE TABLE `survey_answer` (
  `answerid` int(12) unsigned NOT NULL auto_increment,
  `replyid` int(12) NOT NULL default '0',
  `qid` int(12) NOT NULL default '0',
  `answer` text NOT NULL,
  PRIMARY KEY  (`answerid`),
  KEY `answerid` (`answerid`)
) TYPE=MyISAM ;

CREATE TABLE `survey_form` (
  `formid` int(12) NOT NULL auto_increment,
  `f_name` varchar(255) NOT NULL default '',
  `f_desc` text NOT NULL default '',
  `f_startdate` int(12) default NULL,
  `f_expiredate` int(12) default NULL,
  `f_isactive` tinyint(1) default NULL,
  `f_submit_message` text,
  `f_restrictmode` varchar(25) default NULL,
  PRIMARY KEY  (`formid`),
  UNIQUE KEY `fid` (`formid`)
) TYPE=MyISAM ;

CREATE TABLE `survey_question` (
  `qid` int(12) unsigned NOT NULL auto_increment,
  `formid` int(12) NOT NULL default '0',
  `ele_type` varchar(20) NOT NULL default '',
  `q_caption` varchar(255) NOT NULL default '',
  `q_description` varchar(255) default '',
  `q_order` smallint(2) NOT NULL default '0',
  `q_req` tinyint(1) NOT NULL default '1',
  `q_value` text NOT NULL,
  `q_display` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`qid`),
  KEY `q_order` (`q_order`)
) TYPE=MyISAM ;

CREATE TABLE `survey_reply` (
  `replyid` int(12) unsigned NOT NULL auto_increment,
  `formid` int(12) NOT NULL default '0',
  `replydate` int(12) NOT NULL default '0',
  `replyemail` varchar(100) default '0',
  `replyip` varchar(25) NOT NULL default '',
  `replyvalidated` tinyint(1) NOT NULL default '0',
  `vkey` varchar(100) default NULL,
  `purged` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`replyid`),
  KEY `replyid` (`replyid`)
) TYPE=MyISAM ;