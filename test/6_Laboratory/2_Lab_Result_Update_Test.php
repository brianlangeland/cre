<?php
/**
 * Update Lab Results
 */

namespace Test\Laboratory;

class Lab_Result_Update extends \Test\Components\OpenTHC_Test_Case
{
	function test_update_result()
	{
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-l0'], $_ENV['api-license-l0']);

		$res = $this->httpClient->get('/lab/result');
		$res = $this->assertValidResponse($res);

		// var_dump($res);

		$this->assertIsArray($res['meta']);
		// $this->assertGreaterThan(1, count($res['data']));

		// Find Random

		// Update It

	}

}
