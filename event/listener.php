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
	 * Search engines list
	 * @var array
	 */
	protected $search_engines;

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
		$this->search_engines = array();
		$this->table_prefix = $this->container->getParameter('core.table_prefix');
		
		define('SEO_SEARCH_TAGS_TABLE', $this->table_prefix . 'seo_search_tags');
		
		foreach($this->container->getParameter('carlo94it.seosearchtags.search_engines') as $key => $value)
		{
			foreach($value as $host => $param)
			{
				$this->search_engines[$host] = $param;
			}
		}
		
		if ($this->request->is_set('HTTP_REFERER', \phpbb\request\request_interface::SERVER))
		{
			$http_ref = $this->request->server('HTTP_REFERER');
			
			$http_host = parse_url($http_ref, PHP_URL_HOST);
			$http_host = str_replace('www.', '', $http_ref);
			
			if (isset($this->search_engines[$http_ref]))
			{
				$http_query = parse_url($http_ref, PHP_URL_QUERY);
				
				parse_str($http_query, $http_query_array);
				
				if (isset($http_query_array[$this->search_engines[$http_ref]]))
				{
					$http_query = urldecode($http_query_array[$this->search_engines[$http_ref]]);
					
					// Todo: add search text to database
				}
			}
		}
	}
	
	/**
	 * Manage search tags
	 *
	 * @param Event $event Event object
	 * @return null
	 */
	public function manage_search_tags($event)
	{
		// Todo: get and show search tags
	}
}
