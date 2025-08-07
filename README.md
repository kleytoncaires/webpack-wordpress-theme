# Webpack WordPress Starter Theme

WordPress starter theme desenvolvido com Webpack, Sass e tecnologias modernas. Workflow otimizado para desenvolvimento de temas WordPress com build automatizado.

🎨 **STYLES**

-   Sass to CSS conversion
-   Auto-prefixing
-   Minification
-   Source maps

🌋 **JavaScript**

-   Module bundling
-   Source maps
-   Minification

🧹 **BUILD SYSTEM**

-   Automatic hash-based cache busting
-   Selective file cleanup
-   Development and production modes
-   Live reload notifications

## 📋 Prerequisites

-   [Node.js](https://nodejs.org/) >= 16.0.0
-   [NPM](https://npmjs.com/) >= 7.0.0
-   PHP >= 7.4
-   WordPress >= 5.0

## ⚡️ Quick Start

Run inside your WordPress theme directory:

```sh
# Install dependencies
npm install

# Start development mode with watch
npm start

# Or using Yarn
yarn install
yarn start
```

## 🛠️ Available Scripts

### `npm start` / `npm run dev`

Starts development mode with automatic file watching. Monitors Sass and JavaScript files and recompiles automatically with desktop notifications for errors.

### `npm run build`

Compiles files for production with optimizations (minification, CSS optimization, etc.).

### `npm run clean`

Removes all compiled files (CSS, JS, maps) and cleans temporary folders.

## 📁 Project Structure

```
├── assets/
│   ├── css/                    # Sass files
│   │   ├── style.scss         # Main stylesheet
│   │   ├── _variables.scss    # Variables
│   │   ├── _mixins.scss       # Mixins
│   │   └── blocks/            # Block styles
│   ├── js/
│   │   └── scripts.js         # Main JavaScript
│   ├── fonts/                 # Custom fonts
│   └── img/                   # Images and icons
├── blocks/                     # Custom ACF blocks
├── functions/                  # Organized PHP functions
├── partials/                   # Template partials
├── .temp/                      # Temporary folder (ignored)
├── webpack.config.js          # Webpack configuration
└── package.json               # Dependencies and scripts
```

## 🎯 Generated Files

Webpack automatically generates:

### In theme root:

-   `style.[hash].css` - Compiled CSS with 8-character hash for cache busting
-   `scripts.[hash].js` - Compiled JavaScript with hash
-   `style.css` - WordPress theme file with only theme comments
-   `asset-manifest.json` - Hash file mapping for PHP enqueue functions

### In `.temp/` folder:

-   `style.[hash].js` - Temporary JS file from CSS compilation (not used)

## ⚙️ Build Features

### 🧹 Smart File Cleanup

-   **Selective cleanup**: Only removes old hashed files when related source files change
-   **CSS changes**: Cleans only old CSS files and maps
-   **JS changes**: Cleans old JS files, maps, and temporary style.js files
-   **Initial build**: Cleans all hashed files for fresh start

### 🎨 CSS Processing

-   **Sass compilation**: Full SCSS support with imports
-   **Source maps**: For debugging in development
-   **URL handling**: Disabled to prevent asset copying
-   **Production minification**: Automatic in build mode
-   **Banner injection**: WordPress theme headers added automatically

### 📦 JavaScript Processing

-   **Module bundling**: ES6 imports/exports support
-   **Source maps**: Available in both modes
-   **Production optimization**: Automatic minification

### 🏷️ Cache Busting System

-   **8-character hashes**: Based on file content
-   **Manifest generation**: JSON file maps original → hashed filenames
-   **PHP integration**: Helper functions for WordPress enqueue
-   **Selective updates**: Only files that changed get new hashes

### 🔔 Developer Experience

-   **Error notifications**: Desktop alerts for SASS compilation errors
-   **Sound alerts**: Audio notification on errors
-   **Watch mode**: Smart file watching with 1s polling
-   **Fast rebuilds**: Only recompiles changed files

## 🔧 WordPress Configuration

The theme uses helper functions in `functions/webpack-assets.php`:

```php
// Enqueue CSS with automatic versioning
enqueue_webpack_style('theme-style', 'style.css');

// Enqueue JavaScript with dependencies
enqueue_webpack_script('theme-scripts', 'scripts.js', ['jquery'], true);
```

These functions automatically read the `asset-manifest.json` to load the correct hashed filenames.

## 🚨 Troubleshooting

### Permission errors:

```sh
sudo npm install
```

### Files not updating:

```sh
npm run clean
npm start
```

### Node/NPM issues:

Check if you're using Node >= 16:

```sh
node --version  # Should show v16.x.x or higher
npm --version   # Should show v7.x.x or higher
```

### SASS compilation errors:

-   Check the desktop notification for specific error details
-   Verify your SCSS syntax
-   Ensure all imported files exist

### Cache issues:

The hash-based system should prevent most cache issues, but if needed:

```sh
# Clear all generated files
npm run clean
# Force fresh install
rm -rf node_modules package-lock.json
npm install
```

---

## 🚀 WordPress Setup Guide

After setting up the theme, you can configure your WordPress environment with the following steps.

### Prerequisites

-   [WP-CLI](https://wp-cli.org/) installed

### Theme Installation

```sh
# Download and install the theme
wp theme install https://github.com/kleytoncaires/webpack-wordpress-theme/archive/main.zip

# Rename the theme folder (replace 'your-theme-name' with desired name)
mv wp-content/themes/webpack-wordpress-theme-main wp-content/themes/your-theme-name

# Activate the theme
wp theme activate your-theme-name

# Remove unnecessary default themes
wp theme delete twentytwentytwo twentytwentythree twentytwentyfour
rm -rf wp-config-sample.php readme.html license.txt
```

### Language and Timezone Configuration

```sh
# Install Portuguese (Brazil) language
wp language core install pt_BR

# Switch site language to Portuguese (Brazil)
wp site switch-language pt_BR

# Set timezone to 'America/Sao_Paulo'
wp option update timezone_string 'America/Sao_Paulo'
```

### Plugin Installation

```sh
# Install and activate essential plugins
wp plugin install contact-form-7 contact-form-cfdb7 wordpress-seo wp-mail-smtp wp-migrate-db --activate

# Install ACF PRO (requires valid license key)
wp plugin install "https://connect.advancedcustomfields.com/v2/plugins/download?p=pro&k=b3JkZXJfaWQ9Nzg5MDd8dHlwZT1kZXZlbG9wZXJ8ZGF0ZT0yMDE2LTA0LTA1IDEzOjQwOjQw&_gl=1*hn0494*_gcl_au*MTM5NTY4MTA5My4xNzI1MzY4NDc5*_ga*MTU2NTI3MzM4OS4xNzI1MzY4NDc3*_ga_QQ5FN8NX8W*MTcyODUwMTA5Ni42LjEuMTcyODUwMTg3Ni41OS4wLjE2MTU0ODQ1MjA" --activate
```

### Homepage Setup

```sh
# Create a homepage
wp post create --post_type=page --post_title='Home' --post_status=publish

# Set front page to display static page
wp option update show_on_front page

# Get the homepage ID and set it as front page
HOMEPAGE_ID=$(wp post list --post_type=page --name=home --field=ID)
wp option update page_on_front $HOMEPAGE_ID
```

### Install Theme Dependencies

After WordPress setup, install the theme's JavaScript dependencies:

```sh
# Navigate to your theme directory
cd wp-content/themes/your-theme-name

# Install dependencies
npm install

# Start development
npm start
```

## 📄 License & Attribution

MIT © [Kleyton Caires](https://linkedin.com/in/kleytoncaires)

This project is inspired by modern JavaScript build tools and the WordPress community. Built with Webpack, Sass, and other awesome open source tools listed in `package.json`.

## 👨‍💻 Developer

**Kleyton Caires**  
GitHub: [github.com/kleytoncaires](https://github.com/kleytoncaires)  
LinkedIn: [linkedin.com/in/kleytoncaires](https://linkedin.com/in/kleytoncaires)
