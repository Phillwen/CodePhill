<?php
/**
 * 数据库文件类
 * sql = "select * from vol_msg"; 
*  $result = mysqli_query($connect,$sql); 
 */
class Db
{
   //连接数据库
    public static $connect;
    public static $sql='';  //定义查询的语句
    public static $config;
    
    public function __construct($config)
    {
       self::$config = $config['db'];
       self::$connect = new mysqli(self::$config['Server_host'],self::$config['Server_name'],self::$config['Server_pass'],self::$config['Server_db'],self::$config['Server_socket']) or die('Unale to connect'); 
    }

   /**
    * 查询
    * @param  [type] $str 字段
    * @return [type]      
    */
   public static function select($str)
   {
      self::$sql .='SELECT '.$str;
   }
   
   /**
    * 更新
    * @param  [type] $table 表名
    * @param  [type] $str   数组
    * @return [type]        
    */
   public static function update($table,$str)
   {
      self::$sql .= 'UPDATE '.$table;
      for ($i=0; $i <count($str) ; $i++) 
      { 
      	if($i>0)
      	{
           self::$sql .= ', SET '.$str[$i];
      	}else
      	{
           self::$sql .= ' SET '.$str[$i];
      	}
      }
   }
   
   /**
    * 插入
    * @param  [type] $table 表名
    * @param  [type] $str   字段
    * @param  [type] $value 值
    * @return [type]        
    */
   public static function insert($table,$str,$value)
   {
       self::$sql = 'INSERT INTO '.$table.'('.$str.')'.' VALUES('.$value.')';
   }
   
   /**
    * 查询条件
    * @param  [type] $where  
    * @return [type]        [description]
    */
   public static function where($where)
   {
      self::$sql .=' WHERE '.$where;
   }
   
   /**
    * 只查询一条
    * @return [type] [description]
    */
   public static function fetch_row()
   {
   	  mysqli_query(self::$connect,'set names utf8');
      $result = self::$connect->query(self::$sql);
      return $result->fetch_object();
   }
   
   /**
    * 查询全部的数据
    * @return [type] [description]
    */
   public static function fetch_all()
   {
   	  $obj = array();
   	  mysqli_query(self::$connect,'set names utf8');
      $result = self::$connect->query(self::$sql);
      while($row=$result->fetch_object())
  	  {
  		  array_push($obj,$row);
  	  }
      return $obj;
   }
   
   /**
    * 更新或插入
    * @return [type] [description]
    */
   public static function execute()
   {
   	   mysqli_query(self::$connect,'set names utf8');
       $result = self::$connect->query(self::$sql);
   }
   
   /**
    * 查询那张表
    * @return [type] [description]
    */
   public static function from($table)
   {
      self::$sql .=' FROM '.$table;
   }

}

