<?php

namespace Drupal\cnt_description\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "custom timestamp conversion" plugin.
 *
 * NOTE: The plugin ID ('id' key) corresponds to the CKEditor plugin name.
 * It is the first argument of the CKEDITOR.plugins.add() function in the
 * plugin.js file.
 *
 * @CKEditorPlugin(
 *   id = "cnt_in_page_navigation_item",
 *   label = @Translation("SPECIFIC TO DESCRIPTION CONTENT TYPE! Inserts h2 with id to identify this header in in page navigation. This option needs to be enabled.")
 * )
 */
class InPageNavigationItem extends CKEditorPluginBase {

  /**
   * {@inheritdoc}
   *
   * NOTE: The keys of the returned array corresponds to the CKEditor button
   * names. They are the first argument of the editor.ui.addButton() or
   * editor.ui.addRichCombo() functions in the plugin.js file.
   */
  public function getButtons() {
    // Make sure that the path to the image matches the file structure of
    // the CKEditor plugin you are implementing.
    return [
      'InPageNavigationItem' => [
        'label' => $this->t('In page navigation item button'),
        'image' => \Drupal::service('module_handler')->getModule('cnt_description')->getPath() . '/libraries/plugins/cnt_in_page_navigation_item/icons/cnt_in_page_navigation_item.png',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFile(): string {
    return \Drupal::service('module_handler')->getModule('cnt_description')->getPath() . '/libraries/plugins/cnt_in_page_navigation_item/plugin.js';
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    return [];
  }

}
