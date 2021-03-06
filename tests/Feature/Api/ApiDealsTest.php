<?php namespace Tests\Belt\Spot\Feature\Api;

use Belt\Core\Tests;

class ApiDealsTest extends Tests\BeltTestCase
{

    public function test()
    {
        $this->refreshDB();
        $this->actAsSuper();

        # index
        $response = $this->json('GET', '/api/v1/deals');
        $response->assertStatus(200);

        # store
        $response = $this->json('POST', '/api/v1/deals', [
            'name' => 'test',
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'test']);
        $dealID = array_get($response->json(), 'id');

        # show
        $response = $this->json('GET', "/api/v1/deals/$dealID");
        $response->assertStatus(200);

        # update
        $this->json('PUT', "/api/v1/deals/$dealID", ['name' => 'updated']);
        $response = $this->json('GET', "/api/v1/deals/$dealID");
        $response->assertJson(['name' => 'updated']);

        # copy
        $this->json('POST', "/api/v1/deals/$dealID/locations", ['name' => 'test']);
        $this->json('POST', "/api/v1/deals/$dealID/attachments", ['id' => 1]);
        $this->json('POST', "/api/v1/deals/$dealID/terms", ['id' => 1]);
        $this->json('POST', "/api/v1/deals/$dealID/handles", ['url' => "cool-deal"]);
        $this->json('POST', "/api/v1/deals/$dealID/params", [
            'key' => 'foo',
            'value' => 'bar',
        ]);
        $this->json('POST', "/api/v1/deals/$dealID/sections", [
            'subtype' => 'containers.default',
        ]);

        $this->json('POST', "/api/v1/deals/$dealID/tags", ['id' => 1]);
        $response = $this->json('POST', "/api/v1/deals", ['source' => $dealID]);
        $newID = array_get($response->json(), 'id');
        $response = $this->json('GET', "/api/v1/deals/$newID");
        $response->assertStatus(200);

        # delete
        $response = $this->json('DELETE', "/api/v1/deals/$dealID");
        $response->assertStatus(204);
        $response = $this->json('GET', "/api/v1/deals/$dealID");
        $response->assertStatus(404);
    }

}