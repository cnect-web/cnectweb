<?php

namespace Drupal\cnt_newsroom_decorator;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of News Type Bundles entities.
 *
 * @ingroup cnt_newsroom_decorator
 */
class NewsTypeBundleListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('News Type Bundle ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\cnt_newsroom_decorator\Entity\NewsTypeBundle $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.news_type_bundle.edit_form',
      ['news_type_bundle' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
