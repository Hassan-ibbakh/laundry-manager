<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nadif - سيّر مصبنتك بنظام واحد</title>
    <meta name="description" content="Nadif كيساعدك تنظم الطلبات، تتبع الخدمة، وتبقى قريب من زبنائك — من أي جهاز وعبر الإنترنت.">
    <meta property="og:title" content="Nadif - نظام تسيير المصابن">
    <meta property="og:description" content="نظام عملي لتنظيم وتسيير وتتبع العمل اليومي للمصابن ومحلات التنظيف في المغرب.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Cairo', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-white text-gray-900 selection:bg-blue-100 selection:text-blue-900 font-sans" dir="rtl">

    <a
        href="https://wa.me/212633220045"
        target="_blank"
        rel="noopener noreferrer"
        class="fixed bottom-6 right-6 z-50 bg-green-500 text-white p-4 rounded-full shadow-2xl hover:bg-green-600 transition-all hover:scale-110 active:scale-95 flex items-center gap-3"
    >
        <span class="font-bold hidden md:block">تواصل معنا</span>
        <i data-lucide="phone" class="w-6 h-6"></i>
    </a>

    <section class="pt-24 pb-20 px-6 bg-gradient-to-b from-blue-50 to-white overflow-hidden">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-12">
            <div class="flex-1 text-right">
                <h1 class="text-4xl lg:text-7xl font-black text-gray-900 leading-tight mb-6">
                    سيّر مصبنة ديالك <br />
                    <span class="text-blue-600">بـ 0 أخطاء.</span>
                </h1>
                <p class="text-lg lg:text-xl text-gray-600 mb-10 leading-relaxed max-w-2xl">
                    Nadif هو نظام رقمي ذكي لي كايتكلف بالتنظيم ديال الخدمة ديالك، وتقدر تتبع كلشي عن بعد عبر الإنترنت وفي أي وقت — من تليفون أو حاسوب.
                </p>
                <div class="flex flex-wrap gap-4 justify-start">
                    <a
                        href="https://wa.me/212633220045?text=السلام%20عليكم،%20أريد%20طلب%20عرض%20نظام%20Nadif"
                        target="_blank"
                        class="px-8 py-3.5 rounded-xl font-bold bg-blue-600 text-white hover:bg-blue-700 shadow-lg shadow-blue-200 text-lg transition-all duration-300 transform hover:-translate-y-0.5"
                    >
                        أطلب عرض النظام
                    </a>
                    <a
                        href="{{ route('laundry.login') }}"
                        class="px-8 py-3.5 rounded-xl font-bold border border-blue-200 bg-white text-blue-700 hover:bg-blue-50 shadow-sm text-lg transition-all duration-300 transform hover:-translate-y-0.5"
                    >
                        الدخول إلى لوحة التحكم
                    </a>
                </div>
            </div>

            <div class="flex-1 relative w-full max-w-xl lg:max-w-none">
                <div class="relative z-10 rounded-3xl overflow-hidden shadow-2xl border-8 border-white ring-1 ring-gray-100">
                    <img
                        src="{{ asset('images/nadif_hero_tablet_ui.png') }}"
                        alt="Nadif Dashboard UI"
                        class="w-full h-auto object-cover"
                        loading="eager"
                        fetchpriority="high"
                        decoding="async"
                        onerror="this.src='https://placehold.co/800x500/2563eb/ffffff?text=Nadif+Dashboard'"
                    />
                </div>
                <div class="mt-8 text-center lg:text-right">
                    <span class="bg-blue-600 text-white px-5 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-blue-200 inline-block">
                        مشروع مصبنة مربح فقط إلى عرفتي كفاش تسيير
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                    المشكل ماشي فالخدمة ديالك… <br class="md:hidden" />
                    <span class="text-red-600">المشكل فالتنظيم.</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $problems = [
                        ['p' => 'حوايج ديال الناس كايتلفو', 'c' => 'طلبات بزاف وصعيب تعقل على كلشي', 's' => 'تتبع رقمي'],
                        ['p' => 'صعيب تعرف الحوايج ديال كل كليان', 'c' => 'الورقة كاضيع، معلومات غير كافية', 's' => 'نظام رقمي'],
                        ['p' => 'الكليان كايجي يسول واش حوايجو واجدين', 'c' => 'التواصل مع الزبون منعدم', 's' => 'التواصل الدائم مع الزبون'],
                        ['p' => 'صعوبة حساب المصارف والمداخل والأرباح الصافية', 'c' => 'ماتقدرش تعرف شنو باقي فالدمة وشنو لي خالص', 's' => 'تقرير يومي دقيق'],
                    ];
                @endphp

                @foreach ($problems as $item)
                <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 flex flex-col gap-4 text-right">
                    <div class="flex items-center justify-end gap-3 text-red-600 font-bold text-lg">
                        <span>{{ $item['p'] }}</span>
                        <i data-lucide="x-circle" class="w-6 h-6 shrink-0"></i>
                    </div>

                    <div class="space-y-3 pt-2">
                        <div>
                            <span class="text-xs font-black text-gray-400 uppercase tracking-wider block mb-1">السبب</span>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $item['c'] }}</p>
                        </div>
                        <div class="pt-2 border-t border-gray-200">
                            <span class="text-xs font-black text-blue-500 uppercase tracking-wider block mb-1">الحل</span>
                            <p class="text-blue-700 font-bold text-sm">{{ $item['s'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-20 px-6 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-black text-blue-600">
                    الحل هو Nadif
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-red-50 text-right">
                    <h3 class="text-2xl font-bold text-red-600 mb-8 flex items-center justify-end gap-2">
                        قبل Nadif <i data-lucide="x-circle" class="w-6 h-6"></i>
                    </h3>
                    <ul class="space-y-5">
                        <li class="flex items-center justify-end gap-3 text-gray-600">
                            <span>الوراق، دفتار، الصبون، المصلوح، الكليان...</span>
                            <span class="text-red-500">❌</span>
                        </li>
                        <li class="flex items-center justify-end gap-3 text-gray-600">
                            <span>الحوايج تلفو الكليان كايجي غير يسول</span>
                            <span class="text-red-500">❌</span>
                        </li>
                        <li class="flex items-center justify-end gap-3 text-gray-600">
                            <span>كلشي خصك تعقل عليه نتا</span>
                            <span class="text-red-500">❌</span>
                        </li>
                        <li class="flex items-center justify-end gap-3 text-gray-600">
                            <span>مايقدر حتى واحد يخدم فبلاصتك</span>
                            <span class="text-red-500">❌</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-blue-600 p-8 rounded-3xl shadow-xl text-white text-right relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                        <i data-lucide="check-circle-2" class="w-40 h-40"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-8 flex items-center justify-end gap-2">
                        مع Nadif <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                    </h3>
                    <ul class="space-y-5 relative z-10 font-medium">
                        <li class="flex items-center justify-end gap-3">
                            <span>كلشي مسجل الكليان حوايجهوم وفين كاينين</span>
                            <span class="text-blue-200">✓</span>
                        </li>
                        <li class="flex items-center justify-end gap-3">
                            <span>الكليان كايتبع الطلب من تيليفونو</span>
                            <span class="text-blue-200">✓</span>
                        </li>
                        <li class="flex items-center justify-end gap-3">
                            <span>كلشي كاين فالنظام ماتعقل على والو</span>
                            <span class="text-blue-200">✓</span>
                        </li>
                        <li class="flex items-center justify-end gap-3">
                            <span>يقدر أي واحد يخدم معاك</span>
                            <span class="text-blue-200">✓</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 bg-white" id="features">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">شنو هو Nadif؟</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Nadif هو نظام رقمي مخصص للمصابن، كيساعدك تنظم وتتابع الخدمة اليومية ديالك من استقبال الطلب حتى تسليمه للزبون.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $features = [
                        ['title' => 'تسجيل الطلبات سريع', 'desc' => 'واجهة سهلة، وسريعة، سجل (جلابة، سروال، جاكيط...) بالألوان والثمن فثواني.', 'icon' => 'clipboard-list'],
                        ['title' => 'تتبع الحالة (لوحة التحكم)', 'desc' => 'عرف كل طلب واش (تم الاستلام، قيد التنفيذ، أو جاهز).', 'icon' => 'search'],
                        ['title' => 'التقرير المالي اليومي', 'desc' => 'عرف شحال دخلتي، شحال دخلتي كاش، وشحال باقي كيتسال (فالذمة).', 'icon' => 'wallet'],
                        ['title' => 'إدارة الزبناء', 'desc' => 'نظم معلومات الزبناء مع رابط التتبع باش يبقاو ديما راضيين.', 'icon' => 'users'],
                        ['title' => 'تواصل إحترافي', 'desc' => 'علم الزبناء ملي يوجدو حوايجهم باش ياخدوهم.', 'icon' => 'message-square'],
                    ];
                @endphp

                @foreach ($features as $f)
                <div class="p-8 rounded-3xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow text-right">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6 mr-0 ml-auto">
                        <i data-lucide="{{ $f['icon'] }}" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">{{ $f['title'] }}</h4>
                    <p class="text-gray-600 leading-relaxed">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-20 px-6 bg-gray-50 border-y border-gray-100" id="how-it-works">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">كيفاش كيخدم Nadif؟</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">طريقة بسيطة وعملية باش تسير خدمتك اليومية.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                @php
                    $steps = [
                        ['step' => '01', 'title' => 'سجل الطلب', 'desc' => 'غير كايجي الكليان، سجل الطلب ديالو فثواني.', 'icon' => 'clipboard-list'],
                        ['step' => '02', 'title' => 'تتبع الخدمة', 'desc' => 'تفعيل حالة الطلب (تم الإستلام، في الغسيل، جاهز...)', 'icon' => 'play-circle'],
                        ['step' => '03', 'title' => 'تسليم الطلب', 'desc' => 'غير توجد الخدمة، علم الكليان عبر رسالة تلقائية من النظام.', 'icon' => 'check-circle-2'],
                    ];
                @endphp

                @foreach ($steps as $s)
                <div class="flex flex-col items-center text-center p-8 rounded-3xl bg-white border border-gray-100 shadow-sm">
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-200">
                        <i data-lucide="{{ $s['icon'] }}" class="w-8 h-8"></i>
                    </div>
                    <span class="text-blue-600 font-black text-2xl mb-2">{{ $s['step'] }}</span>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">{{ $s['title'] }}</h4>
                    <p class="text-gray-600 leading-relaxed">{{ $s['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-20 px-6 bg-gray-900 text-white" id="offers">
        <div class="max-w-7xl mx-auto flex flex-col gap-16">
            <div class="text-right">
                <h2 class="text-3xl lg:text-4xl font-bold mb-6">ما محتاجش تبدل الأجهزة ديالك.</h2>
                <p class="text-xl text-gray-400 mb-10 leading-relaxed max-w-2xl ml-auto">
                    Nadif خدام عبر الإنترنت، وتقدر تستعملو من الجهاز المناسب ليك.
                </p>

                <div class="grid grid-cols-3 gap-6 mb-12 max-w-2xl ml-auto">
                    <div class="flex flex-col items-center gap-3 p-4 rounded-2xl bg-gray-800 border border-gray-700">
                        <i data-lucide="tablet" class="w-8 h-8 text-blue-400"></i>
                        <span class="font-bold">تابليت</span>
                    </div>
                    <div class="flex flex-col items-center gap-3 p-4 rounded-2xl bg-gray-800 border border-gray-700">
                        <i data-lucide="laptop" class="w-8 h-8 text-blue-400"></i>
                        <span class="font-bold">لابتوب</span>
                    </div>
                    <div class="flex flex-col items-center gap-3 p-4 rounded-2xl bg-gray-800 border border-gray-700">
                        <i data-lucide="monitor" class="w-8 h-8 text-blue-400"></i>
                        <span class="font-bold">بيسي</span>
                    </div>
                </div>

                <div class="p-6 rounded-2xl bg-blue-600/10 border border-blue-500/20 text-right max-w-2xl ml-auto">
                    <p class="text-lg font-bold text-blue-400 mb-2">عندك جهاز مناسب؟ بدا به مباشرة.</p>
                    <p class="text-gray-300">ماعرفتيش أشمن جهاز مناسب لك؟ فريق Nadif يقدرو يوصيوك بالأجهزة المناسبة حسب طريقة خدمتك والميزانية ديالك.</p>
                </div>
            </div>

            <div class="flex justify-center">
                <div class="w-full max-w-md p-8 rounded-3xl bg-white text-gray-900 border border-gray-100 shadow-2xl text-right flex flex-col">
                    <div class="mb-8">
                        <h3 class="text-4xl font-black text-gray-900">Nadif</h3>
                    </div>

                    <ul class="space-y-4 mb-10 flex-grow">
                        <li class="flex items-center justify-end gap-3 text-gray-600">
                            <span>نظام رقمي كامل</span>
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-green-500"></i>
                        </li>
                        <li class="flex items-center justify-end gap-3 text-gray-600">
                            <span>الإستعمال عبر الإنترنت</span>
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-green-500"></i>
                        </li>
                        <li class="flex items-center justify-end gap-3 text-gray-600">
                            <span>التحديثات الدورية</span>
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-green-500"></i>
                        </li>
                        <li class="flex items-center justify-end gap-3 text-gray-600">
                            <span>المواكبة حسب الباقة</span>
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-green-500"></i>
                        </li>
                    </ul>

                    <div class="mb-8 text-left border-t border-gray-100 pt-6">
                        <div class="text-4xl font-black text-gray-900">500 <span class="text-lg font-normal">درهم</span></div>
                        <p class="text-gray-500 text-sm mt-2 font-medium">
                            + إشتراك شهري أو سنوي <br />
                            <span class="text-xs opacity-75">(50 درهم / 500 درهم)</span>
                        </p>
                    </div>

                    <a
                        href="https://wa.me/212633220045?text=أريد%20الاشتراك%20في%20Nadif%20فقط"
                        target="_blank"
                        class="w-full py-4 text-center rounded-xl font-bold bg-blue-600 text-white hover:bg-blue-700 shadow-lg text-lg block transition"
                    >
                        أريد Nadif فقط
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 bg-blue-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">ما عندكش الأجهزة؟ ماشي مشكل.</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    عطينا الميزانية ديالك وطريقة الخدمة، ونوصيوك بالتجهيز المناسب بدون ما تشري حاجة ما محتاجهاش.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="p-8 rounded-2xl bg-white border border-blue-100 text-right">
                    <h4 class="text-xl font-bold text-gray-900 mb-3">ميزانية اقتصادية</h4>
                    <p class="text-gray-600 leading-relaxed">جهاز مناسب للبداية بأقل تكلفة ممكنة.</p>
                </div>
                <div class="p-8 rounded-2xl bg-white border border-blue-100 text-right">
                    <h4 class="text-xl font-bold text-gray-900 mb-3">ميزانية متوسطة</h4>
                    <p class="text-gray-600 leading-relaxed">تجهيز عملي وقوي للاستعمال اليومي المكثف.</p>
                </div>
                <div class="p-8 rounded-2xl bg-white border border-blue-100 text-right">
                    <h4 class="text-xl font-bold text-gray-900 mb-3">تجهيز كامل</h4>
                    <p class="text-gray-600 leading-relaxed">Tablet + Printer + التجهيز المناسب للمصبنة ديالك.</p>
                </div>
            </div>

            <div class="max-w-md mx-auto">
                <div class="p-8 rounded-3xl bg-blue-600 text-white shadow-xl shadow-blue-200 text-right flex flex-col relative overflow-hidden">
                    <div class="mb-8">
                        <h3 class="text-4xl font-black">Nadif+</h3>
                    </div>

                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center justify-end gap-3 font-medium">
                            <span>Nadif</span>
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-blue-200"></i>
                        </li>
                        <li class="flex items-center justify-end gap-3 font-medium">
                            <span>إعداد وتجهيز</span>
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-blue-200"></i>
                        </li>
                        <li class="flex items-center justify-end gap-3 font-medium">
                            <span>مواكبة مباشرة</span>
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-blue-200"></i>
                        </li>
                        <li class="flex items-center justify-end gap-3 font-medium">
                            <span>تعليم الموظفين</span>
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-blue-200"></i>
                        </li>
                    </ul>

                    <div class="mb-8 text-left border-t border-blue-500/30 pt-6">
                        <span class="text-blue-100 text-sm">إبتداءً من</span>
                        <div class="text-4xl font-black">5000 <span class="text-lg font-normal">درهم</span></div>
                        <p class="text-blue-100 text-sm mt-2 font-medium">
                            + إشتراك شهري أو سنوي <br />
                            <span class="text-xs opacity-75">(50 درهم / 500 درهم)</span>
                        </p>
                    </div>

                    <a
                        href="https://wa.me/212633220045?text=أريد%20الاشتراك%20في%20Nadif%20مع%20التجهيز"
                        target="_blank"
                        class="w-full py-4 text-center rounded-xl font-bold bg-white text-blue-600 hover:bg-blue-50 shadow-md text-lg block transition"
                    >
                        أريد Nadif + التجهيز
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 bg-gray-50" id="faq" x-data="{ openTab: 0 }">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 flex items-center justify-center gap-3">
                    أسئلة متكررة <i data-lucide="help-circle" class="w-8 h-8 text-blue-600"></i>
                </h2>
            </div>

            <div class="max-w-3xl mx-auto space-y-4">
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <button
                        @click="openTab = (openTab === 0 ? null : 0)"
                        class="w-full p-6 text-right flex items-center justify-between gap-4 font-bold text-gray-900 hover:bg-gray-50 transition-colors"
                    >
                        <i data-lucide="chevron-down" class="w-5 h-5 text-blue-600 transition-transform duration-200" :class="{ 'rotate-180': openTab === 0 }"></i>
                        <span>واش ضروري يكون عندي إنترنت قوي؟</span>
                    </button>
                    <div x-show="openTab === 0" x-collapse class="px-6 pb-6 text-right text-gray-600 leading-relaxed border-t border-gray-50 pt-4">
                        Nadif مصمم باش يخدم بأقل صبيب إنترنت (حتى 3G أو 4G كافية).
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <button
                        @click="openTab = (openTab === 1 ? null : 1)"
                        class="w-full p-6 text-right flex items-center justify-between gap-4 font-bold text-gray-900 hover:bg-gray-50 transition-colors"
                    >
                        <i data-lucide="chevron-down" class="w-5 h-5 text-blue-600 transition-transform duration-200" :class="{ 'rotate-180': openTab === 1 }"></i>
                        <span>واش نقدر نخدم به من التليفون؟</span>
                    </button>
                    <div x-show="openTab === 1" x-collapse class="px-6 pb-6 text-right text-gray-600 leading-relaxed border-t border-gray-50 pt-4">
                        ماتستعملوش فالكونتوار، Nadif كيخدم من أي جهاز فيه متصفح (تليفون، تابلت، حاسوب).
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <button
                        @click="openTab = (openTab === 2 ? null : 2)"
                        class="w-full p-6 text-right flex items-center justify-between gap-4 font-bold text-gray-900 hover:bg-gray-50 transition-colors"
                    >
                        <i data-lucide="chevron-down" class="w-5 h-5 text-blue-600 transition-transform duration-200" :class="{ 'rotate-180': openTab === 2 }"></i>
                        <span>إلا ضاع ليا الجهاز، واش المعلومات كيمشيو؟</span>
                    </button>
                    <div x-show="openTab === 2" x-collapse class="px-6 pb-6 text-right text-gray-600 leading-relaxed border-t border-gray-50 pt-4">
                        جميع المعلومات ديالك مسجلة بأمان فالسحاب (Cloud)، غير كتدخل من جهاز آخر كتلقى كلشي كيف خليتيه.
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <button
                        @click="openTab = (openTab === 3 ? null : 3)"
                        class="w-full p-6 text-right flex items-center justify-between gap-4 font-bold text-gray-900 hover:bg-gray-50 transition-colors"
                    >
                        <i data-lucide="chevron-down" class="w-5 h-5 text-blue-600 transition-transform duration-200" :class="{ 'rotate-180': openTab === 3 }"></i>
                        <span>واش كاين دعم فني إلا وقع ليا مشكل؟</span>
                    </button>
                    <div x-show="openTab === 3" x-collapse class="px-6 pb-6 text-right text-gray-600 leading-relaxed border-t border-gray-50 pt-4">
                        فريق Nadif متاح دائماً عبر الواتساب وبالمكالمات باش يواكبك ويحل أي مشكل تقني.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-20 px-6 bg-gray-900 text-white border-t border-gray-800" id="contact">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 text-right">
            <div class="md:col-span-3">
                <div class="text-3xl font-black text-blue-400 mb-6 tracking-tighter">Nadif</div>
                <p class="text-gray-400 max-w-sm ml-auto leading-relaxed">
                    مطوَّر تحت إشراف حرفيين قبل التقنيين
                </p>
            </div>

            <div>
                <h5 class="font-bold text-lg mb-6">تواصل معنا</h5>
                <ul class="space-y-4 text-gray-400">
                    <li class="flex items-center justify-end gap-3 font-bold text-white">
                        <span dir="ltr">+212 633 220 045</span>
                        <i data-lucide="phone" class="w-5 h-5 text-blue-400"></i>
                    </li>
                    <li class="flex items-center justify-end gap-3">
                        <span>Ibrahimidallal@gmail.com</span>
                        <i data-lucide="message-square" class="w-5 h-5 text-blue-400"></i>
                    </li>
                    <li class="pt-4 flex justify-end gap-4">
                        <a
                            href="https://www.facebook.com/e.nadif"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 transition-colors text-white"
                        >
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                        </a>
                        <a
                            href="https://www.instagram.com/nadif_express?stkn=MXg0aHI4ZWxkbDZz"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-600 transition-colors text-white"
                        >
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                        </a>
                        <a
                            href="https://wa.me/212633220045"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-green-600 transition-colors text-white"
                        >
                            <i data-lucide="message-square" class="w-5 h-5"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-20 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
            <p>© 2026 Nadifexpress — جميع الحقوق محفوظة.</p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
