
# PwaAssetsGenerator

**PwaAssetsGenerator** is a package for generating **Progressive Web App (PWA)** assets such as **app icons**, **splash screens**, **favicons**, and **manifests**. It provides easy-to-use functions to automate the creation of essential assets for a PWA, making it simple to integrate them into your Laravel or PHP project.

## Installation

To install **PwaAssetsGenerator**, you can require the package via Composer.

1. Add the package to your `composer.json`:

    ```bash
    composer require manhamprod/pwaassetsgenerator
    ```

2. After installation, the service provider will be automatically registered if you are using Laravel. If not, register the **`PwaAssetsGeneratorServiceProvider`** in your **`config/app.php`** file under the `providers` array:

    ```php
    'providers' => [
        // Other providers...
        Manhamprod\PwaAssetsGenerator\PwaAssetsGeneratorServiceProvider::class,
    ],
    ```

3. Add the alias for the **`PwaAssetsGenerator`** facade to the **`aliases`** array:

    ```php
    'aliases' => [
        // Other aliases...
        'PwaAssetsGenerator' => Manhamprod\PwaAssetsGenerator\PwaAssetsGeneratorFacade::class,
    ],
    ```

4. Publish the configuration file using the following Artisan command:

    ```bash
    php artisan vendor:publish --provider="Manhamprod\PwaAssetsGenerator\PwaAssetsGeneratorServiceProvider" --tag=config
    ```

    This will create a **`pwaassetsgenerator.php`** configuration file in the **`config`** directory of your Laravel project.

## Usage

### Generate App Icons

To generate app icons for your PWA:

```php
use Manhamprod\PwaAssetsGenerator\PwaAssetsGenerator;

$icons = PwaAssetsGenerator::generateAppIcon('path/to/image.png');
```

### Generate Splash Screens

To generate splash screens for your PWA:

```php
$splashScreens = PwaAssetsGenerator::generateSplashScreen('path/to/image.png', 'My PWA');
```

### Generate Manifest

To generate a **manifest.webmanifest** file:

```php
PwaAssetsGenerator::generateManifest([
    'name' => 'My PWA',
    'short_name' => 'MyPWA',
    'start_url' => '/',
    'lang' => 'en',
    'description' => 'A Progressive Web App',
    'display' => 'standalone',
    'background_color' => '#ffffff',
    'icons' => [
        ['path' => 'icons/192x192.png', 'size' => '192x192'],
        ['path' => 'icons/512x512.png', 'size' => '512x512'],
    ]
]);
```

### Generate Favicon

To generate a **favicon** from letters:

```php
PwaAssetsGenerator::generateFavicon('AB');
```

### Generate All Assets

To generate all the necessary assets (icons, splash screens, manifest, and favicon) in one call:

```php
PwaAssetsGenerator::generateAssets('path/to/image.png', 'AB', 'My PWA');
```

### Include Splash Screen Links in Blade

To include the splash screen links dynamically in a Blade view:

```php
{!! PwaAssetsGenerator::includeSplashScreen() !!}
```

This will load the splash screen links from the **`resources/views/partials/splash-screen-links.php`** file.

## Configuration

You can customize the settings in the **`config/pwaassetsgenerator.php`** file. This includes:

- Icon sizes
- Splash screen sizes
- Output folder paths
- Background colors
- Fonts and more

## License

This package is open-source and available under the MIT License.
