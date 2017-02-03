<?php

namespace Drupal\tweet_view\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the tweet_view entity edit forms.
 *
 * @ingroup tweet_view
 */
class TweetForm extends ContentEntityForm {

  
  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
  	$getTweetUrl = $form_state->getValue('url');
  	$tweet_url = $getTweetUrl[0]['value'];


$tweet_exist = db_select('tweet', 't')
	    ->fields('t', array('url'))
	    ->condition('t.url', $tweet_url,'=')
	    ->execute()
	    ->fetchAll();

	    $tweet_count = count($tweet_exist);

//print_r($tweet_count);die();
$twitterHas = 'https://twitter.com/';
	if (strpos($tweet_url, $twitterHas) !== false && $tweet_count == 0) {
		//echo "twitter link true and not already exist";die();
	    $form_state->setRedirect('entity.tweet_view_tweet.collection');
	    $entity = $this->getEntity();
	    $entity->save();
	}elseif(strpos($tweet_url, $twitterHas) !== false && $tweet_count == 1) {
		//echo "link true but already exist"; die();
		drupal_set_message(t('Twitter url already exist.'), 'error');
	}elseif(strpos($tweet_url, $twitterHas) == false) {
		//drupal_set_message(t('%url is incorrect. Please enter valid twitter url.'), array('%url' => $tweet_url), 'error');
		$tweeterrormsg = '"'.$tweet_url .'"'. ' is incorrect. Please enter valid twitter url.';
		drupal_set_message($tweeterrormsg, 'error');
	}	
 }
}