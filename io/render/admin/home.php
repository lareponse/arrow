<?php
echo '<section class="admin-homepage">';
require_once 'app/io/render/admin/hero_slide/hero_slide.php';
echo '</section>';

echo '<section class="admin-homepage">';
require_once 'app/io/render/admin/service/service.php';
echo '</section>';

echo '<section class="admin-homepage">';
require_once 'app/io/render/admin/benefit/benefit.php';
echo '</section>';
?>


<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
