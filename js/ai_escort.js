// AI escort chat + optional speech-to-text + optional text-to-speech

const API_URL = "../includes/ai_chat.php"; // PHP talks to chatGPT
const TTS_URL = "../includes/tts.php"; //TTS endpoint

let chatHistory = [];
let voiceEnabled = true;
let currentAudio = null;
let recognition = null;
let recognizing = false;

// DOM elements (must match IDs in ai_escort.php)
const messagesEl = document.getElementById("aiMessages");
const chatForm = document.getElementById("chatForm");
const userInput = document.getElementById("userMessage");
const voiceInputBtn = document.getElementById("voiceInputBtn");
const aiStatusEl = document.getElementById("aiStatus");
const startBtn = document.getElementById("startAiBtn");
const stopBtn = document.getElementById("stopAiBtn");
const muteBtn = document.getElementById("muteAiBtn");
const simulateKeywordBtn = document.getElementById("simulateKeywordBtn");

// Adds message to the AI chat
function addMessage(role, text) {
  if (!messagesEl) return;

  const div = document.createElement("div");
  div.classList.add("msg");

  if (role === "user") {
    div.classList.add("msg-user");
    div.innerHTML = `<strong>את:</strong> ${escapeHtml(text)}`;
  } else {
    div.classList.add("msg-ai");
    div.innerHTML = `<strong>המלווה:</strong> ${escapeHtml(text)}`;
  }

  messagesEl.appendChild(div);
  messagesEl.scrollTop = messagesEl.scrollHeight;

// Keep a short history (helps the AI keep context, but not too long)
  chatHistory.push({ role, text });
  if (chatHistory.length > 6) {
    chatHistory = chatHistory.slice(chatHistory.length - 6);
  }

  // Speak AI messages if voice is enabled
  if (role === "ai" && voiceEnabled) {
    speak(text);
  }
}

// Basic XSS protection for user/AI text
function escapeHtml(str) {
  return str
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");
}

// Request TTS audio and play it
async function speak(text) {
  if (!voiceEnabled) return;

  const res = await fetch(TTS_URL, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ text }),
  });

  if (!res.ok) return;

  const blob = await res.blob();
  const url = URL.createObjectURL(blob);

  if (currentAudio) currentAudio.pause();
  currentAudio = new Audio(url);
  currentAudio.onended = () => URL.revokeObjectURL(url);
  currentAudio.play();
}

// sending message to the server
async function sendToServer(message, meta = {}) {
  try {
    const res = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        message,
        history: chatHistory,
        meta
      }),
    });


    const data = await res.json();
    console.log("📩 Server response:", data);

    if (data.reply) {
      addMessage("ai", data.reply);
    } else if (data.error) {
      addMessage("ai", "נראה שיש בעיה בצד השרת (" + data.error + "). נסי שוב עוד מעט.");
    } else {
      addMessage("ai", "יש לי קצת בעיה להתחבר כרגע, נסי שוב עוד רגע.");
    }
  } catch (err) {
    console.error("❌ ERROR:", err);
    addMessage("ai", "נראה שיש בעיית חיבור, נסי שוב.");
  }
}

// Chat handling
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

// Simulate “distress keyword detected”
if (simulateKeywordBtn) {
  simulateKeywordBtn.addEventListener("click", () => {
    aiStatusEl.textContent = 'זוהתה מילת מצוקה ("עזרה"). מומלץ ליצור קשר עם מוקד חירום.';
    sendToServer("המשתמשת ביקשה עזרה או נשמעת במצוקה.", { simulatedEmergency: true });
  });
}

// Speech-to-text (if the browser supports it)
function initSpeechRecognition() {
  const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
  if (!SR) return null;

  const rec = new SR();
  rec.lang = "he-IL";
  rec.continuous = false;
  rec.interimResults = false;

  rec.onresult = (e) => {
    const text = e.results[0][0].transcript;
    if (userInput) {
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
} else if (voiceInputBtn) {
  voiceInputBtn.disabled = true;
  voiceInputBtn.title = "הדפדפן לא תומך בדיבור למלל";
}
