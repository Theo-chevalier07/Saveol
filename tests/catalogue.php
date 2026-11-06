<?php
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
define('SAVEOL', true);
require ($argv[1] ?? dirname(__DIR__)) . '/includes/modele/gestion_catalogue.php';
$bdd = new PDO('sqlite::memory:', null, null, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
$bdd->exec('CREATE TABLE categorie(id_categorie INTEGER PRIMARY KEY AUTOINCREMENT,libelle TEXT); CREATE TABLE produit(id_produit INTEGER PRIMARY KEY AUTOINCREMENT,nom TEXT,description TEXT,prix REAL,conditionnement TEXT,image TEXT,mis_en_avant INTEGER,id_categorie INTEGER); CREATE TABLE ligne_commande(id_commande INTEGER,id_produit INTEGER,quantite INTEGER,prix_unitaire REAL)');
$n=0;
function ok($v) { global $n; if (!$v) { throw new Exception('Échec du contrôle ' . ($n+1)); } $n++; }
function refuse($f) { try { $f(); } catch (DomainException $e) { ok(true); return; } throw new Exception('Refus attendu.'); }

enregistrer_categorie($bdd,'Tomates');$cat=(int)$bdd->lastInsertId();ok(count(categories_catalogue($bdd))===1);
enregistrer_categorie($bdd,'Fruits',$cat);ok(categories_catalogue($bdd)[0]['libelle']==='Fruits');
refuse(fn()=>enregistrer_categorie($bdd,' '));refuse(fn()=>enregistrer_categorie($bdd,str_repeat('a',51)));
$d=['nom'=>'Tomates','description'=>'<script>test</script>','conditionnement'=>'250 g','prix'=>'2,50','id_categorie'=>(string)$cat,'mis_en_avant'=>'1'];
enregistrer_produit($bdd,$d,'images/produits/test.png');$id=(int)$bdd->lastInsertId();$p=produit_catalogue($bdd,$id);ok($p['prix']==2.50);ok($p['mis_en_avant']==1);ok(count(produits_catalogue($bdd))===1);
$d['prix']='';unset($d['mis_en_avant']);enregistrer_produit($bdd,$d,$p['image'],$id);$p=produit_catalogue($bdd,$id);ok($p['prix']===null);ok($p['mis_en_avant']==0);ok($p['image']==='images/produits/test.png');
foreach(['-1','1000000','1.234','NaN',[]] as $prix){$bad=$d;$bad['prix']=$prix;refuse(fn()=>enregistrer_produit($bdd,$bad,null,$id));}
$bad=$d;$bad['id_categorie']='99999999';refuse(fn()=>enregistrer_produit($bdd,$bad,null,$id));
refuse(fn()=>supprimer_catalogue($bdd,'categorie',$cat));
$bdd->prepare('INSERT INTO ligne_commande VALUES(1,?,2,2.5)')->execute([$id]);refuse(fn()=>supprimer_catalogue($bdd,'produit',$id));
$bdd->exec('DELETE FROM ligne_commande');supprimer_catalogue($bdd,'produit',$id);ok(produit_catalogue($bdd,$id)===false);
supprimer_catalogue($bdd,'categorie',$cat);ok(categories_catalogue($bdd)===[]);
refuse(fn()=>id_catalogue([]));refuse(fn()=>id_catalogue('1 OR 1=1'));
ok(importer_image_catalogue(null,'ancienne.png')==='ancienne.png');
refuse(fn()=>importer_image_catalogue(['error'=>UPLOAD_ERR_INI_SIZE]));
echo "$n contrôles catalogue OK\n";