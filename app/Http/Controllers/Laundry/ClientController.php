<?php

namespace App\Http\Controllers\Laundry;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::where('laundry_id', auth('laundry')->id())
            ->latest()->paginate(15);
        return view('laundry.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('laundry.clients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('clients')->where(function ($query) {
                    return $query->where('laundry_id', auth('laundry')->id());
                }),
            ],
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ], [
            'phone.unique' => 'هذا الهاتف موجود بالفعل.',
        ]);

        Client::create([
            ...$data,
            'laundry_id' => auth('laundry')->id(),
        ]);

        return redirect()->route('laundry.clients.index')
            ->with('success', 'تم إضافة العميل بنجاح.');
    }

    public function edit(int $id)
    {
        $client = Client::where('laundry_id', auth('laundry')->id())->findOrFail($id);
        return view('laundry.clients.edit', compact('client'));
    }

    public function update(Request $request, int $id)
    {
        $client = Client::where('laundry_id', auth('laundry')->id())->findOrFail($id);

        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('clients', 'phone')
                    ->ignore($client->id)
                    ->where(fn ($query) => $query->where('laundry_id', auth('laundry')->id())),
            ],
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $client->update($data);

        return redirect()->route('laundry.clients.index')
            ->with('success', 'تم تحديث العميل بنجاح.');
    }

    public function destroy(int $id)
    {
        $client = Client::where('laundry_id', auth('laundry')->id())->findOrFail($id);
        $client->delete();

        return redirect()->route('laundry.clients.index')
            ->with('success', 'تم حذف العميل بنجاح.');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $clients = Client::where('laundry_id', auth('laundry')->id())
                         ->where(function ($q) use ($query) {
                             $q->where('name', 'LIKE', "%{$query}%")
                               ->orWhere('phone', 'LIKE', "%{$query}%");
                         })
                         ->limit(10)
                         ->get(['id', 'name', 'phone']);
        return response()->json($clients);
    }
}