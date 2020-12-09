<template>
  <div class="container">
    <div class="row align-items-center justify-content-center" v-if="isLoading">
      <div
        class="row align-items-center justify-content-center spinner-grow text-secondary"
        role="status"
      >
        <span class="sr-only">Loading...</span>
      </div>
    </div>
    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-header">Settings</div>

          <div class="card-body">
            <div class="row">
              <div class="col">
                <label for="name">Token</label>
                <div class="input-group mb-3">
                  <input
                    v-model="token"
                    type="text"
                    class="form-control"
                    id="token"
                    name="token"
                    placeholder="Token..."
                  />
                  <button
                    id="updateToken"
                    name="updateToken"
                    class="btn btn-primary"
                    v-on:click="update"
                  >
                    <i class="fas fa-save"></i> Save
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <br />

    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-header">Tests</div>

          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Name</th>
                  <th scope="col">Rate (Hits per second)</th>
                  <th scope="col"></th>
                  <th scope="col">Status</th>
                  <th scope="col">Hits</th>
                  <th scope="col">Duration (ms)</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="test in tests" :key="test.id">
                  <th scope="row">{{ test.id }}</th>
                  <td>{{ test.name }}</td>
                  <td>
                    <input
                      v-model="test.rate"
                      type="number"
                      class="form-control"
                      placeholder="Rate..."
                      step="1"
                      min="1"
                    />
                  </td>
                  <td>
                    <div
                      class="btn-group"
                      role="group"
                      aria-label="Basic example"
                    >
                      <button
                        type="button"
                        class="btn btn-success"
                        v-on:click="start(test)"
                        :disabled="test.is_running"
                      >
                        Start
                        <div
                          v-if="test.is_running"
                          class="spinner-border spinner-border-sm"
                          role="status"
                        ></div>
                      </button>
                      <button
                        type="button"
                        class="btn btn-danger"
                        v-on:click="stop(test)"
                        :disabled="!test.is_running"
                      >
                        Stop
                      </button>
                    </div>
                  </td>
                  <td>{{ test.status }}</td>
                  <td>{{ test.hits }}</td>
                  <td>{{ test.duration }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      isLoading: true,
      token: null,
      tests: [
        {
          id: 0,
          name: "Get Balance",
          rate: 1,
          type: "get",
          endpoint: `${process.env.MIX_MSAS_URL}/api/balance`,
          is_running: false,
          status: null,
          hits: 0,
          duration: null,
          timer: null,
          data: {},
        },
        {
          id: 1,
          name: "Verify Number",
          rate: 1,
          type: "post",
          endpoint: `${process.env.MIX_WEBAPP_URL}/api/v1/verify/number`,
          is_running: false,
          status: null,
          hits: 0,
          duration: null,
          timer: null,
          data: { to: "+35799000000" },
        },
        {
          id: 2,
          name: "Get Lists",
          rate: 1,
          type: "get",
          endpoint: `${process.env.MIX_WEBAPP_URL}/v1/people/lists`,
          is_running: false,
          status: null,
          hits: 0,
          duration: null,
          timer: null,
          data: { },
        },
        {
          id: 3,
          name: "Create a new list",
          rate: 1,
          type: "post",
          endpoint: `${process.env.MIX_WEBAPP_URL}/v1/people/lists/create`,
          is_running: false,
          status: null,
          hits: 0,
          duration: null,
          timer: null,
          data: { name: "Stress", description: "For stress test" },
        },
        {
          id: 4,
          name: "Get messages",
          rate: 1,
          type: "get",
          endpoint: `${process.env.MIX_MSMS_URL}/messages`,
          is_running: false,
          status: null,
          hits: 0,
          duration: null,
          timer: null,
          data: {  },
        },
        {
          id: 5,
          name: "XXX",
          rate: 1,
          type: "get",
          endpoint: `${process.env.MIX_MSMS_URL}/v1/balance`,
          is_running: false,
          status: null,
          hits: 0,
          duration: null,
          timer: null,
          data: {},
        },
      ],
    };
  },

  mounted() {
    this.load();
  },

  methods: {
    load() {
      this.isLoading = true;
      axios
        .get(`token/show`)
        .then((res) => {
          this.token = res.data;
        })
        .catch((error) => {
          //
        })
        .finally(() => {
          this.isLoading = false;
        });
    },

    update() {
      this.isLoading = true;
      axios
        .post(`token/update`, { token: this.token })
        .then((res) => {
          //
        })
        .catch((error) => {
          //
        })
        .finally(() => {
          this.isLoading = false;
        });
    },

    start(test) {
      test.is_running = true;
      let interval = 1000 / parseInt(test.rate);
      test.timer = setInterval(() => {
        switch (test.type) {
          case "get":
            this.get(test);
            break;
          case "post":
            this.post(test);
            break;
          default:
        }
      }, interval);
    },

    stop(test) {
      test.is_running = false;
      clearInterval(test.timer);
    },

    get(test) {
      var startTime = new Date().getTime();
      axios
        .get(test.endpoint, {
            headers: { Authorization: `Bearer ${this.token}` },
            })
        .then((res) => {
          this.tests[test.id].status = res.status;
        })
        .catch((error) => {
          this.tests[test.id].status = error;
        })
        .finally(() => {
          this.tests[test.id].hits = this.tests[test.id].hits + 1;
          var endTime = new Date().getTime();
          this.tests[test.id].duration = endTime - startTime;
        });
    },

    post(test) {
      var startTime = new Date().getTime();
      axios
        .post(test.endpoint, test.data, {headers: { Authorization: `Bearer ${this.token}` }})
        .then((res) => {
          this.tests[test.id].status = res.status;
        })
        .catch((error) => {
          this.tests[test.id].status = error;
        })
        .finally(() => {
          this.tests[test.id].hits = this.tests[test.id].hits + 1;
          var endTime = new Date().getTime();
          this.tests[test.id].duration = endTime - startTime;
        });
    },
  },
};
</script>
