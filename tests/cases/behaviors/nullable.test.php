<?php

App::import( 'Core', array( 'AppModel', 'Model' ) );

/**
 * Article class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.model
 */
class Article extends CakeTestModel {
  public $name = 'Article';
  public $actsAs = array( 'Nullable.Nullable' );
  public $belongsTo = array( 'Author' );
}

/**
 * Author class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.model
 */
class Author extends CakeTestModel {
  public $name = 'Author';
  public $actsAs = array( 'Nullable.Nullable' );
  public $hasMany = array( 'Article' );
}

/**
 * AuditableBehavior test class.
 */
class AuditableBehaviorTest extends CakeTestCase {
  /**
   * Fixtures associated with this test case
   *
   * @var array
   * @access public
   */
	public $fixtures = array(
		'plugin.nullable.article',
    'plugin.nullable.author',
	);
  
  /**
   * Method executed before each test
   *
   * @access public
   */
	public function startTest() {
		$this->Article = ClassRegistry::init( 'Article' );
	}
  
  /**
   * Method executed after each test
   *
   * @access public
   */
	public function endTest() {
		unset( $this->Article );

		ClassRegistry::flush();
	}
  
  /**
   * Test the action of creating a new record.
   *
   * @todo  Test HABTM save
   */
  public function testCreate() {
    $new_article = array(
      'Article' => array(
        'user_id'   => 1,
        'author_id' => '',
        'title'     => 'First Test Article', 
        'body'      => 'First Test Article Body', 
        'published' => 'N', 
      ),
    );

    $this->Article->Behaviors->detach( 'Nullable.Nullable' );
    
    $this->Article->save( $new_article );
    $article = $this->Article->find(
      'first',
      array(
        'recursive' => -1,
        'conditions' => array( 'Article.id' => $this->Article->getLastInsertId() ),
      )
    );

    # Verify that the article record.
    $this->assertEqual( null, $article['Article']['author_id'] );
    $this->assertEqual( 'First Test Article', $article['Article']['title'] );
  }

  /**
   * Test editing an existing record.
   *
   * @todo  Test change to ignored field
   * @todo  Test HABTM save
   */
  public function testEdit() {
    $article = $this->Article->find(
      'first',
      array(
        'recursive' => -1,
        'conditions' => array( 'Article.author_id' => 3 ),
      )
    );

    $article['Article']['author_id'] = '';

    $this->Article->id = $article['Article']['id'];
    $this->Article->save( $article );

    $article = $this->Article->find(
      'first',
      array(
        'recursive' => -1,
        'conditions' => array( 'Article.id' => $this->Article->id ),
      )
    );

    # Verify that the article record.
    $this->assertEqual( null, $article['Article']['author_id'] );
  }
}
