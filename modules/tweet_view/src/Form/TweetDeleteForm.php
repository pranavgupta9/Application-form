<?php

namespace Drupal\tweet_view\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a form for deleting a tweet_view entity.
 *
 * @ingroup tweet_view
 */
class TweetDeleteForm extends ContentEntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete twitter url %url?', array('%url' => $this->entity->label()));
  }

  /**
   * {@inheritdoc}
   *
   * If the delete command is canceled, return to the tweet list.
   */
  public function getCancelUrl() {
    return new Url('entity.tweet_view_tweet.collection');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   *
   * Delete the entity and log the event. logger() replaces the watchdog.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $entity->delete();

    $this->logger('tweet_view')->notice('@type: deleted %title.',
      array(
        '@type' => $this->entity->bundle(),
        '%title' => $this->entity->label(),
      ));
    $form_state->setRedirect('entity.tweet_view_tweet.collection');
  }

}
