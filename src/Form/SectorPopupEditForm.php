<?php

namespace Drupal\sector_popup\Form;

use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Form\FormStateInterface;
use Drupal\sector_popup\SectorPopupAction;
use Drupal\sector_popup\Form\SectorPopupCreateForm;

/**
 * Implement
 *
 */
class SectorPopupEditForm extends SectorPopupCreateForm
{
  public function getFormId()
  {
    return 'sector_popup_edit';
  }
  public function buildForm(array $form, FormStateInterface $form_state, $pid = NULL)
  {
    $entry = SectorPopupAction::load($pid);

    if(empty($entry)) {
      $form['no_value'] = [
        '#markup' => $this->t('<h3>No results found, Please check the popup page again.</h3>')
      ];
      return $form;
    }
    $entry = $entry[0];
    $form = parent::buildForm($form, $form_state);

    $form['popup_enable'] = [
      '#type' => 'checkbox',
      '#title' => t('enable Popup form'),
      '#default_value' => $entry->status,
    ];
    $form['popup_select_page']['#default_value'] = $entry->page_node;
    $form['popup_image']['#default_value'] =  array($entry->img);
    $form['popup_button_name']['#default_value'] = $entry->button_name;
    $form['popup_link']['#default_value'] = $entry->link_target;
    $form['content_left']['#default_value'] = $entry->content_left;
    $form['content_right']['#default_value'] = $entry->content_right;
    $form_state->set('sector_popup_id', $pid);
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {

  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $pid = $form_state->get('sector_popup_id');
    $json = [
      'page_node' => $form_state->getValue('popup_select_page'),
      'popup_type' => '',
      'img' => $form_state->getValue('popup_image')[0],
      'button_name' => $form_state->getValue('popup_button_name'),
      'link_target' => $form_state->getValue('popup_link'),
      'content_left' => $form_state->getValue('content_left')['value'],
      'content_right' => $form_state->getValue('content_right')['value'],
      'status' => $form_state->getValue('popup_enable'),
    ];

    $json = json_encode($json);

    $entry = [
      'pid' => $pid,
      'page_node' => $form_state->getValue('popup_select_page'),
      'popup_type' => '',
      'img' => $form_state->getValue('popup_image')[0],
      'button_name' => $form_state->getValue('popup_button_name'),
      'link_target' => $form_state->getValue('popup_link'),
      'content_left' => $form_state->getValue('content_left')['value'],
      'content_right' => $form_state->getValue('content_right')['value'],
      'json' => $json,
      'status' => $form_state->getValue('popup_enable'),
    ];

    $return = SectorPopupAction::update($entry);
    if ($return) {
      $url = Url::fromRoute('sector_popup.manage');
      $form_state->setRedirectUrl($url);
      \Drupal::messenger()->addStatus(t('Popup settings has been updated Successfully.'));
    } else {
      \Drupal::messenger()->addError(t('Oop, Something Wrong!'));
    }
  }
}
