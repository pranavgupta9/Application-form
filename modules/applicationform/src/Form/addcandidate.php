<?php

namespace Drupal\applicationform\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

/**
 * It will implement the addcandidate form.
 *
 * This Form demostrates a application form with text input element. we
 * extend FormBase which is the simplest form base used in drupal.
 *
 * @see \Drupal\Core\Form\FormBase
 */
class Addcandidate extends FormBase {

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
    return 'candidateform';
  }

  /**
   * Build the simple form.
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
    // textfield.
    $form['candidatefirstname'] = array(
      '#type' => 'textfield',
      '#title' => t('First Name:'),
      '#required' => TRUE,
    );
    // textfield.
    $form['candidatesurname'] = array(
      '#type' => 'textfield',
      '#title' => t('Last Name:'),
      '#required' => TRUE,
    );
    // textfield.
    $form['candidateaddress'] = array(
      '#type' => 'textfield',
      '#title' => t('Address:'),
      '#required' => TRUE,
    );
    // email.
    $form['candidatemail'] = array(
      '#type' => 'email',
      '#title' => t('Email ID'),
      '#required' => TRUE,
    );
    // textfield.
    $form['candidatenumber'] = array(
      '#type' => 'textfield',
      '#title' => t('Number'),
      '#required' => TRUE,
      '#size' => 10,
      '#maxlength' => 10,

    );
    // date.
    $form['candidatedob'] = array(
      '#type' => 'date',
      '#title' => t('Date Of Birth'),
      '#required' => TRUE,

    );
    // radio.
    $form['candidategender'] = array(
      '#type' => 'radios',
      '#title' => ('Gender'),
      '#required' => TRUE,
      '#options' => array(
        'Female' => t('Female'),
        'male' => t('Male'),

      ),
    );
    // select.
    $form['candidatecountry'] = array(
      '#type' => 'select',
      '#title' => ('Country'),
      '#required' => TRUE,
      '#options' => array(
        'USA' => t('USA'),
        'India' => t('INDIA'),
        'UK' => t('UK'),
      ),
    );
    // tel.
    $form['candidatepostalcode'] = array(
      '#type' => 'tel',
      '#title' => t('Postal Code'),
      '#required' => TRUE,
      '#size' => 10,
      '#maxlength' => 10,
    );

    // Add a submit button that handles the submission of the form.
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',

    );

    // @return array
    // The render array defining the elements of the form.
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
    if (strlen($form_state->getValue('candidatefirstname')) > 15) {
      $form_state->setErrorByName('candidatefirstname', $this->t('First name should not be more than 10 characters'));
    }
    // Set an error for the form with a key of "candidatesurname".
    if (strlen($form_state->getValue('candidatesurname')) > 15) {
      $form_state->setErrorByName('candidatesurname', $this->t('Last name should not be more than 10 characters'));
    }
    // Set an error for the form with a key of "candidatenumber".
    if (!is_numeric($form_state->getValue('candidatenumber')) || strlen($form_state->getValue('candidatenumber')) < 10) {
      $form_state->setErrorByName('candidatenumber', $this->t('Only number is allowed and it should be atleast 10 digits'));
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

    /*-
     * Save an entry in the database.
     * The underlying function is db_insert().
     * Making Database connection object.
     * @return int
     * The number of updated rows.
     * @see db_insert()
     */
    $conn = Database::getConnection();
    $conn->insert('application_form')->fields(
    array(
      'First_Name' => $form_state->getValue('candidatefirstname'),
      'Last_Name' => $form_state->getValue('candidatesurname'),
      'Address' => $form_state->getValue('candidateaddress'),
      'Email_ID' => $form_state->getValue('candidatemail'),
      'Number' => $form_state->getValue('candidatenumber'),
      'Date_Of_Birth' => $form_state->getValue('candidatedob'),
      'Gender' => $form_state->getValue('candidategender'),
      'Country' => $form_state->getValue('candidatecountry'),
      'Postal_Code' => $form_state->getValue('candidatepostalcode'),

    )
    )->execute();
    // Set a message that form submitted has been saved.
    drupal_set_message(t('successfully saved the data'));

  }

}
