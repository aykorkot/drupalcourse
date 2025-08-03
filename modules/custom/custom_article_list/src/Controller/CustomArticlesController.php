<?php

namespace Drupal\custom_article_list\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Core\Pager\PagerManagerInterface;
use Drupal\Core\Pager\PagerParametersInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CustomArticlesController extends ControllerBase {

  protected $pagerManager;
  protected $pagerParameters;

  // Injection des services pager (optionnel mais recommandé)
  public function __construct(PagerManagerInterface $pager_manager, PagerParametersInterface $pager_parameters) {
    $this->pagerManager = $pager_manager;
    $this->pagerParameters = $pager_parameters;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('pager.manager'),
      $container->get('pager.parameters')
    );
  }

  public function list() {
  $limit = 3; // Articles par page
  $current_page = $this->pagerParameters->findPage();
  $offset = $current_page * $limit;

  // Requête pour compter le total
  $count_query = \Drupal::entityQuery('node')
    ->condition('type', 'article_personnalise')
    ->condition('status', 1)
    ->accessCheck(TRUE);
  $total = $count_query->count()->execute();

  // Nouvelle requête pour récupérer les nids avec limite et offset
  $query = \Drupal::entityQuery('node')
    ->condition('type', 'article_personnalise')
    ->condition('status', 1)
    ->accessCheck(TRUE)
    ->range($offset, $limit);
  $nids = $query->execute();

  // Charger les nœuds seulement si $nids est un tableau et non vide
  $nodes = [];
  if (!empty($nids) && is_array($nids)) {
    $nodes = Node::loadMultiple($nids);
  }

  $articles = [];

  foreach ($nodes as $node) {
    $titre = $node->hasField('field_titre') ? $node->get('field_titre')->value : '';
    $body = $node->hasField('body') ? $node->get('body')->value : '';
    $image_url = '';

    if ($node->hasField('field_image') && !$node->get('field_image')->isEmpty()) {
      $file = $node->get('field_image')->entity;
      if ($file instanceof File) {
        $uri = $file->getFileUri();
        $image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($uri);
      }
    }

    $articles[] = [
      'title' => $titre,
      'body' => $body,
      'image_url' => $image_url,
    ];
  }

  // Création du pager Drupal
  $this->pagerManager->createPager($total, $limit);

  return [
    '#theme' => 'custom_articles_list',
    '#articles' => $articles,
    '#title' => $this->t('Liste des articles personnalisés'),
    '#pager' => [
      '#type' => 'pager',
    ],
  ];
}


}