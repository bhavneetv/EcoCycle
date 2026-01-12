const sidebarx = document.getElementById("sidebar");
const sidebarTogglex = document.getElementById("sidebarToggle");
//const sidebarOverlay = document.getElementById('sidebarOverlay');
const profileToggle = document.getElementById("profileToggle");
const profileDropdown = document.getElementById("profileDropdown");
const themeToggle = document.getElementById("themeToggle");
const startScanBtnDesh = document.getElementById("startScanBtnDesh");
const scanModal = document.getElementById("scanModal");
const closeScanModal = document.getElementById("closeScanModal");
const navbar = document.getElementById("navbar");

// document.getElementById("profileToggle").addEventListener("click", (e) => {
//   alert("Profile clicked");
//   // e.stopPropagation();
//   // document.getElementById("profileDropdown").classList.toggle("hidden");
// });


// Sidebar Toggle
sidebarTogglex.addEventListener("click", () => {
  sidebarx.classList.toggle("-translate-x-full");

// //   Profile Dropdown
//   profileToggle.addEventListener('click', (e) => {
//       e.stopPropagation();
//       profileDropdown.classList.toggle('hidden');
//   });

  // // Close dropdown when clicking outside
  // d



  // Navbar Scroll Effect
  let lastScrollTop = 0;
  window.addEventListener("scroll", () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > lastScrollTop && scrollTop > 100) {
      // Scrolling down
      navbar.style.transform = "translateY(-100%)";
    } else {
      // Scrolling up
      navbar.style.transform = "translateY(0)";
    }

    // Add shadow when scrolled
    if (scrollTop > 50) {
      navbar.classList.add("shadow-lg");
    } else {
      navbar.classList.remove("shadow-lg");
    }

    lastScrollTop = scrollTop;
  });

 

  // Mobile-specific optimizations
  if ("ontouchstart" in window) {
    // Add touch-specific styles
    document.body.classList.add("touch-device");

    // Prevent zoom on double tap
    let lastTouchEnd = 0;
    document.addEventListener(
      "touchend",
      function (event) {
        const now = new Date().getTime();
        if (now - lastTouchEnd <= 300) {
          event.preventDefault();
        }
        lastTouchEnd = now;
      },
      false
    );
  }
});
