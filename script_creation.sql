
DROP TABLE IF EXISTS igmt_category;
CREATE TABLE igmt_category (
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
	name varchar(40) NOT NULL,
	color varchar(7) NOT NULL,
	description TEXT,
	CONSTRAINT un_igmt_category UNIQUE (name),
	CONSTRAINT pk_igmt_category PRIMARY KEY (id)
) ENGINE = MYISAM ;  

INSERT INTO igmt_category (id, name, description, color) VALUES (1, 'Building', 'Element that can be build', '#2c3e50');
INSERT INTO igmt_category (id, name, description, color) VALUES (2, 'Ressource', 'Element that can be gather', '#27ae60');
INSERT INTO igmt_category (id, name, description, color) VALUES (3, 'Technology', 'Element that can be researched in the Laboratory and improve other element of the game', '#2980b9');
INSERT INTO igmt_category (id, name, description, color) VALUES (4, 'Event', 'Element that can occur at random time', '#c0392b');
INSERT INTO igmt_category (id, name, description, color) VALUES (5, 'Building extension', 'Element that can be build on another building', '#34495e');

DROP TABLE IF EXISTS igmt_element;
CREATE TABLE igmt_element (
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
	name varchar(40) NOT NULL,
	category_id MEDIUMINT NOT NULL,
	description text,
	tag text,
	CONSTRAINT pk_igmt_element PRIMARY KEY (id),
	CONSTRAINT un_igmt_element UNIQUE (name),
	CONSTRAINT fk_igmt_category FOREIGN KEY (name) REFERENCES igmt_category(id)
) ENGINE = MYISAM ;

INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (1, 'Hut', 1, 'Increase maximum population by X', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (2, 'Cabine', 1, 'Increase maximum population by Y', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (3, 'Wood', 2, 'Ressource to build stuff', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (4, 'Workshop', 3, 'You can develop some usefull tools here', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (5, 'Apple fall', 4, 'An appel has fallan from a tree', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (6, 'Laboratory', 1, 'You can research some usefull tech here', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (7, 'Population Increase', 4, 'Increase maximum population every X seconds', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (8, 'Population management unit', 5, 'Allow to do research about population management', '');



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
) ENGINE = MYISAM;

INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (3, 1, 'REQUIRE','Wood > 0');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (3, 4, 'REQUIRE','Wood > X');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (1, 7, 'REQUIRE','Hut built');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (5, 6, 'REQUIRE','');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (4, 6, 'REQUIRE','');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (8, 2, 'REQUIRE','');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (8, 6, 'EXTEND','Population over X');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (2, 1, 'EVOLVE','Cost some wood to evolve but offer better population housing');


