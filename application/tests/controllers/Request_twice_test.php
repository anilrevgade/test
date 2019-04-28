<?php
/**
 * Part of ci-phpunit-test
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/ci-phpunit-test
 */

class Request_twice_test extends TestCase
{
	public function test_index()
	{
		$output = $this->request('GET', 'welcome/index');
		$this->assertStringContainsString('<title>Welcome to CodeIgniter</title>', $output);

		$this->request('GET', 'welcome/method_not_exist');
		$this->assertResponseCode(404);
	}
}
