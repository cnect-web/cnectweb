<?php

namespace Drupal\cnt_newsroom_decorator\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for News Type Bundles entities.
 */
class NewsTypeBundleViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
