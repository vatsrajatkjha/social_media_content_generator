<?php

namespace App\Repositories\Interfaces;

interface ContentRepositoryInterface
{
    public function saveContent($data);
    public function getAllContents();
    public function getContentById($id);
    public function updateContent($id, $data);
}
