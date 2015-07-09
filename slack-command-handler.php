<?php
/**
 * Slack slash command handler.
 *
 * Receives a payload from a slash command and calls an Ansible playbook. This
 * should be placed in a web-accessible location and registered with Slack.
 *
 * @link https://api.slack.com/slash-commands
 */

define( 'SLACK_TOKEN',            '' );
define( 'ANSIBLE_INVENTORY_PATH', '/srv/www/ansible' );
define( 'ANSIBLE_PLAYBOOK_PATH',  '/srv/www/ansible' );

// Ensure this is a POST request with a valid token.
if ( empty( $_POST['token'] ) || SLACK_TOKEN !== $_POST['token'] ) {
	exit( 1 );
}

// @todo Consider validating users here.

list( $environment, $branch ) = explode( ' ', $_POST['text'] );

// Validate the environment.
$environments = array( 'production', 'staging' );
if ( ! in_array( $environment, $environments ) ) {
	exit( 'Invalid environment. Choose `production` or `staging`.' );
}

// Sanitize the branch to deploy.
if ( 'production' === $environment ) {
	$branch = 'production';
} elseif ( empty( $branch ) ) {
	$branch = 'staging';
}

// Connection details are managed in Ansible inventory.
$command = sprintf(
	'sudo -H -u deploy /usr/bin/ansible-playbook -i %1$s/%2$s%3$s %4$s/deploy.yml',
	rtrim( ANSIBLE_INVENTORY_PATH, '/' )
	$environment,
	'staging' === $environment ? ' -c local' : '',
	rtrim( ANSIBLE_PLAYBOOK_PATH, '/' )
);

set_time_limit( 0 );
exec( escapeshellcmd( $command ), $output );
exit( 0 );
