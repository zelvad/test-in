<?php

namespace App\Services\Previews;

use App\Models\Preview;

class GetPreviewsCommand
{
    public function getPreviews()
    {
        return Preview::query()
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->get();
    }
}
