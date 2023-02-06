# Templ Mods

Templ Mods is a WordPress plugin that let's your write SCSS on your local machine and immediately see the changes on a remote WordPress. It also comes bundled with [Templ Deploy](https://github.com/herman-templio/templ-deploy) for easy deployment to Templ.

This is especially useful when working on a site where we don't have a child theme to work on, or when we just want to quickly set up a development workflow.

## Installation

Clone this repo to your local machine then install all dependencies:

```bash
npn install
```

Create a `.env` file containing the address of the remote website (`SITE_URL`) and the desired port of your local proxy:

```bash
SITE_URL='https://example.com'
PROXY_PORT=1337
```

To use Templ Deploy, also create a `.templ.mjs` file with the credentials of the site(s) ("templs") you want ot deploy to, e.g.:

```javascript
'staging': {
    app: 1234, // Templ APP ID
    host: 'example.com',
    port: 12345,
    dst: '/home/user_1234/app_1234_staging/wp-content/plugins/templ-mods/',
    sshCmd: '/usr/bin/templ-ccli cache purge', // Purge Templ Cache after deploy
}
```

Upload the plugin to the remote WordPress site using your method of choice, or by using Templ Deploy:

```bash
npm run deploy staging
```

## Usage

You then can start start editing `src/style.scss` on your local machine and watch your changes "live" by running:

```bash
npm run dev
```

If you want to add custom PHP functions, add them to `src/functions.php`.

If you want to add custom JavaScript to the site, add that to `src/main.js`.

Once you are happy with your changes, you can deploy them by simply running Templ Deploy again.

## Working with WooCommerce

If you need to make changes to WooCommerce template files, you can add WC template files to edit in `dist/templates/woocommerce/`.

Just like you would normally add them to `woocommerce/` inside a theme)...

## Changing port

The default proxy port is 1337, to change you it you have to set your desired port on your local machine by editing `.env`.

You'll also have to set the new port at the remote WordPress site using either the constant `TEMPL_MODS_LOCAL_ADDRESS` or using the filter `templ_mods_local_address`.