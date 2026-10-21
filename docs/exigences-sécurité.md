# MiniShop — Exigences de sécurité

> **Objectif :** définir les mesures de sécurité à mettre en place dans MiniShop afin de protéger les données des utilisateurs, sécuriser les accès et prévenir les principales vulnérabilités d'une application Web.

---

## Sommaire

- [Présentation](#présentation)
- [Protection contre les injections SQL](#protection-contre-les-injections-sql)
- [Sécurisation des mots de passe](#sécurisation-des-mots-de-passe)
- [Protection contre les attaques XSS](#protection-contre-les-attaques-xss)
- [Protection des pages privées](#protection-des-pages-privées)
- [Vérification des autorisations](#vérification-des-autorisations)
- [Synthèse des mesures de sécurité](#synthèse-des-mesures-de-sécurité)

---

## Présentation

La sécurité est un élément essentiel de l'application MiniShop. Elle vise à protéger les informations personnelles des clients, les données des commandes et les fonctionnalités réservées aux utilisateurs authentifiés.

Les protections doivent être appliquées côté serveur en PHP et lors des échanges avec la base de données MySQL. Les contrôles effectués uniquement dans le navigateur ne suffisent pas à garantir la sécurité de l'application.

---

## Protection contre les injections SQL

**Risque :** un utilisateur malveillant pourrait injecter du code SQL dans un formulaire ou un paramètre d'URL afin de consulter, modifier ou supprimer des données sans autorisation.

**Mesures à mettre en œuvre :**

- Utiliser `PDO::prepare()` pour préparer les requêtes SQL.
- Utiliser `PDO::execute()` pour exécuter les requêtes avec les paramètres fournis.
- Utiliser des paramètres liés pour les données provenant des utilisateurs.
- Éviter la concaténation directe des données utilisateur dans les requêtes SQL.

**Exemple en PHP :**

```php
$stmt = $pdo->prepare(
    "SELECT * FROM client WHERE email = :email"
);

$stmt->execute([
    'email' => $email
]);

$client = $stmt->fetch();