<?php
session_start();

// Check if 'idnumber' is set and not empty
if (!isset($_POST['idnumber']) || empty($_POST['idnumber'])) {
    header("Location: index.html");
    exit();
}

// Assuming your database credentials
$host = "92.205.147.175";
$username_db = "momen";
$password_db = "MoMeN011**";
$database = "sharkawi_muc";

// Create a connection to the database
$conn = new mysqli($host, $username_db, $password_db, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 'idnumber' from the form
$username = $_POST['idnumber'];

// Fetch email from the users table where id equals $username
$sql = "SELECT email FROM users WHERE id = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the first row
    // Fetch the first row
    $row = $result->fetch_assoc();

    // Get the email
    $email = $row['email'];

    // Remove the last 20 characters
    $emailWithoutLast20 = substr($email, 0, -20);

    // Get the remaining characters
    $name = $emailWithoutLast20;
    $user_id = $_POST['idnumber'];
} else {
    echo "";
}

// Close the database connection when you're done
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat [<?php echo    $name; ?>]</title>

    <style>
  
    </style>
    <link rel="stylesheet" href="chatstyletemp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <link rel="stylesheet" href="popup.css">
    <link rel="stylesheet" href="Dropdown.css">
</head>

<body>
    <section id="popupbox">
        <span class="overlay"></span>

        <div class="modal-box">
            <i class="fa-solid fa-key"></i>

            <h2>Please enter the Key</h2>
            <br />

            <input class="returnable" id="returnable" type="text" placeholder="Enter the Key" />
            <br />
            <div class="popup-buttons">
                <button class="close-btn">Decrypt</button>
            </div>
        </div>
    </section>
    <div id="container"> 
        <div id="sidebar"  >
            <div class="muc-img-div">
                <img class="muc-img" src="Images/Group 2.svg" alt="">
            </div>
            <div id="welcome-message">Welcome,
                <?php echo    $name; ?>!
            </div>
            <div id="online-users-container">
            <label for="touch"><span class="touch-span" id="toggleIcon">Online Users</span></label>               
            <input type="checkbox" id="touch"> 
           
            <div class="search-div">
           
            <input type="text" class="search" id="search" placeholder="Search by ID">
            </div>
               <br>
                <div id="online-users">
                </div>
            </div>
            <h3 class="ConvTxt">Conversations</h3>
            <div id="channels" class="chann">

            </div>
            <div class="logout-container">

           
            <a href="logout.php" class="logout-button">Logout</a>
            </div>  </div>
        
        <div id="chat-container">
        <div class="sidebtndiv">
            <button id="sidebar-btn"><i class="fa-solid fa-arrow-right" id="side-arrow"></i>
