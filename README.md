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
- Node.js 18+ and npm (for asset compilation)

## Installation

1. **Install the package:**
   ```bash
   composer require theihasan/dual-agent-ui
   ```

2. **Install Inertia.js Laravel adapter** (if not already installed):
   ```bash
   composer require inertiajs/inertia-laravel
   ```

3. **Run the package installer:**
   ```bash
   php artisan dual-agent-ui:install
   ```

   This installer will automatically:
   - Set up Inertia.js configuration
   - Publish Vue.js dashboard components
   - Configure Vite for Vue.js compilation
   - Add necessary middleware
   - Update your package.json with required dependencies

4. **Install JavaScript dependencies and build assets:**
   ```bash
   npm install
   npm run build
   ```

5. **Add the Inertia middleware** to your `bootstrap/app.php`:
   ```php
   ->withMiddleware(function (Middleware $middleware) {
       $middleware->web(append: [
           \App\Http\Middleware\HandleInertiaRequests::class,
       ]);
   })
   ```

## Configuration

The package uses minimal configuration. You can optionally publish the config file:

```bash
php artisan vendor:publish --tag="dual-agent-ui-config"
```

This is the contents of the published config file:

```php
return [
    // Configuration options will be added here as the package evolves
];
```

## Usage

Once installed, you can access the analytics dashboard at:

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

## Development

### Building Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### Customizing Styles

The dashboard uses Vue.js scoped styles. You can customize the appearance by modifying the `<style scoped>` sections in the published Vue components.

## Testing

```bash
composer test
```

The package includes comprehensive tests covering:
- Service provider functionality
- Route registration and accessibility
- Controller responses and data validation
- Inertia.js integration
- Installation process
- Vue.js component structure

## Common Issues

### Dashboard Shows Blank Page

Ensure that:
1. Inertia.js is properly installed: `composer require inertiajs/inertia-laravel`
2. Assets are built: `npm install && npm run build`
3. Inertia middleware is registered in `bootstrap/app.php`

### Assets Not Loading

Make sure your `vite.config.js` includes the Vue plugin:

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
});
```

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
