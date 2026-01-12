(function () {
    // Prevent duplicate initialization
    
      
    
    window.__historyScriptLoaded = true;

    

    // ====== STATE VARIABLES ======
    let currentPage = 1;
    let totalPages = 1;
    let currentType = "all";
    let currentTime = "all";
    let searchTimeout = null;

    // ====== SEARCH INPUT EVENT ======
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener("input", () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                renderTableRows("all", "all", 1, searchInput.value);
                renderMobileCards("all", "all", 1, searchInput.value);
            }, 250);
        });
    }

    // ====== FETCH & RENDER TABLE ROWS ======
    async function renderTableRows(time, type, page, search) {
        time = time || "all";
        type = type || "all";
        page = page || 1;
        search = search || "";

        // console.log(`📄 Fetching history table → Page: ${page}, Type: ${type}, Time: ${time}`);

        const response = await fetch(`../api/fetchHistory.php?type=${type}&time=${time}&page=${page}&search=${search}`);
        const res = await response.json();

        if (res.status !== "success" || res.total_records == 0) {
            document.querySelector("#historyTableBody").innerHTML = `
                <tr>
                    <td colspan="5" class="py-6 text-center text-gray-400">No data found</td>
                </tr>`;
            return;
        }

        let totalPoints = 0;
        const rows = res.data.map((item) => {
            const dateObj = new Date(item.date);
            const formatted = {
                date: dateObj.toLocaleDateString("en-IN", { day: "2-digit", month: "short", year: "numeric" }),
                time: dateObj.toLocaleTimeString("en-IN", { hour: "2-digit", minute: "2-digit" })
            };
            totalPoints += item.points;
            return `
            <tr class="border-b border-gray-700/50 hover:bg-white/5 transition-colors">
                <td class="py-4 px-6">
                    <div>
                        <div class="text-white font-medium">${item.item}</div>
                        ${item.source === "scan"
                ? `<div class="text-gray-400 text-xs">Bottle</div>`
                : `<div class="text-blue-400 text-xs">Reward</div>`}
                    </div>
                </td>
                <td class="py-4 px-6">
                    <span class="text-gray-300 font-mono text-sm">${item.bottle_code || "-"}</span>
                </td>
                <td class="py-4 px-6">
                    <span class="${item.source !== "scan" ? "text-red-400" : "text-green-400"} font-semibold">
                        ${item.source !== "scan" ? "-" : "+"}${item.points}
                    </span>
                </td>
                <td class="py-4 px-6">
                    <div class="text-white">${formatted.date}</div>
                    <div class="text-gray-400 text-sm">${formatted.time}</div>
                </td>
                <td class="py-4 px-6">
                    ${getStatusBadge(item.status)}
                </td>
            </tr>`;
        }).join("");

        document.querySelector("#historyTableBody").innerHTML = rows;

        document.querySelector("#totalPoints").textContent = totalPoints;

        updatePagination(res.page, res.total_pages);
    }

    // ====== FETCH & RENDER MOBILE CARDS ======
    async function renderMobileCards(time, type, page, search) {
        time = time || "all";
        type = type || "all";
        page = page || 1;
        search = search || "";

        // console.log(`📱 Fetching history cards → Page: ${page}`);

        const response = await fetch(`../api/fetchHistory.php?type=${type}&time=${time}&page=${page}&search=${search}`);
        const res = await response.json();

        if (res.status !== "success" || !res.data || res.data.length === 0 || res.total_records == 0) {
            document.querySelector("#historyCards").innerHTML = `
                <div class="p-6 text-center text-gray-400">No history found</div>`;
            return;
        }

        const cards = res.data.map((item) => {
            const dateObj = new Date(item.date);
            const formatted = {
                date: dateObj.toLocaleDateString("en-IN", { day: "2-digit", month: "short", year: "numeric" }),
                time: dateObj.toLocaleTimeString("en-IN", { hour: "2-digit", minute: "2-digit" })
            };

            return `
                <div class="p-6 border-b border-gray-700/50 hover:bg-white/5 transition-colors">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="text-white font-medium">${item.item}</h4>
                            ${item.source === "scan"
                    ? `<p class="text-gray-400 text-xs">Bottle</p>`
                    : `<p class="text-blue-400 text-xs">Reward</p>`}
                        </div>
                        ${getStatusBadge(item.status)}
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="${item.source !== "scan" ? "text-red-400" : "text-green-400"} font-semibold">
                                ${item.source !== "scan" ? "-" : "+"}${item.points} points
                            </span>
                        </div>
                        <div class="text-right">
                            <div class="text-white text-sm">${formatted.date}</div>
                            <div class="text-gray-400 text-xs">${formatted.time}</div>
                        </div>
                    </div>
                </div>`;
        }).join("");

        document.querySelector("#historyCards").innerHTML = cards;
    }

    // ====== STATUS BADGES ======
    function getStatusBadge(status) {
        const statusColors = {
            "Complete": "background-color:rgba(20, 192, 57, 0.27); color:rgb(21, 247, 0);",
            "Confirm": "background-color:rgba(20, 192, 57, 0.27); color:rgb(21, 247, 0);",
            "Processing": "background-color: #aeae0e45; color: #aeae0e;",
            "Pending": "background-color: #aeae0e45; color: #aeae0e;",
            "Accept by recycler": "background-color: #aeae0e45; color: #aeae0e;",
            "Reject": "background-color:rgba(184, 46, 28, 0.27); color:rgb(255, 97, 58);"
        };
        return `<span class="px-3 py-1 rounded-full text-sm" style="${statusColors[status]}" >${status}</span>`;
    }

    // ====== FILTERS ======
    function filterHistory() {
        let status = document.getElementById("statusFilter").value;
        let date = document.getElementById("dateFilter").value;
        renderTableRows(date, status, 1, searchInput.value);
        renderMobileCards(date, status, 1, searchInput.value);
    }

    document.getElementById("statusFilter")?.addEventListener("change", filterHistory);
    document.getElementById("dateFilter")?.addEventListener("change", filterHistory);

    document.getElementById("clearFilters")?.addEventListener("click", () => {
        document.getElementById("statusFilter").value = "all";
        document.getElementById("dateFilter").value = "all";
        searchInput.value = "";
        renderTableRows("all", "all", 1, "");
        renderMobileCards("all", "all", 1, "");
    });

    // ====== PAGINATION ======
    function updatePagination(page, total) {
        currentPage = page;
        totalPages = total;

        document.getElementById("showingRecords").textContent = `Showing ${page * 10 - 9}-${page * 10} of ${total} records`;
        document.getElementById("prevPage").disabled = page === 1;
        document.getElementById("nextPage").disabled = page === total;
    }

    document.getElementById("prevPage")?.addEventListener("click", () => {
        if (currentPage > 1) {
            renderTableRows(currentTime, currentType, currentPage - 1, searchInput.value);
            renderMobileCards(currentTime, currentType, currentPage - 1, searchInput.value);
            updatePagination(currentPage - 1, totalPages);
        }
    });

    document.getElementById("nextPage")?.addEventListener("click", () => {
        if (currentPage < totalPages) {
            renderTableRows(currentTime, currentType, currentPage + 1, searchInput.value);
            renderMobileCards(currentTime, currentType, currentPage + 1, searchInput.value);
            updatePagination(currentPage + 1, totalPages);
        }
    });

    // ====== INITIALIZE PAGE ======
    renderTableRows();
    renderMobileCards();

    // ====== CLEANUP ON LEAVE ======
    window.addEventListener("pagehide", function () {
        // console.log("♻️ Leaving History page, cleaning up...");
        window.__historyScriptLoaded = false;
    });

})();
