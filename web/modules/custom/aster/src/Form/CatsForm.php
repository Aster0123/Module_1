<?php

namespace Drupal\aster\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


class CatsForm extends FormBase
{

  public function getFormId()
  {
    return 'cats_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['cat_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your cat’s name:'),
      '#placeholder' => $this->t('The name must be in range from 2 to 32 symbols'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add cat'),
      '#button_type' => 'primary',
    ];
    return $form;
  }


  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ((mb_strlen($form_state->getValue('cat_name')) < 2)) {
      $form_state->setErrorByName('cat_name', $this->t('Your cat`s name should be more than 2 symbols.'));
    }
    if ((mb_strlen($form_state->getValue('cat_name')) > 32)) {
      $form_state->setErrorByName('cat_name', $this->t('Your cat`s name should be less than 32 symbols.'));
    }
  }


  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    \Drupal::messenger()->addStatus(t('Congratulations! You added your cat!;)'));
  }

}
