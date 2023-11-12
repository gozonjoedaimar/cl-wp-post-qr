<?php 

if ( ! function_exists( 'cl_metabox' ) ) {
   /**
    * Add wp metabox
    */
   function cl_metabox() 
   {
      add_meta_box(
         'cl_post_qr',
         'QR Code',
         'cl_post_qr_metabox',
         'product',
         'side'
      );
   }
   add_action( 'add_meta_boxes', 'cl_metabox' );
}

if ( ! function_exists( 'cl_post_qr_metabox' ) ) {
   /**
    * Metabox content
    * 
    * @param WP_Post $post
    */
   function cl_post_qr_metabox( $post ) 
   {
      global $pagenow;
      if ('post-new.php' == $pagenow) {
         echo "<i>Save post to generate qr code.</i>";
         return;
      }

      $link = cl_get_qr_link($post->ID);
      ?><div id="cl_qr_metabox_content">
         <p>
            <img src="<?= $link ?>" alt="Post QR Image" />
            <a href="<?= $link . "?attachment=1" ?>">Download</a>
         </p>
      </div><?php
   }
}

if ( ! function_exists( 'cl_get_qr_link' ) ) {
   /**
    * Get qr link
    */
    function cl_get_qr_link($post_id) {
        $site_link = get_site_url();
        $qr_link = $site_link . "/cl-post-qr/" . $post_id;
        return $qr_link;
    }
}