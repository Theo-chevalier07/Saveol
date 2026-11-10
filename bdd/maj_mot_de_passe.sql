-- A lancer une seule fois sur une base déjà créée (WAMP ou serveur des SISR)
-- Oblige chaque compte à changer son mot de passe provisoire à la première connexion
ALTER TABLE utilisateur ADD mdp_a_changer BOOLEAN NOT NULL DEFAULT 1 AFTER mot_de_passe;
