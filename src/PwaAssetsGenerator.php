<?php

namespace Manhamprod\PwaAssetsGenerator;

class PwaAssetsGenerator
{
    /**
     * Generate application icons from the provided image.
     *
     * @param string $imagePath Path to the image.
     * @param string|null $outputFolderName Name of the output folder.
     * @param array|null $iconSizes Array of icon sizes.
     * @param string|null $backgroundColor Background color of the icons.
     * @return array Paths of generated icons.
     */
    public static function generateAppIcon(
        string $imagePath,
        ?string $outputFolderName = null,
        ?array $iconSizes = null,
        ?string $backgroundColor = null
    ): array
    {
        $image = imagecreatefrompng($imagePath);
        $paths = [];

        // Set default background color if not provided
        $backgroundColor = $backgroundColor ?? config("pwaassetsgenerator.background");

        // Set default output folder if not provided
        $outputFolderName = $outputFolderName ?? config("pwaassetsgenerator.output.icons");
        $outputFolderPath = $outputFolderName;

        // Create the output folder if it doesn't exist
        if (!file_exists($outputFolderPath)) {
            mkdir($outputFolderPath, 0777, true);
        }

        // Generate icons for each specified size
        foreach ($iconSizes ?? config("pwaassetsgenerator.sizes.icons")  as $iconSize) {
            $outputPath = "$outputFolderPath/" . uniqid("app_icon_") . ".png";
            $canvas = imagecreatetruecolor($iconSize, $iconSize);

            // Set background color
            list($r, $g, $b) = sscanf($backgroundColor, "#%02x%02x%02x");
            $bgColor = imagecolorallocate($canvas, $r, $g, $b);
            imagefill($canvas, 0, 0, $bgColor);

            // Scale the image and center it
            $scaledWidth = $iconSize / 2;
            $scaledHeight = $iconSize / 2;
            $resizedImage = imagescale($image, $scaledWidth, $scaledHeight);

            $x = ($iconSize - $scaledWidth) / 2;
            $y = ($iconSize - $scaledHeight) / 2;

            imagecopy($canvas, $resizedImage, $x, $y, 0, 0, $scaledWidth, $scaledHeight);

            imagepng($canvas, $outputPath);

            $paths[] = [
                "path" => $outputPath,
                "size" => $iconSize
            ];

            imagedestroy($canvas);
            imagedestroy($resizedImage);
        }

        return $paths;
    }

