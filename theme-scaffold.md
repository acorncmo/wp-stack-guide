# Theme Scaffold Reference — Timber + Twig + Tailwind

> **Purpose:** This file contains the complete setup sequence and code patterns for building
> a WordPress theme with Timber, Twig, and Tailwind CSS. Claude Code should read this file
> during initial theme setup, then reference it when building new templates or blocks.
>
> **This is not a guide for humans.** It is a reference document optimized for Claude Code.
> The companion HTML guide explains the "why." This file provides the "what" and "how."

---

## 1. Initial Setup Sequence

Run these steps in order from the theme directory. The theme directory should be inside a
LocalWP WordPress install at:
`~/Local Sites/[site-name]/app/public/wp-content/themes/[theme-name]/`

### 1.1 Create theme directory and metadata file

```bash
mkdir -p ~/Local\ Sites/[site-name]/app/public/wp-content/themes/[theme-name]
cd ~/Local\ Sites/[site-name]/app/public/wp-content/themes/[theme-name]
```

Create `style.css` (WordPress theme header — metadata only, no actual styles):
```css
/*
Theme Name: [Theme Name]
Description: Custom Timber + Twig + Tailwind theme
Version: 1.0.0
Requires PHP: 8.1
*/
```

### 1.2 Install Timber via Composer

```bash
composer init --name="[org]/[theme-name]" --type="wordpress-theme" --no-interaction
composer require timber/timber:^2.0
```

This creates `composer.json`, `composer.lock`, and `vendor/` directory.

### 1.3 Install Tailwind CSS via npm

```bash
npm init -y
npm install -D tailwindcss
```

### 1.4 Create directory structure

```bash
mkdir -p templates/partials templates/blocks blocks inc src/css assets/css assets/images fonts
```

### 1.5 Create Tailwind source file

Write `src/css/app.css`:
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

### 1.6 Create tailwind.config.js

```js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.twig',
    './blocks/**/*.php',
    './src/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        // ADD YOUR BRAND COLORS HERE
        // Example:
        // primary: { 500: '#250F04', 400: '#3F1906', 300: '#59301B' },
        // accent:  { 500: '#25495C', 300: '#B8D6E5' },
      },
      fontFamily: {
        // display: ['"Your Display Font"', 'serif'],
        // sans: ['"Your Body Font"', 'sans-serif'],
      },
      fontSize: {
        // Map your type scale here
        // 'h1': ['64px', { lineHeight: '100px' }],
        // 'h2': ['48px', { lineHeight: '100px' }],
        // 'body': ['14px', { lineHeight: '140%' }],
      },
      screens: {
        'sm': '480px',
        'md': '768px',
        'lg': '1025px',
        'xl': '1440px',
      },
    },
  },
  plugins: [],
}
```

### 1.7 Update package.json scripts

```json
{
  "scripts": {
    "dev": "npx tailwindcss -i ./src/css/app.css -o ./assets/css/app.css --watch",
    "build": "npx tailwindcss -i ./src/css/app.css -o ./assets/css/app.css --minify"
  }
}
```

### 1.8 Create functions.php

```php
<?php
/**
 * Theme functions — Timber + Twig + Tailwind
 */

// Load Timber via Composer
if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    wp_die( 'Timber not found. Run <code>composer install</code> in the theme directory.' );
}
require_once __DIR__ . '/vendor/autoload.php';

use Timber\Timber;
use Timber\Site;

Timber::init();

/**
 * Global context — available in every Twig template.
 */
add_filter( 'timber/context', function( $context ) {
    $context['site']    = new Site();
    $context['options'] = get_option( '[theme]_site_content', [] );

    $context['nav_primary'] = Timber::get_menu( 'primary' );
    $context['nav_footer']  = Timber::get_menu( 'footer' );

    $context['site_url']  = home_url();
    $context['theme_uri'] = get_template_directory_uri();

    return $context;
} );

/**
 * Theme setup.
 */
add_action( 'after_setup_theme', function() {
    register_nav_menus( [
        'primary' => __( 'Primary Navigation', '[theme]' ),
        'footer'  => __( 'Footer Navigation', '[theme]' ),
    ] );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption'
    ] );
} );

/**
 * Enqueue styles.
 */
add_action( 'wp_enqueue_scripts', function() {
    $theme_uri = get_template_directory_uri();
    $version   = wp_get_theme()->get( 'Version' );

    wp_enqueue_style( 'theme-styles',
        $theme_uri . '/assets/css/app.css', [], $version
    );

    // Google Fonts (replace with your font)
    // wp_enqueue_style( 'google-fonts',
    //     'https://fonts.googleapis.com/css2?family=Public+Sans:wght@300..900&display=swap',
    //     [], null
    // );
} );

/**
 * Twig template directory.
 */
add_filter( 'timber/locations', function( $paths ) {
    $paths[] = get_template_directory() . '/templates';
    return $paths;
} );

// Optional: include additional PHP files
// require_once __DIR__ . '/inc/blocks.php';
// require_once __DIR__ . '/inc/admin-settings.php';
// require_once __DIR__ . '/inc/acf-fields.php';
```

