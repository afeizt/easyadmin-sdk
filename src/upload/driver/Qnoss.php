<?php



namespace EasyAdmin\upload\driver;

use EasyAdmin\upload\driver\qnoss\Oss;
use EasyAdmin\upload\FileBase;
use EasyAdmin\upload\trigger\SaveDb;

/**
 * 七牛云上传
 * Class Qnoss.
 */
class Qnoss extends FileBase
{
    /**
     * 重写上传方法.
     *
     * @return array|void
     */
    public function save()
    {
        parent::save();
        $ossIns = Oss::instance($this->uploadConfig);
        $upload = $ossIns->save($this->completeFilePath, $this->completeFilePath);

        if ($upload['save'] == true) {
            if (!empty($this->completeThumbFilePath)) {
                $thumb_upload = $ossIns->save($this->completeThumbFilePath, $this->completeFilePath);
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
