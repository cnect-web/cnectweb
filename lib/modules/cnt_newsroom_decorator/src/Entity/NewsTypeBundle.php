<?php

namespace Drupal\cnt_newsroom_decorator\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the News Type Bundle entity.
 *
 * @ingroup cnt_newsroom_decorator
 *
 * @ContentEntityType(
 *   id = "news_type_bundle",
 *   label = @Translation("News Type Bundle"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\cnt_newsroom_decorator\NewsTypeBundleListBuilder",
 *     "views_data" = "Drupal\cnt_newsroom_decorator\Entity\NewsTypeBundleViewsData",
 *     "translation" = "Drupal\cnt_newsroom_decorator\NewsTypeBundleTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\cnt_newsroom_decorator\Form\NewsTypeBundleForm",
 *       "add" = "Drupal\cnt_newsroom_decorator\Form\NewsTypeBundleForm",
 *       "edit" = "Drupal\cnt_newsroom_decorator\Form\NewsTypeBundleForm",
 *       "delete" = "Drupal\cnt_newsroom_decorator\Form\NewsTypeBundleDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\cnt_newsroom_decorator\NewsTypeBundleHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\cnt_newsroom_decorator\NewsTypeBundleAccessControlHandler",
 *   },
 *   base_table = "news_type_bundle",
 *   data_table = "news_type_bundle_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer news type bundles",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/news_type_bundle/{news_type_bundle}",
 *     "add-form" = "/admin/structure/news_type_bundle/add",
 *     "edit-form" = "/admin/structure/news_type_bundle/{news_type_bundle}/edit",
 *     "delete-form" = "/admin/structure/news_type_bundle/{news_type_bundle}/delete",
 *     "collection" = "/admin/structure/news_type_bundle",
 *   },
 *   field_ui_base_route = "news_type_bundle.settings"
 * )
 */
class NewsTypeBundle extends ContentEntityBase implements NewsTypeBundleInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bundle name'))
      ->setDescription(t('The name of the News Type Bundle.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the News Type Bundle is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