### 1.9 Create index.php (required WordPress fallback)

```php
<?php
// Silence is golden. WordPress requires this file to exist.
// All rendering is handled by Timber via specific template files.
```

### 1.10 Create base.twig

Write `templates/base.twig`:
```twig
<!DOCTYPE html>
<html {{ site.language_attributes }}>
<head>
  <meta charset="{{ site.charset }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  {{ function('wp_head') }}
  {% block head_extra %}{% endblock %}
</head>
<body class="{{ function('body_class') }}">
{{ function('wp_body_open') }}

<div class="site-wrap">
  {% include 'partials/nav.twig' %}

  <main id="main">
    {% block content %}{% endblock %}
  </main>

  {% include 'partials/footer.twig' %}
</div>

{{ function('wp_footer') }}
</body>
</html>
```

### 1.11 Activate theme and start Tailwind

1. Activate the theme in WP Admin → Appearance → Themes
2. In a terminal, from the theme directory: `npm run dev`
3. Verify the site loads (it will be blank but should not error)

---

## 2. PHP Dispatcher Pattern

Every PHP template file follows this exact pattern. The file name determines which
WordPress template hierarchy rule matches. The Twig file does all the rendering.

### front-page.php
```php
<?php
$context = Timber::context();
$context['post'] = Timber::get_post();
Timber::render( 'front-page.twig', $context );
```

### page.php
```php
<?php
$context = Timber::context();
$context['post'] = Timber::get_post();
Timber::render( 'page.twig', $context );
```

### page-[slug].php (e.g., page-contact.php)
```php
<?php
$context = Timber::context();
$context['post'] = Timber::get_post();
Timber::render( 'page-contact.twig', $context );
```

### archive.php
```php
<?php
$context = Timber::context();
$context['posts'] = Timber::get_posts();
Timber::render( 'archive.twig', $context );
```

### single.php
```php
<?php
$context = Timber::context();
$context['post'] = Timber::get_post();
Timber::render( 'single.twig', $context );
```

### 404.php
```php
<?php
$context = Timber::context();
Timber::render( '404.twig', $context );
```

---

## 3. Twig Template Patterns

### Page template (extends base)
```twig
{% extends 'base.twig' %}

{% block content %}
  <section class="py-16 px-6 max-w-3xl mx-auto">
    <h1 class="font-display text-h1 mb-8">{{ post.title }}</h1>
    <div class="prose font-sans text-body">
      {{ post.content }}
    </div>
  </section>
{% endblock %}
```

### Blog archive with post loop
```twig
{% extends 'base.twig' %}

{% block content %}
  <section class="py-16 px-6 max-w-5xl mx-auto">
    <h1 class="font-display text-h2 mb-12">Blog</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      {% for post in posts %}
        <article class="border rounded-2xl overflow-hidden">
          {% if post.thumbnail %}
            <img src="{{ post.thumbnail.src('medium_large') }}"
                 alt="{{ post.title }}"
                 class="w-full h-48 object-cover">
          {% endif %}
          <div class="p-6">
            <h2 class="font-sans font-bold text-h4 mb-2">
              <a href="{{ post.link }}">{{ post.title }}</a>
            </h2>
            <p class="text-body text-brown-200">
              {{ post.preview.length(25).read_more('') }}
            </p>
          </div>
        </article>
      {% endfor %}
    </div>
  </section>
{% endblock %}
```

