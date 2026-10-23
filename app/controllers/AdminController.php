<?php


require_once __DIR__ . '/../services/ImageProduitService.php';
require_once __DIR__ . '/../models/Categorie.php';
require_once __DIR__ . '/../models/ProduitAdmin.php';
require_once __DIR__ . '/../models/CommandeAdmin.php';

class AdminController
{
    private Categorie $categorieModel;
    private ProduitAdmin $produitModel;
    private CommandeAdmin $commandeModel;

    // Statuts d'une commande et leur libellé affiché
    private const STATUTS = [
        'en_attente'     => 'En attente',
        'en_preparation' => 'En préparation',
        'expediee'       => 'Expédiée',
        'livree'         => 'Livrée',
        'annulee'        => 'Annulée'
    ];

    // Transitions autorisées (mêmes règles que le trigger verifier_statut_commande)
    private const TRANSITIONS = [
        'en_attente'     => ['en_preparation', 'annulee'],
        'en_preparation' => ['expediee'],
        'expediee'       => ['livree'],
        'livree'         => [],
        'annulee'        => []
    ];

    public function __construct(PDO $pdo)
    {
        $this->categorieModel = new Categorie($pdo);
        $this->produitModel = new ProduitAdmin($pdo);
        $this->commandeModel = new CommandeAdmin($pdo);
    }

    // --------------------------------------------------
    // Sécurité : réservé aux administrateurs
    // --------------------------------------------------

