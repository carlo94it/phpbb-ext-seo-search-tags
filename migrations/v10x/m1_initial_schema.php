<?php
/**
 *
 * @package SEO Search Tags
 * @copyright (c) 2013 Carlo (carlino1994/carlo94it)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace carlo94it\seosearchtags\migrations\v10x;

/**
 * Initial schema changes needed for Extension installation
 */
class m1_initial_schema extends \phpbb\db\migration\migration
{
	/**
	 * @inheritdoc
	 */
	public function update_schema()
	{
		return array(
			'add_tables'	=> array(
				$this->table_prefix . 'seo_search_tags'	=> array(
					'COLUMNS'	=> array(
						'id'		=> array('UINT', NULL, 'auto_increment'),
						'topic_id'	=> array('UINT', 0),
						'tag'		=> array('TEXT_UNI', ''),
						'views'		=> array('UINT', 0),
					),
					'PRIMARY_KEY'	=> 'id',
					'KEYS'		=> array(
						'topic_id'	=> array('INDEX', 'topic_id'),
					),
				),
			),
		);
	}

	/**
	 * @inheritdoc
	 */
	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'seo_search_tags',
			),
		);
	}
}
