$(document).ready(function() {
    $(document).on('ajaxStart', function() {
        startProgressBar();
    });

    $(document).on('ajaxStop', function() {
        endProgressBar();
    });

    function startProgressBar() {
        const progressBar = document.getElementById('progress-bar');
        progressBar.style.display = 'block'; // Make sure it's visible
        progressBar.style.width = '0%'; // Start from 0%

        // Gradually increase the width to 100% over 2 seconds
        setTimeout(function() {
            progressBar.style.transition = 'width 2s ease';
            progressBar.style.width = '100%';
        }, 10); // Delay slightly to ensure transition occurs
    }

    function endProgressBar() {
        const progressBar = document.getElementById('progress-bar');
        setTimeout(function() { // Delay to show the completion of the process
            progressBar.style.transition = 'width 0.5s ease'; // Faster collapse transition
            progressBar.style.width = '0%'; // Collapse the bar
            setTimeout(function() {
                progressBar.style.display = 'none'; // Hide after collapsing
            }, 500); // Match delay to transition time
        }, 500); // Maintain display of full bar for a moment
    }
});
