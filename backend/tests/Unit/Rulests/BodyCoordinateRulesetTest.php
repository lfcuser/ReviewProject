<?php

namespace Tests\Unit\Rulests;

use App\Rulesets\BodyCoordinateRuleset;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class BodyCoordinateRulesetTest extends TestCase
{
    private array $ruleset;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ruleset = app()->make(BodyCoordinateRuleset::class)->getRuleset();
    }

    private function validate(array $data): bool
    {
        return Validator::make($data, $this->ruleset)->passes();
    }

    public function testValidData()
    {
        $this->assertTrue($this->validate([
            'place_name' => 'TestPlace',
            'lat' => 45.123456,
            'lon' => -73.654321,
        ]));

        $this->assertTrue($this->validate([
            'place_name' => 'TestPlace',
            'lat' => 45.1,
            'lon' => -73.6,
        ]));
    }

    public function testPlaceNameMissing()
    {
        $this->assertFalse($this->validate([
            'lat' => 45.123456,
            'lon' => -73.654321,
        ]));
    }

    public function testLatMissing()
    {
        $this->assertFalse($this->validate([
            'place_name' => 'TestPlace',
            'lon' => -73.654321,
        ]));
    }

    public function testLonMissing()
    {
        $this->assertFalse($this->validate([
            'place_name' => 'TestPlace',
            'lat' => 45.123456,
        ]));
    }

    public function testPlaceNameInvalidCharacters()
    {
        $this->assertFalse($this->validate([
            'place_name' => 'Test123',
            'lat' => 45.123456,
            'lon' => -73.654321,
        ]));
    }

    public function testPlaceNameTooShort()
    {
        $this->assertFalse($this->validate([
            'place_name' => '',
            'lat' => 45.123456,
            'lon' => -73.654321,
        ]));
    }

    public function testPlaceNameTooLong()
    {
        $this->assertFalse($this->validate([
            'place_name' => str_repeat('a', 257),
            'lat' => 45.123456,
            'lon' => -73.654321,
        ]));
    }

    public function testLatTooLow()
    {
        $this->assertFalse($this->validate([
            'place_name' => 'TestPlace',
            'lat' => -90.000001,
            'lon' => -73.654321,
        ]));
    }

    public function testLatTooHigh()
    {
        $this->assertFalse($this->validate([
            'place_name' => 'TestPlace',
            'lat' => 90.000001,
            'lon' => -73.654321,
        ]));
    }

    public function testLonTooLow()
    {
        $this->assertFalse($this->validate([
            'place_name' => 'TestPlace',
            'lat' => 45.123456,
            'lon' => -180.000001,
        ]));
    }

    public function testLonTooHigh()
    {
        $this->assertFalse($this->validate([
            'place_name' => 'TestPlace',
            'lat' => 45.123456,
            'lon' => 180.000001,
        ]));
    }

    public function testLatExceedsPrecision()
    {
        $this->assertFalse($this->validate([
            'place_name' => 'TestPlace',
            'lat' => 45.1234567,
            'lon' => -73.654321,
        ]));
    }

    public function testLonExceedsPrecision()
    {
        $this->assertFalse($this->validate([
            'place_name' => 'TestPlace',
            'lat' => 45.123456,
            'lon' => -73.6543217,
        ]));
    }
}
