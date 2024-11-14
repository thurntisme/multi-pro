(function () {
  var dummyUserImage = "assets/images/users/user-dummy-img.jpg";
  var dummyMultiUserImage = "assets/images/users/multi-user.jpg";
  var isreplyMessage = false;

  // favourite btn
  document.querySelectorAll(".favourite-btn").forEach(function (item) {
    item.addEventListener("click", function (event) {
      this.classList.toggle("active");
    });
  });

  // toggleSelected
  function toggleSelected() {
    var userChatElement = document.querySelectorAll(".user-chat");
    Array.from(document.querySelectorAll(".chat-user-list li a")).forEach(
      function (item) {
        item.addEventListener("click", function (event) {
          userChatElement.forEach(function (elm) {
            elm.classList.add("user-chat-show");
          });

          // chat user list link active
          var chatUserList = document.querySelector(
            ".chat-user-list li.active"
          );
          if (chatUserList) chatUserList.classList.remove("active");
          this.parentNode.classList.add("active");
        });
      }
    );

    // user-chat-remove
    document.querySelectorAll(".user-chat-remove").forEach(function (item) {
      item.addEventListener("click", function (event) {
        userChatElement.forEach(function (elm) {
          elm.classList.remove("user-chat-show");
        });
      });
    });
  }

  //User current Id
  var currentChatId = "users-chat";
  var url = "assets/json/";
  var usersList = "";
  var userChatId = 1;

  scrollToBottom(currentChatId);

  //user list by json
  var getJSON = function (jsonurl, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", url + jsonurl, true);
    xhr.responseType = "json";
    xhr.onload = function () {
      var status = xhr.status;
      if (status === 200) {
        callback(null, xhr.response);
      } else {
        callback(status, xhr.response);
      }
    };
    xhr.send();
  };

  // get User list
  getJSON("chat-users-list.json", function (err, data) {
    if (err !== null) {
      console.log("Something went wrong: " + err);
    } else {
      // set users message list
      var users = data[0].users;
      usersList = data[0].users;
      users.forEach(function (userData, index) {
        var isUserProfile = userData.profile
          ? '<img src="' +
            userData.profile +
            '" class="rounded-circle img-fluid userprofile" alt=""><span class="user-status"></span>'
          : '<div class="avatar-title rounded-circle bg-primary text-white fs-10">' +
            userData.nickname +
            '</div><span class="user-status"></span>';

        var messageCount = '<a href="javascript: void(0);">';
        var activeClass = userData.id === 1 ? "active" : "";
        document.getElementById("userList").innerHTML +=
          '<li id="contact-id-' +
          userData.id +
          '" data-name="character" class="' +
          activeClass +
          '">\
                ' +
          messageCount +
          ' \
                <div class="d-flex align-items-center">\
                    <div class="flex-shrink-0 chat-user-img ' +
          userData.status +
          ' align-self-center me-2 ms-0">\
                        <div class="avatar-xxs">\
                        ' +
          isUserProfile +
          '\
                        </div>\
                    </div>\
                    <div class="flex-grow-1 overflow-hidden">\
                        <p class="text-truncate mb-0">' +
          userData.name +
          "</p>\
                    </div>\
                </div>\
            </a>\
        </li>";
      });

      // set channels list
      var channelsData = data[0].channels;
      channelsData.forEach(function (isChannel, index) {
        var messageCount = '<a href="javascript: void(0);">';
        document.getElementById("channelList").innerHTML +=
          '<li id="contact-id-' +
          isChannel.id +
          '" data-name="channel">\
            ' +
          messageCount +
          ' \
                <div class="d-flex align-items-center">\
                    <div class="flex-shrink-0 chat-user-img align-self-center me-2 ms-0">\
                        <div class="avatar-xxs">\
                            <div class="avatar-title bg-light rounded-circle text-body">#</div>\
                        </div>\
                    </div>\
                    <div class="flex-grow-1 overflow-hidden">\
                        <p class="text-truncate mb-0">' +
          isChannel.name +
          "</p>\
                    </div>\
                    <div>\
                    </div>\
                    </div>\
            </a>\
        </li>";
      });
    }
    toggleSelected();
    chatSwap();
  });

  // getMeg
  function getMsg(id, msg, has_images, has_files, has_dropDown) {
    var msgHTML = '<div class="ctext-wrap">';
    if (msg != null) {
      msgHTML +=
        '<div class="ctext-wrap-content" id=' +
        id +
        '><p class="mb-0 ctext-content">' +
        msg +
        "</p></div>";
    } else if (has_images && has_images.length > 0) {
      msgHTML += '<div class="message-img mb-0">';
      for (i = 0; i < has_images.length; i++) {
        msgHTML +=
          '<div class="message-img-list">\
                <div>\
                    <a class="popup-img d-inline-block" href="' +
          has_images[i] +
          '">\
                        <img src="' +
          has_images[i] +
          '" alt="" class="rounded border">\
                    </a>\
                </div>\
                <div class="message-img-link">\
                <ul class="list-inline mb-0">\
                    <li class="list-inline-item dropdown">\
                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
                            <i class="ri-more-fill"></i>\
                        </a>\
                        <div class="dropdown-menu">\
                            <a class="dropdown-item" href="' +
          has_images[i] +
          '" download=""><i class="ri-download-2-line me-2 text-muted align-bottom"></i>Download</a>\
                            <a class="dropdown-item" href="#"><i class="ri-reply-line me-2 text-muted align-bottom"></i>Reply</a>\
                            <a class="dropdown-item" href="#"><i class="ri-share-line me-2 text-muted align-bottom"></i>Forward</a>\
                            <a class="dropdown-item" href="#"><i class="ri-bookmark-line me-2 text-muted align-bottom"></i>Bookmark</a>\
                            <a class="dropdown-item delete-image" href="#"><i class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>\
                        </div>\
                    </li>\
                </ul>\
                </div>\
            </div>';
      }
      msgHTML += "</div>";
    } else if (has_files.length > 0) {
      msgHTML +=
        '<div class="ctext-wrap-content">\
            <div class="p-3 border-primary border rounded-3">\
            <div class="d-flex align-items-center attached-file">\
                <div class="flex-shrink-0 avatar-sm me-3 ms-0 attached-file-avatar">\
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle font-size-20">\
                        <i class="ri-attachment-2"></i>\
                    </div>\
                </div>\
                <div class="flex-grow-1 overflow-hidden">\
                    <div class="text-start">\
                        <h5 class="font-size-14 mb-1">design-phase-1-approved.pdf</h5>\
                        <p class="text-muted text-truncate font-size-13 mb-0">12.5 MB</p>\
                    </div>\
                </div>\
                <div class="flex-shrink-0 ms-4">\
                    <div class="d-flex gap-2 font-size-20 d-flex align-items-start">\
                        <div>\
                            <a href="#" class="text-muted">\
                                <i class="bx bxs-download"></i>\
                            </a>\
                        </div>\
                    </div>\
                </div>\
            </div>\
            </div>\
        </div>';
    }
    if (has_dropDown === true) {
      msgHTML +=
        '<div class="dropdown align-self-start message-box-drop">\
                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
                    <i class="ri-more-2-fill"></i>\
                </a>\
                <div class="dropdown-menu">\
                    <a class="dropdown-item reply-message" href="#"><i class="ri-reply-line me-2 text-muted align-bottom"></i>Reply</a>\
                    <a class="dropdown-item" href="#"><i class="ri-share-line me-2 text-muted align-bottom"></i>Forward</a>\
                    <a class="dropdown-item copy-message" href="#"><i class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>\
                    <a class="dropdown-item" href="#"><i class="ri-bookmark-line me-2 text-muted align-bottom"></i>Bookmark</a>\
                    <a class="dropdown-item delete-item" href="#"><i class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>\
                </div>\
            </div>';
    }
    msgHTML += "</div>";
    return msgHTML;
  }

  function updateSelectedChat() {
    var msgHTML =
      '<li class="chat-list left" id=1>                        <div class="conversation-list"><div class="user-chat-content"><div class="ctext-wrap"><div class="ctext-wrap-content" id=1><p class="mb-0 ctext-content">Good morning 😊</p></div><div class="dropdown align-self-start message-box-drop">                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">                    <i class="ri-more-2-fill"></i>                </a>                <div class="dropdown-menu">                    <a class="dropdown-item reply-message" href="#"><i class="ri-reply-line me-2 text-muted align-bottom"></i>Reply</a>                    <a class="dropdown-item" href="#"><i class="ri-share-line me-2 text-muted align-bottom"></i>Forward</a>                    <a class="dropdown-item copy-message" href="#"><i class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>                    <a class="dropdown-item" href="#"><i class="ri-bookmark-line me-2 text-muted align-bottom"></i>Bookmark</a>                    <a class="dropdown-item delete-item" href="#"><i class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>                </div>            </div></div><div class="conversation-name"><span class="d-none name">Lisa Parker</span><small class="text-muted time">09:07 am</small> <span class="text-success check-message-icon"><i class="bx bx-check-double"></i></span></div></div>                </div>            </li>';
    document.getElementById("users-conversation").innerHTML = msgHTML;
    deleteMessage();
    deleteImage();
    replyMessage();
    copyMessage();
    copyClipboard();
    scrollToBottom("users-chat");
    updateLightbox();
    document.getElementById("elmLoader").style.display = "none";
  }
  updateSelectedChat();

  // GLightbox Popup
  function updateLightbox() {
    var lightbox = GLightbox({
      selector: ".popup-img",
      title: false,
    });
  }

  // // Scroll to Bottom
  function scrollToBottom(id) {
    setTimeout(function () {
      var simpleBar = document
        .getElementById(id)
        .querySelector("#chat-conversation .simplebar-content-wrapper")
        ? document
            .getElementById(id)
            .querySelector("#chat-conversation .simplebar-content-wrapper")
        : "";

      var offsetHeight = document.getElementsByClassName(
        "chat-conversation-list"
      )[0]
        ? document
            .getElementById(id)
            .getElementsByClassName("chat-conversation-list")[0].scrollHeight -
          window.innerHeight +
          335
        : 0;
      if (offsetHeight)
        simpleBar.scrollTo({
          top: offsetHeight,
          behavior: "smooth",
        });
    }, 100);
  }

  //chat form
  var chatForm = document.querySelector("#chatinput-form");
  var chatInput = document.querySelector("#chat-input");
  var chatInputfeedback = document.querySelector(".chat-input-feedback");

  function currentTime() {
    var ampm = new Date().getHours() >= 12 ? "pm" : "am";
    var hour =
      new Date().getHours() > 12
        ? new Date().getHours() % 12
        : new Date().getHours();
    var minute =
      new Date().getMinutes() < 10
        ? "0" + new Date().getMinutes()
        : new Date().getMinutes();
    if (hour < 10) {
      return "0" + hour + ":" + minute + " " + ampm;
    } else {
      return hour + ":" + minute + " " + ampm;
    }
  }
  setInterval(currentTime, 1000);

  var messageIds = 0;

  if (chatForm) {
    //add an item to the List, including to local storage
    chatForm.addEventListener("submit", function (e) {
      e.preventDefault();

      var currMsgId = `m-${new Date().getTime()}`;
      var chatId = currentChatId;
      var chatReplyId = currentChatId;
      var contactId = document.querySelector(
        ".user-chat-topbar #contact_id"
      ).value;

      var chatInputValue = chatInput.value;

      if (chatInputValue.length === 0) {
        chatInputfeedback.classList.add("show");
        setTimeout(function () {
          chatInputfeedback.classList.remove("show");
        }, 2000);
      } else {
        if (isreplyMessage == true) {
          getReplyChatList(chatReplyId, chatInputValue);
          isreplyMessage = false;
        } else {
          appendMyChatMsg(currMsgId, chatInputValue);
        }
        getMsgResponse(contactId, currMsgId, chatInputValue);
        scrollToBottom(chatId || chatReplyId);
      }
      chatInput.value = "";

      //reply msg remove textarea
      document.getElementById("close_toggle").click();
    });
  }

  //user Name and user Profile change on click
  function chatSwap() {
    document
      .querySelectorAll("#userList li, #channelList li")
      .forEach(function (item) {
        item.addEventListener("click", function () {
          document.getElementById("elmLoader").style.display = "flex";
          var modelType = item.getAttribute("data-name");
          var username = item.querySelector(".text-truncate").innerHTML;
          document.querySelector(
            ".user-chat-topbar .text-truncate .username"
          ).innerHTML = username;
          var modelAvatar = document.querySelector(
            ".user-chat-topbar .avatar-xs"
          );
          var contactId = item.getAttribute("id");
          document.querySelector(".user-chat-topbar #contact_id").value =
            contactId;
          if (modelType === "character") {
            var userProfile =
              document
                .getElementById(contactId)
                .querySelector(".userprofile")
                ?.getAttribute("src") ?? "";
            modelAvatar.setAttribute("src", userProfile);
          } else {
            modelAvatar.setAttribute(
              "src",
              "assets/images/users/multi-user.jpg"
            );
          }
          document.getElementById("users-conversation").innerHTML = "";
          setTimeout(function () {
            updateSelectedChat();
          }, 200);
        });
      });
  }

  //Copy Message to clipboard
  function copyMessage() {
    var copyMessage = document.querySelectorAll(".copy-message");
    copyMessage.forEach(function (item) {
      item.addEventListener("click", function () {
        var isText = item.closest(".ctext-wrap").children[0]
          ? item.closest(".ctext-wrap").children[0].children[0].innerText
          : "";
        navigator.clipboard.writeText(isText);
      });
    });
  }

  //Copy Message Alert
  function copyClipboard() {
    var copyClipboardAlert = document.querySelectorAll(".copy-message");
    copyClipboardAlert.forEach(function (item) {
      item.addEventListener("click", function () {
        document.getElementById("copyClipBoard").style.display = "block";
        document.getElementById("copyClipBoardChannel").style.display = "block";
        setTimeout(hideclipboard, 1000);
        function hideclipboard() {
          document.getElementById("copyClipBoard").style.display = "none";
          document.getElementById("copyClipBoardChannel").style.display =
            "none";
        }
      });
    });
  }

  //Delete Message
  function deleteMessage() {
    var deleteItems = document.querySelectorAll(".delete-item");
    deleteItems.forEach(function (item) {
      item.addEventListener("click", function () {
        item.closest(".user-chat-content").childElementCount == 2
          ? item.closest(".chat-list").remove()
          : item.closest(".ctext-wrap").remove();
      });
    });
  }

  //Delete Image
  function deleteImage() {
    var deleteImage = document.querySelectorAll(
      ".chat-conversation-list .chat-list"
    );
    deleteImage.forEach(function (item) {
      item.querySelectorAll(".delete-image").forEach(function (subitem) {
        subitem.addEventListener("click", function () {
          subitem.closest(".message-img").childElementCount == 1
            ? subitem.closest(".chat-list").remove()
            : subitem.closest(".message-img-list").remove();
        });
      });
    });
  }
  deleteImage();

  //Reply Message
  function replyMessage() {
    var replyMessage = document.querySelectorAll(".reply-message");
    var replyToggleOpen = document.querySelector(".replyCard");
    var replyToggleClose = document.querySelector("#close_toggle");

    replyMessage.forEach(function (item) {
      item.addEventListener("click", function () {
        isreplyMessage = true;
        replyToggleOpen.classList.add("show");
        replyToggleClose.addEventListener("click", function () {
          replyToggleOpen.classList.remove("show");
        });

        var replyMsg =
          item.closest(".ctext-wrap").children[0].children[0].innerText;
        document.querySelector(
          ".replyCard .replymessage-block .flex-grow-1 .mb-0"
        ).innerText = replyMsg;
        var replyuser = document.querySelector(
          ".user-chat-topbar .text-truncate .username"
        ).innerHTML;
        var msgOwnerName = item.closest(".chat-list")
          ? item.closest(".chat-list").classList.contains("left")
            ? replyuser
            : "You"
          : replyuser;
        document.querySelector(
          ".replyCard .replymessage-block .flex-grow-1 .conversation-name"
        ).innerText = msgOwnerName;
      });
    });
  }

  //Append New Message
  var appendMyChatMsg = function (currMsgId, chatItems) {
    var itemList = document.querySelector(".chat-conversation-list");

    if (chatItems != null) {
      itemList.insertAdjacentHTML(
        "beforeend",
        '<li class="chat-list right" id="' +
          currMsgId +
          '" >\
                <div class="conversation-list">\
                    <div class="user-chat-content">\
                        <div class="ctext-wrap">\
                            <div class="ctext-wrap-content">\
                                <p class="mb-0 ctext-content">\
                                    ' +
          chatItems +
          '\
                                </p>\
                            </div>\
                            <div class="dropdown align-self-start message-box-drop">\
                                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
                                    <i class="ri-more-2-fill"></i>\
                                </a>\
                                <div class="dropdown-menu">\
                                    <a class="dropdown-item reply-message" href="#"><i class="ri-reply-line me-2 text-muted align-bottom"></i>Reply</a>\
                                    <a class="dropdown-item" href="#"><i class="ri-share-line me-2 text-muted align-bottom"></i>Forward</a>\
                                    <a class="dropdown-item copy-message" href="#""><i class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>\
                                    <a class="dropdown-item" href="#"><i class="ri-bookmark-line me-2 text-muted align-bottom"></i>Bookmark</a>\
                                    <a class="dropdown-item delete-item" href="#"><i class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>\
                            </div>\
                        </div>\
                    </div>\
                    <div class="conversation-name">\
                        <small class="text-muted time">' +
          currentTime() +
          '</small>\
                        <span class="text-success check-message-icon"><i class="bx bx-check"></i></span>\
                    </div>\
                </div>\
            </div>\
        </li>'
      );
    }

    // remove chat list
    var newChatList = document.getElementById(currMsgId);
    newChatList.querySelectorAll(".delete-item").forEach(function (subitem) {
      subitem.addEventListener("click", function () {
        itemList.removeChild(newChatList);
      });
    });

    //Copy Message
    newChatList.querySelectorAll(".copy-message").forEach(function (subitem) {
      subitem.addEventListener("click", function () {
        var currentValue =
          newChatList.childNodes[1].firstElementChild.firstElementChild
            .firstElementChild.firstElementChild.innerText;
        navigator.clipboard.writeText(currentValue);
      });
    });

    //Copy Clipboard alert
    newChatList.querySelectorAll(".copy-message").forEach(function (subitem) {
      subitem.addEventListener("click", function () {
        document.getElementById("copyClipBoard").style.display = "block";
        setTimeout(hideclipboardNew, 1000);

        function hideclipboardNew() {
          document.getElementById("copyClipBoard").style.display = "none";
        }
      });
    });

    //reply Message model
    newChatList.querySelectorAll(".reply-message").forEach(function (subitem) {
      subitem.addEventListener("click", function () {
        var replyToggleOpenNew = document.querySelector(".replyCard");
        var replyToggleCloseNew = document.querySelector("#close_toggle");
        var replyMessageNew =
          subitem.closest(".ctext-wrap").children[0].children[0].innerText;
        var replyUserNew = document.querySelector(
          ".replyCard .replymessage-block .flex-grow-1 .conversation-name"
        ).innerHTML;
        isreplyMessage = true;
        replyToggleOpenNew.classList.add("show");
        replyToggleCloseNew.addEventListener("click", function () {
          replyToggleOpenNew.classList.remove("show");
        });
        var msgOwnerName = subitem.closest(".chat-list")
          ? subitem.closest(".chat-list").classList.contains("left")
            ? replyUserNew
            : "You"
          : replyUserNew;
        document.querySelector(
          ".replyCard .replymessage-block .flex-grow-1 .conversation-name"
        ).innerText = msgOwnerName;
        document.querySelector(
          ".replyCard .replymessage-block .flex-grow-1 .mb-0"
        ).innerText = replyMessageNew;
      });
    });
  };

  //Append Response Message
  function getMsgResponse(contactId, currMsgId, keyword) {
    console.log(contactId);
    var lastMsg = document.getElementById(currMsgId);
    $.ajax({
      url: "ai-model/response.php",
      type: "POST",
      data: { contactId, keyword },
      beforeSend: function () {
        lastMsg
          .querySelector(".check-message-icon i")
          .classList.remove("bx-check");
        lastMsg
          .querySelector(".check-message-icon i")
          .classList.add("bx-check-double");
      },
      success: function (response) {
        var itemList = document.querySelector(".chat-conversation-list");

        if (response.status === "success") {
          itemList.insertAdjacentHTML(
            "beforeend",
            '<li class="chat-list left" id="chat-list-' +
              messageIds +
              '" >\
                    <div class="conversation-list">\
                        <div class="user-chat-content">\
                            <div class="ctext-wrap">\
                                <div class="ctext-wrap-content">\
                                    <p class="mb-0 ctext-content">\
                                        ' +
              response.message +
              '\
                                    </p>\
                                </div>\
                                <div class="dropdown align-self-start message-box-drop">\
                                    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
                                        <i class="ri-more-2-fill"></i>\
                                    </a>\
                                    <div class="dropdown-menu">\
                                        <a class="dropdown-item reply-message" href="#"><i class="ri-reply-line me-2 text-muted align-bottom"></i>Reply</a>\
                                        <a class="dropdown-item" href="#"><i class="ri-share-line me-2 text-muted align-bottom"></i>Forward</a>\
                                        <a class="dropdown-item copy-message" href="#""><i class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>\
                                        <a class="dropdown-item" href="#"><i class="ri-bookmark-line me-2 text-muted align-bottom"></i>Bookmark</a>\
                                        <a class="dropdown-item delete-item" href="#"><i class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>\
                                    </div>\
                                </div>\
                            </div>\
                            <div class="conversation-name">\
                                <small class="text-muted time">' +
              currentTime() +
              '</small>\
                            <span class="text-success check-message-icon"><i class="bx bx-check"></i></span>\
                        </div>\
                    </div>\
                </div>\
            </li>'
          );
        }
      },
      error: function (xhr, status, error) {
        console.log("An error occurred: " + error);
        lastMsg
          .querySelector(".check-message-icon i")
          .classList.remove("bx-check-double");
        lastMsg
          .querySelector(".check-message-icon i")
          .classList.add("bx-check");
      },
    });
  }

  var messageboxcollapse = 0;

  //message with reply
  var getReplyChatList = function (chatReplyId, chatReplyItems) {
    var chatReplyUser = document.querySelector(
      ".replyCard .replymessage-block .flex-grow-1 .conversation-name"
    ).innerHTML;
    var chatReplyMessage = document.querySelector(
      ".replyCard .replymessage-block .flex-grow-1 .mb-0"
    ).innerText;

    messageIds++;
    var chatreplyConList = document.getElementById(chatReplyId);
    var itemReplyList = chatreplyConList.querySelector(
      ".chat-conversation-list"
    );
    if (chatReplyItems != null) {
      itemReplyList.insertAdjacentHTML(
        "beforeend",
        '<li class="chat-list right" id="chat-list-' +
          messageIds +
          '" >\
                <div class="conversation-list">\
                    <div class="user-chat-content">\
                        <div class="ctext-wrap">\
                            <div class="ctext-wrap-content">\
                            <div class="replymessage-block mb-0 d-flex align-items-start">\
                        <div class="flex-grow-1">\
                            <h5 class="conversation-name">' +
          chatReplyUser +
          '</h5>\
                            <p class="mb-0">' +
          chatReplyMessage +
          '</p>\
                        </div>\
                        <div class="flex-shrink-0">\
                            <button type="button" class="btn btn-sm btn-link mt-n2 me-n3 font-size-18">\
                            </button>\
                        </div>\
                    </div>\
                                <p class="mb-0 ctext-content mt-1">\
                                    ' +
          chatReplyItems +
          '\
                                </p>\
                            </div>\
                            <div class="dropdown align-self-start message-box-drop">\
                                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
                                    <i class="ri-more-2-fill"></i>\
                                </a>\
                                <div class="dropdown-menu">\
                                    <a class="dropdown-item reply-message" href="#"><i class="ri-reply-line me-2 text-muted align-bottom"></i>Reply</a>\
                                    <a class="dropdown-item" href="#"><i class="ri-share-line me-2 text-muted align-bottom"></i>Forward</a>\
                                    <a class="dropdown-item copy-message" href="#"><i class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>\
                                    <a class="dropdown-item" href="#"><i class="ri-bookmark-line me-2 text-muted align-bottom"></i>Bookmark</a>\
                                    <a class="dropdown-item delete-item" href="#"><i class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>\
                            </div>\
                        </div>\
                    </div>\
                    <div class="conversation-name">\
                        <small class="text-muted time">' +
          currentTime() +
          '</small>\
                        <span class="text-success check-message-icon"><i class="bx bx-check"></i></span>\
                    </div>\
                </div>\
            </div>\
        </li>'
      );
      messageboxcollapse++;
    }

    // remove chat list
    var newChatList = document.getElementById("chat-list-" + messageIds);
    newChatList.querySelectorAll(".delete-item").forEach(function (subitem) {
      subitem.addEventListener("click", function () {
        itemList.removeChild(newChatList);
      });
    });

    //Copy Clipboard alert
    newChatList.querySelectorAll(".copy-message").forEach(function (subitem) {
      subitem.addEventListener("click", function () {
        document.getElementById("copyClipBoard").style.display = "block";
        document.getElementById("copyClipBoardChannel").style.display = "block";
        setTimeout(hideclipboardNew, 1000);

        function hideclipboardNew() {
          document.getElementById("copyClipBoard").style.display = "none";
          document.getElementById("copyClipBoardChannel").style.display =
            "none";
        }
      });
    });

    newChatList.querySelectorAll(".reply-message").forEach(function (subitem) {
      subitem.addEventListener("click", function () {
        var replyMessage =
          subitem.closest(".ctext-wrap").children[0].children[0].innerText;
        var replyuser = document.querySelector(
          ".user-chat-topbar .text-truncate .username"
        ).innerHTML;
        document.querySelector(
          ".replyCard .replymessage-block .flex-grow-1 .mb-0"
        ).innerText = replyMessage;
        var msgOwnerName = subitem.closest(".chat-list")
          ? subitem.closest(".chat-list").classList.contains("left")
            ? replyuser
            : "You"
          : replyuser;
        document.querySelector(
          ".replyCard .replymessage-block .flex-grow-1 .conversation-name"
        ).innerText = msgOwnerName;
      });
    });

    //Copy Message
    newChatList.querySelectorAll(".copy-message").forEach(function (subitem) {
      subitem.addEventListener("click", function () {
        newChatList.childNodes[1].children[1].firstElementChild.firstElementChild.getAttribute(
          "id"
        );
        isText =
          newChatList.childNodes[1].children[1].firstElementChild
            .firstElementChild.innerText;
        navigator.clipboard.writeText(isText);
      });
    });
  };

  var emojiPicker = new FgEmojiPicker({
    trigger: [".emoji-btn"],
    removeOnSelection: false,
    closeButton: true,
    position: ["top", "right"],
    preFetch: true,
    dir: "assets/js/pages/plugins/json",
    insertInto: document.querySelector(".chat-input"),
  });

  // emojiPicker position
  var emojiBtn = document.getElementById("emoji-btn");
  emojiBtn.addEventListener("click", function () {
    setTimeout(function () {
      var fgEmojiPicker = document.getElementsByClassName("fg-emoji-picker")[0];
      if (fgEmojiPicker) {
        var leftEmoji = window.getComputedStyle(fgEmojiPicker)
          ? window.getComputedStyle(fgEmojiPicker).getPropertyValue("left")
          : "";
        if (leftEmoji) {
          leftEmoji = leftEmoji.replace("px", "");
          leftEmoji = leftEmoji - 40 + "px";
          fgEmojiPicker.style.left = leftEmoji;
        }
      }
    }, 0);
  });
})();
//Search Message
function searchMessages() {
  var searchInput, searchFilter, searchUL, searchLI, a, i, txtValue;
  searchInput = document.getElementById("searchMessage");
  searchFilter = searchInput.value.toUpperCase();
  searchUL = document.getElementById("users-conversation");
  searchLI = searchUL.getElementsByTagName("li");
  Array.from(searchLI).forEach(function (search) {
    a = search.getElementsByTagName("p")[0]
      ? search.getElementsByTagName("p")[0]
      : "";
    txtValue = a.textContent || a.innerText ? a.textContent || a.innerText : "";
    if (txtValue.toUpperCase().indexOf(searchFilter) > -1) {
      search.style.display = "";
    } else {
      search.style.display = "none";
    }
  });
}

// chat-conversation
var scrollEl = new SimpleBar(document.getElementById("chat-conversation"));
scrollEl.getScrollElement().scrollTop =
  document.getElementById("users-conversation").scrollHeight;
