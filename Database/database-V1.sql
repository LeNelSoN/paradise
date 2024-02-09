CREATE TABLE IF NOT EXISTS classe (
    classe_name VARCHAR(255) PRIMARY KEY,
    description TEXT
);

CREATE TABLE IF NOT EXISTS alimentation (
    alimentation_name VARCHAR(255) PRIMARY KEY,
    description TEXT
);

CREATE TABLE IF NOT EXISTS zone_geographique (
    zone_geographique_name VARCHAR(255) PRIMARY KEY,
    description TEXT
);

CREATE TABLE IF NOT EXISTS fiche (
    specie_name VARCHAR(255) PRIMARY KEY,
    classe VARCHAR(255),
    alimentation VARCHAR(255),
    poidsMoyen VARCHAR(255),
    longevite VARCHAR(255),
    longueur VARCHAR(255),
    taille VARCHAR(255),
    zone_geographique VARCHAR(255),
    description TEXT,
    FOREIGN KEY (classe) REFERENCES classe(classe_name),
    FOREIGN KEY (alimentation) REFERENCES alimentation(alimentation_name),
    FOREIGN KEY (zone_geographique) REFERENCES zone_geographique(zone_geographique_name)
);

ALTER TABLE animal ADD CONSTRAINT fk_animal_fiche FOREIGN KEY (specie) REFERENCES fiche(specie_name);