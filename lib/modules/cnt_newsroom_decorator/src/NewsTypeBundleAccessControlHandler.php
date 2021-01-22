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

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished news type bundles entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published news type bundles entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit news type bundles entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete news type bundles entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add news type bundles entities');
  }


}
