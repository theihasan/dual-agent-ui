# Dual Agent UI for Laravel Nightwatch

[![Latest Version on Packagist](https://img.shields.io/packagist/v/theihasan/dual-agent-ui.svg?style=flat-square)](https://packagist.org/packages/theihasan/dual-agent-ui)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/theihasan/dual-agent-ui/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/theihasan/dual-agent-ui/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/theihasan/dual-agent-ui.svg?style=flat-square)](https://packagist.org/packages/theihasan/dual-agent-ui)
[![License](https://img.shields.io/packagist/l/theihasan/dual-agent-ui.svg?style=flat-square)](https://packagist.org/packages/theihasan/dual-agent-ui)

A beautiful, Vue.js-powered analytics dashboard for monitoring your [Dual Agent](https://github.com/theihasan/dual-agent) metrics. Provides real-time insights into your Laravel application's performance with an intuitive, modern interface built with Inertia.js.

## Requirements

- PHP 8.2+
- Laravel 11.0+
- [Laravel Nightwatch](https://nightwatch.laravel.com) installed and configured
- [Dual Agent](https://github.com/theihasan/dual-agent) package installed

## Installation

1. **Install the package:**
   ```bash
   composer require theihasan/dual-agent-ui
   ```

2. **Run the installer:**
   ```bash
   php artisan dual-agent-ui:install
   ```

3. **Access the dashboard:**
   ```
   https://your-app.com/agent-dashboard
   ```

**That's it!** No npm installation or asset compilation required. The package comes with pre-built Vue.js assets and is completely self-contained.

## Features

- ✅ **Zero Configuration**: Works out of the box without any npm setup
- ✅ **Self-Contained**: All Vue/Inertia assets are pre-built and included
- ✅ **Modern UI**: Beautiful, responsive dashboard built with Vue 3 and Inertia.js
- ✅ **Real-time Metrics**: Live monitoring of your application performance
- ✅ **Secure**: Built-in authentication and authorization
- ✅ **Configurable**: Customize paths, middleware, and access control

## Configuration

After installation, you can customize the package by editing the published config file:

```bash
php artisan vendor:publish --tag=dual-agent-ui-config
```

### Available Configuration Options

```php
// config/dual-agent-ui.php

return [
    // Dashboard access path
    'path' => env('DUAL_AGENT_UI_PATH', 'agent-dashboard'),
    
    // Middleware applied to routes
    'middleware' => ['web', 'auth'],
    
    // Authorization gate (optional)
    'gate' => env('DUAL_AGENT_UI_GATE'),
];
```

## Usage

Access the analytics dashboard at:

```
https://your-app.com/agent-dashboard
```

### Dashboard Features

The dashboard provides:

- **Real-time Metrics**: Live view of your application performance
- **Request Analytics**: Total requests, average response times
- **Error Monitoring**: Error rates and exception tracking  
- **System Health**: Uptime statistics and system status
- **Recent Activity**: Timeline of recent system events
- **Interactive Charts**: Visual representation of your metrics

### Customizing the Dashboard

The dashboard Vue.js components are published to your `resources/js/Pages/` directory, allowing you to customize them as needed:

- `resources/js/Pages/Dashboard.vue` - Main dashboard component
- `resources/views/app.blade.php` - Inertia root template

### Flash Messages

The package automatically configures flash message handling for user feedback:

```php
// In your controllers
return redirect()->back()->with('success', 'Operation completed successfully!');
return redirect()->back()->with('error', 'Something went wrong!');
return redirect()->back()->with('warning', 'Please review your input.');
return redirect()->back()->with('info', 'Here\'s some helpful information.');
```

These messages will automatically appear in your dashboard interface.

## Authorization

By default, the dashboard is accessible to all authenticated users. You can customize access control by defining a gate in your `AppServiceProvider`:

```php
// app/Providers/AppServiceProvider.php

use Illuminate\Support\Facades\Gate;

public function boot()
{
    Gate::define('viewDualAgentUI', function ($user) {
        return $user->isAdmin(); // Customize this logic
    });
}
```

Then update your config:

```php
// config/dual-agent-ui.php
'gate' => 'viewDualAgentUI',
```

## Package Development

This package uses a self-contained architecture. For package developers:

### Building Assets (Package Development Only)

```bash
# Install dependencies
npm install

# Build for production
npm run build
```

Built assets are committed to the repository so end users don't need npm.

## Testing

```bash
composer test
```

## Common Issues

### Dashboard Shows 404

Ensure the package is properly installed:
```bash
php artisan dual-agent-ui:install
```

### Access Denied

Check your middleware configuration in `config/dual-agent-ui.php`. By default, the `auth` middleware is applied.

### Installation Fails

Make sure you have the required dependencies:
1. Laravel Nightwatch: `composer require laravel/nightwatch`
2. Dual Agent: `composer require theihasan/dual-agent`

## Screenshots

*Dashboard screenshots will be added here*

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email theihasan@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- [Ihasan](https://github.com/theihasan)
- Built for [Laravel Nightwatch](https://nightwatch.laravel.com)
- Powered by [Inertia.js](https://inertiajs.com) and [Vue.js](https://vuejs.org)
- Companion package to [Dual Agent](https://github.com/theihasan/dual-agent)
