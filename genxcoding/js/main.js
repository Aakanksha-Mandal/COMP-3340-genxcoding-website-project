// main.js
// Handles the mobile hamburger menu, the scroll-position bug fix,
// and a couple small UI touches. Plain JS, no frameworks.

// ---- fix for the "header jumps down / footer floats up" bug ----
// Some browsers try to restore your previous scroll position when you
// navigate between pages (especially noticeable going from a long page to
// a short one, like About). Forcing manual scroll restoration + scrolling
// to top stops that from happening.
if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}
window.scrollTo(0, 0);

document.addEventListener('DOMContentLoaded', function () {

    // ---- mobile menu toggle ----
    var menuBtn = document.getElementById('menuBtn');
    var nav = document.getElementById('mainNav');

    if (menuBtn && nav) {
        menuBtn.addEventListener('click', function () {
            nav.classList.toggle('show');
        });
    }

    // ---- confirm before removing cart items (extra safety net) ----
    var removeLinks = document.querySelectorAll('a[href*="cart.php?remove="]');
    removeLinks.forEach(function (link) {
        link.addEventListener('click', function (e) {
            var sure = confirm('Remove this item from your cart?');
            if (!sure) {
                e.preventDefault();
            }
        });
    });

    // ---- little "back to top" behaviour on double-clicking the footer ----
    var footer = document.querySelector('.site-footer');
    if (footer) {
        footer.addEventListener('dblclick', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ---- log rating changes on product page (dev debugging leftover, harmless) ----
    var ratingSelect = document.querySelector('select[name="rating"]');
    if (ratingSelect) {
        ratingSelect.addEventListener('change', function () {
            console.log('You selected a rating of ' + ratingSelect.value);
        });
    }

    // ---- add-to-cart without a full page reload ----
    // Only kicks in for logged-in users (data-logged-in="1"). Guests still
    // get the normal form submit, which server-side redirects them to login -
    // no point faking an AJAX success for someone who isn't logged in yet.
    var cartForm = document.getElementById('add-to-cart-form');
    var cartBtn = document.getElementById('add-to-cart-btn');
    if (cartForm && cartForm.dataset.loggedIn === '1') {
        cartForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var originalText = cartBtn.textContent;
            cartBtn.disabled = true;
            cartBtn.textContent = 'Adding...';

            fetch('ajax-add-to-cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams(new FormData(cartForm)) // serializes the form fields (product_id, chosen_option, qty) into a POST body
            })
            .then(function (res) { return res.json(); }) // ajax-add-to-cart.php always responds with JSON
            .then(function (data) {
                // server says we're not actually logged in (session could have
                // expired) - send the browser to login instead of pretending it worked
                if (data.redirect) {
                    window.location = data.redirect;
                    return;
                }
                if (data.success) {
                    // update the little cart count badge in the header without reloading anything
                    var countEl = document.getElementById('cart-count');
                    if (countEl) { countEl.textContent = data.count; }
                    cartBtn.textContent = 'Added \u2713';
                    setTimeout(function () {
                        cartBtn.disabled = false;
                        cartBtn.textContent = originalText;
                    }, 1200);
                } else {
                    alert(data.message || 'Could not add to cart.');
                    cartBtn.disabled = false;
                    cartBtn.textContent = originalText;
                }
            })
            .catch(function () {
                // network hiccup - just fall back to a normal form submit
                cartForm.submit();
            });
        });
    }

    // ---- newsletter "subscribed" confirmation ----
    // newsletter.php redirects back here with ?subscribed=1 - this works the
    // same way on both the PHP pages and the plain static HTML pages, since
    // it's just reading the URL rather than needing server-side PHP.
    var params = new URLSearchParams(window.location.search);
    if (params.get('subscribed') === '1') {
        var newsletterBox = document.getElementById('newsletter');
        if (newsletterBox) {
            var msg = document.createElement('p');
            msg.className = 'newsletter-success';
            msg.textContent = 'Thanks for subscribing!';
            newsletterBox.appendChild(msg);
        }
        // clean the query param out of the URL so the message doesn't
        // reappear if the person refreshes the page later
        params.delete('subscribed');
        var newSearch = params.toString();
        var cleanUrl = window.location.pathname + (newSearch ? '?' + newSearch : '') + window.location.hash;
        window.history.replaceState({}, document.title, cleanUrl);
    }

    // ---- sync the header on genuinely-static HTML pages ----
    // The pages in /static (faq.html, shipping.html, etc.) have no PHP at
    // all, so they always hardcode "Login/Register" in their markup - they
    // have no way to check whether the visitor is actually logged in. This
    // fetches the real session state from session-status.php and, only on
    // those static pages (marked with data-static-auth), rewrites the
    // account links to match what a PHP page would have shown - "Hi, user /
    // Cart (n) / My Account / Logout" if logged in, otherwise leaves the
    // existing Login/Register links alone.
    var staticAuthList = document.querySelector('.auth-links[data-static-auth]');
    if (staticAuthList) {
        fetch('../session-status.php')
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.loggedIn) {
                    staticAuthList.innerHTML =
                        '<li class="welcome-text">Hi, ' + escapeHtml(data.username) + '</li>' +
                        '<li><a href="../cart.php" aria-label="Cart" title="Cart"><i class="fa-solid fa-cart-shopping"></i> <span class="link-text">Cart</span> <span class="cart-count-badge">(' + data.cartCount + ')</span></a></li>' +
                        '<li><a href="../account.php" aria-label="My Account" title="My Account"><i class="fa-solid fa-user"></i> <span class="link-text">My Account</span></a></li>' +
                        '<li><a href="../logout.php" aria-label="Logout" title="Logout"><i class="fa-solid fa-right-from-bracket"></i> <span class="link-text">Logout</span></a></li>';
                }
                // if not logged in, the Login/Register markup already in the
                // page is correct as-is - nothing to change
            })
            .catch(function () {
                // if the fetch fails for any reason, just leave the default
                // Login/Register links showing rather than break the page
            });
    }

    // small helper used above so a username can never accidentally inject HTML
    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // ---- sync the theme on genuinely-static HTML pages ----
    // Same reasoning as the auth-sync block above: the pages in /static have
    // no PHP, so they can't read site_settings to know which of the 3
    // templates (Regular/Dark/Retro) the admin currently has active - they
    // just hardcode regular.css. This fetches the real active theme from
    // theme-status.php and swaps the stylesheet to match, so switching the
    // site-wide template in the admin panel doesn't leave static pages like
    // FAQ or Shipping stuck looking like "Regular" while every other page
    // on the site changes. There's a brief flash of the Regular look before
    // this runs, same trade-off already accepted for the auth-links sync.
    var themeCss = document.getElementById('themeCss');
    if (themeCss) {
        fetch('../theme-status.php')
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.theme && data.theme !== 'regular') {
                    themeCss.href = '../css/' + data.theme + '.css?v=3';
                }
            })
            .catch(function () {
                // if the fetch fails, just leave the default Regular
                // stylesheet in place rather than break the page
            });
    }

});
