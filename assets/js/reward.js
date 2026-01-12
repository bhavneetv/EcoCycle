// to fetch the achievement data from the server and display it on the page with api

async function fetchRewardData() {
  const response = await fetch("../api/achivment.php");
  const data = await response.json();
  if (data.status != "success") {
    return;
  } else {
    return data;
  }
}

fetchRewardData().then((data) => {
  document.getElementById("plasticSaver").textContent =
    data.achievements.bottles_100;
  document.getElementById("ecoHero").textContent =
    data.achievements.kg_co2_saved;
  document.getElementById("topRecycler").textContent =
    data.achievements.bottles_500;
  document.getElementById("weeklyChampion").textContent =
    data.achievements.kg_co2_saved;
  document.getElementById("streakMaster").textContent =
    data.achievements.streak_30_days;
});

function checkPoints() {
  if (document.getElementById("availablePoints").innerText <= 80) {
    document.getElementById("redeemBtn").classList.add("opacity-50");
    document.getElementById("redeemBtn").classList.add("cursor-not-allowed");
    document.getElementById("redeemBtn").disabled = true;
    document.getElementById("redeemText").innerText = "Not enough points";
  } else {
    document.getElementById("redeemBtn").classList.remove("opacity-50");
    document.getElementById("redeemBtn").classList.remove("cursor-not-allowed");
    document.getElementById("redeemBtn").disabled = false;
    document.getElementById("redeemText").innerText = "Ready to redeem";
  }
}
checkPoints();

// to redeem the points
document.getElementById("redeemBtn").addEventListener("click", () => {
  document.getElementById("btnLoaderR").classList.remove("hidden");
  if (document.getElementById("availablePoints").innerText <= 80) {
    document.getElementById("btnLoaderR").classList.add("hidden");
    alert("Not enough points");
    return;
  }
  if (confirm("Are you sure you want to redeem these points?")) {
    redeemPoints();
  }
});

function redeemPoints() {
  $.ajax({
    url: "../api/redeem.php",
    type: "POST",
    data: {},
    success: function (data) {
      console.log(data);
      let dataa = JSON.parse(data);

      if (dataa.status == "success") {
        qrModal(dataa.unique_code);
        alert("Points redeemed successfully");
        document.getElementById("btnLoaderR").classList.add("hidden");
        document.getElementById("redeemBtn").classList.add("opacity-50");
        document
          .getElementById("redeemBtn")
          .classList.add("cursor-not-allowed");
        document.getElementById("redeemBtn").disabled = true;
        document.getElementById("redeemText").innerText = "Not enough points";
        document.getElementById("availablePoints").innerText = "0";
        document.getElementById("redeemedValue").innerText = "0";
        document.getElementById("bottleBeforeRedeemt").innerText = "0 Bottles";
        fetchPointsdetails();
        checkPoints();
      } else if (dataa.status == "pincode") {
        alert("Please Update your address in profile");
        window.location.href = "../profile.php";
        document.getElementById("btnLoaderR").classList.add("hidden");
      } else {
        alert(dataa.status);
        console.log(dataa);
        fetchPointsdetails();
        document.getElementById("btnLoaderR").classList.add("hidden");
      }
    },
  });
}
function formatDate(dateString) {
  // Convert string to Date object
  const date = new Date(dateString);

  // Array of month names
  const months = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
  ];

  // Extract day, month, and last two digits of year
  const day = date.getDate();
  const month = months[date.getMonth()];
  const year = date.getFullYear().toString().slice(2); // Get last 2 digits

  return `${day} ${month} ${year}`;
}

