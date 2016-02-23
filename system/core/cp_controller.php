<?php
/**
 * 系统控制器类
 */
class CP_controller
{
	public $Template = null;
	public $Cache = null;

	public function __construct()
	{
	    require(CONFIG_PATH.'\\'.'config.php');
	    $file = SYSTEM_PATH.'\\cache\\'.'cache.php';
		if(file_exists($file))
		{
			require($file);
			$this->Cache = new Cache($CONFIG);
		}
        $this->template();
	}
    
    /**
     * 视图模板
     * @return [type] [description]
     */
	public function template()
	{
        $file = LIBRARY_PATH.'/board/template.php';
        if(file_exists($file))
        {
           require($file);
           $this->Template = new Template(array('php_turn' => true));
        }
	}
}