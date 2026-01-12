// This script handles the chat UI, sending messages to PHP, and playing voice replies.

const CHAT_ENDPOINT = "../includes/ai_chat.php";
const VOICE_ENDPOINT = "../includes/tts.php";

let chatHistory = [];
let isVoiceActive = true;
let currentAudioPlayer = null;

// Get elements from the HTML page
const messagesDisplay = document.getElementById("aiMessages");
const mainForm = document.getElementById("chatForm");
const textInput = document.getElementById("userMessage");
const statusLabel = document.getElementById("aiStatus");

// Add a new message bubble to the screen
function addMessage(sender, content) {
    if (!messagesDisplay) return;

    // Create the main 'div' of the message element
    const messageRow = document.createElement("div");
    messageRow.className = "msg " + (sender === "user" ? "msg-user" : "msg-ai");

    // Add the name tag (You/escort) to the message bubble to the screen
    const nameTag = document.createElement("strong");
    nameTag.textContent = (sender === "user" ? "את: " : "המלווה: ");

    // Using textContent to protect from XSS
    const textSpan = document.createElement("span");
    textSpan.textContent = content;

    // Append all elements
    messageRow.appendChild(nameTag);
    messageRow.appendChild(textSpan);
    messagesDisplay.appendChild(messageRow);

    // Auto scroll down to the latest message
    messagesDisplay.scrollTop = messagesDisplay.scrollHeight;

    // Saving chat history for further context
    chatHistory.push({ role: sender, text: content });
    if (chatHistory.length > 6) {
        chatHistory.shift();
    }

    // Activating AI voice
    if (sender === "ai" && isVoiceActive) {
        handleVoiceSynthesis(content);
    }
}

// Send user's text to our PHP server
async function processUserRequest(userText, isUrgent = false) {
    try {
        const payload = {
            message: userText,
            history: chatHistory,
            meta: { simulatedEmergency: isUrgent }
        };

        // Get response from the PHP server
        const response = await fetch(CHAT_ENDPOINT, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        // If we got a reply, show it on screen
        if (result.reply) {
            addMessage("ai", result.reply);
        } else {
            addMessage("ai", "מצטערת, משהו השתבש בחיבור. אני עדיין כאן איתך.");
        }
    } catch (error) {
        console.error("Communication Error:", error);
        addMessage("ai", "שגיאת תקשורת. אנא ודאי שיש לך חיבור לאינטרנט.");
    }
}

// TTS convertion using PHP server
async function handleVoiceSynthesis(txt) {
    try {
        const res = await fetch(VOICE_ENDPOINT, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ text: txt })
        });

        if (!res.ok) throw new Error("Voice failed");

        // Convert the response to an audio file and play it
        const audioBlob = await res.blob();
        const audioUrl = URL.createObjectURL(audioBlob);

        if (currentAudioPlayer) currentAudioPlayer.pause();
        currentAudioPlayer = new Audio(audioUrl);
        currentAudioPlayer.play();
    } catch (err) {
        console.warn("שגיאה בהשמעת קול:", err);
    }
}

// Listen for when the user clicks 'Send' or presses Enter
if (mainForm) {
    mainForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const val = textInput.value.trim();
        if (!val) return;

        addMessage("user", val);
        textInput.value = "";
        processUserRequest(val);
    });
}

// Start/Stop AI escort buttons to begin the AI escort session
const startBtn = document.getElementById("startAiBtn");
if (startBtn) {
    startBtn.addEventListener("click", () => {
        statusLabel.textContent = "ליווי AI פעיל";
        addMessage("ai", "היי, אני כאן איתך. את לא לבד.");
    });
}