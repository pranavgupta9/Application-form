<?php

namespace Drupal\tweet_view\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\tweet_view\TweetInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Entity\EntityChangedTrait;

/**
 * Defines the ContentEntityExample entity.
 *
 * @ingroup tweet_view
 *
 * This is the main definition of the entity type. From it, an entityType is
 * derived. The most important properties in this example are listed below.
 *
 * id: The unique identifier of this entityType. It follows the pattern
 * 'moduleUrl_xyz' to avoid naming conflicts.
 *
 * label: Human readable url of the entity type.
 *
 * handlers: Handler classes are used for different tasks. You can use
 * standard handlers provided by D8 or build your own, most probably derived
 * from the standard class. In detail:
 *
 * - view_builder: we use the standard controller to view an instance. It is
 *   called when a route lists an '_entity_view' default for the entityType
 *   (see routing.yml for details. The view can be manipulated by using the
 *   standard drupal tools in the settings.
 *
 * - list_builder: We derive our own list builder class from the
 *   entityListBuilder to control the presentation.
 *   If there is a view available for this entity from the views module, it
 *   overrides the list builder. @todo: any view? naming convention?
 *
 * - form: We derive our own forms to add functionality like additional fields,
 *   redirects etc. These forms are called when the routing list an
 *   '_entity_form' default for the entityType. Depending on the suffix
 *   (.add/.edit/.delete) in the route, the correct form is called.
 *
 * - access: Our own accessController where we determine access rights based on
 *   permissions.
 *
 * More properties:
 *
 *  - base_table: Define the url of the table used to store the data. Make sure
 *    it is unique. The schema is automatically determined from the
 *    BaseFieldDefinitions below. The table is automatically created during
 *    installation.
 *
 *  - fieldable: Can additional fields be added to the entity via the GUI?
 *    Analog to content types.
 *
 *  - entity_keys: How to access the fields. Analog to 'nid' or 'uid'.
 *
 *  - links: Provide links to do standard tasks. The 'edit-form' and
 *    'delete-form' links are added to the list built by the
 *    entityListController. They will show up as action buttons in an additional
 *    column.
 *
 * There are many more properties to be used in an entity type definition. For
 * a complete overview, please refer to the '\Drupal\Core\Entity\EntityType'
 * class definition.
 *
 * The following construct is the actual definition of the entity type which
 * is read and cached. Don't forget to clear cache after changes.
 *
 * @ContentEntityType(
 *   id = "tweet_view_tweet",
 *   label = @Translation("tweet entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\tweet_view\Entity\Controller\TweetListBuilder",
 *     "form" = {
 *       "add" = "Drupal\tweet_view\Form\TweetForm",
 *       "edit" = "Drupal\tweet_view\Form\TweetForm",
 *       "delete" = "Drupal\tweet_view\Form\TweetDeleteForm",
 *     },
 *     "access" = "Drupal\tweet_view\TweetAccessControlHandler",
 *   },
 *   list_cache_contexts = { "user" },
 *   base_table = "tweet",
 *   admin_permission = "administer tweet_view entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "url",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/tweet_view_tweet/{tweet_view_tweet}",
 *     "edit-form" = "/tweet_view_tweet/{tweet_view_tweet}/edit",
 *     "delete-form" = "/tweet/{tweet_view_tweet}/delete",
 *     "collection" = "/tweet_view_tweet/list"
 *   },
 *   field_ui_base_route = "tweet_view.tweet_settings",
 * )
 *
 * The 'links' above are defined by their path. For core to find the
 * corresponding route, the route url must follow the correct pattern:
 *
 * entity.<entity-url>.<link-url> (replace dashes with underscores)
 * Example: 'entity.tweet_view_tweet.canonical'
 *
 * See routing file above for the corresponding implementation
 *
 * The Tweet class defines methods and fields for the tweet entity.
 *
 * Being derived from the ContentEntityBase class, we can override the methods
 * we want. In our case we want to provide access to the standard fields about
 * creation and changed time stamps.
 *
 * Our interface (see TweetInterface) also exposes the EntityOwnerInterface.
 * This allows us to provide methods for setting and providing ownership
 * information.
 *
 * The most important part is the definitions of the field properties for this
 * entity type. These are of the same type as fields added through the GUI, but
 * they can by changed in code. In the definition we can define if the user with
 * the rights privileges can influence the presentation (view, edit) of each
 * field.
 *
 * The class also uses the EntityChangedTrait trait which allows it to record
 * timestamps of save operations.
 */
class Tweet extends ContentEntityBase implements TweetInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   *
   * When a new entity instance is added, set the user_id entity reference to
   * the current user as the creator of the instance.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTime() {
    return $this->get('changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   *
   * Define the field properties here.
   *
   * Field url, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Tweet entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Tweet entity.'))
      ->setReadOnly(TRUE);

    // url field for the tweet.
    // We set display options for the view as well as the form.
    // Users with correct privileges can change the view and edit configuration.




    $fields['url'] = BaseFieldDefinition::create('string')
      ->setLabel(t(''))
      ->setDescription(t('Enter valid twitter url.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))

      
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ))


      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))



      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);



    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User Names'))
      ->setDescription(t('The Name of the associated user.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default');
      

      // ->setDisplayOptions('view', array(
      //   'label' => 'above',
      //   'type' => 'author',
      //   'weight' => -3,
      // ))
      // ->setDisplayOptions('form', array(
      //   'type' => 'entity_reference_autocomplete',
      //   'settings' => array(
      //     'match_operator' => 'CONTAINS',
      //     'size' => 60,
      //     'placeholder' => '',
      //   ),
      //   'weight' => -3,
      // ))


      // ->setDisplayConfigurable('form', TRUE)
      // ->setDisplayConfigurable('view', TRUE);
    
    

// $fields['counter'] = BaseFieldDefinition::create('counter')
//       ->setLabel(t('Counter'))
//       ->setDescription(t('Retweet Counter'));


    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
