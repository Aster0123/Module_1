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
    $form['adding_cat'] = [
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

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    \Drupal::messenger()->addStatus(t('Congratulations! You added your cat!;)'));
  }
}
