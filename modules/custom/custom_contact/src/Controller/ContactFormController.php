<?php

namespace Drupal\custom_contact\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\custom_contact\Form\ContactForm;

class ContactFormController extends ControllerBase {
  public function formPage() {
    return [
      '#theme' => 'custom_contact_form_page',
      '#form' => \Drupal::formBuilder()->getForm(ContactForm::class),
    ];
  }
}