    /**
     * Generate splash screens from the provided image.
     *
     * @param string $imagePath Path to the image.
     * @param string|null $name Text to display on the splash screen.
     * @param string|null $outputFolderName Output folder name.
     * @param array|null $screenSizes Screen sizes for the splash screens.
     * @param string|null $backgroundColor Background color.
     * @param int $iconPercentage Percentage of screen size for the icon.
     * @param string|null $policePath Path to the font.
     * @return array Paths of generated splash screens.
     */
    public static function generateSplashScreen(
        string $imagePath,
        ?string $name = null,
        ?string $outputFolderName = null,
        ?array $screenSizes = null,
        ?string $backgroundColor = null,
        ?string $textColor = null,
        int $iconPercentage = 10,
        ?string $policePath = null
    ): array
    {
        $image = imagecreatefrompng($imagePath);
        $paths = [];

        $backgroundColor = $backgroundColor ?? config("pwaassetsgenerator.background");
        $textColor = $textColor ?? config("pwaassetsgenerator.textColor");

        $outputFolderName = $outputFolderName ?? config("pwaassetsgenerator.output.splash");
        $outputFolderPath = $outputFolderName;

        if (!file_exists($outputFolderPath)) {
            mkdir($outputFolderPath, 0777, true);
        }
        
        $screenSizes = $screenSizes ?? config("pwaassetsgenerator.sizes.splash");

        foreach ($screenSizes as $size) {
            list($screenWidth, $screenHeight) = $size;

            $outputPath = "$outputFolderPath/" . uniqid("splash_screen_") . ".png";

            $canvas = imagecreatetruecolor($screenWidth, $screenHeight);

            list($r, $g, $b) = sscanf($backgroundColor, "#%02x%02x%02x");
            list($tr, $tg, $tb) = sscanf($textColor, "#%02x%02x%02x");
            $bgColor = imagecolorallocate($canvas, $r, $g, $b);
            imagefill($canvas, 0, 0, $bgColor);

            $iconWidth = $screenWidth * ($iconPercentage / 100);
            $iconHeight = imagesy($image) * ($iconWidth / imagesx($image));

            $resizedImage = imagescale($image, $iconWidth, $iconHeight);

            $x = ($screenWidth - $iconWidth) / 2;
            $y = ($screenHeight - $iconHeight) / 2;

            imagecopy($canvas, $resizedImage, $x, $y, 0, 0, $iconWidth, $iconHeight);

            if (!is_null($name)) {
                $fontSize = 30;
                $fontColor = imagecolorallocate($canvas, $tr, $tg, $tb);
                $fontPath = $policePath ?? config("pwaassetsgenerator.police");

                $textWidth = imagettfbbox($fontSize, 0, $fontPath, $name)[2];
                $textX = ($screenWidth - $textWidth) / 2;

                $textY = $screenHeight - ($screenHeight * 6 / 100);
                imagettftext($canvas, $fontSize, 0, $textX, $textY, $fontColor, $fontPath, $name);
            }

            imagepng($canvas, $outputPath);

            $paths[] = [
                "path" => $outputPath,
                "size" => $size
            ];

            imagedestroy($canvas);
            imagedestroy($resizedImage);
        }

        return $paths;
    }

    /**
     * Generate a favicon from letters.
     *
     * @param string $letters Letters to use for the favicon.
     * @param string|null $outputFolderName Output folder name.
     * @param string|null $backgroundColor Background color.
     * @param int $faviconSize Size of the favicon.
     * @param string $fileName Name of the favicon file.
     * @param string|null $policePath Path to the font.
     * @return string Path of the generated favicon.
     */
    public static function generateFavicon(
        string $letters,
        ?string $outputFolderName = null,
        ?string $backgroundColor = null,
        ?string $textColor = null,
        int $faviconSize = 64,
        string $fileName = 'favicon',
        ?string $policePath = null
    ): string
    {

        if(config("pwaassetsgenerator.automatiquePublishFaviconToPublicFolder")){
            $outputFolderPath = public_path();
        }else{  
            $outputFolderName = $outputFolderName ?? config("pwaassetsgenerator.output.favicon");
            $outputFolderPath = $outputFolderName == "" ? "" : "/$outputFolderName";
        }

        $backgroundColor = $backgroundColor ?? config("pwaassetsgenerator.background");
        $textColor = $textColor ?? config("pwaassetsgenerator.textColor");

        if (!file_exists($outputFolderPath)) {
            mkdir($outputFolderPath, 0777, true);
        }

        $faviconPath = "$outputFolderPath/" . $fileName . ".ico";

        $canvas = imagecreatetruecolor($faviconSize, $faviconSize);

        list($r, $g, $b) = sscanf($backgroundColor, "#%02x%02x%02x");
        list($tr, $tg, $tb) = sscanf($textColor, "#%02x%02x%02x");
        $bgColor = imagecolorallocate($canvas, $r, $g, $b);
        imagefill($canvas, 0, 0, $bgColor);

        $fontColor = imagecolorallocate($canvas, $tr, $tg, $tb);

        $fontSize = $faviconSize / 2;
        $fontPath = $policePath ?? config("pwaassetsgenerator.police");

        $textWidth = imagettfbbox($fontSize, 0, $fontPath, $letters)[2];
        $textX = ($faviconSize - $textWidth) / 2;

        $textY = ($faviconSize / 2) + ($fontSize / 2);

        imagettftext($canvas, $fontSize, 0, $textX, $textY, $fontColor, $fontPath, $letters);

        imagepng($canvas, $faviconPath);

        imagedestroy($canvas);

        return $faviconPath;
    }

