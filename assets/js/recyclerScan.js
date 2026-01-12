if (window.__scanScriptLoaded) {
  console.log("⚡ scan.js already loaded, re-initializing scanner only...");
  if (typeof initQrScanner === "function") {
    initQrScanner();
  }
} else {
  window.__scanScriptLoaded = true;
  console.log("✅ scan.js loaded successfully");

  // Prices & Points

  let html5QrcodeScanner;

  document
    .getElementById("submit-manual")
    .addEventListener("click", function () {
      // alert("Manual barcode entry not implemented yet");
      let barcode = document.getElementById("manual-code").value;
      getData(barcode);
    });

  function onScanSuccess(decodedText, decodedResult) {
    console.log(decodedText);
    document.getElementById("blackScreen").style.display = "flex";
    const code = filterUrl(decodedText);
    getData(code);
    // filterData(decodedText);
  }
  document
    .getElementById("confirm-request")
    .addEventListener("click", function () {
      document.getElementById("confirm-spinner").classList.remove("hidden");
      document.getElementById("confirm-request").classList.add("disabled");
      document.getElementById("confirm-request").classList.add("opacity-50");
      // console.log(document.getElementById("recycler-id").textContent);

      doRequest("accept", document.getElementById("recycler-id").textContent);
    });
  document
    .getElementById("reject-request")
    .addEventListener("click", function () {
      document.getElementById("reject-spinner").classList.remove("hidden");
      document.getElementById("reject-request").classList.add("disabled");
      document.getElementById("reject-request").classList.add("opacity-50");
      doRequest("reject", document.getElementById("recycler-id").textContent);
    });

  // ===============================
  // QR SCAN ERROR HANDLER
  // ===============================
  function onScanError(error) {
    console.warn(`Scan Error: ${error}`);
  }

  // ===============================
  // Fallback: Decode from Image
  // ===============================
  async function decodeFromImage(file) {
    const html5QrCode = new Html5Qrcode("scanner-container");
    try {
      const result = await html5QrCode.scanFile(file, true);
      console.log("✅ Image Decode Success:", result);
      // onScanSuccess(result);
    } catch (err) {
      console.error("❌ Image Decode Failed:", err);
      alert("Unable to detect barcode. Try another image or rescan.");
    } finally {
      html5QrCode.clear();
    }
  }

  // ===============================
  // INITIALIZE QR SCANNER
  // ===============================
  function initQrScanner() {
    const config = {
      fps: 30, // faster scanning
      qrbox: { width: 400, height: 400 }, // larger scan area
      rememberLastUsedCamera: true,
      showTorchButtonIfSupported: true,
      supportedFormats: [
        Html5QrcodeSupportedFormats.EAN_13,
        Html5QrcodeSupportedFormats.EAN_8,
        Html5QrcodeSupportedFormats.UPC_A,
        Html5QrcodeSupportedFormats.UPC_E,
        Html5QrcodeSupportedFormats.CODE_39,
        Html5QrcodeSupportedFormats.CODE_93,
        Html5QrcodeSupportedFormats.CODE_128,
        Html5QrcodeSupportedFormats.ITF,
        Html5QrcodeSupportedFormats.QR_CODE,
        Html5QrcodeSupportedFormats.DATA_MATRIX,
      ],
      videoConstraints: {
        facingMode: { ideal: "environment" },
        width: { ideal: 1280 },
        height: { ideal: 720 },
        zoom: 2.5, // request 2.5x zoom (best-effort)
      },
    };

    if (html5QrcodeScanner) {
      html5QrcodeScanner
        .clear()
        .then(() => {
          html5QrcodeScanner.render(onScanSuccessStop, onScanError);
        })
        .catch((err) => console.error("Scanner clear failed:", err));
    } else {
      html5QrcodeScanner = new Html5QrcodeScanner("scanner-container", config);
      html5QrcodeScanner.render(onScanSuccessStop, onScanError);
    }
  }

  // ===============================
  // STOP SCAN ON SUCCESS
  // ===============================
  function onScanSuccessStop(decodedText, decodedResult) {
    onScanSuccess(decodedText, decodedResult);

    if (html5QrcodeScanner) {
      html5QrcodeScanner
        .clear()
        .catch((err) => console.error("Error stopping scanner:", err));
    }
  }

  initQrScanner();

  function filterUrl(url) {
    const urlParams = new URLSearchParams(url);
    return urlParams.get("code");
  }

  function ifFilter() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get("code")) {
      // console.log(urlParams.get("code"));
      getData(urlParams.get("code"));
    }
  }
  ifFilter();
  //  getData("J65VUXF");
  function getData(code) {
    fetch("../api/recycler/fetchDetails.php?code=" + code)
      .then((response) => response.json())
      .then((data) => {
        if (!data.success) {
          showNotification(data.message, "error");
          // Get current URL
          const url = new URL(window.location.href);

          // Remove the "code" parameter
          url.searchParams.delete("code");

          // Replace URL in the address bar (no reload)
          window.history.replaceState(
            {},
            document.title,
            url.pathname + url.search
          );

          return;
        }
        document.getElementById("bottle-details-body").innerHTML = "";
        document.getElementById("request-details").classList.remove("hidden");
        document.getElementById("scanner-section").classList.add("hidden");

        document.getElementById("recycler-id").textContent = data.unique_code;
        document.getElementById("recycler-name").textContent =
          data.user.full_name;
        document.getElementById("recycler-contact").textContent =
          "+91 " + data.user.phone;
        document.getElementById("request-date").textContent =
          data.request.created_at;
        document.getElementById("blackScreen").style.display = "none";

        forPhone(data);
        let sno = 1;
        data.scans.forEach((element) => {
          let row = document.createElement("div");
          row.className =
            "grid grid-cols-5 gap-4 p-4 border-b border-gray-700/50 hover:bg-white/5 transition-colors text-sm";
          row.innerHTML = `
        <div class="text-gray-300">${sno++}</div>
        <div class="text-white font-medium">${element.bottle_name}</div>
        <div class="text-gray-300 font-mono">${element.bottle_code}</div>
        <div class="text-white">${element.quantity}</div>
        <div class="text-green-400">${element.points_earned}</div>
    `;
          document.getElementById("bottle-details-body").appendChild(row);
        });
        // document.getElementById("totalItems").textContent = data.scans.length;
        document.getElementById("totalPoints").textContent = data.scans.reduce(
          (total, scan) => total + scan.points_earned,
          0
        );
        document.getElementById("totalItemsf").textContent = sno - 1;
        document.getElementById("totalPointsf").textContent = data.scans.reduce(
          (total, scan) => total + scan.points_earned,
          0
        );
        document.getElementById("totalKgf").textContent = (
          (sno * 83) /
          1000
        ).toFixed(2);
      })

      .catch((error) => {
        console.log(error);
        showNotification(error, "error");
      });
  }

  function forPhone(data) {
    let sno = 1;
    document.getElementById("bottle-details-bodym").innerHTML = "";
    data.scans.forEach((element) => {
      let rows = document.createElement("div");
      rows.className = "bg-gray-800/20 rounded-lg p-4 border border-gray-600";
      rows.innerHTML = `
         <div class="flex justify-between items-start mb-2">
                <span class="text-xs text-gray-400">${sno++}</span>
                <span class="text-green-400 font-semibold">${
                  element.points_earned
                } pts</span>
            </div>
            <div class="text-white font-medium mb-1">${
              element.bottle_name
            }</div>
            <div class="text-gray-300 text-sm mb-2 font-mono">${
              element.bottle_code
            }</div>
            <div class="text-right">
                <span class="text-gray-400 text-sm">Qty: (L) </span>
                <span class="text-white font-medium">${element.quantity}</span>
            </div>
    `;
      document.getElementById("bottle-details-bodym").appendChild(rows);
    });
  }

  function showNotification(message, type = "success") {
    const statusMessage = document.getElementById("status-message");
    const statusText = document.getElementById("status-text");

    statusText.textContent = message;
    statusMessage.classList.remove("hidden");

    // Change colors based on type
    const messageDiv = statusMessage.querySelector("div");
    messageDiv.className = `glass rounded-lg p-4 border-l-4`;

    if (type === "success") {
      messageDiv.classList.add("border-green-500");
      messageDiv.querySelector("svg").classList.add("text-green-500");
    } else {
      messageDiv.classList.add("border-red-500");
      messageDiv.querySelector("svg").innerHTML =
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
      messageDiv.querySelector("svg").classList.add("text-red-500");
    }

    setTimeout(() => {
      statusMessage.classList.add("hidden");
    }, 4000);
  }
  function doRequest(type, code) {
    $.ajax({
      url: "../backend/confirmRequest.php",
      type: "POST",
      data: {
        code: code,
        type: type,
      },
      success: function (data) {
        document.getElementById("confirm-spinner").classList.add("hidden");
        document.getElementById("confirm-request").classList.remove("disabled");
        document
          .getElementById("confirm-request")
          .classList.remove("opacity-50");
        document.getElementById("reject-spinner").classList.add("hidden");
        document.getElementById("reject-request").classList.remove("disabled");
        document
          .getElementById("reject-request")
          .classList.remove("opacity-50");

          

        showNotification(data.message, "success");
        console.log(data);
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      },
    });
  }
}
