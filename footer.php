<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Freeshifter
 */

namespace reb_livestream_theme;

?>
</main>
<footer class="footer border-t border-logo p-8 text-xs text-logo flex justify-between mt-48">
    <div class="w-full md:w-auto">
        &copy; <?php echo date( 'Y' ) ?> <span class="uppercase">european real estate brand institute</span>
    </div>
    <div class="w-full md:w-auto text-right">
        made by <a href="https://poppgerhard.at" target="_blank">poppgerhard</a>
    </div>
</footer>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<?php wp_footer(); ?>
</body>
</html>
