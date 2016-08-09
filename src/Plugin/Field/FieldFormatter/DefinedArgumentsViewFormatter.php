<?php

/**
 * @file
 * Contains \Drupal\ak_views_formatters\Plugin\Field\FieldFormatter\DefinedArgumentsViewFormatter.
 */

namespace Drupal\ak_views_formatters\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;
use Drupal\views\Views;

/**
 * Plugin implementation of the 'views_reference_arguments' formatter.
 *
 * @FieldFormatter(
 *   id = "views_reference_arguments",
 *   label = @Translation("Selected view w/ arguments"),
 *   description = @Translation("Pass the field value(s) as arguments to the selected view."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class DefinedArgumentsViewFormatter extends EntityReferenceFormatterBase {

  /**
   * Defines the default settings for this plugin.
   *
   * @return array
   * A list of default settings, keyed by the setting name.
   */
  public static function defaultSettings() {
    return array(
      'selected_view' => '',
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['selected_view'] = array(
      '#title' => t('View'),
      '#type' => 'select',
      '#default_value' => $this->getSetting('selected_view'),
      '#options' => $this->getEnabledViews(),
    );
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();
    $summary[] = t('Rendered view: @view', array('@view' => $this->getSetting('selected_view')));

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $arguments = [];
    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
      if ($entity->id()) {
        $arguments[] = $entity->id();
      }
    }
    
    $view = Views::getView($this->getSetting('selected_view'));
    $view->setArguments(array(implode('+', $arguments)));
    $elements = array(
      // @TODO: Choose display rather than hardcoding it.
      $view->buildRenderable('default'),
    );
    return $elements;
  }

  /**
   * Build option list of available Views.
   */
  private function getEnabledViews() {
    $views = Views::getEnabledViews();
    $options = [];
    foreach ($views as $key => $view) {
      $options[$key] = $key;
    }
    return $options;
  }
}
