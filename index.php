<html>
  <head></head>
  <body>
    <h1>PHP SSE test</h1>
      Username: <input type="text" id="user_id" readonly>
      Message: <input type="text" id="text_value">
      <button id="send_message">Send Message</button>
    <button id="join_chat">Connect SSE</button>
    <h2>Server responses</h2>
    <ol id="list">
    </ol>

    <script>
      document.getElementById('user_id').value = Math.trunc(Math.random()*100)+'-'+Math.trunc(Math.random()*100)+'-'+Math.trunc(Math.random()*100)
      document.getElementById('join_chat').addEventListener('click',function(){
        connectToSSE();
      },false);
      document.getElementById('send_message').addEventListener('click',function(){
        sendMessage();
      },false);
      function sendMessage(){
        let postRequest = function() {
            if (this.readyState == 4 && this.status == 200) {
              // Typical action to be performed when the document is ready:
              const newElement = document.createElement("li");
              const eventList = document.getElementById("list");
              newElement.textContent = 'Message sent';
              eventList.appendChild(newElement);
            }
        };
        let requestBody = {
          message: document.getElementById('text_value').value,
          user_id: document.getElementById('user_id').value,
        }
        let requestConfig = { method: "POST" , url: "sendMessage.php" };
        request(requestConfig,postRequest,requestBody);
      }
      function request(config,complete,body){
        if(config && 'url' in config){
          config.method = config.method || 'GET';
          let xhttp = new XMLHttpRequest();
          xhttp.onreadystatechange = complete;
          xhttp.open(config.method, config.url, true);
          xhttp.send(JSON.stringify(body));
        } else {
          console.err('Url for request not defined')
        }
      }
      function connectToSSE(){
        var eventSource = new EventSource("chatPoll.php",{withCredentials:true});
        console.log('connection started',eventSource);
        const eventList = document.getElementById("list");
        eventSource.onmessage = (event) => {
          console.log('new message',event);
          
          const newElement = document.createElement("li");

          newElement.textContent = `${event.data}`;
          eventList.append(newElement);
        };
      }

    </script>
  </body>
</html>