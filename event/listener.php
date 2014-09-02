<?php
/**
*
* @package Delete Pms
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\deletepms\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
    /* @var \phpbb\controller\helper */
    protected $helper;
  
    /**
    * Constructor
    *
    * @param \phpbb\controller\helper    $helper        Controller helper object
    */
    public function __construct(\phpbb\controller\helper $helper)
    {
        $this->helper = $helper;
    }

    static public function getSubscribedEvents()
    {
        return array(
            'core.acp_board_config_edit_add'	=> 'load_config_on_setup',
			'core.user_setup'					=> 'load_language_on_setup'
		);
    }

    public function load_config_on_setup($event)
    {
		if ($event['mode'] == 'features')
		{
			$display_vars = $event['display_vars'];
			
			$add_config_var['delete_pms_days'] = 
				array(
					'lang' 		=> 'DELETE_PMS_DAYS',
					'validate'	=> 'int',
					'type'		=> 'number:0:99',
					'explain'	=> true
				);

			$add_config_var['delete_pms_read'] = 
				array(
					'lang' 		=> 'DELETE_PMS_READ',
					'validate'	=> 'bool',
					'type'		=> 'radio:yes_no',
					'explain'	=> true
				);
			if(!function_exists("insert_config_array")) include("compatibility.php");
			$display_vars['vars'] = phpbb_insert_config_array($display_vars['vars'], $add_config_var, array('after' =>'allow_quick_reply'));
			$event['display_vars'] = array('title' => $display_vars['title'], 'vars' => $display_vars['vars']);
		}
    }
	
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'forumhulp/deletepms',
			'lang_set' => 'delete_pms_common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}
}