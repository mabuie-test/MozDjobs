<?php
function renderNavbar(string $active = ''): void {
  $links = [
    '/index.php' => 'Home',
    '/jobs/index.php' => 'Vagas',
    '/services/index.php' => 'Serviços',
    '/chat.php' => 'Chat',
    '/admin.php' => 'Admin',
  ];
  echo '<header class="header"><div class="wrap"><div class="brand">MozJobs</div><nav class="nav">';
  foreach ($links as $url => $label) {
    $class = $active === $url ? ' style="font-weight:700;text-decoration:underline"' : '';
    echo "<a href=\"{$url}\"{$class}>{$label}</a>";
  }
  echo '</nav></div></header>';
}
