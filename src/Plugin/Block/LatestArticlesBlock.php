<?php

namespace Drupal\cache_assignment\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Provides a block that displays the last 3 article titles.
 *
 * @Block(
 *   id = "latest_articles_block",
 *   admin_label = @Translation("Latest Articles Block")
 * )
 */
class LatestArticlesBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a LatestArticlesBlock object.
   *
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin ID.
   * @param mixed $plugin_definition
   *   The plugin definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, AccountProxyInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Load the last 3 published articles.
    $query = $this->entityTypeManager->getStorage('node')->getQuery()
      ->condition('status', 1)
      ->condition('type', 'article')
      ->sort('created', 'DESC')
      ->range(0, 3)
      ->accessCheck(FALSE);

    $nids = $query->execute();
    $articles = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);

    // Build the list of article titles.
    $items = [];
    foreach ($articles as $article) {
      $items[] = $article->toLink()->toRenderable();
    }

    // Get the current user's email.
    $user_email = $this->currentUser->isAuthenticated() ? $this->currentUser->getEmail() : 'Anonymous';

    return [
      '#theme' => 'item_list',
      #'#items' => $items,
      '#items' => array_merge($items, ["User Email: " . $user_email]),
      '#cache' => [
        'tags' => array_map(fn($nid) => "node:$nid", $nids),
        'contexts' => ['user'],
      ],
    ];
  }

}
