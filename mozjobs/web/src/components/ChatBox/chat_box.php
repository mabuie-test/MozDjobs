<?php
function renderChatBox(array $messages): string {
  if (!$messages) return '<p class="muted">Sem mensagens.</p>';
  $html = '';
  foreach ($messages as $m) {
    $sender = (int)($m['sender_id'] ?? 0);
    $msg = htmlspecialchars((string)($m['message'] ?? ''));
    $html .= "<p><strong>#{$sender}:</strong> {$msg}</p>";
  }
  return $html;
}
