<?php
/**
 * @author  PHILL
 * @copyright 实现一个简单的模板引擎骨架
 */
class Template
{
    private $arrayConfig = array(
    	'suffix'           => '.m',          //设置模板文件的后缀
    	'templateDir'      => 'template/',   //设置模板所在的文件夹
    	'compiledir'       => 'cache/',      //设置编译后存放的目录
    	'cache_htm'        => false,         //是否需要生成静态文件
    	'cache_time'       => 2000,          //缓存的时间
    	'php_turn'         => true,          //是否支持原生的PHP
        'suffix_cache'     => '.htm',        //设置编译文件的后缀
        'cache_control'    => 'control.dat',
        'debug'            => false
     	);
    public $file;   //模板的名称
    public $value = array();  //保存用户定义的值
    public $compileTool;  //编译类的对象
    static private $instance = null;  //唯一对象
    public $debug = array();  //调试信息
    private $controlData = array();
    /**
     * 初始化
     * @param array $arrayConfig [description]
     */
    public function __construct($arrayConfig = array())
    {
        $this->debug['begin'] = microtime(true);
    	$this->arrayConfig = $arrayConfig + $this->arrayConfig;
    	$this->getPath();
    	//判断模板文件夹是否存在
    	if(!is_dir($this->arrayConfig['templateDir']))
    	{
    		exit("template dir isn't found");
    	}
    	//判断编译后的模板文件夹是否存在
    	if(!is_dir($this->arrayConfig['compiledir']))
    	{
    		mkdir($this->arrayConfig['compiledir'],0770,true);  //没有的话,就创建文件夹
    	}
    	include('compileclass.php');
    }
    
    /**
     * 取得模板引擎的实例
     * @return [type] [description]
     */
    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new Template();
        }
        return self::$instance;
    }
    
    /**
     * 单步设置引擎
     * @param [type] $key   键
     * @param [type] $value 值
     */
    public function setConfig($key,$value = null)
    {
        if(is_array($key))
        {
            $this->arrayConfig = $key+$this->arrayConfig;
        }else
        {
            $this->arrayConfig[$key] = $value;
        }
    }
    
    /**
     * 获取当前模板引擎配置，供调试使用
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function getConfig($key = null)
    {
        if($key)
        {
            return $this->arrayConfig[$key];
        }else
        {
            return $this->arrayConfig;
        }
    }
    
    /**
     * 读取文件的路径
     * @return [type] [description]
     */
    public function path()
    {
       return $this->arrayConfig['templateDir'].$this->file.$this->arrayConfig['suffix'];
    }
    
    /**
     * 展示我们的模板
     * @param  [type] $file 模板的名称
     * @return [type]       [description]
     */
    public function show($file)
    {
        try
        {
            $this->file = $file;
            if(!is_file($this->path()))
            {
                exit("找不到对应的模板");
            }
            $compileFile = $this->arrayConfig['compiledir'].'/'.md5($file).'.php';
            $cacheFile = $this->arrayConfig['compiledir'].'/'.md5($file).'.htm';
            if($this->reCache($file) === false)
            {
               $this->compileTool = new compileclass($this->path(),$compileFile,$this->arrayConfig);
               //需要缓存
               if($this->needCache())
               {
                   ob_start();
               }
               extract($this->value,EXTR_OVERWRITE);
               if(!is_file($compileFile) || filemtime($compileFile) < filemtime($this->path()))
               {
                   //模板有改动的情况下
                   $this->compileTool->vars = $this->value;
                   $this->compileTool->compile();
                   include $compileFile;
               }else
               {
                   include $compileFile;
               }
               if($this->needCache())
               {
                   $message = ob_get_contents();
                   file_put_contents($cacheFile,$message);
               }
            }else
            {
                readfile($cacheFile);
            }
        }catch(Exception $e)
        {
            $this->debug_info();
            die;
        }
    }
    
    /**
     * 是否需要重新生成静态文件
     * @param  [type] $file 模板文件
     * @return [type]       [description]
     */
    public function reCache($file)
    {
        $flag = false;
        $cacheFile = $this->arrayConfig['compiledir'].'/'.md5($file).'.htm';
        if($this->arrayConfig['cache_htm'] === true)
        {
        	//是否需要缓存
        	$timeFlag = (time()-@filemtime($cacheFile))<$this->arrayConfig['cache_time']?true:false;
        	if(is_file($cacheFile) && filesize($cacheFile)>1 && $timeFlag) 
        	{
        		//未过期
        		$flag = true;
        	}else
        	{
        		$flag = false;
        	}
        }
        return $flag;
    }
    
    /**
     * 判断是否开启了缓存
     * @return [type] [description]
     */
    public function needCache()
    {
        return $this->arrayConfig['cache_htm'];
    }
    
    /**
     * 用户自定义变量
     * @param  [type] $key   变量名
     * @param  [type] $value 值
     * @return [type]        [description]
     */
    public function assign($key,$value)
    {
       $this->value[$key] = $value;
    }
    
    /**
     * 注入数组变量
     * @param  [type] $array 数组
     * @return [type]        [description]
     */
    public function assignArray($array)
    {
        if(is_array($array))
        {
        	foreach ($array as $k => $v) 
        	{
        		$this->value[$k] = $v;
        	}
        }
    } 
    
    /**
     * 获取文件的绝对路径
     * @return [type] [description]
     */
    public function getPath()
    {
    	$this->arrayConfig['templateDir'] = LIBRARY_PATH.'/board/'.$this->arrayConfig['templateDir'];
    	$this->arrayConfig['compiledir'] = LIBRARY_PATH.'/board/'.$this->arrayConfig['compiledir'];
    }

     /**
     * 错误信息
     * @return [type] [description]
     */
    public function debug_info()
    {
        if($this->arrayConfig['debug'] === true)
        {
            echo '<br/>'.PHP_EOL,'-------------------debug info-----------------',PHP_EOL;
            echo '<br/>程序运行日期:',date("Y-m-d h:i:s"),PHP_EOL;
            echo '<br/>模板解析耗时:',$this->debug['spend'].'秒',PHP_EOL;
            echo '<br/>模板包含标签数目:',$this->debug['count'],PHP_EOL;
            echo '<br/>是否使用静态缓存:',$this->debug['cached'],PHP_EOL;
            echo '<br/>模板引擎实例参数:',var_dump($this->getConfig());
        }
    }
}
