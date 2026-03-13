<?php
function renderJobCard(array $job): string {
  $title = htmlspecialchars((string)($job['title'] ?? 'Vaga'));
  $desc = htmlspecialchars((string)($job['description'] ?? ''));
  $id = (int)($job['id'] ?? 0);
  return "<article class='card'><h3>{$title}</h3><p class='muted'>{$desc}</p><a class='btn secondary' href='/jobs/job-detail.php?id={$id}'>Detalhes</a></article>";
}
