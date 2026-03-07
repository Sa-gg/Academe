/**
 * Academe — Client-Side Interactivity
 * ────────────────────────────────────
 * Modules:
 *   1. Mobile sidebar toggle
 *   2. Live enrollment preview
 *   3. Form double-submit protection
 *   4. Stat count-up animation
 *   5. Clickable table rows
 *   6. Page exit transition
 *   7. Delete confirmation handler
 */

document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    /* ═══════════════════════════════════════════════════
       1. MOBILE SIDEBAR TOGGLE
       ═══════════════════════════════════════════════════ */
    (function () {
        var sidebar = document.querySelector('.sidebar');
        if (!sidebar) return;

        // Create toggle button for mobile
        var toggle = document.createElement('button');
        toggle.className = 'btn-icon sidebar-toggle';
        toggle.setAttribute('aria-label', 'Toggle navigation');
        toggle.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>';
        toggle.style.cssText = 'display:none;position:fixed;top:12px;left:12px;z-index:110;background:var(--bg-card);border:1px solid var(--border-default);box-shadow:var(--shadow-sm);';

        document.body.appendChild(toggle);

        // Create overlay
        var overlay = document.createElement('div');
        overlay.style.cssText = 'display:none;position:fixed;inset:0;background:rgba(0,0,0,0.3);z-index:99;opacity:0;transition:opacity 0.3s ease;';
        document.body.appendChild(overlay);

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.style.display = 'block';
            requestAnimationFrame(function () { overlay.style.opacity = '1'; });
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.style.opacity = '0';
            setTimeout(function () { overlay.style.display = 'none'; }, 300);
        }

        toggle.addEventListener('click', function () {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });
        overlay.addEventListener('click', closeSidebar);

        // Show/hide toggle based on viewport
        var mql = window.matchMedia('(max-width: 768px)');
        function handleViewport(e) {
            toggle.style.display = e.matches ? 'flex' : 'none';
            if (!e.matches) closeSidebar();
        }
        mql.addEventListener('change', handleViewport);
        handleViewport(mql);
    })();

    /* ═══════════════════════════════════════════════════
       2. LIVE ENROLLMENT PREVIEW
       ═══════════════════════════════════════════════════ */
    (function () {
        var form = document.getElementById('enrollmentForm');
        if (!form) return;

        var studentSelect = document.getElementById('student_id');
        var courseSelect = document.getElementById('course_id');
        var previewStudentAvatar = document.getElementById('previewStudentAvatar');
        var previewStudentName = document.getElementById('previewStudentName');
        var previewStudentEmail = document.getElementById('previewStudentEmail');
        var previewCourseAvatar = document.getElementById('previewCourseAvatar');
        var previewCourseName = document.getElementById('previewCourseName');
        var previewCourseDesc = document.getElementById('previewCourseDesc');
        var previewStatus = document.getElementById('previewStatus');

        if (!studentSelect || !courseSelect) return;

        function updatePreview() {
            var sOpt = studentSelect.options[studentSelect.selectedIndex];
            var cOpt = courseSelect.options[courseSelect.selectedIndex];
            var sName = sOpt ? sOpt.getAttribute('data-name') : '';
            var sEmail = sOpt ? sOpt.getAttribute('data-email') : '';
            var cTitle = cOpt ? cOpt.getAttribute('data-title') : '';
            var cDesc = cOpt ? cOpt.getAttribute('data-desc') : '';

            // Student preview
            if (previewStudentName) previewStudentName.textContent = sName || 'Not selected';
            if (previewStudentEmail) previewStudentEmail.textContent = sEmail || '\u2014';
            if (previewStudentAvatar) previewStudentAvatar.textContent = sName ? sName.charAt(0).toUpperCase() : '?';

            // Course preview
            if (previewCourseName) previewCourseName.textContent = cTitle || 'Not selected';
            if (previewCourseDesc) previewCourseDesc.textContent = cDesc || '\u2014';
            if (previewCourseAvatar) previewCourseAvatar.textContent = cTitle ? cTitle.charAt(0).toUpperCase() : '?';

            // Status badge
            if (previewStatus) {
                var ready = studentSelect.value && courseSelect.value;
                previewStatus.textContent = ready ? 'Ready' : 'Incomplete';
                previewStatus.className = 'badge ' + (ready ? 'badge-success' : 'badge-info');
            }
        }

        studentSelect.addEventListener('change', updatePreview);
        courseSelect.addEventListener('change', updatePreview);

        // Initialize on load (for pre-selected values)
        updatePreview();
    })();

    /* ═══════════════════════════════════════════════════
       3. FORM DOUBLE-SUBMIT PROTECTION
       ═══════════════════════════════════════════════════ */
    (function () {
        document.querySelectorAll('form[data-protect-submit]').forEach(function (form) {
            form.addEventListener('submit', function () {
                var btns = form.querySelectorAll('button[type="submit"]');
                btns.forEach(function (btn) {
                    btn.disabled = true;
                    btn.style.opacity = '0.6';
                    btn.style.pointerEvents = 'none';
                });
            });
        });
    })();

    /* ═══════════════════════════════════════════════════
       4. STAT COUNT-UP ANIMATION
       ═══════════════════════════════════════════════════ */
    (function () {
        var statEls = document.querySelectorAll('[data-count]');
        if (!statEls.length) return;

        function animateValue(el) {
            var target = parseInt(el.getAttribute('data-count'), 10);
            if (isNaN(target) || target === 0) {
                el.textContent = target || '0';
                return;
            }
            var duration = 800;
            var start = performance.now();
            function tick(now) {
                var elapsed = now - start;
                var progress = Math.min(elapsed / duration, 1);
                // Ease-out cubic
                var eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(eased * target);
                if (progress < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        }

        // Use IntersectionObserver for viewport entry
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        animateValue(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.3 });
            statEls.forEach(function (el) { observer.observe(el); });
        } else {
            statEls.forEach(animateValue);
        }
    })();

    /* ═══════════════════════════════════════════════════
       5. CLICKABLE TABLE ROWS
       ═══════════════════════════════════════════════════ */
    (function () {
        document.querySelectorAll('tr[data-href]').forEach(function (row) {
            row.style.cursor = 'pointer';
            row.addEventListener('click', function (e) {
                // Don't trigger on link/button clicks within the row
                if (e.target.closest('a, button')) return;
                var href = row.getAttribute('data-href');
                if (href) window.location.href = href;
            });
        });
    })();

    /* ═══════════════════════════════════════════════════
       6. PAGE EXIT TRANSITION
       ═══════════════════════════════════════════════════ */
    (function () {
        var content = document.querySelector('.main-content');
        if (!content) return;

        document.addEventListener('click', function (e) {
            var link = e.target.closest('a[href]');
            if (!link) return;
            var href = link.getAttribute('href');
            // Only handle internal links
            if (!href || href.startsWith('#') || href.startsWith('javascript') || link.target === '_blank') return;
            // Skip if modifier keys held
            if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
            // Check same origin
            try {
                var url = new URL(href, window.location.origin);
                if (url.origin !== window.location.origin) return;
            } catch (_) { return; }

            e.preventDefault();
            content.style.transition = 'opacity 0.15s ease, transform 0.15s ease';
            content.style.opacity = '0';
            content.style.transform = 'translateY(6px)';
            setTimeout(function () { window.location.href = href; }, 150);
        });
    })();

    /* ═══════════════════════════════════════════════════
       7. DELETE CONFIRMATION HANDLER
       ═══════════════════════════════════════════════════ */
    (function () {
        document.querySelectorAll('[data-confirm]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                if (window.confirm(btn.getAttribute('data-confirm'))) {
                    var formId = btn.getAttribute('data-form');
                    if (formId) {
                        document.getElementById(formId).submit();
                    }
                }
            });
        });
    })();
});
