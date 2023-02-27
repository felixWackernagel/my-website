<script setup>
    import Bas from './../layouts/bas.vue';
    import { onMounted, ref } from 'vue';

    let form = ref( {
        id : '',
        name : '',
        photo : '',
        website : '',
        phone : '',
        address : ''
    } );

    onMounted( async() => {
        getLocation();
    } );

    const getLocation = async() => {
        let response = await axios.get( '/api/edit_location' );
        form.value = response.data;
        console.log( 'response', form.value );
    };

    const getPhoto = () => {
        let photo = "/img/location.jpg";
        if( form.value.photo ) {
            if( form.value.photo.indexOf( 'base64' ) != -1 ) {
                photo = form.value.photo;
            } else {
                photo = '/img/upload/' + form.value.photo;
            }
        }
        return photo;
    };

    const changePhoto = (e) => {
        let file = e.target.files[0];
        let reader = new FileReader();
        let limit = 1024 * 1024 * 2; // 2MB
        if( file['size'] > limit ) {
            Swal.fire( {
                icon: 'error',
                title: 'Ooops...',
                text: 'You are uploading a large file'
            } );
            return false;
        }
        reader.onloadend = (file) => {
            form.value.photo = reader.result;
        };
        reader.readAsDataURL( file );
    };

    const updateLocation = async () => {
        await axios.post( `/api/update_location/${form.value.id}`, form.value )
            .then( response => {
                toast.fire( {
                    icon: 'success',
                    title: 'Update successfully'
                } );
            } );
    };
</script>
<template>
    <Bas>
        Locations

        <div>
            <input name="name" placeholder="Name" type="text" v-model="form.name" />
            <br />
            <input name="website" placeholder="Website" type="text" v-model="form.website" />
            <br />
            <input name="phone" placeholder="Phone" type="phone" v-model="form.phone" />
            <br />
            <input name="address" placeholder="Address" type="text" v-model="form.address" />
            <br />
            <img :src="getPhoto()" alt="Photo of the location" />
            <br />
            <input name="photo" type="file" id="photoFile" @change="changePhoto" />
            <br />
            <div class="btn" @click.prevent="updateLocation">Save changes</div>
        </div>
    </Bas>
</template>