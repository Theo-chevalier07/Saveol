SET NAMES utf8mb4;
-- Création de la base
DROP DATABASE IF EXISTS saveol;
CREATE DATABASE saveol CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE saveol;

-- Table des utilisateurs (clients, gestionnaires, admins)
CREATE TABLE utilisateur (
    id_utilisateur INT AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    adresse VARCHAR(255),
    mot_de_passe VARCHAR(255) NOT NULL,
    mdp_a_changer BOOLEAN NOT NULL DEFAULT 1,
    role VARCHAR(20) NOT NULL DEFAULT 'client',
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_utilisateur)
) ENGINE=InnoDB;

-- Table des catégories
CREATE TABLE categorie (
    id_categorie INT AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_categorie)
) ENGINE=InnoDB;

-- Table des produits
CREATE TABLE produit (
    id_produit INT AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(8,2) NULL,
    conditionnement VARCHAR(50) NOT NULL,
    image VARCHAR(255),
    mis_en_avant BOOLEAN NOT NULL DEFAULT 0,
    id_categorie INT NOT NULL,
    PRIMARY KEY (id_produit),
    FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie)
) ENGINE=InnoDB;

-- Table des commandes
CREATE TABLE commande (
    id_commande INT AUTO_INCREMENT,
    date_commande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(20) NOT NULL DEFAULT 'En attente',
    id_utilisateur INT NOT NULL,
    PRIMARY KEY (id_commande),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
) ENGINE=InnoDB;

-- Table des lignes de commande
CREATE TABLE ligne_commande (
    id_commande INT NOT NULL,
    id_produit INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(8,2) NOT NULL,
    PRIMARY KEY (id_commande, id_produit),
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande),
    FOREIGN KEY (id_produit) REFERENCES produit(id_produit)
) ENGINE=InnoDB;
