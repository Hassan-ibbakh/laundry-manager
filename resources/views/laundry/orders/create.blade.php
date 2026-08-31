@extends('layouts.laundry')
@section('title', 'طلب جديد')
@section('content')

<div class="max-w-4xl mx-auto px-4 py-6">

    {{-- رأس الصفحة --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">طلب جديد</h1>
            <p class="text-gray-500 text-sm">إنشاء طلب غسيل في خطوات سهلة</p>
        </div>
        <span class="text-4xl"></span>
    </div>

    {{-- عرض الأخطاء (التحقق من الخادم) --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-500 text-red-700 px-4 py-3 rounded-xl mb-6">
            <p class="font-semibold mb-2">⚠️ حدثت أخطاء أثناء معالجة الطلب:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- شريط التقدم --}}
    <div class="flex items-center justify-between mb-10 relative">
        <div class="absolute left-0 right-0 top-1/2 h-1 bg-gray-200 -translate-y-1/2 z-0">
            <div id="progress-bar" class="h-full bg-blue-500 transition-all duration-500" style="width: 0%;"></div>
        </div>
        @foreach(['👤 العميل', ' الأغراض', ' المراجعة'] as $stepLabel)
            <div class="flex flex-col items-center z-10">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm transition-colors duration-300 step-indicator"
                     data-step="{{ $loop->index }}">{{ $loop->iteration }}</div>
                <span class="text-xs text-gray-500 mt-1">{{ $stepLabel }}</span>
            </div>
        @endforeach
    </div>

    {{-- النموذج --}}
    <form id="orderForm" method="POST" action="{{ route('laundry.orders.store') }}" class="bg-white rounded-2xl shadow-xl overflow-hidden">
        @csrf

        <div class="p-6 md:p-8">

            {{-- الخطوة 1: العميل --}}
            <div id="step-0" class="step-content">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="text-2xl">👤</span> اختيار العميل
                </h2>

                {{-- بحث العميل --}}
                <div class="relative mb-4">
                    <input type="text" id="clientSearch" placeholder="ابحث باسم أو رقم الهاتف..."
                           class="w-full pl-4 pr-10 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div id="suggestions" class="bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto hidden divide-y divide-gray-100"></div>

                {{-- العميل المحدد --}}
                <div id="selectedClientDisplay" class="hidden mt-4 p-4 bg-blue-50 rounded-xl border border-blue-200 items-center justify-between">
                    <div>
                        <span class="font-medium text-gray-800" id="selectedClientName"></span>
                        <span class="text-sm text-gray-500 block" id="selectedClientPhone"></span>
                    </div>
                    <button type="button" id="changeClientBtn" class="text-sm text-blue-600 hover:underline">تغيير</button>
                </div>

                <input type="hidden" name="client_id" id="client_id" value="{{ old('client_id') }}">

                {{-- عميل جديد --}}
                <button type="button" id="newClientToggle" class="mt-4 text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center gap-1">
                    <span>＋</span> إضافة عميل جديد
                </button>

                <div id="newClientFields" class="mt-4 space-y-4 hidden">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">الاسم الكامل</label>
                        <input type="text" name="client_name" id="client_name" value="{{ old('client_name') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
                        <input type="text" name="client_phone" id="client_phone" value="{{ old('client_phone') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>
            </div>

            {{-- الخطوة 2: الأغراض --}}
            <div id="step-1" class="step-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="text-2xl">🧾</span> تفاصيل الملابس
                </h2>

                {{-- نموذج إضافة قطعة (الخدمة، النوع، اللون، الكمية، السعر) --}}
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-4">
                    <div>
                        <span class="block text-sm font-medium text-gray-700">الخدمة <span class="text-red-500">*</span></span>
                        <div id="itemServices" class="space-y-2 rounded-lg border border-gray-300 p-3">
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" value="تصبين" class="item-service h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-400">
                                تصبين
                            </label>
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" value="مصلوح" class="item-service h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-400">
                                مصلوح
                            </label>
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" value="صباغة" class="item-service h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-400">
                                صباغة
                            </label>
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" value="توصيل" class="item-service h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-400">
                                توصيل
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">النوع</label>
                        <div class="relative">
                            <input type="text" id="itemType" placeholder="اكتب نوع القطعة..." autocomplete="off"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <div id="itemTypeSuggestions" class="absolute right-0 left-0 top-full z-20 mt-1 hidden max-h-60 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg"></div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">اللون (اختياري)</label>
                        <div class="relative">
                            <input type="text" id="itemColor" placeholder="اكتب اللون..." autocomplete="off"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <div id="itemColorSuggestions" class="absolute right-0 left-0 top-full z-20 mt-1 hidden max-h-60 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg"></div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">العدد</label>
                        <input type="number" id="itemQuantity" value="1" min="1"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">السعر للقطعة (DH)</label>
                        <input type="number" id="itemPrice" step="0.01" min="0" placeholder="0.00"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>
                <button type="button" id="addItemBtn" class="mb-6 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    ➕ إضافة قطعة
                </button>

                {{-- عربة الطلب --}}
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <h3 class="font-medium text-gray-700 mb-3">ملخص الطلب</h3>
                    <div id="cartItems" class="space-y-2 max-h-60 overflow-y-auto"></div>
                    <div class="flex justify-between border-t pt-3 mt-2 font-bold text-gray-800">
                        <span>الإجمالي</span>
                        <span id="cartTotal">م.د 0.00</span>
                    </div>
                </div>

                {{-- باقي بيانات الطلب --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">تاريخ الاستلام <span class="text-red-500">*</span></label>
                        <input type="date" name="received_at" id="received_at" value="{{ old('received_at', date('Y-m-d')) }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">ملاحظات (اختياري)</label>
                    <textarea name="notes" id="notes" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('notes') }}</textarea>
                </div>

                {{-- حقل مخفي للأغراض --}}
                <input type="hidden" name="items" id="itemsJson">
            </div>

            {{-- الخطوة 3: المراجعة --}}
            <div id="step-2" class="step-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="text-2xl">✅</span> مراجعة الطلب
                </h2>
                <div class="bg-gray-50 rounded-xl p-6 space-y-4 border border-gray-200">
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">العميل</span>
                        <span id="reviewClient" class="font-medium text-gray-900">—</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">تاريخ الاستلام</span>
                        <span id="reviewDate" class="font-medium text-gray-900">—</span>
                    </div>
                    <div class="border-b pb-2">
                        <span class="text-gray-600 block mb-1">القطع</span>
                        <div id="reviewItems" class="space-y-1 text-sm text-gray-700"></div>
                    </div>
                    <div class="flex justify-between font-bold text-lg">
                        <span>المجموع</span>
                        <span id="reviewTotal" class="text-blue-600">م.د 0.00</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- أزرار التنقل --}}
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
            <button type="button" id="prevBtn" class="px-6 py-2 text-gray-600 hover:text-gray-800 font-medium hidden">← السابق</button>
            <button type="button" id="nextBtn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">التالي →</button>
            <button type="submit" id="submitBtn" class="px-8 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium hidden">✓ إنشاء الطلب</button>
        </div>
    </form>
