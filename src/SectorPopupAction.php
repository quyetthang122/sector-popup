<?php

namespace Drupal\sector_popup;

/**
 * Class action for SectorPopup Module.
 */
class SectorPopupAction {

  /**
   * insert database into the table
   *
   * @param array $entry
   * @return void
   */
  public static function insert(array $entry) {
    $return_value = NULL;
    try {
      $return_value = db_insert('sector_popup')
        ->fields($entry)
        ->execute();
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addMessage(t('db_insert failed. Message = %message, query =$query', [
        '%message' => $e->getMessage(),
        '%query' => $e->query_string,
      ]), 'error');
    }

    return $return_value;
  }

  /**
   * Update the popup table.
   *
   * @param array $entry
   * @return void
   */
  public static function update(array $entry) {
    try {
      $count = db_update('sector_popup')
        ->fields($entry)
        ->condition('pid', $entry['pid'])
        ->execute();

    }
    catch (\Exception $e) {
      \Drupal::messenger()->addMessage(t('db_update failed. Message = %message, query = $query', [
        '%message' => $e->getMessage(),
        '%query' => $e->query_string,
      ]), 'error');
    }

    return $count;
  }

  /**
   * Load table base on id.
   *
   * @param [type] $pid
   * @return void
   */
  public static function load($pid) {
    $select = db_select('sector_popup', 'pd');
    $select->fields('pd');
    $select->condition('pid', $pid);
    // Return the result in object format.
    return $select->execute()->fetchAll();
  }

  /**
   * Load all the table.
   *
   * @return void
   */
  public static function loadAll() {
    $select = db_select('sector_popup', 'pd');
    $select->fields('pd');
    // Return all value in table.
    return $select->execute()->fetchAll();
  }

  /**
   * Delete the popup table.
   *
   * @param [type] $pid
   * @return void
   */
  public static function delete($pid) {
    $select = db_delete('sector_popup');
    $select->condition('pid', $pid);
    // Return the result in object format.
    return $select->execute();
  }

}
