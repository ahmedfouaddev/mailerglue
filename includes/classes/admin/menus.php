<?php
/**
 * Admin: Menus.
 */

namespace MailerGlue\Admin;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Menus class.
 */
class Menus
{

    /**
     * Construct.
     */
    public function __construct()
    {

        add_action('admin_menu', array($this, 'setupAdminMenus'), 10);

    }

    /**
     * Setup admin menus.
     */
    public function setupAdminMenus()
    {

        $menuIcon = $this->getMenuIcon();

        add_menu_page(__('Newsletters', 'mailerglue'), __('Newsletters', 'mailerglue'), 'manage_options', 'mailerglue', null, $menuIcon, '25.5471');

    }

    /**
     * Get admin menu icon.
     */
    public function getMenuIcon()
    {
        return 'data:image/svg+xml;base64,' . '';
    }

}
