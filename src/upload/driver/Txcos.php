<?php



namespace EasyAdmin\upload\driver;

use EasyAdmin\upload\driver\txcos\Cos;
use EasyAdmin\upload\FileBase;
use EasyAdmin\upload\trigger\SaveDb;

/**
 * 腾讯云上传
 * Class Txcos.
 */
class Txcos extends FileBase
{
    /**
     * 重写上传方法.
     *
     * @return array|void
     */
    public function save()
    {
        parent::save();
        $cosIns = Cos::instance($this->uploadConfig);
        $upload = $cosIns->save($this->completeFilePath, $this->completeFilePath);

        if ($upload['save'] == true) {
            if (!empty($this->completeThumbFilePath)) {
                $thumb_upload = $cosIns->save($this->completeThumbFilePath, $this->completeFilePath);
                $upload['thumb_url'] = $thumb_upload['url'];
            }
            SaveDb::trigger($this->tableName, [
                'upload_type' => $this->uploadType,
                'original_name' => $this->file->getOriginalName(),
                'mime_type' => $this->file->getOriginalMime(),
                'file_ext' => strtolower($this->file->getOriginalExtension()),
                'url' => $upload['url'],
                'thumb_url' => $thumb_upload['url'] ?? '',
                'create_time' => time(),
            ]);
        }
        $this->rmLocalSave();

        return $upload;
    }
}
