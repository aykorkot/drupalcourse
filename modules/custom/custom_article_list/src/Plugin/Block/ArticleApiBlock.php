<?php

namespace Drupal\custom_article_list\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Fournit un bloc pour afficher les articles depuis l’API en JavaScript.
 *
 * @Block(
 *   id = "article_api_block",
 *   admin_label = @Translation("Bloc API Articles"),
 * )
 */
class ArticleApiBlock extends BlockBase {

  /**
   * Contenu du bloc.
   */
  public function build() {
    return [
      '#markup' => '<div id="api-articles-block">Chargement des articles...</div>',
      '#attached' => [
        'library' => [
          'custom_article_list/article_api_block',
        ],
      ],
    ];
  }
}