</button>

            </div>
            <div id="chat-messages">
           
            </div>
            <div class="inputs-fields">
                <div class="message-input-div">
                <input type="text" id="message-input" placeholder="Type your message..." class="input">
                <input type="number" id="shift-input" min="0" placeholder="Key" class="input">
               
                <button id="send-button" class="button">Send</button>

                </div>
                
            </div>
        </div>
    </div>

    <script>
  

    // Replace the following URL with the one provided by Ngrok
    const ngrokUrl = 'ws://64.226.68.255:8080'; // Note the 'wss' for secure WebSocket connections

    // The WebSocket connection now uses the Ngrok URL
    // const ws = new WebSocket(ngrokUrl + '?username=<?php echo $name; ?>&user_id=<?php echo $user_id ?>');
    const ws = new WebSocket(ngrokUrl + '?username=<?php echo $name; ?>&user_id=<?php echo $user_id ?>');
        const userName = "<?php echo $name; ?>";
        const userId = "<?php echo $user_id; ?>";
        window.onload = async function() {
            await loadChannels();
            console.log("aaaa", window.current_channel_id);
            loadMessages(window.current_channel_id);
            document.getElementById('welcome-message').style.display = 'block';
            loadOnlineUsers();
        };



        function sendMessage() {
            const messageInput = document.getElementById('message-input');
            const categorySelect = document.getElementById('category-select');
            const shiftInput = document.getElementById('shift-input'); // Get the shift input
            const message = messageInput.value.trim();
            // const category = categorySelect.value || "General";
            shiftValue = parseInt(shiftInput.value) || 0; // Get the shift value or default to 3

            // Function to encrypt a message using Caesar cipher with the specified shift value
            function encryptMessage(text, shift) {
                return [...text]
                    .map(char => {
                        const charCode = char.charCodeAt(0);
                        if (charCode >= 65 && charCode <= 90) {
                            return String.fromCharCode((charCode - 65 + shift) % 26 + 65); // Uppercase letters
                        } else if (charCode >= 97 && charCode <= 122) {
                            return String.fromCharCode((charCode - 97 + shift) % 26 + 97); // Lowercase letters
                        } else {
                            return char; // Non-alphabetic characters
                        }
                    })
                    .join('');
            }
            if (message !== '') {
                const fullMessage = encryptMessage(message, shiftValue)
                const data = {
                    sender: encodeURIComponent(userName),
                    message: encodeURIComponent(fullMessage),
                    channel_id: encodeURIComponent(window.current_channel_id),
                    userid: encodeURIComponent(userId),
                };
                fetch('functions/insert_message.php', {
                        method: 'POST',
                        headers: {
                            'Content-type': 'application/x-www-form-urlencoded'
                        },
                        body: Object.keys(data).map(key => `${key}=${data[key]}`).join('&')
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    }).then(() => {
                        const x = JSON.stringify({
                            event: `message`,
                            data: {
                                sender: userName,
                                channel_id: window.current_channel_id,
                                message: message,
                                userid: userId,
                            }
                        })
                        ws.send(x);
                    })
                messageInput.value = '';
                loadChannels();
            }
        }
        // assume model is DB
        // model

        // view 
        function clearChatMessages() {
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.innerHTML = '';
        }
        onlineUsers = [];
        const searchInput = document.getElementById('search');
        let searchVal = ''
        // controller
        searchInput.addEventListener("keyup", function(e) {
            searchVal = e.target.value;
            const filteredOnlineUsers = onlineUsers.filter(user => {
                console.log("c1", user.user_id);
                console.log("c2", searchVal);
                return user.user_id.includes(searchVal);
            });
            console.log("aaaa", filteredOnlineUsers);
            updateOnlineUsers(filteredOnlineUsers);
        })



        function updateOnlineUsers(onlineUsers) {
    
            const onlineUsersElement = document.getElementById('online-users');
            onlineUsersElement.innerHTML = '<ul id="online-users-list"></ul>';

            const onlineUsersList = document.getElementById('online-users-list');
            const categorySelect = document.getElementById('category-select');
            const toggleIcon = document.getElementById('toggleIcon');

            const onlineuserscount=onlineUsers.length;
  const touch = document.getElementById('touch');
                touch.addEventListener('change', function() {
    // Update the height of the online-users-list element based on the checkbox state
    onlineUsersList.style.height = touch.checked ? onlineuserscount*47.4 +"px" : '0';
    toggleIcon.classList.toggle('minus', touch.checked);

  });

  const usersobserver = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type === 'childList') {
    onlineUsersList.style.height = touch.checked ? onlineuserscount*47.4 +"px" : '0';

      }
    });
  });

  const usersconfig = { childList: true, subtree: true };
  usersobserver.observe(onlineUsersList, usersconfig);



  onlineUsersList.addEventListener('change',function(){
  
  });

            console.log("aaaa", {
                onlineUsers
            });
            
            console.log(onlineUsers[1]);
            onlineUsers.filter(x => x.user_id !== userId).forEach(({
                name: username,
                user_id
            }) => {
                const listItem = document.createElement('li');
                const link = document.createElement('li');

                link.setAttribute('data-channel', username);
                link.setAttribute('data-username', username);
                link.setAttribute('data-userid', user_id);
                link.textContent = username + " " + user_id;
                link.setAttribute('class', 'online-user');

                link.addEventListener('click', function(e) {
                    const elem = e.target;
                    fetch("functions/create_channel.php", {
                        method: "POST",
                        body: JSON.stringify({
                            partcipants: [{
                                id: <?php echo $username; ?>,
                                name: "<?php echo $name; ?>"
                            }, {
                                id: +elem.getAttribute("data-userid"),
                                name: elem.getAttribute("data-username")
                            }]
                        })
                    }).then(response => response.json()).then(response => {
                        console.log({
                            response
                        })
                        if (response.message === "Already exists") {
                            window.current_channel_id = response.channel_id;
                            console.log(window.current_channel_id);
                            loadChannels();
                            loadMessages(window.current_channel_id);
                        } else {
                            loadChannels();
                            loadMessages()
                        }
                    })

                    clearChatMessages();

                });

                listItem.appendChild(link);
                onlineUsersList.appendChild(listItem);
            });
            
        }



        function appendMessage(message, userClass) {
            const chatMessages = document.getElementById('chat-messages');
            const messageContainer = document.createElement('div');
            messageContainer.className = `message-container ${userClass}`;

            const idChannelContainer = document.createElement('div');
            idChannelContainer.className = 'id-channel-container';

            const idChannelText = document.createElement('span');
            idChannelText.className = 'id-channel-text';
            idChannelText.textContent = message.split(': ')[0];

            const messageText = document.createElement('span');
            messageText.className = 'message-text';
            const encryptedMessage = message.split(': ').slice(1).join(':');
            messageText.textContent = encryptedMessage;

            messageContainer.addEventListener('click', () => {
                Togglemessage();
                returnable.focus();
            });

            //Popup box
            const closeBtn = document.querySelector(".close-btn");
            const section = document.getElementById("popupbox");
            const overlay = document.querySelector(".overlay");
            const retrunable = document.getElementById("returnable");

            function Togglemessage() {
                retrunable.addEventListener("keypress", (e) => {
                    if (e.key === "Enter") {
                        section.classList.remove("active");
                        setTimeout(() => {
                            section.style.display = "none";
                        }, 300);
                        if (messageText.textContent === encryptedMessage) {
                            let decrypt_shift = returnable.value;
                            // If the current content is the encrypted message, decrypt it
                            messageText.textContent = decryptMessage(encryptedMessage, decrypt_shift);
                            returnable.value = null;
                        }
                    }
                })
                if (messageText.textContent === encryptedMessage) {
                    section.style.display = "block";
                    setTimeout(() => {
                        section.classList.add("active");
                    }, 10);
                    closeBtn.addEventListener('click', function() {
                        section.classList.remove("active");
                        setTimeout(() => {
                            section.style.display = "none";
                        }, 300);


                        if (messageText.textContent === encryptedMessage) {

                            let decrypt_shift = returnable.value;
                            // If the current content is the encrypted message, decrypt it
                            messageText.textContent = decryptMessage(encryptedMessage, decrypt_shift);
                            returnable.value = null;
                        }
                    });

                } else {
                    // If the current content is decrypted, revert to the encrypted message
                    messageText.textContent = encryptedMessage;
                }

            }

            overlay.addEventListener(
                "click",
                () => (
                    section.classList.remove("active"),
                    setTimeout(() => {
                        section.style.display = "none";
                    }, 150)
                )
            );
            //End of popup box code


            idChannelContainer.appendChild(idChannelText);
            idChannelContainer.appendChild(document.createTextNode(':'));
            messageContainer.appendChild(idChannelContainer);
            messageContainer.appendChild(messageText);
            chatMessages.appendChild(messageContainer);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function decryptMessage(text, shift) {
            return [...text]
                .map(char => {
                    const charCode = char.charCodeAt(0);
                    if (charCode >= 65 && charCode <= 90) {
                        return String.fromCharCode(((charCode - 65 - shift + 26 * 999999999) % 26) + 65); // Uppercase letters
                    } else if (charCode >= 97 && charCode <= 122) {
                        return String.fromCharCode(((charCode - 97 - shift + 26 * 999999999) % 26) + 97); // Lowercase letters
                    } else {
                        return char; // Non-alphabetic characters
                    }
                })
                .join('');
        }


        document.getElementById('send-button').addEventListener('click', sendMessage);
        

        document.getElementById('message-input').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                sendMessage();
            }
        });
        document.getElementById('message-input').addEventListener('keydown', function(event) {
            var currentValue = parseInt(document.getElementById('shift-input').value) || 0;

    if (event.key === 'ArrowUp' ) {

        document.getElementById('shift-input').value = currentValue + 1;
    }
    if (event.key === 'ArrowDown') {
        if(currentValue>0)
        document.getElementById('shift-input').value = currentValue - 1;
    }

});


