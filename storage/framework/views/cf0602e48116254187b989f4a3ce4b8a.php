
<?php $__env->startSection('title', 'Gestion des blanchisseries'); ?>
<?php $__env->startSection('content'); ?>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <!-- En-tête -->
    <div class="px-6 py-4 border-b flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">🏪 Gestion des blanchisseries</h2>
            <p class="text-sm text-gray-500 mt-1">Liste de toutes les blanchisseries enregistrées</p>
        </div>
        <a href="<?php echo e(route('admin.laundries.create')); ?>"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
            + Ajouter une blanchisserie
        </a>
    </div>

    <!-- Liste des blanchisseries -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($laundries->isEmpty()): ?>
        <div class="text-center py-16">
            <div class="text-7xl mb-4">🏪</div>
            <p class="text-gray-500 text-lg font-medium">Aucune blanchisserie enregistrée</p>
            <p class="text-gray-400 text-sm mt-1">Commencez par créer votre première blanchisserie</p>
            <a href="<?php echo e(route('admin.laundries.create')); ?>" 
               class="inline-block mt-4 text-blue-600 hover:text-blue-800 font-medium">
                Créer la première blanchisserie →
            </a>
        </div>
    <?php else: ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $laundries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laundry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-violet-500 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                    <?php echo e(strtoupper(substr($laundry->name, 0, 2))); ?>

                                </div>
                                <span class="font-medium text-gray-800"><?php echo e($laundry->name); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm"><?php echo e($laundry->email); ?></td>
                        <td class="px-6 py-4 text-gray-600 text-sm"><?php echo e($laundry->phone); ?></td>
                        <td class="px-6 py-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($laundry->is_active): ?>
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Actif
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    Inactif
                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('admin.laundries.edit', $laundry->id)); ?>"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                                    ✏️ Modifier
                                </a>
                                <span class="text-gray-300">|</span>
                                <form method="POST" action="<?php echo e(route('admin.laundries.destroy', $laundry->id)); ?>"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette blanchisserie ?')" 
                                      class="inline">
                                    <?php echo csrf_field(); ?> 
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($laundries->hasPages()): ?>
        <div class="px-6 py-4 border-t bg-gray-50/50">
            <?php echo e($laundries->links()); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        
        <!-- Total -->
        <div class="px-6 py-3 text-xs text-gray-400 border-t">
            Total : <?php echo e($laundries->total()); ?> blanchisserie(s)
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ATLAS PRO ELECTRO\Desktop\hass\nadef org\laundry-manager\resources\views/admin/laundries/index.blade.php ENDPATH**/ ?>