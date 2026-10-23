
<?php

class ImageProduitService
{
    private const TAILLE_MAX = 2 * 1024 * 1024;

    public static function enregistrer(?array $fichier): ?string
    {
        // Aucune photo sélectionnée : c'est autorisé.
        if ($fichier === null) {
            return null;
        }

        $erreur = $fichier['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($erreur === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($erreur !== UPLOAD_ERR_OK) {
            throw new RuntimeException(
                "Le téléchargement de l'image a échoué."
            );
        }

        // Vérifier que le fichier provient bien d'un téléchargement.
        if (
            !isset($fichier['tmp_name'], $fichier['size']) ||
            !is_string($fichier['tmp_name']) ||
            !is_uploaded_file($fichier['tmp_name'])
        ) {
            throw new RuntimeException('Fichier image invalide.');
        }

        // Limite : 2 Mo.
        if (
            $fichier['size'] <= 0 ||
            $fichier['size'] > self::TAILLE_MAX
        ) {
            throw new RuntimeException(
                "L'image ne doit pas dépasser 2 Mo."
            );
        }

        // Vérifier le véritable format du fichier.
        $typesAutorises = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        $detecteur = new finfo(FILEINFO_MIME_TYPE);
        $typeMime = $detecteur->file($fichier['tmp_name']);

        if (!isset($typesAutorises[$typeMime])) {
            throw new RuntimeException(
                'Format non autorisé. Utilisez JPG, PNG ou WebP.'
            );
        }

        if (@getimagesize($fichier['tmp_name']) === false) {
            throw new RuntimeException(
                "Le fichier sélectionné n'est pas une image valide."
            );
        }

        // Générer un nom unique.
        $nomFichier = 'produit_'
            . bin2hex(random_bytes(16))
            . '.'
            . $typesAutorises[$typeMime];

        // Dossier public des images.
$dossier = dirname(__DIR__, 2) . '/public/images';

if (!is_dir($dossier)) {
    throw new RuntimeException("Le dossier des images n'existe pas.");
}

$destination = $dossier . '/' . $nomFichier;

        // Enregistrer la photo.
        if (!move_uploaded_file($fichier['tmp_name'], $destination)) {
            throw new RuntimeException(
                "Impossible d'enregistrer la photo."
            );
        }

        return $nomFichier;
    }
}

