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
     gotMedia() { 
    },
  }
};
</script>