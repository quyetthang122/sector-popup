<?php

namespace Drupal\sector_popup\Controller;

use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Controller\ControllerBase;
use Drupal\sector_popup\SectorPopupAction;

/**
 * Controller routines for manage page routes.
 */
class SectorPopupController extends ControllerBase {

  /**
   * Display all popup forms.
   *
   * @return void
   */
  public function manageAllPopups() {
    $header = [
      ['data' => $this->t('No.')],
      ['data' => $this->t('Popup_Type')],
      ['data' => $this->t('Page_Node_Id')],
      ['data' => $this->t('Status')],
      ['data' => $this->t('Edit')],
      ['data' => $this->t('Delete')],
    ];

    $result = SectorPopupAction::loadAll();

    $i = 1;
    foreach ($result as $row) {

      $popup_type = $row->popup_type;
      $status = $row->status ? 'Active' : 'Inactive';
      $page_node_id = $row->page_node;

      $url = Url::fromRoute('sector_popup.edit', ['pid' => $row->pid]);
      $edit = Link::fromTextAndUrl($this->t('Edit'), $url);
      $edit = $edit->toRenderable();

      $url = Url::fromRoute('sector_popup.delete', ['pid' => $row->pid]);
      $delete = Link::fromTextAndUrl($this->t('Delete'), $url);
      $delete = $delete->toRenderable();

      $rows[] = [
        ['data' => $i],
        ['data' => $popup_type],
        ['data' => $page_node_id],
        ['data' => $status],
        ['data' => $edit],
        ['data' => $delete],
      ];
      $i++;
    }

    $build['table'] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => 'No Popup Form created',
    ];

    return $build;

  }

  /**
   * Delete popup form function.
   *
   * @param [type] $pid
   *
   * @return void
   */
  public function deletePopup($pid) {
    $result = SectorPopupAction::delete($pid);
    if ($result) {
      \Drupal::messenger()->addStatus(t('Popup Form has been deleted Successfully.'));
    }

    return $this->redirect('sector_popup.manage');
  }

}
