<?php

$installer = $this;
$installer->startSetup();

$installer->run("
  -- DROP TABLE IF EXISTS {$this->getTable('news')};
  CREATE TABLE {$this->getTable('news')} (
  news_id int(11) unsigned NOT NULL auto_increment,
  title varchar(255) NOT NULL default '',
  content text NOT NULL default '',
  status smallint(6) NOT NULL default '0',
  created_time datetime NULL,
  PRIMARY KEY (news_id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();