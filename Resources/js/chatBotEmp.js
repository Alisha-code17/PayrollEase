// Conversation state tracking
const conversationState = {
    showDomainQuestions: true,
    waitingForMenuChoice: false,
    inContinueMode: false
};

// Smart view management to keep questions and answers visible
function maintainQuestionAnswerView() {
    const chatBox = document.getElementById('chat-box');
    // First ensure we're at bottom
    chatBox.scrollTop = chatBox.scrollHeight;
    
    setTimeout(() => {
        // Find all messages
        const messages = chatBox.querySelectorAll('.user-message, .bot-message');
        if (messages.length > 0) {
            // Find the last user question
            let lastQuestion = null;
            for (let i = messages.length - 1; i >= 0; i--) {
                if (messages[i].classList.contains('user-message')) {
                    lastQuestion = messages[i];
                    break;
                }
            }
            
            if (lastQuestion) {
                const questionPos = lastQuestion.offsetTop;
                const visibleHeight = chatBox.clientHeight;
                // Scroll to show question and space for answer
                chatBox.scrollTop = Math.max(0, questionPos - (visibleHeight / 4));
            }
        }
    }, 50);
}

function sendMessage() {
    const userInput = document.getElementById('user-input').value.trim().toLowerCase();
    if (userInput === "") return;

    const chatBox = document.getElementById('chat-box');
    
    // Handle menu choices
    if (conversationState.waitingForMenuChoice) {
        handleMenuChoice(userInput);
        return;
    }

    // Add user message
    const userMessage = document.createElement('div');
    userMessage.className = 'user-message';
    userMessage.textContent = userInput;
    chatBox.appendChild(userMessage);

    // Clear input
    document.getElementById('user-input').value = '';

    // Show typing indicator
    const typingIndicator = document.createElement('div');
    typingIndicator.className = 'bot-message';
    typingIndicator.textContent = 'typing...';
    chatBox.appendChild(typingIndicator);
    maintainQuestionAnswerView();

    fetch("../Controllers/chatBotEmp.php", {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({message: userInput})
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        chatBox.removeChild(typingIndicator);
        
        const botMessage = document.createElement('div');
        botMessage.className = 'bot-message';
        
        const wasDomainQuestion = data.source === 'domain';
        
        if (wasDomainQuestion) {
            botMessage.style.color = '#000000';
            conversationState.showDomainQuestions = false;
            conversationState.inContinueMode = false;
        }
        
        const formattedResponse = formatResponse(data.response);
        botMessage.innerHTML = formattedResponse;
        chatBox.appendChild(botMessage);
        maintainQuestionAnswerView();
        
        if (data.follow_up && data.follow_up.length > 0) {
            if (wasDomainQuestion || conversationState.inContinueMode) {
                showMenuOptions();
            } else if (conversationState.showDomainQuestions) {
                showDomainQuestions(data.follow_up);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        chatBox.removeChild(typingIndicator);
        const errorMessage = document.createElement('div');
        errorMessage.className = 'bot-message error-message';
        errorMessage.innerHTML = 'Failed to fetch response. Please try again.';
        chatBox.appendChild(errorMessage);
        maintainQuestionAnswerView();
    });
}

function handleMenuChoice(choice) {
    const chatBox = document.getElementById('chat-box');
    
    if (choice === 'c') {
        conversationState.waitingForMenuChoice = false;
        conversationState.inContinueMode = true;
        const botMessage = document.createElement('div');
        botMessage.className = 'bot-message';
        botMessage.textContent = "Continuing with general questions...";
        chatBox.appendChild(botMessage);
    } 
    else if (choice === 'm') {
        conversationState.waitingForMenuChoice = false;
        conversationState.showDomainQuestions = true;
        conversationState.inContinueMode = false;
        fetchDomainQuestions();
    }
    
    document.getElementById('user-input').value = '';
    maintainQuestionAnswerView();
}

function fetchDomainQuestions() {
    const chatBox = document.getElementById('chat-box');
    const typingIndicator = document.createElement('div');
    typingIndicator.className = 'bot-message';
    typingIndicator.textContent = 'Loading questions...';
    chatBox.appendChild(typingIndicator);
    maintainQuestionAnswerView();
    
    fetch("../Controllers/chatBotEmp.php", {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({message: "domain_questions"})
    })
    .then(response => response.json())
    .then(data => {
        chatBox.removeChild(typingIndicator);
        if (data.follow_up) showDomainQuestions(data.follow_up);
    })
    .catch(error => {
        chatBox.removeChild(typingIndicator);
        const errorMessage = document.createElement('div');
        errorMessage.className = 'bot-message error-message';
        errorMessage.textContent = 'Failed to load questions.';
        chatBox.appendChild(errorMessage);
        maintainQuestionAnswerView();
    });
}

function showDomainQuestions(questions) {
    const chatBox = document.getElementById('chat-box');
    
    const followUpDiv = document.createElement('div');
    followUpDiv.className = 'follow-up-questions';
    followUpDiv.innerHTML = '<p class="follow-up-title">Choose a question:</p>';
    
    const questionList = document.createElement('ol');
    questions.forEach((question, index) => {
        const listItem = document.createElement('li');
        //listItem.innerHTML = `<span class="question-number">${index + 1}.</span> ${question}`;
        listItem.innerHTML = `<span class="question-number"></span> ${question}`;
        listItem.style.cursor = 'pointer';
        listItem.style.color = '#007bff';
        listItem.addEventListener('click', () => {
            document.getElementById('user-input').value = question;
            sendMessage();
        });
        questionList.appendChild(listItem);
    });
    
    followUpDiv.appendChild(questionList);
    chatBox.appendChild(followUpDiv);
    maintainQuestionAnswerView();
}

function showMenuOptions() {
    const chatBox = document.getElementById('chat-box');
    
    const menuDiv = document.createElement('div');
    menuDiv.className = 'menu-options';
    menuDiv.innerHTML = `
        <p class="menu-title">What would you like to do next?</p>
        <ol>
            <li style="color: #007bff; cursor: pointer;">Press <strong>C</strong> to Continue with general questions</li>
            <li style="color: #007bff; cursor: pointer;">Press <strong>M</strong> to return to Main Menu</li>
        </ol>
    `;
    
    chatBox.appendChild(menuDiv);
    conversationState.waitingForMenuChoice = true;
    maintainQuestionAnswerView();
}

function formatResponse(text) {
    if (!text) return '';
    return text
        .replace(/(\*\*|__)(.*?)\1/g, '<strong>$2</strong>')
        .replace(/(\*|_)(.*?)\1/g, '<em>$2</em>')
        .replace(/`([^`]+)`/g, '<code>$1</code>')
        .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank">$1</a>')
        .replace(/\n/g, '<br>')
        .replace(/ {2,}/g, match => '&nbsp;'.repeat(match.length));
}

function handleUserInput() {
    const input = document.getElementById('user-input').value.trim();
    if (/^\d+$/.test(input)) {
        const index = parseInt(input) - 1;
        const followUps = document.querySelectorAll('.follow-up-questions li');
        if (followUps[index]) {
            document.getElementById('user-input').value = followUps[index].textContent;
        }
    }
    sendMessage();
}

// Event listeners
document.getElementById('send-button').addEventListener('click', handleUserInput);
document.getElementById('user-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') handleUserInput();
});