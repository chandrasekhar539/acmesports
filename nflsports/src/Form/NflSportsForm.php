<?php

/**
 * @file
 * Contains \Drupal\nflsports\Form\NflSportsForm.
 */

namespace Drupal\nflsports\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Configure nflsports settings for this site.
 */
class NflSportsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nflsports_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'nflsports.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('nflsports.settings');
    $form['homepage_header_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Home Page Header Text'),
      '#default_value' => $config->get('homepage_header_text'),
    ];
    $form['api_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API URL'),
      '#default_value' => $config->get('api_url'),
    ];
    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#default_value' => $config->get('api_key'),
    ];
    $form['homepage_image'] = [
      '#type' => 'managed_file',
      '#title' => t('HomePage Image'),
      '#upload_location' => 'public://',
      '#default_value' => $config->get('homepage_image'),
    ];
    $form['national_football_image'] = [
      '#type' => 'managed_file',
      '#title' => t('National Football Image'),
      '#upload_location' => 'public://',
      '#default_value' => $config->get('national_football_image'),
    ];
    $form['american_football_image'] = [
      '#type' => 'managed_file',
      '#title' => t('American Football Image'),
      '#upload_location' => 'public://',
      '#default_value' => $config->get('american_football_image'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
      //save images permanently
      $homeimage = $form_state->getValue('homepage_image');
      $nationalimage = $form_state->getValue('national_football_image');
      $americanimage = $form_state->getValue('american_football_image');
      if (!empty($homeimage[0])) {
        $file = File::load($homeimage[0]);
        $file->setPermanent();
        $file->save();
      }
      if (!empty($nationalimage[0])) {
        $file = File::load($nationalimage[0]);
        $file->setPermanent();
        $file->save();
      }
      if (!empty($americanimage[0])) {
        $file = File::load($americanimage[0]);
        $file->setPermanent();
        $file->save();
      }
      // Retrieve the configuration
       $this->configFactory->getEditable('nflsports.settings')
      ->set('homepage_header_text', $form_state->getValue('homepage_header_text'))
      ->set('api_url', $form_state->getValue('api_url'))
      ->set('api_key', $form_state->getValue('api_key'))
      ->set('homepage_image', $form_state->getValue('homepage_image'))
      ->set('national_football_image', $form_state->getValue('national_football_image'))
      ->set('american_football_image', $form_state->getValue('american_football_image'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}
