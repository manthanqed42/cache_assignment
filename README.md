# Cache Assignment

## Cache API in Drupal

### Exercise 1: Add Caching on a Custom Block

#### Activity 1: Display the Last 3 Articles with Caching

**Steps:**
1. Create a custom block displaying the title of the last 3 articles created on the site as an item list.
2. The block output should be cached to render the content efficiently.
3. The cache should be invalidated automatically when the title of one of the nodes is updated.
4. Add `#cache` to the block with the node IDs of the articles being rendered.

#### Activity 2: Extend the Block to Include User Email

**Steps:**
1. Extend the block created above to also render the email address of the current user.
2. Add cache context for the current user in the block's `build()` function to ensure proper caching behavior.

### Exercise 2: Custom Cache Context

**Steps:**
1. Add a "Preferred Category" field (taxonomy term reference) on the user profile.
2. Create an "Article" content type and include the "Category" field.
3. Create a custom block that displays articles from the preferred category selected in the user account.
4. Handle cache scenarios using a custom cache context to ensure proper cache invalidation when a user updates their preferred category.

## Exercise 3: Setup Varnish and Memcache on Local / Work Around It on Servers

#### Steps:
1. Install Varnish by following the steps in this document: [Configuring Varnish for Drupal](https://www.varnish-software.com/developers/tutorials/configuring-varnish-drupal/).
2. Encountered error: `Error: -a arguments localhost:8443 and localhost:8443 have same address.` Resolved using the following steps:
   - Changed port from `8443` to `8444`.
   - Removed `localhost:8444` from `/etc/systemd/system/varnish.service`.
   - Restarted the Varnish service.
3. Installed and enabled the Purge module.
4. Configured the purger on the Drupal website.
