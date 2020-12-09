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

    <div class="row" v-if="!isLoading">
      <div class="col">
        <div class="card">
          <div class="card-header">Settings</div>

          <div class="card-body">
            <div class="row">
              <div class="col-4">
                <label for="name">Token</label>
                <div class="input-group mb-3">
                  <input
                    v-model="user.token"
                    type="text"
                    class="form-control"
                    id="token"
                    name="token"
                    placeholder="Token..."
                  />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="name">Endpoint Web</label>
                <div class="input-group mb-3">
                  <input
                    v-model="user.endpoint_webapp"
                    type="text"
                    class="form-control"
                    id="endpoint_webapp"
                    name="endpoint_webapp"
                    placeholder="Url..."
                  />
                </div>
              </div>
              <div class="col">
                <label for="name">Endpoint Messages</label>
                <div class="input-group mb-3">
                  <input
                    v-model="user.endpoint_msms"
                    type="text"
                    class="form-control"
                    id="endpoint_msms"
                    name="endpoint_msms"
                    placeholder="Url..."
                  />
                </div>
              </div>
              <div class="col">
                <label for="name">Endpoint Auth</label>
                <div class="input-group mb-3">
                  <input
                    v-model="user.endpoint_msas"
                    type="text"
                    class="form-control"
                    id="endpoint_msas"
                    name="endpoint_msas"
                    placeholder="Url..."
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
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

    <br />

    <div class="row" v-if="!isLoading">
      <div class="col">
        <div class="card">
          <div class="card-header">Tests</div>

          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Name</th>
                  <th scope="col">Rate (Hits per sec)</th>
                  <th scope="col"></th>
                  <th scope="col">Status</th>
                  <th scope="col">Hits</th>
                  <th scope="col">Duration (sec)</th>
                  <th scope="col">Hits per sec</th>
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
                  <td>{{ test.speed }}</td>
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
      user: {id: 0, endpoint_webapp: null, endpoint_msas: null, endpoint_msms: null},
      tests: [],
    };
  },

  computed: {
    endpoint_webapp: function () {
      if (this.user.id == 0) {
          return null;
      }
      return this.user.endpoint_webapp;
    },
    endpoint_msms: function () {
      if (this.user.id == 0) {
          return null;
      }
      return this.user.endpoint_msms;
    },
    endpoint_msas: function () {
      if (this.user.id == 0) {
          return null;
      }
      return this.user.endpoint_msas;
    },
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
          this.user = res.data;
          this.setTests();
        })
        .catch((error) => {
          //
        })
        .finally(() => {
          this.isLoading = false;
        });
    },

    setTests() {
        this.tests = [
        {
          id: 0,
          name: "Get Balance",
          rate: 1,
          type: "get",
          endpoint: `${this.endpoint_msas}/api/balance`,
          is_running: false,
          status: null,
          hits: 0,
          duration: 0,
          speed: 0,
          timer: null,
          payload: {},
        },
        {
          id: 1,
          name: "Verify Number",
          rate: 1,
          type: "post",
          endpoint: `${this.endpoint_webapp}/api/v1/verify/number`,
          is_running: false,
          status: null,
          hits: 0,
          duration: 0,
          speed: 0,
          timer: null,
          payload: { to: "+35799000000" },
        },
        {
          id: 2,
          name: "Get Lists",
          rate: 1,
          type: "get",
          endpoint: `${this.endpoint_webapp}/v1/people/lists`,
          is_running: false,
          status: null,
          hits: 0,
          duration: 0,
          speed: 0,
          timer: null,
          payload: { },
        },
        {
          id: 3,
          name: "Create a new list",
          rate: 1,
          type: "post",
          endpoint: `${this.endpoint_webapp}/v1/people/lists/create`,
          is_running: false,
          status: null,
          hits: 0,
          duration: 0,
          speed: 0,
          timer: null,
          payload: { name: "Stress", description: "For stress test" },
        },
        {
          id: 4,
          name: "Get messages",
          rate: 1,
          type: "get",
          endpoint: `${this.endpoint_msms}/messages`,
          is_running: false,
          status: null,
          hits: 0,
          duration: 0,
          speed: 0,
          timer: null,
          payload: {  },
        }
      ];
    },

    update() {
      this.isLoading = true;
      axios
        .post(`token/update`, { user: this.user })
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
      axios
        .post('start', test)
        .then((res) => {
          this.tests[test.id].status = res.status;
        })
        .catch((error) => {
          this.tests[test.id].status = error;
        })
        .finally(() => {
            //
        });

        test.timer = setInterval(() => {
                this.status(test);
            }, 5000);
    },

    stop(test) {
        clearInterval(test.timer);
      test.is_running = false;
      axios
        .post('stop', test)
        .then((res) => {
          this.tests[test.id].status = res.status;
        })
        .catch((error) => {
          this.tests[test.id].status = error;
        })
        .finally(() => {
            //
        });
    },

    status(test) {
        axios
        .post('status', test)
        .then((res) => {
          this.tests[test.id].status = res.data.status;
          this.tests[test.id].duration = res.data.duration;
          this.tests[test.id].speed = res.data.hits - this.tests[test.id].hits;
          this.tests[test.id].hits = res.data.hits;
        })
        .catch((error) => {
          this.tests[test.id].status = error;
        })
        .finally(() => {
            //
        });
    }

  },
};
</script>
