# Cache Assignment

This module demonstrates the use of the **Cache API** in Drupal by implementing caching mechanisms in custom blocks.

## Exercise 1: Add Caching to a Custom Block

### Activity 1: Cache Custom Block Rendering

Create a custom block that displays the titles of the last three articles created on the site as an item list. The block output should be cached and automatically invalidated when the title of one of the nodes is updated.

#### Steps:
1. Create a custom block to render the titles of the last three articles.
2. Add `#cache` metadata to store the block output and associate it with the node IDs of the articles being rendered.

### Activity 2: Extend Block to Include User Email

Extend the custom block to also display the email address of the currently logged-in user.

#### Steps:
1. Modify the block's `build()` function to include the email address of the current user.
2. Add cache context for the current user to ensure the block is cached separately per user.

## Exercise 2: Implement a Custom Cache Context

### Objective
Enhance caching by implementing a **custom cache context** based on a user’s preferred article category.

#### Steps:
1. Add a **Preferred Category** field (taxonomy term reference) to user profiles.
2. Modify the **Article** content type to include the **Category** field.
3. Create a custom block that displays articles from the preferred category selected in the user’s profile.
4. Implement a custom cache context to ensure the block updates when a user’s preferred category changes.

## Notes
- The Cache API is essential for optimizing performance while ensuring dynamic content updates.
- Cache contexts help vary cached content based on factors like user identity, user roles, or custom-defined conditions.
- Implementing cache tags ensures that content updates trigger cache invalidation when necessary.

### Additional References
- [Drupal Cache API](https://www.drupal.org/docs/8/api/cache-api/cache-api-overview)
- [Cache Contexts](https://www.drupal.org/docs/8/api/cache-api/cache-contexts)
- [Using #cache in Render Arrays](https://www.drupal.org/docs/8/api/render-api/cacheability-of-render-arrays)