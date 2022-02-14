<?php

namespace App\Models\Admin;

use App\Notifications\DataTablesExportDone;
use App\Traits\Translations\Admin\ExportTranslation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use App\Traits\DataTables\Admin\ExportDataTable;
use DateTimeInterface;

class Export extends Model implements HasMedia
{
    use HasMediaTrait;
    use ExportDataTable;
    use ExportTranslation;
    use SoftDeletes;

    const EXPIRED_AFTER_DAYS = 90;

    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @var array
     */
    protected $dates = ['date_start', 'date_end'];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    /**
     * @return Export
     */
    protected function start($modelTarget)
    {
        return self::create([
            'date_start' => Carbon::now(),
            'creator_id' => Auth::id(),
            'state' => 'in_progress',
            'model_target' => $modelTarget,
        ]);
    }

    /**
     * @param $tmpFilePath
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function finish($tmpFilePath)
    {
        $file = storage_path('app/' . $tmpFilePath);

        $this->addMedia($file)
            ->toMediaCollection('exported');

        $this->date_end = Carbon::now();
        $this->state = 'completed';
        $this->save();

        $this->creator->notify(new DataTablesExportDone($this->fresh()));
    }

    /**
     * @param $message
     */
    public function fail($message)
    {
        $this->message = $message;
        $this->date_end = Carbon::now();
        $this->state = 'failed';

        $this->save();

        $this->creator->notify(new DataTablesExportDone($this->fresh()));
    }

    /**
     *
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection('exported')
            ->singleFile();
    }
}
