# Teller Examples

This directory contains runnable examples that demonstrate how to use the Teller package with Paystack and Flutterwave.

## 🚀 Quick Start

```bash
# Run all examples
php run-all-examples.php

# Run individual examples
php plan-management.php
php customer-management.php
php subscription-management.php
php invoice-management.php
php money-helper.php
php date-helper.php
php fluent-api.php
```

## 📋 Available Examples

### Core Features
- **`plan-management.php`** - Create, read, update, and delete billing plans
- **`customer-management.php`** - Manage customer records and card information
- **`subscription-management.php`** - Handle subscription lifecycle (create, pause, resume, upgrade, cancel)
- **`invoice-management.php`** - Create and manage invoices with line items

### Helper Classes
- **`money-helper.php`** - Money calculations, currency formatting, and arithmetic operations
- **`date-helper.php`** - Date calculations for billing cycles, proration, and billing dates

### API Design
- **`fluent-api.php`** - Demonstrate the fluent/chaining API syntax

## ⚙️ Configuration

Before running the examples, make sure to:

1. **Set your Paystack secret key** in each example file:
   ```php
   'secret_key' => 'sk_test_your_secret_key_here',
   ```

2. **Ensure you have an active internet connection**

3. **Verify your Paystack account is active**

## 🎯 What Each Example Shows

Each example demonstrates:
- ✅ **Real API calls** to Paystack
- ✅ **Complete CRUD operations** for each resource
- ✅ **Error handling** and debugging tips
- ✅ **Cleanup** to avoid leaving test data
- ✅ **Step-by-step progress** with clear output

## 🔧 Customization

You can modify any example to:
- Use your own test data
- Test different scenarios
- Experiment with the API
- Learn how to integrate Teller into your application

## 📚 Learning Path

1. Start with **`plan-management.php`** to understand basic operations
2. Try **`customer-management.php`** for customer operations
3. Explore **`subscription-management.php`** for subscription lifecycle
4. Check **`invoice-management.php`** for invoice handling
5. Use **`money-helper.php`** and **`date-helper.php`** for calculations
6. See **`fluent-api.php`** for advanced API usage

## 🚨 Important Notes

- Examples use **test mode** - no real money is charged
- Examples **clean up after themselves** - no leftover test data
- Examples are **safe to run multiple times**
- Examples show **real API responses** from Paystack

## 🆘 Troubleshooting

If an example fails:
1. Check your Paystack secret key
2. Ensure you have an active internet connection
3. Verify your Paystack account is active
4. Check the error messages for specific issues

Happy coding! 🎉
