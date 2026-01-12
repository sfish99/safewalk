// SafeWalk - AI Escort
// Manages the chat UI, Microphone, and API calls.

const API_CHAT = "../includes/ai_chat.php"; // PHP talks to chatGPT
const API_TTS = "../includes/tts.php"; //TTS endpoint

let chatHistory = [];
let voiceEnabled = true;
let currentAudio = null;
//let recognition = null;
//let recognizing = false;

// DOM elements (must match IDs in ai_escort.php)
const chatContainer = document.getElementById("aiMessages");
const chatForm = document.getElementById("chatForm");
const userInput = document.getElementById("userMessage");
const voiceInputBtn = document.getElementById("voiceInputBtn");
const statusText = document.getElementById("aiStatus");
const startBtn = document.getElementById("startAiBtn");
const stopBtn = document.getElementById("stopAiBtn");
const muteBtn = document.getElementById("muteAiBtn");
const simulateKeywordBtn = document.getElementById("simulateKeywordBtn");

// Adds message to the AI chat
function addMessage(role, text) {
  if (!chatContainer) return;

  //create div for a new message bubble
  const div = document.createElement("div");
  div.classList.add("msg");

  //create a message "bubble" for the user input
  if (role === "user") {
    div.classList.add("msg-user");
    div.innerHTML = `<strong>את:</strong> ${(text)}`;
  }
  //create a message "bubble" for the AI output
  else {
    div.classList.add("msg-ai");
    div.innerHTML = `<strong>המלווה:</strong> ${(text)}`;
  }

  chatContainer.appendChild(div);

  //Auto scroll to bottom
  chatContainer.scrollTop = chatContainer.scrollHeight;

  // Keep a short history (helps the AI keep context of the conversation)
  chatHistory.push({ role, text });
  if (chatHistory.length > 5) {
    chatHistory = chatHistory.shift();
  }

  // Speak AI messages if voice is enabled
  if (role === "ai" && voiceEnabled) {
    speak(text);
  }
}

// Request TTS audio and play it
async function speak(text) {
    try {
            const response = await fetch(API_TTS, {
                method: "POST",
                body: JSON.stringify({ text: text })
            });
            const blob = await response.blob();
            const url = URL.createObjectURL(blob);

            if (currentAudio) currentAudio.pause();
            currentAudio = new Audio(url);
            currentAudio.play();
        } catch (err) {
            console.error("TTS Error:", err);
        }
}

// sending message to the server
async function sendToServer(message, meta = {}) {
  try {
    const res = await fetch(API_CHAT, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        message: message,
        history: chatHistory,
        meta: meta
      }),
    });
    const data = await res.json();
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
if (startBtn && stopBtn && muteBtn && statusText) {
  startBtn.addEventListener("click", () => {
    statusText.textContent = "ליווי AI פעיל. אני איתך.";
    startBtn.disabled = true;
    stopBtn.disabled = false;
    muteBtn.disabled = false;
    addMessage("ai", "היי, אני כאן איתך. את לא לבד.");
  });

  stopBtn.addEventListener("click", () => {
    statusText.textContent = "ליווי AI כבוי כרגע.";
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
    statusText.textContent = 'זוהתה מילת מצוקה ("עזרה"). מומלץ ליצור קשר עם מוקד חירום.';
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
