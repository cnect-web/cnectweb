<?php

namespace Drupal\cnt_newsroom_decorator\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining News Type Bundles entities.
 *
 * @ingroup cnt_newsroom_decorator
 */
interface NewsTypeBundleInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the News Type Bundles name.
   *
   * @return string
   *   Name of the News Type Bundles.
   */
  public function getName();

  /**
   * Sets the News Type Bundles name.
   *
   * @param string $name
   *   The News Type Bundles name.
   *
   * @return \Drupal\cnt_newsroom_decorator\Entity\NewsTypeBundleInterface
   *   The called News Type Bundles entity.
   */
  public function setName($name);

  /**
   * Gets the News Type Bundles creation timestamp.
   *
   * @return int
   *   Creation timestamp of the News Type Bundles.
   */
  public function getCreatedTime();

  /**
   * Sets the News Type Bundles creation timestamp.
   *
   * @param int $timestamp
   *   The News Type Bundles creation timestamp.
   *
   * @return \Drupal\cnt_newsroom_decorator\Entity\NewsTypeBundleInterface
   *   The called News Type Bundles entity.
   */
  public function setCreatedTime($timestamp);

}
