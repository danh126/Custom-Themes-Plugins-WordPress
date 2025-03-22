<?php if (WC()->cart->is_empty()) : ?>
    <div class="custom-cart-empty">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/empty-cart.png" alt="Empty Cart">
        <p>giỏ hàng trống!</p>
        <a href="<?php echo wc_get_page_permalink('shop'); ?>" class="button">Start Shopping</a>
    </div>
<?php endif; ?>