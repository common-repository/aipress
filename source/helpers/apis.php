<?php
include AIPRESS_ROOT.'source/helpers/apis/general.php';
include AIPRESS_ROOT.'source/helpers/apis/settings.php';
include AIPRESS_ROOT.'source/helpers/apis/posts.php';
include AIPRESS_ROOT.'source/helpers/apis/images.php';

// Executes
new WP_AiPress_Settings_Rest_Route();
new WP_AiPress_Posts_Rest_Route();
new WP_AiPress_Images_Rest_Route();