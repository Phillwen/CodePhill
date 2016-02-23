<?php
/**
 * for循环一般用来处理比较简单的有序的，可预知大小的集合或数组
 *
 * foreach可用于遍历任何集合或数组，而且操作简单易懂，他唯一的不好就是需要了解集合内部类型（）
 *
 * iterator是最强大的，他可以随时修改或者删除集合内部的元素，
 * 并且是在不需要知道元素和集合的类型的情况下进行的（原因可参考第三点：多态差别），
 * 当你需要对不同的容器实现同样的遍历方式时，迭代器是最好的选择！
 */
class Okiterator implements Iterator
{
	private $_d = array();
	private $_p = 0;
    
    /**
     * 初始化
     */
    public function __construct($data)
    {
       $this->_d = $data;
       $this->_p = 0;
    }

	/**
	 * 指向列表的开头
	 * @return [type] [description]
	 */
	public function rewind()
	{
       $this->_p = 0;
	}
    
    /**
     * 返回当前指针处的元素
     * @return [type] [description]
     */
	public function current()
	{
       return $this->_d[$this->_p];
	}
    
    /**
     * 返回当前的键（比如：指针的值）
     * @return [type] [description]
     */
	public function key()
	{
        return $this->_p;
	}
    
    /**
     * 返回当前指向的元素并且将指针向前移动一步
     * @return function [description]
     */
	public function next()
	{
       $this->_p++;
	}
    
    /**
     * 确定当前指针处有一个元素
     * @return [type] [description]
     */
	public function valid()
	{
        return isset($this->_d[$this->_p]);
	}
}
