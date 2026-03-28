<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Field;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Helpers\LogHelper; // Tambahkan ini

class FieldController extends Controller
{
    public function index(Request $request)
    {
        $query = Field::query();
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $fields = $query->paginate(10)->withQueryString();
        return view('admin.fields.index', compact('fields'));
    }

    public function create()
    {
        return view('admin.fields.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price_day' => 'required|numeric',
            'price_night' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('image'), $imageName);
        }

        $field = Field::create([
            'name' => $request->name,
            'image' => $imageName,
            'price_day' => $request->price_day,
            'price_night' => $request->price_night,
            'is_active' => true
        ]);

        // LOGGING
        LogHelper::store('Tambah Lapangan', "Menambahkan lapangan baru: {$field->name}");

        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $field = Field::findOrFail($id);
        return view('admin.fields.edit', compact('field'));
    }

    public function update(Request $request, $id)
    {
        $field = Field::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'price_day' => 'required|numeric',
            'price_night' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'price_day' => $request->price_day,
            'price_night' => $request->price_night,
            'is_active' => $request->is_active ?? 0
        ];

        if ($request->hasFile('image')) {
            if ($field->image && File::exists(public_path('image/'.$field->image))) {
                File::delete(public_path('image/'.$field->image));
            }
            $file = $request->file('image');
            $imageName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('image'), $imageName);
            $data['image'] = $imageName;
        }

        $field->update($data);

        // LOGGING
        LogHelper::store('Update Lapangan', "Mengubah data lapangan: {$field->name}");

        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil diupdate');
    }

    public function destroy($id)
    {
        $field = Field::findOrFail($id);
        $name = $field->name;

        if ($field->image && File::exists(public_path('image/'.$field->image))) {
            File::delete(public_path('image/'.$field->image));
        }

        $field->delete();

        // LOGGING
        LogHelper::store('Hapus Lapangan', "Menghapus lapangan: {$name}");

        return back()->with('success', 'Lapangan berhasil dihapus');
    }



    /**
     * 🔥 GRID BOOKING + HARGA DINAMIS
     */
    public function grid($id)
    {
        $field = Field::findOrFail($id);

        $startHour = 8;
        $endHour   = 23; // sampai 23 biar last slot 22-23

        $hours = [];
        for ($i = $startHour; $i < $endHour; $i++) {
            $hours[] = sprintf('%02d:00', $i);
        }

        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = Carbon::now()->addDays($i)->format('Y-m-d');
        }

      $details = TransactionDetail::with('transaction')
    ->where('field_id', $field->id)
    ->whereBetween('start_time', [
        $now->copy()->startOfDay(),
        $now->copy()->addDays(7)->endOfDay()
    ])
    ->get();

        $grid = [];

        // 🔥 INIT GRID + PRICE
        foreach ($dates as $date) {
            foreach ($hours as $hour) {

                $hourInt = (int) substr($hour, 0, 2);

                $grid[$date][$hour] = [
                    'status' => 'empty',
                    'customer' => null,
                    'price' => $field->getPriceByHour($hourInt)
                ];
            }
        }

        // 🔥 ISI DATA BOOKING
        foreach ($details as $detail) {

            $start = Carbon::parse($detail->start_time);
            $end   = Carbon::parse($detail->end_time);

            while ($start < $end) {

                $date = $start->format('Y-m-d');
                $hour = $start->format('H:00');

                if (isset($grid[$date][$hour])) {
                    $grid[$date][$hour]['status'] = $detail->status;
                    $grid[$date][$hour]['customer'] = $detail->transaction->customer_name ?? '-';
                }

                $start->addHour();
            }
        }

        return response()->json([
            'grid' => $grid,
            'dates' => $dates,
            'hours' => $hours
        ]);
    }


}