<?php

namespace Drupal\cnt_description\Plugin\ExtraField\Display;

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
    if ((bool) $entity->get('cnt_bol_sv_01')->value) {
      $page_nav = [
        '#markup' => '<nav data-ecl-auto-init="InpageNavigation" class="ecl-inpage-navigation" data-ecl-inpage-navigation="true">
        <div class="ecl-inpage-navigation__title">
            Page contents
        </div>
        <div class="ecl-inpage-navigation__body">
            <button type="button" class="ecl-inpage-navigation__trigger"  id="ecl-inpage-navigation-trigger" data-ecl-inpage-navigation-trigger="true"  aria-controls="ecl-inpage-navigation-list" aria-expanded="false">
                <span class="ecl-inpage-navigation__trigger-current"  data-ecl-inpage-navigation-trigger-current="true">&nbsp;</span>
                <svg focusable="false" aria-hidden="true" class="ecl-inpage-navigation__trigger-icon ecl-icon ecl-icon--s ecl-icon--rotate-180">
                    <use xlink:href="/component-library/dist/media/icons.3cf410f9.svg#ui--corner-arrow"></use>
                </svg>
            </button>
            <ul class="ecl-inpage-navigation__list" aria-labelledby="ecl-inpage-navigation-trigger"
                data-ecl-inpage-navigation-list="true" id="ecl-inpage-navigation-list">
                <li class="ecl-inpage-navigation__item"><a data-ecl-inpage-navigation-link="true" href="#inline-nav-1"
                    class="ecl-inpage-navigation__link ecl-link ecl-link--standalone">Heading A</a></li>
                <li class="ecl-inpage-navigation__item"><a data-ecl-inpage-navigation-link="true" href="#inline-nav-2"
                    class="ecl-inpage-navigation__link ecl-link ecl-link--standalone">Heading B with a long title going on
                    several lines</a></li>
                <li class="ecl-inpage-navigation__item"><a data-ecl-inpage-navigation-link="true" href="#inline-nav-3"
                    class="ecl-inpage-navigation__link ecl-link ecl-link--standalone">Heading C</a></li>
                <li class="ecl-inpage-navigation__item"><a data-ecl-inpage-navigation-link="true" href="#inline-nav-4"
                    class="ecl-inpage-navigation__link ecl-link ecl-link--standalone">Heading D</a></li>
            </ul>
        </div>
      </nav>',
      ];
    }

    return $page_nav;
  }

}
