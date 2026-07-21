@extends('layouts.admin')
@section('title', 'Gestion des blanchisseries')
@section('content')

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <!-- En-tête -->
    <div class="px-6 py-4 border-b flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">🏪 Gestion des blanchisseries</h2>
            <p class="text-sm text-gray-500 mt-1">Liste de toutes les blanchisseries enregistrées</p>
        </div>
        <a href="{{ route('admin.laundries.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
            + Ajouter une blanchisserie
        </a>
    </div>

    <!-- Liste des blanchisseries -->
    @if($laundries->isEmpty())
        <div class="text-center py-16">
            <div class="text-7xl mb-4">🏪</div>
            <p class="text-gray-500 text-lg font-medium">Aucune blanchisserie enregistrée</p>
            <p class="text-gray-400 text-sm mt-1">Commencez par créer votre première blanchisserie</p>
            <a href="{{ route('admin.laundries.create') }}" 
               class="inline-block mt-4 text-blue-600 hover:text-blue-800 font-medium">
                Créer la première blanchisserie →
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 border-b">
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($laundries as $laundry)
                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-violet-500 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                    {{ strtoupper(substr($laundry->name, 0, 2)) }}
                                </div>
                                <span class="font-medium text-gray-800">{{ $laundry->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">{{ $laundry->email }}</td>
                        <td class="px-6 py-4 text-gray-600 text-sm">{{ $laundry->phone }}</td>
                        <td class="px-6 py-4">
                            @if($laundry->is_active)
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Actif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    Inactif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.laundries.edit', $laundry->id) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                                    ✏️ Modifier
                                </a>
                                <span class="text-gray-300">|</span>
                                <form method="POST" action="{{ route('admin.laundries.destroy', $laundry->id) }}"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette blanchisserie ?')" 
                                      class="inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($laundries->hasPages())
        <div class="px-6 py-4 border-t bg-gray-50/50">
            {{ $laundries->links() }}
        </div>
        @endif
        
        <!-- Total -->
        <div class="px-6 py-3 text-xs text-gray-400 border-t">
            Total : {{ $laundries->total() }} blanchisserie(s)
        </div>
    @endif
</div>

@endsection