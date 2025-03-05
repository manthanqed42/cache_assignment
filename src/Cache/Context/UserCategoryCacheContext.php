<?php

namespace Drupal\cache_assignment\Cache\Context;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides a cache context based on the user's selected category.
 */
class UserCategoryCacheContext implements CacheContextInterface {

  protected AccountInterface $currentUser;
  protected EntityTypeManagerInterface $entityTypeManager;

  public function __construct(AccountInterface $current_user, EntityTypeManagerInterface $entity_type_manager) {
    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getLabel() {
    return t("User's selected category");
  }

  /**
   * {@inheritdoc}
   */
  public function getContext() {
    // Load the user entity and get the preferred category.
    $user = $this->entityTypeManager->getStorage('user')->load($this->currentUser->id());

    if ($user && !$user->isAnonymous() && !$user->get('field_category')->isEmpty()) {
      return $user->get('field_category')->target_id;
    }

    return 'none'; // Default cache context for anonymous or users without category
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata() {
    return new CacheableMetadata();
  }

}
