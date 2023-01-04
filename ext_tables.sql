CREATE TABLE tx_httpmonitoring_domain_model_site (
	title varchar(255) NOT NULL DEFAULT '',
	uri int(11) unsigned NOT NULL DEFAULT '0',
	address int(11) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE tx_httpmonitoring_domain_model_uri (
	site int(11) unsigned DEFAULT '0' NOT NULL,
	path varchar(255) NOT NULL DEFAULT '',
	laststatuswaserror smallint(1) unsigned NOT NULL DEFAULT '0',
	log int(11) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE tx_httpmonitoring_domain_model_address (
	site int(11) unsigned DEFAULT '0' NOT NULL,
	email varchar(255) NOT NULL DEFAULT '',
	name varchar(255) NOT NULL DEFAULT ''
);

CREATE TABLE tx_httpmonitoring_domain_model_log (
	uri int(11) unsigned DEFAULT '0' NOT NULL,
	statuscode int(11) NOT NULL DEFAULT '0'
);
