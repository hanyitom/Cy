<?php 
/*
 * 这是本人第一个框架，以后会逐步的完善。
 * 
 * 组件介绍
 * 
 * Config组件
 * Config组件可以支持INI、XML以及PHP三种类型，可以有多个配置文件，在Event_Manager创建时传入配置文件地址
 * INI基于parse_ini_file()
 * XML基于simplexml_load_file()
 * PHP要求配置信息是一个变量名为$config的数组，详情可以参照BASE_INFO.php（若改变配置文件地址必须将BASE_INFO.php一同切过去）
 * 
 * Plugin组件
 * Plugin组件是一个用于支持扩展的组件，里面的文件除core意外可以随意增删
 * 插件复制到Plugin下即可在框架中使用
 * 使用方式有两种：
 * 1、直接通过调用插件名，即$Plugin->PluginName($Params)
 * 2、通过Plugin组件实例用插件名获取插件的实例，即$Plugin->getPlugin($PluginName)
 * 
 * 
 * 
 * 
 * 
 */
?>