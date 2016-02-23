<?php
/**
 *系统自动加载，实例化等驱动类 
 */
final class Appliction
{
	protected static $cache=null;
	static public function run()
	{
		header('Content-Type:text/html;charset=utf8');
		//加载系统配置文件
		require(CONFIG_PATH.'\\'.'config.php');
		self::load_system($CONFIG);
		self::load_library($CONFIG);  //加载扩展类
		//获取系统访问的url
		$URL = $_SERVER['REQUEST_URI'];
		$arr = explode('/',$URL);
		if(isset($arr[3]) && $arr[3]!='')
		{
			$controller = strtolower($arr[3]);
			$controller_file = CONTROLLER_PATH.'\\'.$controller.'.php';
			if(file_exists($controller_file))
			{
				//如果存在
				require($controller_file);
				$instance = new $controller;
				if(isset($arr[4]))
				{
					$action = strtolower($arr[4]);
					$instance->$action();
					self::getview($action);
				}else
				{
					$instance->index();
					self::getview('index');
				}
			}else
			{
				//查找下一个
				$folder = strtolower($arr[3]);
				$controller = strtolower($arr[4]);
			    $controller_file = CONTROLLER_PATH.'\\'.$folder.'\\'.$controller.'.php';
			    if(file_exists($controller_file))
			    {
                    //如果存在
					require($controller_file);
					$instance = new $controller;
					if(isset($arr[5]))
					{
						$action = strtolower($arr[5]);
						$instance->$action();
						$action = $folder.'\\'.$action;
						self::getview($action);
					}else
					{
						$instance->index();
						self::getview('index');
					}
			    }else
			    {
			    	//trigger_error($controller_file."文件不存在");
			    	exit($controller_file."文件不存在");
			    }
			}
		}else
		{
			//访问默认的控制器
			$default_controller = CONTROLLER_PATH.'\\'.$CONFIG['route']['default_controller'].'.php';
			if(file_exists($default_controller))
			{
				require($default_controller);
				$instance = new $CONFIG['route']['default_controller'];
				$default_action = $CONFIG['route']['default_action'];
				$instance->$default_action();
				self::getview($default_action);
				die;
			}else
			{
				trigger_error($default_controller."文件不存在");
				die;
			}
		}
	}
    
    /**
     * 加载视图文件
     * @return [type] [description]
     */
	static private function getview($action)
	{
        $view_path = VIEWS_PATH.'\\'.$action.'.php';
        if(file_exists($view_path))
        {
        	ob_start();
            require($view_path);
            $content = ob_get_contents();
            ob_clean();
            echo $content;
        }else
        {
        	trigger_error("The view is loading faile");
        	die;
        }
	}

	/**
	 * 自动加载系统类
	*/
    static private function load_system($config)
    {
    	$system = $config['system'];
    	foreach ($system as $key) 
    	{
    		$file = SYSTEM_PATH.'\\core\\'.$key.'.php';
    		if(file_exists($file))
    		{
    			require($file);
    			if($key=='db')
    			{
    				$db = new $key($config);
    			}
    		}
    	}
    }

    /**
	 * 自动加载用户自定义类
	*/
    static private function load_library($config)
    {
    	$system = $config['lib'];
    	foreach ($system as $key) 
    	{
    		$file = LIBRARY_PATH.$key.'.php';
    		if(file_exists($file))
    		{
    			require($file);
    		}
    	}
    }
}
