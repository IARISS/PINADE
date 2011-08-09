CREATE TABLE adeserver (id BIGINT AUTO_INCREMENT, nom VARCHAR(255) NOT NULL UNIQUE, description TEXT, identifier VARCHAR(255), ade_url VARCHAR(255), login VARCHAR(255), login_ade_project_id VARCHAR(255), created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE categorie (id BIGINT AUTO_INCREMENT, url VARCHAR(255), nom VARCHAR(255), description TEXT, logo VARCHAR(255), weight BIGINT DEFAULT 0, in_menu TINYINT(1) DEFAULT '0', edt_id BIGINT, INDEX url_idx (url), INDEX edt_id_idx (edt_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE edt (id BIGINT AUTO_INCREMENT, nom VARCHAR(255) NOT NULL UNIQUE, description VARCHAR(255), ade_project_id VARCHAR(255), liens_utiles TEXT, adeserver_id BIGINT, id_piano_day VARCHAR(255) DEFAULT '0,1,2,3,4', start_timestamp BIGINT DEFAULT 1283119200, width BIGINT DEFAULT 800, height BIGINT DEFAULT 600, display_mode VARCHAR(255) DEFAULT '1057855', display_conf_id VARCHAR(255) DEFAULT '8', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX adeserver_id_idx (adeserver_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE message (id BIGINT AUTO_INCREMENT, texte TEXT, promotion_id BIGINT NOT NULL, semaine BIGINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX promotion_id_idx (promotion_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE page (id BIGINT AUTO_INCREMENT, url VARCHAR(255) NOT NULL UNIQUE, title VARCHAR(255), text TEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE promotion (id BIGINT AUTO_INCREMENT, url VARCHAR(255), nom VARCHAR(255), description TEXT, categorie_id BIGINT NOT NULL, weight BIGINT DEFAULT 0, in_menu TINYINT(1) DEFAULT '1', id_tree VARCHAR(255), branch_id VARCHAR(255), select_branch_id VARCHAR(255), select_id VARCHAR(255), INDEX url_idx (url), INDEX categorie_id_idx (categorie_id), PRIMARY KEY(id)) ENGINE = INNODB;
ALTER TABLE categorie ADD CONSTRAINT categorie_edt_id_edt_id FOREIGN KEY (edt_id) REFERENCES edt(id);
ALTER TABLE edt ADD CONSTRAINT edt_adeserver_id_adeserver_id FOREIGN KEY (adeserver_id) REFERENCES adeserver(id);
ALTER TABLE message ADD CONSTRAINT message_promotion_id_promotion_id FOREIGN KEY (promotion_id) REFERENCES promotion(id);
ALTER TABLE promotion ADD CONSTRAINT promotion_categorie_id_categorie_id FOREIGN KEY (categorie_id) REFERENCES categorie(id);
