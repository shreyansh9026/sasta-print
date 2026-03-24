<?php
// ── Custom Exceptions ──────────────────────────────────────────────────────────

class ValidationException extends RuntimeException {
    private array $errors;
    public function __construct(array $errors, string $message = 'Validation failed', int $code = 422) {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }
    public function getErrors(): array { return $this->errors; }
}

class AuthException extends RuntimeException {
    public function __construct(string $msg = 'Unauthorised', int $code = 401) {
        parent::__construct($msg, $code);
    }
}

class NotFoundException extends RuntimeException {
    public function __construct(string $msg = 'Resource not found', int $code = 404) {
        parent::__construct($msg, $code);
    }
}

class PaymentException extends RuntimeException {
    public function __construct(string $msg = 'Payment failed', int $code = 402) {
        parent::__construct($msg, $code);
    }
}
