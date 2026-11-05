<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Authentification</title>
	<style>
		body { margin: 0; padding: 1rem; background: #f3f4f6; font-family: Arial, sans-serif; }
		main { box-sizing: border-box; max-width: 380px; margin: 10vh auto; padding: 2rem; background: #fff; border-radius: 8px; box-shadow: 0 2px 12px #0002; }
		h1 { margin-top: 0; text-align: center; }
		label { display: block; margin: 1rem 0 .4rem; }
		input { box-sizing: border-box; width: 100%; padding: .7rem; border: 1px solid #bbb; border-radius: 4px; }
		button { width: 100%; margin-top: 1.2rem; padding: .75rem; border: 0; border-radius: 4px; background: #2457a7; color: white; font-size: 1rem; cursor: pointer; }
		.message { color: #b42318; }
	</style>
</head>
<body>
	<main>
		<h1>Connexion</h1>
		<form method="post" action="index.php?uc=auth&action=connexion">
			<label for="email">Email</label>
			<input id="email" name="email" type="email" required>

			<label for="mot_de_passe">Mot de passe</label>
			<input id="mot_de_passe" name="mot_de_passe" type="password" required>

			<button type="submit">Se connecter</button>
		</form>
	</main>
</body>
</html>
