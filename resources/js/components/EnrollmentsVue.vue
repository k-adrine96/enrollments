<template>
  <a-button href="/enrollment/create" type="primary" class="create">Create Enrollment</a-button>
  <div class="search">
    <a-input v-model:value="search.title" placeholder="Course Name" />
    <a-input v-model:value="search.name" placeholder="User Name" />
    <a-input v-model:value="search.email" placeholder="Email" />
    <a-button type="primary" @click="onSearch()" class="btn-search">Search</a-button>
  </div>

  <a-table :dataSource="enrollments" :columns="columns" :@change="handleTableChange" class="table">
    <template #bodyCell="{ column, record }">
      <template v-if="column.key === 'action'">
        <a :href="`/enrollment/${record.key}/edit`" class="act edit">
          Edit
        </a>
        <a @click="onDelete(record.key)" class="act delete">
          Delete
        </a>
      </template>
    </template>
  </a-table>
</template>

<script>
  export default {
    props: ['data'],
    data() {
      return {
        enrollments: [],
        search: {
          name: '',
          email: '',
          title: ''
        },
      }
    },
    created() {
      this.enrollments = JSON.parse(this.data);
    },
    setup() {
      const handleTableChange = (sorter) => {
        run({
          sortField: sorter.field,
          sortOrder: sorter.order,
        });
      };
        return {
          handleTableChange,
          columns: [
            {
              title: 'Course name',
              dataIndex: 'coursename',
              key: 'coursename',
              sorter: true,
            },
            {
              title: 'User name',
              dataIndex: 'username',
              key: 'username',
              sorter: true,
            },
            {
              title: 'Status',
              dataIndex: 'status',
              key: 'status',
            },
            {
              title: 'Joining Date',
              dataIndex: 'joiningdate',
              key: 'joiningdate',
              sorter: true,
            },
            {
              title: 'Compliting Date',
              dataIndex: 'complitingdate',
              key: 'complitingdate',
              sorter: true,
            },
            {
              title: 'Action',
              key: 'action',
              scopedSlots: { customRender: 'action' },
            },
          ],
        };
      },
      methods: {
        onDelete (id) {
          axios
            .delete(`http://127.0.0.1:8000/enrollment/${id}`)
            .then(response => {
              if (response.data.status === 'success') {
                  location.reload()
                }
            });
        },
        onSearch () {
          const searchParams = new URLSearchParams(this.search).toString();
          axios
            .get(`http://127.0.0.1:8000/enrollment/search?${searchParams}`)
            .then(response => {
              if (response.data.status === 'success') {
                this.enrollments = JSON.parse(response.data.enrollments);
              }
            });
        }
      },
      name: 'EnrollmentsVue'
  }
</script>

<style>
  .create{
    padding: 25px;
    margin: 2% 5%;
  }
  .search {
    max-width: 30%;
    margin: 2% 5%;
  }
  .search > *{
    margin-top: 20px;
  }
  .btn-search {
    margin-bottom: 50px;
  }
  .act {
    padding: 5px;
  }
  .act.delete {
    color: red;
  }
  .table {
    max-width: 90%;
    margin: 0 auto;
  }
</style>