document.addEventListener('DOMContentLoaded', () => {
    const walkerBtn = document.querySelector('.role-btn-toggle.walker');
    const volunteerBtn = document.querySelector('.role-btn-toggle.volunteer');

    function handleRoleSwitch(event) {
        const clickedButton = event.currentTarget;

        // אם הכפתור כבר פעיל, אל תעשה כלום
        if (clickedButton.classList.contains('active')) {
            return;
        }

        // אם נלחץ כפתור "הולכת רגל"
        if (clickedButton.classList.contains('walker')) {
            // נווט לדף הראשי של הולכת רגל
            window.location.href = 'home_walker.php';
        } 
        // אם נלחץ כפתור "מתנדבת"
        else if (clickedButton.classList.contains('volunteer')) {
            // נווט לדף הראשי של מתנדבת
            window.location.href = 'home_volunteer.php';
        }
    }

    // הוספת מאזינים (Event Listeners) לכפתורים
    if (walkerBtn) {
        walkerBtn.addEventListener('click', handleRoleSwitch);
    }
    if (volunteerBtn) {
        volunteerBtn.addEventListener('click', handleRoleSwitch);
    }
});