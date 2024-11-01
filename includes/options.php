<?php
/**
 * Array of plugin options
 */

$options = array();

$options['general'][] = array(
    "name"    => __( "What to Share", "sti" ),
    "desc"    => '',
    "type"    => "heading"
);

$options['general'][] = array(
    "name"  => __( "Selector", "sti" ),
    "desc"  => __( "Selectors for images. Separate several selectors with commas.", "sti" ),
    "id"    => "selector",
    "value" => 'img',
    "type"  => "text"
);

$options['general'][] = array(
    "name"    => __( "Display Settings", "sti" ),
    "desc"    => '',
    "type"    => "heading"
);

$options['general'][] = array(
    "name"  => __( "Minimal width", "sti" ),
    "desc"  => __( "Minimum width of image to use it for sharing.", "sti" ),
    "id"    => "minWidth",
    "value" => '200',
    "type"  => "text"
);

$options['general'][] = array(
    "name"  => __( "Minimal height", "sti" ),
    "desc"  => __( "Minimum height of image to use it for sharing.", "sti" ),
    "id"    => "minHeight",
    "value" => '200',
    "type"  => "text"
);

$options['general'][] = array(
    "name"  => __( "Facebook App ID", "sti" ),
    "desc"  => __( "For better facebook sharing create your app and write here it's id.", "sti" ) . ' <a href="https://share-this-image.com/docs/#!/app_id" target="_blank">' . __( "Learn more", "sti" ) . '</a>',
    "id"    => "fb_app",
    "value" => '',
    "type"  => "text"
);

$options['general'][] = array(
    "name"  => __( "Facebook Sharing Type", "sti" ),
    "desc"  => __( "Choose between share or feed dialog.", "sti" ),
    "id"    => "fb_type",
    "value" => 'share',
    "type"  => "radio",
    'choices' => array(
        'share' => __( "Share Dialog. Gives people the ability to publish an individual story to their timeline, a friend's timeline, a group, or in a private message on Messenger )", "sti" ),
        'feed' => __( "Feed Dialog. People can publish individual stories to their timeline. This includes captions that your app manages and a personal comment from the person sharing the content.", "sti" )
    )
);

$options['general'][] = array(
    "name"  => __( "Twitter via", "sti" ),
    "desc"  => __( "Set twitters 'via' property.", "sti" ),
    "id"    => "twitter_via",
    "value" => '',
    "type"  => "text"
);

$options['general'][] = array(
    "name"  => __( "Share buttons", "sti" ),
    "desc"  => __( "Drag and drop to the right area share buttons that you want to appear for image sharing", "sti" ) . '<br>' . __( "Position of fields shows the position of each share button in the sharing box.", "sti" ),
    "id"    => "primary_menu",
    "value" => "facebook,twitter,google,linkedin,pinterest",
    "choices" => array(
        "facebook"      => __( "Facebook", "sti" ),
        "twitter"       => __( "Twitter", "sti" ),
        "google"        => __( "Google+", "sti" ),
        "linkedin"      => __( "LinkedIn", "sti" ),
        "pinterest"     => __( "Pinterest", "sti" ),
        "tumblr"        => __( "Tumblr", "sti" ),
        "reddit"        => __( "Reddit", "sti" ),
        "digg"          => __( "Digg", "sti" ),
        "delicious"     => __( "Delicious", "sti" ),
        "vkontakte"     => __( "Vkontakte", "sti" ),
        "odnoklassniki" => __( "Odnoklassniki", "sti" )
    ),
    "type"  => "sortable"
);

$options['general'][] = array(
    "name"  => __( "Enable on mobile?", "sti" ),
    "desc"  => __( "Enable image sharing on mobile devices", "sti" ),
    "id"    => "on_mobile",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true' => __( 'On', 'sti' ),
        'false' => __( 'Off', 'sti' )
    )
);

$options['general'][] = array(
    "name"  => __( "Always show?", "sti" ),
    "desc"  => __( "Always show sharing buttons?", "sti" ),
    "id"    => "always_show",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true' => __( 'On', 'sti' ),
        'false' => __( 'Off', 'sti' )
    )
);

$options['general'][] = array(
    "name"  => __( "Use intermediate page.", "sti" ),
    "desc"  => __( "If you have some problems with redirection from social networks to page with sharing image try to switch Off this option.", "sti" ) . '</br>' .
               __( "But before apply it need to be tested to ensure that all work's fine.", 'sti' ),
    "id"    => "sharer",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'sti' ),
        'false' => __( 'Off', 'sti' )
    )
);

$options['content'][] = array(
    "name"    => __( "Default Content", "sti" ),
    "desc"    => '',
    "type"    => "heading"
);

$options['content'][] = array(
    "name"  => __( "Default Title", "sti" ),
    "desc"  => __( "Content for 'Default Title' source.", "sti" ),
    "id"    => "title",
    "value" => '',
    "type"  => "text"
);

$options['content'][] = array(
    "name"  => __( "Default Description", "sti" ),
    "desc"  => __( "Content for 'Default Description' source.", "sti" ),
    "id"    => "summary",
    "value" => '',
    "type"  => "textarea"
);