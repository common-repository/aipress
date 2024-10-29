<?php
class  WP_AiPress_General_Functions extends WP_REST_Controller{
    function sanitizeArr($req)
    {
        $data = array();
        $request2 = (array)$req;
        $request = array();
        foreach($request2 as $req2) {
            if (is_array($req2)) {
                foreach($req2 as $req2_k => $req2_v) {
                    if ($req2_k == 'JSON') {
                        $request = $req2_v;
                    }
                }
            }
        }

        foreach ($request as $k => $b){
            $data[$k] = sanitize_text_field($b);
        }
        return $data;
    }

    function imageNameCreator($type): string
    {
        $extention = '.jpg';
        if($type == "image/png"){
            $extention = '.png';
        }
        else if($type == "image/gif"){
            $extention = '.gif';
        }
        else if($type == "image/bmp"){
            $extention = '.bmp';
        }
        else if($type == "image/webp"){
            $extention = '.webp';
        }
        else if($type == "image/svg+xml"){
            $extention = '.svg';
        }
        return sha1(microtime()).$extention;
    }

    function justImageUpload($image,$type){
        $url = $image;
        $title = $type;
        $alt_text = $type;

        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

// sideload the image --- requires the files above to work correctly
        $src = media_sideload_image( $url, null, null, 'src' );

// convert the url to image id
        $image_id = attachment_url_to_postid( $src );

        return [
           'id' => $image_id,
            'path' => wp_get_attachment_image_url( $image_id ,'' )
        ];
    }

    function urlDecoder($url){
        $decode =preg_replace("/[^A-Za-z]+/", "", $url);
        return  substr( $decode, -5);;

    }

    function imageUploader($image,$type,$post_id)
    {
        $image_url = $image; // Define the image URL here
        $image_name = $this->imageNameCreator($type);
        $upload_dir = wp_upload_dir(); // Set upload folder
        $image_data = file_get_contents($image_url); // Get image data
        $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
        $filename = basename($unique_file_name); // Create image file name
        if( wp_mkdir_p( $upload_dir['path'] ) ) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }
        // Create the image  file on the server
        file_put_contents( $file, $image_data );

        // Check image file type
        $wp_filetype = wp_check_filetype( $filename, null );

        // Set attachment data
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name( $filename ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        // Create the attachment
        $attach_id = wp_insert_attachment( $attachment, $file, $post_id );

        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

        // Assign metadata to attachment
        wp_update_attachment_metadata( $attach_id, $attach_data );

        // And finally assign featured image to post
        set_post_thumbnail( $post_id, $attach_id );



    }


    public function get_api_permission(): bool
    {
        return current_user_can('edit_posts');
    }

    public function get_api_permission_public(): bool
    {
        return true;
    }

}