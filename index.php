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
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        @keyframes bounceIn { 0% { transform: scale(0.3); opacity: 0; } 50% { transform: scale(1.05); } 70% { transform: scale(0.9); } 100% { transform: scale(1); opacity: 1; } }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        @keyframes slideUp { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes countUp { from { opacity: 0; transform: scale(0.5); } to { opacity: 1; transform: scale(1); } }
        
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
            backdrop-filter: blur(4px);
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
        
        .skeleton {
            background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 8px;
        }
        
        .stat-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,0.3); }
        .stat-card .stat-icon { transition: all 0.3s ease; }
        .stat-card:hover .stat-icon { transform: scale(1.15) rotate(-5deg); }
        
        .btn-pulse { animation: pulse 2s ease-in-out infinite; }
        .bounce-in { animation: bounceIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
        
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(0,0,0,0.2); }
        
        .fade-in-up { animation: fadeInUp 0.6s ease forwards; opacity: 0; }
        .fade-in-up:nth-child(1) { animation-delay: 0.1s; }
        .fade-in-up:nth-child(2) { animation-delay: 0.2s; }
        .fade-in-up:nth-child(3) { animation-delay: 0.3s; }
        .fade-in-up:nth-child(4) { animation-delay: 0.4s; }
        
        .connection-dot {
            width: 8px; height: 8px; border-radius: 50%;
            display: inline-block; margin-right: 6px;
        }
        .connection-dot.online { background-color: #4ade80; box-shadow: 0 0 8px rgba(74, 222, 128, 0.6); }
        .connection-dot.offline { background-color: #f87171; box-shadow: 0 0 8px rgba(248, 113, 113, 0.6); animation: pulse 1.5s infinite; }
        
        .nav-link-active { background: rgba(59, 130, 246, 0.15); border-left: 3px solid #3b82f6; color: #60a5fa !important; }
        
        input:focus, select:focus, textarea:focus { transition: all 0.2s ease; }
        
        .table-row-hover { transition: all 0.2s ease; }
        .table-row-hover:hover { background: rgba(59, 130, 246, 0.05); }
        
        .badge { transition: all 0.2s ease; }
        .badge:hover { transform: scale(1.05); }
    </style>
</head>
<body class="antialiased text-slate-800 h-screen flex flex-col">

    <!-- Loading Indicator -->
    <div id="loadingOverlay" class="loading-overlay hidden">
        <div class="text-center bounce-in">
            <div class="spinner w-14 h-14 mb-4 border-4" style="border-width:4px"></div>
            <p class="text-lg font-bold" id="loading-text">Inapakia data...</p>
            <p class="text-sm text-slate-400 mt-1">Tafadhali subiri</p>
        </div>
    </div>

    <!-- Navbar Kuu -->
    <nav id="main-nav" class="bg-white shadow-sm p-4 sticky top-0 z-40 transition-all duration-300">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="font-bold text-xl text-blue-600 flex items-center cursor-pointer group" onclick="showView('landing')">
                <i class="fas fa-qrcode mr-2 text-2xl group-hover:rotate-12 transition-transform duration-300"></i> Smart<span class="text-slate-800">Feedback</span>
            </div>
            <div class="flex gap-2 sm:gap-3 items-center">
                <span id="connection-status" class="hidden sm:flex items-center text-xs font-bold px-3 py-1.5 rounded-full border" title="Connection Status">
                    <span class="connection-dot offline" id="conn-dot"></span>
                    <span id="conn-text">Inaunganisha...</span>
                </span>
                <button id="nav-login-btn" onclick="showView('admin-login')" class="px-4 py-2 bg-slate-800 text-white rounded-lg font-semibold shadow hover:bg-slate-700 text-sm transition hover:-translate-y-0.5 active:translate-y-0">
                    <i class="fas fa-lock sm:mr-2"></i> <span class="hidden sm:inline">Admin</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- VIEW 1: LANDING PAGE -->
    <div id="view-landing" class="view-section active-view flex-grow items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-100 p-4">
        <div class="max-w-4xl w-full grid md:grid-cols-2 gap-8 items-center bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden border border-white/50 card-hover">
            <div class="p-8 md:p-12 text-center md:text-left">
                <div class="inline-block bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-1.5 rounded-full text-xs font-bold mb-4 shadow-lg shadow-blue-500/20">
                    <i class="fas fa-database mr-1"></i> Real Database System
                </div>
                <h1 class="text-4xl md:text-5xl font-black mb-4 leading-tight text-slate-800">
                    Sauti yako,<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Zawadi yako.</span>
                </h1>
                <p class="text-slate-500 mb-8 leading-relaxed">Scan QR Code kutoa maoni yako kuhusu huduma zetu na upate papo hapo zawadi/punguzo la bei (Promo Code).</p>
                
                <div class="flex flex-col gap-3">
                    <button onclick="showView('user-flow')" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-blue-600/30 hover:shadow-xl hover:shadow-blue-600/40 hover:-translate-y-1 active:translate-y-0 transition-all duration-200 flex justify-between items-center group">
                        <span><i class="fas fa-user-edit mr-2 group-hover:rotate-12 transition-transform"></i> Toa Maoni (User Demo)</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    <button onclick="showView('admin-login')" class="w-full bg-gradient-to-r from-slate-700 to-slate-800 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 active:translate-y-0 transition-all duration-200 flex justify-between items-center group">
                        <span><i class="fas fa-shield-alt mr-2"></i> Ingia kama Admin</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
                <div class="mt-6 flex justify-center md:justify-start gap-6 text-slate-400 text-xs">
                    <span><i class="fas fa-star text-yellow-400 mr-1"></i> 5-Star Rating</span>
                    <span><i class="fas fa-gift text-green-400 mr-1"></i> Rewards</span>
                    <span><i class="fas fa-chart-bar text-blue-400 mr-1"></i> Analytics</span>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-12 flex items-center justify-center relative overflow-hidden h-full hidden md:flex">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-10 left-10 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute bottom-10 right-10 w-60 h-60 bg-blue-300 rounded-full blur-3xl"></div>
                </div>
                <div class="bg-white/10 backdrop-blur p-8 rounded-3xl shadow-2xl relative z-10 transform rotate-3 hover:rotate-0 hover:scale-105 transition-all duration-500 border border-white/20">
                    <div class="bg-white p-5 rounded-2xl">
                        <i class="fas fa-qrcode text-8xl text-slate-800"></i>
                    </div>
                    <div class="mt-4 text-center text-sm font-bold text-white/70 tracking-widest">SCAN ME</div>
                </div>
            </div>
        </div>
    </div>

    <!-- VIEW 2: USER FLOW -->
    <div id="view-user-flow" class="view-section hidden-view flex-grow items-center justify-center p-4 bg-gradient-to-br from-blue-50 to-slate-50">
        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200 relative card-hover">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-white text-center">
                <div class="inline-block bg-white/20 px-3 py-1 rounded-full text-xs font-bold mb-2">
                    <i class="fas fa-map-marker-alt mr-1"></i> <span id="selected-branch">Tawi la Posta, Dar</span>
                </div>
                <h2 class="text-2xl font-bold">Maoni Yako</h2>
                <div class="flex justify-between items-center mt-4 text-xs font-bold text-blue-200">
                    <span id="step1-indicator" class="text-white flex items-center"><span class="w-5 h-5 bg-white text-blue-600 rounded-full text-[10px] flex items-center justify-center mr-1">1</span> Nyota</span>
                    <span id="step2-indicator" class="flex items-center"><span class="w-5 h-5 bg-white/20 text-white rounded-full text-[10px] flex items-center justify-center mr-1">2</span> Ujumbe</span>
                    <span id="step3-indicator" class="flex items-center"><span class="w-5 h-5 bg-white/20 text-white rounded-full text-[10px] flex items-center justify-center mr-1">3</span> Zawadi</span>
                </div>
                <div class="w-full bg-blue-800/50 h-1.5 mt-3 rounded-full overflow-hidden">
                    <div id="user-progress" class="bg-gradient-to-r from-yellow-400 to-orange-500 h-full w-1/3 transition-all duration-700 ease-out"></div>
                </div>
            </div>

            <div id="user-step-1" class="p-6 active-view-block">
                <label class="block text-sm font-bold text-slate-700 mb-2 text-center">Unaipaje huduma yetu leo?</label>
                <div class="star-rating text-5xl text-slate-200 flex justify-center gap-3 mb-6" id="user-stars">
                    <i class="far fa-star" data-val="1"></i><i class="far fa-star" data-val="2"></i>
                    <i class="far fa-star" data-val="3"></i><i class="far fa-star" data-val="4"></i>
                    <i class="far fa-star" data-val="5"></i>
                </div>
                <p id="rating-label" class="text-center text-xs text-slate-400 mb-2 h-4">Bonyeza nyota kukadiria</p>
                
                <div id="rating-error" class="hidden text-red-500 text-xs text-center font-bold mb-4 bg-red-50 rounded-xl p-3">Tafadhali chagua nyota angalau moja.</div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1"><i class="fas fa-user text-slate-400 mr-1"></i> Jina lako <span class="text-slate-300 font-normal">(Sio Lazima)</span></label>
                        <input type="text" id="user-name" placeholder="Mfano: John Doe" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1"><i class="fas fa-envelope text-slate-400 mr-1"></i> Email yako <span class="text-slate-300 font-normal">(Sio Lazima)</span></label>
                        <input type="email" id="user-email" placeholder="Mfano: john@email.com" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1"><i class="fas fa-building text-slate-400 mr-1"></i> Chagua Tawi</label>
                        <select id="user-branch" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none bg-white transition-all cursor-pointer">
                            <option value="1">Posta (HQ), Dar</option>
                            <option value="2">Dodoma Branch</option>
                            <option value="3">Arusha Branch</option>
                            <option value="4">Mwanza Branch</option>
                        </select>
                    </div>
                </div>

                <button onclick="nextUserStep(2)" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold py-3.5 rounded-xl mt-6 hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg shadow-blue-600/20 hover:shadow-xl hover:shadow-blue-600/30 hover:-translate-y-0.5 active:translate-y-0">
                    Endelea <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>

            <div id="user-step-2" class="p-6 hidden-view">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1"><i class="fas fa-tag text-slate-400 mr-1"></i> Kundi la Maoni</label>
                        <select id="user-category" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none bg-white transition-all cursor-pointer">
                            <option>Huduma kwa Wateja</option>
                            <option>Ubora wa Bidhaa</option>
                            <option>Mazingira/Usafi</option>
                            <option>Mengineyo</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1"><i class="fas fa-pen text-slate-400 mr-1"></i> Ujumbe Wako</label>
                        <textarea id="user-comment" rows="4" placeholder="Tuambie nini kimekusibu au kukufurahisha..." class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all resize-none"></textarea>
                        <div class="text-right text-[10px] text-slate-400 mt-1"><span id="char-count">0</span> characters</div>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button onclick="nextUserStep(1)" class="w-1/3 bg-slate-200 text-slate-700 font-bold py-3 rounded-xl hover:bg-slate-300 transition-all hover:-translate-y-0.5 active:translate-y-0">
                        <i class="fas fa-arrow-left mr-1"></i> Rudi
                    </button>
                    <button onclick="submitUserFeedback()" id="btn-submit" class="w-2/3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 flex justify-center items-center gap-2 shadow-lg shadow-blue-600/20 hover:shadow-xl hover:shadow-blue-600/30 hover:-translate-y-0.5 active:translate-y-0">
                        <span><i class="fas fa-paper-plane mr-1"></i> Tuma Maoni</span>
                    </button>
                </div>
            </div>

            <div id="user-step-3" class="p-6 hidden-view text-center flex-col items-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 bounce-in">
                    <i class="fas fa-check text-4xl text-green-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">Ahsante Sana!</h3>
                <p class="text-slate-500 text-sm mb-6">Maoni yako yamepokelewa kwenye Database. Kama shukrani, pokea zawadi hii.</p>
                
                <div class="bg-gradient-to-r from-yellow-400 via-orange-500 to-red-500 p-1 rounded-2xl mb-6 w-full animate-pulse">
                    <div class="bg-white p-4 rounded-xl border border-dashed border-orange-200">
                        <p class="text-xs text-slate-500 mb-1 font-bold"><i class="fas fa-gift text-orange-500 mr-1"></i> PROMO CODE YAKO</p>
                        <h4 id="display-promo" class="text-4xl font-black text-slate-800 tracking-widest">---</h4>
                        <p class="text-orange-600 font-bold text-sm mt-2"><i class="fas fa-tags mr-1"></i> Punguzo la 15% Leo!</p>
                    </div>
                </div>
                
                <button onclick="showView('landing')" class="inline-flex items-center text-slate-500 font-bold text-sm hover:text-blue-600 transition-colors group">
                    <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Rudi Mwanzo
                </button>
            </div>
        </div>
    </div>

    <!-- VIEW 3: ADMIN LOGIN -->
    <div id="view-admin-login" class="view-section hidden-view flex-grow items-center justify-center p-4 bg-gradient-to-br from-slate-900 via-slate-900 to-blue-950">
        <div class="w-full max-w-sm bg-slate-800/90 backdrop-blur-sm rounded-3xl shadow-2xl shadow-black/50 p-8 border border-slate-700/50 relative">
            <button onclick="showView('landing')" class="absolute top-4 right-4 text-slate-400 hover:text-white transition hover:rotate-90">
                <i class="fas fa-times text-xl"></i>
            </button>
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto text-white text-2xl mb-4 shadow-lg shadow-blue-500/30 transform rotate-6 hover:rotate-0 transition-transform duration-300">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h2 class="text-2xl font-bold text-white">Admin Login</h2>
                <p class="text-slate-400 text-sm">Ingiza username na password yako</p>
            </div>

            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1"><i class="fas fa-user mr-1"></i> Username</label>
                    <input type="text" id="login-username" placeholder="admin" 
                           class="w-full bg-slate-900/50 border border-slate-700 text-white p-3 rounded-xl focus:border-blue-500 outline-none transition-all focus:ring-2 focus:ring-blue-500/20 focus:bg-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1"><i class="fas fa-lock mr-1"></i> Password</label>
                    <input type="password" id="login-password" placeholder="********" 
                           class="w-full bg-slate-900/50 border border-slate-700 text-white p-3 rounded-xl focus:border-blue-500 outline-none transition-all focus:ring-2 focus:ring-blue-500/20 focus:bg-slate-900">
                </div>
            </div>
            <div id="login-error" class="text-red-400 text-sm text-center mb-4 hidden bg-red-500/10 rounded-xl p-3 border border-red-500/20"></div>
            <button onclick="simulateLogin(event)" id="btn-login" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold py-3.5 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 flex justify-center items-center gap-2 shadow-lg shadow-blue-600/20 hover:shadow-xl hover:shadow-blue-600/30">
                <span><i class="fas fa-sign-in-alt mr-1"></i> Ingia</span>
            </button>
        </div>
    </div>

    <!-- VIEW 4: ADMIN DASHBOARD -->
    <div id="view-admin-dashboard" class="view-section hidden-view admin-bg">
        <div class="flex h-full">
            <aside class="w-64 bg-gradient-to-b from-slate-900 to-slate-950 border-r border-slate-800/50 flex-col h-full hidden md:flex shrink-0 sticky top-0">
                <div class="p-6 border-b border-slate-800/50 flex justify-between items-center">
                    <div class="font-bold text-xl text-blue-500 flex items-center"><i class="fas fa-database mr-2 text-blue-400"></i>Smart<span class="text-white">SRS</span></div>
                </div>
                <div class="p-4 flex-grow custom-scrollbar overflow-y-auto space-y-1">
                    <div class="text-[10px] font-bold text-slate-600 mb-3 uppercase tracking-widest pl-3">Menu Kuu</div>
                    <button onclick="switchTab('tab-dashboard')" id="nav-tab-dashboard" class="admin-nav-btn w-full flex items-center p-3 rounded-xl bg-slate-800/80 text-white font-medium transition-all duration-200">
                        <i class="fas fa-tachometer-alt w-6 text-blue-400"></i> Dashboard
                    </button>
                    <button onclick="switchTab('tab-maoni')" id="nav-tab-maoni" class="admin-nav-btn w-full flex items-center p-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white font-medium transition-all duration-200">
                        <i class="fas fa-comments w-6 text-slate-500"></i> Maoni <span id="new-feedback-badge" class="ml-auto bg-red-500/90 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">0</span>
                    </button>
                    <button onclick="switchTab('tab-qr')" id="nav-tab-qr" class="admin-nav-btn w-full flex items-center p-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white font-medium transition-all duration-200">
                        <i class="fas fa-qrcode w-6 text-slate-500"></i> QR Codes
                    </button>
                    <button onclick="switchTab('tab-analytics')" id="nav-tab-analytics" class="admin-nav-btn w-full flex items-center p-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white font-medium transition-all duration-200">
                        <i class="fas fa-chart-line w-6 text-slate-500"></i> Analytics
                    </button>
                    <button onclick="switchTab('tab-matawi')" id="nav-tab-matawi" class="admin-nav-btn w-full flex items-center p-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white font-medium transition-all duration-200">
                        <i class="fas fa-building w-6 text-slate-500"></i> Matawi
                    </button>
                    <button onclick="switchTab('tab-zawadi')" id="nav-tab-zawadi" class="admin-nav-btn w-full flex items-center p-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white font-medium transition-all duration-200">
                        <i class="fas fa-gift w-6 text-slate-500"></i> Zawadi
                    </button>
                </div>
                <div class="p-4 border-t border-slate-800/50">
                    <div class="flex items-center gap-3 mb-3 px-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold" id="admin-avatar">A</div>
                        <div class="text-xs">
                            <div class="text-white font-bold" id="admin-name-display">Admin</div>
                            <div class="text-slate-500" id="admin-role-display">admin</div>
                        </div>
                    </div>
                    <button onclick="logout()" class="w-full bg-slate-800/50 text-slate-400 font-bold py-2.5 rounded-xl hover:bg-red-500/20 hover:text-red-400 transition-all duration-200 flex items-center justify-center gap-2 border border-slate-700/30">
                        <i class="fas fa-sign-out-alt"></i> Log Out
                    </button>
                </div>
            </aside>

            <main class="flex-grow flex flex-col h-full overflow-hidden bg-slate-950 w-full">
                <header class="md:hidden bg-slate-900 border-b border-slate-800 z-10 shrink-0">
                    <div class="p-4 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="font-bold text-blue-500"><i class="fas fa-database mr-2"></i> SmartSRS</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-slate-500 hidden" id="mob-conn-status"><span class="connection-dot offline" id="mob-conn-dot"></span></span>
                            <button onclick="logout()" class="text-slate-400 p-2 hover:text-red-400 transition"><i class="fas fa-sign-out-alt text-xl"></i></button>
                        </div>
                    </div>
                    <div class="flex overflow-x-auto whitespace-nowrap p-2 gap-2 custom-scrollbar border-t border-slate-800">
                        <button onclick="switchTab('tab-dashboard')" id="mob-tab-dashboard" class="admin-mob-btn px-4 py-1.5 bg-blue-600 text-white rounded-full text-sm font-bold"><i class="fas fa-tachometer-alt mr-1"></i> Dashboard</button>
                        <button onclick="switchTab('tab-maoni')" id="mob-tab-maoni" class="admin-mob-btn px-4 py-1.5 bg-slate-800 text-slate-300 rounded-full text-sm font-bold"><i class="fas fa-comments mr-1"></i> Maoni</button>
                        <button onclick="switchTab('tab-qr')" id="mob-tab-qr" class="admin-mob-btn px-4 py-1.5 bg-slate-800 text-slate-300 rounded-full text-sm font-bold"><i class="fas fa-qrcode mr-1"></i> QR</button>
                        <button onclick="switchTab('tab-analytics')" id="mob-tab-analytics" class="admin-mob-btn px-4 py-1.5 bg-slate-800 text-slate-300 rounded-full text-sm font-bold"><i class="fas fa-chart-line mr-1"></i> Analytics</button>
                        <button onclick="switchTab('tab-matawi')" id="mob-tab-matawi" class="admin-mob-btn px-4 py-1.5 bg-slate-800 text-slate-300 rounded-full text-sm font-bold"><i class="fas fa-building mr-1"></i> Matawi</button>
                        <button onclick="switchTab('tab-zawadi')" id="mob-tab-zawadi" class="admin-mob-btn px-4 py-1.5 bg-slate-800 text-slate-300 rounded-full text-sm font-bold"><i class="fas fa-gift mr-1"></i> Zawadi</button>
                    </div>
                </header>

                <div class="flex-grow overflow-y-auto p-4 md:p-8 custom-scrollbar">
                    <!-- TAB 1: DASHBOARD -->
                    <div id="tab-dashboard" class="admin-tab active-view-block space-y-6">
                        <div class="flex flex-wrap justify-between items-start gap-2">
                            <div>
                                <h2 class="text-2xl font-bold text-white">Dashboard Overview</h2>
                                <p class="text-slate-400 text-sm">Muhtasari wa wakati halisi kutoka Database</p>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-900 px-3 py-1.5 rounded-full border border-slate-800">
                                <i class="fas fa-sync-alt text-blue-400" id="refresh-icon"></i>
                                <span>Imeonesha: <span id="last-updated-time">...</span></span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="stat-card bg-gradient-to-br from-slate-800 to-slate-900 p-4 md:p-5 rounded-2xl border border-slate-700/50 shadow-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="text-slate-400 text-xs md:text-sm font-bold">Total Feedback</div>
                                    <div class="stat-icon w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center text-blue-400"><i class="fas fa-clipboard-list text-sm"></i></div>
                                </div>
                                <div class="text-2xl md:text-3xl font-black text-white" id="db-total">0</div>
                                <div class="text-[10px] text-slate-600 mt-1">Maoni yote yaliyowasilishwa</div>
                            </div>
                            <div class="stat-card bg-gradient-to-br from-slate-800 to-slate-900 p-4 md:p-5 rounded-2xl border border-green-500/20 shadow-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="text-green-400 text-xs md:text-sm font-bold"><i class="fas fa-smile mr-1"></i> Positive</div>
                                    <div class="stat-icon w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center text-green-400"><i class="fas fa-thumbs-up text-sm"></i></div>
                                </div>
                                <div class="text-2xl md:text-3xl font-black text-green-400" id="db-pos">0</div>
                                <div class="text-[10px] text-slate-600 mt-1">Wateja walioridhika</div>
                            </div>
                            <div class="stat-card bg-gradient-to-br from-slate-800 to-slate-900 p-4 md:p-5 rounded-2xl border border-yellow-500/20 shadow-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="text-yellow-400 text-xs md:text-sm font-bold"><i class="fas fa-meh mr-1"></i> Neutral</div>
                                    <div class="stat-icon w-8 h-8 bg-yellow-500/20 rounded-lg flex items-center justify-center text-yellow-400"><i class="fas fa-minus text-sm"></i></div>
                                </div>
                                <div class="text-2xl md:text-3xl font-black text-yellow-400" id="db-neu">0</div>
                                <div class="text-[10px] text-slate-600 mt-1">Maoni ya kawaida</div>
                            </div>
                            <div class="stat-card bg-gradient-to-br from-slate-800 to-slate-900 p-4 md:p-5 rounded-2xl border border-red-500/30 relative overflow-hidden shadow-lg">
                                <div class="flex justify-between items-start mb-2 relative z-10">
                                    <div class="text-red-400 text-xs md:text-sm font-bold"><i class="fas fa-angry mr-1"></i> Negative</div>
                                    <div class="stat-icon w-8 h-8 bg-red-500/20 rounded-lg flex items-center justify-center text-red-400"><i class="fas fa-exclamation-triangle text-sm"></i></div>
                                </div>
                                <div class="text-2xl md:text-3xl font-black text-red-400 relative z-10" id="db-neg">0</div>
                                <div class="text-[10px] text-slate-600 mt-1 relative z-10">Wateja wasioridhika</div>
                                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-red-500/10 rounded-full blur-2xl"></div>
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
                        <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg card-hover">
                            <div class="p-4 border-b border-slate-800 flex flex-col sm:flex-row gap-3 justify-between items-center">
                                <div class="relative w-full sm:w-auto">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                                    <input type="text" id="search-feedback" placeholder="Tafuta maoni..." class="w-full sm:w-72 bg-slate-950 border border-slate-700 text-sm text-white pl-8 pr-3 py-2.5 rounded-lg outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                </div>
                                <button onclick="filterFeedbacks()" class="w-full sm:w-auto bg-blue-600/20 text-blue-400 px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-blue-600/30 transition border border-blue-500/20"><i class="fas fa-filter mr-1"></i> Filter Data</button>
                            </div>
                            <div id="feedbacks-table-skeleton" class="hidden p-4 space-y-3">
                                <div class="skeleton h-10 w-full"></div>
                                <div class="skeleton h-10 w-full"></div>
                                <div class="skeleton h-10 w-full"></div>
                                <div class="skeleton h-10 w-full"></div>
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
                            <button onclick="showAddBranchForm()" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold hover:from-blue-700 hover:to-blue-800 transition shadow-lg shadow-blue-600/20"><i class="fas fa-plus mr-1"></i> Add Branch</button>
                        </div>
                        <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg card-hover">
                            <div class="p-3 border-b border-slate-800 flex items-center gap-2 text-xs text-slate-500">
                                <i class="fas fa-info-circle"></i> Matawi yote yaliyosajiliwa katika mfumo
                            </div>
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
                            <div class="text-sm bg-green-500/20 text-green-400 px-4 py-1.5 rounded-full font-bold border border-green-500/30 shadow-lg shadow-green-500/10">
                                <i class="fas fa-sync-alt mr-1 animated-spin" style="animation: spin 2s linear infinite;"></i> Auto-Generation: ON
                            </div>
                        </div>
                        <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg card-hover">
                            <div class="p-3 border-b border-slate-800 flex items-center gap-2 text-xs text-slate-500">
                                <i class="fas fa-gift text-green-400"></i> Kila maoni yanapowasilishwa, promo code inazalishwa moja kwa moja
                            </div>
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
    function showLoading(show, msg) {
        var overlay = document.getElementById('loadingOverlay');
        var text = document.getElementById('loading-text');
        if (text && msg) text.innerText = msg;
        if (overlay) {
            if (show) overlay.classList.remove('hidden');
            else {
                overlay.classList.add('hidden');
                if (text) text.innerText = 'Inapakia data...';
            }
        }
    }

    function showToast(message, type) {
        type = type || 'success';
        var existingToast = document.querySelector('.toast-notification');
        if (existingToast) existingToast.remove();
        
        var toast = document.createElement('div');
        toast.className = 'toast-notification';
        
        var bgColor = type === 'success' ? 'from-green-600 to-emerald-600' : (type === 'error' ? 'from-red-600 to-rose-600' : 'from-blue-600 to-indigo-600');
        var icon = type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle');
        
        toast.innerHTML = '<div class="bg-gradient-to-r ' + bgColor + ' text-white px-5 py-3.5 rounded-xl shadow-2xl flex items-center font-bold text-sm shadow-black/30">' +
            '<div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-3"><i class="fas ' + icon + '"></i></div>' +
            '<span>' + message + '</span>' +
            '</div>';
        
        document.body.appendChild(toast);
        
        setTimeout(function() {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = 'all 0.3s ease';
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

                var nameDisplay = document.getElementById('admin-name-display');
                var roleDisplay = document.getElementById('admin-role-display');
                var avatar = document.getElementById('admin-avatar');
                if (nameDisplay) nameDisplay.innerText = result.user.fullname;
                if (roleDisplay) roleDisplay.innerText = result.user.role;
                if (avatar) avatar.innerText = (result.user.fullname || 'A').charAt(0).toUpperCase();

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
            errorDiv.textContent = 'Tatizo la mtandao.';
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
            var nameDisplay = document.getElementById('admin-name-display');
            var roleDisplay = document.getElementById('admin-role-display');
            var avatar = document.getElementById('admin-avatar');
            if (nameDisplay) nameDisplay.innerText = adminName || 'Admin';
            if (roleDisplay) roleDisplay.innerText = localStorage.getItem('admin_role') || 'admin';
            if (avatar) avatar.innerText = (adminName || 'A').charAt(0).toUpperCase();
            
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
    function updateConnStatus(online, msg) {
        var dot = document.getElementById('conn-dot');
        var text = document.getElementById('conn-text');
        var statusEl = document.getElementById('connection-status');
        if (!dot || !text || !statusEl) return;
        
        if (online) {
            dot.className = 'connection-dot online';
            text.textContent = msg || 'Imeunganishwa';
            statusEl.className = 'hidden sm:flex items-center text-xs font-bold px-3 py-1.5 rounded-full border border-green-500/30 bg-green-500/10 text-green-400';
        } else {
            dot.className = 'connection-dot offline';
            text.textContent = msg || 'Haijaunganishwa';
            statusEl.className = 'hidden sm:flex items-center text-xs font-bold px-3 py-1.5 rounded-full border border-red-500/30 bg-red-500/10 text-red-400';
        }
    }

    async function testConnection() {
        showLoading(true);
        updateConnStatus(false, 'Inaunganisha...');
        
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
                updateConnStatus(true, 'Imeunganishwa');
                showToast('Imeunganishwa kwenye database!', 'success');
                return true;
            } else {
                updateConnStatus(false, 'Hitilafu ya database');
                return false;
            }
        } catch (error) {
            console.error('❌ API Connection Error:', error);
            updateConnStatus(false, 'Hakuna mtandao');
            showToast('Tatizo la mtandao.', 'error');
            return false;
        } finally {
            showLoading(false);
        }
    }

    async function loadAllData() {
        showLoading(true);
        
        try {
            var controller = new AbortController();
            var timeoutId = setTimeout(function() { controller.abort(); }, 15000);
            
            var statsResponse = await fetch(API_BASE + 'get_stats.php', { signal: controller.signal });
            var statsData = await statsResponse.json();
            
            if (statsData.success) {
                statsDataFromDB = statsData.stats || {};
                branchesData = statsData.branches || [];
                qrCodesData = statsData.qrcodes || [];
                updateConnStatus(true, 'Imeunganishwa');
            } else {
                updateConnStatus(false, 'Hitilafu ya database');
            }
            
            var feedbackResponse = await fetch(API_BASE + 'get_feedbacks.php', { signal: controller.signal });
            var feedbackData = await feedbackResponse.json();
            
            if (feedbackData.success) {
                feedbacksData = feedbackData.data || [];
            }
            
            clearTimeout(timeoutId);
            updateAdminDashboard();
            
            var timeEl = document.getElementById('last-updated-time');
            if (timeEl) timeEl.innerText = new Date().toLocaleTimeString();
            
        } catch (error) {
            console.error('Load data error:', error);
            updateConnStatus(false, 'Hakuna mtandao');
            showToast('Imeshindwa kupakia data.', 'error');
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
            var controller = new AbortController();
            var timeoutId = setTimeout(function() { controller.abort(); }, 10000);
            
            var response = await fetch(API_BASE + 'save_feedback.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(feedbackData),
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            var result = await response.json();
            
            if (result.success) {
                document.getElementById('user-step-2').classList.remove('active-view-block');
                document.getElementById('user-step-2').classList.add('hidden-view');
                document.getElementById('user-step-3').classList.remove('hidden-view');
                document.getElementById('user-step-3').classList.add('active-view');
                document.getElementById('user-progress').style.width = '100%';
                document.getElementById('step3-indicator').classList.add('text-white');
                document.getElementById('display-promo').innerText = result.promo_code;
                
                showToast('Maoni yametumwa! Promo code: ' + result.promo_code, 'success');
                await loadAllData();
            } else {
                showToast('Hitilafu: ' + result.message, 'error');
            }
        } catch (error) {
            console.error('Submit error:', error);
            if (error.name === 'AbortError') {
                showToast('Ombi limeisha muda. Angalia mtandao wako.', 'error');
            } else {
                showToast('Tatizo la mtandao.', 'error');
            }
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
            startAutoRefresh();
        } else {
            stopAutoRefresh();
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
        var labels = ['', 'Mbaya', 'Hafifu', 'Wastani', 'Nzuri', 'Bora Kabisa!'];
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
                var label = document.getElementById('rating-label');
                if (label) label.innerText = labels[currentRating] || '';
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
        
        animateCounter(totalEl, statsDataFromDB.total_feedbacks || 0, 1000);
        animateCounter(posEl, pos, 800);
        animateCounter(neuEl, neu, 800);
        animateCounter(negEl, neg, 800);
        
        updateFeedbacksTable();
        updateRewardsTable();
        updateBranchesTable();
        updateQRCodesDisplay();
        updateBranchesPerformance();
        
        renderDashChart(pos, neu, neg);
        renderTrendChart();
    }

    function getSentimentBadge(sentiment) {
        if (sentiment === 'Positive') return '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/15 text-green-400 border border-green-500/20"><i class="fas fa-smile text-[10px]"></i> Positive</span>';
        if (sentiment === 'Negative') return '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-500/15 text-red-400 border border-red-500/20"><i class="fas fa-frown text-[10px]"></i> Negative</span>';
        return '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-500/15 text-yellow-400 border border-yellow-500/20"><i class="fas fa-meh text-[10px]"></i> Neutral</span>';
    }

    function getStatusBadge(status) {
        if (status === 'Resolved') return '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/15 text-green-400 border border-green-500/20"><i class="fas fa-check-circle text-[10px]"></i> Resolved</span>';
        if (status === 'Replied') return '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-500/15 text-blue-400 border border-blue-500/20"><i class="fas fa-reply text-[10px]"></i> Replied</span>';
        return '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-500/15 text-yellow-400 border border-yellow-500/20"><i class="fas fa-clock text-[10px]"></i> Pending</span>';
    }

    function updateFeedbacksTable() {
        var maoniHTML = '';
        for (var i = 0; i < feedbacksData.length; i++) {
            var f = feedbacksData[i];
            var stars = '';
            for (var s = 0; s < 5; s++) {
                stars += s < f.rating ? '<i class="fas fa-star text-yellow-400 text-xs"></i>' : '<i class="far fa-star text-slate-600 text-xs"></i>';
            }
            var date = new Date(f.created_at).toLocaleString();
            
            maoniHTML += '<tr class="table-row-hover border-b border-slate-800/50">' +
                '<td class="p-4"><div class="font-bold text-white text-sm">' + escapeHtml(f.branch_name || 'N/A') + '</div></td>' +
                '<td class="p-4"><div class="text-xs text-slate-400">' + escapeHtml(f.customer_name || 'Anonymous') + '</div>' +
                '<div class="truncate max-w-[200px] text-slate-300 text-xs mt-1">"' + escapeHtml((f.comment || '').substring(0, 60)) + '"</div></td>' +
                '<td class="p-4"><div class="flex gap-0.5">' + stars + '</div></td>' +
                '<td class="p-4">' + getSentimentBadge(f.sentiment) + '</td>' +
                '<td class="p-4">' + getStatusBadge(f.status) + '</td>' +
                '<td class="p-4 text-xs text-slate-500">' + date + '</td>' +
                '</tr>';
        }
        
        var tableBody = document.getElementById('table-maoni');
        if (tableBody) {
            tableBody.innerHTML = maoniHTML || '<tr><td colspan="6" class="p-8 text-center text-slate-500"><i class="fas fa-inbox text-2xl mb-2 block"></i> Hakuna maoni bado</td></tr>';
        }
        
        updateAlerts();
    }

    function updateAlerts() {
        var alertsHTML = '';
        var pendingCount = 0;
        var alertCount = 0;
        
        for (var i = 0; i < feedbacksData.length; i++) {
            var f = feedbacksData[i];
            if (f.status === 'Pending') pendingCount++;
            if (f.sentiment === 'Negative' && f.status === 'Pending') {
                alertCount++;
                var date = new Date(f.created_at).toLocaleString();
                var ratingLabel = f.rating === 1 ? '★' : '★'.repeat(f.rating);
                alertsHTML += '<div class="bg-gradient-to-r from-red-500/10 to-transparent border-l-4 border-red-500 p-3.5 rounded-r-xl mb-2.5 card-hover">' +
                    '<div class="flex justify-between items-start"><strong class="text-red-400 text-sm flex items-center gap-1"><i class="fas fa-exclamation-triangle text-[10px]"></i> ' + escapeHtml(f.branch_name || 'Unknown') + '</strong>' +
                    '<span class="text-[10px] text-slate-600 whitespace-nowrap ml-2">' + date + '</span></div>' +
                    '<div class="text-yellow-400 text-xs my-1">' + ratingLabel + '/5</div>' +
                    '<p class="text-slate-400 text-xs mt-1 italic">"' + escapeHtml((f.comment || '').substring(0, 100)) + '"</p>' +
                    '</div>';
            }
        }
        
        var alertsContainer = document.getElementById('dashboard-alerts');
        if (alertsContainer) {
            if (alertCount > 0) {
                alertsContainer.innerHTML = '<div class="flex items-center gap-2 mb-3 text-xs text-red-400"><i class="fas fa-bell"></i> <span>' + alertCount + ' negative feedbacks waiting</span></div>' + alertsHTML;
            } else {
                alertsContainer.innerHTML = '<div class="text-center py-8"><div class="w-12 h-12 bg-green-500/10 rounded-full flex items-center justify-center mx-auto mb-3"><i class="fas fa-check text-green-400"></i></div><p class="text-slate-500 text-sm">Hakuna alerts za kuhitaji kuingilia kwa sasa.</p></div>';
            }
        }
        
        var badge = document.getElementById('new-feedback-badge');
        if (badge) badge.innerText = pendingCount;
    }

    function updateRewardsTable() {
        var zawadiHTML = '';
        for (var i = 0; i < feedbacksData.length; i++) {
            var f = feedbacksData[i];
            if (f.promo_code) {
                zawadiHTML += '<tr class="table-row-hover border-b border-slate-800/50">' +
                    '<td class="p-4 font-bold text-blue-400 font-mono text-sm">' + escapeHtml(f.promo_code) + '</td>' +
                    '<td class="p-4"><span class="px-2 py-1 bg-green-500/15 text-green-400 text-xs rounded-full font-bold border border-green-500/20">15% OFF</span></td>' +
                    '<td class="p-4 text-sm">' + escapeHtml(f.customer_name || 'Anonymous') + '</td>' +
                    '<td class="p-4"><span class="px-2.5 py-1 bg-slate-800 text-slate-400 text-xs rounded-full font-bold border border-slate-700"><i class="fas fa-circle text-[6px] text-green-400 mr-1"></i> Unused</span></td>' +
                    '<td class="p-4 text-xs text-slate-500">' + new Date(f.created_at).toLocaleDateString() + '</td>' +
                    '</tr>';
            }
        }
        var tableZawadi = document.getElementById('table-zawadi');
        if (tableZawadi) {
            tableZawadi.innerHTML = zawadiHTML || '<tr><td colspan="5" class="p-8 text-center text-slate-500"><i class="fas fa-gift text-2xl mb-2 block"></i> Hakuna zawadi bado</td></tr>';
        }
    }

    function updateBranchesTable() {
        var branchesHTML = '';
        for (var i = 0; i < branchesData.length; i++) {
            var b = branchesData[i];
            var statusClass = b.status === 'active' ? 'text-green-400 border-green-500/20 bg-green-500/10' : 'text-red-400 border-red-500/20 bg-red-500/10';
            branchesHTML += '<tr class="table-row-hover border-b border-slate-800/50">' +
                '<td class="p-4"><div class="font-bold text-white flex items-center gap-2"><i class="fas fa-store text-slate-500"></i> ' + escapeHtml(b.branch_name) + '</div></td>' +
                '<td class="p-4 text-sm">' + escapeHtml(b.manager_name || '<span class="text-slate-500">Not assigned</span>') + '</td>' +
                '<td class="p-4"><span class="px-2.5 py-1 rounded-full text-xs font-bold border ' + statusClass + '"><i class="fas fa-circle text-[6px] mr-1"></i>' + (b.status || 'Active') + '</span></td>' +
                '<td class="p-4"><span class="text-sm font-bold text-blue-400">' + (b.scan_count || 0) + '</span> <span class="text-xs text-slate-500">scans</span></td>' +
                '</tr>';
        }
        var branchesTable = document.getElementById('branches-table');
        if (branchesTable) {
            branchesTable.innerHTML = branchesHTML || '<tr><td colspan="4" class="p-8 text-center text-slate-500"><i class="fas fa-building text-2xl mb-2 block"></i> Hakuna matawi</td></tr>';
        }
    }

    function updateQRCodesDisplay() {
        var qrHTML = '';
        for (var i = 0; i < qrCodesData.length; i++) {
            var qr = qrCodesData[i];
            var qrData = encodeURIComponent(qr.qr_code_data || API_BASE + 'feedback.php?branch=' + qr.branch_id);
            qrHTML += '<div class="bg-gradient-to-b from-slate-900 to-slate-950 p-5 rounded-2xl border border-slate-800/50 text-center relative shadow-lg card-hover">' +
                '<div class="absolute top-3 right-3 bg-green-500/15 text-green-400 text-[10px] px-2 py-0.5 rounded-full font-bold border border-green-500/20"><i class="fas fa-circle text-[6px] mr-1"></i> Active</div>' +
                '<div class="bg-white rounded-xl p-3 mb-4 inline-block shadow-lg"><img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + qrData + '" class="mx-auto w-32 h-32"></div>' +
                '<h3 class="font-bold text-white">' + escapeHtml(qr.branch_name) + '</h3>' +
                '<div class="flex items-center justify-center gap-1 text-xs text-slate-400 mb-4 mt-1"><i class="fas fa-qrcode"></i> Scans: <span class="text-blue-400 font-bold">' + (qr.scan_count || 0) + '</span></div>' +
                '<div class="flex gap-2 justify-center">' +
                '<button onclick="downloadQR(\'' + qrData + '\')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-sm rounded-xl transition-all hover:-translate-y-0.5"><i class="fas fa-download mr-1"></i> Download</button>' +
                '<button onclick="updateScanCount(' + qr.id + ')" class="px-4 py-2 bg-blue-600/20 hover:bg-blue-600/30 text-blue-400 text-sm rounded-xl transition-all hover:-translate-y-0.5 border border-blue-500/20"><i class="fas fa-sync-alt mr-1"></i> Scan</button>' +
                '</div></div>';
        }
        var qrContainer = document.getElementById('qr-codes-container');
        if (qrContainer) {
            qrContainer.innerHTML = qrHTML || '<div class="col-span-full text-center py-12 text-slate-500"><i class="fas fa-qrcode text-4xl mb-4 block opacity-30"></i> Hakuna QR codes bado</div>';
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
            var total = branchFeedbacks.length;
            var positivePercent = total > 0 ? (positiveCount / total) * 100 : 0;
            var barColor = positivePercent >= 70 ? 'from-green-500 to-emerald-500' : (positivePercent >= 40 ? 'from-yellow-500 to-orange-500' : 'from-red-500 to-rose-500');
            var feedbackCount = total > 0 ? total + ' maoni' : 'Hakuna maoni';
            
            perfHTML += '<div class="mb-4 fade-in-up">' +
                '<div class="flex justify-between text-sm mb-1.5">' +
                '<span class="text-white flex items-center gap-2"><span class="w-6 h-6 rounded-lg bg-slate-800 flex items-center justify-center text-[10px] text-slate-400">' + (i + 1) + '</span> ' + escapeHtml(b.branch_name) + '</span>' +
                '<span class="flex items-center gap-2"><span class="text-xs text-slate-500">' + feedbackCount + '</span> <span class="' + (positivePercent >= 70 ? 'text-green-400' : positivePercent >= 40 ? 'text-yellow-400' : 'text-red-400') + ' font-bold">' + Math.round(positivePercent) + '%</span></span>' +
                '</div>' +
                '<div class="w-full bg-slate-800 h-3 rounded-full overflow-hidden">' +
                '<div class="bg-gradient-to-r ' + barColor + ' h-full rounded-full transition-all duration-1000" style="width: ' + positivePercent + '%"></div>' +
                '</div></div>';
        }
        var performanceContainer = document.getElementById('branches-performance');
        if (performanceContainer) {
            performanceContainer.innerHTML = perfHTML || '<p class="text-slate-500 text-center py-8"><i class="fas fa-chart-bar text-2xl mb-2 block opacity-30"></i> No data available</p>';
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
            var stars = '';
            for (var s = 0; s < 5; s++) {
                stars += s < f.rating ? '<i class="fas fa-star text-yellow-400 text-xs"></i>' : '<i class="far fa-star text-slate-600 text-xs"></i>';
            }
            var date = new Date(f.created_at).toLocaleString();
            
            maoniHTML += '<tr class="table-row-hover border-b border-slate-800/50">' +
                '<td class="p-4"><div class="font-bold text-white text-sm">' + escapeHtml(f.branch_name || 'N/A') + '</div></td>' +
                '<td class="p-4"><div class="truncate max-w-[200px] text-slate-300 text-xs">"' + escapeHtml((f.comment || '').substring(0, 60)) + '"</div></td>' +
                '<td class="p-4"><div class="flex gap-0.5">' + stars + '</div></td>' +
                '<td class="p-4">' + getSentimentBadge(f.sentiment) + '</td>' +
                '<td class="p-4">' + getStatusBadge(f.status) + '</td>' +
                '<td class="p-4 text-xs text-slate-500">' + date + '</td>' +
                '</tr>';
        }
        
        var tableBody = document.getElementById('table-maoni');
        if (tableBody) {
            tableBody.innerHTML = maoniHTML || '<tr><td colspan="6" class="p-8 text-center text-slate-500"><i class="fas fa-search text-2xl mb-2 block"></i> Hakuna matokeo ya utafutaji</td></tr>';
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
            navBtns[j].classList.remove('bg-slate-800/80', 'text-white');
            navBtns[j].classList.add('text-slate-400');
        }
        
        var activeBtn = document.getElementById('nav-' + tabId);
        if (activeBtn) {
            activeBtn.classList.add('bg-slate-800/80', 'text-white');
            activeBtn.classList.remove('text-slate-400');
        }
        
        var mobBtns = document.querySelectorAll('.admin-mob-btn');
        for (var k = 0; k < mobBtns.length; k++) {
            mobBtns[k].classList.remove('bg-blue-600', 'text-white');
            mobBtns[k].classList.add('bg-slate-800', 'text-slate-300');
        }
        var activeMobBtn = document.getElementById('mob-' + tabId);
        if (activeMobBtn) {
            activeMobBtn.classList.remove('bg-slate-800', 'text-slate-300');
            activeMobBtn.classList.add('bg-blue-600', 'text-white');
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

    function animateCounter(el, target, duration) {
        if (!el) return;
        duration = duration || 800;
        var start = 0;
        var startTime = null;
        
        function step(timestamp) {
            if (!startTime) startTime = timestamp;
            var progress = Math.min((timestamp - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            el.innerText = Math.floor(eased * target);
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                el.innerText = target;
            }
        }
        requestAnimationFrame(step);
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
    // AUTO REFRESH
    // ============================================
    var autoRefreshInterval = null;

    function startAutoRefresh() {
        if (autoRefreshInterval) clearInterval(autoRefreshInterval);
        autoRefreshInterval = setInterval(function() {
            var dashView = document.getElementById('view-admin-dashboard');
            if (dashView && !dashView.classList.contains('hidden-view')) {
                loadAllData();
            }
        }, 60000);
    }

    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
            autoRefreshInterval = null;
        }
    }

    // ============================================
    // CHARACTER COUNTER
    // ============================================
    var commentField = document.getElementById('user-comment');
    if (commentField) {
        commentField.addEventListener('input', function() {
            var count = document.getElementById('char-count');
            if (count) count.innerText = this.value.length;
        });
    }

    // ============================================
    // ENTER KEY SUPPORT FOR LOGIN
    // ============================================
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            var loginView = document.getElementById('view-admin-login');
            if (loginView && !loginView.classList.contains('hidden-view')) {
                simulateLogin(e);
            }
        }
    });

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