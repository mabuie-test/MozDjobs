<?php
namespace App\Helpers;
class Validator {
  public static function requireFields(array $data, array $fields): array {
    $missing = [];
    foreach ($fields as $field) if (!isset($data[$field]) || $data[$field] === '') $missing[] = $field;
    return $missing;
  }
}
