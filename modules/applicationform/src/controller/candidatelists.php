<?php

namespace Drupal\applicationform\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;
use Drupal\Core\Database\Database;

/**
 * Simple page controller for Candidates.
 */
class Candidatelists extends ControllerBase {
  /**
   * The database object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
    $container->get('database')
    );
  }

  /**
   * Propagate in modifycandidateentry.
   *
   * @param string $id
   *   To get form fields values by id from database.
   *
   * @see db_select()
   * @see http://drupal.org/node/310075
   */
  public static function provideData($id) {

    $connection = Database::getConnection();
    $query = $connection->select('application_form', 'nfd');
    $query->fields('nfd',
      ['Id', 'First_Name', 'Last_Name', 'Address',
        'Email_ID', 'Number', 'Date_Of_Birth', 'Gender', 'Country', 'Postal_Code',
      ]
      );

    $query->condition('nfd.Id', $id, '=');
    $result = $query->execute()->fetchObject();
    return $result;

  }

  /**
   * This object display all the candidates.
   *
   * @todo It will display all the fields of candidates.
   */
  public function viewData() {
    $connection = Database::getConnection();
    $query = $connection->select('application_form', 'ef');
    $query->fields('ef', array('Id', 'First_Name', 'Last_Name', 'Address',
      'Email_ID', 'Number', 'Date_Of_Birth', 'Gender', 'Country', 'Postal_Code',
    ));
    // Builds header for table.
    $header = array(
    array('ID', 'field' => 'Id'),
      t('First Name'),
      t('Last Name'),
      t('Address'),
      t('Email ID'),
      t('Number'),
      t('Date Of Birth'),
      t('Gender'),
      t('Country'),
      t('Postal Code'),
      t('Edit Operation'),
      t('Delete Operation'),
    );
    // Creates TableExtender and sort it order by header.
    $table_sort = $query->extend('Drupal\Core\Database\Query\TableSortExtender')->orderByHeader($header);
    // Creates PageExtender with limit 8 per page.
    $pager = $table_sort->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(8);
    $result = $pager->execute()->fetchAll();

    foreach ($result as $row) {
      // Create rows for table.
      $rows[] = array(
        $row->Id,
        $row->First_Name,
        $row->Last_Name,
        $row->Address,
        $row->Email_ID,
        $row->Number,
        $row->Date_Of_Birth,
        $row->Gender,
        $row->Country,
        $row->Postal_Code,

        /*
        * Builds the Edit link url for the controller.
        *
        * @return Url
        *  Edit url
        */
        \Drupal::l(t('Edit'), Url::fromRoute('applicationform.modifyentry', array(),
      array('query' => array('edit_id' => $row->Id)))),

        /*
        * Builds the Delete link url for the controller.
        *
        * @return Url
        *  Delete url
        */
        \Drupal::l(t('Delete'), Url::fromRoute('applicationform.deletecandidate', array(),
      array('query' => array('delete_id' => $row->Id)))),

      );
    }

    $build = array(
      '#markup' => t('Results'),
    );
    // Builds Table.
    $build['applicationform_table'] = array(
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    );
    // Builds pager for table.
    $build['pager'] = array(
      '#type' => 'pager',
    );

    /*
     * @return $build
     *  The render array defining the elements of the table.
     */
    return $build;

  }

}
