<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laundry;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;   // important pour gérer les fichiers

class LaundryController extends Controller
{
    public function index(Request $request)
    {
        $query = Laundry::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $laundries = $query->latest()->paginate(10);
        
        return view('admin.laundries.index', compact('laundries'));
    }

    public function create()
    {
        return view('admin.laundries.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:laundries',
            'phone'    => 'required|string|max:20',
            'password' => 'required|min:6',
            'logo'     => 'nullable|image|max:2048', // 2MB max
        ], [
            'name.required' => 'Le nom de la blanchisserie est requis',
            'email.required' => 'L\'email est requis',
            'email.unique' => 'Cet email est déjà utilisé',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = true;

        // Gestion du logo
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = basename($path);
        }

        try {
            Laundry::create($data);
        } catch (QueryException $e) {
            $errorMessage = 'Une erreur est survenue lors de la création. Veuillez réessayer.';
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                $errorMessage = 'Cet email est déjà utilisé.';
            }
            return back()->withInput()->withErrors(['email' => $errorMessage]);
        }

        return redirect()->route('admin.laundries.index')
            ->with('success', 'Blanchisserie créée avec succès !');
    }

    public function edit($id)
    {
        $laundry = Laundry::findOrFail($id);
        return view('admin.laundries.edit', compact('laundry'));
    }

    public function update(Request $request, $id)
    {
        $laundry = Laundry::findOrFail($id);

        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:laundries,email,'.$id,
            'phone'     => 'required|string|max:20',
            'password'  => 'nullable|min:6',
            'logo'      => 'nullable|image|max:2048',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->has('is_active');

        // Gestion du logo
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo s'il existe
            if ($laundry->logo && Storage::disk('public')->exists('logos/' . $laundry->logo)) {
                Storage::disk('public')->delete('logos/' . $laundry->logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = basename($path);
        }

        $laundry->update($data);

        return redirect()->route('admin.laundries.index')
            ->with('success', 'Blanchisserie mise à jour avec succès !');
    }

    public function destroy($id)
    {
        $laundry = Laundry::findOrFail($id);

        // Supprimer le logo si présent
        if ($laundry->logo && Storage::disk('public')->exists('logos/' . $laundry->logo)) {
            Storage::disk('public')->delete('logos/' . $laundry->logo);
        }

        $laundry->delete();

        return redirect()->route('admin.laundries.index')
            ->with('success', 'Blanchisserie supprimée avec succès !');
    }
}