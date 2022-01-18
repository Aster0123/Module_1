<?php

namespace Drupal\aster\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;

class CatController extends ControllerBase
{

  public function content()
  {
    $form = \Drupal::formBuilder()->getForm('Drupal\aster\Form\CatsForm');
    $element = 'Hello! You can add here a photo of your cat.';
    return [
      '#theme' => 'cats',
      '#form' => $form,
      '#markup' => $element,
      '#list'=>$this->catTable(),
    ];
  }
    public function catTable(): array
  {
    $query= \Drupal::database();
    $result = $query->select('aster', 'astertb')
      ->fields('astertb', ['name', 'email', 'image', 'date'])
      ->orderBy('date', 'DESC')
      ->execute()->fetchAll();
    $data = [];
    foreach ($result as $row) {
      $file = File::load($row->image);
      $uri = $file->getFileUri();
      $photoCats = [
        '#theme' => 'image',
        '#uri' => $uri,
        '#alt' => 'Cat',
        '#title' => 'Cat',
        '#width' => 255,
      ];
      $data[] = [
        'name' => $row->name,
        'email' => $row->email,
        'image' => [
          'data' => $photoCats,
        ],
        'date' => $row->date,
      ];
    };
    return $data;
  }
}
