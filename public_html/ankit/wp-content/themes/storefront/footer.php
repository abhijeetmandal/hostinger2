<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'storefront_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="col-full">

			<?php
			/**
			 * Functions hooked in to storefront_footer action
			 *
			 * @hooked storefront_footer_widgets - 10
			 * @hooked storefront_credit         - 20
			 */
			do_action( 'storefront_footer' );
			?>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->

	<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

<script>
  window.intergramId = "814973053";
  window.intergramCustomizations = {
    closedChatAvatarUrl: 'https://ankit.crushit.fit/assets/images/ankit.jpg',
    introMessage: 'Hello! How can I help you?',
    closedStyle: 'button', // button or chat
    titleClosed: 'Click to chat!',
    titleOpen: 'Have any question?',
    introMessage: 'Hello! How can I help you?',
    autoResponse: 'Currently replying in 20 minutes',
    autoNoResponse: 'We are still checking...',

    // Can be any css supported color 'red', 'rgb(255,87,34)', etc
    //mainColor: "#E91E63",
    //mainColor: url("https://ankit.crushit.fit/assets/images/avatar.jpeg"),
    alwaysUseFloatingButton: false // use the mobile floating button also on large screens
  };

</script>
<script id="intergram" type="text/javascript" src="https://ankit.crushit.fit/wp-includes/js/intergram_widget_1.js"></script>



</body>
</html>
