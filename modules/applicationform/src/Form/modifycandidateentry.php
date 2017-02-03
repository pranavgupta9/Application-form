<?php

namespace Drupal\applicationform\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\applicationform\controller\candidatelists;
use Drupal\Core\Url;

/**
 * It will implement the modifycandidateentry form.
 *
 * This Form demostrates an edit candidate form with text input element.
 * we extend FormBase which is the simplest form base used in drupal.
 *
 * @see \Drupal\Core\Form\FormBase
 */
class Modifycandidateentry extends FormBase {

  /**
   * Getter method for Form ID.
   *
   * The form ID is used in implementations of hook_form_alter() to allow other
   * modules to alter the render array built by this form. It must be unique
   * site wide. It normally starts with the providing module's name.
   *
   * @return string
   *   The unique ID of the form defined by this class.
   */
  public function getFormId() {
    return 'modifyentry';
  }

  /**
   * Build the simple Edit form.
   *
   * A build form method constructs an array that defines how markup and
   * other form elements are included in an HTML form.
   *
   * @param array $form
   *   Default form array structure.
   * @param FormStateInterface $form_state
   *   Object containing current form state.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /*
     * @param string $Id
     *  $Id will set the variables in the form.
     *
     * The explode() function breaks a string into an array.
     */
    $path = \Drupal::request()->getQueryString();
    $arg = explode('=', $path);
    $id = isset($arg[1]) ? $arg[1] : '';
    if (!empty($id)) {

      // @param array $data
      // $data place values in fields using provideData.
      // provideData is an object of class candidatelists.
      $data = candidatelists::provideData($id);
      $first_name = $data->First_Name;
      $last_name = $data->Last_Name;
      $address = $data->Address;
      $email_id = $data->Email_ID;
      $number = $data->Number;
      $date_of_birth = $data->Date_Of_Birth;
      $gender = $data->Gender;
      $country = $data->Country;
      $postal_code = $data->Postal_Code;

    }

    else {
      // If values are empty.
      $first_name = '';
      $last_name = '';
      $address = '';
      $email_id = '';
      $number = '';
      $date_of_birth = '';
      $gender = '';
      $country = '';
      $postal_code = '';

    }
    // Place default_value as $Id.
    // 'type' is hidden.
    $form['Node_Id'] = array(
      '#type' => 'hidden',
      '#default_value' => $id,
    );
    // Place default_value as $First_name.
    $form['candidatefirstname'] = array(
      '#type' => 'textfield',
      '#title' => t('First Name'),
      '#required' => TRUE,
      '#default_value' => $first_name,
    );
    // Place default_value as $Last_Name.
    $form['candidatesurname'] = array(
      '#type' => 'textfield',
      '#title' => t('Last Name'),
      '#required' => TRUE,
      '#default_value' => $last_name,
    );
    // Place default_value as $Address.
    $form['candidateaddress'] = array(
      '#type' => 'textfield',
      '#title' => t('Address'),
      '#required' => TRUE,
      '#default_value' => $address,
    );
    // Place default_value as $Email_ID.
    $form['candidatemail'] = array(
      '#type' => 'email',
      '#title' => t('Email ID'),
      '#required' => TRUE,
      '#default_value' => $email_id,
    );
    // Place default_value as $Number.
    $form['candidatenumber'] = array(
      '#type' => 'tel',
      '#title' => t('Number'),
      '#required' => TRUE,
      '#size' => 10,
      '#maxlength' => 10,
      '#default_value' => $number,
    );
    // Place default_value as $Date_of_Birth.
    $form['candidatedob'] = array(
      '#type' => 'date',
      '#title' => t('Date Of Birth'),
      '#required' => TRUE,
      '#default_value' => $date_of_birth,

    );
    // Place default_value as $Gender.
    $form['candidategender'] = array(
      '#type' => 'radios',
      '#title' => ('Gender'),
      '#required' => TRUE,
      '#options' => array(
        'Female' => t('Female'),
        'male' => t('Male'),
      ),
      '#default_value' => $gender,

    );
    // Place default_value as $Country.
    $form['candidatecountry'] = array(
      '#type' => 'select',
      '#title' => ('Country'),
      '#required' => TRUE,
      '#options' => array(
        'USA' => t('USA'),
        'India' => t('India'),
        'UK' => t('UK'),
      ),
      '#default_value' => $country,

    );
    // Place default_value as $Postal_Code.
    $form['candidatepostalcode'] = array(
      '#type' => 'tel',
      '#title' => t('Postal Code'),
      '#required' => TRUE,
      '#size' => 10,
      '#maxlength' => 10,
      '#default_value' => $postal_code,
    );

