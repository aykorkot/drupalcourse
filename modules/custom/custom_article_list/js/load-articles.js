(function ($, Drupal, once) {
  // Déclare un comportement Drupal qui sera exécuté à chaque chargement de page ou d'AJAX.
  Drupal.behaviors.loadArticles = {
    attach: function (context, settings) {
      // Sélectionne l'élément avec l'ID 'api-articles-block' UNIQUEMENT une fois,
      // pour éviter que le code ne soit exécuté plusieurs fois.
      const elements = once('load-articles', '#api-articles-block', context);

      // Boucle sur les éléments sélectionnés (en général un seul ici).
      elements.forEach(function (element) {
        // Affiche un message temporaire pendant le chargement des données.
        element.innerHTML = 'Chargement des articles...';

        // Fait une requête AJAX vers l'URL de l’API pour obtenir les articles.
        fetch('/api/articles')
          .then(response => response.json()) // Convertit la réponse JSON en objet JavaScript.
          .then(data => {

            // Commence la création d'une liste HTML pour afficher les articles.
            let html = '<ul>';

            // Pour chaque article reçu dans la réponse :
            data.forEach(article => {
              html += `
                <li>
                  <h3>${article.title}</h3>
                  <p>${article.body}</p>
                  ${article.image_url ? `<img src="${article.image_url}" alt="${article.title}" style="max-width: 200px;">` : ''}
                </li>
              `;
            });

            html += '</ul>'; // Termine la liste HTML.

            // Injecte le contenu HTML généré dans le bloc.
            element.innerHTML = html;
          })
          .catch(error => {
            // Si une erreur se produit (ex : API indisponible), affiche un message d’erreur.
            console.error('Erreur lors du chargement des articles:', error);
            element.innerHTML = 'Impossible de charger les articles.';
          });
      });
    }
  };
})(jQuery, Drupal, once);


