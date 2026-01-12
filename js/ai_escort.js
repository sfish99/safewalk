// AI escort chat + optional speech-to-text + optional text-to-speech

const CHAT_ENDPOINT = "../includes/ai_chat.php";
const VOICE_ENDPOINT = "../includes/tts.php";

let chatHistory = [];
let isVoiceActive = true;
let currentAudioPlayer = null;

const messagesDisplay = document.getElementById("aiMessages");
const mainForm = document.getElementById("chatForm");
const textInput = document.getElementById("userMessage");
const statusLabel = document.getElementById("aiStatus");

//Adding messages to the chat section
function addMessage(sender, content) {
    if (!messagesDisplay) return;

    // Creation of the message elment
    const messageRow = document.createElement("div");
    messageRow.className = "msg " + (sender === "user" ? "msg-user" : "msg-ai");

    // Adding the label (you/escort) to the message header in the UI
    const nameTag = document.createElement("strong");
    nameTag.textContent = (sender === "user" ? "את: " : "המלווה: ");

    //Using textContent to protect from XSS
    const textSpan = document.createElement("span");
    textSpan.textContent = content;

    // Appending elements
    messageRow.appendChild(nameTag);
    messageRow.appendChild(textSpan);
    messagesDisplay.appendChild(messageRow);

    // Auto scroll down
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

// Sending info to PHP server
async function processUserRequest(userText, isUrgent = false) {
    try {
        const payload = {
            message: userText,
            history: chatHistory,
            meta: { simulatedEmergency: isUrgent }
        };

        const response = await fetch(CHAT_ENDPOINT, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

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

        const audioBlob = await res.blob();
        const audioUrl = URL.createObjectURL(audioBlob);

        if (currentAudioPlayer) currentAudioPlayer.pause();
        currentAudioPlayer = new Audio(audioUrl);
        currentAudioPlayer.play();
    } catch (err) {
        console.warn("שגיאה בהשמעת קול:", err);
    }
}

// Lister form
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

// Start/Stop AI escort buttons
const startBtn = document.getElementById("startAiBtn");
if (startBtn) {
    startBtn.addEventListener("click", () => {
        statusLabel.textContent = "ליווי AI פעיל";
        addMessage("ai", "אני כאן, בואי נתחיל ללכת יחד.");
    });
}