</div>

<style>
    .step-content { animation: fadeSlide 0.3s ease-out; }
    @keyframes fadeSlide { 0% { opacity:0; transform:translateY(12px); } 100% { opacity:1; transform:translateY(0); } }
    .suggestion-item { padding:10px 16px; cursor:pointer; transition:background 0.15s; }
    .suggestion-item:hover { background:#f3f4f6; }
    .suggestion-item .name { font-weight:500; color:#1f2937; }
    .suggestion-item .phone { font-size:0.85rem; color:#6b7280; margin-right:12px; }
    .cart-item { display:flex; justify-content:space-between; align-items:center; background:white; padding:8px 12px; border-radius:8px; border:1px solid #e5e7eb; }
    .cart-item .item-detail { flex:1; }
    .remove-item { color:#ef4444; cursor:pointer; font-weight:bold; padding:0 8px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // ----- الخطوات -----
    const steps = document.querySelectorAll('.step-content');
    const indicators = document.querySelectorAll('.step-indicator');
    const progressBar = document.getElementById('progress-bar');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    let currentStep = 0;
    const totalSteps = 3;

    // ----- PANIER -----
    let cart = [];

    const clothingTypes = [
        'سروال', 'قميجة', 'تيشورت', 'شورط', 'تريكو', 'فيستة', 'جاكيطة',
        'جيلي', 'سورفيت', 'أونصومبل', 'سبرديلة', 'كومبلي', 'مونطو', 'جلابة',
        'قميص', 'كسوة نساء', 'عباية', 'فراشة', 'زيف', 'جابادور', 'كندورة',
        'فوقية', 'قفطان', 'تكشيطة', 'صاية', 'طابلية', 'بنوار', 'فوطة',
        'مانطة', 'كوفرلي', 'لحاف', 'تلميط', 'إزار', 'ريدو', 'مخدة', 'زربية',
        'موكيت', 'طابي', 'صلاية', 'سلهام', 'لاغوب', 'كاسكيط', 'كاشكول',
        'شكارة', 'صاك'
    ];

    const itemTypeInput = document.getElementById('itemType');
    const itemTypeSuggestions = document.getElementById('itemTypeSuggestions');

    function updateTypeSuggestions() {
        const query = itemTypeInput.value.trim();
        itemTypeSuggestions.innerHTML = '';

        if (!query) {
            itemTypeSuggestions.classList.add('hidden');
            return;
        }

        const matches = clothingTypes.filter(type => type.includes(query));
        matches.forEach(type => {
            const option = document.createElement('button');
            option.type = 'button';
            option.className = 'block w-full px-3 py-2 text-right text-sm hover:bg-blue-50';
            option.textContent = type;
            option.addEventListener('mousedown', function(event) {
                event.preventDefault();
                itemTypeInput.value = type;
                itemTypeSuggestions.classList.add('hidden');
            });
            itemTypeSuggestions.appendChild(option);
        });

        itemTypeSuggestions.classList.toggle('hidden', matches.length === 0);
    }

    itemTypeInput.addEventListener('input', updateTypeSuggestions);

    const clothingColors = [
        'أبيض', 'أسود', 'كري', 'أزرق', 'شيبي', 'بيج', 'مارو', 'أحمر',
        'أخضر', 'أصفر', 'صومو', 'ليموني', 'غوز', 'موف', 'مزوق', 'مخطط'
    ];
    const itemColorInput = document.getElementById('itemColor');
    const itemColorSuggestions = document.getElementById('itemColorSuggestions');

    function updateColorSuggestions() {
        const query = itemColorInput.value.trim();
        itemColorSuggestions.innerHTML = '';

        if (!query) {
            itemColorSuggestions.classList.add('hidden');
            return;
        }

        const matches = clothingColors.filter(color => color.includes(query));
        matches.forEach(color => {
            const option = document.createElement('button');
            option.type = 'button';
            option.className = 'block w-full px-3 py-2 text-right text-sm hover:bg-blue-50';
            option.textContent = color;
            option.addEventListener('mousedown', function(event) {
                event.preventDefault();
                itemColorInput.value = color;
                itemColorSuggestions.classList.add('hidden');
            });
            itemColorSuggestions.appendChild(option);
        });

        itemColorSuggestions.classList.toggle('hidden', matches.length === 0);
    }

    itemColorInput.addEventListener('input', updateColorSuggestions);
    document.addEventListener('click', function(event) {
        if (!itemTypeInput.contains(event.target) && !itemTypeSuggestions.contains(event.target)) {
            itemTypeSuggestions.classList.add('hidden');
        }
        if (!itemColorInput.contains(event.target) && !itemColorSuggestions.contains(event.target)) {
            itemColorSuggestions.classList.add('hidden');
        }
    });

    function updateCartUI() {
        const container = document.getElementById('cartItems');
        const totalSpan = document.getElementById('cartTotal');
        container.innerHTML = '';
        let total = 0;
        cart.forEach((item, index) => {
            const subTotal = item.quantity * item.unit_price;
            total += subTotal;
            const div = document.createElement('div');
            div.className = 'cart-item';
            div.innerHTML = `
                <div class="item-detail">
                    <span class="font-medium">${item.type}</span>
                    ${item.color ? `<span class="text-sm text-gray-500"> - ${item.color}</span>` : ''}
                    <span class="text-xs text-gray-400 mx-1">(${item.service.join(' + ')})</span>
                    <span class="text-sm text-gray-500 mx-2">(${item.quantity} قطعة)</span>
                    <span class="text-sm font-bold">${subTotal.toFixed(2)} م.د</span>
                </div>
                <span class="remove-item" data-index="${index}">✕</span>
            `;
            container.appendChild(div);
        });
        document.querySelectorAll('.remove-item').forEach(el => {
            el.addEventListener('click', function() {
                const idx = parseInt(this.dataset.index);
                cart.splice(idx, 1);
                updateCartUI();
            });
        });
        totalSpan.textContent = 'م.د ' + total.toFixed(2);
        document.getElementById('itemsJson').value = JSON.stringify(cart);
    }

    // إضافة قطعة
    document.getElementById('addItemBtn').addEventListener('click', function() {
        const service = Array.from(document.querySelectorAll('.item-service:checked')).map(input => input.value);
        const type = document.getElementById('itemType').value.trim();
        const color = document.getElementById('itemColor').value.trim();
        const quantity = parseInt(document.getElementById('itemQuantity').value) || 1;
        const unit_price = parseFloat(document.getElementById('itemPrice').value) || 0;

        if (service.length === 0) {
            alert('الرجاء اختيار الخدمة.');
            return;
        }
        if (!type) {
            alert('الرجاء إدخال نوع القطعة.');
            return;
        }
        if (quantity < 1 || unit_price < 0) {
            alert('العدد يجب أن يكون 1 على الأقل والسعر موجب.');
            return;
        }

        cart.push({ service, type, color, quantity, unit_price });
        updateCartUI();

        document.querySelectorAll('.item-service').forEach(input => input.checked = false);
        itemTypeInput.value = '';
        itemTypeSuggestions.classList.add('hidden');
        itemColorInput.value = '';
        itemColorSuggestions.classList.add('hidden');
        document.getElementById('itemQuantity').value = 1;
        document.getElementById('itemPrice').value = '';
    });

    // ----- بحث العميل -----
    const searchInput = document.getElementById('clientSearch');
    const suggestions = document.getElementById('suggestions');
    const selectedDisplay = document.getElementById('selectedClientDisplay');
    const selectedName = document.getElementById('selectedClientName');
    const selectedPhone = document.getElementById('selectedClientPhone');
    const clientIdInput = document.getElementById('client_id');
    const changeBtn = document.getElementById('changeClientBtn');
    const newClientToggle = document.getElementById('newClientToggle');
    const newClientFields = document.getElementById('newClientFields');

    const clientNameInput = document.getElementById('client_name');
    const clientPhoneInput = document.getElementById('client_phone');

    // منع زر "Enter" من إرسال النموذج في جميع الحقول
    // (ليس فقط في حقل البحث) — يمنع الإرسال غير المقصود
    document.getElementById('orderForm').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && e.target.id !== 'submitBtn') {
            e.preventDefault();
        }
    });

    // ----- حالة العميل: مصدر واحد للحقيقة -----
    // الوضع: 'none' | 'existing' | 'new'
    function setClientMode(mode) {
        if (mode === 'existing') {
            // نمسح حقول العميل الجديد حتى لا يُرسل الوضعان معاً
            clientNameInput.value = '';
            clientPhoneInput.value = '';
            newClientFields.classList.add('hidden');
        } else if (mode === 'new') {
            clientIdInput.value = '';
            selectedDisplay.classList.add('hidden');
            selectedDisplay.classList.remove('flex');
            newClientFields.classList.remove('hidden');
        } else { // 'none'
            clientIdInput.value = '';
            selectedDisplay.classList.add('hidden');
            selectedDisplay.classList.remove('flex');
            newClientFields.classList.add('hidden');
        }
    }

    function selectClient(client) {
        clientIdInput.value = client.id;
        selectedName.textContent = client.name;
        selectedPhone.textContent = client.phone;
        selectedDisplay.classList.remove('hidden');
        selectedDisplay.classList.add('flex');
        searchInput.value = '';
        suggestions.classList.add('hidden');
        setClientMode('existing');
    }

    function clearSelection() {
        searchInput.value = '';
        suggestions.classList.add('hidden');
        setClientMode('none');
    }

    changeBtn.addEventListener('click', clearSelection);

    let searchTimeout = null;
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 2) {
            suggestions.classList.add('hidden');
            return;
        }
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetch(`{{ route('laundry.clients.search') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestions.innerHTML = '';
                    if (data.length === 0) {
                        suggestions.innerHTML = '<div class="p-3 text-gray-500 text-sm">لا يوجد عملاء</div>';
                    } else {
                        data.forEach(client => {
                            const div = document.createElement('div');
                            div.className = 'suggestion-item flex justify-between items-center';
                            div.innerHTML = `<span class="name">${client.name}</span><span class="phone">${client.phone}</span>`;
                            // mousedown (وليس click) : يعمل قبل فقدان التركيز للمدخل،
                            // مما يمنع سباق الأحداث الذي قد يلغي الاختيار
                            div.addEventListener('mousedown', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                selectClient(client);
                            });
                            suggestions.appendChild(div);
                        });
                    }
                    suggestions.classList.remove('hidden');
                })
                .catch(() => {
                    suggestions.innerHTML = '<div class="p-3 text-red-500 text-sm">حدث خطأ أثناء البحث</div>';
                    suggestions.classList.remove('hidden');
                });
        }, 300);
    });

    // إغلاق الاقتراحات فقط عند النقر خارج أي عنصر عميل
    document.addEventListener('click', function(e) {
        const isClickOnSuggestion = suggestions.contains(e.target);
        const isClickOnSearchInput = e.target === searchInput;
        const isClickOnSelectedDisplay = selectedDisplay.contains(e.target);

        if (!isClickOnSuggestion && !isClickOnSearchInput && !isClickOnSelectedDisplay) {
            suggestions.classList.add('hidden');
        }
    }, true); // استخدام التقاط الحدث بدلاً من الفقاعية للتحكم الأفضل

    newClientToggle.addEventListener('click', function() {
        if (newClientFields.classList.contains('hidden')) {
            setClientMode('new');
        } else {
            setClientMode('none');
        }
    });

    // ----- التنقل -----
    function goToStep(step) {
        if (step < 0 || step >= totalSteps) return;
        if (step === 1 && currentStep === 0) {
            const clientId = clientIdInput.value.trim();
            const clientName = clientNameInput.value.trim();
            const clientPhone = clientPhoneInput.value.trim();

            // يجب أن يكون وضع واحد فقط صالحاً: إما عميل موجود (id)،
            // أو عميل جديد مكتمل (الاسم والهاتف). لا يمكن أن يكون كلاهما أو جزءياً.
            const hasExisting = clientId.length > 0;
            const hasNew = clientName.length > 0 && clientPhone.length > 0;

            if (!hasExisting && !hasNew) {
                if (clientName.length > 0 || clientPhone.length > 0) {
                    alert('الرجاء إدخال الاسم ورقم الهاتف معاً لإضافة عميل جديد.');
                } else {
                    alert('الرجاء اختيار عميل أو إضافة عميل جديد.');
                }
                return;
            }
        }
        if (step === 2 && currentStep === 1) {
            const date = document.getElementById('received_at').value;
            if (!date) {
                alert('الرجاء اختيار تاريخ الاستلام.');
                return;
            }
            if (cart.length === 0) {
                alert('الرجاء إضافة قطعة واحدة على الأقل.');
                return;
            }
            generateReview();
        }
        currentStep = step;
        updateUI();
    }

    function updateUI() {
        steps.forEach((el, i) => el.classList.toggle('hidden', i !== currentStep));
        indicators.forEach((el, i) => {
            el.classList.remove('bg-gray-300', 'bg-blue-600', 'bg-green-500');
            if (i < currentStep) el.classList.add('bg-green-500');
            else if (i === currentStep) el.classList.add('bg-blue-600');
            else el.classList.add('bg-gray-300');
        });
        progressBar.style.width = ((currentStep / (totalSteps - 1)) * 100) + '%';
        prevBtn.classList.toggle('hidden', currentStep === 0);
        nextBtn.classList.toggle('hidden', currentStep === totalSteps - 1);
        submitBtn.classList.toggle('hidden', currentStep !== totalSteps - 1);
    }

    function generateReview() {
        const clientName = selectedDisplay.classList.contains('hidden') ?
            (document.getElementById('client_name').value || 'عميل جديد') :
            document.getElementById('selectedClientName').textContent;
        document.getElementById('reviewClient').textContent = clientName;

        const date = document.getElementById('received_at').value;
        document.getElementById('reviewDate').textContent = date ? new Date(date).toLocaleDateString('ar-EG') : '—';

        const itemsContainer = document.getElementById('reviewItems');
        itemsContainer.innerHTML = '';
        let total = 0;
        cart.forEach(item => {
            const sub = item.quantity * item.unit_price;
            total += sub;
            const p = document.createElement('div');
            p.innerHTML = `${item.type} ${item.color ? ' - '+item.color : ''} (${item.service.join(' + ')}) ${item.quantity} × ${item.unit_price} م.د = <span class="font-bold">${sub.toFixed(2)} م.د</span>`;
            itemsContainer.appendChild(p);
        });
        document.getElementById('reviewTotal').textContent = 'م.د ' + total.toFixed(2);
    }

    nextBtn.addEventListener('click', () => goToStep(currentStep + 1));
    prevBtn.addEventListener('click', () => goToStep(currentStep - 1));

    // ----- إرسال النموذج عبر الطريقة التقليدية (بدون fetch/AJAX) -----
    // قفل ضد الإرسال المزدوج: يمنع أي محاولة إرسال جديدة أثناء وجود
    // إرسال جارٍ بالفعل (نقرة مزدوجة، زر Enter، حدث 'submit' مزدوج، إلخ).
    // هذا مكمل لتعطيل الزر ولقفل الخادم في OrderController::store.
    let isSubmitting = false;

    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {

            // إذا كان الإرسال جارياً بالفعل: نوقف أي محاولة جديدة
            if (isSubmitting) {
                e.preventDefault();
                return;
            }

            // التحقق النهائي: عميل (موجود أو جديد، وليس كلاهما أو لا أحد)
            const clientId = clientIdInput.value.trim();
            const clientName = clientNameInput.value.trim();
            const clientPhone = clientPhoneInput.value.trim();
            const hasExisting = clientId.length > 0;
            const hasNew = clientName.length > 0 && clientPhone.length > 0;

            if (!hasExisting && !hasNew) {
                e.preventDefault();
                alert('الرجاء اختيار عميل أو إضافة عميل جديد بالاسم والهاتف معاً.');
                goToStep(0);
                return;
            }

            // لا ترسل الوضعين معاً
            if (hasExisting) {
                clientNameInput.value = '';
                clientPhoneInput.value = '';
            }

            // يجب أن تحتوي العربة على قطعة واحدة على الأقل
            if (cart.length === 0) {
                e.preventDefault();
                alert('الرجاء إضافة قطعة واحدة على الأقل.');
                return;
            }

            // Synchroniser le panier dans le champ caché avant l'envoi
            document.getElementById('itemsJson').value = JSON.stringify(cart);

            // أصبح كل شيء صالحاً: نسمح بإرسال النموذج بالطريقة العادية
            // (لا يوجد e.preventDefault() هنا -> POST عادي + إعادة توجيه Laravel)
            isSubmitting = true;
            submitBtn.disabled = true;
            submitBtn.textContent = '... جاري الإنشاء';

            // Sécurité : si jamais le navigateur reste bloqué (rare), on redébloque
            // le bouton après un délai raisonnable pour ne pas piéger l'utilisateur.
            setTimeout(() => {
                if (isSubmitting) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = '✓ إنشاء الطلب';
                    isSubmitting = false;
                }
            }, 15000);
        });
    } else {
        console.error('Form element not found!');
    }

    // Initialisation
    updateUI();

    const oldItemsRaw = @json(old('items', []));
    let oldItems = [];
    if (Array.isArray(oldItemsRaw)) {
        oldItems = oldItemsRaw;
    } else if (typeof oldItemsRaw === 'string' && oldItemsRaw.trim().length > 0) {
        try {
            oldItems = JSON.parse(oldItemsRaw);
        } catch (error) {
            console.warn('تعذر تحليل عناصر الطلب السابقة:', error, oldItemsRaw);
            oldItems = [];
        }
    }

    const oldSelectedClient = @json($selectedClientData ?? null);
    const oldClientName = @json(old('client_name', ''));
    const oldClientPhone = @json(old('client_phone', ''));

    if (oldSelectedClient) {
        selectClient(oldSelectedClient);
    } else if (oldClientName && oldClientPhone) {
        clientNameInput.value = oldClientName;
        clientPhoneInput.value = oldClientPhone;
        setClientMode('new');
    }

    if (oldItems.length > 0) {
        cart = oldItems;
        updateCartUI();
        currentStep = 1;
        if (document.getElementById('received_at').value) {
            currentStep = 2;
            generateReview();
        }
    }
});
</script>

@endsection