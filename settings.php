<?php
add_action('admin_menu', 'wp_hashed_ids_add_admin_menu');
add_action('admin_init', 'wp_hashed_ids_settings_init');

function wp_hashed_ids_add_admin_menu() { 
	add_options_page('WP Hashed Ids', 'WP Hashed Ids', 'manage_options', 'wp_hashed_ids', 'wp_hashed_ids_options_page');
}

function wp_hashed_ids_settings_init() { 
	register_setting('wpHashSettings', 'wp_hashed_ids_settings');

	add_settings_section(
		'wp_hashed_ids_wpHashSettings_section', 
		__('Settings', 'wordpress'), 
		'wp_hashed_ids_settings_section_callback', 
		'wpHashSettings'
	);

	add_settings_field( 
		'wp_hashed_ids_min_length', 
		__('Minimum hash length', 'wordpress'), 
		'wp_hashed_ids_min_length_render', 
		'wpHashSettings', 
		'wp_hashed_ids_wpHashSettings_section' 
	);

	add_settings_field( 
		'wp_hashed_ids_alphabet', 
		__('Alphabet (at least 16 characters)', 'wordpress'), 
		'wp_hashed_ids_alphabet_render', 
		'wpHashSettings', 
		'wp_hashed_ids_wpHashSettings_section' 
	);

	add_settings_field( 
		'wp_hashed_ids_salt', 
		__('Salt', 'wordpress'), 
		'wp_hashed_ids_salt_render', 
		'wpHashSettings', 
		'wp_hashed_ids_wpHashSettings_section' 
	);
}

function wp_hashed_ids_min_length_render() { 
	$options = get_option('wp_hashed_ids_settings');
	?>
	<input type='number' name='wp_hashed_ids_settings[wp_hashed_ids_min_length]' value='<?php echo $options['wp_hashed_ids_min_length']; ?>'>
	<?php
}

function wp_hashed_ids_salt_render() { 
	$options = get_option('wp_hashed_ids_settings');
	?>
	<input type='text' name='wp_hashed_ids_settings[wp_hashed_ids_salt]' value='<?php echo $options['wp_hashed_ids_salt']; ?>'>
	<?php
}


function wp_hashed_ids_alphabet_render() { 
	$options = get_option('wp_hashed_ids_settings');
	?>
	<input type='text' name='wp_hashed_ids_settings[wp_hashed_ids_alphabet]' value='<?php echo $options['wp_hashed_ids_alphabet']; ?>'>
	<?php
}

function wp_hashed_ids_settings_section_callback() { 
	echo __("<p>Please set here the <strong>minimum hash length</strong>, the <strong>alphabet</strong> (set of characters used to compose the hash) and the <strong>salt</strong> (a random string used to generate the hash).</p><p><strong>None of this settings is mandatory</strong>, please if you don't know what you are doing, just leave everything blank.</p><p><strong>Please notice that changing any the settings will change all your website's URLs!</strong></p>", 'wordpress');
}

function wp_hashed_ids_options_page() {
	?>
	<div class="wrap">
	<h1>WP Hashed Ids</h1>
	<form action='options.php' method='post'>
		<?php
		settings_fields('wpHashSettings');
		do_settings_sections('wpHashSettings');
		submit_button();
		?>
	</form>
	</div>
	<?php
}
