<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Project
 * 
 * @property int $id
 * @property string $name
 * @property string|null $logo
 * @property string $status
 * @property string $menus
 * @property string|null $sections
 * @property string|null $file_path
 * @property string|null $template
 * @property string $type
 * @property int|null $user_id
 * @property string|null $note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class Project extends Model implements HasMedia
{
	use InteractsWithMedia;

	protected $table = 'projects';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'name',
		'logo',
		'status',
		'menus',
		'sections',
		'file_path',
		'template',
		'type',
		'user_id',
		'note'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
