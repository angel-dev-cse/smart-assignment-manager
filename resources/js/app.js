import "./bootstrap";

import Alpine from "alpinejs";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Alpine = Alpine;
Alpine.start();

window.Pusher = Pusher;

Echo.debug = true;

document.addEventListener("DOMContentLoaded", function () {
    let activeChatPopup = null; // To track the currently active chat popup

    // Show and hide chat pop-up
    document.querySelectorAll(".open-chat-popup").forEach((link) => {
        link.addEventListener("click", async (event) => {
            event.preventDefault();

            const currentUserId = $("html").data("current-user-id");
            const recipientId = link.getAttribute("data-recipient");
            let recipientName = null;
            // get the recipient name
            try {
                const response = await fetch(`/get-user/${recipientId}`);
                const data = await response.json();
                recipientName = data.name;
                console.log(recipientName);
            } catch (error) {
                console.log("Couldn't fetch recipient", error);
            }

            let messageContainer = null;
            let chatId = null;
            let senderId = null;
            let senderName = null;
            let lastMessage = null;

            function formatDate(createdAt) {
                const options = {
                    hour: "numeric",
                    minute: "numeric",
                    hour12: true,
                };
                const formattedDate = `${createdAt.getDate()} ${createdAt.toLocaleString(
                    "default",
                    { month: "short" }
                )}, ${createdAt.toLocaleString("en-US", options)}`;
                return formattedDate;
            }

            async function getChatData($chatId) {
                try {
                    const response = await fetch(`/get-chat-data/${chatId}`);
                    let data = await response.json();

                    senderId = data.sender.id;
                    senderName = data.sender.name;
                    lastMessage = data.lastMessage;
                } catch (error) {
                    console.log("Error featching chat data", error);
                }
            }

            // get the chat ID
            async function getChatId($recipientId) {
                try {
                    const response = await fetch(`/get-chat-id/${recipientId}`);
                    const data = await response.json();
                    chatId = data.chatId;

                    // attach listener if it's not null
                    if (chatId) {
                        attachListener(chatId);
                    }

                    console.log("Chat ID : ", chatId);

                    if (chatId) {
                        await getChatData(chatId);
                    }
                } catch (err) {
                    console.log("Error fetching the chat ID", err);
                }
            }

            function attachListener(id) {
                window.Echo.private(`chat.${id}`).listen("SendMessage", (e) => {
                    console.log(e);
                    let message = e.message;
                    let createdAt = new Date(message.created_at);
                    let formattedDate = formatDate(createdAt);
                    let messageHeader = null;
                    let messageBody = null;

                    if (message.user_id === currentUserId) {
                        messageHeader = $(
                            '<div class="d-flex justify-content-between mt-3">'
                        )
                            .append(
                                `<p class="small mb-1 text-muted">${formattedDate}</p>`
                            )
                            .append(
                                `<p class="small mb-1">${message.user.name}</p>`
                            );

                        messageBody = $(
                            '<div class="d-flex flex-row justify-content-end mb-3">'
                        ).append(`<div>
                                        <p class="small p-2 mx-3 rounded-3 bg-secondary text-break">${message.content}</p>
                                    </div>`)
                            .append(`<img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                                        alt="avatar 1" style="width: 45px; height: 100%;">`);
                    } else {
                        messageHeader = $(
                            '<div class="d-flex justify-content-between mt-3">'
                        )
                            .append(
                                `<p class="small mb-1">${message.user.name}</p>`
                            )
                            .append(
                                `<p class="small mb-1 text-muted">${formattedDate}</p>`
                            );
                        messageBody = $(
                            '<div class="d-flex flex-row justify-content-start mb-3">'
                        )
                            .append(`<img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                                        alt="avatar 1" style="width: 45px; height: 100%;">`)
                            .append(`<div>
                                        <p class="small p-2 mx-3 rounded-3 text-break" style="background-color: #f5f6f7;">${message.content}</p>
                                    </div>`);
                    }

                    messageContainer.append(messageHeader);
                    messageContainer.append(messageBody);
                    messageContainer.scrollTop(
                        messageContainer[0].scrollHeight
                    );
                });
            }

            async function createChatId() {
                try {
                    const response = await fetch("/create-chat", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr(
                                "content"
                            ),
                        },
                        body: JSON.stringify({ recipient_id: recipientId }), // Pass user2_id
                    });

                    const data = await response.json();
                    chatId = data.chatId; // Return the new chat ID
                    console.log("New chat id", chatId);
                    attachListener(chatId); // Attach the listener
                } catch (error) {
                    console.log("Error creating chat", error);
                    return null;
                }
            }

            async function populateMessages(id) {
                console.log("Populating messages");
                try {
                    const response = await fetch(`/chats/${id}`);
                    const data = await response.json();
                    let messages = data.messages;
                    // console.log(messages);

                    messages.forEach((message) => {
                        let messageHeader = null;
                        let messageBody = null;

                        const createdAt = new Date(message.created_at);
                        let formattedDate = formatDate(createdAt);

                        if (currentUserId === message.user_id) {
                            messageHeader = $(
                                '<div class="d-flex justify-content-between mt-3">'
                            )
                                .append(
                                    `<p class="small mb-1 text-muted">${formattedDate}</p>`
                                )
                                .append(
                                    `<p class="small mb-1">${message.user.name}</p>`
                                );

                            messageBody = $(
                                '<div class="d-flex flex-row justify-content-end mb-3">'
                            ).append(`<div>
                                        <p class="small p-2 mx-3 rounded-3 bg-secondary text-break">${message.content}</p>
                                    </div>`)
                                .append(`<img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                                        alt="avatar 1" style="width: 45px; height: 100%;">`);
                        } else {
                            messageHeader = $(
                                '<div class="d-flex justify-content-between mt-3">'
                            )
                                .append(
                                    `<p class="small mb-1">${message.user.name}</p>`
                                )
                                .append(
                                    `<p class="small mb-1 text-muted">${formattedDate}</p>`
                                );
                            messageBody = $(
                                '<div class="d-flex flex-row justify-content-start mb-3">'
                            )
                                .append(`<img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                                        alt="avatar 1" style="width: 45px; height: 100%;">`)
                                .append(`<div>
                                        <p class="small p-2 mx-3 rounded-3 text-break" style="background-color: #f5f6f7;">${message.content}</p>
                                    </div>`);
                        }

                        messageContainer.append(messageHeader);
                        messageContainer.append(messageBody);
                    });
                } catch (err) {
                    console.log("Error fetching the chat details", err);
                }
            }

            function showChatWindow() {
                // If an active chat popup exists, remove it before adding a new on
                document.body.appendChild(chatPopup);
                chatPopup.style.display = "block";
                messageContainer.scrollTop(messageContainer[0].scrollHeight);
                activeChatPopup = chatPopup;
            }

            // START - Cloning the template
            if (activeChatPopup) {
                activeChatPopup.remove();
            }

            const chatPopupTemplate = document.getElementById(
                "chat-popup-template"
            );
            const chatPopup = chatPopupTemplate.cloneNode(true);
            $(chatPopup).find("#chat-header").text(recipientName);
            messageContainer = $(chatPopup).find("#message-container");

            // handle the close icon
            $(chatPopup)
                .find("#close-chat-popup")
                .on("click", () => {
                    if (activeChatPopup) {
                        activeChatPopup.remove();
                        activeChatPopup = null;
                    }
                });

            await getChatId(recipientId);

            // Populate chat content using recipientId
            if (chatId) {
                await populateMessages(chatId);
            } else {
                messageContainer.append("Send A Message!");
            }

            showChatWindow();

            // Send and receive messages using Echo
            const messageInput = $(activeChatPopup).find("#message-input");
            const sendButton = $(activeChatPopup).find("#send-button");

            sendButton.on("click", async () => {
                const messageContent = messageInput.val().trim();
                const filePath = null;

                if (!chatId) {
                    console.log("Creating new chat id");
                    await createChatId(); // attach listener inside of it
                } 

                if (messageContent) {
                    try {
                        const response = await fetch(`/chats/${chatId}/send`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                Accept: "application/json",
                                "X-CSRF-TOKEN": $(
                                    "meta[name='csrf-token']"
                                ).attr("content"),
                            },
                            body: JSON.stringify({
                                content: messageContent,
                                file: filePath,
                            }),
                        });

                        if (response.ok) {
                            messageInput.val("");
                        } else {
                            console.log("Failed to send the message");
                        }
                    } catch (error) {
                        console.log("Error sending the message", error);
                    }
                }

                messageContainer.scrollTop(messageContainer[0].scrollHeight);
            });
        });
    });

    // ... Your Echo setup and message handling ...
});
