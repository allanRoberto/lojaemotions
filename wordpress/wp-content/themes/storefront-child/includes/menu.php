<?php require(get_template_directory() . '-child/includes/menu_class.php'); ?>

<div class="navbar">											<!-- Div Navbar -->
    <button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
    </button>
    <div class="navbar-inner">									<!-- Navbar-inner -->
        <div class="nav-collapse collapse">						<!-- Nav-collapse -->
            <nav class="menu">									<!-- Main nav -->
				<?php wp_nav_menu(array(
                'container'       => false,
                'items_wrap'      => '<ul id="%1$s" class="%2$s nav">%3$s</ul>',
                'walker'          => new twitter_bootstrap_nav_walker
                ));
                ?>
            </nav>												<!-- End Nav Tag -->
        </div>													<!-- End Nav-collapse -->
    </div>														<!-- End Navbar-inner -->
</div>	