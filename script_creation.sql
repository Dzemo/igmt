DROP TABLE IF EXISTS igmt_category;
CREATE TABLE igmt_category (
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
	name varchar(40) NOT NULL,
	color varchar(7) NOT NULL,
	description TEXT,
	CONSTRAINT un_igmt_category UNIQUE (name),
	CONSTRAINT pk_igmt_category PRIMARY KEY (id)
) ENGINE = MYISAM;

INSERT INTO igmt_category (id, name, description, color) VALUES (1, 'Ressource', 'Element that can be gather', '#27ae60');
INSERT INTO igmt_category (id, name, description, color) VALUES (2, 'Building', 'Element that can be build', '#2c3e50');
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
) ENGINE = MYISAM;
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (1, 'Time', 1, 'Time is money', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (2, 'Wood', 1, 'Ressource to build stuff', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (3, 'Rock', 1, 'Ressource to build stuff', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (4, 'Hut', 2, 'Increase maximum population by X', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (5, 'Cabine', 2, 'Increase maximum population by Y', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (6, 'Laboratory', 2, 'You can research some usefull tech here', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (7, 'Workshop', 2, 'You can develop some usefull tools here', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (8, 'Toolcrafting', 3, 'Use tool to chop wood, increase wood gathering efficiency by X%', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (9, 'Apple fall', 4, 'An appel has fallan from a tree', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (10, 'Population Increase', 4, 'Some wandering guy have join our settlement', '');
INSERT INTO igmt_element (id, name, category_id, description, tag) VALUES (11, 'Population management unit', 5, 'Allow to do research about population management', '');

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

INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (2, 4, 'REQUIRE','Wood > 0');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (2, 5, 'REQUIRE','Wood > X');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (3, 5, 'REQUIRE','Stone > 0');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (6, 8, 'REQUIRE','Laboratiry build');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (7, 8, 'REQUIRE','Workshop build');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (10, 4, 'REQUIRE','');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (5, 7, 'REQUIRE','');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (9, 6, 'REQUIRE','');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (7, 3, 'REQUIRE','Workshop build');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (11, 6, 'EXTEND','Population over X');
INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (5, 7, 'EVOLVE','Cost some wood to evolve but offer better population housing');

DROP TABLE IF EXISTS igmt_cost_scaling;
CREATE TABLE igmt_cost_scaling (
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
	name varchar(40) NOT NULL,
	formula varchar(255) NOT NULL,
	CONSTRAINT pk_igmt_cost_scaling PRIMARY KEY (id)
) ENGINE = MYISAM;

/*forumla from http://mrhen.com/blog/?cat=10*/
INSERT INTO igmt_cost_scaling(id, name, formula) VALUES (1, 'None', 'cost( n ) = base');
INSERT INTO igmt_cost_scaling(id, name, formula) VALUES (2, 'Linear', 'cost( n ) = base * n');
INSERT INTO igmt_cost_scaling(id, name, formula) VALUES (3, 'Polynomial', 'cost( n ) = base * n ^ Y');
INSERT INTO igmt_cost_scaling(id, name, formula) VALUES (4, 'Exponential', 'cost( n ) = base * Y ^ ( n - 1 )');

DROP TABLE IF EXISTS igmt_cost;
CREATE TABLE igmt_cost (
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
	element_from_id MEDIUMINT NOT NULL,
	element_to_pay_id MEDIUMINT,
        scaling_id MEDIUMINT,
	base_quantity MEDIUMINT,
	CONSTRAINT pk_igmt_cost PRIMARY KEY (id),
	CONSTRAINT un_igmt_cost UNIQUE (element_from_id, element_to_pay_id),
	CONSTRAINT fk_igmt_scaling FOREIGN KEY (scaling_id) REFERENCES igmt_cost_scaling(id),
	CONSTRAINT fk_igmt_element_from FOREIGN KEY (element_from_id) REFERENCES igmt_element(id),
	CONSTRAINT fk_igmt_element_to_pay FOREIGN KEY (element_to_pay_id) REFERENCES igmt_element(id)
) ENGINE = MYISAM;

INSERT INTO igmt_cost(element_from_id, element_to_pay_id, scaling_id, base_quantity) VALUES (4, 2, 3, 10);
INSERT INTO igmt_cost(element_from_id, element_to_pay_id, scaling_id, base_quantity) VALUES (5, 2, 3, 20);
INSERT INTO igmt_cost(element_from_id, element_to_pay_id, scaling_id, base_quantity) VALUES (5, 3, 3, 10);
