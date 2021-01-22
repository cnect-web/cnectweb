<?php

namespace Drupal\cnt_newsroom_decorator;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the News Type Bundles entity.
 *
 * @see \Drupal\cnt_newsroom_decorator\Entity\NewsTypeBundle.
 */
class NewsTypeBundleAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\cnt_newsroom_decorator\Entity\NewsTypeBundleInterface $entity */

    switch ($operation) {

      case 'view':

        return AccessResult::allowedIfHasPermission($account, 'view news type bundles');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit news type bundles');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete news type bundles');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add news type bundles');
  }


}
