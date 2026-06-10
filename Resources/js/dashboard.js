const sidebar = document.getElementById('sidebar');     
const content = document.getElementById('content');
const header = document.getElementById('header');
const toggleButton = document.getElementById('toggleSidebar');

toggleButton.addEventListener('click', () => {
    sidebar.classList.toggle('hidden');
    content.classList.toggle('full-width');
    header.classList.toggle('full-width'); // Toggle the header
});
// Select the circle element
const circle = document.getElementById('circle');

// Add a mousemove event listener to the document
document.addEventListener('mousemove', (event) => {
    const mouseX = event.clientX; // Get the X coordinate of the cursor
    const mouseY = event.clientY; // Get the Y coordinate of the cursor

    // Update the position of the circle
    circle.style.left = `${mouseX}px`;
    circle.style.top = `${mouseY}px`;
});

