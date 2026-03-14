# WordPress + Timber + Twig + Tailwind Starter Kit

A complete starter kit for building fast, maintainable WordPress sites with Timber, Twig, and Tailwind CSS, using Claude Code as your co-pilot. No developer required.

Built from three real production migrations by [acornCMO](https://acorncmo.com).

## What's in here

```
├── starter-theme/          # Working WordPress theme scaffold
│   ├── CLAUDE.md           # Project context file (customize this)
│   ├── functions.php       # Timber init, menus, enqueues
│   ├── templates/          # Twig templates (base, page, single, archive)
│   ├── blocks/             # Block PHP shims (empty, ready to use)
│   ├── inc/                # Block render helper
│   ├── src/css/            # Tailwind source
│   ├── assets/css/         # Compiled Tailwind output
│   ├── tailwind.config.js  # Design tokens (replace with your brand)
│   ├── composer.json       # Timber dependency
│   ├── package.json        # Tailwind build scripts
│   └── .gitignore
│
├── theme-scaffold.md       # Claude Code reference (setup sequences + patterns)
├── guide.html              # Full guide (the readable version at acorncmo.com)
└── architecture-diagram.svg
```

## Get started in 3 steps

**1. Clone and copy the starter theme into your LocalWP site:**

```bash
git clone https://github.com/acorncmo/wp-stack-guide.git
cp -r wp-stack-guide/starter-theme/ \
  ~/Local\ Sites/your-site/app/public/wp-content/themes/your-theme/
```

**2. Customize `CLAUDE.md` in the theme directory.**

Open it, replace every bracketed placeholder with your project's details: your site name, your brand colors, your page list. This is the file that makes Claude Code effective. A generic one is barely useful. A customized one is the difference between a helpful assistant and an effective co-pilot.

**3. Start a Claude Code session:**

```
cd ~/Local\ Sites/your-site/app/public/wp-content/themes/your-theme/
claude
```

Then tell it:

> Read CLAUDE.md and theme-scaffold.md. I want to set up this theme for [my site]. Walk me through the setup.

Claude Code will install Composer dependencies, set up Tailwind, and walk you through activating the theme. From there, you build.

## After initial setup

Every subsequent session, you only need CLAUDE.md:

> Read CLAUDE.md. Here's what I want to work on today: [your task].

When you hit a new pattern (first custom block, first deployment, first migration), point Claude Code back to the reference:

> Read theme-scaffold.md section 4. I want to add a Lazy Block for a testimonial section.

End every session by asking Claude Code to update the "Current Status" and "Next Session" sections in CLAUDE.md. This is how you maintain continuity across sessions.

## The full guide

The complete guide covers everything behind this starter kit: why this stack, how it works, three hosting options compared (SiteGround, WP Engine, Cloudways), local development with LocalWP, migration from Elementor or headless CMS, deployment workflows, and working with Claude Code effectively.

Read it at [acorncmo.com/wordpress-guide](https://acorncmo.com/wordpress-guide) or open `guide.html` locally.

## What this stack gives you

- **Fast sites.** No page builder bloat. Tailwind compiles to a tiny CSS file.
- **Readable code.** Twig templates read like HTML with variables. You can make changes months later and still understand what everything does.
- **Portable themes.** Your theme lives in Git. Deploy to any WordPress host.
- **AI-native workflow.** Claude Code reads and writes Twig + Tailwind fluently. This is the stack where AI assistance actually works.

## Tech stack

| Layer | Tool | Role |
|-------|------|------|
| CMS | WordPress 6.x | Content management, plugin ecosystem |
| PHP bridge | Timber 2.x | Separates PHP logic from HTML templates |
| Templating | Twig | Clean, readable templates |
| Styling | Tailwind CSS 3.x | Utility-first CSS, compiled via CLI |
| Local dev | LocalWP | One-click local WordPress |
| AI co-pilot | Claude Code | Builds, modifies, and debugs the theme |

## License

Code (starter theme, scaffold reference): [MIT](LICENSE).
Guide content (HTML guide, diagram, README): [CC BY 4.0](https://creativecommons.org/licenses/by/4.0/).

---

Built by [acornCMO](https://acorncmo.com). From seed to scale.
