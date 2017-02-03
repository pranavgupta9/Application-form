<?php

namespace Drupal\tweet_view\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a list controller for tweet_view entity.
 *
 * @ingroup tweet_view
 */
class TweetListBuilder extends EntityListBuilder {

  /**
   * The url generator.
   *
   * @var \Drupal\Core\Routing\UrlGeneratorInterface
   */
  protected $urlGenerator;
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity.manager')->getStorage($entity_type->id()),
      $container->get('url_generator')
    );
  }

  /**
   * Constructs a new TweetListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The entity storage class.
   * @param \Drupal\Core\Routing\UrlGeneratorInterface $url_generator
   *   The url generator.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, UrlGeneratorInterface $url_generator) {
    parent::__construct($entity_type, $storage);
    $this->urlGenerator = $url_generator;


    //find user id.

    //echo $currentUser = \Drupal::currentUser()->id(); exit;
    $this->currentUser = \Drupal::currentUser();
    //echo $currentUser->id(); exit;
  }


  /**
   * {@inheritdoc}
   *
   * Building the header and content lines for the tweet list.
   *
   * Calling the parent::buildHeader() adds a column for the possible actions
   * and inserts the 'edit' and 'delete' links as defined for the entity type.
   */
  public function buildHeader() {
    //$header['id'] = $this->t('ID');
    $header['url'] = $this->t('Tweet URL');
    //$header['first_name'] = $this->t('First Name');
    //$header['gender'] = $this->t('Gender');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\tweet_view\Entity\Tweet */
    //$row['id'] = $entity->id();
    $row['url'] = $entity->link();
    //$row['first_name'] = $entity->first_name->value;
    //$row['gender'] = $entity->gender->value;
    return $row + parent::buildRow($entity);
  }

  /**
   * { @inheritdoc }
   */
  public function getEntityIds() {
    $current_user_id = $this->currentUser->id();

    $query = $this->getStorage()->getQuery()
      ->condition('user_id', $current_user_id, '=');

    return $query->execute();
  }
}
