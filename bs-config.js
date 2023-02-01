
/*
 |--------------------------------------------------------------------------
 | Browser-sync config file
 |--------------------------------------------------------------------------
 |
 | For up-to-date information about the options:
 |   http://www.browsersync.io/docs/options/
 |
 | There are more options than you see here, these are just the ones that are
 | set internally. See the website for more info.
 |
 |
 */

require('dotenv').config();

module.exports = {
    // "proxy": "https://tammelin.org",
    "proxy": process.env.SITE_URL || 'https://tammelin.org',
    "port": process.env.PROXY_PORT,
    "startPath": "?bs",
    "files": ['assets/*'],
    "serveStatic": [{
        route: '/wp-content/plugins/templ-mods/assets',
        dir: 'assets'
    }],
    "ui": {
        "port": process.env.PROXY_PORT-1
    }
};