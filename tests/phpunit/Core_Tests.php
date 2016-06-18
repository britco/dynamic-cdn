<?php
namespace EAMann\Dynamic_CDN\Core;

use Mockery\Mock;
use EAMann\Dynamic_CDN as Base;
use WP_Mock as M;

class Core_Tests extends Base\TestCase {
	protected $testFiles = [
		'classes/DomainManager.php',
		'functions/core.php'
	];
	
	/**
	 * Test load method.
	 */
	public function test_setup() {
		// Setup
		M::expectActionAdded( 'init', 'EAMann\Dynamic_CDN\Core\init' );
		M::expectAction( 'dynamic_cdn_first_loaded' );

		// Act
		setup();

		// Verify
		$this->assertConditionsMet();
	}

	/**
	 * Test initialization method.
	 */
	public function test_init() {
		// Setup
		M::expectAction( 'dynamic_cdn_init' );
		
		// Act
		init();
		
		// Verify
		$this->assertConditionsMet();
	}

	public function test_srcset_replacement() {
		$manager = Base\DomainManager( 'http://test.com' );
		$manager->add( 'https://cdn1.com' );

		// Mocks
		M::wpFunction( 'is_ssl', [ 'return' => false ] );
		M::wpPassthruFunction( 'esc_url' );

		$source = [
			'url' => 'http://test.com/image.jpg'
		];

		$this->assertEquals( 'https://cdn1.com/image.jpg', replace_srcset( $source )['url'] );
	}
}