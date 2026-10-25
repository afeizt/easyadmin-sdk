<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace Test\Upload;

use EasyAdmin\upload\FileBase;
use PHPUnit\Framework\TestCase;

class FileBaseTest extends TestCase
{
	private FileBase $fileBase;

	protected function setUp(): void
	{
		$this->fileBase = new FileBase();
	}

	public function testSetUploadType(): void
	{
		$obj = $this->fileBase->setUploadType('alioss');
		$this->assertSame($this->fileBase, $obj);
	}

	public function testSetUploadConfig(): void
	{
		$config = ['upload_type' => 'local', 'max_size' => 1024];
		$obj = $this->fileBase->setUploadConfig($config);
		$this->assertSame($this->fileBase, $obj);
	}

	public function testSetTableName(): void
	{
		$obj = $this->fileBase->setTableName('custom_uploadfile');
		$this->assertSame($this->fileBase, $obj);
	}

	public function testSetImageThumb(): void
	{
		$obj = $this->fileBase->setImageThumb(true);
		$this->assertSame($this->fileBase, $obj);
	}

	public function testSetImageThumbWidth(): void
	{
		$obj = $this->fileBase->setImageThumbWidth(800);
		$this->assertSame($this->fileBase, $obj);
	}

	public function testSetImageThumbHeight(): void
	{
		$obj = $this->fileBase->setImageThumbHeight(600);
		$this->assertSame($this->fileBase, $obj);
	}

	public function testSetAddWatermark(): void
	{
		$obj = $this->fileBase->setAddWatermark(true);
		$this->assertSame($this->fileBase, $obj);
	}

	public function testSetWaterMarkType(): void
	{
		$obj = $this->fileBase->setWaterMarkType('text');
		$this->assertSame($this->fileBase, $obj);
	}

	public function testSetWaterMarkContent(): void
	{
		$obj = $this->fileBase->setWaterMarkContent('MyWatermark');
		$this->assertSame($this->fileBase, $obj);
	}

	public function testSetWaterMarkImage(): void
	{
		$obj = $this->fileBase->setWaterMarkImage('/path/to/watermark.png');
		$this->assertSame($this->fileBase, $obj);
	}

	public function testSetWatermarkTextSize(): void
	{
		$obj = $this->fileBase->setWatermarkTextSize(30);
		$this->assertSame($this->fileBase, $obj);
	}

	public function testSetUploadCustomDomain(): void
	{
		$obj = $this->fileBase->setUploadCustomDomain('https://cdn.example.com');
		$this->assertSame($this->fileBase, $obj);
	}

	public function testSetUploadFullPath(): void
	{
		$obj = $this->fileBase->setUploadFullPath(true);
		$this->assertSame($this->fileBase, $obj);
	}

	public function testSetWaterMarkBackground(): void
	{
		$obj = $this->fileBase->setWaterMarkBackground('#ffffff');
		$this->assertSame($this->fileBase, $obj);
	}

	public function testFluentChain(): void
	{
		$result = $this->fileBase
			->setUploadType('local')
			->setUploadConfig(['key' => 'val'])
			->setTableName('test_table')
			->setImageThumb(true)
			->setImageThumbWidth(320)
			->setImageThumbHeight(240);

		$this->assertInstanceOf(FileBase::class, $result);
	}
}
