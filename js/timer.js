document.addEventListener("DOMContentLoaded", function () {
    const timerDiv = document.getElementById("timer");
    if (!timerDiv) return;

    let remaining = parseInt(timerDiv.getAttribute("data-remaining"), 10);
    let eid = timerDiv.getAttribute("data-eid");

    function updateTimer() {
        let minutes = Math.floor(remaining / 60);
        let seconds = remaining % 60;
        timerDiv.textContent = `${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;

        if (remaining <= 0) {
            alert("⏳ Time’s up! Redirecting to results...");
            // ✅ don’t force submit, just go to result page
            window.location.href = `welcome.php?q=result&eid=${eid}`;
        } else {
            remaining--;
            setTimeout(updateTimer, 1000);
        }
    }

    updateTimer();
});

// ✅ Validation before submitting a question
function validateQuizForm() {
    let options = document.getElementsByName("ans");
    let selected = false;

    for (let i = 0; i < options.length; i++) {
        if (options[i].checked) {
            selected = true;
            break;
        }
    }

    if (!selected) {
        alert("⚠️ Please select an answer before submitting!");
        return false; // stop form submission
    }
    return true; // allow submit
}
