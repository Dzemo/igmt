DROP TABLE IF EXISTS igmt_element;
CREATE TABLE igmt_element (
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
	name varchar(40) NOT NULL,
	category varchar(20) NOT NULL,
	description text,
	tag text,
	CONSTRAINT pk_igmt_element PRIMARY KEY (id),
	CONSTRAINT un_ihmy_element UNIQUE (name),
	CONSTRAINT fk_igmt_category FOREIGN KEY (name) REFERENCES igmt_category(name)
);

INSERT INTO igmt_element (id, name, category, description, tag) VALUES (1, 'Hut', 'Building', 'Increase maximum population by X', '');
INSERT INTO igmt_element (id, name, category, description, tag) VALUES (2, 'Cabine', 'Building', 'Increase maximum population by Y', '');
INSERT INTO igmt_element (id, name, category, description, tag) VALUES (3, 'Wood', 'Ressource', 'Ressource to build stuff', '');
INSERT INTO igmt_element (id, name, category, description, tag) VALUES (4, 'Workshop', 'Building', 'You can develop some usefull tools here', '');
INSERT INTO igmt_element (id, name, category, description, tag) VALUES (5, 'Apple fall', 'Event', 'An appel has fallan from a tree', '');
INSERT INTO igmt_element (id, name, category, description, tag) VALUES (6, 'Laboratory', 'Building', 'You can research some usefull tech here', '');
INSERT INTO igmt_element (id, name, category, description, tag) VALUES (7, 'Population Increase', 'Event', 'Increase maximum population every X seconds', '');
INSERT INTO igmt_element (id, name, category, description, tag) VALUES (8, 'Population management unit', 'Building extension', 'Allow to do research about population management', '');



DROP TABLE IF EXISTS igmt_link;
CREATE TABLE igmt_link (
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
	from_id MEDIUMINT NULL,
	to_id MEDIUMINT NOT NULL,
	type varchar(20) NOT NULL,	/* REQUIRE or EXTEND or EVOLVE */
	conditions text,
	CONSTRAINT pk_igmt_link PRIMARY KEY (id),
	CONSTRAINT fk_igmt_link_from FOREIGN KEY (from_id) REFERENCES igmt_element(id),
	CONSTRAINT fk_igmt_link_to FOREIGN KEY (to_id) REFERENCES igmt_element(id)
);

INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (3, 1, 'REQUIRE','Wood > 0');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (3, 4, 'REQUIRE','Wood > X');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (1, 7, 'REQUIRE','Hut built');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (5, 6, 'REQUIRE','');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (4, 6, 'REQUIRE','');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (8, 2, 'REQUIRE','');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (8, 6, 'EXTEND','Population over X');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (2, 1, 'EVOLVE','Cost some wood to evolve but offer better population housing');


DROP TABLE IF EXISTS igmt_category;
CREATE TABLE igmt_category (
	name varchar(40) NOT NULL,
	color varchar(7) NOT NULL,
	CONSTRAINT pk_igmt_category PRIMARY KEY (name)
);

INSERT INTO igmt_category (name, color) VALUES ('Building', '#2c3e50');
INSERT INTO igmt_category (name, color) VALUES ('Ressource', '#27ae60');
INSERT INTO igmt_category (name, color) VALUES ('Technology', '#2980b9');
INSERT INTO igmt_category (name, color) VALUES ('Event', '#c0392b');
INSERT INTO igmt_category (name, color) VALUES ('Building extension', '#34495e');
