# GenX Coding - Online Store

A PHP/MySQL e-commerce site selling developer merch (keyboards, hoodies, mugs, stickers, bags, and more).

## 1. What's included

```
genxcoding/
  config.php                <- DB connection + BASE_URL settings (not in git, see .gitignore)
  config.example.php         <- safe template for config.php, with placeholder credentials
  .gitignore                  <- keeps config.php (real credentials) out of the GitHub repo
  database.sql               <- import into MySQL; creates all tables + seed data (20 products, categories, admin account)

  index.php                  <- homepage (featured products, hero, intro video)
  products.php                <- product listing, search, category filter pills
  category.php                 <- products filtered by one category
  product.php                   <- single product page (add-to-cart form + reviews)
  cart.php                       <- shopping cart
  checkout.php                    <- checkout form
  login.php / register.php / logout.php  <- authentication
  account.php                      <- private "my account" page - profile editing + order history
  about.php / contact.php           <- company info and contact form
  newsletter.php                     <- handles the footer newsletter signup form
  ajax-add-to-cart.php                <- JSON endpoint used by js/main.js for the no-reload "Add to Cart" button
  session-status.php                   <- JSON endpoint the static pages use to sync login state
  theme-status.php                      <- JSON endpoint the static pages use to sync the active template

  includes/
    header.php          <- shared <head>/nav - reads the active template from the DB, sets per-page SEO tags
    footer.php            <- shared footer - category list, newsletter form, closes the layout wrapper
    helpers.php             <- render_price() / is_on_sale() - shared sale-pricing logic

  css/
    regular.css      <- default template (also auto-adapts to visitor's OS dark mode)
    dark.css           <- fixed dark template
    retro.css            <- fixed retro/terminal template
  js/main.js          <- mobile menu, AJAX add-to-cart, newsletter confirmation, scroll-position fix

  images/    <- 21 original product/illustration images + favicon.png, all custom-made
  videos/    <- desk-banner.mp4, brand-story.mp4, product-highlights.mp4

  static/
    faq.html, shipping.html, privacy.html, sizing-guide.html, care-guide.html, sitemap.html
    (plain HTML pages - no PHP - but share the same header/footer/CSS as the rest of the site)

  wiki/
    help1.php - help7.php   <- help center: getting started, cart/checkout, account, reviews, themes,
                                updating content, and technical documentation (database design +
                                front-end architecture)

  admin/
    login.php            <- admin login
    dashboard.php          <- stats + a simple bar chart
    products.php             <- add/edit/delete products, reassign categories, set sale prices
    categories.php             <- add/rename/delete categories, shows product count per category
    users.php                  <- enable/disable user accounts
    orders.php                   <- view + update order status
    templates.php                  <- pick the site-wide template (Regular/Dark/Retro)
    monitor.php                      <- site status / health check page
    help.php                           <- admin-facing documentation (how to use this panel)
    includes/header.php, footer.php      <- mirrors the public site's header/footer (same CSS classes,
                                             same active theme) so the admin panel looks like part of
                                             the same site instead of a separate, mismatched tool
```

## 2. Installing on a new server

1. Log in to your hosting control panel and create a MySQL database and a database user with a password.
   Note down: host, db name, db username, db password.
2. Import `database.sql` using phpMyAdmin (Import tab) OR from terminal:
   ```
   mysql -u yourusername -p yourdbname < database.sql
   ```
3. Copy `config.example.php` to `config.php` (the real `config.php`, with actual credentials in it, is
   deliberately excluded from git via `.gitignore` - see section 7) and fill in:
   ```php
   $db_host = "localhost";
   $db_user = "your_db_username";
   $db_pass = "your_db_password";
   $db_name = "your_db_name";
   ```
   and set the `BASE_URL` constant to match the folder the site lives in - it must start with a
   leading slash and have no trailing slash (e.g. `/genxcoding`, or `/Project/genxcoding` for a
   nested folder). See the comment above it in `config.example.php` for more examples - getting this
   wrong is the most common reason a freshly-deployed copy of this site shows broken CSS/links.
4. Generate a real admin password hash (the one shipped in `database.sql` is a starting default):
   ```
   php -r "echo password_hash('yourNewPassword', PASSWORD_DEFAULT) . PHP_EOL;"
   ```
   Copy the output and update the `admin` row's `password` column in the `users` table via phpMyAdmin.
5. Upload the whole `genxcoding` folder to the host's public folder (e.g. `public_html`).
6. Visit the site's URL - it should load. Visit `/admin/login.php` to test the admin panel.

## 3. Local development

Use XAMPP (or MAMP/WAMP) to test changes locally before uploading anywhere:
1. Put this folder in `htdocs/genxcoding`, start Apache + MySQL.
2. Create a database via `http://localhost/phpmyadmin`, import `database.sql`.
3. Copy `config.example.php` to `config.php` and set `$db_host = "localhost"; $db_user = "root";
   $db_pass = ""; $db_name = "genxcoding";` (XAMPP defaults) and `define('BASE_URL', '/genxcoding');`.
4. Test everything at `http://localhost/genxcoding/` before uploading anywhere.

## 4. Adding real content (non-programmer friendly)

Full step-by-step instructions for adding products, images, and videos - written for whoever runs the
store day to day, not just developers - live in the site itself:
- **`/wiki/help6.php`** - "Updating Site Content" (products, images, videos)
- **`/wiki/help7.php`** - "Technical Documentation" (database design, front-end architecture)
- **`/admin/help.php`** - what every admin panel screen does

Quick summary: product text/prices/sale prices/category are all set in Admin > Manage Products (except
name/description/image, which are only editable at creation time or via phpMyAdmin); images just need
to be uploaded to `/images` with the filename typed into that same form; videos go in `/videos` and the
`<video src="...">` path gets updated wherever that video is embedded.

## 5. Site-wide templates

The admin (not individual visitors) controls the site's look from **Admin > Site Template**
(`admin/templates.php`), choosing between Regular, Dark, and Retro. The Regular template additionally
auto-adapts to a visitor's OS dark-mode setting via CSS `prefers-color-scheme`, independent of the
admin's chosen template. See `wiki/help5.php` for the full explanation. The admin panel itself uses the
exact same CSS classes and reads the same active template, so it's never visually out of sync with the
public storefront.

## 6. Videos

| File | Goes on | What it shows |
|---|---|---|
| `videos/desk-banner.mp4` | Homepage hero | A short looping background clip (autoplay, muted, no controls) showing the desk-setup aesthetic |
| `videos/brand-story.mp4` | About page | A short brand/marketing video |
| `videos/product-highlights.mp4` | About page | A look at a few featured products |

To swap one out, just replace the file (keep the same filename), or update the matching
`<video src="...">` path if renaming it.

## 7. Known limitations

- No real payment processing (checkout records the order but doesn't charge a card)
- Contact form saves messages to a local text file instead of sending real email (many shared hosting
  plans block PHP's `mail()` function)
- Admin's "edit product" form updates price, sale price, and category right in the product list -
  name/description/image edits after a product is created still need phpMyAdmin for now (documented
  in `admin/help.php`)
- SQL queries use `mysqli_real_escape_string()` for injection protection; prepared statements
  (`mysqli_prepare`) would be a stronger next step if extending this project further
- `config.php` (real DB credentials) is excluded from git via `.gitignore` - only
  `config.example.php` (a safe template with placeholder values) gets committed. Don't remove that
  `.gitignore` line or force-add `config.php` - that would publish the real database password.

## 8. Default admin login
Username: `admin`
Password: `admin123`
