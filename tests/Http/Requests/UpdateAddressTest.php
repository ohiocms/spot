<?php

use Belt\Spot\Http\Requests\UpdateAddress;

class UpdateAddressTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @covers \Belt\Spot\Http\Requests\UpdateAddress::rules
     */
    public function test()
    {

        $request = new UpdateAddress();

        $this->assertTrue(is_array($request->rules()));
    }

}