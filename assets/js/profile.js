  // DOM Elements
  const editBtn = document.getElementById('editBtn');
  const profileForm = document.getElementById('profileForm');
  const successToast = document.getElementById('successToast');
  const inputs = profileForm.querySelectorAll('input, textarea');

  let isEditing = false;

  // Edit/Save functionality
  editBtn.addEventListener('click', () => {
      if (!isEditing) {
          // Enable editing mode
          enableEditing();
      } else {
          // Save changes
          saveChanges();
      }
  });

  function enableEditing() {
      isEditing = true;
      
      // Enable all inputs
      inputs.forEach(input => {
          input.disabled = false;
          input.classList.remove('disabled:opacity-60', 'disabled:cursor-not-allowed');
          input.classList.add('bg-white/15', 'border-green-500/50');
      });
      
      // Change button text and style
      editBtn.textContent = 'Save Changes';
      editBtn.classList.remove('from-green-500', 'to-blue-500', 'hover:from-green-600', 'hover:to-blue-600');
      editBtn.classList.add('from-blue-500', 'to-purple-500', 'hover:from-blue-600', 'hover:to-purple-600');
      
      // Focus on first input
      inputs[0].focus();
  }

  
  function saveChanges() {
      // Validate form (basic validation)
      const fullName = document.getElementById('fullName').value.trim();
      const email = document.getElementById('email').value.trim();
      const phone = document.getElementById('phone').value.trim();
      const address = document.getElementById('address').value.trim();
      const pincode = document.getElementById('pincode').value.trim();
      const country = document.getElementById('countryx').value;

      if (!fullName || !email || !phone || !address || !pincode || !country) {
        console.log(pincode);
          showErrorToast('Please fill in all required fields');
          return;
      }

      // Email validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
          showErrorToast('Please enter a valid email address');
          return;
      }

     updateProfile(fullName, email, phone, address, pincode, country);
      
      setTimeout(() => {
          // Disable editing mode
          disableEditing();
          // Update avatar initials if name changed
          updateAvatarInitials(fullName);
      }, 1000);
  }

  function disableEditing() {
      isEditing = false;
      
      // Disable all inputs
      inputs.forEach(input => {
          input.disabled = true;
          input.classList.add('disabled:opacity-60', 'disabled:cursor-not-allowed');
          input.classList.remove('bg-white/15', 'border-green-500/50');
      });
      
      // Reset button text and style
      editBtn.textContent = 'Edit Details';
      editBtn.classList.remove('from-blue-500', 'to-purple-500', 'hover:from-blue-600', 'hover:to-purple-600');
      editBtn.classList.add('from-green-500', 'to-blue-500', 'hover:from-green-600', 'hover:to-blue-600');
      editBtn.disabled = false;
      editBtn.innerHTML = 'Edit Details';
  }

  function showLoadingState() {
      editBtn.disabled = true;
      editBtn.innerHTML = `
          <div class="flex items-center justify-center space-x-2">
              <svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
              <span>Saving...</span>
          </div>
      `;
  }

  function showSuccessToast() {
      successToast.classList.add('show');
      setTimeout(() => {
          successToast.classList.remove('show');
      }, 3000);
  }

  function showErrorToast(message) {
      // Create error toast (you can customize this)
      const errorToast = document.createElement('div');
      errorToast.className = 'toast fixed top-6 right-6 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50';
      errorToast.innerHTML = `
          <div class="flex items-center space-x-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <span class="font-medium">${message}</span>
          </div>
      `;
      
      document.body.appendChild(errorToast);
      
      // Show toast
      setTimeout(() => {
          errorToast.classList.add('show');
      }, 100);
      
      // Hide and remove toast
      setTimeout(() => {
          errorToast.classList.remove('show');
          setTimeout(() => {
              document.body.removeChild(errorToast);
          }, 300);
      }, 3000);
  }

  function updateAvatarInitials(fullName) {
      const avatar = document.querySelector('.profile-avatar');
      const names = fullName.split('');
      const initials = names.length >= 2 
          ? names[0][0] + names[names.length - 1][0] 
          : names[0][0] + (names[0][1] || '');
      avatar.textContent = initials.toUpperCase();
  }


  function goToHistory(link) {
      // Add smooth transition effect
      document.body.style.transition = 'opacity 0.3s ease';
      document.body.style.opacity = '0';
      
      setTimeout(() => {
          window.location.href = link;
      }, 300);
  }

  // Initialize app
  document.addEventListener('DOMContentLoaded', () => {
      // Add loading animation
      document.body.style.opacity = '0';
      setTimeout(() => {
          document.body.style.transition = 'opacity 0.5s ease';
          document.body.style.opacity = '1';
      }, 100);

      // Add entrance animations
      const cards = document.querySelectorAll('.card-hover');
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

  // Handle form submission with Enter key
  profileForm.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && isEditing) {
          e.preventDefault();
          saveChanges();
      }
  });

  // Mobile-specific optimizations
  if ('ontouchstart' in window) {
      document.body.classList.add('touch-device');
      
      // Prevent zoom on double tap
      let lastTouchEnd = 0;
      document.addEventListener('touchend', function (event) {
          const now = (new Date()).getTime();
          if (now - lastTouchEnd <= 300) {
              event.preventDefault();
          }
          lastTouchEnd = now;
      }, false);
  }

  // Keyboard shortcuts
  document.addEventListener('keydown', (e) => {
      // Ctrl/Cmd + E to edit
      if ((e.ctrlKey || e.metaKey) && e.key === 'e' && !isEditing) {
          e.preventDefault();
          enableEditing();
      }
      
      // Escape to cancel editing
      if (e.key === 'Escape' && isEditing) {
          // Reset form values and disable editing
          location.reload(); // Simple way to reset form
      }
  });

function updateProfile(fullName, email, phone, address, pincode, country) {

    $.ajax({
        url: 'api/updateProfile.php',
        type: 'POST',
        data: {
            fullName: fullName,
            email: email,
            phone: phone,
            address: address,
            pincode: pincode,
            country: country
        },
        success: function (response) {
           
            if (response === 'success') {
                showSuccessToast();
            } else {
                showErrorToast(response);
            }
        }
    });
    
}