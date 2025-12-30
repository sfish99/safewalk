// Select all FAQ question buttons
const questions = document.querySelectorAll(".faq-question");
// Loop through each question button
questions.forEach(q => {
    // Add a click event listener to each question
    q.addEventListener("click", () => {
        // Toggle the "active" class
        q.classList.toggle("active");
        // Get the answer element (the next sibling after the question)
        const answer = q.nextElementSibling;
        // If the answer is already open, close it
        if (answer.style.maxHeight) {
            answer.style.maxHeight = null;
        } 
         // Otherwise, open the answer by setting its height dynamically
        else {
            answer.style.maxHeight = answer.scrollHeight + "px";
        }
    });
});
