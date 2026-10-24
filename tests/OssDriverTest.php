<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace Test;

use EasyAdmin\upload\driver\alioss\Oss as AliossDriver;
use EasyAdmin\upload\driver\qnoss\Oss as QnossDriver;
use EasyAdmin\upload\driver\txcos\Cos as TxcosDriver;
use EasyAdmin\upload\interfaces\OssDriver;
use PHPUnit\Framework\TestCase;

class OssDriverTest extends TestCase
{

	/**
	 * 验证 Alioss 实现了 OssDriver 接口
	 */
	public function testAliossImplementsInterface()
	{
		$reflection = new \ReflectionClass(AliossDriver::class);
		$this->assertTrue($reflection->implementsInterface(OssDriver::class));
	}

	/**
	 * 验证 Qnoss 实现了 OssDriver 接口
	 */
	public function testQnossImplementsInterface()
	{
		$reflection = new \ReflectionClass(QnossDriver::class);
		$this->assertTrue($reflection->implementsInterface(OssDriver::class));
	}

	/**
	 * 验证 Txcos/Cos 实现了 OssDriver 接口
	 */
	public function testTxcosImplementsInterface()
	{
		$reflection = new \ReflectionClass(TxcosDriver::class);
		$this->assertTrue($reflection->implementsInterface(OssDriver::class));
	}

	/**
	 * 验证 OssDriver 接口定义了 save 方法
	 */
	public function testOssDriverInterfaceHasSaveMethod()
	{
		$reflection = new \ReflectionClass(OssDriver::class);
		$this->assertTrue($reflection->hasMethod('save'));
	}

	/**
	 * 验证 Alioss 有 instance 静态方法
	 */
	public function testAliossHasInstanceMethod()
	{
		$reflection = new \ReflectionClass(AliossDriver::class);
		$this->assertTrue($reflection->hasMethod('instance'));
		$method = $reflection->getMethod('instance');
		$this->assertTrue($method->isStatic());
	}

	/**
	 * 验证 Qnoss 有 instance 静态方法
	 */
	public function testQnossHasInstanceMethod()
	{
		$reflection = new \ReflectionClass(QnossDriver::class);
		$this->assertTrue($reflection->hasMethod('instance'));
		$method = $reflection->getMethod('instance');
		$this->assertTrue($method->isStatic());
	}

	/**
	 * 验证 Txcos/Cos 有 instance 静态方法
	 */
	public function testTxcosHasInstanceMethod()
	{
		$reflection = new \ReflectionClass(TxcosDriver::class);
		$this->assertTrue($reflection->hasMethod('instance'));
		$method = $reflection->getMethod('instance');
		$this->assertTrue($method->isStatic());
	}

	/**
	 * 验证 save 方法签名
	 */
	public function testSaveMethodSignature()
	{
		$reflection = new \ReflectionClass(OssDriver::class);
		$method = $reflection->getMethod('save');
		$params = $method->getParameters();

		$this->assertCount(2, $params);
		$this->assertEquals('objectName', $params[0]->getName());
		$this->assertEquals('filePath', $params[1]->getName());
	}
}
