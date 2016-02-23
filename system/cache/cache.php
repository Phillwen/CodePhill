<?php
/**
 * @author PHILL
 * 缓存配置类
 */
class Cache
{
	protected $config; //缓存配置信息
	protected $cache;  //缓存的对象

	/**
	 * 初始化缓存
	 * @param [type] $config [description]
	 */
	public function __construct($config)
	{
        $this->config = $config['cache'];
        $this->cache = new $this->config['cache_name'];
        $this->cache->connect($this->config['cache_host'],$this->config['cache_port']);
	}
    
    /**
     * 获取缓存中的数据
     * @return [type] [description]
     */
    public function get($key)
    { 
       return $this->cache->get($key);
    }
    
    /**
     * 把数据保存到缓存中
     * @param [type]  $key    [键名]
     * @param [type]  $value  [键值]
     * @param integer $flag   [是否使用ZLib压缩]
     * @param integer $expire [缓存数据的过期时间，0位永不过期]
     */
    public function set($key,$value,$flag = 0,$expire=0)
    {
       return $this->cache->set($key,$value,$flag,$expire);
    }
    
    /**
     * 关闭缓存连接
     * @return [type] [description]
     */
    public function close()
    {
    	$this->cache->close();
    }
    
    /**
     * 删除键
     * @param  [type] $key [键名]
     * @return [type]      [description]
     */
    public function delete($key)
    {
       $this->cache->delete();
    }
	

}