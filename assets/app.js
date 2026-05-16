import './styles/app.css';

console.log('AssetMapper is active! 🎉');

// Define the function
window.toggleModal = function() {
    const modal = document.getElementById('authModal');
    const container = document.getElementById('modalContainer');
    // Using document.body directly removes the "unresolved" warning
    const body = document.body;

    if (!modal || !container) return;

    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // This stops the background from scrolling while the modal is open
        body.style.overflow = 'hidden';

        setTimeout(() => {
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
        }, 10);
    } else {
        container.classList.add('scale-95', 'opacity-0');
        container.classList.remove('scale-100', 'opacity-100');

        // This gives scrolling back to the user when the modal closes
        body.style.overflow = 'auto';

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
};

// Add the user-type specific logic too
window.openRegisterModal = function(userType) {
    const modal = document.getElementById('authModal');
    if (!modal) return;

    const title = modal.querySelector('h2');
    const subtitle = modal.querySelector('p');

    if (userType === 'job_seeker') {
        title.innerText = "Join as a Professional";
        subtitle.innerText = "Start applying to Symfony developer roles";
    } else if (userType === 'employer') {
        title.innerText = "Hire the Best";
        subtitle.innerText = "Post your job and find experts";
    }

    window.toggleModal();
};

// Close when clicking outside the box
window.addEventListener('click', function(event) {
    const modal = document.getElementById('authModal');
    if (event.target === modal) {
        window.toggleModal();
    }
});