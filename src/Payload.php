<?php

namespace SoapBox\SerializedPayloads;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payload extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'serialized_payloads';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Allow mass assignment for the following fields.
     *
     * @var array
     */
    protected $fillable = ['data'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['data' => 'json'];

    /**
     * Update our primary key here to be a uuid automagically, so we don't have
     * to remember to set it.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Uuid::uuid4();
            }
        });
    }

    /**
     * Create a payload from a request object.
     *
     * @param \Illumiante\Http\Request $request
     *
     * @return \SoapBox\Payloads\Payload
     */
    public static function createFromRequest(Request $request): Payload
    {
        return static::create(['data' => $request->getContent()]);
    }

    /**
     * Marks the payload as processed.
     *
     * @return void
     */
    public function process(): void
    {
        $this->processed_at = Carbon::now();
        $this->save();
    }

    /**
     * Returns the data from the payload.
     *
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * Adds a scope to only return payloads that should be cleaned up.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShouldDelete(Builder $query): Builder
    {
        return $query->where('processed_at', '<', Carbon::now()->subDay());
    }
}
