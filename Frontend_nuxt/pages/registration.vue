<script setup>
import * as yup from "yup";

const registration = useRegistration();
const userProps = useUserData();

let dataUser = ref({});
let name = ref("");
let email = ref("");
let password = ref("");
let modal = ref(false);
let statusFetch = ref(false);

const schema = yup.object({
  email: yup.string().nullable().email().required(),
  password: yup.string().min(3).required(),
  name: yup.string().min(3).required(),
});

async function userRegistration() {
  statusFetch.value = true;
  const { data, status } = await useAsyncData(
    "users",
    () =>
      $fetch(`/api/create`, {
        method: "GET",
        baseURL: "http://127.0.0.1:8000",
        params: {
          name: name.value,
          email: email.value,
          password: password.value,
        },
      }),
    {
      watch: [name, email, password],
    }
  );

  dataUser = data;
  userProps.value = data;

  const statusInterval = setInterval(stopLoader, 500);

  function stopLoader() {
    if (status.value === "success") {
      statusFetch.value = false;
      return clearInterval(statusInterval);
    }
  }

  if (data.value.status === true) {
    statusFetch.value = false;
    registration.value = "true";
    navigateTo("/");
    return;
  } else {
    statusFetch.value = false;
    modal.value = true;
    return;
  }
}

function crossToLogin() {
  navigateTo("login");
}
</script>

<template>
  <div class="flex flex-col items-center">
    <h1 class="text-5xl font-bold p-5 m-auto text-center">Sign Up</h1>
    <Form
      :validation-schema="schema"
      @submit="userRegistration"
      class="flex flex-col w-full max-w-3xl p-7 bg-gray-100 border-2 border-solid border-blue-600 rounded-lg"
    >
      <label class="label" for="">
        <p class="pb-2">Enter your Name</p>
        <Field
          v-model="name"
          name="name"
          type="text"
          placeholder="name"
          class="w-full p-2 bg-blue-100 placeholder-red-900 border-2 border-solid border-blue-200 rounded"
        />
        <ErrorMessage name="name" v-slot="{ message }">
          <p class="text-red-600">{{ message }}</p>
        </ErrorMessage>
      </label>
      <label class="label" for="">
        <p class="pb-2">Enter your Email</p>
        <Field
          v-model="email"
          name="email"
          type="email"
          placeholder="email"
          class="w-full p-2 bg-blue-100 placeholder-red-900 border-2 border-solid border-blue-200 rounded"
        />
        <ErrorMessage name="email" v-slot="{ message }">
          <p class="text-red-600">{{ message }}</p>
        </ErrorMessage>
      </label>
      <label class="label" for="">
        <p class="pb-2">Enter your Password</p>
        <Field
          v-model="password"
          name="password"
          type="password"
          placeholder="password"
          class="w-full p-2 bg-blue-100 placeholder-red-900 border-2 border-solid border-blue-200 rounded"
        />
        <ErrorMessage name="password" v-slot="{ message }">
          <p class="text-red-600">{{ message }}</p>
        </ErrorMessage>
      </label>
      <button class="mb-5 p-2 bg-green-300 rounded" type="submit">
        <span>registration</span>
      </button>
    </Form>
    <button @click="crossToLogin" class="p-3 text-blue-800">
      <span>Have an account?</span>
      <span>login</span>
    </button>
    <Teleport to="body">
      <div v-if="modal" class="modal">
        <div class="content flex flex-col justify-between">
          <div class="flex flex-col justify-between">
            <div class="w-full mb-5 text-right">
              <button class="w-6" @click="modal = false">
                <img src="/close.png" alt="" />
              </button>
            </div>
            <div class="flex mb-3 items-center">
              <img class="w-9 mr-5" src="/error.png" alt="icon" />
              <h2 class="text-2xl">Registration failed</h2>
            </div>
            <p class="w-2/3 mb-16">{{ dataUser.error }}</p>
            <button
              @click="modal = false"
              class="p-3 text-blue-800 mt-6 p-2 bg-green-300 rounded"
              type="submit"
            >
              <span>Close</span>
            </button>
          </div>
        </div>
      </div>
    </Teleport>
    <Loader v-if="statusFetch" />
  </div>
</template>

<style scoped>
.label {
  height: 110px;
  padding-bottom: 5px;
}
.modal {
  position: absolute;
  top: 0;
  width: 100%;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: rgba(0, 0, 0, 0.5);
}
.content {
  width: 500px;
  height: 350px;
  padding: 25px;
  border-radius: 0.3rem;
  background-color: #fff;
}
</style>
