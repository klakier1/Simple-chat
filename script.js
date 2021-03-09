/* GLOBAL VARIABLES */

let _selcetedReciver = null;
let _allRecivers = null;

/**
 * Get users list on load
 */
window.addEventListener("load", getUsersList);

/**
 * Listener for send button
 */
document
  .querySelector("#chat-send-button")
  .addEventListener("click", function (event) {
    event.preventDefault();
    if (_selcetedReciver) {
      const msgElement = document.querySelector("#chat-input-text");
      const msgText = msgElement.value;
      if (msgText != "") {
        sendMessage(_selcetedReciver.id, msgText);
        msgElement.value = "";
      }
    }
  });

/**
 * Listener for message text input.
 * onKeyUp ENTER, performs click on send button
 */
document
  .querySelector("#chat-input-text")
  .addEventListener("keyup", function (event) {
    // Number 13 is the "Enter" key on the keyboard
    if (event.keyCode === 13) {
      // Cancel the default action, if needed
      event.preventDefault();
      // Trigger the button element with a click
      document.querySelector("#chat-send-button").click();
    }
  });

/**
 * Check for new message
 */
window.setTimeout(checkNewMsg, 500);

function checkNewMsg() {
  if (_selcetedReciver) {
    const lastMsg = document.querySelector(".chat").lastChild;
    if (lastMsg && lastMsg.id) {
      const lastMsgId = parseInt(lastMsg.id.replace("msg", ""), 10);
      getLastMessages(_selcetedReciver.id, lastMsgId);
    } else {
      getConversation(_selcetedReciver.id);
      setTimeout(checkNewMsg, 1000);
    }
  } else {
    setTimeout(checkNewMsg, 500);
  }
}

/**
 * Listener for click on user item,
 * attached to every item in function renderUserList
 * @param {*} element
 * @param {*} event
 * @param {*} user
 */
function selectReceiver(element, event, user) {
  if (_selcetedReciver) {
    const previousSelectedreceiver = document.querySelector(
      `#user${_selcetedReciver.login}`
    );
    if (previousSelectedreceiver)
      previousSelectedreceiver.classList.remove("chat-user-list-item-active");
  }
  _selcetedReciver = user;
  const selectedreceiver = document.querySelector(
    `#user${_selcetedReciver.login}`
  );
  selectedreceiver.classList.add("chat-user-list-item-active");
  getConversation(_selcetedReciver.id);
}

/* AJAX */

function getUsersList() {
  const req = new XMLHttpRequest();

  req.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      renderUserList(JSON.parse(this.responseText));
    }
  };
  req.open("GET", "./scripts/getUserList.php", true);
  req.send();
}

function sendMessage(reciverId, msg) {
  const req = new XMLHttpRequest();
  req.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log(this.responseText);
    }
  };
  req.open("POST", "./scripts/sendMessage.php", true);
  req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  req.send(`receiverId=${reciverId}&message=${msg}`);
}

/**
 * Gets whole conversation
 * @param {int|string} receiverId
 */
function getConversation(receiverId) {
  const req = new XMLHttpRequest();
  req.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log(this.responseText);
      const result = JSON.parse(this.responseText);
      //clear chat window
      document.querySelector(".chat").innerHTML = "";
      if (result.count > 0) renderConversation(result.msg);
    }
  };
  req.open("POST", "./scripts/receiveMessage.php", true);
  req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  req.send(`receiverId=${receiverId}`);
}
/**
 * Gets conversation from lastMessageId
 * @param {int|string} receiverId
 * @param {int|string} lastMessageId
 */
function getLastMessages(receiverId, lastMessageId) {
  const req = new XMLHttpRequest();
  req.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log(this.responseText);
      const result = JSON.parse(this.responseText);
      if (result.count > 0) renderConversation(result.msg);
      window.setTimeout(checkNewMsg, 500);
    }
  };
  req.open("POST", "./scripts/receiveMessage.php", true);
  req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  req.send(`receiverId=${receiverId}&lastMsgId=${lastMessageId}`);
}

/* RENDERING */

function renderConversation(messages) {
  messages.forEach((element) => {
    renderMessageDiv(element);
  });
}

function renderMessageDiv(message) {
  const chatRootDiv = document.querySelector(".chat");
  const item = document.createElement("div");
  const pUserName = document.createElement("p");
  pUserName.setAttribute("class", "chat-message-user-name");
  const pMessage = document.createElement("p");
  pMessage.setAttribute("class", "chat-message-text");
  if (message.sender_id == _currentUser.id) {
    item.setAttribute("class", "msgOutcoming");
    pUserName.innerHTML = `${_currentUser.login}`;
  } else {
    item.setAttribute("class", "msgIncoming");
    let sender = _allRecivers.find((obj) => {
      return obj.id === message.sender_id;
    });

    pUserName.innerHTML = `${sender.login}`;
  }
  pMessage.innerHTML = `${message.message}`;
  item.appendChild(pUserName);
  item.appendChild(pMessage);
  item.setAttribute("id", `msg${message.id}`);
  chatRootDiv.appendChild(item);
  chatRootDiv.scrollTop = chatRootDiv.lastChild.offsetTop;
}

function renderUserList(users) {
  const list = document.querySelector(".chat-user-list");
  //store all users
  _allRecivers = users.users;

  //create list of users
  users.users.forEach((user) => {
    //create <li> elements
    const item = document.createElement("li");
    const pUserName = document.createElement("p");
    pUserName.setAttribute("class", "char-user-list-name");
    const pUserEmail = document.createElement("p");
    pUserEmail.setAttribute("class", "char-user-list-email");
    item.setAttribute("class", "chat-user-list-item");
    pUserName.innerHTML = `${user.login}`;
    pUserEmail.innerHTML = `${user.email}`;
    item.appendChild(pUserName);
    item.appendChild(pUserEmail);
    item.setAttribute("id", `user${user.login}`);
    item.addEventListener("click", function (event) {
      selectReceiver(this, event, user);
    });
    //add to list
    list.appendChild(item);
  });
}