    private function verifierAdmin(): void
    {
        // Visiteur non connecté : retour à la connexion
        if (empty($_SESSION['client_id'])) {
            header('Location: index.php?page=connexion', true, 303);
            exit;
        }

        // Client connecté mais pas administrateur : accès refusé
        if (($_SESSION['client_role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo 'Accès réservé aux administrateurs.';
            exit;
        }
    }

    // --------------------------------------------------
    // Affichage des pages (GET)
    // --------------------------------------------------

    public function afficher(string $page): void
    {
        $this->verifierAdmin();

        switch ($page) {
            case 'admin':
                $this->tableauDeBord();
                break;

            case 'admin_produits':
                $this->produits();
                break;

            case 'admin_categories':
                $this->categories();
                break;

            case 'admin_stocks':
                $this->stocks();
                break;

            case 'admin_commandes':
                $this->commandes();
                break;

            default:
                http_response_code(404);
                echo 'Page introuvable.';
                break;
        }
    }

    private function tableauDeBord(): void
    {
        $produits = $this->produitModel->lister();
        $commandes = $this->commandeModel->lister();
        $categories = $this->categorieModel->listerAdmin();

        $nbProduits = count($produits);
        $nbCategories = count($categories);
        $nbCommandes = count($commandes);

        $nbEnAttente = 0;
        foreach ($commandes as $commande) {
            if ($commande['statut'] === 'en_attente') {
                $nbEnAttente++;
            }
        }

        $this->afficherVue('index', [
            'nbProduits' => $nbProduits,
            'nbCategories' => $nbCategories,
            'nbCommandes' => $nbCommandes,
            'nbEnAttente' => $nbEnAttente
        ]);
    }

    private function produits(): void
    {
        $produits = $this->produitModel->lister();
        $categories = $this->categorieModel->listerAdmin();

        // Produit à modifier (?modifier=ID)
        $produitEdition = null;
        $idEdition = filter_var(
            $_GET['modifier'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if ($idEdition !== false && $idEdition !== null) {
            $produitEdition = $this->produitModel->trouverParId($idEdition);
        }

        $this->afficherVue('produits', [
            'produits' => $produits,
            'categories' => $categories,
            'produitEdition' => $produitEdition
        ]);
    }

    private function categories(): void
    {
        $categories = $this->categorieModel->listerAdmin();

        // Catégorie à modifier (?modifier=ID)
        $categorieEdition = null;
        $idEdition = filter_var(
            $_GET['modifier'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if ($idEdition !== false && $idEdition !== null) {
            $categorieEdition = $this->categorieModel->trouverParId($idEdition);
        }

        $this->afficherVue('categories', [
            'categories' => $categories,
            'categorieEdition' => $categorieEdition
        ]);
    }

    private function stocks(): void
    {
        $this->afficherVue('stocks', [
            'produits' => $this->produitModel->lister()
        ]);
    }

    private function commandes(): void
    {
        $commandes = $this->commandeModel->lister();

        // Détail d'une commande (?id=ID)
        $commandeDetail = null;
        $lignes = [];
        $historique = [];
        $transitionsPossibles = [];

        $idCommande = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if ($idCommande !== false && $idCommande !== null) {
            $commandeDetail = $this->commandeModel->trouverParId($idCommande);

            if ($commandeDetail !== null) {
                $lignes = $this->commandeModel->getLignes($idCommande);
                $historique = $this->commandeModel->getHistorique($idCommande);
                $transitionsPossibles =
                    self::TRANSITIONS[$commandeDetail['statut']] ?? [];
            }
        }

        $this->afficherVue('commandes', [
            'commandes' => $commandes,
            'commandeDetail' => $commandeDetail,
            'lignes' => $lignes,
            'historique' => $historique,
            'transitionsPossibles' => $transitionsPossibles,
            'statuts' => self::STATUTS
        ]);
    }

    // Charger une vue admin avec ses données et les messages éventuels
    private function afficherVue(string $vue, array $donnees = []): void
    {
        $messageSucces = $this->consommerMessage('admin_succes');
        $messageErreur = $this->consommerMessage('admin_erreur');

        extract($donnees);

        require __DIR__ . '/../views/admin/' . $vue . '.php';
    }

    // Lire puis effacer un message stocké en session
    private function consommerMessage(string $cle): string
    {
        $message = is_string($_SESSION[$cle] ?? null)
            ? $_SESSION[$cle]
            : '';

        unset($_SESSION[$cle]);

        return $message;
    }

    // --------------------------------------------------
    // Traitement des formulaires (POST)
    // Le jeton CSRF est déjà vérifié dans index.php
    // --------------------------------------------------

    public function traiterAction(string $action): void
    {
        $this->verifierAdmin();

        switch ($action) {
            case 'admin_ajouter_produit':
                $this->ajouterProduit();
                break;

            case 'admin_modifier_produit':
                $this->modifierProduit();
                break;

            case 'admin_etat_produit':
                $this->changerEtatProduit();
                break;

            case 'admin_ajouter_categorie':
                $this->ajouterCategorie();
                break;

            case 'admin_modifier_categorie':
                $this->modifierCategorie();
                break;

            case 'admin_supprimer_categorie':
                $this->supprimerCategorie();
                break;

            case 'admin_modifier_stock':
                $this->modifierStock();
                break;

            case 'admin_modifier_statut':
                $this->modifierStatut();
                break;

            default:
                http_response_code(400);
                echo 'Action inconnue.';
                break;
        }
    }

    // ----- Produits -----


private function ajouterProduit(): void
{
    $nom = $this->lireTexte('nom');
    $description = $this->lireTexte('description');
    $prix = $this->lirePrix('prix');
    $stock = $this->lireEntier('stock', 0);
    $idCategorie = $this->lireEntier('id_categorie', 1);

    $erreur = $this->validerProduit($nom, $description, $prix, $idCategorie);

    if ($erreur === '' && $stock === null) {
        $erreur = 'Le stock doit être un entier positif ou nul.';
    }

    if ($erreur !== '') {
        $this->terminer('admin_erreur', $erreur, 'admin_produits');
    }

    $this->executer(
        function () use ($nom, $description, $prix, $stock, $idCategorie) {

            // Vérifier et enregistrer la photo si elle existe.
            $nomImage = ImageProduitService::enregistrer(
                $_FILES['image_produit'] ?? null
            );


try {
    // Démarrer une transaction SQL.
    $this->produitModel->commencerTransaction();

    // Créer le produit et récupérer son identifiant.
    $idProduit = $this->produitModel->ajouter(
        $nom,
        $description,
        $prix,
        $stock,
        $idCategorie
    );

    // Associer la photo si une image a été choisie.
    if ($nomImage !== null) {
        $this->produitModel->modifierImage(
            $idProduit,
            $nomImage
        );
    }

    // Confirmer les changements si tout a réussi.
    $this->produitModel->validerTransaction();

} catch (Throwable $e) {
    // Annuler les changements SQL en cas d'erreur.
    $this->produitModel->annulerTransaction();

    // Supprimer la nouvelle photo si elle a été enregistrée.
    if ($nomImage !== null) {
        $cheminImage = dirname(__DIR__, 2)
            . '/public/images/' . $nomImage;

        if (is_file($cheminImage)) {
            unlink($cheminImage);
        }
    }

    throw $e;
}

        },
        'Produit ajouté avec succès.',
        'admin_produits'
    );
}



private function modifierProduit(): void
{
    $idProduit = $this->lireEntier('id_produit', 1);
    $nom = $this->lireTexte('nom');
    $description = $this->lireTexte('description');
    $prix = $this->lirePrix('prix');
    $idCategorie = $this->lireEntier('id_categorie', 1);

    if ($idProduit === null) {
        $this->terminer('admin_erreur', 'Produit invalide.', 'admin_produits');
    }

    $erreur = $this->validerProduit($nom, $description, $prix, $idCategorie);

    if ($erreur !== '') {
        $this->terminer('admin_erreur', $erreur, 'admin_produits');
    }

    $this->executer(
        function () use ($idProduit, $nom, $description, $prix, $idCategorie) {

            // Enregistrer une nouvelle photo si elle a été choisie.
            $nomImage = ImageProduitService::enregistrer(
                $_FILES['image_produit'] ?? null
            );


try {
    // Démarrer une transaction SQL.
    $this->produitModel->commencerTransaction();

    // Modifier les informations du produit.
    $this->produitModel->modifier(
        $idProduit,
        $nom,
        $description,
        $prix,
        $idCategorie
    );

    // Remplacer la photo uniquement si une nouvelle a été choisie.
    if ($nomImage !== null) {
        $this->produitModel->modifierImage(
            $idProduit,
            $nomImage
        );
    }

    // Confirmer les modifications.
    $this->produitModel->validerTransaction();

} catch (Throwable $e) {
    // Annuler les modifications SQL en cas d'erreur.
    $this->produitModel->annulerTransaction();

    // Supprimer la nouvelle photo téléchargée.
    if ($nomImage !== null) {
        $cheminImage = dirname(__DIR__, 2)
            . '/public/images/' . $nomImage;

        if (is_file($cheminImage)) {
            unlink($cheminImage);
        }
    }

    throw $e;
}

        },
        'Produit modifié avec succès.',
        'admin_produits'
    );
}


    // Activer ou désactiver un produit (remplace la suppression)
    private function changerEtatProduit(): void
    {
        $idProduit = $this->lireEntier('id_produit', 1);
        $actif = $this->lireTexte('actif') === '1';

        if ($idProduit === null) {
            $this->terminer('admin_erreur', 'Produit invalide.', 'admin_produits');
        }

        $this->executer(
            function () use ($idProduit, $actif) {
                $this->produitModel->changerEtat($idProduit, $actif);
            },
            $actif ? 'Produit réactivé.' : 'Produit désactivé.',
            'admin_produits'
        );
    }

    // Vérifier les champs communs à l'ajout et à la modification d'un produit
    private function validerProduit(
        string $nom,
        string $description,
        ?string $prix,
        ?int $idCategorie
    ): string {
        if ($nom === '') {
            return 'Le nom du produit est obligatoire.';
        }

        if (mb_strlen($nom) > 150) {
            return 'Le nom ne doit pas dépasser 150 caractères.';
        }

        if (mb_strlen($description) > 2000) {
            return 'La description ne doit pas dépasser 2000 caractères.';
        }

        if ($prix === null) {
            return 'Le prix doit être un nombre strictement positif.';
        }

        if ($idCategorie === null) {
            return 'Veuillez choisir une catégorie.';
        }

        return '';
    }

    // ----- Catégories -----

    private function ajouterCategorie(): void
    {
        $nom = $this->lireTexte('nom');

        if ($nom === '' || mb_strlen($nom) > 100) {
            $this->terminer(
                'admin_erreur',
                'Le nom de la catégorie est obligatoire (100 caractères maximum).',
                'admin_categories'
            );
        }

        $this->executer(
            function () use ($nom) {
                $this->categorieModel->ajouter($nom);
            },
            'Catégorie ajoutée avec succès.',
            'admin_categories'
        );
    }

    private function modifierCategorie(): void
    {
        $idCategorie = $this->lireEntier('id_categorie', 1);
        $nom = $this->lireTexte('nom');

        if ($idCategorie === null) {
            $this->terminer('admin_erreur', 'Catégorie invalide.', 'admin_categories');
        }

        if ($nom === '' || mb_strlen($nom) > 100) {
            $this->terminer(
                'admin_erreur',
                'Le nom de la catégorie est obligatoire (100 caractères maximum).',
                'admin_categories'
            );
        }

        $this->executer(
            function () use ($idCategorie, $nom) {
                $this->categorieModel->modifier($idCategorie, $nom);
            },
            'Catégorie modifiée avec succès.',
            'admin_categories'
        );
    }

    private function supprimerCategorie(): void
    {
        $idCategorie = $this->lireEntier('id_categorie', 1);

        if ($idCategorie === null) {
            $this->terminer('admin_erreur', 'Catégorie invalide.', 'admin_categories');
        }

        $this->executer(
            function () use ($idCategorie) {
                $this->categorieModel->supprimer($idCategorie);
            },
            'Catégorie supprimée.',
            'admin_categories'
        );
    }

    // ----- Stocks -----

    private function modifierStock(): void
    {
        $idProduit = $this->lireEntier('id_produit', 1);
        $stock = $this->lireEntier('stock', 0);

        if ($idProduit === null || $stock === null) {
            $this->terminer(
                'admin_erreur',
                'Le stock doit être un entier positif ou nul.',
                'admin_stocks'
            );
        }

        $this->executer(
            function () use ($idProduit, $stock) {
                $this->produitModel->modifierStock($idProduit, $stock);
            },
            'Stock mis à jour.',
            'admin_stocks'
        );
    }

    // ----- Commandes -----

    private function modifierStatut(): void
    {
        $idCommande = $this->lireEntier('id_commande', 1);
        $statut = $this->lireTexte('statut');

        if ($idCommande === null || !array_key_exists($statut, self::STATUTS)) {
            $this->terminer(
                'admin_erreur',
                'Modification de statut invalide.',
                'admin_commandes'
            );
        }

        $this->executer(
            function () use ($idCommande, $statut) {
                $this->commandeModel->modifierStatut($idCommande, $statut);
            },
            'Statut de la commande modifié.',
            'admin_commandes&id=' . $idCommande
        );
    }

    // --------------------------------------------------
    // Outils internes
    // --------------------------------------------------

    // Lire un champ texte du formulaire (chaîne uniquement)
    private function lireTexte(string $cle): string
    {
        return is_string($_POST[$cle] ?? null)
            ? trim($_POST[$cle])
            : '';
    }

    // Lire un entier supérieur ou égal à $min, sinon null
    private function lireEntier(string $cle, int $min): ?int
    {
        $valeur = filter_var(
            $_POST[$cle] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => $min]]
        );

        return ($valeur === false || $valeur === null) ? null : $valeur;
    }

    // Lire un prix strictement positif (virgule acceptée), sinon null
    private function lirePrix(string $cle): ?string
    {
        $brut = str_replace(',', '.', $this->lireTexte($cle));
        $valeur = filter_var($brut, FILTER_VALIDATE_FLOAT);

        if ($valeur === false || $valeur <= 0 || $valeur > 99999999.99) {
            return null;
        }

        return number_format($valeur, 2, '.', '');
    }

    // Exécuter une opération en base et rediriger avec un message

private function executer(
    callable $operation,
    string $messageSucces,
    string $redirection
): void {
    try {
        $operation();
        $_SESSION['admin_succes'] = $messageSucces;

    } catch (PDOException $e) {
        $_SESSION['admin_erreur'] = $this->messageErreur($e);

    } catch (RuntimeException $e) {
        $_SESSION['admin_erreur'] = $e->getMessage();
    }

    header('Location: index.php?page=' . $redirection, true, 303);
    exit;
}

    // Enregistrer un message puis rediriger
    private function terminer(
        string $cle,
        string $message,
        string $redirection
    ): void {
        $_SESSION[$cle] = $message;

        header('Location: index.php?page=' . $redirection, true, 303);
        exit;
    }

    // Message affichable : texte des règles métier (SIGNAL 45000),
    // message générique pour toute autre erreur SQL
    private function messageErreur(PDOException $e): string
    {
        $info = $e->errorInfo ?? [];

        if (($info[0] ?? '') === '45000' && !empty($info[2])) {
            return (string) $info[2];
        }

        error_log($e->getMessage());

        return 'Opération impossible pour le moment.';
    }
}