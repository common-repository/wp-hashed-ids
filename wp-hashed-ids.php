<?php
/**
 * @package WP Hash IDs
 * @version 2.0
 */
/*
Plugin Name: WP Hash IDs
Plugin URI: http://www.alessandrobalasco.com
Description: Adds the ability to crypt your URLs from something like http://www.example.com/1434 to http://www.example.com/B7j1rPk8. You can still use all the other permalinks parameters (like category slug, post slug, date, ecc), so you can have http://www.example.com/2012/10/10/B7j1rPk8, or http://www.example.com/B7j1rPk8/my-post-slug
Author: Alessandro Balasco
Version: 2.0
*/

require_once "settings.php";

// using composer in a WordPress plugin is hell... so let's just pretend to be in 1999
if (!class_exists('\\Hashids\\Hashids')) {
	require_once('hashids.php/lib/Hashids/HashGenerator.php');
	require_once('hashids.php/lib/Hashids/Hashids.php');
}

function hashed_id() {
    global $wp_rewrite;
    add_rewrite_tag('%hashed_id%','([^/]+)');
    $permalink = $wp_rewrite->permalink_structure;
    if (!empty($permalink) && false !== strpos( $permalink, '%hashed_id%' )) {
        add_filter('pre_post_link', '_hashed_id_post_link', 10, 2);
        add_filter('parse_request', '_hashed_id_parse_request');
    }
}

function _hashed_id_default_options() {
    return array(
        'wp_hashed_ids_min_length' => 8,
        'wp_hashed_ids_salt' => AUTH_KEY,
        'wp_hashed_ids_alphabet' => ''
    );
}

function _hashed_id_get_instance() {
    $options = get_option('wp_hashed_ids_settings', _hashed_id_default_options());
    $min_length = is_numeric($options['wp_hashed_ids_min_length']) ? $options['wp_hashed_ids_min_length'] : 8;
    $salt = $options['wp_hashed_ids_salt'] != '' ? $options['wp_hashed_ids_salt'] : AUTH_KEY;
    $alpha_length = strlen($options['wp_hashed_ids_alphabet']);
    $alphabet = (alpha_length >= $min_length || alpha_length < 16) ? $options['wp_hashed_ids_alphabet'] : '';
	return new Hashids\Hashids($salt, $min_length, $alphabet);
}

function _hashed_id_post_link($permalink, $post) {
    $hashids = _hashed_id_get_instance();
    return str_replace('%hashed_id%', $hashids->encode((int)$post->ID), $permalink);
}

function _hashed_id_parse_request($qv) {
    $hashed_id = $qv->query_vars['hashed_id'];
    if (strlen($hashed_id) > 0) {
        $hashids = _hashed_id_get_instance();
        $id = $hashids->decode($hashed_id);
        if (isset($id[0]) && is_numeric($id[0])) {
            $qv->query_vars['p'] = $id[0];
        } else {
            $qv->query_vars['pagename'] = $hashed_id;
        }
    }
    return $qv;
}
add_action('init', 'hashed_id');

function hashed_ids_activate_plugin() {
    global $wp_rewrite;
    if ($wp_rewrite->using_permalinks()) {
        $wp_rewrite->set_permalink_structure(
            str_replace('%post_id%', '%hashed_id%', $wp_rewrite->permalink_structure)
        );
    }
    flush_rewrite_rules(false);
}
register_activation_hook(__FILE__, 'hashed_ids_activate_plugin');

function hashed_ids_deactivate_plugin() {
    global $wp_rewrite;
    if ($wp_rewrite->using_permalinks()) {
        $wp_rewrite->set_permalink_structure(
            str_replace('%hashed_id%', '%post_id%', $wp_rewrite->permalink_structure)
        );
    }
    flush_rewrite_rules(false);
}
register_deactivation_hook(__FILE__, 'hashed_ids_deactivate_plugin');
