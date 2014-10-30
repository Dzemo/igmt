DROP TABLE IF EXISTS igmt_element;
CREATE TABLE igmt_element (
	name varchar(40) NOT NULL,
	category varchar(20) NOT NULL,
	description text,
	tag text,
	CONSTRAINT pk_igmt_elements PRIMARY KEY (name),
	CONSTRAINT fk_igmt_category FOREIGN KEY (name) REFERENCES igmt_category(name)
);

INSERT INTO igmt_element (name, category, description, tag) VALUES ('Hut', 'Building', 'Increase maximum population', '');
INSERT INTO igmt_element (name, category, description, tag) VALUES ('Wood', 'Ressource', 'Ressource to build stuff', '');
INSERT INTO igmt_element (name, category, description, tag) VALUES ('Population Increase 10', 'Event', 'Increase maximum population by 10', '');



DROP TABLE IF EXISTS igmt_link;
CREATE TABLE igmt_link (
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
	need varchar(40) NOT NULL,
	allow varchar(40) NOT NULL,
	type varchar(20) NOT NULL,	/* REQUIRE or EXTENDS */
	conditions text,
	CONSTRAINT pk_igmt_link PRIMARY KEY (id),
	CONSTRAINT fk_igmt_link_from FOREIGN KEY (need) REFERENCES igmt_element(name),
	CONSTRAINT fk_igmt_link_to FOREIGN KEY (allow) REFERENCES igmt_element(name)
);

INSERT INTO igmt_link (need, allow, type, conditions) VALUES ('Wood', 'Hut', 'REQUIRE','Wood > 0');
INSERT INTO igmt_link (need, allow, type, conditions) VALUES ('Hut', 'Population Increase 10', 'REQUIRE','Hut built');


DROP TABLE IF EXISTS igmt_category;
CREATE TABLE igmt_category (
	name varchar(40) NOT NULL,
	color varchar(7) NOT NULL,
	CONSTRAINT pk_igmt_category PRIMARY KEY (name)
);

INSERT INTO igmt_category (name, color) VALUES ('Building', '#332516');
INSERT INTO igmt_category (name, color) VALUES ('Ressource', '#2dce3c');
INSERT INTO igmt_category (name, color) VALUES ('Technology', '#2d53ce');
INSERT INTO igmt_category (name, color) VALUES ('Event', '#ce2d2d');
