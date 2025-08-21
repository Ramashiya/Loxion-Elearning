document.addEventListener("DOMContentLoaded", () => {
    initiateChat();
    document.getElementById("send-button").addEventListener("click", handleSendMessage);
    document.getElementById("user-input").addEventListener("keypress", (e) => {
        if (e.key === "Enter") handleSendMessage();
    });
});

function initiateChat() {
    showMessage("Hello! Welcome to MyAssist. Please select your role:", "bot");
    displayRoleOptions();
}

function showMessage(text, sender) {
    const chatContent = document.getElementById("chat-content");
    const messageElement = document.createElement("div");
    messageElement.className = sender === "bot" ? "message bot-message" : "message user-message";
    messageElement.innerText = text;
    chatContent.appendChild(messageElement);
    chatContent.scrollTop = chatContent.scrollHeight;
}

function displayRoleOptions() {
    const roles = ["Learner", "Teacher", "Parent", "Admin"];
    roles.forEach(role => {
        const roleButton = document.createElement("button");
        roleButton.className = "role-button";
        roleButton.innerText = role;
        roleButton.onclick = () => selectRole(role);
        document.getElementById("chat-content").appendChild(roleButton);
    });
}

function selectRole(role) {
    document.querySelectorAll(".role-button").forEach(button => button.remove());
    showMessage(`You selected: ${role}. Here are the top 10 questions you can ask:`, "bot");
    fetchTopQuestions(role);
}

function fetchTopQuestions(role) {
    fetch("responses.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ role: role, action: "getTopQuestions" })
    })
    .then(response => response.json())
    .then(data => {
        data.questions.forEach(question => {
            const questionButton = document.createElement("button");
            questionButton.className = "question-button";
            questionButton.innerText = question;
            questionButton.onclick = () => handleUserMessage(question);
            document.getElementById("chat-content").appendChild(questionButton);
        });
    })
    .catch(error => {
        console.error("Error fetching questions:", error);
        showMessage("Sorry, an error occurred while fetching questions.", "bot");
    });
}

function handleUserMessage(message) {
    showMessage(message, "user");
    
    // Check if the user wants to end the conversation
    if (isEndingConversation(message)) {
        resetToRoleSelection();
        return;
    }

    fetch("chatbot.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        showMessage(data.response, "bot");
        showMessage("Is that all, or would you like to contact support?", "bot");
    })
    .catch(error => {
        console.error("Error handling message:", error);
        showMessage("Sorry, I couldn't understand that. Could you try again?", "bot");
    });
}

function handleSendMessage() {
    const userInput = document.getElementById("user-input").value.trim();
    if (userInput !== "") {
        handleUserMessage(userInput);
        document.getElementById("user-input").value = ""; // Clear input after sending
    }
}

// Function to check if the user's message indicates the end of the conversation
function isEndingConversation(message) {
    const endKeywords = ["that's all", "done", "finished", "no more", "end"];
    return endKeywords.some(keyword => message.toLowerCase().includes(keyword));
}

// Function to reset to role selection
function resetToRoleSelection() {
    showMessage("Thank you! Returning to role selection. Please select your role:", "bot");
    displayRoleOptions();
}



