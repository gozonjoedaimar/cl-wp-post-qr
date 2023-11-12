<?php

use SimpleSoftwareIO\QrCode\Generator;

if ( ! function_exists('cl_qr_route') ) {
   /**
    * QR code route
    * 
    * usage: /cl-post-qr/{post_id}
    */
    function cl_qr_route() {
        // Your code here
        $request_uri = $_SERVER['REQUEST_URI'];
        $paths = explode('/', $request_uri);
        $post_id = array_pop($paths);
        $page = array_pop($paths);

        $as_png = extension_loaded('imagick');
    
        if ($page === 'cl-post-qr') {
            $link = cl_get_post_link($post_id);

            if (empty($link)) {
               status_header(404);
               nocache_headers();
               return;
            }

            // Default format
            $ext = "svg";
            $content_type = "image/svg+xml";

            // Build QR code
            $qr_generator = new Generator;
            $qr = $qr_generator->size(254);

            if ($as_png) {
               $ext = "png";
               $content_type = "image/png";
               $qr = $qr->format('png');
            }

            // Generate QR code
            $qr = $qr->generate($link);

            // Set headers
            header('Content-Type: '.$content_type);

            if (isset($_GET['attachment'])) {
               header('Content-Disposition: attachment; filename="qr.'.$ext.'"');
            }

            echo $qr;
            die();
        }
    }
    add_action('parse_query', 'cl_qr_route');
}

if ( ! function_exists('cl_get_post_link') ) {
   /**
    * Get post link
    */
   function cl_get_post_link($post_id) {
      $post = get_post($post_id);
      if ($post) {
         return get_permalink($post);
      }
      return "";
   }
}
