<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getHierarchy($parentId = null)
    {
        $nodes = HdCatalogue::where('idParent', $parentId)->get();

        $result = [];

        foreach ($nodes as $node) {
            $children = $this->getHierarchy($node->id);

            $result[] = [
                'id' => $node->id,
                'name' => $node->name,
                'children' => $children,
            ];
        }

        return $result;
    }

    public function getHierarchyAsJson()
    {
        $hierarchy = $this->getHierarchy();
        return response()->json($hierarchy);
    }

    public function parent()
    {
        return $this->belongsTo(HdCatalogue::class, 'idParent', 'id');
    }
}


