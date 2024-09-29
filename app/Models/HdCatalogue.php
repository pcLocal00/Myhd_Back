<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class HdCatalogue extends Model
{
    use HasFactory;

    protected $table = 'hd_catalogue';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'code',
        'name',
        'description',
        'img',
        'icon',
        'orderShow',
        'isActive',
        'isShowInCatalogue',
        'isVisibleClient',
        'isVisibleInterne',
        'typeShow',
        'idParent',
    ];

    public function getHierarchy($parentId = null, $idFkFamily = null)
    {
        $nodes = HdCatalogue::where('idParent', $parentId)->get();
        $result = [];

        foreach ($nodes as $node) {
            $children = $this->getHierarchy($node->id, $idFkFamily);

            $selected = ($node->id == $idFkFamily);

            $result[] = [
                'id' => $node->id,
                'name' => $node->name,
                'children' => $children,
                'selected' => $selected,
            ];
        }

        return $result;
    }

    public function getHierarchyAsJson($idFkFamily = null)
    {
        $hierarchy = $this->getHierarchy(null, $idFkFamily);
        return response()->json($hierarchy);
    }

    public function parent()
    {
        return $this->belongsTo(HdCatalogue::class, 'idParent', 'id');
    }
}


