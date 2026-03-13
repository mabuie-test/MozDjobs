export const EVENTS = [
  'user_registered',
  'job_posted',
  'service_published',
  'proposal_submitted',
  'order_created',
  'payment_escrow_created',
  'payment_released',
  'review_submitted',
  'dispute_opened',
  'dispute_resolved',
  'favorite_added',
  'notification_created'
];

export function trackEvent(eventName, payload = {}) {
  if (!EVENTS.includes(eventName)) {
    console.warn(`[analytics] unknown event: ${eventName}`);
    return;
  }

  console.log('[analytics]', {
    eventName,
    payload,
    timestamp: new Date().toISOString(),
  });
}
