<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Group extends Model
{
    use HasFactory;

    protected $table = "groups";

    protected $fillable = [
        "creator_id",
        "uniqueUrl",
        "name",
        "managers",
        "description",
        "participant_count"
    ];


    public function creator() {
        return $this->belongsTo(User::class, "creator_id", "id");
    }

    public function posts() {
        return $this->hasMany(GroupPost::class);
    }


    public static function convertIdsToModels(Collection $ids): Collection {
        $groupModels = collect();

        foreach($ids as $id) {
            $group = Group::find($id);

            if($group != null) {
                $groupModels->add($group);
            }
        }

        return $groupModels;
    }
}
