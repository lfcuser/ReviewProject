<?php

namespace Tests\Unit\Rulests;

use App\Rulesets\CoordinateRuleset;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CoordinateRulesetTest extends TestCase
{
    private array $ruleset;

    protected function setUp(): void
    {
        parent::setUp();

        Validator::resolver(function ($translator, $data, $rules, $messages, $attributes) {
            return new class($translator, $data, $rules, $messages, $attributes) extends \Illuminate\Validation\Validator {
                public function validateExists($attribute, $value, $parameters)
                {
                    return true;
                }
            };
        });

        $this->ruleset = app()->make(CoordinateRuleset::class)->getRuleset();
    }

    private function validate(array $data): bool
    {
        return Validator::make($data, $this->ruleset)->passes();
    }

    public function testValidData()
    {
        $this->assertTrue($this->validate([
            'id' => 1,
            'place_name' => 'TestPlace',
            'lat' => 45.123456,
            'lon' => -73.654321,
        ]));
    }

    public function testMissingId()
    {
        $this->assertFalse($this->validate([
            'place_name' => 'TestPlace',
            'lat' => 45.123456,
            'lon' => -73.654321,
        ]));
    }

    public function testMissingPlaceName()
    {
        $this->assertFalse($this->validate([
            'id' => 1,
            'lat' => 45.123456,
            'lon' => -73.654321,
        ]));
    }

    public function testInvalidPlaceName()
    {
        $this->assertFalse($this->validate([
            'id' => 1,
            'place_name' => 'Test',
            'lat' => 45.123456,
            'lon' => -73.654321,
        ]));
    }

    public function testInvalidLatOutOfRange()
    {
        $this->assertFalse($this->validate([
            'id' => 1,
            'place_name' => 'TestPlace',
            'lat' => 91.0,
            'lon' => -73.654321,
        ]));
    }

    public function testInvalidLonOutOfRange()
    {
        $this->assertFalse($this->validate([
            'id' => 1,
            'place_name' => 'TestPlace',
            'lat' => 45.123456,
            'lon' => -181.0,
        ]));
    }

    public function testLatWithTooFewDecimals()
    {
        $this->assertFalse($this->validate([
            'id' => 1,
            'place_name' => 'TestPlace',
            'lat' => 45.1234567,
            'lon' => -73.654321,
        ]));
    }

    public function testLonWithTooFewDecimals()
    {
        $this->assertFalse($this->validate([
            'id' => 1,
            'place_name' => 'TestPlace',
            'lat' => 45.123456,
            'lon' => -73.6123456,
        ]));
    }

    public function testIdAsString()
    {
        $this->assertFalse($this->validate([
            'id' => 'abc',
            'place_name' => 'TestPlace',
            'lat' => 45.123456,
            'lon' => -73.654321,
        ]));
    }

    public function testIdAsFloat()
    {
        $this->assertFalse($this->validate([
            'id' => 1.5,
            'place_name' => 'TestPlace',
            'lat' => 45.123456,
            'lon' => -73.654321,
        ]));
    }

    public function testIdAsNull()
    {
        $this->assertFalse($this->validate([
            'id' => null,
            'place_name' => 'TestPlace',
            'lat' => 45.123456,
            'lon' => -73.654321,
        ]));
    }
}
