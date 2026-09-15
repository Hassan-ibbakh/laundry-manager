@extends('layouts.laundry')

@section('title', 'تسجيل طلب جديد')

@section('content')
<div class="max-w-3xl mx-auto pb-20 relative" dir="rtl">
    {{-- En-tête avec titre dynamique, badge panier et bouton fermer --}}
    <div class="flex items-center justify-between mb-6">
        <h2 id="pageTitle" class="text-2xl font-bold text-gray-900">تسجيل طلب جديد</h2>
        <div class="flex items-center gap-2">
            <button
                id="cartHeaderBtn"
                type="button"
                class="relative hidden p-3 bg-blue-600 text-white rounded-full shadow-lg shadow-blue-200 hover:bg-blue-700 transition-transform active:scale-95"
                title="مراجعة السلة"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span id="cartHeaderBadge" class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs font-bold border-2 border-white">
                    0
                </span>
            </button>
            <a href="{{ route('laundry.orders.index') }}" class="p-2 text-gray-500 hover:bg-gray-100 rounded-full transition-colors" title="إلغاء">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
            <p class="font-bold flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                تعذر حفظ الطلب
            </p>
            <ul class="mt-2 list-inside list-disc text-sm pr-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="orderForm" method="POST" action="{{ route('laundry.orders.store') }}">
        @csrf
        <input type="hidden" name="items" id="itemsJson">
        <input type="hidden" name="client_id" id="clientId" value="{{ old('client_id') }}">
        <input type="hidden" name="payment_status" id="paymentStatusInput" value="{{ old('payment_status', 'unpaid') }}">

        {{-- ======================================================== --}}
        {{-- ÉTAPE 1 : SELECTION DES VETEMENTS (Item Selection Panel)  --}}
        {{-- ======================================================== --}}
        <div id="stepItems" class="space-y-6">
            {{-- 1. Services --}}
            <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-gray-900">
                    <span class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-sm font-black">1</span>
                    نوع الخدمة
                </h3>
                <div class="flex gap-3">
                    {{-- تمت إضافة 'أفرشة' هنا --}}
                    @foreach(['غسيل', 'مصلوح', 'صباغة', 'أفرشة'] as $service)
                        <button
                            type="button"
                            data-service="{{ $service === 'غسيل' ? 'تصبين' : $service }}"
                            class="service-btn flex-1 py-3 px-2 sm:px-4 rounded-xl border-2 border-gray-100 bg-white text-gray-600 hover:border-gray-200 transition-all font-bold text-center active:scale-[0.98] text-sm sm:text-base"
                        >
                            {{ $service }}
                        </button>
                    @endforeach
                </div>
            </section>

            {{-- 2. Piece Type --}}
            <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-4 gap-3">
                    <h3 class="text-lg font-bold flex items-center gap-2 text-gray-900 shrink-0">
                        <span class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-sm font-black">2</span>
                        نوع القطعة
                    </h3>
                    <input 
                        type="text"
                        id="pieceSearchInput"
                        placeholder="ابحث أو اكتب نوعاً آخر..."
                        class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none w-full sm:w-1/2"
                    />
                </div>
                <div id="piecesGrid" class="grid grid-cols-3 sm:grid-cols-5 gap-2 max-h-[240px] overflow-y-auto pr-1">
                    {{-- Rempli dynamiquement en JS --}}
                </div>
            </section>

            {{-- 3. Color --}}
            <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-4 gap-3">
                    <h3 class="text-lg font-bold flex items-center gap-2 text-gray-900 shrink-0">
                        <span class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-sm font-black">3</span>
                        الألوان
                    </h3>
                    <input 
                        type="text"
                        id="customColorInput"
                        placeholder="اكتب ألواناً أخرى..."
                        class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none w-full sm:w-1/2"
                    />
                </div>
                <div id="colorsGrid" class="grid grid-cols-2 sm:grid-cols-4 gap-2 max-h-[240px] overflow-y-auto pr-1">
                    {{-- Rempli dynamiquement --}}
                </div>
            </section>

            {{-- 4. Quantity & Price --}}
            <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <h3 id="quantityLabel" class="text-lg font-bold mb-4 text-gray-900">الكمية</h3>
                        <div class="flex items-center gap-4 bg-gray-100 p-2 rounded-xl">
                            <button 
                                type="button"
                                id="qtyMinus"
                                class="w-10 h-10 bg-white text-gray-600 rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 active:scale-95 font-bold text-xl"
                            >
                                −
                            </button>
                            <input id="qtyDisplay" type="number" value="1" min="0.1" step="0.1" class="flex-1 min-w-0 bg-transparent text-center text-xl font-bold text-gray-800 outline-none">
                            <button 
                                type="button"
                                id="qtyPlus"
                                class="w-10 h-10 bg-white text-gray-600 rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 active:scale-95 font-bold text-xl"
                            >
                                +
                            </button>
                        </div>
                    </div>
                    <div>
                        <h3 id="priceLabel" class="text-lg font-bold mb-4 text-gray-900">السعر المقترح (للقطعة)</h3>
                        <div class="flex items-center gap-4 bg-gray-100 p-2 rounded-xl">
                            <button 
                                type="button"
                                id="priceMinus"
                                class="w-10 h-10 bg-white text-gray-600 rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 active:scale-95 font-bold text-xl"
                            >
                                −
                            </button>
                            <div class="flex flex-1 items-center justify-center gap-1">
                                <input
                                    type="number"
                                    id="priceInput"
                                    value="20"
                                    min="1"
                                    step="1"
                                    inputmode="decimal"
                                    aria-label="السعر بالدرهم"
                                    class="w-20 border-0 bg-transparent text-center text-xl font-bold text-gray-800 outline-none focus:ring-0"
                                >
                                <span class="text-sm font-bold text-gray-500">درهم</span>
                            </div>
                            <button 
                                type="button"
                                id="pricePlus"
                                class="w-10 h-10 bg-white text-gray-600 rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 active:scale-95 font-bold text-xl"
                            >
                                +
                            </button>
                        </div>
                    </div>
                </div>

                <button
                    type="button"
                    id="addToCartBtn"
                    disabled
                    class="w-full mt-8 py-4 bg-blue-600 text-white rounded-xl font-bold text-lg shadow-lg shadow-blue-200 hover:bg-blue-700 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    إضافة إلى السلة
                </button>
            </section>
        </div>

        {{-- ======================================================== --}}
        {{-- ÉTAPE 2 : VALIDATION & RECAPITULATIF (Review & Confirm)   --}}
        {{-- ======================================================== --}}
        <div id="stepReview" class="hidden grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Colonne 1 : Détail des pièces & Livraison --}}
            <div class="space-y-6">
                <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-gray-900">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        تفاصيل القطع
                    </h3>
                    <div id="cartItemsList" class="space-y-4">
                        {{-- Rempli dynamiquement --}}
                    </div>
                    <div class="pt-4 mt-4 border-t-2 border-dashed border-gray-100 flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-800">المجموع</span>
                        <span id="reviewTotalAmount" class="text-2xl font-bold text-blue-700">0 درهم</span>
                    </div>
                </section>

                <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-gray-900">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                        خيارات التوصيل
                    </h3>
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl mb-4">
                        <input type="hidden" name="delivery_required" value="0">
                        <input 
                            type="checkbox" 
                            id="deliveryCheckbox" 
                            name="delivery_required" 
                            value="1"
                            @checked(old('delivery_required'))
                            class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                        />
                        <label for="deliveryCheckbox" class="font-bold text-gray-700 cursor-pointer">هل يريد العميل التوصيل؟</label>
                    </div>
                    
                    <div id="deliveryAddressSection" class="hidden space-y-4 pt-2">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">عنوان التوصيل</label>
                            <textarea 
                                name="delivery_address" 
                                id="deliveryAddressInput"
                                rows="3"
                                class="w-full p-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                                placeholder="أدخل العنوان الكامل..."
                            >{{ old('delivery_address') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-1">ملاحظات على الطلب</label>
                        <input 
                            type="text" 
                            name="notes" 
                            id="orderNotes" 
                            placeholder="أي ملاحظة أو تعليمات خاصة..."
                            class="w-full p-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm"
                            value="{{ old('notes') }}"
                        />
                    </div>
                </section>
            </div>

            {{-- Colonne 2 : Fiche Client & Caisse --}}
            <div class="space-y-6">
                <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold mb-6 flex items-center gap-2 text-gray-900">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        معلومات العميل
                        <span class="text-[10px] font-normal text-red-500 bg-red-50 px-2 py-0.5 rounded-full mr-auto">
                            للعميل الجديد: الاسم أو الهاتف
                        </span>
                    </h3>
                    
                    <div class="space-y-4">
                        {{-- Téléphone avec autocomplétion --}}
                        <div class="relative">
                            <label class="block text-sm font-bold text-gray-700 mb-1">رقم الهاتف</label>
                            <div class="relative">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                </span>
                                <input 
                                    type="tel"
                                    name="client_phone"
                                    id="customerPhone"
                                    value="{{ old('client_phone') }}"
                                    class="w-full pr-12 pl-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-mono"
                                    placeholder="مثال: 0612345678"
                                    autocomplete="off"
                                />
                            </div>
                            {{-- Dropdown de recherche client --}}
                            <div id="customerSuggestions" class="absolute z-30 mt-1 hidden w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl max-h-48 overflow-y-auto"></div>
                        </div>

                        {{-- Nom du client --}}
                        <div class="relative">
                            <label class="block text-sm font-bold text-gray-700 mb-1">اسم العميل</label>
                            <div class="relative">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                </span>
                                <input 
                                    type="text"
                                    name="client_name"
                                    id="customerName"
                                    value="{{ old('client_name') }}"
                                    class="w-full pr-12 pl-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm"
                                    placeholder="الاسم الكامل للعميل"
                                />
                            </div>
                            <div id="customerNameSuggestions" class="absolute z-30 mt-1 hidden w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl max-h-48 overflow-y-auto"></div>
                        </div>

                        {{-- Date de réception --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">تاريخ الاستلام</label>
                            <input 
                                type="date"
                                name="received_at"
                                id="receivedAt"
                                value="{{ old('received_at', now()->format('Y-m-d')) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm"
                                required
                            />
                        </div>
                    </div>

                    {{-- Encadré récapitulatif total --}}
                    <div class="mt-8 p-6 bg-blue-50 rounded-2xl border border-blue-100">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-blue-700 font-bold">المجموع النهائي:</span>
                            <span id="finalTotalDisplay" class="text-3xl font-black text-blue-900">0 درهم</span>
                        </div>
                        <p class="text-xs text-blue-600">سيتم تسجيل الطلب بحالة "تم الاستلام" تلقائياً.</p>
                    </div>

                    {{-- Toggle d'encaissement / Caisse --}}
                    <div class="mt-4 p-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div id="paidIconBox" class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 bg-gray-100 text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900">حالة الدفع</p>
                                    <p id="paidStatusText" class="text-[11px] font-bold text-gray-500 transition-colors">
                                        لم يتم الدفع بعد
                                    </p>
                                </div>
                            </div>
                            
                            <button
                                type="button"
                                id="togglePaidBtn"
                                class="relative inline-flex h-7 w-14 items-center rounded-full transition-all duration-300 bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <span id="togglePaidCircle" class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform duration-300 shadow-md translate-x-1"></span>
                            </button>
                        </div>
                    </div>

                    {{-- Boutons d'action finaux --}}
                    <div class="flex gap-4 mt-8">
                        <button
                            type="button"
                            id="backToItemsBtn"
                            class="flex-1 py-4 border-2 border-gray-200 text-gray-600 rounded-xl font-bold hover:bg-gray-50 transition-colors text-center"
                        >
                            تعديل القطع
                        </button>
                        <button
                            type="submit"
                            id="submitOrderBtn"
                            class="flex-[2] py-4 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 disabled:opacity-50 transition-all flex items-center justify-center gap-2 active:scale-98"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                            تسجيل الطلب
                        </button>
                    </div>
                </section>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Dictionnaire des pièces
    const PIECE_TYPES = [
        'سروال', 'قميجة', 'تيشورت', 'شورط', 'تريكو', 'فيستة', 'جاكيطة', 'جيلي',
        'سورفيت', 'أونصومبل', 'سبرديلة', 'كومبلي', 'مونطو', 'جلابة', 'قميص',
        'كسوة نساء', 'عباية', 'فراشة', 'زيف', 'جابادور', 'كندورة', 'فوقية',
        'قفطان', 'تكشيطة', 'صاية', 'طابلية', 'بنوار', 'فوطة', 'مانطة', 'كوفرلي',
        'لحاف', 'تلميط', 'إزار', 'ريدو', 'مخدة', 'زربية', 'موكيت', 'طابي',
        'صلاية', 'سلهام', 'لاغوب', 'كاسكيط', 'كاشكول', 'شكارة', 'صاك'
    ];

    // 2. Dictionnaire des couleurs et palettes hexadécimales
    const COLOR_MAP = {
        'أبيض': '#FFFFFF',
        'أسود': '#000000',
        'كري': '#808080',
        'أزرق': '#0000FF',
        'شيبي': '#ADD8E6',
        'بيج': '#F5F5DC',
        'مارو': '#800000',
        'أحمر': '#FF0000',
        'أخضر': '#008000',
        'أصفر': '#FFFF00',
        'صومو': '#FA8072',
        'ليموني': '#FFFACD',
        'غوز': '#FFC0CB',
        'موف': '#E0B0FF',
        'مزوق': 'linear-gradient(45deg, #ef4444, #3b82f6, #22c55e, #eab308)',
        'مخطط': 'repeating-linear-gradient(45deg, #ccc, #ccc 8px, #eee 8px, #eee 16px)'
    };
    const COLORS = Object.keys(COLOR_MAP);

    // Helpers DOM
    const $ = id => document.getElementById(id);
    const escapeHtml = value => String(value).replace(/[&<>'"]/g, character => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        "'": '&#039;',
        '"': '&quot;'
    })[character]);

    // États
    let currentStep = 'items';
    let selectedServices = [];
    let selectedPiece = '';
    let selectedColors = [];
    let quantity = 1;
    const SUGGESTED_PRICES = {
        'مصلوح': 5,
        'تصبين+مصلوح': 15,
        'صباغة': 30,
        'أفرشة': 20
    };
    const PRICE_RULES_VERSION = 'v2';
    let price = 20;
    let isPaid = false;
    let cart = [];

    function normalizeQuantity(value) {
        return Math.round(Number(value) * 10) / 10;
    }

    // --- Génération de la grille des types de pièces ---
    function renderPiecesGrid(filter = '') {
        const filtered = PIECE_TYPES.filter(t => !filter || t.includes(filter));
        $('piecesGrid').innerHTML = filtered.map(t => `
            <button
                type="button"
                data-type="${escapeHtml(t)}"
                class="piece-btn py-2 px-3 rounded-lg border text-sm transition-all truncate font-bold text-center ${
                    selectedPiece === t 
                    ? 'border-blue-600 bg-blue-600 text-white shadow-md' 
                    : 'border-gray-100 bg-gray-50 text-gray-600 hover:bg-gray-100'
                }"
            >
                ${escapeHtml(t)}
            </button>
        `).join('');

        document.querySelectorAll('.piece-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                selectedPiece = btn.dataset.type;
                $('pieceSearchInput').value = selectedPiece;
                renderPiecesGrid(selectedPiece);
                checkLearnedPrice();
                validateAddBtn();
            });
        });
    }

    $('pieceSearchInput').addEventListener('input', (e) => {
        selectedPiece = e.target.value.trim();
        renderPiecesGrid(selectedPiece);
        checkLearnedPrice();
        validateAddBtn();
    });

    // --- Génération de la palette des couleurs ---
    function renderColorsGrid() {
        $('colorsGrid').innerHTML = COLORS.map(c => {
            const isSelected = selectedColors.includes(c);
            const isWhite = c === 'أبيض';
            const bg = COLOR_MAP[c];
            return `
                <button
                    type="button"
                    data-color="${c}"
                    class="color-btn py-2.5 px-3 rounded-xl border text-sm transition-all flex items-center gap-2 ${
                        isSelected 
                        ? 'border-blue-600 bg-blue-50 text-blue-700 font-bold' 
                        : 'border-gray-100 bg-gray-50 text-gray-600 hover:bg-gray-100'
                    }"
                >
                    <div 
                        class="w-4 h-4 rounded-full border border-gray-300 shadow-inner shrink-0" 
                        style="background: ${bg}; ${isWhite ? 'border-color: #cbd5e1;' : ''}"
                    ></div>
                    <span class="truncate text-xs font-bold">${c}</span>
                    ${isSelected ? '<span class="mr-auto text-blue-600 font-bold">✓</span>' : ''}
                </button>
            `;
        }).join('');

        document.querySelectorAll('.color-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const c = btn.dataset.color;
                if (selectedColors.includes(c)) {
                    selectedColors = selectedColors.filter(x => x !== c);
                } else {
                    selectedColors.push(c);
                }
                $('customColorInput').value = selectedColors.join(' / ');
                renderColorsGrid();
                validateAddBtn();
            });
        });
    }

    $('customColorInput').addEventListener('input', (e) => {
        const val = e.target.value.trim();
        selectedColors = val ? val.split('/').map(s => s.trim()).filter(Boolean) : [];
        e.target.value = selectedColors.join(' / ');
        renderColorsGrid();
        validateAddBtn();
    });

    // --- Gestion des boutons de Services ---
    document.querySelectorAll('.service-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const s = btn.dataset.service;
            if (selectedServices.includes(s)) {
                selectedServices = selectedServices.filter(x => x !== s);
                btn.className = 'service-btn flex-1 py-3 px-2 sm:px-4 rounded-xl border-2 border-gray-100 bg-white text-gray-600 hover:border-gray-200 transition-all font-bold text-center text-sm sm:text-base';
            } else {
                selectedServices.push(s);
                btn.className = 'service-btn flex-1 py-3 px-2 sm:px-4 rounded-xl border-2 border-blue-600 bg-blue-50 text-blue-700 shadow-sm transition-all font-bold text-center text-sm sm:text-base';
            }
            
            // تحديث تسمية الكمية حسب نوع الخدمة
            if (selectedServices.includes('أفرشة')) {
                $('quantityLabel').textContent = 'عدد المترات';
                $('priceLabel').textContent = 'السعر لكل متر';
                $('qtyDisplay').min = '0.1';
                $('qtyDisplay').step = '0.1';
                $('priceInput').value = '20';
            } else {
                $('quantityLabel').textContent = 'الكمية';
                $('priceLabel').textContent = 'السعر المقترح (للقطعة)';
                $('qtyDisplay').min = '1';
                $('qtyDisplay').step = '1';
                quantity = 1;
                $('qtyDisplay').value = 1;
            }
            
            checkLearnedPrice();
            validateAddBtn();
        });
    });

    $('qtyDisplay').addEventListener('input', (event) => {
        const value = event.target.value;
        if (value === '') {
            quantity = 0;
            validateAddBtn();
            return;
        }

        quantity = normalizeQuantity(value);
        validateAddBtn();
    });
    $('qtyDisplay').addEventListener('blur', (event) => {
        const minimum = selectedServices.includes('أفرشة') ? 0.1 : 1;
        quantity = Math.max(minimum, normalizeQuantity(event.target.value) || minimum);
        event.target.value = quantity.toFixed(1).replace(/\.0$/, '');
        validateAddBtn();
    });

    // --- Mémorisation automatique des prix (Learned Price) ---
    function getPriceKey() {
        if (!selectedPiece || !selectedServices.length) return null;
        return `nadif_price_${PRICE_RULES_VERSION}_${selectedServices.slice().sort().join('+')}_${selectedPiece}`;
    }

    function getSuggestedPrice() {
        const serviceKey = selectedServices.slice().sort().join('+');
        return SUGGESTED_PRICES[serviceKey] ?? 15;
    }

    function checkLearnedPrice() {
        // سعر الأفرشة يدخله المستخدم لكل متر.
        if (selectedServices.includes('أفرشة')) {
            price = Number($('priceInput').value) || 20;
            return;
        }

        const key = getPriceKey();
        price = getSuggestedPrice();
        if (key) {
            const stored = localStorage.getItem(key);
            if (stored) {
                price = Number(stored);
            }
        }
        $('priceInput').value = price;
    }

    // --- Compteurs Quantité & Prix ---
    $('qtyMinus').addEventListener('click', () => {
        const step = selectedServices.includes('أفرشة') ? 0.1 : 1;
        quantity = Math.max(step, normalizeQuantity(quantity - step));
        $('qtyDisplay').value = quantity.toFixed(1).replace(/\.0$/, '');
    });
    $('qtyPlus').addEventListener('click', () => {
        quantity = normalizeQuantity(quantity + (selectedServices.includes('أفرشة') ? 0.1 : 1));
        $('qtyDisplay').value = quantity.toFixed(1).replace(/\.0$/, '');
    });
    $('priceMinus').addEventListener('click', () => {
        price = Math.max(1, price - 1);
        $('priceInput').value = price;
    });
    $('pricePlus').addEventListener('click', () => {
        price += 1;
        $('priceInput').value = price;
    });
    $('priceInput').addEventListener('input', (e) => {
        price = Math.max(1, Number(e.target.value) || 1);
    });
    $('priceInput').addEventListener('blur', () => {
        $('priceInput').value = price;
    });

    // --- Validation du bouton d'ajout ---
    function validateAddBtn() {
        let isValid = selectedPiece && selectedServices.length > 0 && selectedColors.length > 0;
        
        // إذا اختار العميل أفرشة، يجب إدخال عدد المترات
        if (selectedServices.includes('أفرشة')) {
            if (quantity <= 0) isValid = false;
        }

        $('addToCartBtn').disabled = !isValid;
    }

    // --- Ajout au panier ---
    $('addToCartBtn').addEventListener('click', () => {
        if (!selectedPiece || !selectedServices.length || !selectedColors.length) return;

        const minimumQuantity = selectedServices.includes('أفرشة') ? 0.1 : 1;
        quantity = Math.max(minimumQuantity, normalizeQuantity($('qtyDisplay').value) || 0);
        if (quantity <= 0) {
            $('qtyDisplay').focus();
            return;
        }

        // Mémoriser le prix des services classiques.
        if (!selectedServices.includes('أفرشة')) {
            const key = getPriceKey();
            if (key) localStorage.setItem(key, String(price));
        }

        cart.push({
            id: 'item_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5),
            service: [...selectedServices],
            type: selectedPiece,
            color: selectedColors.join(' / '),
            quantity: quantity,
            unit_price: price,
            total_price: quantity * price
        });

        // Reset formulaire
        selectedServices = [];
        selectedPiece = '';
        selectedColors = [];
        quantity = 1;
        price = 20;

        $('qtyDisplay').value = 1;
        $('qtyDisplay').min = '1';
        $('qtyDisplay').step = '1';
        $('priceInput').value = price;
        $('pieceSearchInput').value = '';
        $('customColorInput').value = '';
        
        $('quantityLabel').textContent = 'الكمية';
        $('priceLabel').textContent = 'السعر المقترح (للقطعة)';

        document.querySelectorAll('.service-btn').forEach(b => {
            b.className = 'service-btn flex-1 py-3 px-2 sm:px-4 rounded-xl border-2 border-gray-100 bg-white text-gray-600 hover:border-gray-200 transition-all font-bold text-center text-sm sm:text-base';
        });

        renderPiecesGrid();
        renderColorsGrid();
        validateAddBtn();
        updateCartBadge();
    });

    function updateCartBadge() {
        $('itemsJson').value = JSON.stringify(cart);
        const count = cart.reduce((s, i) => s + i.quantity, 0);
        $('cartHeaderBadge').textContent = count;
        if (cart.length > 0 && currentStep === 'items') {
            $('cartHeaderBtn').classList.remove('hidden');
        } else {
            $('cartHeaderBtn').classList.add('hidden');
        }
    }

    // --- Navigation entre Étape 1 et 2 ---
    function switchStep(step) {
        currentStep = step;
        if (step === 'review') {
            $('stepItems').classList.add('hidden');
            $('stepReview').classList.remove('hidden');
            $('pageTitle').textContent = 'مراجعة الطلب وتأكيده';
            $('cartHeaderBtn').classList.add('hidden');
            renderReviewCart();
        } else {
            $('stepReview').classList.add('hidden');
            $('stepItems').classList.remove('hidden');
            $('pageTitle').textContent = 'تسجيل طلب جديد';
            if (cart.length > 0) $('cartHeaderBtn').classList.remove('hidden');
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    $('cartHeaderBtn').addEventListener('click', () => switchStep('review'));
    $('backToItemsBtn').addEventListener('click', () => switchStep('items'));

    // --- Affichage du panier récapitulatif ---
    function renderReviewCart() {
        const total = cart.reduce((sum, item) => sum + item.total_price, 0);

        $('cartItemsList').innerHTML = cart.map((item, index) => `
            <div class="group flex justify-between items-center pb-4 border-b border-gray-50 last:border-0 last:pb-0">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        data-remove-index="${index}"
                        class="remove-cart-item p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"
                        title="حذف القطعة"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    </button>
                    <div>
                        <p class="font-bold text-gray-800">
                            <span class="text-blue-600 ml-1 font-mono font-black">${item.service.includes('أفرشة') ? `${item.quantity} متر` : `${item.quantity}x`}</span>
                            ${escapeHtml(item.type)} 
                            <span class="text-xs text-gray-500 font-normal">(${escapeHtml(item.color)})</span>
                            ${item.dimensions ? `<span class="inline-block mr-2 px-2 py-0.5 bg-indigo-100 text-indigo-700 text-xs rounded-full font-bold" dir="ltr">مقاس: ${escapeHtml(item.dimensions)}</span>` : ''}
                        </p>
                        <p class="text-xs text-gray-400">${escapeHtml(item.service.join(' + '))} · ${item.unit_price} درهم ${item.service.includes('أفرشة') ? 'للمتر' : 'للقطعة'}</p>
                    </div>
                </div>
                <p class="font-bold text-blue-600">${item.total_price} درهم</p>
            </div>
        `).join('');

        $('reviewTotalAmount').textContent = `${total} درهم`;
        $('finalTotalDisplay').textContent = `${total} درهم`;

        document.querySelectorAll('.remove-cart-item').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const idx = Number(btn.dataset.removeIndex);
                cart.splice(idx, 1);
                updateCartBadge();
                if (cart.length === 0) {
                    switchStep('items');
                } else {
                    renderReviewCart();
                }
            });
        });
    }

    // --- Toggle Livraison ---
    $('deliveryCheckbox').addEventListener('change', (e) => {
        if (e.target.checked) {
            $('deliveryAddressSection').classList.remove('hidden');
            $('deliveryAddressInput').required = true;
            $('deliveryAddressInput').focus();
        } else {
            $('deliveryAddressSection').classList.add('hidden');
            $('deliveryAddressInput').required = false;
        }
    });

    // --- Toggle de Caisse / Paiement ---
    $('togglePaidBtn').addEventListener('click', () => {
        isPaid = !isPaid;
        $('paymentStatusInput').value = isPaid ? 'paid' : 'unpaid';

        if (isPaid) {
            $('paidIconBox').className = 'w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 bg-green-100 text-green-600 scale-110';
            $('paidStatusText').className = 'text-[11px] font-bold text-green-600 transition-colors';
            $('paidStatusText').textContent = 'تم استلام المبلغ نقداً';
            $('togglePaidBtn').className = 'relative inline-flex h-7 w-14 items-center rounded-full transition-all duration-300 bg-green-500 focus:outline-none focus:ring-2 focus:ring-blue-500';
            $('togglePaidCircle').className = 'inline-block h-5 w-5 transform rounded-full bg-white transition-transform duration-300 shadow-md -translate-x-8';
        } else {
            $('paidIconBox').className = 'w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 bg-gray-100 text-gray-400';
            $('paidStatusText').className = 'text-[11px] font-bold text-gray-500 transition-colors';
            $('paidStatusText').textContent = 'لم يتم الدفع بعد';
            $('togglePaidBtn').className = 'relative inline-flex h-7 w-14 items-center rounded-full transition-all duration-300 bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500';
            $('togglePaidCircle').className = 'inline-block h-5 w-5 transform rounded-full bg-white transition-transform duration-300 shadow-md translate-x-1';
        }
    });

    // --- Recherche / Autocomplétion client par téléphone ou nom ---
    let searchTimer;
    $('customerPhone').addEventListener('input', (e) => {
        clearTimeout(searchTimer);
        const q = e.target.value.trim();
        if (q.length < 3) {
            $('customerSuggestions').classList.add('hidden');
            return;
        }

        searchTimer = setTimeout(async () => {
            try {
                const res = await fetch(`{{ route('laundry.clients.search') }}?q=${encodeURIComponent(q)}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const clients = await res.json();
                if (clients.length) {
                    $('customerSuggestions').innerHTML = clients.map(c => `
                        <button 
                            type="button" 
                            data-id="${c.id}" 
                            data-name="${escapeHtml(c.name)}" 
                            data-phone="${escapeHtml(c.phone)}" 
                            class="client-sugg-item block w-full px-4 py-2.5 text-right hover:bg-blue-50 border-b border-gray-100 last:border-0"
                        >
                            <span class="block font-bold text-sm text-gray-900">${escapeHtml(c.name)}</span>
                            <span class="text-xs text-gray-500 font-mono">${escapeHtml(c.phone)}</span>
                        </button>
                    `).join('');
                    $('customerSuggestions').classList.remove('hidden');

                    document.querySelectorAll('.client-sugg-item').forEach(b => {
                        b.addEventListener('click', () => {
                            $('clientId').value = b.dataset.id;
                            $('customerName').value = b.dataset.name;
                            $('customerPhone').value = b.dataset.phone;
                            $('customerSuggestions').classList.add('hidden');
                        });
                    });
                } else {
                    $('customerSuggestions').classList.add('hidden');
                }
            } catch (err) {
                $('customerSuggestions').classList.add('hidden');
            }
        }, 250);
    });

    $('customerName').addEventListener('input', (e) => {
        clearTimeout(searchTimer);
        $('clientId').value = '';
        const q = e.target.value.trim();
        if (q.length < 2) {
            $('customerNameSuggestions').classList.add('hidden');
            return;
        }

        searchTimer = setTimeout(async () => {
            try {
                const res = await fetch(`{{ route('laundry.clients.search') }}?q=${encodeURIComponent(q)}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const clients = await res.json();
                $('customerNameSuggestions').innerHTML = clients.map(c => `
                    <button type="button" data-id="${c.id}" data-name="${escapeHtml(c.name)}" data-phone="${escapeHtml(c.phone)}"
                        class="client-name-sugg-item block w-full border-b border-gray-100 px-4 py-2.5 text-right hover:bg-blue-50 last:border-0">
                        <span class="block text-sm font-bold text-gray-900">${escapeHtml(c.name)}</span>
                        <span class="text-xs font-mono text-gray-500">${escapeHtml(c.phone)}</span>
                    </button>
                `).join('');
                $('customerNameSuggestions').classList.toggle('hidden', clients.length === 0);

                document.querySelectorAll('.client-name-sugg-item').forEach(button => {
                    button.addEventListener('click', () => {
                        $('clientId').value = button.dataset.id;
                        $('customerName').value = button.dataset.name;
                        $('customerPhone').value = button.dataset.phone;
                        $('customerNameSuggestions').classList.add('hidden');
                    });
                });
            } catch (_) {
                $('customerNameSuggestions').classList.add('hidden');
            }
        }, 250);
    });

    $('customerPhone').addEventListener('input', () => {
        $('clientId').value = '';
    });

    // Fermer les suggestions au clic en dehors
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#customerSuggestions') && !e.target.closest('#customerNameSuggestions') && e.target !== $('customerPhone') && e.target !== $('customerName')) {
            $('customerSuggestions').classList.add('hidden');
            $('customerNameSuggestions').classList.add('hidden');
        }
    });

    // --- Validation avant soumission du formulaire ---
    $('orderForm').addEventListener('submit', (e) => {
        if (!cart.length) {
            e.preventDefault();
            switchStep('items');
            alert('الرجاء إضافة قطعة واحدة على الأقل إلى السلة.');
            return;
        }

        const phone = $('customerPhone').value.trim();
        const name = $('customerName').value.trim();
        const hasClient = $('clientId').value;

        if (!hasClient && !name && !phone) {
            e.preventDefault();
            alert('الرجاء إدخال اسم العميل أو رقم هاتفه، أو اختيار عميل موجود.');
            $('customerName').focus();
            return;
        }
    });

    // Synchroniser l'état de paiement restauré après une erreur de validation.
    if ($('paymentStatusInput').value === 'paid') {
        $('togglePaidBtn').click();
    }

    // Restaurer correctement l'option de livraison après une erreur de validation.
    if ($('deliveryCheckbox').checked) {
        $('deliveryAddressSection').classList.remove('hidden');
        $('deliveryAddressInput').required = true;
    }

    // Initialisation
    renderPiecesGrid();
    renderColorsGrid();
});
</script>
@endsection