    // Add a submit button that handles the submission of the form.
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Edit'),
    );

    /*
     * @return array
     *  The render array defining the elements of the form.
     */
    return $form;
  }

  /**
   * Implements form validation.
   *
   * The validateForm method is the default method called to validate input on
   * a form.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Set an error for the form with a key of "candidatefirstname".
    if (strlen($form_state->getValue('candidatefirstname')) > 10) {
      $form_state->setErrorByName('candidatefirstname', $this->t('First name should not be more than 8 characters'));
    }
    // Set an error for the form with a key of "candidatesurname".
    if (strlen($form_state->getValue('candidatesurname')) > 10) {
      $form_state->setErrorByName('candidatesurname', $this->t('Last name should not be more than 8 characters'));
    }
    // Set an error for the form with a key of "candidatenumber".
    if (!is_numeric($form_state->getValue('candidatenumber')) || strlen($form_state->getValue('candidatenumber')) < 10) {
      $form_state->setErrorByName('candidatenumber', $this->t('Only number is allowed'));
    }
    // Set an error for the form with a key of "candidatemail".
    if (!filter_var($form_state->getValue('candidatemail'))) {
      $form_state->setErrorByName('candidatemail', $this->t('That e-mail address is not valid.'));
    }
    // Set an error for the form with a key of "candidatefirstname".
    if (!preg_match("/^[a-zA-Z ]*$/", $form_state->getValue('candidatefirstname'))) {
      $form_state->setErrorByName('candidatefirstame', $this->t('First Name is invalid only letters and whitespace'));
    }
    // Set an error for the form with a key of "candidatesurname".
    if (!preg_match("/^[a-zA-Z ]*$/", $form_state->getValue('candidatesurname'))) {
      $form_state->setErrorByName('candidatesurname', $this->t('Last Name is invalid only letters and whitespace'));
    }
    // Set an error for the form with a key of "candidatemail".
    if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $form_state->getValue('candidatemail'))) {
      $form_state->setErrorByName('candidatemail', $this->t('invalid email'));
    }
    // Set an error for the form with a key of "candidateaddress".
    if (preg_match("/[&{}$()<>%\*\#@~;:]/i", $form_state->getValue('candidateaddress'))) {
      $form_state->setErrorByName('candidateaddress', $this->t('Certain special characters are not allowed in address field.'));
    }
    // Set an error for the form with a key of "candidatepostalcode".
    if (!is_numeric($form_state->getValue('candidatepostalcode')) || strlen($form_state->getValue('candidatepostalcode')) > 10) {
      $form_state->setErrorByName('candidatepostalcode', $this->t('postal code should be numerical and not more than 10 digits.'));

    }

  }

  /**
   * Implements a form submit handler.
   *
   * The submitForm method is the default method called for any submit elements.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    /*
     * Update an entry in the database.
     *
     * @param array $store
     *   An array containing all the fields of the item to be updated.
     *
     * @return int
     *   The number of updated rows.
     *
     * @see db_update()
     */
    $store = array(

      'First_Name' => $form_state->getValue('candidatefirstname'),
      'Last_Name' => $form_state->getValue('candidatesurname'),
      'Address' => $form_state->getValue('candidateaddress'),
      'Email_ID' => $form_state->getValue('candidatemail'),
      'Number' => $form_state->getValue('candidatenumber'),
      'Date_Of_Birth' => $form_state->getValue('candidatedob'),
      'Gender' => $form_state->getValue('candidategender'),
      'Country' => $form_state->getValue('candidatecountry'),
      'Postal_Code' => $form_state->getValue('candidatepostalcode'),
    );
    // db_update()...->execute() returns the number of rows updated.
    $query = \Drupal::database()->update('application_form');
    $query->fields($store);
    $query->condition('Id', $form_state->getValue('Node_Id'));
    $query->execute();
    $url = Url::fromRoute('applicationform.candidatelist');
    return $form_state->setRedirectUrl($url);

  }

}
