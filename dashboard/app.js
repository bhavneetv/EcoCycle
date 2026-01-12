document.addEventListener("DOMContentLoaded", () => {
  const mainContent = document.getElementById("main-content");
  const links = document.querySelectorAll("a[data-page]");
  const qrScript = "https://unpkg.com/html5-qrcode";
  const pageTitleEl = document.getElementById("pageTitle");

  function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(";").shift();
    return null;
  }

  const userRole = getCookie("userRole") || "user";
//   console.log(userRole);

  const params = new URLSearchParams(window.location.search);
  let currentPage =
    params.get("page") ||
    (userRole === "recycler" ? "recyclerDashboard" : "dashboard");

  // Format title (split camelCase)
  function formatPageTitle(page) {
    if (/[A-Z]/.test(page)) {
      let text = page.replace(/([a-z])([A-Z])/g, "$1 $2").toLowerCase();
      if (text === "recycler dashboard") {
        return "Dashboard";
      } else {
        return text.charAt(0).toUpperCase() + text.slice(1);
      }
    }
    return page.charAt(0).toUpperCase() + page.slice(1);
  }

  // Reload JS file
  function reloadScript(src, callback) {
    const old = document.querySelector(`script[src^="${src}"]`);
    if (old) old.remove();

    const script = document.createElement("script");
    script.src = src + "?v=" + Date.now();
    script.defer = true;
    script.onload = () => callback && callback();
    document.body.appendChild(script);
  }

  // Load page HTML + script
  function loadPage(page) {
    let filePage = page;

    if (page === "recyclerDashboard") {
      filePage = "recyclerDashboard";
    } else if (page === "dashboard" && userRole === "recycler") {
      filePage = "recyclerDashboard";
      page = "recyclerDashboard";
      history.replaceState({ page }, "", "?page=" + page);
    } else if (page === "dashboard" && userRole === "user") {
      filePage = "dashboard";
      page = "dashboard";
      history.replaceState({ page }, "", "?page=" + page);
    }

    fetch(`pages/${filePage}.php`)
      .then((res) => {
        if (res.status === 401) {
          console.log("Unauthorized");
          window.location.href = "../login.php"; // 🚀 force full redirect
          throw new Error("Unauthorized");
        }
        return res.text();
      })
      .catch((err) => {
        console.error(err);
        mainContent.innerHTML = `<h2 class="text-red-500">Page not found</h2>`;
      })
      .then((html) => {
        if (page === "support") {
          window.location.href = "../contactus.php";
          return;
        }

        mainContent.innerHTML = html;

        // Load page-specific JS
        reloadScript(`../assets/js/${filePage}.js`, () => {
          if (page === "scan" || page === "recyclerScan") {
            initQrPage();
          }
        });

        if (pageTitleEl) {
          pageTitleEl.textContent = formatPageTitle(page);
        }
        document.title = "EcoCycle | " + formatPageTitle(page);

        // ✅ Highlight active link
        links.forEach((l) => {
          l.classList.remove("bg-white/10");
          if (l.getAttribute("data-page") === page) {
            l.classList.add("bg-white/10");
          }
        });
      })
      .catch(() => {
        mainContent.innerHTML = `<h2 class="text-red-500">Page not found</h2>`;
      });
  }

  // QR scanner init
  function initQrPage() {
    if (typeof Html5Qrcode !== "undefined") {
      if (window.html5QrcodeScanner?.clear) {
        window.html5QrcodeScanner.clear().catch(() => {});
      }
      if (typeof initQrScanner === "function") initQrScanner();
    } else {
      reloadScript(qrScript, initQrPage);
    }
  }

  // Sidebar links
  links.forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();

      links.forEach((l) => l.classList.remove("bg-white/10"));
      link.classList.add("bg-white/10");

      const sidebar = document.querySelector("#sidebar");
      if (sidebar) sidebar.classList.toggle("-translate-x-full");

      const page = link.getAttribute("data-page");
      history.pushState({ page }, "", `?page=${page}`);
      loadPage(page);
    });
  });

  // Browser navigation
  window.addEventListener("popstate", () => {
    const params = new URLSearchParams(window.location.search);
    const page =
      params.get("page") ||
      (userRole === "recycler" ? "recyclerDashboard" : "dashboard");
    loadPage(page);
  });

  // Initial load
  loadPage(currentPage);
});
