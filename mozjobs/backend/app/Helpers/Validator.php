<?php
namespace App\Helpers;

class Validator {
  public static function requireFields(array $data, array $fields): array {
    $missing = [];
    foreach ($fields as $field) {
      if (!array_key_exists($field, $data) || $data[$field] === '' || $data[$field] === null) {
        $missing[] = $field;
      }
    }
    return $missing;
  }

  public static function email(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
  }

  public static function minLength(string $value, int $size): bool {
    return mb_strlen($value) >= $size;
  }
}
