<template>
    <a-form
        class="create-form"
        :model="formState"
        name="basic"
        :label-col="{ span: 8 }"
        :wrapper-col="{ span: 16 }"
        autocomplete="off"
        @finish="onFinish"
        @finishFailed="onFinishFailed"
    >
        <a-form-item class="form-label" label="User" name="user_id" :rules="[{ required: true, message: 'Please input your user!' }]">
            <a-select
                ref="select"
                v-model:value="formState.user_id"
                style="width: 250px; margin: 20px"
                :options="JSON.parse(users)"
                :field-names="{ label: 'name', value: 'id'}"
            />
        </a-form-item>

        <a-form-item class="form-label" label="Course" name="course_id" :rules="[{ required: true, message: 'Please input your course!' }]">
            <a-select
                ref="select"
                v-model:value="formState.course_id"
                style="width: 250px; margin: 20px"
                :options="JSON.parse(courses)"
                :field-names="{ label: 'title', value: 'id'}"
            />
        </a-form-item>

        <a-form-item class="form-label" label="Status" name="status" :rules="[{ required: true, message: 'Please input your status!' }]">
            <a-select
                ref="select"
                v-model:value="formState.status"
                style="width: 250px; margin: 20px"
                :options="JSON.parse(status)"
                :field-names="{ label: 'value', value: 'value'}"
            />
        </a-form-item>

        <a-form-item :wrapper-col="{ offset: 8, span: 16 }">
            <a-button type="primary" html-type="submit">Submit</a-button>
        </a-form-item>
    </a-form>
</template>
<script>

import { defineComponent, reactive } from 'vue';
export default defineComponent({
    props: ['courses', 'users', 'status'],
    setup() {
        const formState = reactive({
            user_id: '',
            course_id: '',
            status: '',
        });

        const onFinish = values => {
            axios
                .post('http://127.0.0.1:8000/enrollment', values)
                .then(response => {
                    if (response.data.status === 'success') {
                        window.location.href = '/';
                    }
                });
            console.log('Success:', values);
        };

        const onFinishFailed = errorInfo => {
            console.log('Failed:', errorInfo);
        };
        return {
            formState,
            onFinish,
            onFinishFailed,
        };
    },
});
</script>
<style>
    .create-form {
        width: 100%;
        margin: 0 auto;
        padding-top: 150px;
    }
    .form-label > div > label {
        margin-top: 15px; 
    }
</style>
