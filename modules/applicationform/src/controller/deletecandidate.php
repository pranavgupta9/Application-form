<?php

namespace Drupal\applicationform\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Simple page controller for deleting candidates.
 */
class Deletecandidate extends ControllerBase {

  /**
   * Delete an entry from the database.
   *
   * @param string $id
   *   An array containing at least the person identifier 'id' element of the
   *   entry to delete.
   *
   * @see db_delete()
   */
  public static function deleteItem($id) {

    $query = \Drupal::database()->delete('application_form');
    $query->condition('Id', $id, '=');
    $query->execute();

  }

  /**
   * Delete entry from the table.
   *
   * @todo $id and $param_node is used to set the parameters.
   *
   *   The result of explode() does not depend on any run-time information.
   *
   *   self is called to delete an entry from the table.
   *   inside this class.
   */
  public function deleteData() {
    $path = \Drupal::request()->getQueryString();
    $arg = explode('=', $path);
    $param_node = isset($arg[0]) ? $arg[0] : '';
    $id = isset($arg[1]) ? $arg[1] : '';
    self::deleteItem($id);
    return $this->redirect('applicationform.candidatelist');
  }

}
