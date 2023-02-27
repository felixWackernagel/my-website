<script setup>
    import { useRouter } from 'vue-router';
    import { useUserStore } from './../../../stores/user';

    const store = useUserStore();

    let router = useRouter();

    const logout = async () => {
        let response = await axios.post( '/api/logout' );
        if( response.data.success ) {
            store.logout();
            router.push( '/' );
        }
    }
</script>
<template>
    <nav>
        <ul>
            <li><router-link to="/">Home</router-link></li>
            <li><router-link :to="{ name: 'adminLocations' }">Locations</router-link></li>
            <li><router-link :to="{ name: 'adminQuizzes' }">Quizzes</router-link></li>
            <li><button @click="logout">Logout</button></li>
            <li v-if="store.authorized">
                <div>{{ store.authorizedUser.name }}</div>
            </li>
        </ul>
    </nav>
</template>