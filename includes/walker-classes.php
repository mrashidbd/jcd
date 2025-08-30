<?php
/**
 * Custom Walker Classes for Navigation
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom Navigation Walker for Desktop Menu
 */
class Ducsu_Nav_Walker extends Walker_Nav_Menu {

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . ' class="text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-2 px-4 rounded-lg hover:bg-gray-50">';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') .
            apply_filters('the_title', $item->title, $item->ID) .
            (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Mobile Navigation Walker
 */
class Ducsu_Mobile_Nav_Walker extends Walker_Nav_Menu {

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . ' class="block text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-3 px-4 rounded-lg hover:bg-gray-50">';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') .
            apply_filters('the_title', $item->title, $item->ID) .
            (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Fallback Menu Functions
 */

/**
 * Fallback Menu for Desktop
 */
function ducsu_jcd_fallback_menu() {
    echo '<ul class="flex items-center space-x-8">';
    echo '<li><a href="/" class="text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-2 px-4 rounded-lg hover:bg-gray-50">হোম</a></li>';
    echo '<li><a href="/central-panel" class="text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-2 px-4 rounded-lg hover:bg-gray-50">কেন্দ্রীয় প্যানেল</a></li>';
    echo '<li><a href="/hall-panels" class="text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-2 px-4 rounded-lg hover:bg-gray-50">হল প্যানেল</a></li>';
    echo '<li><a href="/manifesto" class="text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-2 px-4 rounded-lg hover:bg-gray-50">ইশতেহার</a></li>';
    echo '<li><a href="/contact" class="text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-2 px-4 rounded-lg hover:bg-gray-50">যোগাযোগ</a></li>';
    echo '</ul>';
}

/**
 * Fallback Menu for Mobile
 */
function ducsu_jcd_mobile_fallback_menu() {
    echo '<ul class="mobile-nav-list space-y-2">';
    echo '<li><a href="/" class="block text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-3 px-4 rounded-lg hover:bg-gray-50">হোম</a></li>';
    echo '<li><a href="/central-panel" class="block text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-3 px-4 rounded-lg hover:bg-gray-50">কেন্দ্রীয় প্যানেল</a></li>';
    echo '<li><a href="/hall-panels" class="block text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-3 px-4 rounded-lg hover:bg-gray-50">হল প্যানেল</a></li>';
    echo '<li><a href="/manifesto" class="block text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-3 px-4 rounded-lg hover:bg-gray-50">ইশতেহার</a></li>';
    echo '<li><a href="/contact" class="block text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-3 px-4 rounded-lg hover:bg-gray-50">যোগাযোগ</a></li>';
    echo '</ul>';
}

/**
 * Footer Navigation Walker
 */
class Ducsu_Footer_Nav_Walker extends Walker_Nav_Menu {

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . ' class="text-gray-300 hover:text-primary-green transition-colors duration-300 flex items-center">';
        $item_output .= '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        $item_output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>';
        $item_output .= '</svg>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') .
            apply_filters('the_title', $item->title, $item->ID) .
            (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}