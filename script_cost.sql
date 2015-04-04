DROP TABLE IF EXISTS igmt_cost;
CREATE TABLE igmt_cost (
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
	element_from_id MEDIUMINT NOT NULL,
	element_to_pay_id MEDIUMINT,
	base_quantity MEDIUMINT,
	CONSTRAINT pk_igmt_cost PRIMARY KEY (id),
	CONSTRAINT un_igmt_cost UNIQUE (element_from_id, element_to_pay_id),
	CONSTRAINT fk_element_from FOREIGN KEY (element_from_id) REFERENCES igmt_element(id),
	CONSTRAINT fk_igmt_element_to_pay FOREIGN KEY (element_to_pay_id) REFERENCES igmt_element(id)
);