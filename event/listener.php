<?php
/**
 *
 * @package SEO Search Tags
 * @copyright (c) 2013 Carlo (carlino1994/carlo94it)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace carlo94it\seosearchtags\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/**
	 * Database object
	 * @var \phpbb\db\driver
	 */
	protected $db;

	/**
	 * Request object
	 * @var \phpbb\request
	 */
	protected $request;

	/**
	 * Table prefix
	 * @var string
	 */
	protected $table_prefix;
	
	/**
	 * Get subscribed events
	 *
	 * @return array
	 * @static
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'						=> 'setup',
			'viewtopic_body_topic_actions_before'	=> 'manage_search_tags',
		);
	}
	
	/**
	 * Set up the environment
	 *
	 * @param Event $event Event object
	 * @return null
	 */
	public function setup($event)
	{
		global $phpbb_container;
		
		$this->container = $phpbb_container;
		
		$this->db = $this->container->get('dbal.conn');
		$this->request = $this->container->get('request');
		$this->table_prefix = $this->container->getParameter('core.table_prefix');
		
		define('SEO_SEARCH_TAGS_TABLE', $table_prefix . 'seo_search_tags');
	}
	
	/**
	 * Manage search tags
	 *
	 * @param Event $event Event object
	 * @return null
	 */
	public function manage_search_tags($event)
	{
		// Todo: write the code for the method
	}
}
