<?php
/**
 * 模板编译工具类
 */
class compileclass
{
	private $template;  //待编译的文件
	private $content;   //需要替换的文本
	private $comfile;   //编译后的文本

	private $left = '{';  //左定界符
	private $right = '}'; //右定界符
	private $T_P = array();
	private $T_R = array();
	private $value = array(); //值
	private $phpTurn;   
    
    /**
     * 构造函数
     * @param [type] $template    模板文件
     * @param [type] $compileFile 缓存的文件
     * @param [type] $config      配置信息
     */
	public function __construct($template,$compileFile,$config)
	{
        $this->template = $template;
        $this->comfile = $compileFile;
        $this->content = file_get_contents($template);
        if($config['php_turn'] === false)
        {
        	$this->T_R[] = "#<\? (= |php|)(.+?)\?>#is";
            $this->T_R[] = "&lt;? \\1\\2? &gt;";
        }
        $this->T_P[] = "#\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)}#";
		$this->T_P[] = "#\{(loop|foreach) \\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)}#i";
		$this->T_P[] = "#\{\/(loop|foreach)}#i";
		$this->T_P[] = "#\{(K|V)\}#";
		$this->T_P[] = "#\{if (.* ?)\}#i";
		$this->T_P[] = "#\{(else if|elseif) (.* ?)\}#i";
		$this->T_P[] = "#\{else\}#i";
		$this->T_P[] = "#\{/if\}#i";
		$this->T_P[] = "#\{(\#|\*)(.* ?)(\#|\*)\}#";

		$this->T_R[] = "<?php echo \$this->value['\\1'];?>";
		$this->T_R[] = "<?php foreach((array)\$this->value['\\2'] as \$K=>\$V){?>";
		$this->T_R[] = "<?php }?>";
		$this->T_R[] = "<?php echo \$\\1;?>";
		$this->T_R[] = "<?php if (\\1){ ?>";
		$this->T_R[] = "<?php }else if(\\2){?>";
		$this->T_R[] = "<?php }else{?>";
		$this->T_R[] = "<?php } ?>";
		$this->T_R[] = "";
	}
    
    /**
     * 保存模板
     * @return [type] [description]
     */
	public function compile()
	{
       $this->c_var2();
       $this->c_staticFile();
       file_put_contents($this->comfile,$this->content);
	}
    
    /**
     * 编译变量标签功能
     * @return [type] [description]
     */
	public function c_var2()
	{
        $this->content = preg_replace($this->T_P,$this->T_R,$this->content);
	}
    
    /**
     * 加入对静态javascript文件的解析
     * @return [type] [description]
     */
	public function c_staticFile()
	{
        $this->content = preg_replace('#\{\!(.*?)\!\}#','<script src=\\1'.'?t='.time().'></script>',$this->content);
	}

}