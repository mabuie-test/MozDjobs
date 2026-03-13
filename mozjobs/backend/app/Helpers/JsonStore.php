<?php
namespace App\Helpers;

class JsonStore {
  private string $dir;
  private string $key;

  public function __construct(?string $dir = null) {
    $this->dir = $dir ?: __DIR__.'/../../storage/data';
    if (!is_dir($this->dir)) {
      mkdir($this->dir, 0777, true);
    }

    $rawKey = getenv('APP_ENCRYPTION_KEY') ?: getenv('APP_KEY') ?: 'mozjobs-dev-encryption-key';
    $this->key = hash('sha256', $rawKey, true);
  }

  public function all(string $collection): array {
    $path = $this->path($collection);
    if (!file_exists($path)) {
      return [];
    }

    $raw = file_get_contents($path) ?: '[]';
    $decoded = json_decode($raw, true);
    if (!is_array($decoded)) {
      return [];
    }

    if (($decoded['__enc_v'] ?? 0) === 1) {
      return $this->decryptPayload($decoded);
    }

    return array_is_list($decoded) ? $decoded : [];
  }

  public function saveAll(string $collection, array $items): void {
    $payload = $this->encryptPayload(array_values($items));
    file_put_contents($this->path($collection), json_encode($payload, JSON_PRETTY_PRINT));
  }

  public function create(string $collection, array $item): array {
    $items = $this->all($collection);
    $item['id'] = $this->nextId($items);
    $item['created_at'] = date('c');
    $items[] = $item;
    $this->saveAll($collection, $items);
    return $item;
  }

  public function update(string $collection, int $id, callable $callback): ?array {
    $items = $this->all($collection);
    foreach ($items as $index => $item) {
      if ((int) ($item['id'] ?? 0) === $id) {
        $items[$index] = $callback($item);
        $this->saveAll($collection, $items);
        return $items[$index];
      }
    }

    return null;
  }

  public function findBy(string $collection, string $field, mixed $value): ?array {
    foreach ($this->all($collection) as $item) {
      if (($item[$field] ?? null) === $value) {
        return $item;
      }
    }
    return null;
  }

  private function encryptPayload(array $items): array {
    $json = json_encode($items);
    if (!is_string($json)) {
      return ['__enc_v' => 0, 'data' => []];
    }

    $iv = random_bytes(12);
    $tag = '';
    $ciphertext = openssl_encrypt($json, 'aes-256-gcm', $this->key, OPENSSL_RAW_DATA, $iv, $tag);
    if (!is_string($ciphertext)) {
      return ['__enc_v' => 0, 'data' => []];
    }

    return [
      '__enc_v' => 1,
      'algo' => 'aes-256-gcm',
      'iv' => base64_encode($iv),
      'tag' => base64_encode($tag),
      'ciphertext' => base64_encode($ciphertext),
    ];
  }

  private function decryptPayload(array $payload): array {
    $iv = base64_decode((string)($payload['iv'] ?? ''), true);
    $tag = base64_decode((string)($payload['tag'] ?? ''), true);
    $ciphertext = base64_decode((string)($payload['ciphertext'] ?? ''), true);

    if (!is_string($iv) || !is_string($tag) || !is_string($ciphertext)) {
      return [];
    }

    $plain = openssl_decrypt($ciphertext, 'aes-256-gcm', $this->key, OPENSSL_RAW_DATA, $iv, $tag);
    if (!is_string($plain)) {
      return [];
    }

    $decoded = json_decode($plain, true);
    return is_array($decoded) && array_is_list($decoded) ? $decoded : [];
  }

  private function nextId(array $items): int {
    $ids = array_column($items, 'id');
    return ($ids ? max(array_map('intval', $ids)) : 0) + 1;
  }

  private function path(string $collection): string {
    return $this->dir.'/'.$collection.'.json';
  }
}
