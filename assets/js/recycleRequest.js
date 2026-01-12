

function displayRequests() {
  fetch("../api/fetchRequest.php")
    .then((response) => response.json())
    .then((data) => {
        document.getElementById("pincode").textContent = data.UserPincode;
        document.getElementById("pincodex").textContent = data.UserPincode;
      if (data.status == true || data.total_requests > 0) {
        document.getElementById("totalRequests").textContent = data.total_requests;
        document.getElementById("totalBottles").textContent = data.total_bottles;
        // forTable(data);
        forPhone(data);
        data.requests.forEach((request) => {
          let row = document.createElement("tr");
          row.className =
            "border-b border-gray-700/50 hover:bg-white/5 transition-colors";
          row.innerHTML = `
                           <td class="py-4 px-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                        ${request.user_name
                                          .charAt(0)
                                          .toUpperCase()}
                                    </div>
                                    <span class="text-white">${
                                      request.user_name
                                    }</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-gray-300">${
                              request.phone
                            }</td>
                            <td class="py-4 px-4 text-gray-300">${
                              request.address
                            }</td>
                            <td class="py-4 px-4">
                                <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm font-medium">${
                                  request.total_bottle
                                }</span>
                            </td>
                            <td class="py-4 px-4 text-gray-300">${
                              request.pincode
                            }</td>
                           <td class="py-4 px-4 text-center">
                                <button 
                                    id="accept-btn-${request.unique_id}" 
                                    onclick="acceptRequest('${request.unique_id}')" 
                                    class="flex items-center justify-center gap-2 bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                                    <span class="btn-text">Accept</span>
                                   
                                </button>
                            </td>

       
            `;
          document.getElementById("requestsTableBody").appendChild(row);
        });
      }
      else{
        document.getElementById("emptyState").classList.remove("hidden");
      }
    })
    .catch((error) => {
      console.log(error);
    });

  function forPhone(data) {
    data.requests.forEach((request) => {
      let row = document.createElement("div");
      row.className =
        "glass rounded-xl p-4 border border-gray-700/50 hover:border-gray-600/50 transition-colors";
      row.innerHTML = `
                            <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-red-500 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                ${request.user_name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <h4 class="text-white font-medium">${
                                  request.user_name
                                }</h4>
                                <p class="text-gray-400 text-sm">${
                                  request.phone
                                }</p>
                            </div>
                        </div>
                        <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm font-medium">${
                          request.total_bottle
                        } bottles</span>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-300 text-sm mb-1">📍 ${
                          request.address
                        }</p>
                        <p class="text-gray-400 text-sm">📮 Pincode: ${
                          request.pincode
                        }</p>
                    </div>
                   <button 
                    id="accept-btn-${request.unique_id}" 
                    onclick="acceptRequest('${request.unique_id}')" 
                    class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-medium py-3 rounded-lg transition-all duration-300 transform active:scale-95" >
                    <span class="btn-text">Accept Request</span>
                    <span class="spinner hidden">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </span>
                </button>

              `;
      document.getElementById("requestsTableMobile").appendChild(row);
    });
  }
}
displayRequests();

function acceptRequest(requestId) {

  // let consle = "do you eant to accept this request?";
  if (!confirm("Do you want to accept this request?")) {
    return;
  }

    // Show loading state
    const button = event.target;
    const originalText = button.textContent;
   
    button.innerHTML = ` <span class="spinner ">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                    </span>`;
   
    button.disabled = true;
    button.classList.add('opacity-50');
    

    acceptRequestphp(requestId);


    // Simulate API call
    setTimeout(() => {
        // Remove the request from DOM
        const row = button.closest('tr') || button.closest('.glass');
        row.style.transform = 'translateX(100%)';
        row.style.opacity = '0';

        
       // displayRequests();

        setTimeout(() => {
            row.remove();
            displayRequests();

            // Check if any requests remain
            const remainingRequests = document.querySelectorAll('#requestsTableBody tr').length +
                document.querySelectorAll('.md\\:hidden .glass').length;

            if (remainingRequests === 0) {
                document.getElementById('emptyState').classList.remove('hidden');
            }
        }, 300);

        // Show success notification
        showNotification('Request accepted successfully!', 'success');
    }, 1500);
}

// Show notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-20 right-6 z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300`;
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            ${message}
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Initialize page
document.addEventListener('DOMContentLoaded', () => {
    // Add entrance animations
    const cards = document.querySelectorAll('.card-hover, .glass');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

function acceptRequestphp(unique_id){
    $.ajax({
        url: '../backend/acceptRequest.php',
        type: 'POST',
        data: {unique_id: unique_id},
        success: function(response){
            console.log(response);
        }
    });
    
}
