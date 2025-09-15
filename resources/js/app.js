// Register service worker for PWA
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js').catch(() => {
      // Silent failure; SW is optional
    });
  });
}

// Minimal install banner using beforeinstallprompt
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault();
  deferredPrompt = e;
  showInstallBanner();
});

window.addEventListener('appinstalled', () => {
  hideInstallBanner();
  deferredPrompt = undefined;
});

function showInstallBanner() {
  if (document.getElementById('pwa-install-banner')) return;
  const banner = document.createElement('div');
  banner.id = 'pwa-install-banner';
  banner.style.position = 'fixed';
  banner.style.left = '16px';
  banner.style.right = '16px';
  banner.style.bottom = '16px';
  banner.style.zIndex = '2147483647';
  banner.style.background = '#0f172a';
  banner.style.border = '1px solid #1f2a44';
  banner.style.color = '#e2e8f0';
  banner.style.borderRadius = '12px';
  banner.style.padding = '12px 14px';
  banner.style.display = 'flex';
  banner.style.alignItems = 'center';
  banner.style.gap = '12px';
  banner.style.boxShadow = '0 8px 24px rgba(2,6,23,0.45)';
  banner.innerHTML = `
    <img src="/favicon.svg" alt="" style="width:24px;height:24px" />
    <div style="flex:1">
      <div style="font-weight:700; font-size:14px">Install Stampit</div>
      <div style="opacity:.8; font-size:12px">Get a faster, app-like experience.</div>
    </div>
    <button id="pwa-install-btn" style="background:#0ea5e9;color:#0b1220;border:0;padding:8px 12px;border-radius:10px;font-weight:700;cursor:pointer">Install</button>
    <button id="pwa-install-close" aria-label="Close" style="background:transparent;color:#94a3b8;border:0;padding:6px 8px;border-radius:8px;cursor:pointer">Dismiss</button>
  `;
  document.body.appendChild(banner);

  document.getElementById('pwa-install-btn')?.addEventListener('click', async () => {
    if (!deferredPrompt) return;
    banner.style.opacity = '0.6';
    banner.style.pointerEvents = 'none';
    deferredPrompt.prompt();
    const choice = await deferredPrompt.userChoice;
    if (choice.outcome === 'accepted') {
      hideInstallBanner();
    } else {
      banner.style.opacity = '';
      banner.style.pointerEvents = '';
    }
    deferredPrompt = undefined;
  });
  document.getElementById('pwa-install-close')?.addEventListener('click', hideInstallBanner);
}

function hideInstallBanner() {
  const el = document.getElementById('pwa-install-banner');
  if (el && el.parentNode) {
    el.parentNode.removeChild(el);
  }
}


