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
              <div class="col">
                <label for="token">Token</label>
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
              <div class="col">
                <label for="client_id">Client Id</label>
                <div class="input-group mb-3">
                  <input
                    v-model="user.client_id"
                    type="text"
                    class="form-control"
                    id="client_id"
                    name="client_id"
                    placeholder="Client Id..."
                  />
                </div>
              </div>
              <div class="col">
                <label for="secret">Secret</label>
                <div class="input-group mb-3">
                  <input
                    v-model="user.secret"
                    type="text"
                    class="form-control"
                    id="secret"
                    name="secret"
                    placeholder="Secret..."
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
                    id="updateUser"
                    name="updateUser"
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
                        :disabled="test.is_running == 1"
                      >
                        Start
                        <div
                          v-if="test.is_running == 1"
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

  mounted() {
    this.load();
  },

  methods: {
    load() {
      this.isLoading = true;
      axios
        .get(`token/show`)
        .then((res) => {
          this.user = res.data.user;
          this.tests = res.data.tests;
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
          test.status = res.status;
        })
        .catch((error) => {
          test.status = error;
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
          //
        })
        .catch((error) => {
          //test.status = error;
        })
        .finally(() => {
            //
        });
    },

    status(test) {
        axios
        .post('status', test)
        .then((res) => {
          test.status = res.data.status;
          test.duration = res.data.duration;
          test.speed = res.data.hits - test.hits;
          test.hits = res.data.hits;
        })
        .catch((error) => {
          test.status = error;
        })
        .finally(() => {
            //
        });
    },

  },
};
</script>