### Navigation partial
```twig
{# partials/nav.twig #}
<header class="bg-[PRIMARY-BG-COLOR] sticky top-0 z-50">
  <nav class="container mx-auto flex items-center justify-between py-4 px-6">
    {# Left: nav links (desktop) #}
    <div class="hidden lg:flex items-center gap-8">
      {% for item in nav_primary.items %}
        <a href="{{ item.link }}"
           class="text-white text-sm font-sans hover:opacity-80 transition-opacity
                  {{ item.current ? 'opacity-80' : '' }}">
          {{ item.title }}
        </a>
      {% endfor %}
    </div>

    {# Center: logo #}
    <a href="{{ site_url }}">
      <img src="{{ theme_uri }}/assets/images/logo.svg" alt="{{ site.name }}" class="h-10">
    </a>

    {# Right: CTA button (desktop) #}
    <div class="hidden lg:block">
      <a href="{{ site_url }}/contact/"
         class="bg-white text-[PRIMARY-TEXT] font-sans text-sm font-semibold
                px-6 py-2 rounded-full hover:opacity-90 transition-opacity">
        Contact us
      </a>
    </div>

    {# Mobile hamburger #}
    <button id="mobile-toggle" class="lg:hidden text-white text-2xl">&#9776;</button>
  </nav>

  {# Mobile menu #}
  <div id="mobile-menu" class="hidden lg:hidden px-6 pb-4">
    {% for item in nav_primary.items %}
      <a href="{{ item.link }}" class="block text-white text-sm py-2 border-b border-white/10">
        {{ item.title }}
      </a>
    {% endfor %}
  </div>
</header>

<script>
document.getElementById('mobile-toggle').addEventListener('click', function() {
  document.getElementById('mobile-menu').classList.toggle('hidden');
});
</script>
```

### Footer partial
```twig
{# partials/footer.twig #}
<footer class="bg-[PRIMARY-BG-COLOR] text-white py-16 px-6">
  <div class="max-w-5xl mx-auto">
    {# Footer content here: columns, links, logo, copyright #}
    <p class="text-sm opacity-60 mt-12">
      &copy; {{ "now"|date("Y") }} {{ site.name }}
    </p>
  </div>
</footer>
```

---

## 4. Block Patterns

### Option A: Lazy Blocks + Twig (simpler setup, fewer blocks)

#### Register the render helper in `inc/blocks.php`
```php
<?php
function theme_block_render( $template, $attrs = [] ) {
    $context = Timber::context();
    $context['attrs'] = $attrs;
    return Timber::compile( "blocks/{$template}.twig", $context );
}
```

#### Create the PHP shim at `blocks/lazyblock-[slug]/block.php`
```php
<?php
echo theme_block_render( '[slug]', $attributes );
```

#### Create the Twig template at `templates/blocks/[slug].twig`
```twig
{# Access fields via attrs.field_name #}
<section class="py-16 px-6">
  {% if attrs.heading %}
    <h2 class="font-display text-h2 mb-4">{{ attrs.heading }}</h2>
  {% endif %}
  {% if attrs.description %}
    <p class="text-body max-w-2xl">{{ attrs.description }}</p>
  {% endif %}
</section>
```

**Lazy Blocks setup in WP Admin:**
1. Go to Lazy Blocks → Add New
2. Set slug to `lazyblock/[your-slug]`
3. Add controls. **Critical:** change every auto-generated control name from hyphens to underscores
4. Set Output Method to "Theme Template"
5. Verify `blocks/lazyblock-[slug]/block.php` exists in the theme

### Option B: ACF Flexible Content (page builder, many modules)

#### In functions.php or inc/acf-fields.php, register the Flexible Content field group
```php
add_action('acf/init', function() {
    acf_add_local_field_group([
        'key'      => 'group_page_builder',
        'title'    => 'Page Builder',
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'page']]],
        'fields'   => [[
            'key'     => 'field_page_builder',
            'name'    => 'page_builder',
            'type'    => 'flexible_content',
            'layouts' => [
                // Define each module as a layout with its own sub-fields
            ]
        ]]
    ]);
});
```

#### Page template renders the builder loop
```twig
{% extends 'base.twig' %}
{% block content %}
  {% for block in page_builder %}
    {% set layout = block.acf_fc_layout %}
    {% include "modules/" ~ layout|replace({'_': '-'}) ~ ".twig"
       with {block: block} ignore missing %}
  {% endfor %}
{% endblock %}
```

#### Each module template receives its data via `block`
```twig
{# templates/modules/hero-large.twig #}
<section class="relative min-h-[540px] flex items-center bg-[BG-COLOR]">
  <div class="container mx-auto px-6 py-24">
    {% if block.eyebrow %}
      <p class="text-sm uppercase tracking-widest mb-4">{{ block.eyebrow }}</p>
    {% endif %}
    <h1 class="font-display text-title mb-8">{{ block.heading }}</h1>
    {% if block.cta_text and block.cta_url %}
      <a href="{{ block.cta_url }}" class="btn">{{ block.cta_text }}</a>
    {% endif %}
  </div>
</section>
```

---

## 5. Timber/Twig Quick Reference

