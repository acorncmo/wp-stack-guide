# Project Context — [Your Site Name]

> **How to use this file:**
> Place this file in the root of your theme directory. At the start of every Claude Code
> session, say: *"Read CLAUDE.md and let me know what you see."*
>
> Update the "Current Status" and "Next Session" sections at the end of every session.

---

## What This Project Is

Custom WordPress theme for [YOUR SITE URL], built with Timber + Twig + Tailwind CSS.

This is a code-based theme, not a page builder. All page layouts are defined in Twig
templates with Tailwind utility classes. Content is managed through the WordPress editor
and custom fields. The theme is version-controlled in Git and deployed to [YOUR HOST].

---

## Tech Stack

| Tool | Version | Purpose |
|------|---------|---------|
| WordPress | 6.x | CMS |
| PHP | 8.1+ | Server runtime |
| Timber | ^2.0 | PHP/Twig bridge for WordPress |
| Twig | 3.x (ships with Timber) | Templating language |
| Tailwind CSS | ^3.4 | Utility-first CSS framework |
| Build tool | Tailwind CLI | `npm run dev` (watch) / `npm run build` (minify) |
| Composer | latest | Installs Timber into `vendor/` |
| Node/npm | latest LTS | Installs Tailwind, runs build scripts |

**Custom fields:** [ACF Pro / Lazy Blocks / both / neither — pick one and describe]
**Host:** [SiteGround / WP Engine / Cloudways]
**Local dev:** LocalWP at `localhost:[PORT]`

---

## File Locations

```
Theme root: ~/Local Sites/[site-name]/app/public/wp-content/themes/[theme-name]/

Key paths:
  templates/              ← All .twig template files
  templates/partials/     ← Shared partials (nav, footer)
  templates/blocks/       ← Block-level Twig templates
  blocks/                 ← Block PHP shims (Lazy Blocks + native)
  inc/                    ← PHP includes (blocks.php, admin-settings.php, acf-fields.php)
  src/css/app.css         ← Tailwind source file (directives only)
  assets/css/app.css      ← Compiled Tailwind output
  assets/images/          ← Theme images (not in WP media library)
  fonts/                  ← Self-hosted font files
  functions.php           ← Timber init, menus, enqueues, global context
  tailwind.config.js      ← Design tokens (colors, fonts, sizes, breakpoints)
  package.json            ← npm scripts: dev + build
  composer.json           ← Timber dependency
```

---

## Conventions — Follow These

### PHP template files (front-page.php, page.php, single.php, etc.)
Always 3 lines. They are dispatchers, not templates:
```php
<?php
$context = Timber::context();
$context['post'] = Timber::get_post();
Timber::render( 'front-page.twig', $context );
```

### Twig templates
- Every page template extends `base.twig` and fills `{% block content %}`
- Use `{% include 'partials/nav.twig' %}` for shared components
- All styling via Tailwind utility classes — no custom CSS classes
- Access global context: `{{ site_url }}`, `{{ theme_uri }}`, `{{ nav_primary }}`, `{{ options }}`
- Access post data: `{{ post.title }}`, `{{ post.content }}`, `{{ post.thumbnail }}`

### Blocks (if using Lazy Blocks)
All blocks render through a shared helper:
```
Block → blocks/lazyblock-{slug}/block.php → theme_block_render($template, $attrs)
     → Timber::compile("blocks/{$template}.twig", $context + attrs)
```
- Lazy Block control names use **underscores** (not hyphens — Lazy Blocks auto-generates hyphens, must be manually corrected)
- Block data is accessed in Twig via `{{ attrs.field_name }}`

### Blocks (if using ACF Flexible Content)
Pages use an ACF Flexible Content field called `page_builder`. The page template loops
through blocks and dynamically includes the right module template:
```twig
{% for block in page_builder %}
  {% include "modules/" ~ block.acf_fc_layout|replace({'_': '-'}) ~ ".twig"
     with {block: block} ignore missing %}
{% endfor %}
```
- Module data is accessed in Twig via `{{ block.field_name }}`
- ACF field groups are registered in PHP (`inc/acf-fields.php`), not the UI

