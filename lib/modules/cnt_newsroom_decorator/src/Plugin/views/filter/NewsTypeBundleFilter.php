<?php

namespace Drupal\cnt_newsroom_decorator\Plugin\views\filter;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\filter\InOperator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the list of news type bundles to filter newsroom items.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("news_type_bundle_views_filter")
 */
final class NewsTypeBundleFilter extends InOperator {
  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity repository.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactoryInterface
   */
  protected $queryFactory;

  /**
   * Constructs a NewsTypeBundleFilter object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function valueForm(&$form, FormStateInterface $form_state) {
    $form['value'] = [];
    $identifier = $this->options['expose']['identifier'];
    $form['value'][$identifier] = [
      '#type' => 'hidden',
      '#value' => 1,
    ];
    $this->buildBundleFacets($form, $identifier);
    $this->joinValuesToOne($form_state);

    if (count($this->userSelectedIds) <= 1) {
      $this->userSelectedIds = $this->allIds;
    }
  }

  /**
   * Keeps joined ids from user input.
   *
   * @var array
   */
  private $userSelectedIds = [];


  /**
   * Keeps all ids from all filters.
   *
   * @var array
   */
  private $allIds = [];

  /**
   * {@inheritdoc}
   */
  private function buildBundleFacets(array &$form, string $identificator) {
    $records = $this->entityTypeManager->getStorage('news_type_bundle')->loadByProperties(['status' => 1]);
    $idx = 1;
    foreach ($records as $record) {
      $news_types = $record->cnt_newsroom_item_types->referencedEntities();
      $opts = [];

      foreach ($news_types as $type) {
        $this->allIds[] = $type->tid->value;
        $opts[$type->tid->value] = $type->get('name')->value;
      }

      // Hack: Exposed form filter by design works only with one filter.
      // We split one filter into several filters.
      $id = 'p' . $idx;

      if (count($opts) > 1) {
        $form['value'][$id] = [
          '#type' => 'select',
          '#multiple' => TRUE,
          '#title' => $record->name->value,
          '#options' => $opts,
        ];
      }
      else {
        $form['value'][$id] = [
          '#type' => 'checkboxes',
          '#title' => $record->name->value,
          '#options' => $opts,
        ];
      }
      $idx++;
    }
  }

  /**
   * {@inheritdoc}
   */
  private function joinValuesToOne(FormStateInterface $form_state) {
    $userInput = $form_state->getUserInput();
    $mergedValues = [];
    foreach ($userInput as $array) {
      if (is_array($array)) {
        $mergedValues = array_merge($mergedValues, $array);
      }
      else {
        $mergedValues[] = $array;
      }
    }
    $this->userSelectedIds = $mergedValues;
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $this->ensureMyTable();
    // We have to replace joined ids here,
    // To make sure that all selected values are considered in the query.
    if (!empty($this->userSelectedIds)) {
      $this->value = $this->userSelectedIds;
    }
    $this->query->addWhere($this->options['group'], "$this->tableAlias.$this->realField", $this->value, $this->operator);
  }

}
