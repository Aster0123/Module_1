<?php

namespace Drupal\aster\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\MessageCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 *
 */
class EditCats extends FormBase {

  public $idCat;

  /**
   *
   */
  public function getFormId() {
    return 'edit cat';
  }

  /**
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state, $idCat = NULL) {
    $this->id = $idCat;
    $query = \Drupal::database();
    $data = $query
      ->select('aster', 'edt')
      ->condition('edt.id', $idCat, '=')
      ->fields('edt', ['name', 'email', 'image', 'id'])
      ->execute()->fetchAll();
    $form['cat_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your cat’s name:'),
      '#default_value' => $data[0]->name,
      '#required' => TRUE,
      '#maxlength' => 32,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Your email:'),
      '#default_value' => $data[0]->email,
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::AjaxEmail',
        'event' => 'change',
        'progress' => [
          'type' => 'none',
        ],
      ],
    ];

    $form['cat_image'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Your cat’s photo:'),
      '#description' => t('Please use only these extensions: jpeg, jpg, png'),
      '#upload_location' => 'public://images/',
      '#default_value' => [$data[0]->image],
      '#required' => TRUE,
      '#upload_validators' => [
        'file_validate_extensions' => ['jpeg jpg png'],
        'file_validate_size' => [2097152],
      ],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Edit'),
      '#button_type' => 'primary',
      '#ajax' => [
        'callback' => '::AjaxSubmit',
        'progress' => [
          'type' => 'none',
        ],
      ],
    ];
    return $form;
  }

  /**
   *
   */
  public function validateName(array &$form, FormStateInterface $form_state) {
    if ((mb_strlen($form_state->getValue('cat_name')) < 2)) {
      return FALSE;
    }
    elseif ((mb_strlen($form_state->getValue('cat_name')) > 32)) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   *
   */
  public function validateEmail(array &$form, FormStateInterface $form_state) {
    if (!preg_match("/^[a-zA-Z_\-]+@[a-zA-Z_\-\.]+\.[a-zA-Z\.]{2,6}+$/", $form_state->getValue('email'))) {
      $form_state->setErrorByName('email', $this->t('Your email is NOT invalid'));
      return FALSE;
    }
    return TRUE;
  }

  /**
   *
   */
  public function validateImage(array &$form, FormStateInterface $form_state) {
    $picture = $form_state->getValue('cat_image');

    if (!empty($picture[0])) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   *
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!$this->validateName($form, $form_state) && $this->validateEmail($form, $form_state) && $this->validateImage($form, $form_state)) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }

  /**
   *
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if ($this->validateForm($form, $form_state)) {
      $picture = $form_state->getValue('cat_image');
      $file = File::load($picture[0]);
      $file->setPermanent();
      $file->save();

      $cat = [
        'name' => $form_state->getValue('cat_name'),
        'email' => $form_state->getValue('email'),
        'image' => $picture[0],
      ];
      \Drupal::database()->update('aster')->condition('id', $this->id)->fields($cat)->execute();
      $form_state->setRedirect('aster.cats');
    }
  }

  /**
   *
   */
  public function AjaxSubmit(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();
    $nameValid = $this->validateName($form, $form_state);
    $imageValid = $this->validateImage($form, $form_state);

    if (!$nameValid) {
      $response->addCommand(new MessageCommand('Your name is NOT valid'));
    }
    elseif (!$imageValid) {
      $response->addCommand(new MessageCommand('Please, upload your cat image'));
    }
    else {
      $response->addCommand(new MessageCommand('Congratulations! You edited your cat!'));
    }
    \Drupal::messenger()->deleteAll();
    return $response;
  }

  /**
   *
   */
  public function AjaxEmail(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();
    if (preg_match("/^[a-zA-Z_\-]+@[a-zA-Z_\-\.]+\.[a-zA-Z\.]{2,6}+$/", $form_state->getValue('email'))) {
      $response->addCommand(new MessageCommand('Your email is valid'));
    }
    else {
      $response->addCommand(new MessageCommand('Your email is NOT valid', ".null", [], TRUE));
    }
    return $response;
  }

}
