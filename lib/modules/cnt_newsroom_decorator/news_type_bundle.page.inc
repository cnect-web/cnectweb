<?php

/**
 * @file
 * Contains news_type_bundle.page.inc.
 *
 * Page callback for News Type Bundles entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for News Type Bundles templates.
 *
 * Default template: news_type_bundle.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_news_type_bundle(array &$variables) {
  // Fetch NewsTypeBundle Entity Object.
  $news_type_bundle = $variables['elements']['#news_type_bundle'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
