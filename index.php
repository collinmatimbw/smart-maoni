<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Feedback & Reward System - Real Database</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; background-color: #f1f5f9; overflow-x: hidden; }
        .glass-panel { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
        .admin-bg { background-color: #0f172a; color: #f8fafc; }
        .star-rating i { cursor: pointer; transition: color 0.2s, transform 0.2s; }
        .star-rating i:hover, .star-rating i.active { color: #fbbf24; transform: scale(1.1); }
        
        .view-section { transition: opacity 0.4s ease-in-out; }
        .hidden-view { display: none !important; opacity: 0; }
        .active-view { display: flex !important; opacity: 1; animation: fadeIn 0.5s; }
        .active-view-block { display: block !important; opacity: 1; animation: fadeIn 0.5s; }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .spinner { border: 3px solid rgba(255, 255, 255, 0.3); border-top-color: #ffffff; border-radius: 50%; width: 20px; height: 20px; animation: spin 1s linear infinite; display: inline-block; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; border-radius: 4px; }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }
        
        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 10000;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        #view-admin-dashboard {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
            background-color: #0f172a;
            overflow-y: auto;
        }
    </style>
</head>
<body class="antialiased text-slate-800 h-screen flex flex-col">

    <!-- Loading Indicator -->
    <div id="loadingOverlay" class="loading-overlay hidden">
        <div class="text-center">
            <div class="spinner w-12 h-12 mb-4"></div>
            <p class="text-lg font-bold">Inapakia data...</p>
        </div>
    </div>

    <!-- Navbar Kuu -->
    <nav id="main-nav" class="bg-white shadow-sm p-4 sticky top-0 z-40 transition-all duration-300">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="font-bold text-xl text-blue-600 flex items-center cursor-pointer" onclick="showView('landing')">
                <i class="fas fa-qrcode mr-2 text-2xl"></i> Smart<span class="text-slate-800">Feedback</span>
            </div>
            <div class="flex gap-2 sm:gap-3">
                <button onclick="showToast('Lugha imebadilishwa (Demo Mode)', 'info')" class="px-3 py-1 border border-slate-300 rounded text-sm font-bold text-slate-600 hover:bg-slate-50 transition">
                    <i class="fas fa-language mr-1"></i> SW/EN
                </button>
                <button id="nav-login-btn" onclick="showView('admin-login')" class="px-4 py-2 bg-slate-800 text-white rounded-lg font-semibold shadow hover:bg-slate-700 text-sm transition">
                    <i class="fas fa-lock sm:mr-2"></i> <span class="hidden sm:inline">Admin</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- VIEW 1: LANDING PAGE -->
    <div id="view-landing" class="view-section active-view flex-grow items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-4">
        <div class="max-w-4xl w-full grid md:grid-cols-2 gap-8 items-center bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="p-8 md:p-12 text-center md:text-left">
                <div class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold mb-4">
                    <i class="fas fa-database mr-1"></i> Real Database System
                </div>
                <h1 class="text-4xl md:text-5xl font-black mb-4 leading-tight text-slate-800">
                    Sauti yako,<br><span class="text-blue-600">Zawadi yako.</span>
                </h1>
                <p class="text-slate-500 mb-8">Scan QR Code kutoa maoni yako kuhusu huduma zetu na upate papo hapo zawadi/punguzo la bei (Promo Code).</p>
                
                <div class="flex flex-col gap-4">
                    <button onclick="showView('user-flow')" class="w-full bg-blue-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:bg-blue-700 hover:-translate-y-1 transition flex justify-between items-center">
                        <span><i class="fas fa-user-edit mr-2"></i> Toa Maoni (User Demo)</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <button onclick="showView('admin-login')" class="w-full bg-slate-100 text-slate-700 border border-slate-200 font-bold py-3 px-6 rounded-xl hover:bg-slate-200 transition flex justify-between items-center">
                        <span><i class="fas fa-shield-alt mr-2"></i> Ingia kama Admin</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
            <div class="bg-blue-600 p-12 flex items-center justify-center relative overflow-hidden h-full hidden md:flex">
                <div class="absolute inset-0 opacity-20 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmYiLz48L3N2Zz4=')]"></div>
                <div class="bg-white p-6 rounded-2xl shadow-2xl relative z-10 transform rotate-3 hover:rotate-0 transition duration-300">
                    <i class="fas fa-qrcode text-9xl text-slate-800"></i>
                    <div class="mt-4 text-center text-sm font-bold text-slate-500">SCAN ME</div>
                </div>
            </div>
        </div>
    </div>

    <!-- VIEW 2: USER FLOW -->
    <div id="view-user-flow" class="view-section hidden-view flex-grow items-center justify-center p-4 bg-slate-50">
        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200 relative">
            <div class="bg-blue-600 p-6 text-white text-center">
                <div class="inline-block bg-white/20 px-3 py-1 rounded-full text-xs font-bold mb-2">
                    <i class="fas fa-map-marker-alt mr-1"></i> <span id="selected-branch">Tawi la Posta, Dar</span>
                </div>
                <h2 class="text-2xl font-bold">Maoni Yako</h2>
                <div class="flex justify-between items-center mt-4 text-xs font-bold text-blue-200">
                    <span id="step1-indicator" class="text-white">1. Nyota</span>
                    <span id="step2-indicator">2. Ujumbe</span>
                    <span id="step3-indicator">3. Zawadi</span>
                </div>
                <div class="w-full bg-blue-800 h-1 mt-2 rounded-full overflow-hidden">
                    <div id="user-progress" class="bg-yellow-400 h-full w-1/3 transition-all duration-500"></div>
                </div>
            </div>

            <div id="user-step-1" class="p-6 active-view-block">
                <label class="block text-sm font-bold text-slate-700 mb-2 text-center">Unaipaje huduma yetu leo?</label>
                <div class="star-rating text-5xl text-slate-200 flex justify-center gap-2 mb-6" id="user-stars">
                    <i class="far fa-star" data-val="1"></i><i class="far fa-star" data-val="2"></i>
                    <i class="far fa-star" data-val="3"></i><i class="far fa-star" data-val="4"></i>
                    <i class="far fa-star" data-val="5"></i>
                </div>
                
                <div id="rating-error" class="hidden text-red-500 text-xs text-center font-bold mb-4">Tafadhali chagua nyota angalau moja.</div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Jina lako (Sio Lazima)</label>
                        <input type="text" id="user-name" placeholder="Mfano: John Doe" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Email yako (Sio Lazima)</label>
                        <input type="email" id="user-email" placeholder="Mfano: john@email.com" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Chagua Tawi</label>
                        <select id="user-branch" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value="1">Posta (HQ), Dar</option>
                            <option value="2">Dodoma Branch</option>
                            <option value="3">Arusha Branch</option>
                            <option value="4">Mwanza Branch</option>
                        </select>
                    </div>
                </div>

                <button onclick="nextUserStep(2)" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl mt-6 hover:bg-blue-700 transition">
                    Endelea <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>

            <div id="user-step-2" class="p-6 hidden-view">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Kundi la Maoni</label>
                        <select id="user-category" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option>Huduma kwa Wateja</option>
                            <option>Ubora wa Bidhaa</option>
                            <option>Mazingira/Usafi</option>
                            <option>Mengineyo</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Ujumbe Wako</label>
                        <textarea id="user-comment" rows="3" placeholder="Tuambie nini kimekusibu au kukufurahisha..." class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button onclick="nextUserStep(1)" class="w-1/3 bg-slate-200 text-slate-700 font-bold py-3 rounded-xl hover:bg-slate-300 transition">
                        Rudi
                    </button>
                    <button onclick="submitUserFeedback()" id="btn-submit" class="w-2/3 bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition flex justify-center items-center gap-2">
                        <span>Tuma Maoni</span>
                    </button>
                </div>
            </div>

            <div id="user-step-3" class="p-6 hidden-view text-center flex-col items-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-4xl text-green-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">Ahsante Sana!</h3>
                <p class="text-slate-500 text-sm mb-6">Maoni yako yamepokelewa kwenye Database. Kama shukrani, pokea zawadi hii.</p>
                
                <div class="bg-gradient-to-r from-yellow-400 to-orange-500 p-1 rounded-2xl mb-6 w-full">
                    <div class="bg-white p-4 rounded-xl border border-dashed border-orange-200">
                        <p class="text-xs text-slate-500 mb-1 font-bold">PROMO CODE YAKO</p>
                        <h4 id="display-promo" class="text-4xl font-black text-slate-800 tracking-widest">---</h4>
                        <p class="text-orange-600 font-bold text-sm mt-2"><i class="fas fa-gift mr-1"></i> Punguzo la 15% Leo!</p>
                    </div>
                </div>
                
                <button onclick="showView('landing')" class="text-slate-500 font-bold text-sm hover:text-blue-600 underline">
                    Rudi Mwanzo
                </button>
            </div>
        </div>
    </div>

    <!-- VIEW 3: ADMIN LOGIN -->
    <div id="view-admin-login" class="view-section hidden-view flex-grow items-center justify-center p-4 bg-slate-900">
        <div class="w-full max-w-sm bg-slate-800 rounded-3xl shadow-2xl p-8 border border-slate-700 relative">
            <button onclick="showView('landing')" class="absolute top-4 right-4 text-slate-400 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto text-blue-400 text-2xl mb-4">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h2 class="text-2xl font-bold text-white">Admin Login</h2>
                <p class="text-slate-400 text-sm">Ingiza username na password yako</p>
            </div>

            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Username</label>
                    <input type="text" id="login-username" placeholder="admin" 
                           class="w-full bg-slate-900 border border-slate-700 text-white p-3 rounded-xl focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Password</label>
                    <input type="password" id="login-password" placeholder="********" 
                           class="w-full bg-slate-900 border border-slate-700 text-white p-3 rounded-xl focus:border-blue-500 outline-none">
                </div>
            </div>
            <div id="login-error" class="text-red-400 text-sm text-center mb-4 hidden"></div>
            <button onclick="simulateLogin(event)" id="btn-login" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition flex justify-center items-center gap-2">
                <span>Ingia</span>
            </button>
        </div>
    </div>

    <!-- VIEW 4: ADMIN DASHBOARD -->
    <div id="view-admin-dashboard" class="view-section hidden-view admin-bg">
        <div class="flex h-full">
            <aside class="w-64 bg-slate-900 border-r border-slate-800 flex-col h-full hidden md:flex shrink-0 sticky top-0">
                <div class="p-6 border-b border-slate-800 flex justify-between items-center">
                    <div class="font-bold text-xl text-blue-500"><i class="fas fa-database mr-2"></i>SmartSRS</div>
                </div>
                <div class="p-4 flex-grow custom-scrollbar overflow-y-auto space-y-2">
                    <div class="text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider pl-3">Menu Kuu</div>
                    <button onclick="switchTab('tab-dashboard')" id="nav-tab-dashboard" class="admin-nav-btn w-full flex items-center p-3 rounded-lg bg-slate-800 text-white transition">
                        <i class="fas fa-tachometer-alt w-6"></i> Dashboard
                    </button>
                    <button onclick="switchTab('tab-maoni')" id="nav-tab-maoni" class="admin-nav-btn w-full flex items-center p-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-comments w-6"></i> Maoni <span id="new-feedback-badge" class="ml-auto bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">0</span>
                    </button>
                    <button onclick="switchTab('tab-qr')" id="nav-tab-qr" class="admin-nav-btn w-full flex items-center p-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-qrcode w-6"></i> QR Codes
                    </button>
                    <button onclick="switchTab('tab-analytics')" id="nav-tab-analytics" class="admin-nav-btn w-full flex items-center p-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-chart-line w-6"></i> Analytics
                    </button>
                    <button onclick="switchTab('tab-matawi')" id="nav-tab-matawi" class="admin-nav-btn w-full flex items-center p-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-building w-6"></i> Matawi
                    </button>
                    <button onclick="switchTab('tab-zawadi')" id="nav-tab-zawadi" class="admin-nav-btn w-full flex items-center p-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-gift w-6"></i> Zawadi
                    </button>
                </div>
                <div class="p-4 border-t border-slate-800">
                    <button onclick="logout()" class="w-full bg-slate-800 text-slate-400 font-bold py-2 rounded-lg hover:bg-red-500/20 hover:text-red-400 transition">
                        <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                    </button>
                </div>
            </aside>

            <main class="flex-grow flex flex-col h-full overflow-hidden bg-slate-950 w-full">
                <header class="md:hidden bg-slate-900 border-b border-slate-800 z-10 shrink-0">
                    <div class="p-4 flex justify-between items-center">
                        <div class="font-bold text-blue-500"><i class="fas fa-database mr-2"></i> SmartSRS</div>
                        <button onclick="logout()" class="text-slate-400 p-2"><i class="fas fa-sign-out-alt text-xl"></i></button>
                    </div>
                    <div class="flex overflow-x-auto whitespace-nowrap p-2 gap-2 custom-scrollbar border-t border-slate-800">
                        <button onclick="switchTab('tab-dashboard')" id="mob-tab-dashboard" class="admin-mob-btn px-4 py-1.5 bg-blue-600 text-white rounded-full text-sm font-bold">Dashboard</button>
                        <button onclick="switchTab('tab-maoni')" id="mob-tab-maoni" class="admin-mob-btn px-4 py-1.5 bg-slate-800 text-slate-300 rounded-full text-sm font-bold">Maoni</button>
                        <button onclick="switchTab('tab-qr')" id="mob-tab-qr" class="admin-mob-btn px-4 py-1.5 bg-slate-800 text-slate-300 rounded-full text-sm font-bold">QR Codes</button>
                        <button onclick="switchTab('tab-analytics')" id="mob-tab-analytics" class="admin-mob-btn px-4 py-1.5 bg-slate-800 text-slate-300 rounded-full text-sm font-bold">Analytics</button>
                        <button onclick="switchTab('tab-matawi')" id="mob-tab-matawi" class="admin-mob-btn px-4 py-1.5 bg-slate-800 text-slate-300 rounded-full text-sm font-bold">Matawi</button>
                        <button onclick="switchTab('tab-zawadi')" id="mob-tab-zawadi" class="admin-mob-btn px-4 py-1.5 bg-slate-800 text-slate-300 rounded-full text-sm font-bold">Zawadi</button>
                    </div>
                </header>

                <div class="flex-grow overflow-y-auto p-4 md:p-8 custom-scrollbar">
                    <!-- TAB 1: DASHBOARD -->
                    <div id="tab-dashboard" class="admin-tab active-view-block space-y-6">
                        <div>
                            <h2 class="text-2xl font-bold text-white">Dashboard Overview</h2>
                            <p class="text-slate-400 text-sm">Muhtasari wa wakati halisi kutoka Database</p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-slate-900 p-4 md:p-5 rounded-2xl border border-slate-800 shadow-lg">
                                <div class="text-slate-400 text-xs md:text-sm font-bold mb-1">Total Feedback</div>
                                <div class="text-2xl md:text-3xl font-black text-white" id="db-total">0</div>
                            </div>
                            <div class="bg-slate-900 p-4 md:p-5 rounded-2xl border border-slate-800 shadow-lg">
                                <div class="text-green-400 text-xs md:text-sm font-bold mb-1"><i class="fas fa-smile mr-1"></i> Positive</div>
                                <div class="text-2xl md:text-3xl font-black text-green-400" id="db-pos">0</div>
                            </div>
                            <div class="bg-slate-900 p-4 md:p-5 rounded-2xl border border-slate-800 shadow-lg">
                                <div class="text-yellow-400 text-xs md:text-sm font-bold mb-1"><i class="fas fa-meh mr-1"></i> Neutral</div>
                                <div class="text-2xl md:text-3xl font-black text-yellow-400" id="db-neu">0</div>
                            </div>
                            <div class="bg-slate-900 p-4 md:p-5 rounded-2xl border border-red-500/30 relative overflow-hidden shadow-lg">
                                <div class="text-red-400 text-xs md:text-sm font-bold mb-1 relative z-10"><i class="fas fa-angry mr-1"></i> Negative</div>
                                <div class="text-2xl md:text-3xl font-black text-red-400 relative z-10" id="db-neg">0</div>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="md:col-span-1 bg-slate-900 rounded-2xl border border-slate-800 p-5 shadow-lg">
                                <h3 class="font-bold text-white mb-4"><i class="fas fa-bell text-red-400 mr-2"></i> Intervention Alerts</h3>
                                <div id="dashboard-alerts" class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar pr-2"></div>
                            </div>
                            <div class="md:col-span-2 bg-slate-900 rounded-2xl border border-slate-800 p-5 shadow-lg">
                                <h3 class="font-bold text-white mb-4">AI Sentiment Distribution</h3>
                                <div class="h-64 w-full"><canvas id="dashChart"></canvas></div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: MAONI -->
                    <div id="tab-maoni" class="admin-tab hidden-view flex-col space-y-4">
                        <h2 class="text-2xl font-bold text-white">Orodha ya Maoni kutoka Database</h2>
                        <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg">
                            <div class="p-4 border-b border-slate-800 flex flex-col sm:flex-row gap-3 justify-between items-center">
                                <input type="text" id="search-feedback" placeholder="Tafuta maoni..." class="w-full sm:w-auto bg-slate-950 border border-slate-700 text-sm text-white px-3 py-2 rounded-lg outline-none focus:border-blue-500">
                                <button onclick="filterFeedbacks()" class="w-full sm:w-auto bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700 transition">Filter Data</button>
                            </div>
                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="w-full text-left text-sm whitespace-nowrap min-w-[700px]">
                                    <thead class="bg-slate-950 text-slate-400">
                                        <tr><th class="p-4">Tawi</th><th class="p-4">Ujumbe</th><th class="p-4">Rating</th><th class="p-4">AI Sentiment</th><th class="p-4">Status</th><th class="p-4">Tarehe</th></tr>
                                    </thead>
                                    <tbody id="table-maoni" class="divide-y divide-slate-800 text-slate-300"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: QR CODES -->
                    <div id="tab-qr" class="admin-tab hidden-view flex-col space-y-4">
                        <div class="flex flex-wrap gap-4 justify-between items-center mb-2">
                            <h2 class="text-2xl font-bold text-white">Dynamic QR Management</h2>
                            <button onclick="createNewQR()" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-blue-700 transition"><i class="fas fa-plus mr-1"></i> Tengeneza Mpya</button>
                        </div>
                        <div id="qr-codes-container" class="grid sm:grid-cols-2 md:grid-cols-3 gap-6"></div>
                    </div>

                    <!-- TAB 4: ANALYTICS -->
                    <div id="tab-analytics" class="admin-tab hidden-view flex-col space-y-6">
                        <div class="flex flex-wrap gap-4 justify-between items-center mb-2">
                            <h2 class="text-2xl font-bold text-white">Deep Analytics</h2>
                            <button onclick="exportReport()" class="bg-slate-800 text-slate-300 px-4 py-2 rounded-lg font-bold text-sm hover:bg-slate-700 hover:text-white border border-slate-700 transition"><i class="fas fa-file-pdf mr-1"></i> Export PDF</button>
                        </div>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800 shadow-lg">
                                <h3 class="font-bold text-white mb-4">Mwenendo wa Maoni</h3>
                                <div class="h-64 w-full"><canvas id="trendChart"></canvas></div>
                            </div>
                            <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800 shadow-lg">
                                <h3 class="font-bold text-white mb-4">Utendaji Matawi (Heatmap)</h3>
                                <div id="branches-performance" class="space-y-4 mt-8"></div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 5: MATAWI -->
                    <div id="tab-matawi" class="admin-tab hidden-view flex-col space-y-4">
                        <div class="flex justify-between items-center mb-2">
                            <h2 class="text-2xl font-bold text-white">Matawi & Admins</h2>
                            <button onclick="showAddBranchForm()" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm font-bold hover:bg-blue-700 transition">Add Branch</button>
                        </div>
                        <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg">
                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="w-full text-left text-sm min-w-[500px]">
                                    <thead class="bg-slate-950 text-slate-400"><tr><th class="p-4">Tawi</th><th class="p-4">Meneja</th><th class="p-4">Status</th><th class="p-4">QR Scans</th></tr></thead>
                                    <tbody id="branches-table" class="divide-y divide-slate-800 text-slate-300"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 6: ZAWADI -->
                    <div id="tab-zawadi" class="admin-tab hidden-view flex-col space-y-4">
                        <div class="flex flex-wrap justify-between items-center mb-2 gap-2">
                            <h2 class="text-2xl font-bold text-white">Promo Codes (Rewards)</h2>
                            <div class="text-sm bg-green-500/20 text-green-400 px-3 py-1.5 rounded font-bold border border-green-500/30">Auto-Generation: ON</div>
                        </div>
                        <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg">
                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="w-full text-left text-sm min-w-[500px]">
                                    <thead class="bg-slate-950 text-slate-400"><tr><th class="p-4">Code</th><th class="p-4">Discount</th><th class="p-4">Mteja</th><th class="p-4">Status</th><th class="p-4">Expires</th></tr></thead>
                                    <tbody id="table-zawadi" class="divide-y divide-slate-800 text-slate-300"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
    // ============================================
    // GLOBAL VARIABLES
    // ============================================
    var API_BASE = '/smart_feedback/api/';
    var currentRating = 0;
    var chartInstance = null;
    var trendChartInst = null;
    var feedbacksData = [];
    var statsDataFromDB = {};
    var branchesData = [];
    var qrCodesData = [];

    // ============================================
    // HELPER FUNCTIONS
    // ============================================
    function showLoading(show) {
        var overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            if (show) overlay.classList.remove('hidden');
            else overlay.classList.add('hidden');
        }
    }

    function showToast(message, type) {
        type = type || 'success';
        var existingToast = document.querySelector('.toast-notification');
        if (existingToast) existingToast.remove();
        
        var toast = document.createElement('div');
        toast.className = 'toast-notification';
        
        var bgColor = type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-600' : 'bg-blue-600');
        var icon = type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle');
        
        toast.innerHTML = '<div class="' + bgColor + ' text-white px-5 py-3 rounded-xl shadow-2xl flex items-center font-bold text-sm">' +
            '<i class="fas ' + icon + ' text-xl mr-3"></i>' +
            '<span>' + message + '</span>' +
            '</div>';
        
        document.body.appendChild(toast);
        
        setTimeout(function() {
            toast.style.opacity = '0';
            setTimeout(function() { toast.remove(); }, 300);
        }, 4000);
    }

    // ============================================
    // LOGIN USING DATABASE - FIXED
    // ============================================
    async function simulateLogin(event) {
        if (event) event.preventDefault();

        var username = document.getElementById('login-username').value.trim();
        var password = document.getElementById('login-password').value.trim();
        var errorDiv = document.getElementById('login-error');
        var btn = document.getElementById('btn-login');

        errorDiv.classList.add('hidden');

        if (username === "" || password === "") {
            errorDiv.textContent = "Jaza username na password!";
            errorDiv.classList.remove('hidden');
            return false;
        }

        var originalText = btn.innerHTML;
        btn.innerHTML = '<div class="spinner"></div> Inaingiza...';
        btn.disabled = true;

        try {
            var response = await fetch(API_BASE + 'admin_login.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    username: username, 
                    password: password 
                })
            });

            var result = await response.json();
            console.log('Login response:', result);

            if (result.success) {
                localStorage.setItem('admin_logged_in', 'true');
                localStorage.setItem('admin_username', result.user.username);
                localStorage.setItem('admin_name', result.user.fullname);
                localStorage.setItem('admin_role', result.user.role);
                localStorage.setItem('admin_id', result.user.id);

                showToast('Karibu ' + result.user.fullname + '!', 'success');
                showView('admin-dashboard');
                loadAllData();
            } else {
                errorDiv.textContent = result.message || "Username au password si sahihi!";
                errorDiv.classList.remove('hidden');
                document.getElementById('login-password').value = '';
            }
        } catch (error) {
            console.error('Login error:', error);
            errorDiv.textContent = 'Tatizo la mtandao. Hakikisha XAMPP inaendesha. Jaribu: admin / admin123';
            errorDiv.classList.remove('hidden');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }

        return false;
    }

    function checkAdminSession() {
        var isLoggedIn = localStorage.getItem('admin_logged_in');
        var adminName = localStorage.getItem('admin_name');
        
        if (isLoggedIn === 'true' && adminName) {
            var currentView = document.querySelector('.view-section:not(.hidden-view)');
            if (currentView && currentView.id === 'view-admin-login') {
                showView('admin-dashboard');
                showToast('Karibu tena ' + adminName + '!', 'success');
                loadAllData();
            }
            return true;
        }
        return false;
    }

    function logout() {
        localStorage.removeItem('admin_logged_in');
        localStorage.removeItem('admin_username');
        localStorage.removeItem('admin_name');
        localStorage.removeItem('admin_role');
        localStorage.removeItem('admin_id');
        
        showToast('Akaunti imefungwa kikamilifu!', 'info');
        setTimeout(function() {
            showView('landing');
        }, 1000);
    }

    // ============================================
    // API CALLS
    // ============================================
    async function testConnection() {
        showLoading(true);
        
        try {
            var controller = new AbortController();
            var timeoutId = setTimeout(function() { controller.abort(); }, 10000);
            
            var response = await fetch(API_BASE + 'get_stats.php', {
                signal: controller.signal,
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });
            
            clearTimeout(timeoutId);
            
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            
            var data = await response.json();
            
            if (data.success) {
                console.log('✅ API Connection: SUCCESS');
                showToast('✅ Connected to database successfully!', 'success');
                return true;
            } else {
                console.error('❌ API Connection: FAILED');
                return false;
            }
        } catch (error) {
            console.error('❌ API Connection Error:', error);
            return false;
        } finally {
            showLoading(false);
        }
    }

    async function loadAllData() {
        showLoading(true);
        
        try {
            var statsResponse = await fetch(API_BASE + 'get_stats.php');
            var statsData = await statsResponse.json();
            
            if (statsData.success) {
                statsDataFromDB = statsData.stats || {};
                branchesData = statsData.branches || [];
                qrCodesData = statsData.qrcodes || [];
                console.log('✅ Stats loaded:', statsDataFromDB);
            }
            
            var feedbackResponse = await fetch(API_BASE + 'get_feedbacks.php');
            var feedbackData = await feedbackResponse.json();
            
            if (feedbackData.success) {
                feedbacksData = feedbackData.data || [];
                console.log('✅ Feedbacks loaded:', feedbacksData.length);
            }
            
            updateAdminDashboard();
            
        } catch (error) {
            console.error('Load data error:', error);
            showToast('Failed to load data from server', 'error');
        }
        
        showLoading(false);
    }

    async function submitUserFeedback() {
        if (currentRating === 0) {
            document.getElementById('rating-error').classList.remove('hidden');
            return;
        }
        
        var btn = document.getElementById('btn-submit');
        var originalText = btn.innerHTML;
        btn.innerHTML = '<div class="spinner"></div> Inachakata...';
        btn.disabled = true;
        
        var branchSelect = document.getElementById('user-branch');
        var branchId = branchSelect ? parseInt(branchSelect.value) : 1;
        
        var feedbackData = {
            branch_id: branchId,
            customer_name: document.getElementById('user-name').value || 'Anonymous',
            customer_email: document.getElementById('user-email').value || '',
            rating: currentRating,
            category: document.getElementById('user-category').value,
            comment: document.getElementById('user-comment').value,
            image_url: ''
        };
        
        try {
            var response = await fetch(API_BASE + 'save_feedback.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(feedbackData)
            });
            
            var result = await response.json();
            
            if (result.success) {
                document.getElementById('user-step-2').classList.remove('active-view-block');
                document.getElementById('user-step-2').classList.add('hidden-view');
                document.getElementById('user-step-3').classList.remove('hidden-view');
                document.getElementById('user-step-3').classList.add('active-view');
                document.getElementById('user-progress').style.width = '100%';
                document.getElementById('step3-indicator').classList.add('text-white');
                document.getElementById('display-promo').innerText = result.promo_code;
                
                showToast('✅ Maoni yametumwa! Promo code: ' + result.promo_code, 'success');
                await loadAllData();
            } else {
                showToast('❌ Error: ' + result.message, 'error');
            }
        } catch (error) {
            console.error('Submit error:', error);
            showToast('❌ Network error. Please check connection and try again.', 'error');
        }
        
        btn.innerHTML = originalText;
        btn.disabled = false;
    }

    // ============================================
    // UI NAVIGATION
    // ============================================
    function showView(viewId) {
        var sections = document.querySelectorAll('.view-section');
        for (var i = 0; i < sections.length; i++) {
            sections[i].classList.remove('active-view', 'active-view-block');
            sections[i].classList.add('hidden-view');
        }
        
        var targetView = document.getElementById('view-' + viewId);
        if (targetView) {
            targetView.classList.remove('hidden-view');
            if (viewId === 'admin-dashboard' || viewId === 'landing') {
                targetView.classList.add('active-view-block');
            } else {
                targetView.classList.add('active-view');
            }
        }
        
        var nav = document.getElementById('main-nav');
        if (nav) {
            if (viewId === 'admin-dashboard' || viewId === 'admin-login') {
                nav.classList.add('hidden');
            } else {
                nav.classList.remove('hidden');
            }
        }
        
        if (viewId === 'admin-dashboard') {
            loadAllData();
            renderTrendChart();
        }
        if (viewId === 'user-flow') {
            resetUserForm();
        }
        if (viewId === 'admin-login') {
            var errorDiv = document.getElementById('login-error');
            if (errorDiv) errorDiv.classList.add('hidden');
            var passwordField = document.getElementById('login-password');
            if (passwordField) passwordField.value = '';
        }
    }

    function resetUserForm() {
        currentRating = 0;
        var stars = document.querySelectorAll('#user-stars i');
        for (var i = 0; i < stars.length; i++) {
            var s = stars[i];
            s.classList.remove('fas', 'active', 'text-yellow-400');
            s.classList.add('far', 'text-slate-200');
        }
        
        var nameInput = document.getElementById('user-name');
        var emailInput = document.getElementById('user-email');
        var commentInput = document.getElementById('user-comment');
        var categorySelect = document.getElementById('user-category');
        
        if (nameInput) nameInput.value = '';
        if (emailInput) emailInput.value = '';
        if (commentInput) commentInput.value = '';
        if (categorySelect) categorySelect.value = 'Huduma kwa Wateja';
        
        var step3 = document.getElementById('user-step-3');
        if (step3) {
            step3.classList.remove('active-view');
            step3.classList.add('hidden-view');
        }
        
        nextUserStep(1);
    }

    function nextUserStep(step) {
        if (step === 2 && currentRating === 0) {
            document.getElementById('rating-error').classList.remove('hidden');
            return;
        }
        
        document.getElementById('rating-error').classList.add('hidden');
        
        var step1 = document.getElementById('user-step-1');
        var step2 = document.getElementById('user-step-2');
        
        if (step === 1) {
            step1.classList.remove('hidden-view');
            step1.classList.add('active-view-block');
            step2.classList.remove('active-view-block');
            step2.classList.add('hidden-view');
            document.getElementById('user-progress').style.width = '33%';
            document.getElementById('step1-indicator').classList.add('text-white');
            document.getElementById('step2-indicator').classList.remove('text-white');
        } else {
            step1.classList.remove('active-view-block');
            step1.classList.add('hidden-view');
            step2.classList.remove('hidden-view');
            step2.classList.add('active-view-block');
            document.getElementById('user-progress').style.width = '66%';
            document.getElementById('step1-indicator').classList.remove('text-white');
            document.getElementById('step2-indicator').classList.add('text-white');
        }
    }

    // ============================================
    // STAR RATING HANDLER
    // ============================================
    function initStarRating() {
        var stars = document.querySelectorAll('#user-stars i');
        for (var i = 0; i < stars.length; i++) {
            var star = stars[i];
            star.addEventListener('click', function(e) {
                currentRating = parseInt(e.target.getAttribute('data-val'));
                document.getElementById('rating-error').classList.add('hidden');
                var allStars = document.querySelectorAll('#user-stars i');
                for (var idx = 0; idx < allStars.length; idx++) {
                    var s = allStars[idx];
                    if (idx < currentRating) {
                        s.classList.remove('far');
                        s.classList.add('fas', 'active', 'text-yellow-400');
                    } else {
                        s.classList.remove('fas', 'active', 'text-yellow-400');
                        s.classList.add('far');
                    }
                }
            });
        }
    }

    // ============================================
    // ADMIN DASHBOARD FUNCTIONS
    // ============================================
    function updateAdminDashboard() {
        var pos = statsDataFromDB.positive || 0;
        var neu = statsDataFromDB.neutral || 0;
        var neg = statsDataFromDB.negative || 0;
        
        var totalEl = document.getElementById('db-total');
        var posEl = document.getElementById('db-pos');
        var neuEl = document.getElementById('db-neu');
        var negEl = document.getElementById('db-neg');
        
        if (totalEl) totalEl.innerText = statsDataFromDB.total_feedbacks || 0;
        if (posEl) posEl.innerText = pos;
        if (neuEl) neuEl.innerText = neu;
        if (negEl) negEl.innerText = neg;
        
        updateFeedbacksTable();
        updateRewardsTable();
        updateBranchesTable();
        updateQRCodesDisplay();
        updateBranchesPerformance();
        
        renderDashChart(pos, neu, neg);
        renderTrendChart();
    }

    function updateFeedbacksTable() {
        var maoniHTML = '';
        for (var i = 0; i < feedbacksData.length; i++) {
            var f = feedbacksData[i];
            var sentimentClass = f.sentiment === 'Positive' ? 'text-green-400' : 
                                  (f.sentiment === 'Negative' ? 'text-red-400' : 'text-yellow-400');
            var stars = '★'.repeat(f.rating) + '☆'.repeat(5 - f.rating);
            var date = new Date(f.created_at).toLocaleString();
            
            maoniHTML += '<tr class="hover:bg-slate-800/50 transition">' +
                '<td class="p-4 font-bold">' + escapeHtml(f.branch_name || 'N/A') + '</td>' +
                '<td class="p-4"><div class="text-xs text-slate-500">' + escapeHtml(f.customer_name || 'Anonymous') + '</div>' +
                '<div class="truncate max-w-[200px]">' + escapeHtml((f.comment || '').substring(0, 50)) + '</div></td>' +
                '<td class="p-4 text-yellow-400">' + stars + '</td>' +
                '<td class="p-4"><span class="px-2 py-1 rounded text-xs font-bold ' + sentimentClass + ' bg-slate-800">' + (f.sentiment || 'Neutral') + '</span></td>' +
                '<td class="p-4"><span class="px-2 py-1 rounded text-xs bg-slate-800">' + (f.status || 'Pending') + '</span></td>' +
                '<td class="p-4 text-xs">' + date + '</td>' +
                '</tr>';
        }
        
        var tableBody = document.getElementById('table-maoni');
        if (tableBody) {
            tableBody.innerHTML = maoniHTML || '<tr><td colspan="6" class="p-4 text-center">No feedbacks found</td></tr>';
        }
        
        updateAlerts();
    }

    function updateAlerts() {
        var alertsHTML = '';
        var pendingCount = 0;
        
        for (var i = 0; i < feedbacksData.length; i++) {
            var f = feedbacksData[i];
            if (f.status === 'Pending') pendingCount++;
            if (f.sentiment === 'Negative' && f.status === 'Pending') {
                var date = new Date(f.created_at).toLocaleString();
                alertsHTML += '<div class="bg-red-500/10 border-l-4 border-red-500 p-3 rounded mb-2">' +
                    '<div class="flex justify-between"><strong class="text-red-400 text-sm">' + escapeHtml(f.branch_name || 'Unknown') + '</strong>' +
                    '<span class="text-xs text-slate-500">' + date + '</span></div>' +
                    '<p class="text-slate-300 text-sm mt-1">"' + escapeHtml((f.comment || '').substring(0, 100)) + '"</p>' +
                    '</div>';
            }
        }
        
        var alertsContainer = document.getElementById('dashboard-alerts');
        if (alertsContainer) {
            alertsContainer.innerHTML = alertsHTML || '<p class="text-slate-500 text-sm italic">Hakuna alerts za kuhitaji kuingilia kwa sasa.</p>';
        }
        
        var badge = document.getElementById('new-feedback-badge');
        if (badge) badge.innerText = pendingCount;
    }

    function updateRewardsTable() {
        var zawadiHTML = '';
        for (var i = 0; i < feedbacksData.length; i++) {
            var f = feedbacksData[i];
            if (f.promo_code) {
                zawadiHTML += '<tr class="hover:bg-slate-800/50 transition">' +
                    '<td class="p-4 font-bold text-blue-400">' + escapeHtml(f.promo_code) + '</td>' +
                    '<td class="p-4 text-green-400 font-bold">15% OFF</td>' +
                    '<td class="p-4">' + escapeHtml(f.customer_name || 'Anonymous') + '</td>' +
                    '<td class="p-4"><span class="px-2 py-1 bg-slate-800 text-slate-400 text-xs rounded font-bold">Unused</span></td>' +
                    '<td class="p-4 text-xs">' + new Date(f.created_at).toLocaleDateString() + '</td>' +
                    '</tr>';
            }
        }
        var tableZawadi = document.getElementById('table-zawadi');
        if (tableZawadi) {
            tableZawadi.innerHTML = zawadiHTML || '<tr><td colspan="5" class="p-4 text-center">No rewards yet</td></tr>';
        }
    }

    function updateBranchesTable() {
        var branchesHTML = '';
        for (var i = 0; i < branchesData.length; i++) {
            var b = branchesData[i];
            branchesHTML += '<tr class="hover:bg-slate-800/50 transition">' +
                '<td class="p-4 font-bold">' + escapeHtml(b.branch_name) + '</td>' +
                '<td class="p-4">' + escapeHtml(b.manager_name || 'Not assigned') + '</td>' +
                '<td class="p-4"><span class="text-green-400"><i class="fas fa-circle text-[8px] mr-1"></i>' + (b.status || 'Active') + '</span></td>' +
                '<td class="p-4">' + (b.scan_count || 0) + '</td>' +
                '</tr>';
        }
        var branchesTable = document.getElementById('branches-table');
        if (branchesTable) {
            branchesTable.innerHTML = branchesHTML || '<tr><td colspan="4" class="p-4 text-center">No branches found</td></tr>';
        }
    }

    function updateQRCodesDisplay() {
        var qrHTML = '';
        for (var i = 0; i < qrCodesData.length; i++) {
            var qr = qrCodesData[i];
            var qrData = encodeURIComponent(qr.qr_code_data || API_BASE + 'feedback.php?branch=' + qr.branch_id);
            qrHTML += '<div class="bg-slate-900 p-5 rounded-2xl border border-slate-800 text-center relative shadow-lg">' +
                '<div class="absolute top-2 right-2 bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded font-bold">Active</div>' +
                '<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + qrData + '" class="mx-auto rounded-lg mb-4 bg-white p-2">' +
                '<h3 class="font-bold text-white">' + escapeHtml(qr.branch_name) + '</h3>' +
                '<p class="text-xs text-slate-400 mb-4">Scans: ' + (qr.scan_count || 0) + '</p>' +
                '<div class="flex gap-2 justify-center">' +
                '<button onclick="downloadQR(\'' + qrData + '\')" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-white text-sm rounded transition"><i class="fas fa-download"></i></button>' +
                '<button onclick="updateScanCount(' + qr.id + ')" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-white text-sm rounded transition"><i class="fas fa-sync-alt"></i> Scan</button>' +
                '</div></div>';
        }
        var qrContainer = document.getElementById('qr-codes-container');
        if (qrContainer) {
            qrContainer.innerHTML = qrHTML || '<p class="text-center text-slate-400">No QR codes found</p>';
        }
    }

    function updateBranchesPerformance() {
        var perfHTML = '';
        for (var i = 0; i < branchesData.length; i++) {
            var b = branchesData[i];
            var branchFeedbacks = [];
            for (var j = 0; j < feedbacksData.length; j++) {
                if (feedbacksData[j].branch_id === b.id) branchFeedbacks.push(feedbacksData[j]);
            }
            var positiveCount = 0;
            for (var k = 0; k < branchFeedbacks.length; k++) {
                if (branchFeedbacks[k].sentiment === 'Positive') positiveCount++;
            }
            var positivePercent = branchFeedbacks.length > 0 ? (positiveCount / branchFeedbacks.length) * 100 : 75;
            var barColor = positivePercent > 70 ? 'bg-green-500' : (positivePercent > 50 ? 'bg-yellow-500' : 'bg-red-500');
            
            perfHTML += '<div class="mb-3">' +
                '<div class="flex justify-between text-sm mb-1">' +
                '<span class="text-white">' + escapeHtml(b.branch_name) + '</span>' +
                '<span class="text-green-400">' + Math.round(positivePercent) + '% Positive</span>' +
                '</div>' +
                '<div class="w-full bg-slate-800 h-3 rounded-full overflow-hidden">' +
                '<div class="' + barColor + ' h-full transition-all" style="width: ' + positivePercent + '%"></div>' +
                '</div></div>';
        }
        var performanceContainer = document.getElementById('branches-performance');
        if (performanceContainer) {
            performanceContainer.innerHTML = perfHTML || '<p class="text-slate-400">No data available</p>';
        }
    }

    function downloadQR(qrData) {
        var url = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + qrData;
        window.open(url, '_blank');
        showToast('Downloading QR Code...', 'info');
    }

    async function updateScanCount(qrId) {
        try {
            var response = await fetch(API_BASE + 'manage_qr.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ qr_id: qrId })
            });
            var result = await response.json();
            if (result.success) {
                showToast('Scan count updated!', 'success');
                await loadAllData();
            }
        } catch (error) {
            console.error('Error updating scan count:', error);
            showToast('Error updating scan count', 'error');
        }
    }

    async function createNewQR() {
        try {
            var response = await fetch(API_BASE + 'manage_qr.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ branch_id: 1 })
            });
            var result = await response.json();
            if (result.success) {
                showToast('New QR code created!', 'success');
                await loadAllData();
            } else {
                showToast('Error creating QR code', 'error');
            }
        } catch (error) {
            console.error('Error creating QR:', error);
            showToast('Error creating QR code', 'error');
        }
    }

    function filterFeedbacks() {
        var searchInput = document.getElementById('search-feedback');
        var searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        
        var filtered = [];
        for (var i = 0; i < feedbacksData.length; i++) {
            var f = feedbacksData[i];
            if ((f.comment && f.comment.toLowerCase().includes(searchTerm)) ||
                (f.customer_name && f.customer_name.toLowerCase().includes(searchTerm)) ||
                (f.branch_name && f.branch_name.toLowerCase().includes(searchTerm))) {
                filtered.push(f);
            }
        }
        
        var maoniHTML = '';
        for (var j = 0; j < filtered.length; j++) {
            var f = filtered[j];
            var sentimentClass = f.sentiment === 'Positive' ? 'text-green-400' : 
                                  (f.sentiment === 'Negative' ? 'text-red-400' : 'text-yellow-400');
            var stars = '★'.repeat(f.rating) + '☆'.repeat(5 - f.rating);
            var date = new Date(f.created_at).toLocaleString();
            
            maoniHTML += '<tr class="hover:bg-slate-800/50 transition">' +
                '<td class="p-4 font-bold">' + escapeHtml(f.branch_name || 'N/A') + '</td>' +
                '<td class="p-4"><div class="truncate max-w-[200px]">' + escapeHtml((f.comment || '').substring(0, 50)) + '</div></td>' +
                '<td class="p-4 text-yellow-400">' + stars + '</td>' +
                '<td class="p-4"><span class="px-2 py-1 rounded text-xs font-bold ' + sentimentClass + ' bg-slate-800">' + (f.sentiment || 'Neutral') + '</span></td>' +
                '<td class="p-4"><span class="px-2 py-1 rounded text-xs bg-slate-800">' + (f.status || 'Pending') + '</span></td>' +
                '<td class="p-4 text-xs">' + date + '</td>' +
                '</tr>';
        }
        
        var tableBody = document.getElementById('table-maoni');
        if (tableBody) {
            tableBody.innerHTML = maoniHTML || '<tr><td colspan="6" class="p-4 text-center">No matches found</td></tr>';
        }
    }

    function showAddBranchForm() {
        showToast('Fomu ya kuongeza tawi itafunguka', 'info');
    }

    function exportReport() {
        showToast('Ripoti inaandaliwa...', 'info');
        setTimeout(function() { showToast('Ripoti imeexport kikamilifu!', 'success'); }, 2000);
    }

    function switchTab(tabId) {
        var tabs = document.querySelectorAll('.admin-tab');
        for (var i = 0; i < tabs.length; i++) {
            tabs[i].classList.add('hidden-view');
            tabs[i].classList.remove('active-view-block', 'active-view');
        }
        
        var targetTab = document.getElementById(tabId);
        if (targetTab) {
            targetTab.classList.remove('hidden-view');
            if (tabId === 'tab-dashboard') targetTab.classList.add('active-view-block');
            else targetTab.classList.add('active-view');
        }
        
        var navBtns = document.querySelectorAll('.admin-nav-btn');
        for (var j = 0; j < navBtns.length; j++) {
            navBtns[j].classList.remove('bg-slate-800', 'text-white');
            navBtns[j].classList.add('text-slate-300');
        }
        
        var activeBtn = document.getElementById('nav-' + tabId);
        if (activeBtn) {
            activeBtn.classList.add('bg-slate-800', 'text-white');
            activeBtn.classList.remove('text-slate-300');
        }
        
        if (tabId === 'tab-analytics') {
            renderTrendChart();
        }
    }

    function renderDashChart(pos, neu, neg) {
        var canvas = document.getElementById('dashChart');
        if (!canvas) return;
        
        if (chartInstance) chartInstance.destroy();
        
        chartInstance = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['Positive', 'Neutral', 'Negative'],
                datasets: [{ 
                    data: [pos || 1, neu || 1, neg || 1], 
                    backgroundColor: ['#4ade80', '#facc15', '#f87171'], 
                    borderWidth: 0 
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { position: 'right', labels: { color: '#94a3b8' } } 
                }, 
                cutout: '75%' 
            }
        });
    }

    function renderTrendChart() {
        var canvas = document.getElementById('trendChart');
        if (!canvas) return;
        
        if (trendChartInst) trendChartInst.destroy();
        
        var monthlyData = [0, 0, 0, 0, 0, 0];
        for (var i = 0; i < feedbacksData.length; i++) {
            var date = new Date(feedbacksData[i].created_at);
            var month = date.getMonth();
            if (month >= 0 && month < 6) {
                monthlyData[month]++;
            }
        }
        
        trendChartInst = new Chart(canvas, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{ 
                    label: 'Maoni Yaliyopokelewa', 
                    data: monthlyData, 
                    backgroundColor: '#4ade80', 
                    borderRadius: 4
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                scales: { 
                    y: { grid: { color: '#334155' }, ticks: { color: '#94a3b8' } }, 
                    x: { grid: { color: '#334155' }, ticks: { color: '#94a3b8' } } 
                }, 
                plugins: { legend: { labels: { color: '#94a3b8' } } } 
            }
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    // ============================================
    // INITIALIZATION
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 System initializing...');
        console.log('📡 API Base URL:', API_BASE);
        
        initStarRating();
        testConnection();
        checkAdminSession();
    });
    </script>
</body>
</html>