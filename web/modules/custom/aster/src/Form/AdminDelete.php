<?php

namespace Drupal\aster\Form;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 *
 */
class AdminDelete extends ConfirmFormBase {

  public $idCat;

  /**
   *
   */
  public function getFormId() {
    return 'delete admin cat';
  }

  /**
   *
   */
  public function getQuestion() {
    return t('To delete this cat?');
  }

  /**
   *
   */
  public function getDescription(): TranslatableMarkup {
    return t('Do you really want to delete this cat?');
  }

  /**
   *
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   *
   */
  public function getCancelText() {
    return t('Cancel');
  }

  /**
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state, $idCat = NULL) {
    $this->id = $idCat;
    return parent::buildForm($form, $form_state);
  }

  /**
   *
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $query = \Drupal::database();
    $query->delete('aster')
      ->condition('id', $this->id)
      ->execute();
    \Drupal::messenger()->addStatus('You deleted your cat');
    $form_state->setRedirect('admin_menu');
  }

  /**
   *
   */
  public function getCancelUrl() {
    return new Url('admin_menu');
  }

}
