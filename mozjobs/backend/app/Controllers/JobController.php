<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class JobController {
  public function index(array $query = []): array {
    $jobs = (new JsonStore())->all('jobs');

    if (!empty($query['q'])) {
      $q = mb_strtolower((string) $query['q']);
      $jobs = array_values(array_filter($jobs, function ($job) use ($q) {
        return str_contains(mb_strtolower((string) ($job['title'] ?? '')), $q)
          || str_contains(mb_strtolower((string) ($job['description'] ?? '')), $q)
          || str_contains(mb_strtolower((string) ($job['location'] ?? '')), $q);
      }));
    }

    if (isset($query['approved'])) {
      $approved = filter_var($query['approved'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
      if ($approved !== null) {
        $jobs = array_values(array_filter($jobs, fn($job) => (bool) ($job['approved'] ?? false) === $approved));
      }
    }

    return ['resource' => 'Job', 'items' => $jobs];
  }

  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['company_id', 'title', 'description']);
    if ($missing) {
      return ['error' => 'missing: '.implode(',', $missing)];
    }

    $data['approved'] = false;
    $data['status'] = 'open';
    return ['resource' => 'Job', 'saved' => (new JsonStore())->create('jobs', $data)];
  }

  public function apply(array $data): array {
    $missing = Validator::requireFields($data, ['job_id', 'professional_id', 'cover_letter']);
    if ($missing) {
      return ['error' => 'missing: '.implode(',', $missing)];
    }

    $application = (new JsonStore())->create('applications', [
      'job_id' => (int) $data['job_id'],
      'professional_id' => (int) $data['professional_id'],
      'cover_letter' => trim((string) $data['cover_letter']),
      'status' => 'submitted'
    ]);

    return ['resource' => 'Application', 'saved' => $application];
  }
}
