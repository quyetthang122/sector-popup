<?php

namespace Drupal\sector_popup\Form;

use Drupal\node\Entity\Node;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Form base.
 */
class SectorPopupCreateForm extends FormBase {

  /**
   * Get form id function
   * {@inheritdoc}.
   *
   * @return void
   */
  public function getFormId() {
    return 'sector_popup_create';
  }

  /**
   * Building the create form function
   * {@inheritdoc}.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return void
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    $year = date('Y', strtotime("+1 years"));

    $form['popup_type'] = [
      '#type' => 'select',
      '#title' => t('Select the pop up type'),
      '#empty_value' => '',
      '#empty_option' => '- Select a page -',
      '#options' => $this->getPopupFormType(),
      '#require' => TRUE,
      '#weight' => -10,
    ];

    $form['container_popup'] = [
        '#type' => 'container',
        '#states' => [
        'visible' => [
          [
            [':input[name="popup_type"]' => ['value' => 'popup_center']],
            'or',
            [':input[name="popup_type"]' => ['value' => 'popup_all_screen']],
            'or',
            [':input[name="popup_type"]' => ['value' => 'popup_left_corner']],
            'or',
            [':input[name="popup_type"]' => ['value' => 'popup_event_coming']],
          ],
        ],
      ],
      ];

      $form['container_popup']['popup_enable'] = [
        '#type' => 'checkbox',
        '#title' => t('enable Popup form'),
      ];

      // $form['container_popup']['popup_select_content_type'] = [
      //   '#type' => 'select',
      //   '#title' => t('Select content type'),
      //   '#empty_value' => '',
      //   '#empty_option' => '- Select content type -',
      //   '#options' => $this->getContentTypes(),
      //   '#require' => TRUE,
      // ];

      $form['container_popup']['popup_select_page'] = [
        '#type' => 'select',
        '#title' => t('Select the page to popup'),
        '#empty_value' => '',
        '#empty_option' => '- Select a page -',
        '#options' => $this->getNodeTitles(),
        '#require' => TRUE,
      ];

      $form['container_popup']['popup_image'] = [
        '#type'          => 'managed_file',
        '#title'         => t('Choose your file to upload'),
        '#upload_location' => 'public://images/',
        '#default_value' => '',
        '#description'   => t('Your description for upload file'),
      ];

      $form['container_popup']['popup_date'] = array(
          '#type' => 'date',
          '#title' => 'Enter Date of Event',
          '#required' => TRUE,
          '#default_value' => array('month' => 10, 'day' => 26, 'year' => 2020),
          '#format' => 'mm/dd/YY',
          '#description' => t('i.e. 09/06/' . $year),
          '#states' => [
          'invisible' => [
            [
              [':input[name="popup_type"]' => ['value' => '']],
              'or',
              [':input[name="popup_type"]' => ['value' => 'popup_all_screen']],
              'or',
              [':input[name="popup_type"]' => ['value' => 'popup_left_corner']],
              'or',
              [':input[name="popup_type"]' => ['value' => 'popup_center']],
            ],
          ],
        ],
      );

      $form['container_popup']['popup_button_name'] = [
        '#type' => 'textfield',
        '#title' => t('Button name for popup form'),
        '#default_value' => '',
        '#description' => $this->t('e.g: Submit, See more, Read more,..'),
        '#size' => 60,
        '#required' => TRUE,
      ];

      $form['container_popup']['popup_link'] = [
        '#type' => 'linkit',
        '#title' => $this->t('Select link target for button'),
        '#description' => $this->t('Start typing to see a list of results. Click to select.'),
        '#autocomplete_route_name' => 'linkit.autocomplete',
        '#autocomplete_route_parameters' => [
          'linkit_profile_id' => 'default',
        ],
      ];

      $form['container_popup']['popup_title'] = [
        '#type' => 'textfield',
        '#title' => t('Title for popup form'),
        '#default_value' => '',
        '#size' => 60,
        '#required' => TRUE,
      ];

      $form['container_popup']['content_1'] = [
        '#type' => 'text_format',
        '#title' => 'Body',
        '#format' => 'basic_html',
        '#default_value' => '<p>the default value for content</p>',
        '#require' => TRUE,
      ];

      $form['container_popup']['popup_title_2'] = [
        '#type' => 'textfield',
        '#title' => t('Title for popup form 2'),
        '#default_value' => '',
        '#size' => 60,
        '#required' => TRUE,
        '#states' => [
        'invisible' => [
          [
            [':input[name="popup_type"]' => ['value' => '']],
            'or',
            [':input[name="popup_type"]' => ['value' => 'popup_all_screen']],
            'or',
            [':input[name="popup_type"]' => ['value' => 'popup_left_corner']],
            'or',
            [':input[name="popup_type"]' => ['value' => 'popup_event_coming']],
          ],
        ],
      ],
      ];

      $form['container_popup']['content_2'] = [
        '#type' => 'text_format',
        '#title' => 'Body 2',
        '#format' => 'basic_html',
        '#default_value' => '<p>the default value for content</p>',
        '#states' => [
        'invisible' => [
          [
            [':input[name="popup_type"]' => ['value' => '']],
            'or',
            [':input[name="popup_type"]' => ['value' => 'popup_all_screen']],
            'or',
            [':input[name="popup_type"]' => ['value' => 'popup_left_corner']],
            'or',
            [':input[name="popup_type"]' => ['value' => 'popup_event_coming']],
          ],
        ],
      ],
      ];

      $form['container_popup']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
        '#weight' => 1000,
      ];

    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // if ($form_state->getValue('popup_title') == NULL) {
    //   $form_state->setErrorByName('popup_title', $this->t('Please put your title'));
    // }
    // if($form_state->getValue('popup_image') == NULL){
    //     $form_state->setErrorByName('popup_image', $this->t('Please set your image'));
    // }.
    // if ($form_state->getValue('popup_select_page') == NULL) {
    //   $form_state->setErrorByName('popup_select_page', $this->t('Please set your image'));
    // }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $url = $this->getImageUrl($form_state->getValue('popup_image')[0]);

    $json = [
      'page_node' => $form_state->getValue('popup_select_page'),
      'popup_type' => '',
      'img' => $url,
      'button_name' => $form_state->getValue('popup_button_name'),
      'link_target' => $form_state->getValue('popup_link'),
      'content_1' => $form_state->getValue('content_left')['value'],
      'content_2' => $form_state->getValue('content_right')['value'],
      'status' => $form_state->getValue('popup_enable'),
    ];

    $json = json_encode($json);

    $entry = [
      'page_node' => $form_state->getValue('popup_select_page'),
      'popup_type' => '',
      'img' => $form_state->getValue('popup_image')[0],
      'button_name' => $form_state->getValue('popup_button_name'),
      'link_target' => $form_state->getValue('popup_link'),
      'content_1' => $form_state->getValue('content_1')['value'],
      'content_2' => $form_state->getValue('content_2')['value'],
      'json' => $json,
      'status' => $form_state->getValue('popup_enable'),
    ];

    //dpm($entry);

    // $return = SectorPopupAction::insert($entry);
    // Return message after insert values into the database.
    // $this->reponseMessage($return);
  }

  /**
   * Get Node title.
   *
   * @return void
   */
  protected function getNodeTitles() {
    $titles = [];
    $nids = \Drupal::entityQuery('node')
    // Content type.
      ->condition('type', 'page')
      ->execute();
    foreach ($nids as $nid) {
      $node = Node::load($nid);
      $title = $node->title->value;
      $titles[$nid] = $title;
    }
    return $titles;
  }

  protected function getContentTypes(){
     $node_types = \Drupal\node\Entity\NodeType::loadMultiple();
      // If you need to display them in a drop down:
      $options = [];
      foreach ($node_types as $node_type) {
        $options[$node_type->id()] = $node_type->label();
      }
      return $options;
  }

  /**
   * Get Image Url.
   *
   * @param [type] $imageid
   *
   * @return void
   */
  protected function getImageUrl($imageid) {
    $file = File::load($imageid);
    $url = $file->url();
    return $url;
  }

  /**
   * Return message when the form finish.
   *
   * @param [type] $return
   *
   * @return void
   */
  protected function reponseMessage($return) {

    if ($return) {
      $message = \Drupal::messenger()->addStatus(t('Popup settings has been created Successfully.'));

      return $message;
    }
    return \Drupal::messenger()->addError(t('Oop, Something Wrong!'));
  }

  /**
   * Return popup form type function.
   *
   * @return void
   */
  protected function getPopupFormType() {
    return [
      'popup_center' => 'Popup Center',
      'popup_event_coming' => 'Popup event coming',
      'popup_all_screen' => 'Popup all screen',
      'popup_left_corner' => 'Popup left corner',

    ];

  }

}
