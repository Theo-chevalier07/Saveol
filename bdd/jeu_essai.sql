SET NAMES utf8mb4;
USE saveol;

-- Utilisateurs : 1 admin, 1 gestionnaire, 3 clients
-- Mots de passe : Admin123! / Gestion123! / Client123!
INSERT INTO utilisateur (nom, prenom, email, telephone, adresse, mot_de_passe, role, date_creation) VALUES
('Netralli', 'Marc', 'admin@saveol.fr', '0298401010', '1 rue de la Presqu''île, 29470 Plougastel-Daoulas', '3eb3fe66b31e3b4d10fa70b5cad49c7112294af6ae4e476a1c405155d45aa121', 'admin', '2026-09-01 08:00:00'),
('Farez', 'Julie', 'gestion@saveol.fr', '0298401020', '1 rue de la Presqu''île, 29470 Plougastel-Daoulas', '14cb1392e34869c5ad54c212f8d4db51c78a851675e5f4cded2c550a5fc92792', 'gestionnaire', '2026-09-01 08:30:00'),
('Le Gall', 'Yann', 'client@primeurs-elorn.fr', '0298223344', '12 quai de l''Elorn, 29800 Landerneau', '598e94d875ce2d6f38c297129b5c059afe1b4f6590682b19e27c3deecf6c4140', 'client', '2026-09-15 10:12:00'),
('Morvan', 'Sophie', 'achats@marche-frais-quimper.fr', '0298556677', '45 avenue de la Gare, 29000 Quimper', '598e94d875ce2d6f38c297129b5c059afe1b4f6590682b19e27c3deecf6c4140', 'client', '2026-09-20 14:40:00'),
('Kerjean', 'Thomas', 'commandes@bretagne-fruits.fr', '0299112233', 'ZA de Kergaradec, 29490 Guipavas', '598e94d875ce2d6f38c297129b5c059afe1b4f6590682b19e27c3deecf6c4140', 'client', '2026-10-02 09:05:00');

-- Catégories
INSERT INTO categorie (libelle) VALUES
('Tomates'),
('Tomates cerises'),
('Fraises'),
('Mâche');

-- Produits (prix par colis, NULL = sur devis)
INSERT INTO produit (nom, description, prix, conditionnement, image, mis_en_avant, id_categorie) VALUES
('Tomate grappe', 'Tomates rondes vendues sur la grappe, cultivées sous serre en Bretagne.', 14.50, 'Colis 6 kg', 'tomate_grappe.jpg', 1, 1),
('Tomate ronde', 'La tomate classique, ferme et juteuse, idéale en salade.', 12.90, 'Colis 6 kg', 'tomate_ronde.jpg', 0, 1),
('Tomate cœur de bœuf', 'Grosse tomate charnue, peu de pépins, parfaite en tranches.', 21.00, 'Colis 5 kg', 'tomate_coeur_de_boeuf.jpg', 1, 1),
('Tomate allongée', 'Tomate allongée à la chair dense, idéale pour la cuisson.', 15.80, 'Colis 5 kg', 'tomate_allongee.jpg', 0, 1),
('Tomate jaune', 'Tomate ronde jaune, douce et peu acide.', 18.40, 'Colis 5 kg', 'tomate_jaune.jpg', 0, 1),
('Tomate en palette', 'Tomates rondes en grande quantité pour les grossistes, prix selon le volume.', NULL, 'Palette 500 kg', 'tomate_palette.jpg', 0, 1),
('Tomate cerise rouge', 'Petite tomate ronde et sucrée, à croquer à l''apéritif.', 2.10, 'Barquette 250 g', 'cerise_rouge.jpg', 1, 2),
('Tomate cerise allongée', 'Tomate cerise en forme d''olive, très sucrée.', 2.40, 'Barquette 250 g', 'cerise_allongee.jpg', 0, 2),
('Mélange de tomates cerises', 'Mélange de tomates cerises rouges, jaunes et noires.', 2.90, 'Barquette 300 g', 'cerise_melange.jpg', 0, 2),
('Tomate cerise en grappe', 'Tomates cerises rouges vendues sur la grappe.', 3.20, 'Barquette 400 g', 'cerise_grappe.jpg', 0, 2),
('Fraise Gariguette', 'Fraise allongée, parfumée et légèrement acidulée, la reine du printemps.', 3.90, 'Barquette 250 g', 'fraise_gariguette.jpg', 1, 3),
('Fraise Ciflorette', 'Fraise sucrée et peu acide, à la chair fondante.', 3.70, 'Barquette 250 g', 'fraise_ciflorette.jpg', 0, 3),
('Fraise Charlotte', 'Fraise ronde au goût de fraise des bois.', 4.20, 'Barquette 500 g', 'fraise_charlotte.jpg', 0, 3),
('Fraise Mara des bois', 'Fraise très parfumée, récoltée de juin à octobre.', 4.50, 'Barquette 250 g', 'fraise_mara.jpg', 0, 3),
('Mâche nantaise', 'Mâche lavée et prête à consommer, cultivée dans le Val Nantais.', 1.60, 'Sachet 150 g', 'mache_sachet.jpg', 1, 4),
('Mâche en vrac', 'Mâche non lavée pour la vente au poids en rayon.', 9.80, 'Colis 2 kg', 'mache_vrac.jpg', 0, 4),
('Mélange mâche et roquette', 'Mélange de jeunes pousses, lavé et prêt à consommer.', 1.90, 'Sachet 125 g', 'mache_roquette.jpg', 0, 4);

-- Commandes
INSERT INTO commande (date_commande, statut, id_utilisateur) VALUES
('2026-10-05 09:15:00', 'Terminée', 3),
('2026-10-12 11:30:00', 'Expédiée', 4),
('2026-10-20 08:45:00', 'Validée', 3),
('2026-10-28 16:20:00', 'Refusée', 5),
('2026-11-02 10:05:00', 'En attente', 5),
('2026-11-03 07:50:00', 'En attente', 4);

-- Lignes de commande (prix_unitaire = prix du produit au moment de la commande)
INSERT INTO ligne_commande (id_commande, id_produit, quantite, prix_unitaire) VALUES
(1, 1, 20, 14.50),
(1, 7, 100, 2.10),
(1, 15, 50, 1.60),
(2, 3, 10, 21.00),
(2, 11, 80, 3.90),
(3, 2, 30, 12.90),
(3, 9, 60, 2.90),
(3, 16, 15, 9.80),
(4, 13, 40, 4.20),
(5, 1, 50, 14.50),
(5, 8, 120, 2.40),
(5, 12, 60, 3.70),
(6, 5, 12, 18.40),
(6, 17, 70, 1.90);
