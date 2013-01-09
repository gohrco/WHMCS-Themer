DROP TABLE IF EXISTS `mod_themer_settings`;


-- command split --


CREATE TABLE `mod_themer_settings` (
  `key` text NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- command split --


INSERT INTO `mod_themer_settings` (`key`, `value`) VALUES
('enable', '0'),
('usetheme', '1'),
('restrictip', ''),
('restrictuser', ''),
('fontselect', ''),
('license', ''),
('localkey', '');


-- command split --


DROP TABLE IF EXISTS `mod_themer_themes`;


-- command split --


CREATE TABLE `mod_themer_themes` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- command split --


INSERT INTO `mod_themer_themes` (`id`, `name`, `description`, `params`) VALUES
(1, 'Default WHMCS Theme', 'This is the theme contains the default settings from WHMCS.  It cannot be edited.', '{"fullwidth":"0","font":"Helvetica Neue","logo":"templates/default/img/whmcslogo.png","bodytype":"1","bodyoptnsolid":"#efefef","bodyoptnfrom":"#efefef","bodyoptnto":"#efefef","bodyoptndir":"1","bodyoptnpattern":"45degreee_fabric.png","bodyoptnimage":"","alinks":"3","alinksstd":"#004a95","alinksvis":"#004a95","alinkshov":"#005580","navbarfrom":"#333333","navbarto":"#222222","navbartxt":"#999999","navbarhov":"#999999","navbardropbg":"#ffffff","navbardroptxt":"#333333","navbardrophl":"#0088cc","txtelemgffont":"- use primary -","txtelemgfsize":"13","textelmgfcolor":null,"txtelemh1font":"- use primary -","txtelemh1size":"30","textelmh1color":null,"txtelemh2font":"- use primary -","txtelemh2size":"24","textelmh2color":null,"txtelemh3font":"- use primary -","txtelemh3size":"18","textelmh3color":null,"txtelemh4font":"- use primary -","txtelemh4size":"14","textelmh4color":null,"txtelemh5font":"- use primary -","txtelemh5size":"12","textelmh5color":null,"txtelemh6font":"- use primary -","txtelemh6size":"11","textelmh6color":null}'),
(2, 'Default WHMCS Theme - Wide', 'This theme is a wide version of the WHMCS Default theme to demonstrate the appearance of a wide theme.', '{"fullwidth":"1","contentbg":"#ffffff","font":"Helvetica Neue","logo":"modules/addons/themer/assets/whmcslogo-clear.png","bodytype":"3","bodyoptnsolid":"#efefef","bodyoptnfrom":"#efefef","bodyoptnto":"#efefef","bodyoptndir":"1","bodyoptnpattern":"absurdidad.png","bodyoptnimage":"","alinks":"3","alinksstd":"#004a95","alinksvis":"#004a95","alinkshov":"#005580","navbarfrom":"#333333","navbarto":"#222222","navbartxt":"#999999","navbarhov":"#999999","navbardropbg":"#ffffff","navbardroptxt":"#333333","navbardrophl":"#0088cc","txtelemgffont":"- use primary -","txtelemgfsize":"13","txtelemgfcolor":"#333333","txtelemh1font":"- use primary -","txtelemh1size":"30","txtelemh1color":"#333333","txtelemh2font":"- use primary -","txtelemh2size":"24","txtelemh2color":"#333333","txtelemh3font":"- use primary -","txtelemh3size":"18","txtelemh3color":"#333333","txtelemh4font":"- use primary -","txtelemh4size":"14","txtelemh4color":"#333333","txtelemh5font":"- use primary -","txtelemh5size":"12","txtelemh5color":"#333333","txtelemh6font":"- use primary -","txtelemh6size":"11","txtelemh6color":"#999999"}'),
(3, 'Sahara', 'A simple desert-themed style.', '{"fullwidth":"1","contentbg":"#e3dac3","font":"Lora","logo":"modules/addons/themer/assets/whmcslogo-clear2.png","bodytype":"3","bodyoptnsolid":"#efefef","bodyoptnfrom":"#efefef","bodyoptnto":"#efefef","bodyoptndir":"1","bodyoptnpattern":"bedge_grunge.png","bodyoptnimage":"","alinks":"1","alinksstd":"#b3752e","alinksvis":"#b86a04","alinkshov":"#ed9b57","navbarfrom":"#ebba6c","navbarto":"#8f5828","navbartxt":"#e0dcd9","navbarhov":"#ffffff","navbardropbg":"#ffffff","navbardroptxt":"#333333","navbardrophl":"#e0b28f","txtelemgffont":"- use primary -","txtelemgfsize":"13","txtelemgfcolor":"#593c26","txtelemh1font":"- use primary -","txtelemh1size":"30","txtelemh1color":"#42331a","txtelemh2font":"- use primary -","txtelemh2size":"24","txtelemh2color":"#5e3a18","txtelemh3font":"- use primary -","txtelemh3size":"18","txtelemh3color":"#666166","txtelemh4font":"- use primary -","txtelemh4size":"14","txtelemh4color":"#333333","txtelemh5font":"- use primary -","txtelemh5size":"12","txtelemh5color":"#333333","txtelemh6font":"- use primary -","txtelemh6size":"11","txtelemh6color":"#999999"}'),
(4, 'Fountain', 'A mellow, blue theme.', '{"fullwidth":"1","contentbg":"#afc9d6","font":"Helvetica Neue","logo":"modules/addons/themer/assets/whmcslogo-clear2.png","bodytype":"1","bodyoptnsolid":"#e4f0f5","bodyoptnfrom":"#afe6d7","bodyoptnto":"#f51119","bodyoptndir":"1","bodyoptnpattern":"absurdidad.png","bodyoptnimage":"","alinks":"3","alinksstd":"#004a95","alinksvis":"#004a95","alinkshov":"#4e52ba","navbarfrom":"#717cad","navbarto":"#334170","navbartxt":"#dfdfe6","navbarhov":"#ffffff","navbardropbg":"#ffffff","navbardroptxt":"#333333","navbardrophl":"#60b6db","txtelemgffont":"- use primary -","txtelemgfsize":"13","txtelemgfcolor":"#333333","txtelemh1font":"Handlee","txtelemh1size":"30","txtelemh1color":"#0e1021","txtelemh2font":"Handlee","txtelemh2size":"24","txtelemh2color":"#333333","txtelemh3font":"Handlee","txtelemh3size":"18","txtelemh3color":"#333333","txtelemh4font":"- use primary -","txtelemh4size":"14","txtelemh4color":"#333333","txtelemh5font":"- use primary -","txtelemh5size":"12","txtelemh5color":"#333333","txtelemh6font":"- use primary -","txtelemh6size":"11","txtelemh6color":"#999999"}'),
(5, 'Whiteboard', 'A theme to show the possibilities of WHMCS Themer, not specifically created for actual use.', '{"fullwidth":"1","contentbg":"#e3e1b6","font":"Abel","logo":"modules/addons/themer/assets/whmcslogo-clear2.png","bodytype":"3","bodyoptnsolid":"#efefef","bodyoptnfrom":"#8a494b","bodyoptnto":"#f0e7e7","bodyoptndir":"5","bodyoptnpattern":"old_mathematics.png","bodyoptnimage":"http://spacetelescope.org/static/archives/images/screen/heic0715a.jpg","alinks":"1","alinksstd":"#52ab64","alinksvis":"#121314","alinkshov":"#df3deb","navbarfrom":"#ffffff","navbarto":"#dae4f5","navbartxt":"#252529","navbarhov":"#cd7fdb","navbardropbg":"#000000","navbardroptxt":"#ffffff","navbardrophl":"#d1e7f0","txtelemgffont":"- use primary -","txtelemgfsize":"13","txtelemgfcolor":"#333333","txtelemh1font":"Alice","txtelemh1size":"30","txtelemh1color":"#71bd6b","txtelemh2font":"- use primary -","txtelemh2size":"24","txtelemh2color":"#cf1017","txtelemh3font":"- use primary -","txtelemh3size":"18","txtelemh3color":"#5162e0","txtelemh4font":"- use primary -","txtelemh4size":"14","txtelemh4color":"#e6e263","txtelemh5font":"- use primary -","txtelemh5size":"12","txtelemh5color":"#f0dde0","txtelemh6font":"- use primary -","txtelemh6size":"11","txtelemh6color":"#999999"}');