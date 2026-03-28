<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function index(Request $request)
    {
        $query = Field::query();

        // 🔍 Filter nama lapangan
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // 📄 Pagination (sama seperti admin)
        $fields = $query->paginate(10);

        return view('owner.fields.index', compact('fields'));
    }
}