### Variables and output
```twig
{{ post.title }}                          {# Output a variable #}
{{ post.content }}                        {# Output post content (with HTML) #}
{{ post.preview.length(25) }}             {# Excerpt, 25 words #}
{{ post.date }}                           {# Formatted date #}
{{ post.author.name }}                    {# Author name #}
{{ post.thumbnail.src('medium_large') }}  {# Featured image URL at size #}
{{ "now"|date("Y") }}                     {# Current year #}
{{ site.name }}                           {# Site title #}
{{ site_url }}                            {# Home URL #}
{{ theme_uri }}                           {# Theme directory URL (for assets) #}
```

### Conditionals
```twig
{% if post.thumbnail %}
  <img src="{{ post.thumbnail.src }}">
{% endif %}

{% if post.terms('category') %}
  {% for term in post.terms('category') %}
    {{ term.name }}
  {% endfor %}
{% endif %}
```

### Loops
```twig
{% for post in posts %}
  <h2>{{ post.title }}</h2>
{% endfor %}

{# With loop index #}
{% for item in items %}
  <div class="{{ loop.first ? 'first' : '' }} {{ loop.last ? 'last' : '' }}">
    {{ item.title }} ({{ loop.index }} of {{ loop.length }})
  </div>
{% endfor %}
```

### Includes and extends
```twig
{% extends 'base.twig' %}                    {# Inherit layout #}
{% block content %}...{% endblock %}          {# Fill a block #}
{% include 'partials/nav.twig' %}             {# Include a partial #}
{% include 'partials/card.twig' with {card: post} %}  {# Pass data to partial #}
```

### Calling WordPress functions from Twig
```twig
{{ function('wp_head') }}
{{ function('wp_footer') }}
{{ function('wp_body_open') }}
{{ function('body_class') }}
{{ function('get_search_form') }}
{{ function('do_shortcode', '[contact-form-7 id="123"]') }}
```

### ACF fields in Twig (if using ACF Pro)
```twig
{{ post.meta('field_name') }}                {# Simple field #}
{% set img = post.meta('image_field') %}     {# Image (returns array) #}
<img src="{{ img.url }}" alt="{{ img.alt }}">

{% for item in post.meta('repeater_field') %} {# Repeater #}
  {{ item.sub_field_name }}
{% endfor %}
```

---

## 6. .gitignore

```
node_modules/
vendor/
.env
.DS_Store
Thumbs.db
*.log
.vscode/
.idea/
*.swp
```

**Note for WP Engine deploys:** Remove `vendor/` from .gitignore. WP Engine has no
Composer, so vendor must be committed.

---

## 7. Deployment Commands

### SiteGround (SFTP or SSH)
```bash
npm run build
# Upload theme via SFTP, or:
ssh user@server -p 18765 "cd ~/www/site/public_html/wp-content/themes/[theme] && git pull"
```

### WP Engine (Git push)
```bash
npm run build
git add .
git commit -m "Deploy: [description]"
git push origin main         # GitHub backup
git push production main     # WP Engine deploy
# First push only: git push production main --force
```

### Cloudways (rsync)
```bash
npm run build
rsync -rltz --delete --omit-dir-times --no-perms \
  --exclude 'node_modules' --exclude '.git' --exclude '.env' \
  ./ master@SERVER_IP:/home/master/applications/APP_ID/public_html/wp-content/themes/[theme]/
ssh master@SERVER_IP "cd /home/master/applications/APP_ID/public_html && wp cache flush"
```

---

## 8. Common Fixes

| Symptom | Cause | Fix |
|---------|-------|-----|
| White screen | Timber not installed | `composer install` in theme dir |
| Tailwind classes not applying | Build not running | Run `npm run dev` |
| Tailwind classes not applying | File not in content array | Add path to `tailwind.config.js` content |
| Wrong template loading | PHP file name mismatch | Check WordPress template hierarchy naming |
| ACF field returns nothing | Missing `_fieldname` meta | Use `update_field()` not `update_post_meta()` |
| Lazy Block not rendering | Missing block.php shim | Create `blocks/lazyblock-[slug]/block.php` |
| Lazy Block wrong field name | Hyphens instead of underscores | Edit control names in Lazy Blocks UI |
| Stale content after deploy | Cache not flushed | Flush host cache + `wp cache flush` |
| Images HTTP on WP Engine | SSL termination at LB | Add `upload_dir` filter forcing HTTPS |
| DB URLs wrong after migration | Forgot search-replace | `wp search-replace 'old' 'new'` |
| git push to WP Engine rejected | First push, history diverges | `git push production main --force` (once) |