document.getElementById('message-input').addEventListener('wheel', function(event) {
    var currentValue = parseInt(document.getElementById('shift-input').value) || 0;

    // Detect whether the wheel event is scrolling up or down
    var delta = Math.sign(event.deltaY);

    if (delta > 0) {
        // Scrolling down
        if (currentValue > 0)
            document.getElementById('shift-input').value = currentValue - 1;
    } else if (delta < 0) {
        // Scrolling up
        document.getElementById('shift-input').value = currentValue + 1;
    }
});
        // ws.onopen(function() {
        //     console.log("Clinet Connected");
        // })
        ws.onmessage = function(e) {
            const {
                event,
                data
            } = JSON.parse(e.data);
            console.log("WebSocket Message Received:", e.data);

            switch (event) {
                case "online_users":
                    onlineUsers = data;
                    return updateOnlineUsers(data);
                case "message":
                    const {
                        sender, channel_id, message
                    } = data;
                    // appendMessage(`${sender} (${category}): ${message}`);
                    loadMessages(window.current_channel_id);
                    loadChannels()
                    break;
                default:
                    throw new Error("unhandled case");
            }
            
        };





        function renderChannels(channels) {


            const x = channels.filter(chann => chann.name != "");
            console.log(x);
            const channelsContainer = document.getElementById("channels");
            channelsContainer.innerHTML = "";
            x.forEach(channel => {
                const elem = document.createElement("div");
                elem.className = window.current_channel_id === channel.id ? "conversation-link current" : "conversation-link ";
                elem.textContent = channel.name.split("_").filter(x => x != userName)[0] || "self";
                elem.setAttribute("data-channel-id", channel.id);
                elem.addEventListener('click', function(event) {

                    event.preventDefault();
                    const channel_id = this.getAttribute('data-channel-id');
                    window.current_channel_id = channel_id;
                    loadChannels();
                    loadMessages(channel_id);
                    window.current_channel_id = channel_id;
                });
                channelsContainer.appendChild(elem);
            })
            
        }

        function loadChannels() {
            return fetch(`functions/get_channels.php`).then(res => res.json()).then(channels => {
                // Added Logic to filer Channels 
                console.log({
                    userId: +userId,
                    channels
                })
                const getMajorById = (userId) => {
                    switch (userId.substring(0, 4)) {
                        case "2211":
                            return "Physical Therapy";
                        case "2212":
                            return "Engineering";
                        case "2213":
                            return "Business";

                        default:
                            return "Employess";
                    }
                }
                const y = channels.filter(ch => ch.is_private ?
                    ch.participants.includes(+userId) :
                    ch.name === "General" ? true :
                    ch.name === getMajorById(userId) ? true : false
                );
                console.log("bbbbb", channels[0], channels[0].id)
                if (!window.current_channel_id)
                    window.current_channel_id = channels[0].id;
                renderChannels(y);
                // loadMessages(window.current_channel_id)
            })
            
        }

        function loadMessages(channel_id) {
            clearChatMessages();
            console.log(channel_id);
            fetch(`functions/get_messages.php?channel_id=${encodeURIComponent(channel_id)}`).then(res => res.json()).then(messages =>
                messages.forEach(function(message) {
                console.log(message);
                   const isCurrentUser = message.userid == userId;
                    const userClass = isCurrentUser ? 'user-message' : 'other-message';
                    appendMessage(`${message.sender} : ${message.message}`, userClass);
                })
            )
            // document.getElementById('category-select').value = channel;
        }


        function loadOnlineUsers() {
            ws.send("Get Online Users");
        }




        //
        const sidebarbtn = document.getElementById('sidebar-btn');
        const sidebar = document.getElementById('sidebar');

        const toggleIcon = document.getElementById('toggleIcon');
      const chann = document.getElementById('channels')
        const welcomemessage = document.getElementById('welcome-message');
        const ConvTxt = document.querySelector('.ConvTxt');
        const sidearrow = document.getElementById('side-arrow');

        
        

        sidebarbtn.addEventListener("click", function(e) {
            if (window.innerWidth <= 480) {
                if (sidebar.style.width === "63%"){
            sidebar.style.width="0px";
            sidebar.style.padding="0px"
            toggleIcon.style.fontSize ="1em";
            ConvTxt.style.fontSize ="16px";
            chann.classList.toggle('hide-scrollbar');
            sidearrow.style.transform="rotate(0deg)";
       console.log(conversationLinks); // Check what elements are selected
   
        }
        else if (sidebar.style.width !== "63%")  {
            sidebar.style.width="63%";
            sidebar.style.padding="30px"
            toggleIcon.style.fontSize ="1.2em";
            ConvTxt.style.fontSize ="16px";
            chann.classList.remove('hide-scrollbar');
            sidearrow.style.transform="rotate(-180deg)";


        }
  } else {
    
    if (sidebar.style.width === "250px"){
            sidebar.style.width="100px";
            sidebar.style.padding="15px"
            toggleIcon.style.fontSize ="1em";
            ConvTxt.style.fontSize ="15px";
            chann.classList.toggle('hide-scrollbar');
            sidearrow.style.transform="rotate(0deg)";
       console.log(conversationLinks); // Check what elements are selected
   
        }
        else if (sidebar.style.width !== "250px")  {
            sidebar.style.width="250px";
            sidebar.style.padding="30px"
            toggleIcon.style.fontSize ="1.2em";
            ConvTxt.style.fontSize ="16px";
            chann.classList.remove('hide-scrollbar');
            sidearrow.style.transform="rotate(-180deg)";


        }
  }
    
    
        })

function startofchat(){
    if (window.innerWidth <= 480) {
            sidebar.style.width="0px";
            sidebar.style.padding="0px" 
            sidebar.style.marginLeft = "0px";        }
        else{
            sidebar.style.width="250px";
            sidebar.style.padding="30px"
            sidebar.style.marginLeft = "7px"; 


        }

};


startofchat()
                     
    </script>
</body>

</html>