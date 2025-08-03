<?php

namespace Drupal\custom_article_list\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'Latest Articles' Block.
 *
 * @Block(
 *   id = "latest_articles_block",
 *   admin_label = @Translation("Derniers articles"),
 *   category = @Translation("Custom")
 * )
 */
class LatestArticlesBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Récupérer la configuration du nombre d'articles à afficher.
    $config = \Drupal::config('custom_article_list.settings');
    $limit = $config->get('article_count');

    
    // Si la config n'est pas définie ou invalide, on utilise une valeur par défaut.
    if (!is_numeric($limit) || (int) $limit < 1) {
      $limit = 5;
    } else {
      $limit = (int) $limit;
    }

    \Drupal::logger('latest_articles')->notice('LIMIT CONFIG: @limit', ['@limit' => $limit]);

    // Requête pour récupérer les derniers articles publiés.
    $nids = \Drupal::entityQuery('node')
      ->accessCheck(TRUE)
      ->condition('status', 1)
      ->condition('type', 'article_personnalise')
      ->sort('created', 'DESC')
      ->range(0, $limit)
      ->execute();

    $articles = Node::loadMultiple($nids);

    // Construire la liste des liens vers les articles.
    $items = [];
    foreach ($articles as $article) {
      $items[] = [
        '#type' => 'link',
        '#title' => $article->getTitle(),
        '#url' => $article->toUrl(),
      ];
    }

    return [
      '#theme' => 'item_list',
      '#items' => $items,
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

  public function getCacheContexts() {
    // Simplifier temporairement pour test
    return ['url'];
  }

  public function getCacheTags() {
    return ['config:custom_article_list.settings'];
  }
}
