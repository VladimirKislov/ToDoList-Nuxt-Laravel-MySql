<script setup lang="ts">
definePageMeta({
  middleware: "auth",
});

const userProps = useUserData();
const { data }: any = await useFetch(
  `http://127.0.0.1:8000/api/getListItem/${userProps.value.data.user_id}`
);
const edit: any = usePropsEditData();

let addTaskName = ref("");

const openAdd = ref(false);
let openEditMode = ref(false);

interface propListItem {
  id: string;
  name: string;
  status: string;
}

let items: Array<propListItem> | any = ref(data.value);

function editListItem(item: any) {
  edit.value = item;
}
async function handleAddTask() {
  const { data }: any = await useAsyncData("tasks", () =>
    $fetch(`/api/addTask`, {
      method: "GET",
      baseURL: "http://127.0.0.1:8000",
      params: {
        user_id: userProps.value.data.user_id,
        task_name: addTaskName.value,
      },
    })
  );
  items.value = data.value;
}
async function handleRemoveTask(item: any) {
  const { data }: any = await useAsyncData("tasks", () =>
    $fetch(`/api/removeTask`, {
      method: "GET",
      baseURL: "http://127.0.0.1:8000",
      params: {
        task_id: item.task_id,
        user_id: item.user_id,
      },
    })
  );
  items.value = data.value;
}
async function handleUpdateTask(item: any) {
  const { data }: any = await useAsyncData("tasks", () =>
    $fetch(`/api/updateTask`, {
      method: "GET",
      baseURL: "http://127.0.0.1:8000",
      params: {
        status: item.status,
        task_name: item.name === undefined ? item.task_name : item.name,
        task_id: item.task_id,
        user_id: item.user_id,
      },
    })
  );
  items.value = data.value;
}
function getClassForStatusName(status: any) {
  return {
        'new': status === 'new',
        'process': status === 'process',
        'completed': status === 'completed'
      };
}
</script>

<template>
  <div class="flex flex-col p-7">
    <h1 class="text-5xl font-bold mb-20 p-5">Todo List Application</h1>
    <div class="flex flex-col max-w-7xl w-full m-auto">
      <button
        class="ml-auto p-3 text-blue-800 border-2 border-solid border-blue-800 rounded"
        @click="openAdd = true"
      >
        add task
      </button>
      <div class="flex justify-between mb-6 pt-10 pb-3 pl-10 pr-10 border-b-2 border-black">
        <span class="w-20">#</span>
        <span class="w-96">Task Name</span>
        <span class="w-28">Status</span>
        <span class="w-20">Edit</span>
        <span class="w-20">Delete</span>
      </div>
      <ul>
        <li
          class="flex justify-between items-center mb-5 px-10"
          v-for="(item, index) of items"
          :key="index"
        >
          <p class="w-20">{{ index + 1 }}</p>
          <p class="w-96">
            {{ item.task_name }}
          </p>
          <p class="w-28" v-bind:class="getClassForStatusName(item.status)">{{ item.status }}</p>
          <button
            class="w-20 p-2 text-blue-800 bg-blue-100 border-2 border-solid border-blue-800 rounded"
            @click="
              () => {
                editListItem(item);
                openEditMode = true;
              }
            "
          >
            Edit
          </button>
          <button
            class="w-20 p-2 text-red-800 bg-red-100 border-2 border-solid border-red-800 rounded"
            @click="
              () => {
                handleRemoveTask(item);
              }
            "
          >
            Delete
          </button>
        </li>
      </ul>
    </div>
    <Teleport to="body">
      <div v-if="openAdd" class="modal">
        <div class="content flex flex-col justify-between">
          <div class="flex flex-col justify-between">
            <div class="w-full mb-5 text-right">
              <button class="w-6" @click="openAdd = false"><img src="/close.png" alt="" /></button>
            </div>
            <div class="flex mb-3 items-center">
              <img class="w-9" src="/task.png" alt="icon" />
              <h2 class="text-2xl">Create new Task</h2>
            </div>
            <p class="w-2/3">Enter the name of the task in the input field and click button Add</p>
          </div>
          <div class="flex justify-between">
            <label class="w-full mr-5" for="">
              <p>Enter name task</p>
              <input
                class="w-full p-2 border-2 border-black rounded"
                type="text"
                v-model="addTaskName"
              />
            </label>
            <button
              class="w-1/3 mt-6 p-2 bg-green-300 rounded"
              @click="
                () => {
                  openAdd = false;
                  handleAddTask();
                }
              "
            >
              Add
            </button>
          </div>
        </div>
      </div>
    </Teleport>
    <Teleport to="body">
      <div v-if="openEditMode" class="modal">
        <div class="content flex flex-col justify-between">
          <div class="flex flex-col justify-between">
            <div class="w-full mb-5 text-right">
              <button class="w-6" @click="openEditMode = false">
                <img src="/close.png" alt="" />
              </button>
            </div>
            <div class="flex mb-3 items-center">
              <img class="w-9" src="/task.png" alt="icon" />
              <h2 class="text-2xl">Edit Task</h2>
            </div>
            <p class="w-2/3">In this menu, you can change the Name of the Task and its Status</p>
          </div>
          <div>
            <label for="">
              <p>Edit name task</p>
              <input
                class="w-full p-2 border-2 border-black rounded"
                type="text"
                v-model="edit.name"
              />
            </label>
            <div class="flex justify-between items-center">
              <label for="">
                <p>Edit status task {{ edit.status }}</p>
                <select class="w-28" v-model="edit.status">
                  <option disabled value="">{{ edit.status }}</option>
                  <option>new</option>
                  <option>process</option>
                  <option>completed</option>
                </select>
              </label>
              <button
                class="w-1/3 mt-6 p-2 bg-green-300 rounded"
                @click="
                  () => {
                    handleUpdateTask(edit);
                    openEditMode = false;
                  }
                "
              >
                Save
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<style>
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
.new {
  background-color: blue;
  border-radius: 0.5rem;
  padding: 5px;
  text-align: center;
  color: #FFF;
}
.process {
  background-color: yellow;
  border-radius: 0.5rem;
  padding: 5px;
  text-align: center;
  color: #333;
}
.completed {
  background-color: green;
  border-radius: 0.5rem;
  padding: 5px;
  text-align: center;
  color: #FFF;
}
</style>
