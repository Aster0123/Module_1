<?php

namespace Drupal\aster\Controller;

use Drupal\Core\Controller\ControllerBase;

class CatController extends ControllerBase{
//  public function page() {
//    $element = [
//      '#markup' => 'Hello! You can add here a photo of your cat.'];
//    return $element;
//  }
  public function content() {
    $form = \Drupal::formBuilder() -> getForm('Drupal\aster\Form\CatsForm');
    $element = 'Hello! You can add here a photo of your cat.';
    return [
      '#theme' => 'cats',
      '#form' => $form,
      '#markup' => $element,
    ];

  }
}

