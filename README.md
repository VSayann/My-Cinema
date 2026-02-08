# My cinéma

## Comment y accéder ?

### Étape 1 - Cloner le projet :

```bash
git clone https://github.com/EpitechWebAcademiePromo2027/W-WEB-102-LIL-1-1-my_cinema-27.git
cd W-WEB-102-LIL-1-1-my_cinema-27
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

### ENJOY !