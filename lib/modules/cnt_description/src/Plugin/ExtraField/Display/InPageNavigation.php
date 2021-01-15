<?php

namespace Drupal\cnt_description\Plugin\ExtraField\Display;

use DOMDocument;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\extra_field\Plugin\ExtraFieldDisplayBase;

/**
 * Example Extra field Display.
 *
 * @ExtraFieldDisplay(
 *   id = "in_page_navigation",
 *   label = @Translation("In Page Navigation"),
 *   bundles = {
 *     "node.cnt_description",
 *   }
 * )
 */
class InPageNavigation extends ExtraFieldDisplayBase {

  /**
   * {@inheritdoc}
   */
  public function view(ContentEntityInterface $entity) {
    $page_nav = [];
    // Check if in page navigation option is enabled.
    if ((bool) $entity->get('cnt_bol_sv_01')->value) {
      // Get headers from main body.
      $dom = new DOMDocument();
      $dom->loadHTML($entity->get('cnt_txt_fmt_01')->value);

      $h2s = $dom->getElementsByTagName('h2');
      $links = [];
      foreach ($h2s as $h2) {
        // Create in page navigation items links.
        $links[] = [
          'href' => "#{$h2->getAttribute('id')}",
          'label' => $h2->textContent,
        ];
      }

      return [
        '#theme' => 'cnt_description_in_page_navigation',
        '#links' => $links,
      ];
    }

    return $page_nav;
  }

}
