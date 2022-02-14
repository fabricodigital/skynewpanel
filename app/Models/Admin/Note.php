<?php

namespace App\Models\Admin;

use App\Traits\DataTables\Admin\NoteDataTable;
use App\Traits\Revisionable\Admin\NoteRevisionable;
use App\Traits\Translations\Admin\NoteTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use NoteDataTable;
    use NoteRevisionable;
    use NoteTranslation;
    use SoftDeletes;

    protected $guarded = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get the user that owns the Note
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function widget() {
        return $this->belongsTo(Widget::class);
    }
}
