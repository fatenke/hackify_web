# 🎯 Hackify

## 📝 Description

Hackify est une plateforme web permettant d’organiser, de gérer et de suivre des hackathons de manière collaborative et structurée.

- **Objectif** : Faciliter l'organisation des hackathons pour les organisateurs, offrir un espace clair pour les participants, et permettre une évaluation juste via un jury.
- **Problème résolu** : L'absence d'une plateforme unifiée pour la gestion complète de hackathons, du lancement à l'évaluation.
- **Fonctionnalités principales** :
  - Gestion des hackathons par les organisateurs.
  - Inscriptions des participants.
  - Évaluation des projets par un jury avec attribution de notes.
  - Ajout de ressources et accompagnement par des coachs.
  - Visualisation sur carte via Geoapify.
  - Notifications par email (acceptation/refus).

## 📚 Table des matières

- [Installation](#installation)
- [Utilisation](#utilisation)
- [Fonctionnalités](#fonctionnalités)
- [Contribution](#contribution)
- [Licence](#licence)

## ⚙️ Installation

1. Clonez le repository :
git clone https://github.com/fatenke/hackify_web
2. Installez les dépendances PHP :
composer install
npm run
npm dev run

4. Configurez votre base de données dans le fichier `.env`.

5. Exécutez les migrations :
php bin/console doctrine:migrations:migrate
5. Lancez le serveur Symfony :
symfony server:start

## 💡 Utilisation
### Technologies utilisées
- **Symfony** : Backend (PHP)
- **Twig** : Moteur de templates
- **MySQL** : Base de données

## ✨ Fonctionnalités

- Authentification des utilisateurs (organisateur, participant, coach, jury)
- Gestion des rôles et des permissions
- Création et édition de hackathons
- Inscription aux hackathons
- Soumission de projets par les participants
- Évaluation des projets par un jury avec notation
- Ajout de ressources par les coachs pour les projets
- Visualisation des lieux des hackathons sur une carte

## 🤝 Contribution

Les contributions sont les bienvenues ! Pour contribuer :

1. Fork le repo
2. Crée une branche : `git checkout -b feature/mon-feature`
3. Commit tes changements : `git commit -m "Ajout de ma fonctionnalité"`
4. Push : `git push origin feature/mon-feature`
5. Crée une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus d’informations.
