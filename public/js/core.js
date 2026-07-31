document.addEventListener("DOMContentLoaded", function () {
    var menuBtn = document.getElementById("mobileMenuBtn");
    var sidebar = document.getElementById("mainSidebar");
    var overlay = document.getElementById("sidebarOverlay");

    function closeSidebar() {
        if (!sidebar || !overlay) return;
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
        document.body.style.overflow = "";
    }

    if (menuBtn && sidebar && overlay) {
        function toggleSidebar() {
            var open = sidebar.classList.toggle("active");
            overlay.classList.toggle("active", open);
            document.body.style.overflow = open ? "hidden" : "";
        }
        menuBtn.addEventListener("click", toggleSidebar);
        overlay.addEventListener("click", toggleSidebar);
    }

    function scrollToCourseAnchor(slug) {
        if (!slug && window.location.hash && window.location.hash.indexOf("#course-") === 0) {
            slug = window.location.hash.slice(8);
        }
        if (!slug) return;

        var courseEl = document.getElementById("course-" + slug);
        if (!courseEl) return;

        courseEl.classList.add("is-active");
        requestAnimationFrame(function () {
            courseEl.scrollIntoView({ behavior: "smooth", block: "start" });
        });
    }

    scrollToCourseAnchor();
    window.addEventListener("hashchange", function () {
        scrollToCourseAnchor();
    });

    document.querySelectorAll(".cat-card-header").forEach(function (header) {
        header.addEventListener("click", function () {
            if (window.innerWidth >= 640) return;

            var card = header.closest(".cat-card");
            if (!card) return;

            var wasOpen = card.classList.contains("is-expanded");
            document.querySelectorAll(".cat-card.is-expanded").forEach(function (c) {
                c.classList.remove("is-expanded");
            });
            if (!wasOpen) card.classList.add("is-expanded");
        });
    });

    document.querySelectorAll(".cat-more-btn").forEach(function (btn) {
        var originalHtml = btn.innerHTML;
        var expanded = false;
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            expanded = !expanded;
            btn.closest(".cat-list").querySelectorAll(".hidden-course").forEach(function (el) {
                el.classList.toggle("show-hidden-course", expanded);
            });
            btn.innerHTML = expanded
                ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg> Show less'
                : originalHtml;
        });
    });

    var profileBtn = document.getElementById("peProfileBtn");
    var profileDropdown = document.getElementById("peProfileDropdown");
    if (profileBtn && profileDropdown) {
        profileBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            profileDropdown.classList.toggle("show");
        });
        document.addEventListener("click", function (e) {
            if (!profileDropdown.contains(e.target) && !profileBtn.contains(e.target)) {
                profileDropdown.classList.remove("show");
            }
        });
    }

    initSearch("heroSearchInput", "heroSearchDropdown", "heroSearchResults", "heroSearchWrapper");
    initSearch("headerSearchInput", "headerSearchDropdown", "headerSearchResults", "headerSearchWrapper");
});

function initSearch(inputId, dropdownId, resultsId, wrapperId) {
    var input = document.getElementById(inputId);
    var dropdown = document.getElementById(dropdownId);
    var results = document.getElementById(resultsId);
    var wrapper = document.getElementById(wrapperId);
    var timer;

    if (!input || !dropdown || !results || !wrapper) return;

    input.addEventListener("input", function () {
        clearTimeout(timer);
        var query = input.value.trim();
        if (query.length < 2) {
            dropdown.style.display = "none";
            return;
        }
        timer = setTimeout(function () {
            fetch("/api/global-search?q=" + encodeURIComponent(query))
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    results.innerHTML = "";
                    if (!data.length) {
                        results.innerHTML = '<div class="pe-search-empty">No matching courses or quizzes found.</div>';
                        dropdown.style.display = "block";
                        return;
                    }
                    data.forEach(function (item) {
                        var isCourse = item.type === "course";
                        var icon = isCourse
                            ? "pe-icon-course"
                            : "pe-icon-quiz";
                        var badge = isCourse
                            ? "pe-badge-course"
                            : "pe-badge-quiz";
                        var svg = isCourse
                            ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>'
                            : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>';
                        results.insertAdjacentHTML("beforeend",
                            '<a class="pe-result-item" href="' + escapeHtml(item.url) + '">' +
                            '<div class="pe-icon-box ' + icon + '">' + svg + '</div>' +
                            '<div class="pe-result-text">' +
                            '<p class="pe-result-title">' + escapeHtml(item.title) + '</p>' +
                            '<p class="pe-result-subtitle">' + escapeHtml(item.subtitle) + '</p>' +
                            '</div><span class="pe-badge ' + badge + '">' + escapeHtml(item.type) + '</span></a>');
                    });
                    dropdown.style.display = "block";
                })
                .catch(function (err) { console.error("Search error:", err); });
        }, 250);
    });

    document.addEventListener("click", function (e) {
        if (!wrapper.contains(e.target)) dropdown.style.display = "none";
    });
}

function escapeHtml(str) {
    if (!str) return "";
    return String(str).replace(/[&<>'"]/g, function (tag) {
        return { "&": "&amp;", "<": "&lt;", ">": "&gt;", "'": "&#39;", '"': "&quot;" }[tag];
    });
}
