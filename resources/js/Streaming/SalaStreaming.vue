<template>
  <div>
    <video></video>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Example Component</div>

          <div class="card-body">
            <div class="form-group" style="padding:50px">
              <label>Tu ID:</label>
              <br />
              <textarea class="form-control" id="yourId"></textarea>
              <br />
              <label>Otro ID:</label>
              <br />
              <textarea class="form-control" id="otherId"></textarea>
              <button class="btn btn-primary" id="connect">Conectar</button>
              <br />

              <label>Mensaje:</label>
              <br />
              <textarea class="form-control" id="yourMessage"></textarea>
              <button class="btn btn-primary" id="send">Enviar</button>
              <pre id="messages"></pre>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
var Peer = require("simple-peer");

var self;
var swal__;
var toast__;
export default {
  data() {
    return {
        video:null,
        items:[],
    };
  },
  mounted() {
      self = this;
      this.init();
  },
  methods: {
    cargarStreamer() {
      axios
        .get("streaming/cargar/")
        .then(response => {
          var data = response.data;
          self.items = data;
          console.log(data);
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    init() {
      this.video = document.querySelector("video");
      this.cargarStreamer();
    },
     gotMedia(stream) {
      this.peer1 = new Peer(
        { trickle: false }
      );

      this.peer1.on("signal", data => {
        //GET MY DATA ON THE
        let dat = JSON.stringify(data);
       /* if (location.hash === "#host") {
          this.token = dat;
          localStorage.setItem("stream", dat);
        } else {*/
          localStorage.setItem("cliente", dat);
      //  }
        document.getElementById("yourId").value = dat;
      });

      document.getElementById("connect").addEventListener("click", function() {
        let dat = localStorage.getItem("stream");
       /* if (location.hash === "#host") {
          let cl = localStorage.getItem("cliente");
          self.peer1.signal(JSON.parse(cl));
        } else {*/
          localStorage.setItem("cliente", dat);
          console.log(dat);
          self.peer1.signal(JSON.parse(dat));
       // }

        // alert('connect: ' + dat);
      });
      this.peer1.on("data", data => {
        document.getElementById("messages").textContent += data + "\n";
        console.log("got a message from peer1: " + data);
      });

      this.peer1.on("stream", stream => {
        // got remote video stream, now let's show it in a video tag
        var video = document.querySelector("video");
        if ("srcObject" in video) {
          video.srcObject = stream;
        } else {
          video.src = window.URL.createObjectURL(stream); // for older browsers
        }
        video.play();
      });
      document.getElementById("send").addEventListener("click", function() {
        var yourMessage = document.getElementById("yourMessage").value;
        console.log(
          "yourMessage JSON.stringify(data): " + JSON.stringify(yourMessage)
        );
        self.peer1.send(yourMessage);
      });
    },
  }
};
</script>