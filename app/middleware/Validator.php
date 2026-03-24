<?php
// ── Input Sanitizer / Validator ────────────────────────────────────────────────
class Validator {
    private array $errors = [];
    private array $data   = [];

    public function __construct(array $input) {
        // Sanitize all string inputs by default
        foreach ($input as $k => $v) {
            $this->data[$k] = is_string($v) ? trim(htmlspecialchars($v, ENT_QUOTES, 'UTF-8')) : $v;
        }
    }

    public function required(string $field, string $label = ''): static {
        if (empty($this->data[$field])) {
            $this->errors[$field] = ($label ?: ucfirst($field)) . ' is required.';
        }
        return $this;
    }

    public function email(string $field): static {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = 'Invalid email address.';
        }
        return $this;
    }

    public function min(string $field, int $length): static {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field] = ucfirst($field) . " must be at least $length characters.";
        }
        return $this;
    }

    public function max(string $field, int $length): static {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field] = ucfirst($field) . " must not exceed $length characters.";
        }
        return $this;
    }

    public function numeric(string $field): static {
        if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = ucfirst($field) . ' must be a number.';
        }
        return $this;
    }

    public function fails(): bool  { return !empty($this->errors); }
    public function passes(): bool { return empty($this->errors); }
    public function errors(): array { return $this->errors; }
    public function get(string $field, mixed $default = null): mixed {
        return $this->data[$field] ?? $default;
    }
    public function all(): array { return $this->data; }

    /** Raw (unescaped) value — use only for passwords */
    public function raw(string $field, mixed $default = null): mixed {
        return $_POST[$field] ?? $default;
    }
}
