<?php
use PHPUnit\Framework\TestCase;

// Minimal boot for tests
require_once __DIR__ . '/../../app/middleware/Validator.php';

class ValidatorTest extends TestCase {

    public function testRequiredRule() {
        $v = new Validator(['name' => '', 'age' => '25']);
        $v->required('name')->required('age');
        
        $this->assertTrue($v->fails());
        $this->assertArrayHasKey('name', $v->errors());
        $this->assertArrayNotHasKey('age', $v->errors());
    }

    public function testEmailRule() {
        $v = new Validator(['email' => 'invalid-email', 'valid' => 'test@example.com']);
        $v->email('email')->email('valid');
        
        $this->assertTrue($v->fails());
        $this->assertArrayHasKey('email', $v->errors());
        $this->assertArrayNotHasKey('valid', $v->errors());
    }

    public function testNumericRule() {
        $v = new Validator(['price' => 'abc', 'qty' => '10']);
        $v->numeric('price')->numeric('qty');
        
        $this->assertTrue($v->fails());
        $this->assertArrayHasKey('price', $v->errors());
        $this->assertArrayNotHasKey('qty', $v->errors());
    }

    public function testMinLengthRule() {
        $v = new Validator(['pass' => '123']);
        $v->minlength('pass', 6);
        
        $this->assertTrue($v->fails());
        $this->assertStringContainsString('at least 6 characters', $v->errors()['pass']);
    }

    public function testMatchesRule() {
        $v = new Validator(['p1' => '123', 'p2' => '456']);
        $v->matches('p1', 'p2', 'Passwords');
        
        $this->assertTrue($v->fails());
    }

    public function testSanitization() {
        $v = new Validator(['comment' => ' <script>alert(1)</script> ']);
        // Constructor already trims and escapes (depending on implementation - checking current)
        $val = $v->get('comment');
        $this->assertEquals('&lt;script&gt;alert(1)&lt;/script&gt;', $val);
    }
}