    /**
     * Generate the manifest.json file.
     *
     * @param array $data Manifest data.
     * @return void
     */
    public static function generateManifest(array $data): void 
    {
        $icons = [];
        foreach ($data['icons'] as $icon) {
            $icons[] = [
                'src'    => $icon['path'],
                'sizes'  => $icon['size'],
                'type'   => 'image/png'
            ];
        }

        $manifestContent = [
            'name' => $data['name'] ?? config("pwaassetsgenerator.manifest.name"),
            'short_name' => $data['short_name'] ?? config("pwaassetsgenerator.manifest.shortName"),
            'start_url' => $data['start_url'] ?? config("pwaassetsgenerator.manifest.startUrl"),
            'lang' => $data['lang'] ?? config("pwaassetsgenerator.manifest.lang"),
            'description' => $data['description'] ?? config("pwaassetsgenerator.manifest.description"),
            'display' => $data['display'] ?? config("pwaassetsgenerator.manifest.display"),
            'background_color' => $data['background_color'] ?? config("pwaassetsgenerator.manifest.backgroundColor"),
            'icons' => $icons,
        ];

        $jsonContent = json_encode($manifestContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $outputFolderPath = config("pwaassetsgenerator.automatiquePublishManifestToPublicFolder") ? public_path() : config("pwaassetsgenerator.output.manifest");
        $manifestPath = "$outputFolderPath/manifest.webmanifest";

        file_put_contents($manifestPath, $jsonContent);

    }
    

    /**
     * Generate the splashscreen links.
     *
     * @param array $splashscreen splashscreen data.
     * @param string $filePath file output path.
     * @return void
     */
    public static function generateSplashScreenLinks(array $splashscreen, ?string $filePath = null): void
    {
        $links = '';
        $filePath = $filePath ?? config("pwaassetsgenerator.output.splashScreenLinkPath");

        foreach ($splashscreen as $screen) {
            if (isset($screen['path']) && isset($screen['size'])) {
                $links .= '<link rel="apple-touch-startup-image" href="' . asset($screen['path']) . '"media="screen and (device-width: ' . $screen['size'][0] . ') and (device-height: ' . $screen['size'][1] . ') and (orientation: ' . $screen['size'][2] . ')">' . PHP_EOL;
            } else {
                throw new \InvalidArgumentException('Chaque splash screen doit contenir "path" et "size".');
            }
        }

        $directory = dirname($filePath);

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($filePath, $links);

    }

    /**
     * Include the splashscreen links.
     *
     * @param string $filePath splashscreen link file path.
     * @return void
     */
    public static function includeSplashScreen(?string $filePath = null)
    {

        $filePath = $filePath ?? config("pwaassetsgenerator.output.splashScreenLinkPath");

        if (file_exists($filePath)) {
            include $filePath;
        } else {
            return;
        }

    }

    /**
     * generate all assets for pwa.
     *
     * @param string $imagePath.
     * @param string $letters.
     * @param string $name.
     * @return string
     */
    public static function generateAssetsFiles($manifestData, $splashScreen, $splashPath): void
    {
        self::generateManifest($manifestData);
        self::generateSplashScreenLinks($splashScreen, $splashPath);
    }

    /**
     * generate all assets for pwa.
     *
     * @param string $imagePath.
     * @param string $letters.
     * @param string $name.
     * @return string
     */
    public static function generateAssets(string $imagePath, string $letters, ?string $name = null, ?array $manifestData = null): string
    {
        $icons = self::generateAppIcon($imagePath);

        $splashScreen = self::generateSplashScreen($imagePath, $name);

        $favicon = self::generateFavicon($letters);

        self::generateManifest($manifestData ?? ["icons" => $icons]);
        self::generateSplashScreenLinks($splashScreen);

        return $favicon;

    }

}
