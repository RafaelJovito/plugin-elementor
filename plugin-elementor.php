<?php
/*
Plugin Name: Plugin Elementor
Description: Descrição plugin.
Version: 1.0
Author:
Author URI:
Text Domain: plugin-elementor
*/

// Funções de ativação e desativação
register_activation_hook( __FILE__, 'plugin_elementor_activate' );
register_deactivation_hook( __FILE__, 'plugin_elementor_deactivate' );

function plugin_elementor_activate() {
    // Tarefas a serem executadas na ativação do plugin.
}

function plugin_elementor_deactivate() {
    // Tarefas a serem executadas na desativação do plugin.
}

// Registra o widget com o Elementor
add_action( 'elementor/widgets/widgets_registered', function() {
    require_once( plugin_dir_path( __FILE__ ) . 'widgets/widget-elementor.php' );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widget_Elementor() );
});