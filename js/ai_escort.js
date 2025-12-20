// Voice over escort + speach to text (if browser supports it) + AI escorting chat

const API_URL = "../includes/ai_chat.php"; // PHP talks to chatGPT

let chatHistory = [];
let voiceEnabled = true;
let recognition = null;
let recognizing = false;

// DOM
const messagesEl = document.getElementById("aiMessages");
const chatForm = document.getElementById("chatForm");
const userInput = document.getElementById("userMessage");
const voiceInputBtn = document.getElementById("voiceInputBtn");
const toggleVoiceBtn = document.getElementById("toggleVoiceBtn");
const voiceStatusEl = document.getElementById("voiceStatus");
const aiStatusEl = document.getElementById("aiStatus");
const startBtn = document.getElementById("startAiBtn");
const stopBtn = document.getElementById("stopAiBtn");
const muteBtn = document.getElementById("muteAiBtn");
const simulateKeywordBtn = document.getElementById("simulateKeywordBtn");
const emergencyStatusEl = document.getElementById("emergencyStatus");

// Adds message to the chat
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

  chatHistory.push({ role, text });
  if (chatHistory.length > 6) {
    chatHistory = chatHistory.slice(chatHistory.length - 6);
  }

  if (role === "ai" && voiceEnabled) {
    speak(text);
  }
}

// הגנה בסיסית מ-XSS
function escapeHtml(str) {
  return str
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");
}

// Text to Speech
function speak(text) {
  if (!("speechSynthesis" in window)) return;
  const utter = new SpeechSynthesisUtterance(text);
  utter.lang = "he-IL";
  utter.pitch = 0.9;
  utter.rate = 1.0;
  window.speechSynthesis.cancel();
  window.speechSynthesis.speak(utter);
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

    // לא זורקים שגיאה על !res.ok – כי גם במצב כזה יש לנו reply מה-PHP
    const data = await res.json();
    console.log("📩 Server response:", data);

    if (data.reply) {
      // תמיד משתמשות ב-reply – גם אם יש error / http_code 429 וכו'
      addMessage("ai", data.reply);
    } else if (data.error) {
      // במקרה קיצון: יש error אבל אין reply
      addMessage("ai", "נראה שיש בעיה בצד השרת (" + data.error + "). נסי שוב עוד מעט.");
    } else {
      addMessage("ai", "יש לי קצת בעיה להתחבר כרגע, נסי שוב עוד רגע.");
    }
  } catch (err) {
    console.error("❌ ERROR:", err);
    addMessage("ai", "נראה שיש בעיית חיבור, נסי שוב.");
  }
}

// Chat handeling
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

// on/off button switch
if (toggleVoiceBtn && voiceStatusEl) {
  toggleVoiceBtn.addEventListener("click", () => {
    voiceEnabled = !voiceEnabled;
    voiceStatusEl.textContent = voiceEnabled ? "קריאה בקול: פעיל" : "קריאה בקול: כבוי";
  });
}

// הפעלת ליווי AI
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
    if (voiceStatusEl) {
      voiceStatusEl.textContent = voiceEnabled ? "קריאה בקול: פעיל" : "קריאה בקול: כבוי";
    }
  });
}

// סימולציית זיהוי מילת מצוקה
if (simulateKeywordBtn && emergencyStatusEl) {
  simulateKeywordBtn.addEventListener("click", () => {
    emergencyStatusEl.textContent = 'זוהתה מילת מצוקה ("עזרה"). מומלץ ליצור קשר עם מוקד חירום.';
    addMessage(
      "ai",
      "אני איתך. נשמע שקשה לך. אם את מרגישה לא בטוח, אפשר לפנות למוקד חירום או לאדם שאת סומכת עליו."
    );
    sendToServer("המשתמשת ביקשה עזרה או נשמעת במצוקה.", { simulatedEmergency: true });
  });
}

// זיהוי דיבור (אם הדפדפן תומך)
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
