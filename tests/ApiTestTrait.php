<?php namespace Tests;

use function PHPUnit\Framework\assertContains;

trait ApiTestTrait
{
    private $response;
    public function assertApiResponse(Array $actualData)
    {
        $this->assertApiSuccess();

        $response = json_decode($this->response->getContent(), true);
        $responseData = $response['data'];
        $this->assertNotEmpty($responseData['id']);
    }

    public function assertApiSuccess()
    {
        $this->response->assertStatus(200);
    }

    public function assertModelData(Array $actualData, Array $expectedData)
    {
        foreach ($actualData as $key => $value) {
            if (in_array($key, ['created_at', 'updated_at'])) {
                continue;
            }
            $this->assertEquals($actualData[$key], $expectedData[$key]);
        }
    }
}
