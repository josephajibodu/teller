# Teller - Stripe-like Billing for Africa

[![Latest Version on Packagist](https://img.shields.io/packagist/v/josephajibodu/teller.svg?style=flat-square)](https://packagist.org/packages/josephajibodu/teller)
[![Total Downloads](https://img.shields.io/packagist/dt/josephajibodu/teller.svg?style=flat-square)](https://packagist.org/packages/josephajibodu/teller)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

A clean, developer-friendly subscription billing abstraction layer for Laravel that brings Stripe-like subscription and proration features to Paystack and Flutterwave.

## 🚀 The Problem (and Opportunity)

Paystack and Flutterwave provide billing rails, not billing logic. That means:
- ❌ No prorations
- ❌ No upgrade/downgrade handling  
- ❌ No deferred upgrades
- ❌ No billing portal
- ❌ No unified "subscription experience" like Stripe's Billing

**Teller** solves this by providing a "Billing Logic Layer" on top of Paystack and Flutterwave - essentially "Stripe Billing for Africa."

## ✨ Features

### 🔹 Core Features
- 🧾 **Plan Management** - Abstract plan creation for both Paystack + Flutterwave
- 💰 **Proration Engine** - Handles fair billing when upgrading or downgrading mid-cycle
- ⏱ **Deferred Upgrades** - Schedule upgrades for next billing cycle
- 🔄 **Renewal Logic** - Sync local DB cycles with gateway events
- 💳 **Payment Gateway Abstraction** - Plug-and-play support for Paystack & Flutterwave
- 📦 **Webhook Handling** - Standardized events (subscription.renewed, payment.failed, etc.)
- 🔐 **Card Management** - Generate and send "update card" links via API
- 🧮 **Fair Billing Policies** - Configurable rules (cutoff day for proration, rounding, etc.)

### 🔹 Advanced Features (Coming Soon)
- 🪙 **Wallet / Credit Balance** - Automatically credit leftover time or unused value
- 🧠 **Smart Billing Policy Engine** - Define rules like "After 25 days, always defer upgrades"
- 🌍 **Multi-gateway sync** - Allow users to switch gateway without breaking subscriptions
- 📊 **Dashboard** - For merchants to monitor subscriptions, churn, renewals
- ⚙️ **API + SDK** - REST API and SDK (PHP/JS) for non-Laravel users

## 📦 Installation

```bash
composer require josephajibodu/teller
```

### Laravel Integration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="JosephAjibodu\Teller\TellerServiceProvider" --tag="teller-config"
```

Add your gateway credentials to your `.env` file:

```env
TELLER_DEFAULT_GATEWAY=paystack
PAYSTACK_SECRET_KEY=sk_test_...
PAYSTACK_PUBLIC_KEY=pk_test_...
PAYSTACK_WEBHOOK_SECRET=whsec_...

FLUTTERWAVE_SECRET_KEY=FLWSECK_TEST-...
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK_TEST-...
FLUTTERWAVE_WEBHOOK_SECRET=your_webhook_secret
```

## 🎯 Quick Start

### Basic Setup

```php
use JosephAjibodu\Teller\Teller;

// Initialize with default gateway
$billing = Teller::make();

// Or specify a gateway
$billing = Teller::gateway('flutterwave');

// Or use the fluent API
$billing = Teller::for($user)->onGateway('paystack');
```

### Plan Management

```php
// Create a plan
$plan = $billing->plans()->create([
    'name' => 'Pro Plan',
    'amount' => 25000, // 250 NGN in kobo
    'interval' => 'monthly',
    'description' => 'Access to premium features',
]);

// Get all plans
$plans = $billing->plans()->all();

// Update a plan
$billing->plans()->update('pro-plan-id', [
    'amount' => 30000,
]);
```

### Customer Management

```php
// Create a customer
$customer = $billing->customers()->create([
    'email' => 'joseph@example.com',
    'name' => 'Joseph Ajibodu',
    'metadata' => ['team_id' => 4],
]);

// Update customer's card
$billing->customers()->updateCard('customer-id', 'new-card-token');
```

### Subscription Lifecycle

```php
// Create a subscription
$subscription = $billing->subscriptions()->create('customer-id', [
    'plan_id' => 'pro-monthly',
    'trial_days' => 7,
]);

// Cancel a subscription
$billing->subscriptions()->cancel('subscription-id');

// Resume a subscription (if within grace period)
$billing->subscriptions()->resume('subscription-id');
```

### 🔥 Upgrade/Downgrade with Proration

This is Teller's core differentiator:

```php
// Upgrade with proration (charge immediately)
$billing->subscriptions()->upgrade('subscription-id', [
    'to_plan' => 'business-monthly',
    'prorate' => true,
    'immediate' => true,
]);

// Schedule upgrade for next billing cycle
$billing->subscriptions()->upgrade('subscription-id', [
    'to_plan' => 'business-monthly',
    'prorate' => false,
    'effective' => 'next_cycle',
]);

// Downgrade with proration
$billing->subscriptions()->downgrade('subscription-id', [
    'to_plan' => 'basic-monthly',
    'prorate' => true,
]);
```

### Invoice Management

```php
// Create an invoice
$invoice = $billing->invoices()->create([
    'customer_id' => 'customer-id',
    'amount' => 5000, // 50 NGN in kobo
    'description' => 'Extra storage space',
]);

// List invoices for a customer
$invoices = $billing->invoices()->all(['customer_id' => 'customer-id']);
```

### Webhook Handling

```php
// In your Laravel controller
Route::post('/billing/webhook', [BillingWebhookController::class, 'handle']);

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
    }
}
```

### Fluent API

```php
// Chain operations for better readability
Teller::for($user)
    ->onGateway('paystack')
    ->subscriptions()
    ->upgrade('subscription-id', [
        'to_plan' => 'premium-monthly',
        'prorate' => true,
    ]);
```

## 🛠 Money Helper

```php
use JosephAjibodu\Teller\Support\Money;

// Create money objects
$amount = Money::fromNaira(250.00); // 250 NGN
$amount = Money::fromKobo(25000); // 250 NGN in kobo

// Perform calculations
$total = $amount->add(Money::fromNaira(50.00)); // 300 NGN
$discount = $amount->multiply(0.1); // 10% discount
$formatted = $amount->format(); // "250.00 NGN"
```

## 📅 Date Helper

```php
use JosephAjibodu\Teller\Support\DateHelper;

$date = new \DateTime();

// Get billing information
$daysInMonth = DateHelper::daysInMonth($date);
$daysRemaining = DateHelper::daysRemainingInMonth($date);
$nextBilling = DateHelper::nextBillingDate($date, 'monthly');
```

## ⚙️ Configuration

```php
// config/teller.php
return [
    'default_gateway' => env('TELLER_DEFAULT_GATEWAY', 'paystack'),
    
    'gateways' => [
        'paystack' => [
            'secret_key' => env('PAYSTACK_SECRET_KEY'),
            'public_key' => env('PAYSTACK_PUBLIC_KEY'),
            'webhook_secret' => env('PAYSTACK_WEBHOOK_SECRET'),
        ],
        'flutterwave' => [
            'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
            'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
            'webhook_secret' => env('FLUTTERWAVE_WEBHOOK_SECRET'),
        ],
    ],
    
    'proration' => [
        'enabled' => env('TELLER_PRORATION_ENABLED', true),
        'rounding' => env('TELLER_PRORATION_ROUNDING', 'up'),
        'cutoff_day' => env('TELLER_PRORATION_CUTOFF_DAY', 25),
    ],
];
```

## 🧪 Testing

```bash
composer test
```

## 🤝 Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## 🙏 Acknowledgments

- Inspired by Laravel Cashier
- Built for the African developer community
- Special thanks to Paystack and Flutterwave for their excellent APIs

## 📞 Support

- 📧 Email: josephajibodu@gmail.com
- 🐛 Issues: [GitHub Issues](https://github.com/josephajibodu/teller/issues)
- 📖 Documentation: [GitHub Wiki](https://github.com/josephajibodu/teller/wiki)

---

**Made with ❤️ for African developers**
