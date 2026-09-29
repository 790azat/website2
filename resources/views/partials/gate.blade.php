{{--
    Entry gate (slide-to-verify captcha) shown over the site until the visitor
    completes it. It shows on every page load; nothing is remembered. Legal pages stay open so the gate's own links work.
    After passing, the visitor is sent to one of the main guides (random).
--}}
<script>window.__gateGuides = @json(\App\Support\SiteContent::programs()->pluck('slug')->values());</script>
@verbatim
<style>
    html.gate-on { background: #f7f5ee; }
    html.gate-on body { visibility: hidden; overflow: hidden; }
    #ef-gate {
        visibility: visible; position: fixed; inset: 0; z-index: 2147483000;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 24px 16px; overflow-y: auto; color: #11402c;
        background:
            radial-gradient(40rem 22rem at 0% 0%, rgba(212, 243, 138, .55), transparent 60%),
            radial-gradient(40rem 26rem at 100% 100%, rgba(124, 207, 160, .35), transparent 60%),
            #f7f5ee;
        font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        transition: opacity .45s ease;
    }
    #ef-gate.is-leaving { opacity: 0; }
    #ef-gate * { box-sizing: border-box; }
    .ef-card {
        width: 100%; max-width: 420px; padding: 34px 26px 28px; border-radius: 24px; text-align: center;
        background: #fff; border: 1px solid #e2e0d4; box-shadow: 0 24px 60px -24px rgba(17, 64, 44, .35);
        animation: ef-rise .45s cubic-bezier(.2, .8, .2, 1) both;
    }
    @keyframes ef-rise { from { opacity: 0; transform: translateY(12px); } }
    .ef-shield { margin: 0 auto 16px; width: 64px; height: 64px; border-radius: 20px; display: grid; place-items: center; background: #edf3ea; color: #187a4b; }
    .ef-title { margin: 0; font-size: 24px; line-height: 1.2; font-weight: 800; letter-spacing: -.02em; color: #072418; }
    .ef-sub { margin: 8px 0 24px; font-size: 15px; line-height: 1.5; color: #4b5b52; }
    .ef-track {
        position: relative; height: 60px; border-radius: 18px; background: #edf3ea; border: 1px solid #d6f2df;
        overflow: hidden; user-select: none; -webkit-user-select: none; touch-action: none;
    }
    .ef-fill { position: absolute; inset: 0 auto 0 0; width: 60px; background: linear-gradient(90deg, #26975f, #47b37a); border-radius: 17px; }
    .ef-label {
        position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; padding-left: 52px;
        font-size: 14px; font-weight: 700; color: #187a4b; letter-spacing: .02em; pointer-events: none;
        background: linear-gradient(90deg, #187a4b 0%, #187a4b 40%, #a3d136 50%, #187a4b 60%, #187a4b 100%);
        background-size: 200% 100%; -webkit-background-clip: text; background-clip: text; color: transparent;
        animation: ef-glint 2.4s linear infinite;
    }
    @keyframes ef-glint { from { background-position: 100% 0; } to { background-position: -100% 0; } }
    .ef-knob {
        position: absolute; top: 4px; left: 4px; width: 52px; height: 52px; border: 0; border-radius: 14px; cursor: grab;
        display: grid; place-items: center; color: #072418; background: #d4f38a;
        box-shadow: 0 6px 16px -4px rgba(20, 98, 62, .55), inset 0 -3px 0 rgba(20, 98, 62, .18);
    }
    .ef-knob:active { cursor: grabbing; }
    .ef-knob:focus-visible { outline: 3px solid #14623e; outline-offset: 2px; }
    .ef-track.is-done .ef-knob { background: #fff; color: #187a4b; }
    .ef-track.is-done .ef-label { animation: none; color: #fff; -webkit-text-fill-color: #fff; background: none; padding-left: 0; }
    .ef-track.is-back .ef-knob, .ef-track.is-back .ef-fill { transition: left .3s ease, width .3s ease; }
    .ef-hint { margin: 12px 0 0; font-size: 12px; color: #7a867f; }
    .ef-state { display: inline-flex; align-items: center; gap: 8px; margin: 0 0 14px; padding: 6px 12px; border-radius: 999px; background: #eefaf2; color: #14623e; font-size: 13px; font-weight: 700; }
    .ef-state b { width: 8px; height: 8px; border-radius: 50%; background: #26975f; box-shadow: 0 0 0 0 rgba(38, 151, 95, .6); animation: ef-ping 1.2s ease-out infinite; }
    @keyframes ef-ping { to { box-shadow: 0 0 0 10px rgba(38, 151, 95, 0); } }
    .ef-bar { margin-top: 22px; height: 8px; border-radius: 99px; background: #edf3ea; overflow: hidden; }
    .ef-bar i { display: block; height: 100%; width: 0; border-radius: 99px; background: linear-gradient(90deg, #26975f, #a3d136); animation: ef-load 1.5s ease-in-out forwards; }
    @keyframes ef-load { to { width: 100%; } }
    .ef-wait { margin-top: 12px; font-size: 12px; letter-spacing: .16em; text-transform: uppercase; color: #7a867f; }
    .ef-foot { max-width: 420px; margin-top: 20px; font-size: 12px; line-height: 1.55; text-align: center; color: #6b7a71; }
    .ef-foot a { color: #14623e; text-decoration: underline; text-underline-offset: 2px; }
    @media (prefers-reduced-motion: reduce) { #ef-gate * { animation: none !important; } .ef-bar i { width: 100%; } }
</style>
<script>
(function () {
    var KEY = 'ef_gate_pass', root = document.documentElement;
    // No lasting pass: the gate shows on every page load. Passing it lets only
    // the main guide it opens next through, once.
    var passed = false;
    try { passed = sessionStorage.getItem(KEY) === '1'; sessionStorage.removeItem(KEY); } catch (e) {}
    if (passed || /\/(terms-of-use|privacy-policy)(\.html)?$/.test(location.pathname)) return;
    root.classList.add('gate-on');

    var T = {
        en: { title: 'Quick security check', sub: "Confirm you're a human to open the site.", slide: 'Slide to verify', done: 'Verified', hint: 'Drag the green button all the way to the right', ok: 'Access granted', live: 'Active session', prep: 'Preparing access', fin: 'Finishing up in a moment.', wait: 'Please wait', foot: 'The following content is informational and educational and does not constitute financial, legal or professional advice.', agree: 'By continuing, you agree to our {t} and our {p}.', t: 'Terms of Use', p: 'Privacy Policy' },
        es: { title: 'Verificación rápida', sub: 'Confirma que eres una persona para abrir el sitio.', slide: 'Desliza para verificar', done: 'Verificado', hint: 'Arrastra el botón verde hasta la derecha', ok: 'Acceso concedido', live: 'Sesión activa', prep: 'Preparando el acceso', fin: 'Terminamos en un momento.', wait: 'Espera por favor', foot: 'El siguiente contenido es informativo y educativo y no constituye asesoramiento financiero, legal ni profesional.', agree: 'Al continuar, aceptas nuestros {t} y nuestra {p}.', t: 'Términos de uso', p: 'Política de privacidad' },
        fr: { title: 'Vérification rapide', sub: 'Confirmez que vous êtes humain pour ouvrir le site.', slide: 'Glissez pour vérifier', done: 'Vérifié', hint: "Faites glisser le bouton vert jusqu'à droite", ok: 'Accès accordé', live: 'Session active', prep: "Préparation de l'accès", fin: 'Encore un instant.', wait: 'Veuillez patienter', foot: "Le contenu suivant est informatif et éducatif et ne constitue pas un conseil financier, juridique ou professionnel.", agree: 'En continuant, vous acceptez nos {t} et notre {p}.', t: "Conditions d'utilisation", p: 'Politique de confidentialité' }
    };
    var lang = (root.lang || 'en').slice(0, 2), t = T[lang] || T.en, pre = T[lang] && lang !== 'en' ? '/' + lang : '';

    var TITLES = { en: 'Security check', es: 'Verificación de seguridad', fr: 'Vérification de sécurité' };
    var pageTitle = '', blankIcon = document.createElement('link');
    blankIcon.rel = 'icon';
    blankIcon.href = 'data:,';

    function build() {
        // Keep the site's name and icon out of the browser tab while the gate is up.
        pageTitle = document.title;
        document.title = TITLES[lang] || TITLES.en;
        document.head.appendChild(blankIcon);
        var g = document.createElement('div');
        g.id = 'ef-gate';
        g.setAttribute('role', 'dialog');
        g.setAttribute('aria-modal', 'true');
        g.setAttribute('aria-labelledby', 'ef-q');
        var agree = t.agree.replace('{t}', '<a href="' + pre + '/terms-of-use">' + t.t + '</a>').replace('{p}', '<a href="' + pre + '/privacy-policy">' + t.p + '</a>');
        g.innerHTML =
            '<div class="ef-card">' +
                '<div class="ef-shield"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/></svg></div>' +
                '<h2 class="ef-title" id="ef-q">' + t.title + '</h2>' +
                '<p class="ef-sub">' + t.sub + '</p>' +
                '<div class="ef-track"><div class="ef-fill"></div><div class="ef-label">' + t.slide + '</div>' +
                    '<button type="button" class="ef-knob" role="slider" aria-label="' + t.slide + '" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">' +
                        '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6l6 6-6 6M13 6l6 6-6 6"/></svg>' +
                    '</button>' +
                '</div>' +
                '<p class="ef-hint">' + t.hint + '</p>' +
            '</div>' +
            '<p class="ef-foot">' + t.foot + '<br>' + agree + '</p>';
        document.body.appendChild(g);

        var card = g.querySelector('.ef-card'), track = g.querySelector('.ef-track'), knob = g.querySelector('.ef-knob'),
            fill = g.querySelector('.ef-fill'), label = g.querySelector('.ef-label');
        var startX = 0, pos = 0, dragging = false, finished = false;
        function max() { return track.clientWidth - knob.offsetWidth - 8; }
        function set(x) {
            pos = Math.max(0, Math.min(max(), x));
            knob.style.left = (4 + pos) + 'px';
            fill.style.width = (pos + 60) + 'px';
            knob.setAttribute('aria-valuenow', String(Math.round(pos / max() * 100)));
        }
        function release() {
            if (finished) return;
            if (pos >= max() - 2) return complete();
            track.classList.add('is-back');
            set(0);
            setTimeout(function () { track.classList.remove('is-back'); }, 320);
        }
        knob.addEventListener('pointerdown', function (e) {
            if (finished) return;
            dragging = true;
            startX = e.clientX - pos;
            knob.setPointerCapture(e.pointerId);
        });
        knob.addEventListener('pointermove', function (e) { if (dragging && !finished) set(e.clientX - startX); });
        knob.addEventListener('pointerup', function () { dragging = false; release(); });
        knob.addEventListener('pointercancel', function () { dragging = false; release(); });
        knob.addEventListener('keydown', function (e) {
            if (finished) return;
            if (e.key === 'ArrowRight') { set(pos + max() / 5); e.preventDefault(); }
            else if (e.key === 'ArrowLeft') { set(pos - max() / 5); e.preventDefault(); }
            else if (e.key === 'End') { set(max()); e.preventDefault(); }
            if (pos >= max() - 2) complete();
        });

        function complete() {
            finished = true;
            set(max());
            fill.style.width = '100%';
            track.classList.add('is-done');
            label.textContent = t.done;
            knob.innerHTML = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>';
            setTimeout(function () {
                card.innerHTML =
                    '<div><span class="ef-state"><b></b>' + t.ok + ' · ' + t.live + '</span></div>' +
                    '<h2 class="ef-title" role="status">' + t.prep + '</h2>' +
                    '<p class="ef-sub" style="margin-bottom:0">' + t.fin + '</p>' +
                    '<div class="ef-bar"><i></i></div>' +
                    '<div class="ef-wait">' + t.wait + '</div>';
            }, 550);
            setTimeout(function () {
                var guides = window.__gateGuides || [];
                if (guides.length && !/\/programs\//.test(location.pathname)) {
                    try { sessionStorage.setItem(KEY, '1'); } catch (e) {}
                    location.replace(pre + '/programs/' + guides[Math.floor(Math.random() * guides.length)]);
                    return;
                }
                document.title = pageTitle;
                blankIcon.remove();
                g.classList.add('is-leaving');
                root.classList.remove('gate-on');
                setTimeout(function () { g.remove(); }, 460);
            }, 2150);
        }
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', build);
    else build();
})();
</script>
@endverbatim
