<?php

require_once('../validators/validator.php');

use depage\htmlform\validators\validator;

class validatorTestClass extends validator {
    // needed for testLog() ($this->log() is protected)
    public function log($argument, $type) {
        parent::log ($argument, $type);
    }
}

class validatorTest extends \PHPUnit_Framework_TestCase {
    public function testText() {
        $textValidator = validator::factory('text');
        $this->assertEquals(true, $textValidator->validate('anyString'));
    }

    public function testEmail() {
        $emailValidator = validator::factory('email');
        $this->assertEquals(false, $emailValidator->validate('anyString'));
        $this->assertEquals(true, $emailValidator->validate('test@depage.net'));
    }

   public function testUrl() {
        $urlValidator = validator::factory('url');
        $this->assertEquals(false, $urlValidator->validate('anyString'));
        $this->assertEquals(true, $urlValidator->validate('http://www.depage.net'));
    }

    public function testCustomRegEx() {
        $customValidator = validator::factory('/[a-zA-Z]/');
        $this->assertEquals(false, $customValidator->validate('1234'));
        $this->assertEquals(true, $customValidator->validate('letters'));
    }

    public function testNumber() {
        $numberValidator = validator::factory('number');
        $this->assertEquals(false,  $numberValidator->validate('letters',   array('min' => null,    'max' => null)));
        $this->assertEquals(false,  $numberValidator->validate(-10,         array('min' => 0,       'max' => 10)));
        $this->assertEquals(true,   $numberValidator->validate(5,           array('min' => 0,       'max' => 10)));
        $this->assertEquals(true,   $numberValidator->validate(5,           array('min' => null,    'max' => null)));
    }

    public function testGetPatternAttribute() {
        $regExValidator = validator::factory('/[a-z]/');
        $this->assertEquals(' pattern="[a-z]"', $regExValidator->getPatternAttribute());

        $telValidator = validator::factory('tel');
        $this->assertEquals('', $telValidator->getPatternAttribute());

        $anyValidator = validator::factory('foo');
        $this->assertEquals('', $anyValidator->getPatternAttribute());
    }

    public function testLog() {
        $log        = new logTestClass;
        $validator  = new validatorTestClass($log);

        $validator->log('argumentString', 'typeString');

        $expected = array(
            'argument'  => 'argumentString',
            'type'      => 'typeString',
        );

        $this->assertEquals($expected, $log->error);
    }
}
