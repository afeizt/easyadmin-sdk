<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace Test\Upload;

use EasyAdmin\upload\Uploadfile;
use PHPUnit\Framework\TestCase;

class UploadfileTest extends TestCase
{
	public function testInstanceReturnsSingleton(): void
	{
		$instance1 = Uploadfile::instance();
		$instance2 = Uploadfile::instance();

		$this->assertInstanceOf(Uploadfile::class, $instance1);
		$this->assertSame($instance1, $instance2);
	}

	public function testSetUploadType(): void
	{
		$upload = Uploadfile::instance();
		$obj = $upload->setUploadType('local');
		$this->assertSame($upload, $obj);
	}

	public function testSetTableName(): void
	{
		$upload = Uploadfile::instance();
		$obj = $upload->setTableName('custom_table');
		$this->assertSame($upload, $obj);
	}

	public function testSetUploadConfig(): void
	{
		$upload = Uploadfile::instance();
		$obj = $upload->setUploadConfig(['upload_type' => 'local']);
		$this->assertSame($upload, $obj);
	}

	public function testFluentChain(): void
	{
		$upload = Uploadfile::instance();
		$result = $upload
			->setUploadType('local')
			->setUploadConfig(['key' => 'val'])
			->setTableName('test_table');

		$this->assertInstanceOf(Uploadfile::class, $result);
	}
}
