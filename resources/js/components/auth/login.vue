<script setup>
    import { reactive, ref } from 'vue';
    import { useRouter } from 'vue-router';
    import { useUserStore } from './../../stores/user';

    const store = useUserStore();

    let router = useRouter();

    let form = reactive( {
        email: '',
        password: ''
    } );

    let errors = ref( null );

    const login = async () => {
        errors.value = null;

        await axios.get( '/sanctum/csrf-cookie' );
        await axios.post( '/api/login', form )
            .then( response => {
                if( response.data.success ) {
                    store.login( response.data.data.user );
                    router.push( '/admin/home' );
                } else {
                    errors.value = response.data.errors;
                }
            } )
            .catch( error => {
                errors.value = error.response.data.errors;
            } );
    };
</script>
<template>
    <div class="login-form">
        <form @submit.prevent="login">
            <div v-for="error in errors.message" :key="error" v-if="errors?.message">{{ error }}</div>
            <input class="email-field" type="email" name="email" placeholder="E-Mail" v-model="form.email" />
            <div v-for="error in errors.email" :key="error" v-if="errors?.email">{{ error }}</div>
            <br>
            <input class="password-field" type="password" name="password" placeholder="Password" v-model="form.password" />
            <div v-for="error in errors.password" :key="error" v-if="errors?.password">{{ error }}</div>
            <br>
            <input class="submit-button" type="submit" value="Login" />
        </form>
    </div>
</template>
<style>

</style>