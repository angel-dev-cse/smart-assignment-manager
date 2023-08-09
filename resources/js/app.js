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
    let chatId = null;
    let messageContainer = null;
    const currentUserId = $("html").data("current-user-id");
    let recipientName = null;

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

    // Show and hide chat pop-up
    document.querySelectorAll(".open-chat-popup").forEach((link) => {
        link.addEventListener("click", async (event) => {
            event.preventDefault();
            const recipientId = link.getAttribute("data-recipient");

            const chatPopupTemplate = document.getElementById(
                "chat-popup-template"
            );

            // Clone the template
            const chatPopup = chatPopupTemplate.cloneNode(true);
            messageContainer = $(chatPopup).find("#message-container");

            // get the chat ID
            async function getChatId($recipientId) {
                try {
                    const response = await fetch(`/get-chat-id/${recipientId}`);
                    const data = await response.json();
                    chatId = data.chatId;
                } catch (err) {
                    console.log("Error fetching the chat ID", err);
                }
            }

            async function showChat(id) {
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
                            recipientName = message.user.name;

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

                    chatPopup.style.display = "block";
                } catch (err) {
                    console.log("Error fetching the chat details", err);
                }
            }

            await getChatId(recipientId);

            // Populate chat content using recipientId
            if (chatId) {
                console.log(chatId);
                await showChat(chatId);
            }

            // If an active chat popup exists, remove it before adding a new one
            if (activeChatPopup) {
                activeChatPopup.remove();
            }

            document.body.appendChild(chatPopup);
            messageContainer.scrollTop(messageContainer[0].scrollHeight);
            activeChatPopup = chatPopup;

            $(activeChatPopup)
                .find("#close-chat-popup")
                .on("click", () => {
                    if (activeChatPopup) {
                        activeChatPopup.remove();
                        activeChatPopup = null;
                    }
                });

            $(activeChatPopup).find("#chat-header").text(recipientName);

            // Send and receive messages using Echo
            const messageInput = $(activeChatPopup).find("#message-input");
            const sendButton = $(activeChatPopup).find("#send-button");

            sendButton.on("click", async () => {
                const messageContent = messageInput.val().trim();
                const filePath = null;

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

            window.Echo.private(`chat.${chatId}`).listen("SendMessage", (e) => {
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
                messageContainer.scrollTop(messageContainer[0].scrollHeight);
            });
        });
    });

    // ... Your Echo setup and message handling ...
});
