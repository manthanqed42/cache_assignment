<?php

namespace Drupal\cache_assignment\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block that shows articles based on the user’s preferred category.
 *
 * @Block(
 *   id = "user_category_articles_block",
 *   admin_label = @Translation("User Category Articles")
 * )
 */
class UserCategoryArticlesBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected EntityTypeManagerInterface $entityTypeManager;
  protected AccountProxyInterface $currentUser;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, AccountProxyInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $current_user;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('current_user')
    );
  }

  public function build() {
    $articles = [];
    $storage = $this->entityTypeManager->getStorage('node');
    
    // Get the logged-in user's preferred category.
    $user = $this->entityTypeManager->getStorage('user')->load($this->currentUser->id());

    if ($user && !$user->isAnonymous() && !$user->get('field_category')->isEmpty()) {
      $category_tid = $user->get('field_category')->target_id;

      // Load articles from the preferred category.
      $nids = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'article')
        ->condition('field_category', $category_tid)
        ->accessCheck(TRUE)
        ->range(0, 5)
        ->sort('created', 'DESC')
        ->execute();

      $nodes = $storage->loadMultiple($nids);

      foreach ($nodes as $node) {
        $articles[] = [
          '#markup' => $node->toLink()->toString(),
        ];
      }
    }

    return [
      '#theme' => 'item_list',
      '#items' => $articles,
      '#cache' => [
        'contexts' => ['user_category'],
        'tags' => ['node_list'],
        'max-age' => 3600,
      ],
    ];
  }

}
