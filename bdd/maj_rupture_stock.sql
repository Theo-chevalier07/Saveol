-- Ajout de la rupture de stock sur les produits (1 = disponible, 0 = rupture)
ALTER TABLE produit ADD disponible BOOLEAN NOT NULL DEFAULT 1 AFTER mis_en_avant;
