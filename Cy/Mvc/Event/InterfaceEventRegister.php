<?php
namespace Cy\Mvc\Event;

/**
 * 对象寄存接口
 * @author Toby
 */
interface InterfaceEventRegister
{
	/**
	 * 寄存事件对象实例
	 * @param String $namespace	寄存对象的命名空间
	 * @param Object $eventObj	寄存对象的实例
	 */
	public function register($namespace, $eventObj);

	/**
	 * 获取已寄存的对象实例
	 * @param String $namespace 寄存对象的命名空间
	 */
	public function getRegistered($namespace);

	/**
	 * 检查是否已经寄存了指定的对象实例
	 * @param Object $eventObj	寄存对象的实例
	 */
}
