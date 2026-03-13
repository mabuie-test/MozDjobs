<?php
$required = [
  __DIR__.'/../../database/migrations/create_users.sql',
  __DIR__.'/../../database/migrations/create_profiles.sql',
  __DIR__.'/../../database/migrations/create_jobs.sql',
  __DIR__.'/../../database/migrations/create_services.sql',
  __DIR__.'/../../database/migrations/create_orders.sql',
  __DIR__.'/../../database/migrations/create_payments.sql',
  __DIR__.'/../../database/migrations/create_reviews.sql',
  __DIR__.'/../../database/migrations/create_chats.sql',
  __DIR__.'/../../database/migrations/create_applications.sql',
  __DIR__.'/../../database/migrations/create_disputes.sql',
  __DIR__.'/../../database/migrations/create_feed_posts.sql',
  __DIR__.'/../../database/migrations/create_feed_reactions.sql',
  __DIR__.'/../../database/migrations/create_feed_comments.sql',
  __DIR__.'/../../database/migrations/create_stories.sql',
  __DIR__.'/../../database/migrations/create_follows.sql',
];
foreach ($required as $file) {
  if (!file_exists($file)) {
    echo "missing migration: {$file}\n";
    exit(1);
  }
}
echo "migrations presence test ok\n";
