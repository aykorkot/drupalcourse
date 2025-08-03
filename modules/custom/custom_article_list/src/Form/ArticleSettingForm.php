<?php

namespace Drupal\custom_article_list\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ArticleSettingForm extends ConfigFormBase {

  protected function getEditableConfigNames() {
    return ['custom_article_list.settings'];
  }

  public function getFormId() {
    return 'custom_article_list_settings_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('custom_article_list.settings');

    $form['article_count'] = [
      '#type' => 'number',
      '#title' => $this->t('Nombre d\'articles à afficher'),
      '#default_value' => $config->get('article_count') ?? 5,
      '#min' => 1,
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('custom_article_list.settings')
      ->set('article_count', $form_state->getValue('article_count'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
