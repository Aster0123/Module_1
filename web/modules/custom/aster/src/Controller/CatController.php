<?php

namespace Drupal\aster\Controller;

use Drupal\Core\Controller\ControllerBase;

class CatController extends ControllerBase{
  public function page() {
    $element = [
      '#markup' => 'Hello! You can add here a photo of your cat.'];
    return $element;
  }
}

