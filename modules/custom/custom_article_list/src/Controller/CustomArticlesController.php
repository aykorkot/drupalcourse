<?php

namespace Drupal\custom_article_list\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;

class CustomArticlesController extends ControllerBase {

  // public function list() {
  //   $items = [];

  //   $nids = \Drupal::entityQuery('node')
  //     ->condition('type', 'article_personnalise')
  //     ->accessCheck(TRUE)
  //     ->execute();

  //   if (!empty($nids)) {
  //     $nodes = Node::loadMultiple($nids);

  //     foreach ($nodes as $node) {
  //       $titre = $node->hasField('field_titre') ? $node->get('field_titre')->value : '';
  //       $body = $node->hasField('body') ? $node->get('body')->value : '';
  //       $image_url = '';

  //       // Vérifie si une image existe
  //       if ($node->hasField('field_image') && !$node->get('field_image')->isEmpty()) {
  //         $file = $node->get('field_image')->entity;
  //         if ($file instanceof File) {
  //           $uri = $file->getFileUri();
  //           $image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($uri);
  //         }
  //       }

  //       $html = '<div class="article-item">';
  //       if (!empty($titre)) {
  //         $html .= '<h3>' . $titre . '</h3>';
  //       }
  //       if (!empty($image_url)) {
  //         $html .= '<img src="' . $image_url . '" style="max-width:300px;">';
  //       }
  //       $html .= '<p>' . $body . '</p>';
  //       $html .= '</div>';

  //       $items[] = ['#markup' => $html];
  //     }
  //   }

  //   return [
  //     '#theme' => 'item_list',
  //     '#items' => $items,
  //     '#title' => $this->t('Liste des articles personnalisés'),
  //   ];
  // }
  public function list() {
    $articles = [];

    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'article_personnalise')
      ->accessCheck(TRUE)
      ->execute();

    if (!empty($nids)) {
      $nodes = Node::loadMultiple($nids);

      foreach ($nodes as $node) {
        $titre = $node->hasField('field_titre') ? $node->get('field_titre')->value : '';
        $body = $node->hasField('body') ? $node->get('body')->value : '';
        $image_url = '';

        if ($node->hasField('field_image') && !$node->get('field_image')->isEmpty()) {
          $file = $node->get('field_image')->entity;
          if ($file) {
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
    }

    return [
      '#theme' => 'custom_articles_list', // nom du template Twig
      '#articles' => $articles,
      '#title' => $this->t('Liste des articles personnalisés'),
    ];
  }
}
