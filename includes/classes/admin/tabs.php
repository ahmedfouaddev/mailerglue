<?php

namespace MailerGlue\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Tabs class.
 */
class Tabs {

	/**
	 * Output.
	 */
	public function output( $tabs = array() ) {

		if ( empty( $tabs ) || ! is_array( $tabs ) ) {
			return;
		}

		?>
		<div class="mailerglue-ui">
			<div class="components-panel__body mailerglue-tabs is-opened">
				<div class="components-panel__row">
					<ul>
						<?php foreach( $tabs as $page => $title ) : ?>
						<li><a class="components-button is-link <?php if ( $this->is_current_tab( $page ) ) echo 'mailerglue-active'; ?>" href="<?php echo esc_url( admin_url( $page ) ); ?>"><?php echo esc_html( $title ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Checks if given link is active tab.
	 */
	public function is_current_tab( $page ) {
		global $pagenow;

		$current_url = basename( $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );

		if ( $page === $current_url ) {
			return true;
		}

		return false;
	}

}