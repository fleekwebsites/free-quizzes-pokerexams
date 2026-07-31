/* Quiz interactions */
document.addEventListener("DOMContentLoaded", function () {
    if (!window.quizConfig) return;

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) return;
    const csrfToken = csrfMeta.getAttribute("content");
    const toast = document.getElementById("intToast");

    function showToast(message) {
        if (!toast) return;
        toast.innerText = message;
        toast.style.display = "block";
        setTimeout(() => (toast.style.display = "none"), 3000);
    }

    function createFallbackShareModal() {
        if (document.getElementById("intModalShareFallback")) return;

        document.body.insertAdjacentHTML(
            "beforeend",
            '<div class="int-modal-overlay int-share-overlay" id="intModalShareFallback">' +
                '<div class="int-modal-box">' +
                '<button class="int-modal-close" id="closeShareModal" aria-label="Close modal">' +
                '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
                "</button>" +
                '<h3 class="int-modal-title">Share Practice Test</h3>' +
                '<p class="int-modal-subtitle">Choose a platform to share.</p>' +
                '<div class="int-share-list">' +
                '<a href="#" id="share-wa" class="int-share-item" target="_blank" rel="noopener">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#25D366" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> WhatsApp</a>' +
                '<a href="#" id="share-tw" class="int-share-item" target="_blank" rel="noopener">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0f1419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg> X (Twitter)</a>' +
                '<a href="#" id="share-fb" class="int-share-item" target="_blank" rel="noopener">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1877F2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg> Facebook</a>' +
                '<button type="button" id="share-copy" class="int-share-item">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"></rect><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path></svg> Copy Link</button>' +
                "</div></div></div>",
        );

        const modal = document.getElementById("intModalShareFallback");
        document.getElementById("closeShareModal").addEventListener("click", function () {
            modal.style.display = "none";
        });
        modal.addEventListener("click", function (e) {
            if (e.target === modal) modal.style.display = "none";
        });
        document.getElementById("share-copy").addEventListener("click", function () {
            navigator.clipboard.writeText(window.location.href);
            showToast("Link copied to clipboard!");
            modal.style.display = "none";
        });
    }

    function openFallbackShare(url, title) {
        createFallbackShareModal();
        var encUrl = encodeURIComponent(url);
        var encTitle = encodeURIComponent(title);
        document.getElementById("share-wa").href =
            "https://api.whatsapp.com/send?text=" + encTitle + "%20" + encUrl;
        document.getElementById("share-tw").href =
            "https://twitter.com/intent/tweet?text=" + encTitle + "&url=" + encUrl;
        document.getElementById("share-fb").href =
            "https://www.facebook.com/sharer/sharer.php?u=" + encUrl;
        document.getElementById("intModalShareFallback").style.display = "flex";
    }

    document.querySelectorAll('.btn-share, button[title="Copy link"]').forEach(function (btn) {
        if (btn.hasAttribute("onclick")) btn.removeAttribute("onclick");
        btn.addEventListener("click", async function (e) {
            e.preventDefault();
            e.stopPropagation();
            var shareUrl = window.location.href;
            var shareTitle = document.title;
            if (navigator.share) {
                try {
                    await navigator.share({ title: shareTitle, url: shareUrl });
                } catch (err) {
                    /* user canceled */
                }
            } else {
                openFallbackShare(shareUrl, shareTitle);
            }
        });
    });

    function setupModal(modalId, btnSelector, formId, getEntityData) {
        const modal = document.getElementById(modalId);
        const btns = document.querySelectorAll(btnSelector);
        const closeBtn = modal?.querySelector(".int-modal-close");
        const form = document.getElementById(formId);

        if (!modal || !form || btns.length === 0) return;

        let currentEntityData = {};

        btns.forEach((btn) => {
            btn.removeAttribute("onclick");
            btn.addEventListener("click", (e) => {
                e.preventDefault();
                e.stopPropagation();
                currentEntityData = getEntityData(btn);
                modal.style.display = "flex";
            });
        });

        closeBtn.addEventListener(
            "click",
            () => (modal.style.display = "none"),
        );
        modal.addEventListener("click", (e) => {
            if (e.target === modal) modal.style.display = "none";
        });

        form.addEventListener("submit", function (e) {
            e.preventDefault();
            const submitBtn = form.querySelector(".int-btn-submit");
            submitBtn.disabled = true;
            submitBtn.innerText = "Submitting...";

            const formData = new FormData(form);
            const payload = Object.fromEntries(formData.entries());
            Object.assign(payload, currentEntityData);

            fetch(form.getAttribute("action"), {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(payload),
            })
                .then((res) => res.json())
                .then((data) => {
                    showToast(data.message || "Success!");
                    modal.style.display = "none";
                    form.reset();
                })
                .catch((err) => {
                    showToast("An error occurred. Please try again.");
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerText = "Submit";
                });
        });
    }

    setupModal(
        "intModalFlag",
        'button[title="Flag an issue"], .btn-flag',
        "intFormFlag",
        (btn) => {
            const activeOptions = document.querySelector(
                '.question-slide[style*="display: block"] .options-container',
            );
            if (activeOptions) {
                return {
                    entity_type: "question",
                    entity_id: activeOptions.getAttribute("data-question-id"),
                };
            }

            const explicitId = btn.getAttribute("data-question-id");
            if (explicitId) {
                return { entity_type: "question", entity_id: explicitId };
            }

            return {
                entity_type: "exam",
                entity_id: window.quizConfig?.examId || 1,
            };
        },
    );

    const oldPopover = document.getElementById("popover-rate-quiz");
    if (oldPopover) oldPopover.remove();

    setupModal("intModalRate", "#btn-rate-quiz", "intFormRate", () => {
        return {
            entity_type: "exam",
            entity_id: window.quizConfig?.examId || 1,
        };
    });
});
/* Quiz engine */
document.addEventListener("DOMContentLoaded", function () {
    if (!window.quizConfig) return;

    let currentStreak = 0;

    const exitModal = document.getElementById("modal-exit-quiz");
    const switchModal = document.getElementById("modal-switch-quiz");
    const rateBtn = document.getElementById("btn-rate-quiz");
    const ratePopover = document.getElementById("popover-rate-quiz");

    document.querySelectorAll(".btn-exit-quiz").forEach(function (btn) {
        btn.addEventListener("click", function () {
            if (exitModal) exitModal.style.display = "flex";
        });
    });

    const cancelExitBtn = document.getElementById("btn-cancel-exit");
    if (cancelExitBtn && exitModal) {
        cancelExitBtn.addEventListener("click", function () {
            exitModal.style.display = "none";
        });
    }

    // Sidebar Accordion Logic
    const categoryBtns = document.querySelectorAll(".sidebar-category-btn");
    categoryBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            const sublist = btn.nextElementSibling;
            if (
                sublist &&
                sublist.classList.contains("sidebar-sublist-container")
            ) {
                const isClosed = sublist.style.display === "none";
                sublist.style.display = isClosed ? "block" : "none";

                if (isClosed) {
                    btn.classList.add("open");
                } else {
                    btn.classList.remove("open");
                }
            }
        });
    });

    // Open Switch Modal
    let targetSwitchUrl = "";
    const switchQuizBtns = document.querySelectorAll(".btn-switch-quiz");
    switchQuizBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            // Capture the URL they were trying to navigate to
            targetSwitchUrl =
                btn.getAttribute("href") ||
                (btn.closest("a") && btn.closest("a").getAttribute("href"));
            if (targetSwitchUrl && targetSwitchUrl !== "#") {
                if (switchModal) switchModal.style.display = "flex";
            }
        });
    });

    // Handle Switch Actions
    const btnSaveSwitch = document.getElementById("btn-save-switch");
    const btnAbandonSwitch = document.getElementById("btn-abandon-switch");

    if (btnSaveSwitch) {
        btnSaveSwitch.addEventListener("click", () => {
            if (targetSwitchUrl) window.location.href = targetSwitchUrl;
        });
    }

    if (btnAbandonSwitch) {
        btnAbandonSwitch.addEventListener("click", () => {
            if (targetSwitchUrl) window.location.href = targetSwitchUrl;
        });
    }

    // Close Switch Modal
    const cancelSwitchBtn = document.getElementById("btn-cancel-switch");
    if (cancelSwitchBtn) {
        cancelSwitchBtn.addEventListener("click", () => {
            switchModal.style.display = "none";
            targetSwitchUrl = ""; // Reset on close
        });
    }

    // Toggle Rate Popover
    if (rateBtn && ratePopover) {
        rateBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            ratePopover.style.display =
                ratePopover.style.display === "none" ? "flex" : "none";
        });
    }

    // Close Modals and Popovers on Backdrop Click
    window.addEventListener("click", (e) => {
        if (e.target === exitModal) exitModal.style.display = "none";
        if (e.target === switchModal) switchModal.style.display = "none";
        if (
            ratePopover &&
            ratePopover.style.display === "flex" &&
            !ratePopover.contains(e.target) &&
            e.target !== rateBtn
        ) {
            ratePopover.style.display = "none";
        }
    });

    // --- Navigation Logic (Sliding between all questions) ---
    window.goToSlide = function (index) {
        const total = window.quizConfig.totalQuestions;
        if (index < 0 || index >= total) {
            if (index >= total) {
                if (window.quizConfig.subdivisionUrl) {
                    window.location.href = window.quizConfig.subdivisionUrl;
                }
            }
            return;
        }

        // Hide all questions
        document.querySelectorAll(".question-slide").forEach((slide) => {
            slide.style.display = "none";
        });

        // Show target question
        const targetSlide = document.getElementById("slide-" + index);
        if (!targetSlide) {
            return;
        }

        targetSlide.style.display = "block";

        // Reset all navigation blocks to their standard un-scaled state
        document.querySelectorAll(".nav-btn").forEach((btn) => {
            let state = btn.getAttribute("data-state");
            let base =
                "nav-btn w-full aspect-square rounded-xl border-2 flex items-center justify-center text-xs font-bold transition-all duration-200 select-none cursor-pointer ";

            if (state === "correct") {
                btn.className =
                    base + "bg-emerald-500 border-emerald-500 text-white";
            } else if (state === "incorrect") {
                btn.className = base + "bg-red-500 border-red-500 text-white";
            } else {
                btn.className =
                    base +
                    "bg-white border-slate-300 text-slate-400 hover:border-[#06BBCC]/60 hover:text-[#06BBCC]";
            }
        });

        // Scale and highlight the active navigation block
        const activeNav = document.getElementById("nav-btn-" + index);
        if (activeNav) {
            let state = activeNav.getAttribute("data-state");
            if (state === "correct") {
                activeNav.classList.add(
                    "scale-105",
                    "shadow-md",
                    "shadow-emerald-500/40",
                );
            } else if (state === "incorrect") {
                activeNav.classList.add(
                    "scale-105",
                    "shadow-md",
                    "shadow-red-500/40",
                );
            } else {
                activeNav.className =
                    "nav-btn w-full aspect-square rounded-xl border-2 flex items-center justify-center text-xs font-bold transition-all duration-200 select-none cursor-pointer bg-[#06BBCC] border-[#06BBCC] text-white shadow-md shadow-[#06BBCC]/40 scale-105";
            }

            activeNav.scrollIntoView({ behavior: "smooth", block: "center" });
        }

        window.scrollTo({ top: 0, behavior: "smooth" });

        // Update mobile tracker
        let mobileTracker = document.getElementById("mobile-q-tracker");
        if (mobileTracker) {
            mobileTracker.innerText = `Q ${index + 1} / ${total}`;
        }

        syncSlideFooter(targetSlide);
    };

    function syncSlideFooter(slide) {
        if (!slide) return;
        var container = slide.querySelector(".options-container");
        if (!container) return;

        var isMulti = container.getAttribute("data-multi") === "true";
        var answered = slide.querySelector(".answer-option[disabled]");
        var rationale = slide.querySelector(".rationale-container");
        var checkBtn = slide.querySelector(".btn-check-answer");
        var nextBtn = slide.querySelector(".btn-next-question");
        var selectMsg = slide.querySelector(".select-to-continue");

        if (rationale) {
            rationale.classList.toggle("is-hidden", !answered);
            if (answered) {
                rationale.style.display = "block";
            } else {
                rationale.style.display = "none";
            }
        }

        if (answered) {
            if (checkBtn) {
                checkBtn.classList.add("is-hidden");
                checkBtn.classList.remove("is-visible");
                checkBtn.style.display = "none";
            }
            if (nextBtn) {
                nextBtn.classList.add("is-visible");
                nextBtn.classList.remove("is-hidden");
                nextBtn.style.display = "flex";
            }
            if (selectMsg) {
                selectMsg.classList.add("is-hidden");
                selectMsg.style.display = "none";
            }
            return;
        }

        if (isMulti) {
            var anySelected = container.querySelector(".answer-option.is-selected");
            if (checkBtn) {
                checkBtn.classList.toggle("is-visible", !!anySelected);
                checkBtn.classList.toggle("is-hidden", !anySelected);
                checkBtn.style.display = anySelected ? "flex" : "none";
            }
            if (nextBtn) {
                nextBtn.classList.add("is-hidden");
                nextBtn.classList.remove("is-visible");
                nextBtn.style.display = "none";
            }
        } else {
            if (checkBtn) {
                checkBtn.classList.add("is-hidden");
                checkBtn.classList.remove("is-visible");
                checkBtn.style.display = "none";
            }
            if (nextBtn) {
                nextBtn.classList.add("is-visible");
                nextBtn.classList.remove("is-hidden");
                nextBtn.style.display = "flex";
            }
        }

        if (selectMsg) {
            selectMsg.classList.add("is-hidden");
            selectMsg.style.display = "none";
        }
    }

    function syncMultiSelectFooter(container) {
        var index = container.getAttribute("data-index");
        var slide = document.getElementById("slide-" + index);
        syncSlideFooter(slide);
    }

    // --- Answer Selection & Real AJAX Logic ---
    const optionsContainers = document.querySelectorAll(".options-container");

    if (optionsContainers.length > 0) {
        optionsContainers.forEach((container) => {
            const answerOptions = container.querySelectorAll(".answer-option");
            const isMulti = container.getAttribute("data-multi") === "true";
            const index = parseInt(container.getAttribute("data-index"));
            const slide = document.getElementById("slide-" + index);
            const checkAnswerBtn = slide
                ? slide.querySelector(".btn-check-answer")
                : null;

            // Centralized submit logic
            const submitAnswer = (questionId, selectedValue) => {
                // 1. Visually disable all options immediately
                answerOptions.forEach((opt) => {
                    opt.disabled = true;
                    opt.classList.add("opacity-40", "cursor-default");
                    opt.classList.remove(
                        "hover:border-[#06BBCC]/60",
                        "hover:bg-[#06BBCC]/3",
                        "cursor-pointer",
                        "hover:shadow-sm"
                    );
                });

                if (checkAnswerBtn) checkAnswerBtn.style.display = "none";

                // 2. Fetch correct answer from backend
                fetch(window.quizConfig.submitUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": window.quizConfig.csrfToken,
                    },
                    body: JSON.stringify({
                        question_id: questionId,
                        selected_option: selectedValue,
                        exam_id: window.quizConfig.examId,
                        index: index,
                    }),
                })
                    .then((res) => {
                        
                        if (!res.ok) throw new Error("Network response error");
                        return res.json();
                    })
                    .then((data) => {
                        if (data.success) {
                            
                            if (data.stats) {
                                let answeredEl = document.getElementById("stat-answered");
                                if (answeredEl)
                                    answeredEl.innerHTML = `${data.stats.answered}<span class="text-xs text-slate-400 font-medium">/${window.quizConfig.totalQuestions}</span>`;

                                let correctEl = document.getElementById("stat-correct");
                                if (correctEl) correctEl.innerText = data.stats.correct;

                                let incorrectEl = document.getElementById("stat-incorrect");
                                if (incorrectEl) incorrectEl.innerText = data.stats.incorrect;

                                let remainEl = document.getElementById("stat-remaining");
                                if (remainEl)
                                    remainEl.innerText = `${window.quizConfig.totalQuestions - data.stats.answered} left`;

                                let fractionEl = document.getElementById("stat-fraction");
                                if (fractionEl)
                                    fractionEl.innerText = `${data.stats.correct}/${data.stats.answered} correct`;

                                // --- Circular Progress Dynamic Update ---
                                let accuracy = data.stats.accuracy || 0;
                                let circleColor = "#cbd5e1";
                                if (data.stats.answered > 0) {
                                    circleColor =
                                        accuracy >= 70
                                            ? "#10b981"
                                            : accuracy >= 40
                                            ? "#f59e0b"
                                            : "#ef4444";
                                }

                                let circumference = 276.46015351590177;
                                let dashOffset =
                                    circumference - (accuracy / 100) * circumference;

                                let statCircle = document.getElementById("stat-circle");
                                if (statCircle) {
                                    statCircle.setAttribute("stroke", circleColor);
                                    statCircle.setAttribute(
                                        "stroke-dashoffset",
                                        dashOffset
                                    );
                                }

                                let statPercentage = document.getElementById("stat-percentage");
                                if (statPercentage) {
                                    statPercentage.innerText = accuracy + "%";
                                    statPercentage.style.color = circleColor;
                                }
                            }

                            // Apply show.blade.php "Intelligence" mapping
                            let correctArray = String(data.correct_answer || "")
                                .split(",")
                                .map((s) => s.trim())
                                .filter(Boolean)
                                .sort();
                            let selectedArray = String(selectedValue || "")
                                .split(",")
                                .map((s) => s.trim())
                                .filter(Boolean)
                                .sort();

                            let exactCorrectStr = correctArray.join(", ");
                            let pickedAnswerStr = selectedArray.join(", ");

                            
                            let isFullyCorrect = false;
                            if (isMulti) {
                                isFullyCorrect =
                                    JSON.stringify(selectedArray) ===
                                    JSON.stringify(correctArray);
                            } else {
                                isFullyCorrect =
                                    JSON.stringify(selectedArray) ===
                                    JSON.stringify(correctArray);
                                if (!isFullyCorrect && data.is_correct) {
                                    isFullyCorrect = true;
                                }
                            }

                            // --- Fire Streak Engine ---
                            if (isFullyCorrect) {
                                currentStreak++;
                            } else {
                                currentStreak = 0;
                            }

                            const streakContainer =
                                document.getElementById("streak-container");
                            const streakCountEl =
                                document.getElementById("streak-count");

                            if (streakContainer && streakCountEl) {
                                if (currentStreak > 0) {
                                    streakCountEl.innerText = currentStreak;
                                    streakContainer.style.display = "flex";
                                } else {
                                    streakContainer.style.display = "none";
                                }
                            }

                            // Mark Nav bar Red
                            const navBtn = document.getElementById(
                                "nav-btn-" + index
                            );
                            if (navBtn) {
                                let newState = isFullyCorrect
                                    ? "correct"
                                    : "incorrect";
                                navBtn.setAttribute("data-state", newState);

                                let base =
                                    "nav-btn w-full aspect-square rounded-xl border-2 flex items-center justify-center text-xs font-bold transition-all duration-200 select-none cursor-pointer scale-105 shadow-md ";

                                if (isFullyCorrect) {
                                    navBtn.className =
                                        base +
                                        "bg-emerald-500 border-emerald-500 text-white shadow-emerald-500/40";
                                } else {
                                    navBtn.className =
                                        base +
                                        "bg-red-500 border-red-500 text-white shadow-red-500/40";
                                }
                            }

                            // Update mobile dots
                            const mobileDots = document.querySelectorAll(
                                "#mobile-progress-bar .mobile-dot"
                            );
                            if (mobileDots[index]) {
                                mobileDots[index].className =
                                    "flex-1 h-1.5 rounded-full transition-all mobile-dot " +
                                    (isFullyCorrect
                                        ? "bg-emerald-500"
                                        : "bg-red-500");
                            }

                            // 4. Highlight Correct
                            answerOptions.forEach((opt) => {
                                const val = opt.getAttribute("data-value");
                                let iconBox = opt.querySelector(".option-icon");

                                opt.classList.remove(
                                    "border-[#06BBCC]",
                                    "bg-[#06BBCC]/5",
                                    "is-selected"
                                );

                                if (correctArray.includes(val)) {
                                    opt.classList.remove(
                                        "opacity-40",
                                        "border-slate-200"
                                    );
                                    opt.classList.add(
                                        "border-emerald-400",
                                        "bg-emerald-50",
                                        "shadow-emerald-100",
                                        "shadow-md",
                                        "opacity-100"
                                    );

                                    iconBox.classList.remove(
                                        "bg-slate-100",
                                        "text-slate-500"
                                    );
                                    iconBox.classList.add(
                                        "bg-emerald-500",
                                        "text-white"
                                    );
                                    iconBox.innerHTML =
                                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check w-5 h-5"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>';
                                } else if (
                                    selectedArray.includes(val) &&
                                    !isFullyCorrect
                                ) {
                                    opt.classList.remove(
                                        "opacity-40",
                                        "border-slate-200"
                                    );
                                    opt.classList.add(
                                        "border-red-400",
                                        "bg-red-50",
                                        "shadow-red-100",
                                        "shadow-md",
                                        "opacity-100"
                                    );

                                    iconBox.classList.remove(
                                        "bg-slate-100",
                                        "text-slate-500"
                                    );
                                    iconBox.classList.add(
                                        "bg-red-500",
                                        "text-white"
                                    );
                                    iconBox.innerHTML =
                                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x w-5 h-5"><circle cx="12" cy="12" r="10"></circle><path d="m15 9-6 6"></path><path d="m9 9 6 6"></path></svg>';
                                }
                            });

                            // 5. Populate and Show "Intelligent" Rationale
                            const rationaleContainer = slide.querySelector(
                                ".rationale-container"
                            );
                            const rationaleBox =
                                rationaleContainer.querySelector(
                                    ".rationale-box"
                                );
                            const rHeader =
                                rationaleBox.querySelector(".r-header");

                            rHeader.className =
                                "flex flex-wrap gap-3 mb-3 r-header";

                            if (isFullyCorrect) {
                                rationaleBox.classList.remove(
                                    "border-red-200",
                                    "bg-red-50/40"
                                );
                                rationaleBox.classList.add(
                                    "border-emerald-200",
                                    "bg-emerald-50/40"
                                );
                                rHeader.innerHTML = `
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-semibold bg-emerald-100 text-emerald-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                                        Your Answer: Option(s) ${pickedAnswerStr}
                                    </div>
                                `;
                            } else {
                                rationaleBox.classList.remove(
                                    "border-emerald-200",
                                    "bg-emerald-50/40"
                                );
                                rationaleBox.classList.add(
                                    "border-red-200",
                                    "bg-red-50/40"
                                );
                                rHeader.innerHTML = `
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-semibold bg-red-100 text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x"><circle cx="12" cy="12" r="10"></circle><path d="m15 9-6 6"></path><path d="m9 9 6 6"></path></svg>
                                        Your Answer: Option(s) ${pickedAnswerStr}
                                    </div>
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-semibold bg-emerald-100 text-emerald-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                                        Correct Answer: Option(s) ${exactCorrectStr}
                                    </div>
                                `;
                            }

                            rationaleContainer.style.display = "block";
                            rationaleContainer.classList.remove("is-hidden");

                            // 6. Toggle Footer Buttons Globally
                            document
                                .querySelectorAll(".select-to-continue")
                                .forEach(function (msgEl) {
                                    msgEl.style.display = "none";
                                    msgEl.classList.add("is-hidden");
                                });

                            document
                                .querySelectorAll(".options-container")
                                .forEach(function (cont) {
                                    if (
                                        cont.getAttribute("data-multi") !==
                                        "true"
                                    ) {
                                        var idx =
                                            cont.getAttribute("data-index");
                                        var s = document.getElementById(
                                            "slide-" + idx
                                        );
                                        syncSlideFooter(s);
                                    }
                                });

                            syncSlideFooter(slide);
                        }
                    })
                    .catch((error) => {
                        
                        console.error("Failed to submit answer:", error);
                        
                        answerOptions.forEach((opt) => {
                            opt.disabled = false;
                            opt.classList.remove("opacity-40", "cursor-default");
                            opt.classList.add(
                                "hover:border-[#06BBCC]/60",
                                "hover:bg-[#06BBCC]/3",
                                "cursor-pointer",
                                "hover:shadow-sm"
                            );
                        });
                        
                        if (checkAnswerBtn) checkAnswerBtn.style.display = "flex";
                    });
            };

            // Bind click to options
            answerOptions.forEach((option) => {
                option.addEventListener("click", function () {
                    if (this.disabled) return;

                    const questionId =
                        container.getAttribute("data-question-id");

                    if (isMulti) {
                        this.classList.toggle("is-selected");
                        if (this.classList.contains("is-selected")) {
                            this.classList.add(
                                "border-[#06BBCC]",
                                "bg-[#06BBCC]/5",
                            );
                            this.classList.remove("border-slate-200");
                        } else {
                            this.classList.remove(
                                "border-[#06BBCC]",
                                "bg-[#06BBCC]/5",
                            );
                            this.classList.add("border-slate-200");
                        }
                        syncMultiSelectFooter(container);
                    } else {
                        // Single select submits immediately
                        const selectedValue = this.getAttribute("data-value");
                        submitAnswer(questionId, selectedValue);
                    }
                });
            });

            // Bind Check Answer button
            if (isMulti && checkAnswerBtn) {
                checkAnswerBtn.addEventListener("click", function () {
                    const questionId =
                        container.getAttribute("data-question-id");

                    let selectedValues = [];
                    answerOptions.forEach((opt) => {
                        if (opt.classList.contains("is-selected")) {
                            selectedValues.push(opt.getAttribute("data-value"));
                        }
                    });

                    if (selectedValues.length === 0) {
                        alert("Please select at least one answer.");
                        return;
                    }

                    selectedValues.sort();
                    submitAnswer(questionId, selectedValues.join(", "));
                });
            }
        });

        document.querySelectorAll(".question-slide").forEach(function (slide) {
            syncSlideFooter(slide);
        });

        var startIndex = 0;
        for (var i = 0; i < window.quizConfig.totalQuestions; i++) {
            var startSlide = document.getElementById("slide-" + i);
            if (!startSlide) continue;
            var firstOpt = startSlide.querySelector(".answer-option");
            if (firstOpt && !firstOpt.disabled) {
                startIndex = i;
                break;
            }
        }
        goToSlide(startIndex);
    }
});
