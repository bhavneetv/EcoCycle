<!-- Main Content -->
<style>
    .loader {
        width: fit-content;
        font-weight: bold;
        font-family: sans-serif;
        font-size: 30px;
        padding-bottom: 8px;
        background: linear-gradient(currentColor 0 0) 0 100%/0% 3px no-repeat;
        animation: l2 2s linear infinite;
    }

    .loader:before {
        content: "Loading..."
    }

    @keyframes l2 {
        to {
            background-size: 100% 3px
        }
    }
</style>
<div id="blackScreen" class="blackScreen hidden" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: none; justify-content: center; align-items: center; z-index: 9999;">
    <div class="loader"></div>
</div>

<?php include "../../includes/topInfo.php"; ?>
<main class="pt-16 lg:pl-64 min-h-screen">
    <div class="p-6 max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-4">
                Scan Your Bottle
                <svg class="w-8 h-8 inline-block ml-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V6a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"></path>
                </svg>
            </h1>
            <p class="text-gray-300 text-lg">Position the barcode in front of your camera to earn rewards!</p>
        </div>

        <!-- Camera Scanner Section -->
        <div class="p-6">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Scan QR Code</h2>

                    <!-- Scanner container will be inserted here by JavaScript -->
                    <div id="scanner-container" class="mb-6" style="color: blue;">

                    </div>


                </div>
            </div>
        </div>

        <div id="manual-barcode-entry" class="p-6 mb-6 ">
            <div class="max-w-2xl mx-auto">
                <div class="glass rounded-2xl p-6 card-hover">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V6a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold text-white mb-2">Enter Barcode Manually</h3>
                        <p class="text-gray-400">Type the barcode number from your bottle</p>
                    </div>

                    <div class="space-y-6">
                        <label for="manual-barcode-number" class="block text-white font-medium mb-3">
                            Barcode Number *
                        </label>
                        <input type="text" id="manual-barcode-number" name="barcodeNumber" required
                            class="w-full px-4 py-4 rounded-lg bg-white/20 border border-white/30 text-white text-lg text-center placeholder-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-transparent tracking-wider"
                            placeholder="Enter barcode number (e.g., 1234567890123)"
                            pattern="[0-9]*"
                            inputmode="numeric">
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" id="submit-barcode-btn"
                            class="flex items-center justify-center gap-2 flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300">
                            <svg id="barcode-loading-spinner" class="animate-spin h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>Search Bottle</span>
                        </button>

                        <button type="button" id="cancel-barcode-btn"
                            class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300">
                            Back to Scanner
                        </button>
                    </div>

                </div>
            </div>
        </div>


        <!-- Scan Result Section (Hidden by default) -->
        <div id="scan-result" class="glass rounded-2xl p-6 mb-6 card-hover hidden">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-white mb-2">Scan Successful!</h3>
                <p class="text-gray-400">Bottle details retrieved successfully</p>
            </div>

            <!-- Bottle Details Card -->
            <div class="bg-white/10 rounded-xl p-6 max-w-md mx-auto">
                <div class="text-center mb-4">
                    <div class="w-20 h-20 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <h4 id="bottle-name" class="text-xl font-semibold text-white mb-2"></h4>
                    <p id="bottle-barcode" class="text-gray-400 text-sm font-mono">Barcode: </p>
                </div>

                <div class="border-t border-white/20 pt-4">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-400">Reward Points:</span>
                        <span id="reward-points" class="text-2xl font-bold text-green-400"></span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-400">Cash Value:</span>
                        <span id="cash-value" class="text-xl font-semibold text-blue-400"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">CO₂ Saved:</span>
                        <span id="co2-saved" class="text-lg font-medium text-green-400"></span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" id="submit-manual-btnx"
                        class="flex items-center justify-center gap-2 flex-1 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed">

                        <!-- Spinner -->
                        <svg id="manual-loading-spinnerx" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>

                        <!-- Button text -->
                        <span>Submit for Approval</span>
                    </button>

                    <button type="button" id="cancel-manual-btn"
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300">
                        Back to Scanner
                    </button>
                </div>

            </div>
        </div>

        <div id="manual-entry-container" class="glass rounded-2xl p-6 mb-6 card-hover hidden">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-white mb-2">Scanner Failed? Add Manually</h3>
                <p class="text-gray-400">Please provide bottle details for admin approval</p>
            </div>

            <div class="bg-white/10 rounded-xl p-6 max-w-2xl mx-auto">
                <form id="manual-entry-form" class="space-y-6">
                    <!-- Bottle Name -->
                    <div>
                        <label for="manual-bottle-name" class="block text-white font-medium mb-2">
                            Bottle Name *
                        </label>
                        <input type="text" id="manual-bottle-name" name="bottleName" required
                            class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                            placeholder="Enter bottle name or brand">
                    </div>

                    <!-- Bottle Barcode -->
                    <div>
                        <label for="manual-bottle-quantity" class="block text-white font-medium mb-2">
                            Bottle quantity *
                        </label>
                        <input type="text" id="manual-bottle-quantity" name="bottleQuantity" required
                            class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                            placeholder="Enter quantity of this bottle in Liters">
                    </div>
                    <div>
                        <label for="manual-bottle-barcode" class="block text-white font-medium mb-2">
                            Bottle Barcode *
                        </label>
                        <input type="text" id="manual-bottle-barcode" name="bottleBarcode" required
                            class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                            placeholder="Enter barcode number">
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-white font-medium mb-2">
                            Bottle Image *
                        </label>
                        <div class="image-upload-area rounded-lg p-6 text-center cursor-pointer"
                            onclick="document.getElementById('image-upload').click()">
                            <input type="file" id="image-upload" name="bottleImage" accept="image/*" required class="hidden">

                            <div id="upload-placeholder">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-gray-300 mb-2">Click to upload bottle image</p>
                                <p class="text-gray-400 text-sm">PNG, JPG up to 4MB</p>
                            </div>

                            <div id="image-preview" class="hidden">
                                <img id="preview-img" src="" alt="Preview" class="max-w-full h-32 object-contain mx-auto rounded-lg mb-2">
                                <p class="text-green-400 text-sm">Image uploaded successfully</p>
                            </div>
                        </div>
                    </div>

                    <!-- Image Requirements -->
                    <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <h4 class="text-yellow-400 font-semibold mb-2">Image Requirements:</h4>
                                <ul class="text-gray-300 text-sm space-y-1">
                                    <li>• Image must contain the barcode with number clearly visible</li>
                                    <li>• Include full bottle image in the same photo</li>
                                    <li>• Ensure good lighting and clear focus</li>
                                    <li>• Both barcode and bottle should be clearly identifiable</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Points Update Notice -->
                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-blue-300 text-sm">
                                <strong>Note:</strong> Your points will be updated once an admin approves your submission.
                            </p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-3">
                        <button type="submit" id="submit-manual-btn"
                            class="flex items-center justify-center gap-2 flex-1 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300">
                            <svg id="manual-loading-spinner" class="animate-spin h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>Submit for Approval</span>
                        </button>

                        <button type="button" id="cancel-manual-btn"
                            class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300">
                            Back to Scanner
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Instructions Card -->
        <div class="glass rounded-2xl p-6 card-hover">
            <h3 class="text-xl font-semibold text-white mb-4">Scanning Tips</h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-white font-medium mb-2">Good Lighting</h4>
                        <p class="text-gray-400 text-sm">Ensure the barcode is well-lit and clearly visible</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-white font-medium mb-2">Steady Hold</h4>
                        <p class="text-gray-400 text-sm">Keep the bottle steady within the scanning area</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-white font-medium mb-2">Clean Surface</h4>
                        <p class="text-gray-400 text-sm">Make sure the barcode is clean and undamaged</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-white font-medium mb-2">Be Patient</h4>
                        <p class="text-gray-400 text-sm">Allow a few seconds for the scanner to read the code</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="../assets/js/scan.js"></script>


</body>

</html>