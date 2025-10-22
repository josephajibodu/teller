# Installation Guide

## Laravel Integration

### 1. Install the Package

```bash
composer require josephajibodu/teller
```

### 2. Publish Configuration (Optional)

If you want to customize the configuration, you can publish the config file:

```bash
php artisan vendor:publish --tag="teller-config"
```

This will create `config/teller.php` in your Laravel project.

### 3. Environment Variables

Add your gateway credentials to your `.env` file:

```env
# Default Gateway
TELLER_DEFAULT_GATEWAY=paystack

# Paystack Configuration
PAYSTACK_SECRET_KEY=sk_test_...
PAYSTACK_PUBLIC_KEY=pk_test_...
PAYSTACK_WEBHOOK_SECRET=whsec_...

# Flutterwave Configuration
FLUTTERWAVE_SECRET_KEY=FLWSECK_TEST-...
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK_TEST-...
FLUTTERWAVE_WEBHOOK_SECRET=your_webhook_secret

# Proration Settings
TELLER_PRORATION_ENABLED=true
TELLER_PRORATION_ROUNDING=up
TELLER_PRORATION_CUTOFF_DAY=25
```

### 4. Service Provider Registration

Add the service provider to your `config/app.php`:

```php
'providers' => [
    // ... other providers
    JosephAjibodu\Teller\TellerServiceProvider::class,
],
```

Or if you're using Laravel 11+, the service provider will be auto-discovered.

### 5. Usage

```php
use JosephAjibodu\Teller\Teller;

// Initialize with default gateway
$billing = Teller::make();

// Create a plan
$plan = $billing->plans()->create([
    'name' => 'Pro Plan',
    'amount' => 25000, // 250 NGN in kobo
    'interval' => 'monthly',
]);

// Create a customer
$customer = $billing->customers()->create([
    'email' => 'user@example.com',
    'name' => 'John Doe',
]);

// Create a subscription
$subscription = $billing->subscriptions()->create($customer->id, [
    'plan_id' => $plan->id,
    'trial_days' => 7,
]);
```

## Standalone PHP Usage

### 1. Install the Package

```bash
composer require josephajibodu/teller
```

### 2. Configure

```php
use JosephAjibodu\Teller\Helpers\TellerConfig;

TellerConfig::set([
    'default_gateway' => 'paystack',
    'gateways' => [
        'paystack' => [
            'secret_key' => 'sk_test_...',
            'public_key' => 'pk_test_...',
        ],
        'flutterwave' => [
            'secret_key' => 'FLWSECK_TEST-...',
            'public_key' => 'FLWPUBK_TEST-...',
        ],
    ],
    'proration' => [
        'enabled' => true,
        'rounding' => 'up',
    ],
]);
```

### 3. Usage

```php
use JosephAjibodu\Teller\Teller;

$billing = Teller::make();

// Use the same API as Laravel integration
$plan = $billing->plans()->create([
    'name' => 'Pro Plan',
    'amount' => 25000,
    'interval' => 'monthly',
]);
```

## Webhook Setup

### Paystack Webhooks

1. Go to your Paystack dashboard
2. Navigate to Settings > Webhooks
3. Add your webhook URL: `https://yourdomain.com/billing/webhook`
4. Select the events you want to receive
5. Copy the webhook secret to your `.env` file

### Flutterwave Webhooks

1. Go to your Flutterwave dashboard
2. Navigate to Settings > Webhooks
3. Add your webhook URL: `https://yourdomain.com/billing/webhook`
4. Select the events you want to receive
5. Copy the webhook secret to your `.env` file

### Webhook Controller

Create a webhook controller to handle incoming events:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JosephAjibodu\Teller\Teller;

class BillingWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $event = Teller::webhooks()->handle($request->all());

        switch ($event->type) {
            case 'subscription.created':
                // Handle new subscription
                break;
            case 'subscription.renewed':
                // Handle subscription renewal
                break;
            case 'subscription.upgraded':
                // Handle subscription upgrade
                break;
            case 'subscription.cancelled':
                // Handle subscription cancellation
                break;
            case 'payment.failed':
                // Handle failed payment
                break;
        }

        return response()->json(['status' => 'success']);
    }
}
```

### Webhook Routes

Add the webhook route to your `routes/web.php`:

```php
Route::post('/billing/webhook', [BillingWebhookController::class, 'handle']);
```

## Testing

### Test Credentials

#### Paystack Test Credentials
- Use `sk_test_...` for secret keys
- Use `pk_test_...` for public keys
- Test cards: 4084084084084081 (success), 4084084084084085 (declined)

#### Flutterwave Test Credentials
- Use `FLWSECK_TEST-...` for secret keys
- Use `FLWPUBK_TEST-...` for public keys
- Test cards: 4187427415564246 (success), 4187427415564247 (declined)

### Testing Webhooks

You can use tools like ngrok to test webhooks locally:

```bash
# Install ngrok
npm install -g ngrok

# Expose your local server
ngrok http 8000

# Use the ngrok URL for webhook testing
# https://abc123.ngrok.io/billing/webhook
```

## Troubleshooting

### Common Issues

1. **Gateway not found**: Make sure you've set the correct gateway name in your configuration
2. **Authentication failed**: Verify your secret keys are correct
3. **Webhook verification failed**: Check that your webhook secrets match your gateway dashboard
4. **Proration not working**: Ensure proration is enabled in your configuration

### Debug Mode

Enable debug mode to see detailed error messages:

```php
TellerConfig::set([
    'debug' => true,
]);
```

### Logging

Teller will log all API requests and responses when debug mode is enabled. Check your Laravel logs for detailed information about any issues.