async function fetchPointsdetails() {
  // document.getElementById("activityTable").innerHTML = "";

  const response = await fetch("../api/pointsdetail.php");
  const data = await response.json();

  if (data.result == "error" || data.data.length == 0) {
    document.getElementById(
      "activityTable"
    ).innerHTML = `<tr><td colspan="5" class="py-6 text-center text-gray-400">No data found</td></tr>`;
    return;
  }
  document.getElementById("mobileActivityCards").innerHTML = "";
  forphone(data);
  data.data.forEach((element) => {
    let row = document.createElement("tr");
    row.className =
      "border-b border-gray-700/50 hover:bg-white/5 transition-colors";
    row.innerHTML = `
     <tr class="border-b border-gray-700/50 hover:bg-white/5 transition-colors">
                    <td class="py-3 px-4 text-gray-300">${formatDate(
                      element.created_at
                    )}</td>
                    <td class="py-3 px-4 text-white">${
                      element.recycler_name
                    }</td>
                    <td class="py-3 px-4 text-gray-300">${
                      element.totalBottles
                    }</td>
                    <td class="py-3 px-4 ${
                      element.status === "Reject"
                        ? "text-green-400"
                        : "text-red-400"
                    }">${element.points}</td>
                    <td class="py-3 px-4">
                        <span class="${
                          element.status
                        } px-3 py-1 rounded-full text-sm">${
      element.status
    }</span>
                    </td>
                    <td class="py-3 px-4">
                        ${
                          element.status === "Confirm"
                            ? `<button class="action-btn bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-lg transition-colors" onclick="showRedeemedCode('${element.unique_code}')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </button>`
                            : `<button class="action-btn bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-colors" onclick="tableQRmodel('${element.unique_code}')">
                                <svg width="64px" class="w-4 h-4" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <rect x="3" y="3" width="7" height="7" rx="1" stroke="#ffffff" stroke-width="2"></rect> <rect x="3" y="14" width="7" height="7" rx="1" stroke="#ffffff" stroke-width="2"></rect> <rect x="14" y="3" width="7" height="7" rx="1" stroke="#ffffff" stroke-width="2"></rect> <rect x="13" y="13" width="3" height="3" rx="0.5" fill="#ffffff"></rect> <rect x="16" y="16" width="3" height="3" rx="0.5" fill="#ffffff"></rect> <rect x="19" y="13" width="3" height="3" rx="0.5" fill="#ffffff"></rect> <rect x="19" y="19" width="3" height="3" rx="0.5" fill="#ffffff"></rect> <rect x="13" y="19" width="3" height="3" rx="0.5" fill="#ffffff"></rect> </g></svg>
                            </button>`
                        }
    `;
    document.getElementById("activityTable").appendChild(row);
  });

  function forphone(data) {
    data.data.forEach((element) => {
      let div = document.createElement("div");
      div.className =
        "mobile-card glass rounded-lg p-4 border border-gray-600/30";
      div.innerHTML = `<div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <h4 class="text-white font-medium text-sm mb-1">${
                      element.recycler_name
                    }</h4>
                    <p class="text-gray-400 text-xs">${formatDate(
                      element.created_at
                    )}</p>
                </div>
                 <div class="flex items-center space-x-2">
                    <span class="${
                      element.status
                    } px-2 py-1 rounded-full text-xs">${element.status}</span>
                    ${
                      element.status === "Confirm"
                        ? `<button class="action-btn bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-lg transition-colors" onclick="showRedeemedCode('${element.unique_code}')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </button>`
                        : `<button class="action-btn bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-colors" onclick="tableQRmodel('${element.unique_code}')">
                            <svg width="64px" class="w-4 h-4" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <rect x="3" y="3" width="7" height="7" rx="1" stroke="#ffffff" stroke-width="2"></rect> <rect x="3" y="14" width="7" height="7" rx="1" stroke="#ffffff" stroke-width="2"></rect> <rect x="14" y="3" width="7" height="7" rx="1" stroke="#ffffff" stroke-width="2"></rect> <rect x="13" y="13" width="3" height="3" rx="0.5" fill="#ffffff"></rect> <rect x="16" y="16" width="3" height="3" rx="0.5" fill="#ffffff"></rect> <rect x="19" y="13" width="3" height="3" rx="0.5" fill="#ffffff"></rect> <rect x="19" y="19" width="3" height="3" rx="0.5" fill="#ffffff"></rect> <rect x="13" y="19" width="3" height="3" rx="0.5" fill="#ffffff"></rect> </g></svg>
                        </button>`
                    }
                </div> 
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Bottles:</span>
                    <span class="text-gray-300">${element.totalBottles}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Points:</span>
                    <span class="${
                      element.status === "Reject"
                        ? "text-green-400"
                        : "text-red-400"
                    }">${element.points}</span>
                </div>
            </div>`;

      document.getElementById("mobileActivityCards").appendChild(div);
    });
  }
}
fetchPointsdetails();

// QR Code Modal Functions
function qrModal(unique_code) {
  const qrModal = document.getElementById("qrModal");
  const closeQrModal = document.getElementById("closeQrModal");

  const downloadQr = document.getElementById("downloadQr");
 
  const confirmRedemption = document.getElementById("confirmRedemption");

  // Generate unique redemption code
  function generateRedemptionCode() {
    const timestamp = Date.now();
    const userId = "<?php echo $user_id; ?>";
    const points = "<?php echo $reward_points - $redeemed_points; ?>";
    return `REDEEM_${userId}_${points}_${timestamp}`;
  }

  // Show QR Modal
  function showQrModal(unique_code) {
    let url =
      "https://ecocyclebeta.free.nf/dashboard/index.php?page=recyclerScan&code=" + unique_code;
    const redemptionCode = url;

    // Clear previous QR code
    document.getElementById("qrcode").innerHTML = "";

    // Generate new QR code

    const qr = new QRious({
      element: document.getElementById("qrcode"),
      value: redemptionCode,
      size: 170,
      foreground: "#1f2937",
      background: "#ffffff",
    });

    // Show modal
    qrModal.classList.remove("hidden");
    document.body.style.overflow = "hidden";
  }
  showQrModal(unique_code);
  // Hide QR Modal
  function hideQrModal() {
    qrModal.classList.add("hidden");
    document.body.style.overflow = "auto";
  }

  // Download QR Code
  function downloadQrCode() {
    const canvas = document.querySelector("#qrcode");
    if (canvas) {
      const link = document.createElement("a");
      link.download = `redemption_qr_${Date.now()}.png`;
      link.href = canvas.toDataURL();
      link.click();
    }
  }

  // Event Listeners

  closeQrModal.addEventListener("click", hideQrModal);

  downloadQr.addEventListener("click", downloadQrCode);

  confirmRedemption.addEventListener("click", function () {
    // Add your redemption confirmation logic here
    alert(
      "Redemption request submitted! Please show the QR code to complete the process."
    );
    hideQrModal();
  });

  // Close modal when clicking outside
  qrModal.addEventListener("click", function (e) {
    if (e.target === qrModal) {
      hideQrModal();
    }
  });

  // Close modal with Escape key
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !qrModal.classList.contains("hidden")) {
      hideQrModal();
    }
  });
}

// const modal = document.getElementById("redeemedModal");
// const codeText = document.getElementById("redeemedCodeText");
function showRedeemedCode(uniqueCode) {

   fetchDisplayCode(uniqueCode);
  // console.log(data);
  // codeText.textContent = data;
  // modal.classList.remove("hidden");
  // document.body.style.overflow = "hidden"; // Prevent background scrolling
}

function closeModalTable() {
  document.getElementById("qrModaltable").classList.add("hidden");
  document.body.style.overflow = "auto"; // Restore scrolling
}

function closeRedeemedModal() {
  document.getElementById("redeemedModal").classList.add("hidden");
  document.body.style.overflow = "auto"; // Restore scrolling
}

// Close modals when clicking outside
document.getElementById("qrModaltable").addEventListener("click", function (e) {
  if (e.target === this) {
    closeModalTable();
  }
});

document
  .getElementById("redeemedModal")
  .addEventListener("click", function (e) {
    if (e.target === this) {
      closeRedeemedModal();
    }
  });

// Handle escape key to close modals
document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") {
    closeModal();
    closeRedeemedModal();
  }
});

function tableQRmodel(unique_code) {
  document.getElementById("codeText").innerText = unique_code;
  document.getElementById("qrModaltable").classList.remove("hidden");
  const modalx = document.getElementById("qrcodePop");

  function showQrModalTable(unique_code) {
    let url = "https://ecocyclebeta.free.nf/dashboard/index.php?page=recyclerScan&code=" + unique_code;
    const redemptionCode = url;

    // Clear previous QR code
    document.getElementById("qrcodePop").innerHTML = "";

    // Generate new QR code

    const qr = new QRious({
      element: document.getElementById("qrcodePop"),
      value: redemptionCode,
      size: 170,
      foreground: "#1f2937",
      background: "#ffffff",
    });

    // Show modal
    modalx.classList.remove("hidden");
    document.body.style.overflow = "hidden";
  }

  showQrModalTable(unique_code)
}

function fetchDisplayCode(unique_code) {
  // console.log(unique_code);
  
  $.ajax({
    url: "../backend/displayCode.php",
    type: "POST",
    data: {
      code: unique_code
    },
    success: function(data) {
     $("#redeemedCodeText").text(data);
     $("#redeemedModal").removeClass("hidden");
     document.body.style.overflow = "hidden"; 
    }
  })
}