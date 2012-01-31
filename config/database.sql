-- --------------------------------------------------------

-- 
-- Table `tl_layout`
-- 

CREATE TABLE `tl_layout` (
  `addjQuery` char(1) NOT NULL default '',
  `jQueryVersion` varchar(16) NOT NULL default '',
  `jQuerySource` varchar(16) NOT NULL default '',
  `jQueryScripts` blob NULL,
  `jQuerycombineScripts` char(1) NOT NULL default '',
  `addjQueryUI` char(1) NOT NULL default '',
  `jQueryUIVersion` varchar(16) NOT NULL default '',
  `jQueryUISource` varchar(16) NOT NULL default '',
  `jQueryUITheme` varchar(256) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;