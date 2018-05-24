<?php

use Illuminate\Database\Eloquent\Model;
use Belt\Spot\Behaviors\IncludesAddress;

class IncludesAddressTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @covers \Belt\Spot\Behaviors\IncludesAddress::full
     * @covers \Belt\Spot\Behaviors\IncludesAddress::getFullAttribute
     * @covers \Belt\Spot\Behaviors\IncludesAddress::setNameAttribute
     * @covers \Belt\Spot\Behaviors\IncludesAddress::setNicknameAttribute
     * @covers \Belt\Spot\Behaviors\IncludesAddress::setLine1Attribute
     * @covers \Belt\Spot\Behaviors\IncludesAddress::setLine2Attribute
     * @covers \Belt\Spot\Behaviors\IncludesAddress::setLine3Attribute
     * @covers \Belt\Spot\Behaviors\IncludesAddress::setLine4Attribute
     * @covers \Belt\Spot\Behaviors\IncludesAddress::setLocalityAttribute
     * @covers \Belt\Spot\Behaviors\IncludesAddress::setSubLocalityAttribute
     * @covers \Belt\Spot\Behaviors\IncludesAddress::setRegionAttribute
     * @covers \Belt\Spot\Behaviors\IncludesAddress::setPostcodeAttribute
     * @covers \Belt\Spot\Behaviors\IncludesAddress::setCountryAttribute
     * @covers \Belt\Spot\Behaviors\IncludesAddress::setOriginalAttribute
     * @covers \Belt\Spot\Behaviors\IncludesAddress::setFormattedAttribute
     */
    public function test()
    {
        $addressStub = new IncludesAddressTestStub();

        # name
        $addressStub->setNameAttribute(' Test ');
        $this->assertEquals('TEST', $addressStub->name);

        # nickname
        $addressStub->setNicknameAttribute(' Test ');
        $this->assertEquals('TEST', $addressStub->nickname);

        # line1
        $addressStub->setLine1Attribute(' Test ');
        $this->assertEquals('TEST', $addressStub->line1);

        # line2
        $addressStub->setLine2Attribute(' Test ');
        $this->assertEquals('TEST', $addressStub->line2);

        # line3
        $addressStub->setLine3Attribute(' Test ');
        $this->assertEquals('TEST', $addressStub->line3);

        # line4
        $addressStub->setLine4Attribute(' Test ');
        $this->assertEquals('TEST', $addressStub->line4);

        # locality
        $addressStub->setLocalityAttribute(' Test ');
        $this->assertEquals('TEST', $addressStub->locality);

        # sub_locality
        $addressStub->setSubLocalityAttribute(' Test ');
        $this->assertEquals('TEST', $addressStub->sub_locality);

        # region
        $addressStub->setRegionAttribute(' Test ');
        $this->assertEquals('TEST', $addressStub->region);

        # postcode
        $addressStub->setPostcodeAttribute(' Test ');
        $this->assertEquals('TEST', $addressStub->postcode);

        # country
        $addressStub->setCountryAttribute(' Test ');
        $this->assertEquals('TE', $addressStub->country);

        # original
        $addressStub->setOriginalAttribute(' Test ');
        $this->assertEquals('TEST', $addressStub->original);

        # formatted
        $addressStub->setFormattedAttribute(' Test ');
        $this->assertEquals('TEST', $addressStub->formatted);

        # full
        $this->assertEquals($addressStub->getFullAttribute(), $addressStub->full());
        $this->assertEquals('TEST, TEST, TEST, TEST, TEST, TEST, TEST TEST, TEST, TE', $addressStub->full());
    }

}

class IncludesAddressTestStub extends Model
{
    use IncludesAddress;
}