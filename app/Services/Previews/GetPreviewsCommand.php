<?php

namespace App\Services\Previews;

use App\Models\Preview;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetPreviewsCommand
{
    /**
     * Загрузка списка превью
     * @return Builder[]|Collection
     */
    public function getPreviews()
    {
        return Preview::query()
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->get();
    }
}
