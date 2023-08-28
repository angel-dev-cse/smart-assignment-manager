<div id="chat-popup-template" class="chat-popup" style="display: none; position: fixed; bottom: -30px; right: -100px; z-index: 1000; width:600px;">
  <div class="container py-5">
    <div class="row">
      <div class="col-md-8">
        <div class="card shadow-sm border">
          <div class="card-header border-0 border-top border-5 rounded border-primary">
            <div class="row">
              <div class="col-lg-10">
                <h5 id="chat-header" class="mb-0">Chat messages</h5>
              </div>

              <div class="col-lg-2">
                <i id="close-chat-popup" class="mdi mdi-close-outline"></i>
              </div>
            </div>
          </div>
          <div id="message-container" class="card-body pb-0" data-mdb-perfect-scrollbar="true" style="position: relative; height:250px; overflow-y:scroll">
          </div>
          <div class="card-footer text-muted d-flex p-3">
            <div class="input-group mb-0 mr-0">
              <input type="text" id="message-input" class="form-control" placeholder="Type your message"
                aria-label="Recipient's username" aria-describedby="send-button" />
              <button class="btn btn-dark btn-sm active fw-bold" type="button" id="send-button" style="margin-left:-10px">
                Send
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>