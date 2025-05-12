# 🎓🧠 KARRINI – Plateforme éducative et psychologique

**KARRINI** est une **plateforme web et desktop** innovante développée en Tunisie dans le cadre d’un projet académique de fin d’études à *Esprit School of Engineering*.

Ce projet a été conçu pour rassembler enseignants, étudiants et parents dans un espace numérique commun, interactif et bienveillant. Il centralise à la fois des outils éducatifs et des ressources de soutien psychologique.

---

## 📌 Description du Projet

Ce projet a été développé dans le cadre du module **PIDEV 3A47** à *Esprit School of Engineering*.  
Il vise à :

- ✅ Centraliser les outils éducatifs et psychologiques dans une seule plateforme  
- ✅ Simplifier la gestion des formations, examens, cours et événements académiques  
- ✅ Offrir un espace confidentiel de **soutien psychologique gratuit** aux étudiants et enseignants

---

## 🏷️ Topics GitHub recommandés

education
javafx
symfony
mental-health
student-support
esprit-school-of-engineering
school-management

**Technologies utilisées :**

- 💻 **JavaFX** pour l'application Desktop
- 🌐 **Symfony (PHP)** pour l'application Web

---


---

## 🧠 Mots-clés (SEO)

> Karrini, Esprit School of Engineering, JavaFX, Symfony, plateforme éducative, soutien psychologique, gestion scolaire, examens, élèves, enseignants, parents, Tunisie, PIDEV 3A, e-learning, consultation psychologique gratuite, desktop Java, web PHP.

---

## 📚 Table des Matières

- [Installation](#installation)  
- [Utilisation](#utilisation)  
- [Fonctionnalités](#fonctionnalités)  
- [Captures d’écran](#captures-décran)  
- [Contribuer](#contribuer)  
- [Licence](#licence)  
- [Contact](#contact)  

---

## 🛠️ Installation

### 🌐 Application Web – Symfony

```bash
git clone https://github.com/votre-utilisateur/karrini-web.git
cd karrini-web
composer install
cp .env.example .env
# Modifier les variables de connexion à la base de données
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony server:start

```

### 🖥️ Application Desktop – JavaFX

1. Cloner le dépôt : `git clone https://github.com/votre-utilisateur/karrini-desktop.git`
2. Ouvrir le projet dans un IDE (ex : IntelliJ IDEA)
3. Ajouter JavaFX à votre configuration (JavaFX SDK)
4. Compiler et exécuter le projet

---

## 🚀 Utilisation

- 👨‍🏫 **Enseignants** : ajout de cours, évaluations, suivi des étudiants
- 👨‍🎓 **Étudiants** :  consultation des formations, passage d’examens, accès aux ressources et à l’aide psychologique
- 👨‍👩‍👧‍👦 **Parents** : suivi pédagogique et comportemental des enfants
- 🧠 **Psychologues** : prise en charge des demandes d’accompagnement

---

## 🧩 Fonctionnalités

- 🎯 Gestion des formations, examens et certificats
- 📆 Module calendrier : événements et dates d’examens
- 📁 Espace ressources pédagogiques 
- 🧘 Espace de soutien psychologique confidentiel
- 🖥️ Interface fluide en JavaFX
- 🌍 Interface web responsive avec Symfony + Bootstrap

---

## 🖼️ Captures d’écran

*(à insérer après les captures disponibles)*

```markdown
![Accueil Web](./screenshots/homepage-web.png)
![Vue Formation JavaFX](./screenshots/formation-desktop.png)
```

---

## 🤝 Contribuer

Merci de vouloir contribuer à KARRINI ! Voici comment commencer :

1. **Fork** ce dépôt
2. Crée une branche (`git checkout -b nouvelle-fonctionnalite`)
3. Fais tes modifications
4. Commit (`git commit -m "Ajout d'une nouvelle fonctionnalité"`)
5. Push (`git push origin nouvelle-fonctionnalite`)
6. Crée une **Pull Request**

### 🧪 Bonnes pratiques :

- Commente ton code
- Nomme les commits de manière claire
- Teste avant de proposer une PR

---

## 📜 Licence

Ce projet est sous licence **MIT**.  
Cela signifie que vous pouvez :

- ✅ Utiliser le code
- ✅ Le modifier
- ✅ Le redistribuer

Tant que vous **mentionnez l’auteur original**.

Voir le fichier [`LICENSE`](LICENSE) pour plus d’informations.

---

## 📬 Contact

Tu veux collaborer, suggérer ou signaler un bug ?

📧 Email : contact.karrini@example.com  
🔗 LinkedIn : [linkedin.com/in/votreprofil](https://linkedin.com/in/karrini)  
🌐 Site Web : www.karrini.com !

---

> Made with 💙 in Tunisia | KARRINI – “Apprendre et s’épanouir, ensemble.”
