<?php

/**
 * @file
 * Contains \Drupal\ak_views_formatters\Plugin\Field\FieldFormatter\ViewReferenceFormatter.
 */

namespace Drupal\ak_views_formatters\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceIdFormatter;
use Drupal\views\Views;

/**
 * Plugin implementation of the 'views_reference' formatter.
 *
 * @FieldFormatter(
 *   id = "views_reference",
 *   label = @Translation("Referenced view"),
 *   description = @Translation("Render the referenced View."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class ViewReferenceFormatter extends EntityReferenceIdFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = array();

    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
      if ($entity->id()) {
        $view = Views::getView($entity->id());
        $elements[$delta] = array(
          // @TODO: Choose display rather than hardcoding it.
          $view->buildRenderable('default'),
        );
      }
    }

    return $elements;
  }
}
