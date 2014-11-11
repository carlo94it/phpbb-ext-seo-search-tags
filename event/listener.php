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
	 * @var \phpbb\db\driver\driver
	 */
	protected $db;

	/**
	 * Request object
	 * @var \phpbb\request\request
	 */
	protected $request;
	
	/**
	 * Search engines list
	 * @var array
	 */
	protected $search_engines;

	/**
	 * Table name
	 * @var string
	 */
	protected $table;
	
	/**
	 * Request object
	 * @var \phpbb\template\template
	 */
	protected $template;
	
	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver     $db             Database object
	 * @param \phpbb\request\request      $request        Request object
	 * @param \phpbb\template\template    $template       Template object
	 * @return \carlo94it\seosearchtags\event\listener
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver $db, \phpbb\request\request $request, \phpbb\template\template $template)
	{
		$this->db = $db;
		$this->request = $request;
		$this->template = $template;
	}
	
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
		
		$this->table = $this->container->getParameter('carlo94it.seosearchtags.tables.tags');
		$this->search_engines = array();
		
		foreach($this->container->getParameter('carlo94it.seosearchtags.search_engines') as $key => $value)
		{
			foreach($value as $host => $param)
			{
				$this->search_engines[$host] = $param;
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
		$this->template->assign_vars(array(
			'SEO_SEARCH_TAGS'	=> 'ciaoooooooo',
		));
		
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
					
					$sql = "SELECT COUNT(*) as tags
						FROM " . SEO_SEARCH_TAGS_TABLE . "
						WHERE topic_id = 1 AND tags = '" . $db->sql_escape($http_query) . "'";
					
					$result = $db->sql_query($sql);
					$tags = (int) $db->sql_fetchfield('tags');
					
					if ($tags)
					{
						//$db->sql_query("UPDATE " . SEO_SEARCH_TAGS_TABLE . " SET view = view + 1 WHERE topic_id = {$topic_id} AND string = '{$db->sql_escape($search_terms)}'");
					}
				}
			}
		}
	}
}
