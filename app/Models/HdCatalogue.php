<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HdCatalogue extends Model
{
    use HasFactory;

    protected $table = 'hd_catalogue';

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
    // Recursive function to get the hierarchical structure
    public function getHierarchy($parentId = null)
    {
        // Fetch all records with the given parentId
        $nodes = HdCatalogue::where('idParent', $parentId)->get();

        $result = [];

        foreach ($nodes as $node) {
            $children = $this->getHierarchy($node->id); // Recursive call for children

            $result[] = [
                'id' => $node->id,
                'name' => $node->name,
                'children' => $children, // Append children if they exist
            ];
        }

        return $result;
    }

    // Function to return the hierarchy as JSON
    public function getHierarchyAsJson()
    {
        $hierarchy = $this->getHierarchy(); // Start from the top-level parents
        return response()->json($hierarchy); // Return as JSON response
    }
}


