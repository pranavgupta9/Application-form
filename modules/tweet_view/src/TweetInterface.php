<?php

namespace Drupal\tweet_view;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Tweet entity.
 *
 * We have this interface so we can join the other interfaces it extends.
 *
 * @ingroup tweet_view
 */
interface TweetInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
