@php
    $showSearch = $showSearch ?? true;
    $activeNav = $activeNav ?? 'library';
    $showMobileMenu = $showMobileMenu ?? true;
@endphp
<header class="pe-global-header">
            <div class="pe-header-inner">

                @if ($showMobileMenu)
                <button id="mobileMenuBtn" class="pe-header-menu-btn" aria-label="Toggle menu">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="4" x2="20" y1="12" y2="12"></line>
                        <line x1="4" x2="20" y1="6" y2="6"></line>
                        <line x1="4" x2="20" y1="18" y2="18"></line>
                    </svg>
                </button>
                @endif

                <a class="pe-header-brand" href="{{ main_url('/') }}">
                    <div class="pe-brand-logo-wrapper">
                        <img src="{{ asset('img/logo.png') }}" alt="Poker Exams Logo" width="40" height="40" loading="eager">
                    </div>
                    <div class="pe-brand-text">
                        <span class="pe-brand-title">Poker Exams</span>
                        <span class="pe-brand-subtitle">Practice Tests</span>
                    </div>
                </a>
@if ($showSearch)
                <div class="pe-header-search-wrapper" id="headerSearchWrapper">
                    <div class="pe-header-search-relative">
                        <svg class="pe-search-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>

                        <input type="search" id="headerSearchInput" class="pe-header-search-input"
                            placeholder="Search exams, courses, or quizzes…" autocomplete="off"
                            aria-label="Search exams, courses, or quizzes">

                        <div id="headerSearchDropdown" class="pe-search-dropdown">
                            <div id="headerSearchResults"></div>
                        </div>
                    </div>
                </div>
@else
                <div class="pe-header-spacer"></div>
@endif
                <nav class="pe-header-nav">
                    <a class="pe-nav-link{{ ($activeNav ?? 'library') === 'home' ? ' active' : '' }}" href="{{ route('library.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
                            <path
                                d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z">
                            </path>
                        </svg>
                        Home
                    </a>
                    <a class="pe-nav-link{{ ($activeNav ?? 'library') === 'library' ? ' active' : '' }}" href="https://pokerexams.com/library">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2"></rect>
                            <path d="M3 9h18"></path>
                            <path d="M3 15h18"></path>
                            <path d="M9 3v18"></path>
                            <path d="M15 3v18"></path>
                        </svg>
                        Categories
                    </a>
                </nav>

                <div class="pe-header-profile-wrapper" id="peProfileWrapper">
                    <button class="pe-header-profile-btn" id="peProfileBtn">
                        <div class="pe-profile-avatar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <span class="pe-profile-name">Account</span>
                        <svg class="pe-profile-chevron" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>
                    </button>

                    <div class="pe-dropdown-menu pe-dropdown-profile" id="peProfileDropdown">
                        <div class="pe-dropdown-header">
                            <p class="pe-dropdown-title">Welcome Guest</p>
                            <p class="pe-dropdown-subtitle">Sign in to track your progress</p>
                        </div>
                        <a class="pe-dropdown-item" href="{{ main_url('/profile') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg> Profile
                        </a>
                        <a class="pe-dropdown-item" href="{{ main_url('/login') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <polyline points="10 17 15 12 10 7"></polyline>
                                <line x1="15" x2="3" y1="12" y2="12"></line>
                            </svg> Login
                        </a>
                    </div>
                </div>

            </div>
        </header>