// ===============================
// Prevent multiple executions
// ===============================
if (window.__scanScriptLoaded) {
  console.log("⚡ scan.js already loaded, re-initializing scanner only...");
  if (typeof initQrScanner === "function") {
    initQrScanner();
  }
} else {
  window.__scanScriptLoaded = true;
  console.log("✅ scan.js loaded successfully");

  // Prices & Points
  const baseFactor = 10;
  const pricePlastic = 1;
  const priceCan = 1.2;
  const priceGlass = 1.5;
  const pointExchangeRate = 0.25;
  let html5QrcodeScanner;

  // document.getElementById("submit-barcode-btn").addEventListener("click", function() {
  //   alert("Manual barcode entry not implemented yet");
  //   let barcode = document.getElementById("manual-barcode-number").value;
  //   filterData(barcode);
  // })

  // ===============================
  // QR SCAN SUCCESS HANDLER
  // ===============================
  function onScanSuccess(decodedText, decodedResult) {
    document.getElementById("blackScreen").style.display = "flex";
    filterData(decodedText);
  }

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
      onScanSuccess(result);
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

  // ===============================
  // SCAN ANOTHER BUTTON HANDLER
  // ===============================
  const scanBtn = document.getElementById("scan-another-btn");
  if (scanBtn) {
    scanBtn.addEventListener("click", () => {
      document.getElementById("scan-result").classList.add("hidden");
      initQrScanner();
    });
  }

  // ===============================
  // FETCH DATA FROM API
  // ===============================
  function filterData(barcode) {
    fetch(`../api/products.php?barcode=${barcode}`)
      .then((res) => res.json())

      .then((data) => {

        console.log(data);
        if (data.status === "error") {
          alert("Product not found");
          document
            .getElementById("manual-entry-container")
            .classList.remove("hidden");
          document.getElementById("blackScreen").style.display = "none";
          //   window.location.reload();
          return;
        }

        if (data.status === "success") {
          document.getElementById("scan-result").classList.remove("hidden");
          document.getElementById("bottle-name").textContent =
            data.data.name + " " + data.data.quantity;
          document.getElementById("bottle-barcode").textContent =
            data.data.barcode;
          document.getElementById("blackScreen").style.display = "none";

          // Calculate reward points
          let rewardPoints;
          let co2Saved;

          const qty = data.data.quantitySum || data.data.quantity;
          const material = data.data.material;

          if (material === "plastic") {
            rewardPoints = Math.round(baseFactor * qty * pricePlastic);
            co2Saved = Math.round(82.8 * qty * pricePlastic);
          } else if (material === "can") {
            rewardPoints = Math.round(baseFactor * qty * priceCan);
            co2Saved = Math.round(300 * qty * priceCan);
          } else if (material === "glass") {
            rewardPoints = Math.round(baseFactor * qty * priceGlass);
            co2Saved = Math.round(60 * qty * priceGlass);
          } else {
            rewardPoints = Math.round(baseFactor * qty * pricePlastic);
            co2Saved = Math.round(82.8 * qty * pricePlastic);
          }

          document.getElementById("cash-value").textContent =
            "₹" + Math.round(rewardPoints * pointExchangeRate);
          document.getElementById("reward-points").textContent = rewardPoints;
          document.getElementById("co2-saved").textContent = co2Saved + " g";

          // Confirm button
          const confirmBtn = document.getElementById("submit-manual-btnx");
         

            confirmBtn.onclick = () => {
              document
                .getElementById("manual-loading-spinnerx")
                .classList.remove("hidden");
              confirmBtn.disabled = true;

              requestPhp(barcode, data.data.name, rewardPoints, qty, co2Saved);
            };
          
        } else {
          alert("Invalid data");
          window.location.reload();
        }
      })
      .catch((err) => console.error("Request Failed", err));
  }

  // ===============================
  // AJAX REQUEST TO STORE DATA
  // ===============================
  function requestPhp(barcode, name, points, quantity, co2Saved) {
    $.ajax({
      url: "../api/scanStore.php",
      type: "POST",
      data: {
        barcode,
        name,
        points,
        quantity,
        co2Saved,
      },
      success: function (data) {
        console.log(data);
        const spinner = document.getElementById("manual-loading-spinnerx");
        const confirmBtn = document.getElementById("submit-manual-btnx");
        spinner.classList.add("hidden");
        confirmBtn.disabled = false;

        if (data.error) {
          alert(data.error);
        } else {
          alert(data);
        }
      },
    });
  }

  document
    .getElementById("submit-barcode-btn")
    .addEventListener("click", function () {
      document.getElementById("blackScreen").style.display = "flex";
      let barcode = document.getElementById("manual-barcode-number").value;
      filterData(barcode);
    });

  // ===
  // ============================
  // INITIALIZE SCANNER ON FIRST LOAD
  // ===============================
  initQrScanner();

  const form = document.getElementById("manual-entry-form");
  const imageInput = document.getElementById("image-upload");
  const preview = document.getElementById("image-preview");
  const previewImg = document.getElementById("preview-img");
  const placeholder = document.getElementById("upload-placeholder");
  const submitBtn = document.getElementById("submit-manual-btn");
  const spinner = document.getElementById("manual-loading-spinner");
  const submitText = submitBtn.querySelector("span");

  // Image preview + validation
  imageInput.addEventListener("change", function () {
    const file = this.files[0];
    if (!file) return;

    if (file.size > 4 * 1024 * 1024) {
      // 4MB limit
      alert("Image must be less than 4MB");
      this.value = "";
      preview.classList.add("hidden");
      placeholder.classList.remove("hidden");
      return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
      previewImg.src = e.target.result;
      preview.classList.remove("hidden");
      placeholder.classList.add("hidden");
    };
    reader.readAsDataURL(file);
  });

  // Form submit with loader
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Show loader
    spinner.classList.remove("hidden");
    submitText.textContent = "Submitting...";
    submitBtn.disabled = true;

    try {
      const formData = new FormData(form);
      const res = await fetch("../backend/save_manual_entry.php", {
        method: "POST",
        body: formData,
      });
      const data = await res.json();
    //   console.log(data);

      if (data.success) {
        alert("Bottle submitted successfully for approval!");
        form.reset();
        preview.classList.add("hidden");
        placeholder.classList.remove("hidden");
      } else {
        alert("Error: " + data.message);
      }
    } catch (err) {
      alert("Something went wrong!");
      console.error(err);
    }

    // Hide loader
    spinner.classList.add("hidden");
    submitText.textContent = "Submit for Approval";
    submitBtn.disabled = false;
  });
}
