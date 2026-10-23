// MiniShop - Interactions JavaScript

document.addEventListener('DOMContentLoaded', function () {

    // Demander confirmation avant de supprimer un produit du panier
    const formulairesSuppression = document.querySelectorAll(
        '.form-supprimer'
    );

    formulairesSuppression.forEach(function (formulaire) {

        formulaire.addEventListener('submit', function (evenement) {

            const confirmation = window.confirm(
                'Voulez-vous vraiment supprimer ce produit de votre panier ?'
            );

            if (!confirmation) {
                evenement.preventDefault();
            }

        });

    });

// Vérifier que les mots de passe correspondent à l'inscription
const formulaireInscription = document.querySelector(
    'form[action="index.php?page=inscription"]'
);

if (formulaireInscription) {
    const motDePasse = document.getElementById('password');
    const confirmation = document.getElementById('confirmation');

    if (motDePasse && confirmation) {
        function verifierMotsDePasse() {
            if (
                confirmation.value !== '' &&
                motDePasse.value !== confirmation.value
            ) {
                confirmation.setCustomValidity(
                    'Les mots de passe ne correspondent pas.'
                );
            } else {
                confirmation.setCustomValidity('');
            }
        }

        motDePasse.addEventListener('input', verifierMotsDePasse);
        confirmation.addEventListener('input', verifierMotsDePasse);

        formulaireInscription.addEventListener('submit', function () {
            verifierMotsDePasse();
        });
    }
}
});