### Tailwind
- Source: `src/css/app.css` → Output: `assets/css/app.css`
- Run `npm run dev` while developing (watches for changes)
- Run `npm run build` before committing/deploying (minifies)
- `tailwind.config.js` content array MUST include all paths with Tailwind classes:
  ```js
  content: [
    './templates/**/*.twig',
    './blocks/**/*.php',
    './src/**/*.js',
  ]
  ```
- If a new Tailwind class is not applying, check: (1) `npm run dev` is running, (2) the file is in the content array

### Git
- Theme-only repo (not full WP install)
- `node_modules/` and `vendor/` are gitignored [adjust if deploying to WP Engine — vendor/ must be committed]
- Compiled CSS is committed (no build step on server) [adjust if you build on deploy]
- Commit messages describe what changed and why

---

## Design System

### Colors
| Token | Hex | Usage |
|-------|-----|-------|
| [color-name]-500 | #[hex] | [Primary usage] |
| [color-name]-400 | #[hex] | [Secondary usage] |
| [color-name]-300 | #[hex] | [Accent usage] |
| ... | | |

### Fonts
- **Display:** [Font Name] — headlines, hero text (self-hosted / Google Fonts)
- **Body:** [Font Name] — body copy, UI text (Google Fonts)

### Breakpoints
| Name | Width |
|------|-------|
| sm | [X]px |
| md | [X]px |
| lg | [X]px |
| xl | [X]px |

---

## Site Structure

| Page | PHP File | Twig Template | Notes |
|------|----------|---------------|-------|
| Homepage | front-page.php | front-page.twig | [describe sections] |
| [Page name] | page-[slug].php | page-[slug].twig | [describe sections] |
| Blog archive | archive.php | archive.twig | Post grid with pagination |
| Single post | single.php | single.twig | Hero + prose content |
| Generic page | page.php | page.twig | Block editor content |
| 404 | 404.php | 404.twig | Simple error page |

---

## Navigation

Menus registered in `functions.php`:
- `primary` — Main nav (available in Twig as `{{ nav_primary }}`)
- `footer` — Footer nav (available in Twig as `{{ nav_footer }}`)

Manage menus in WP Admin → Appearance → Menus.

---

## Plugins Active

| Plugin | Purpose | Theme dependency? |
|--------|---------|-------------------|
| Timber | Twig templating bridge | Yes — theme breaks without it |
| [ACF Pro / Lazy Blocks] | Custom fields / blocks | Yes — [which pages depend on it] |
| [Contact Form 7 / etc.] | [Purpose] | [Yes/No] |
| [SEO plugin] | SEO meta, sitemap | No — functions independently |
| [Caching plugin] | [Host-specific caching] | No — host-level |

---

## Current Status

### What is done
- [ ] Theme scaffolded (base.twig, nav, footer)
- [ ] Homepage template
- [ ] [Other completed pages]
- [ ] Tailwind config with design tokens
- [ ] Git repo initialized and connected to GitHub

### What is not done
- [ ] [Remaining pages]
- [ ] [Blocks to build]
- [ ] [Migration tasks]
- [ ] [Deployment setup]
- [ ] Visual QA against [reference — live site / Figma / etc.]

---

## Next Session

**Priority:** [What to work on next]

**Context needed:** [Any open questions, blockers, or decisions pending]

**Files likely to change:** [List specific files so Claude Code can read them first]

---

## Deployment

**Host:** [SiteGround / WP Engine / Cloudways]
**Method:** [SFTP / git push production main / rsync deploy.sh]
**Staging URL:** [if applicable]
**Production URL:** [your domain]

### Deploy steps
1. `npm run build`
2. `git add . && git commit -m "description"`
3. `git push origin main`
4. [Host-specific deploy command]
5. Flush caches: [host-specific instructions]

---

## Known Gotchas

- **Tailwind classes not applying?** Check that `npm run dev` is running and the file is in `tailwind.config.js` content array
- **White screen after theme activation?** Run `composer install` in the theme directory
- **Lazy Block control names:** Must use underscores, not the auto-generated hyphens
- **Database migration:** Always `wp search-replace` with `--dry-run` first
- **[Add project-specific gotchas as you discover them]**
