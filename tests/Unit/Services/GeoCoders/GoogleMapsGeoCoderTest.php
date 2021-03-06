<?php namespace Tests\Belt\Spot\Unit\Services\GeoCoders;

use Mockery as m;

use Belt\Core\Tests\BeltTestCase;
use Belt\Core\Tests;
use Belt\Spot\Services\GeoCoders\GoogleMapsGeoCoder;

class GoogleMapsGeoCoderTest extends BeltTestCase
{

    use Tests\CommonMocks;

    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Spot\Services\GeoCoders\GoogleMapsGeoCoder::apiKey
     * @covers \Belt\Spot\Services\GeoCoders\GoogleMapsGeoCoder::component
     * @covers \Belt\Spot\Services\GeoCoders\GoogleMapsGeoCoder::geocode
     */
    public function test()
    {
        $guzzle = $this->getGuzzleMock();

        $service = new GoogleMapsGeoCoder();
        $service->guzzle = $guzzle;
        $service->result = [
            'address_components' => [
                [
                    'long_name' => 'Some City',
                    'short_name' => 'Some City',
                    'types' => [
                        'locality',
                        'political',
                    ]
                ],
                [
                    'long_name' => 'United States',
                    'short_name' => 'US',
                    'types' => [
                        'country',
                        'political',
                    ]
                ],
            ]
        ];

        # component
        $this->assertEquals('Some City', $service->component('locality', 'long_name'));
        $this->assertEquals('US', $service->component('country'));
        $this->assertEquals('US', $service->component('country'));
        $this->assertEquals('', $service->component('neighborhood'));

        # service
        $service->geocode('Some Location');
        $this->assertTrue(is_array($service->location->toArray()));

        # apiKey
        putenv("GOOGLE_SERVER_API_KEY=");
        putenv("GOOGLE_API_KEY=foo");
        $this->assertEquals('foo', GoogleMapsGeoCoder::apiKey());
        putenv("GOOGLE_SERVER_API_KEY=bar");
        $this->assertEquals('bar', GoogleMapsGeoCoder::apiKey());
    }

    /**
     * @covers \Belt\Spot\Services\GeoCoders\GoogleMapsGeoCoder::geocode
     * @expectedException \Exception
     */
    public function testException()
    {
        $service = new GoogleMapsGeoCoder();

        # service fail
        $guzzle = m::mock(\GuzzleHttp\Client::class . '[get]');
        $guzzle->shouldReceive('get')->andThrow(new \Exception());
        $service->guzzle = $guzzle;
        $service->geocode('Some Location');
    }

}
