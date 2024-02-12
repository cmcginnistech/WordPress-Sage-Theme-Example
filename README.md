## Requirements
| Prerequisite    | How to check | How to install
| --------------- | ------------ | ------------- |
| PHP >= 5.4.x    | `php -v`     | [php.net](http://php.net/manual/en/install.php) |
| Node.js 0.12.x  | `node -v`    | [nodejs.org](http://nodejs.org/) |
| gulp >= 3.8.10  | `gulp -v`    | `npm install -g gulp` |
| Bower >= 1.3.12 | `bower -v`   | `npm install -g bower` |

Additionally, [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/) is a plugin requirement for utilizing components in your project.

## Features
* [gulp](http://gulpjs.com/) build script that compiles both Sass and Less, checks for JavaScript errors with JSHint, optimizes images, and concatenates / minifies files
* [BrowserSync](http://www.browsersync.io/) for keeping multiple browsers and devices synchronized while testing, along with injecting updated CSS and JS into your browser while you're developing
* [Bower](http://bower.io/) for front-end package management
* [asset-builder](https://github.com/austinpray/asset-builder) for the JSON file based asset pipeline
* [Bootstrap](http://getbootstrap.com/)
* [Theme wrapper](https://roots.io/sage/docs/theme-wrapper/)
* ARIA roles and microformats
* Posts use the [hNews](http://microformats.org/wiki/hnews) microformat
* [Multilingual ready](https://roots.io/wpml/) and over 30 available [community translations](https://github.com/roots/sage-translations)

## Theme Installation
1. [Download the master branch](https://github.com/cuberis/cuberis-base/archive/master.zip) of this repo.
2. Unzip theme in wp-content/themes/.
3. Rename theme to your project name and activate!

## Theme Functionality
`functions.php` is used to include files from the lib/ directory which contains all of the theme functionality. Don’t place any custom code in this file—use it only for includes. PHP code is namespaced.

* **assets.php**: Enqueue stylesheets and scripts.
* **customizer.php**: Add customizer fields.
* **extras.php**: Contains helper functions, including `componify()`.
* **setup.php**: Enable/disable theme features, set config values, register nav menus, sidebars, and define theme support for WordPress core functionality such as post thumbnails, post formats, and HTML5 markup.
* **titles.php**: Control the output of page titles.
* **types.php**: Register custom post types and taxonomies.
* **wrapper.php**: DRY theme wrapper. Read more info about [Sage’s theme wrapper](https://roots.io/sage/docs/theme-wrapper/).

Add additional lib includes as needed.

## Development
Currently, we use use [gulp](http://gulpjs.com/) as a build system and [Bower](http://bower.io/) to manage front-end packages. Building the theme requires [node.js](http://nodejs.org/download/). We recommend you update to the latest version of npm: `npm install -g npm@latest`.

1. Install [gulp](http://gulpjs.com) and [Bower](http://bower.io/) globally with `npm install -g gulp bower`.
2. Navigate to the theme directory, then run `npm install`.
3. Run `bower install`.

You now have all the necessary dependencies to run the build process.

### Available gulp commands
* `gulp` — Compile and optimize the files in your assets directory.
* `gulp watch` — Fire up Browsersync, compile assets when file changes are made.
* `gulp --production` — Compile assets for production (no source maps). Always run this when pushing to a production server.


## Wiki
{{ TBD }}
* Components
* Local dev environments
* Recommended plugins
* Adding CSS / JS libraries
* Deployment / DeployHQ

## Sage Documentation
* Source: [https://github.com/roots/sage](https://github.com/roots/sage)
* Homepage: [https://roots.io/sage/](https://roots.io/sage/)
* Documentation: [https://roots.io/sage/docs/](https://roots.io/sage/docs/)
