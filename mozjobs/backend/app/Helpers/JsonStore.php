<?php
namespace App\Helpers;

class JsonStore {
  private string $dir;

  public function __construct(?string $dir = null) {
    $this->dir = $dir ?: __DIR__.'/../../storage/data';
    if (!is_dir($this->dir)) {
      mkdir($this->dir, 0777, true);
    }
  }

  public function all(string $collection): array {
    $path = $this->path($collection);
    if (!file_exists($path)) {
      return [];
    }

    $raw = file_get_contents($path) ?: '[]';
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
  }

  public function saveAll(string $collection, array $items): void {
    file_put_contents($this->path($collection), json_encode(array_values($items), JSON_PRETTY_PRINT));
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

  private function nextId(array $items): int {
    $ids = array_column($items, 'id');
    return ($ids ? max(array_map('intval', $ids)) : 0) + 1;
  }

  private function path(string $collection): string {
    return $this->dir.'/'.$collection.'.json';
  }
}
