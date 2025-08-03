<?php

namespace Drupal\custom_article_list\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\File\FileUrlGeneratorInterface;

class ArticleApiController extends ControllerBase {

  protected $fileUrlGenerator;

  public function __construct(FileUrlGeneratorInterface $fileUrlGenerator) {
    $this->fileUrlGenerator = $fileUrlGenerator;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('file_url_generator')
    );
  }

  public function getArticles(): Response {
    
    $nodes = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->loadByProperties(['type' => 'article_personnalise', 'status' => 1, ]);

    $data = [];

    foreach ($nodes as $node) {
      $title = $node->getTitle();
      $body = $node->hasField('body') ? $node->get('body')->value : '';
      $image_url = '';
      if ($node->hasField('field_image') && !$node->get('field_image')->isEmpty()) {
        $file = $node->get('field_image')->entity;
        if ($file) {
          $image_url = $this->fileUrlGenerator->generateAbsoluteString($file->getFileUri());
        }
      }

      $data[] = [
        'title' => $title,
        'body' => strip_tags($node->body->value),
        'image_url' => $image_url,
      ];
    }

    $json = json_encode(
      $data,
      JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );

    return new Response($json, 200, ['Content-Type' => 'application/json']);
  }
}
