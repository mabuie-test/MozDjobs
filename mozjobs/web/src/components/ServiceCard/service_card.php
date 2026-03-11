<?php
function renderServiceCard(array $service): string {
  $title = htmlspecialchars((string)($service['title'] ?? 'Serviço'));
  $desc = htmlspecialchars((string)($service['description'] ?? ''));
  $price = (float)($service['price'] ?? 0);
  return "<article class='card'><h3>{$title}</h3><p class='muted'>{$desc}</p><p><strong>{$price} MZN</strong></p></article>";
}
