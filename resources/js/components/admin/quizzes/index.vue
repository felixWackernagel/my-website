<script setup>
    import Bas from './../layouts/bas.vue';
    import Modal from './../../bootstrap/modal.vue';
    import { onMounted, ref } from 'vue';

    let quizzes = ref( [] );
    let errors = ref( null );
    const showModal = ref( false );
    const editMode = ref( false );

    let locations = ref( [] );

    let form = ref( {
        id: 0,
        number : null,
        quiz_winner : '',
        quiz_master : '',
        location_id : null,
        started_at : ''
    } );

    onMounted( async() => {
        getQuizzes();
        getLocations();
    } );

    const getQuizzes = async() => {
        let response = await axios.get( '/api/v1/quizzes/' );
        quizzes.value = response.data.quizzes;
    };

    const getLocations = async() => {
        let response = await axios.get( '/api/locations/' );
        locations.value = response.data.locations;
    };

    const openModal = () => {
        showModal.value = true;
    };

    const closeModal = () => {
        showModal.value = false;
        editMode.value = false;
        form.value = ( {} );
        form.value.location_id = null;
        errors.value = null;
    };

    const editModal = ( quiz ) => {
        editMode.value = true;
        showModal.value = true;
        form.value = quiz;
        form.value.location_id = quiz.location ? quiz.location.id : null;
    };

    const createQuiz = async () => {
        await axios.post( '/api/v1/quizzes/', form.value )
            .then( response => {
                errors.value = null;
                let quizNumber = form.value.number;
                getQuizzes();
                closeModal();
                Toast.fire( {
                    icon: 'success',
                    title: `Quiz #${quizNumber} wurde gespeichert.`
                } );
            } )
            .catch( error => {
                errors.value = error.response.data.errors;
            } );
    };

    const updateQuiz = async () => {
        await axios.put( `/api/v1/quizzes/${form.value.id}`, form.value )
            .then( response => {
                errors.value = null;
                let quizNumber = form.value.number;
                getQuizzes();
                closeModal();
                Toast.fire( {
                    icon: 'success',
                    title: `Quiz #${quizNumber} wurde aktualisiert.`
                } );
            } )
            .catch( error => {
                errors.value = error.response.data.errors;
            } );
    };

    const deleteQuiz = async (id, quizNumber) => {
        Swal.fire( {
            text: `Soll Quiz #${quizNumber} wirklich gelöscht werden?`,
            icon: 'question',
            showCancelButton: true,
            reverseButtons: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Löschen',
            cancelButtonText: 'Abbrechen'
        } )
        .then( result => {
            if( result.value ) {
                axios.delete( `/api/v1/quizzes/${id}` )
                    .then( response => {
                        
                        getQuizzes();
                        Toast.fire( {
                            icon: 'success',
                            title: `Quiz #${quizNumber} wurde gelöscht.`
                        } );
                    } );
            }
        } );      
    };

    const formatDateTime = ( dateTime ) => {
        if( dateTime == null || dateTime.length == 0 ) {
            return '';
        }

        if( dateTime.indexOf( 'T' ) > -1 ) {
            let values = dateTime.split( 'T' );
            let date = values[0].split( '-' );
            return `${date[2]}.${date[1]}.${date[0]} ${values[1]}`;
        }

        return dateTime;
    };
</script>
<template>
    <Bas>
        <div class="card shadow">
            <div class="card-header">
                <div class="actions">
                    <span class="h5 mb-0">Quizze</span>
                    <button type="button" class="btn btn-sm btn-success" @click="openModal()"><i class="bi bi-plus-lg"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive" v-if="quizzes.length > 0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Quiz-Master</th>
                                <th scope="col">Gewinner</th>
                                <th scope="col">Kneipe</th>
                                <th scope="col">Datum</th>
                                <th scope="col">Aktionen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="quiz in quizzes" :key="quiz.id">
                                <th scope="row">{{ quiz.number }}</th>
                                <td>{{ quiz.quiz_master }}</td>
                                <td>{{ quiz.quiz_winner }}</td>
                                <td>{{ quiz?.location?.name }}</td>
                                <td>{{ formatDateTime( quiz.started_at ) }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning me-3" @click="editModal( quiz )"><i class="bi bi-pencil"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger" @click="deleteQuiz( quiz.id, quiz.number )"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <Modal
            :custom_classes="[]" 
            v-model="showModal"
            @modal-closed="closeModal">
            <!-- <div v-for="(v, k) in errors" :key="k" v-if="errors">
                <div v-for="error in v" :key="error">{{ error }}</div>
            </div> -->
            <form novalidate>
                    <div class="mb-3 row">
                        <label for="number" class="col-sm-4 col-form-label">Nummer</label>
                        <div class="col-sm-8">
                            <input type="number" min="1" v-model="form.number" class="form-control" :class="{ 'is-invalid' : errors?.number }" id="number" required />
                            <div class="invalid-feedback" v-for="error in errors.number" :key="error" v-if="errors?.number">{{ error }}</div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="quiz_master" class="col-sm-4 col-form-label">Quiz-Master</label>
                        <div class="col-sm-8">
                            <input type="text" v-model="form.quiz_master" class="form-control" :class="{ 'is-invalid' : errors?.quiz_master }" id="quiz_master" />
                            <div class="invalid-feedback" v-for="error in errors.quiz_master" :key="error" v-if="errors?.quiz_master">{{ error }}</div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="quiz_winner" class="col-sm-4 col-form-label">Gewinner</label>
                        <div class="col-sm-8">
                            <input type="text" v-model="form.quiz_winner" class="form-control" :class="{ 'is-invalid' : errors?.quiz_winner }" id="quiz_winner" />
                            <div class="invalid-feedback" v-for="error in errors.quiz_winner" :key="error" v-if="errors?.quiz_winner">{{ error }}</div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="location" class="col-sm-4 col-form-label">Kneipe</label>
                        <div class="col-sm-8">
                            <select class="form-select" :class="{ 'is-invalid' : errors?.location }" id="location" v-model="form.location_id">
                                <option selected :value="null"></option>
                                <option v-for="location in locations" :value="location.id" :key="location.id">
                                    {{ location.name }}
                                </option>
                            </select>
                            <div class="invalid-feedback" v-for="error in errors.location" :key="error" v-if="errors?.location">{{ error }}</div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="started_at" class="col-sm-4 col-form-label">Datum</label>
                        <div class="col-sm-8">
                            <input type="datetime-local" v-model="form.started_at" class="form-control" :class="{ 'is-invalid' : errors?.started_at }" id="started_at" />
                            <div class="invalid-feedback" v-for="error in errors.started_at" :key="error" v-if="errors?.started_at">{{ error }}</div>
                        </div>
                    </div>
            </form>

            <template #title>
                <div v-show="!editMode">Quiz anlegen</div>
                <div v-show="editMode">Quiz bearbeiten</div>
            </template>
            <template #footer>
                <button @click.prevent="closeModal()" type="button" class="btn btn-secondary">Abbrechen</button>
                <button @click.prevent="createQuiz()" v-show="!editMode" type="button" class="btn btn-success">Anlegen</button>
                <button @click.prevent="updateQuiz()" v-show="editMode" type="button" class="btn btn-warning">Speichern</button>
            </template>
        </Modal>
    </Bas>
</template>
<style>
    .actions {
        display: flex;
        justify-content: space-between;
    }
</style>