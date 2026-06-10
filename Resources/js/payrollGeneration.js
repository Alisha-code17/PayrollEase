
        function displayCurrentDate() {
          // Get the current date
          const now = new Date();
    
          // Format the date
          const options = { year: 'numeric', month: 'long', day: 'numeric' };
          const formattedDate = now.toLocaleDateString(undefined, options);
    
          // Display the date in the HTML element
          document.getElementById('currentDate').innerText = formattedDate;
        }
    
        // Call the function
        displayCurrentDate();
      