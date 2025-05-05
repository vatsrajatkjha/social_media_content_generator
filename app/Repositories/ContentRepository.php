<?php

namespace App\Repositories;

use App\Models\Content;
use App\Repositories\Interfaces\ContentRepositoryInterface;

class ContentRepository implements ContentRepositoryInterface
{
    protected $model;

    public function __construct(Content $content)
    {
        $this->model = $content;
    }

    public function saveContent($data)
    {
        return $this->model->create($data);
    }

    public function getAllContents()
    {
        return $this->model->latest();
    }

    public function getContentById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function updateContent($id, $data)
    {
        $content = $this->getContentById($id);
        $content->update($data);
        return $content;
    }
}
