<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="index.html"><img src="assets/images/logo/logo.png" alt="Logo" srcset=""></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">

            <!--SİDEBAR-->
			<?php
				global $keybmin;

				$menu_html = [
					'ul'                     => '<ul class="menu">',
					'/ul'                    => '</ul>',
					'li'                     => '<li class="sidebar-item %s">',
					'/li'                    => '</li>',
					'a'                      => '<a href="%s" class="sidebar-link">',
					'/a'                     => '</a>',
					'sub_content'            => '<li class="sidebar-item has-sub %s">',
					'/sub_content'           => '</li>',
					'menu_name'              => '<span>%s</span>',
					'menu_active_class_name' => 'active',
				];

				$sub_menu_html = [
					'ul'           => '<ul class="submenu">',
					'/ul'          => '</ul>',
					'li'           => '<li class="submenu-item">',
					'/li'          => '</li>',
					'a'            => '<a href="%s" class="sidebar-link">',
					'/a'           => '</a>',
					'sub_content'  => '<li class="sidebar-item has-sub">',
					'/sub_content' => '</li>',
					'menu_name'    => '<span>%s</span>',
				];

				$menu_array = $keybmin->get_sidebar_array();
				print_r($keybmin->get_sidebar_html($menu_array, $keybmin->page, $menu_html, $sub_menu_html));
			?>
            <!--SİDEBAR-->
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>