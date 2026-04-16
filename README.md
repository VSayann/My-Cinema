# Epitech - Projet 4 : My Cinema
*26/01/2026 - 08/02/2026*

## Démos GitHub Pages :
[Lien GitHub Pages](https://vsayann.github.io/My-Cinema/frontend)

## Contexte
Pour le quatrième prjet dans ma formation Epitech, il nous a été demandé de créer une arrière-boutique ('*back-office*') pour un gérant de cinéma fictif. Il doit pouvoir créer, modifier, supprimer et afficher : les films à l'affiche, les salles de projection et le planning des séances 
L'application doit être pensée uniquement pour le gérant et les salariés, pas pour le public donc le front-end n'a pas d'importance, on se focalise beaucoup plus sur l'agencement du back-end.

### Technos utilisées :
- HTML+CSS
- Javascript
- PHP ('*MVC*', '*OOP*')
- PDO
- MySQL
- API REST

### Exigences Générales :
- Aucun framework ni maquette
- CRUD complet en SQL
- Base de données relationnelle
- Architecture exigée :
    - Un dossier backend avec PHP qui fait tourner l'API
    - Un dossier frontend avec HTML+CSS et JavaScript qui consomme l'API

### Exigences Films :
- Gestion de tous les films qui ont été, sont ou seront diffusés (Ajouter, Modifier, Supprimer)
- Liste paginée des films
- ⚠️ Suppression possible uniquement pour les films sans séance associée

### Exigences Salles :
- Gestion des salles (Créer, Modifier, Supprimer)
- Affichage complet (Nom, Capacité, Type - Standard, 3D, IMAX)
- ⚠️ En cas de suppression de salle, gérer les séances en lien avec celle-ci.

### Exigences Séances :
- Gestion des séances (Créer, Modifier, Supprimer)
- Chaque séance doit être associé à : un film, une salle, une date (+heure) précise
- ⚠️ Impossible de créer deux séances au même horaire dans une même salle.
- ⚠️ La durée du film doit être prise en compte pour éviter les chevauchements.

## Comment y accéder ?

### Étape 1 - Cloner le projet :

```bash
git clone https://github.com/VSayann/My-Cinema.git
cd My-Cinema
```

### Étape 2 - Configurer votre Database :

```bash
# Lancer la base de données
mysql -u root -p

# Initialiser "my_cinema"
mysql -u root -p < script.sql
```

### Étape 3 - Configurer le fichier .env :

Le projet utilise un fichier .env pour sécuriser les identifiants permettant de se connecter à la base de données :

```bash
# Copier le fichier exemple
cd backend
cp .env.example .env
# Éditer le fichier .env avec vos identifiants
```
```env
DB_HOST= # Nom d'hebergeur de la DB 
DB_NAME=my_cinema # Cela ne change pas
DB_USER= # Nom d'utilisateur de la DB
DB_PASS= # MdP d'utilisateur de la DB
```

### Étape 4 - Configurer le serveur backend :

Le projet utilise le serveur PHP intégré.
Pour ce faire, retournez à la racine du projet puis ouvrez 2 terminals différents :
```bash
# Terminal 1 - Backend
cd backend
php -S localhost:8000

# Terminal 2 - Frontend
cd frontend
php -S localhost:3000
```

Crédits : \
Sayann Valmond - Étudiant chez Epitech Lille\
[GitHub](https://github.com/VSayann) / [Linkedin](https://www.linkedin.com/in/sayann-valmond/) / [Porfolio](https://vsayann.github.io/Portfolio/)
