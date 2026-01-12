// This script handles the chat UI, sending messages to PHP, and playing voice replies.

const CHAT_ENDPOINT = "../includes/ai_chat.php";
const VOICE_ENDPOINT = "../includes/tts.php";

let chatHistory = [];
let voiceEnabled = true;
let currentAudio = null;
let recognition = null;
let recognizing = false;

// Get elements from the PHP page
const messagesEl = document.getElementById("aiMessages");
const chatForm = document.getElementById("chatForm");
const userInput = document.getElementById("userMessage");
const voiceInputBtn = document.getElementById("voiceInputBtn");
const aiStatusEl = document.getElementById("aiStatus");
const startBtn = document.getElementById("startAiBtn");
const stopBtn = document.getElementById("stopAiBtn");
const muteBtn = document.getElementById("muteAiBtn");
const simulateKeywordBtn = document.getElementById("simulateKeywordBtn");

// Add a new message bubble to the screen
function addMessage(role, text) {
    if (!messagesEl) return;

    const div = document.createElement("div");
    div.className = "msg " + (role === "user" ? "msg-user" : "msg-ai");

    const nameTag = document.createElement("strong");
    nameTag.textContent = (role === "user" ? "את: " : "המלווה: ");

    const textSpan = document.createElement("span");
    textSpan.textContent = text;

    div.appendChild(nameTag);
    div.appendChild(textSpan);
    messagesEl.appendChild(div);

    messagesEl.scrollTop = messagesEl.scrollHeight;

    chatHistory.push({ role: role, text: text });
    if (chatHistory.length > 6) {
        chatHistory.shift();
    }

    if (role === "ai" && voiceEnabled) {
        speak(text);
    }
}

// Request TTS audio and play it
async function speak(text) {
    try {
        const res = await fetch(VOICE_ENDPOINT, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ text })
        });

        if (!res.ok) return;

        const blob = await res.blob();
        const url = URL.createObjectURL(blob);

        if (currentAudio) currentAudio.pause();
        currentAudio = new Audio(url);
        currentAudio.play();
    } catch (err) {
        console.warn("שגיאה בהשמעת קול:", err);
    }
}

// Send users text to the PHP server
async function sendToServer(userText, isUrgent = false) {
    try {
        const payload = {
            message: userText,
            history: chatHistory,
            meta: { simulatedEmergency: isUrgent }
        };

        const response = await fetch(CHAT_ENDPOINT, {
            method: "POST",
            headers: { "Content-Type": "application/json" }, // תוקן מ-text-Type
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (result.reply) {
            addMessage("ai", result.reply);
        } else {
            addMessage("ai", "מצטערת, משהו השתבש בחיבור.");
        }
    } catch (error) {
        console.error("Communication Error:", error);
        addMessage("ai", "שגיאת תקשורת.");
    }
}

// Listen for chat form submission
if (chatForm && userInput) {
    chatForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const text = userInput.value.trim();
        if (!text) return;

        addMessage("user", text);
        userInput.value = "";
        sendToServer(text);
    });
}

// AI escort controls (start/stop/mute)
if (startBtn && stopBtn && muteBtn && aiStatusEl) {
    startBtn.addEventListener("click", () => {
        aiStatusEl.textContent = "ליווי AI פעיל. אני איתך.";
        startBtn.disabled = true;
        stopBtn.disabled = false;
        muteBtn.disabled = false;
        addMessage("ai", "היי, אני כאן איתך. את לא לבד.");
    });

    stopBtn.addEventListener("click", () => {
        aiStatusEl.textContent = "ליווי AI כבוי כרגע.";
        startBtn.disabled = false;
        stopBtn.disabled = true;
        muteBtn.disabled = true;
        addMessage("ai", "סיימנו את הליווי כרגע. תוכלי להפעיל אותי שוב בכל רגע.");
    });

    muteBtn.addEventListener("click", () => {
        voiceEnabled = !voiceEnabled;
        muteBtn.textContent = voiceEnabled ? "השתקה" : "בטלי השתקה";
    });
}

// Simulate emergency
if (simulateKeywordBtn) {
    simulateKeywordBtn.addEventListener("click", () => {
        aiStatusEl.textContent = 'זוהתה מילת מצוקה ("עזרה"). מומלץ ליצור קשר עם מוקד חירום.';
        sendToServer("המשתמשת ביקשה עזרה או נשמעת במצוקה.", { simulatedEmergency: true });
    });
}

// Speech-to-text
function initSpeechRecognition() {
    const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SR) return null;

    const rec = new SR();
    rec.lang = "he-IL";
    rec.continuous = false;
    rec.interimResults = false;

    rec.onresult = (e) => {
        const text = e.results[0][0].transcript;
        if (userInput){
            userInput.value = text;
        }
    };

  rec.onerror = () => {
    recognizing = false;
  };

  rec.onend = () => {
    recognizing = false;
  };

    return rec;
}

recognition = initSpeechRecognition();

if (voiceInputBtn && recognition) {
    voiceInputBtn.addEventListener("click", () => {
        if (!recognizing) {
            recognizing = true;
            recognition.start();
        } else {
            recognizing = false;
            recognition.stop();
        }
    });
}