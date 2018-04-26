<?php

class AdminTest extends \WP_UnitTestCase {

	/**
	 * @var \Pressbooks\CAS\Admin
	 */
	protected $admin;

	/**
	 *
	 */
	public function setUp() {
		parent::setUp();
		$this->admin = new \Pressbooks\CAS\Admin();
	}

	public function test_addMenu() {
		$this->admin->addMenu();
		$this->assertTrue( true ); // Did not crash
	}

	public function test_printMenu() {
		ob_start();
		$this->admin->printMenu();
		$buffer = ob_get_clean();
		$this->assertContains( '</form>', $buffer );
	}

	public function test_options() {

		$options = $this->admin->getOptions();

		$this->assertEquals( $options['server_version'], 'CAS_VERSION_2_0' );
		$this->assertEquals( $options['server_hostname'], '' );
		$this->assertEquals( $options['server_port'], 443 );
		$this->assertEquals( $options['server_path'], '/' );
		$this->assertEquals( $options['provision'], 'refuse' );
		$this->assertEquals( $options['email_domain'], '' );
		$this->assertEquals( $options['button_text'], '' );
		$this->assertEquals( $options['bypass'], 0 );
		$this->assertEquals( $options['forced_redirection'], 0 );

		$_REQUEST['_wpnonce'] = wp_create_nonce( 'pb-cas-sso' );
		$_POST = [
			'server_version' => 'CAS_VERSION_3_0',
			'server_hostname' => 'cas.server.edu',
			'server_port' => '999',
			'server_path' => '/foo/bar',
			'provision' => 'create',
			'email_domain' => '@pressbooks.test',
			'button_text' => 'SSO',
			'bypass' => '1',
			'forced_redirection' => '1',
		];
		$this->admin->saveOptions();
		$options = $this->admin->getOptions();

		$this->assertEquals( $options['server_version'], 'CAS_VERSION_3_0' );
		$this->assertEquals( $options['server_hostname'], 'cas.server.edu' );
		$this->assertEquals( $options['server_port'], 999 );
		$this->assertEquals( $options['server_path'], '/foo/bar/' );
		$this->assertEquals( $options['provision'], 'create' );
		$this->assertEquals( $options['email_domain'], 'pressbooks.test' );
		$this->assertEquals( $options['button_text'], 'SSO' );
		$this->assertEquals( $options['bypass'], 1 );
		$this->assertEquals( $options['forced_redirection'], 1 );
	}

}