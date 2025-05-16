# Hackify

## Description

Hackify est une plateforme web permettant d’organiser, de gérer et de suivre des hackathons de manière collaborative et structurée.

### Objectif : Faciliter l'organisation des hackathons pour les organisateurs, offrir un espace clair pour les participants, et permettre une évaluation juste via un jury.
### Problème résolu : L'absence d'une plateforme unifiée pour la gestion complète de hackathons, du lancement à l'évaluation.
### Fonctionnalités principales :
  - Gestion des hackathons par les organisateurs.
  - Inscriptions des participants.
  - Évaluation des projets par un jury avec attribution de notes.
  - Ajout de ressources et accompagnement par des coachs.
  - Visualisation sur carte via Geoapify.
  - Notifications par email (acceptation/refus).

### Contexte du Projet
Ce projet a été établi comme projet d'étude à *Esprit Engineering School* pour l'année scolaire *2024/2025*. Il s'inscrit dans le cadre de la formation en ingénierie et vise à appliquer des concepts avancés de développement web et de gestion de base de données.

## Table des matières

- [Installation](#installation)
- [Utilisation](#utilisation)
- [Contributions](#contributions)
- [Mots-clés](#mots-clés)

## Installation

1. Clonez le repository :
```bash
git clone https://github.com/fatenke/hackify_web
```
3. Installez les dépendances PHP :
```bash
composer install
npm run
npm dev run
```
4. Configurez votre base de données dans le fichier `.env`.

6. Exécutez les migrations :
```bash
php bin/console doctrine:migrations:migrate
```
5. Lancez le serveur Symfony :
```bash
symfony server:start
```

## Utilisation
### Installation de MySQL
Symfony utilise une base de données pour stocker les données de l'application. MySQL est l’un des systèmes les plus couramment utilisés.
1. Téléchargez MySQL depuis le site officiel : [MySQL - Téléchargement](https://dev.mysql.com/downloads/)
2. Installez MySQL en suivant les instructions pour votre système d’exploitation.
3. Une fois installé, vérifiez que MySQL fonctionne correctement en exécutant la commande suivante dans votre terminal :
```bash
mysql -u root -p
```
### Installation de XAMPP
XAMPP est un environnement de développement Apache simple à installer, qui inclut PHP, MySQL et phpMyAdmin. 
1. Téléchargez XAMPP depuis le site officiel : [XAMPP - Téléchargement](https://www.apachefriends.org/fr/index.html)
2. Installez XAMPP en suivant les instructions d’installation.
3. Lancez le *XAMPP Control Panel* et démarrez les modules *Apache* et *MySQL*.
4. Vous pouvez accéder à phpMyAdmin via : [http://localhost/phpmyadmin](http://localhost/phpmyadmin)

### Installation de PHP
Pour utiliser ce projet, vous devez installer PHP, Voici les etapes:
1. Télécharger PHP à partir du site officiel : [ PHP-Téléchargement] ( https://www.php.net/download.php).
2.Installez PHP en suivant les instructions spécifiques à votre systeme d'exploitation:
- Pour *Windows*, vous pouvez utiliser [XAMPP] (https://apachefriends.org/fr/index.html)
3. Vérifiez l'installation de PHP en exécutant la commande suivante dans votre terminal:
```bash
php -v
```
###  Installation de Composer
Composer est un gestionnaire de dépendances indispensable pour Symfony.
1. Téléchargez Composer depuis le site officiel : [Composer - Téléchargement](https://getcomposer.org/download/)
2. Suivez les instructions d’installation selon votre système d’exploitation (Windows, macOS, Linux).
3. Une fois installé, vérifiez que Composer fonctionne correctement en exécutant la commande suivante dans votre terminal :
```bash
composer -v
```
### Installation de Symfony CLI
La Symfony CLI est un outil en ligne de commande permettant de créer, exécuter et gérer facilement des projets Symfony.
1. Téléchargez la Symfony CLI depuis le site officiel : [Symfony CLI - Installation](https://symfony.com/download)
2. Suivez les instructions d’installation selon votre système d’exploitation (Windows, macOS, Linux).
3. Vérifiez que l'installation est correcte en exécutant la commande suivante dans votre terminal :
```bash
symfony -v
```

## Fonctionnalités

- Authentification des utilisateurs (organisateur, participant, coach, jury)
- Gestion des rôles et des permissions
- Création et édition de hackathons
- Inscription aux hackathons
- Soumission de projets par les participants
- Évaluation des projets par un jury avec notation
- Ajout de ressources par les coachs pour les projets
- Visualisation des lieux des hackathons sur une carte

## Contributions

1. **Fork le projet**: Allez sur la page Github du projet et cliquez sur le bouton **Fork** dans le coin supérieur droit pour créer une copie du projet dans votre propre compte Github.
2. **Clonez votre fork**: Clonez le fork sur votre machine locale:
```bash
git clone https://github.com/fatenke/hackify_web
```
3. **Créez une nouvelle branche**
```bash
git checkout -b nomdubranche
```
4. **Committer aprés modifications pour le enregistrer**
```bash
git add . 
git commit -m 'Ajout de la fonctionnalité x'
```
5. **Pousser vos modifications**
```bash
git push origin nomdubranche
```
4. **Soumettez une Pull Request**
```bash
git pull origin nomdubranche
```

## Mots-clés
Symfony 6  
PHP 8.1+
Bundle
Service
Doctrine
QR Code
Symfony Mailer
Symfony Notifier
RH 
Doctrine ORM  
Bootstrap 5  
Mantis Admin Template   
Composer  
Symfony CLI  
MySQL

