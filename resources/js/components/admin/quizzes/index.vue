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
                getQuizzes();
                closeModal();
                toast.fire( {
                    icon: 'success',
                    title: 'New Quiz successfully created.'
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
                getQuizzes();
                closeModal();
                toast.fire( {
                    icon: 'success',
                    title: 'Quiz successfully updated.'
                } );
            } )
            .catch( error => {
                errors.value = error.response.data.errors;
            } );
    };

    const deleteQuiz = async (id) => {
        Swal.fire( {
            title: 'Soll das Quiz gelöscht werden?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3885d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Löschen',
            cancelButtonText: 'Abbrechen'
        } )
        .then( result => {
            if( result.value ) {
                axios.delete( `/api/v1/quizzes/${id}` )
                    .then( response => {
                        getQuizzes();
                        toast.fire( {
                            icon: 'success',
                            title: 'Das Quiz wurde gelöscht.'
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
                                    <button type="button" class="btn btn-sm btn-danger" @click="deleteQuiz( quiz.id )"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <Modal
            :custom_classes="[]" 
            v-model="showModal">
            <div v-for="(v, k) in errors" :key="k" v-if="errors">
                <div v-for="error in v" :key="error">{{ error }}</div>
            </div>
            <form>
                <div>
                    <input name="number" placeholder="Number" type="number" min="1" v-model="form.number" />
                    <div v-for="error in errors.number" :key="error" v-if="errors?.number">{{ error }}</div>
                    <br />
                    <input name="quiz_master" placeholder="Quiz-Master" type="text" v-model="form.quiz_master" />
                    <div v-for="error in errors.quiz_master" :key="error" v-if="errors?.quiz_master">{{ error }}</div>
                    <br />
                    <input name="quiz_winner" placeholder="Winner" type="text" v-model="form.quiz_winner" />
                    <div v-for="error in errors.quiz_winner" :key="error" v-if="errors?.quiz_winner">{{ error }}</div>
                    <br />
                    <select name="location" v-model="form.location_id">
                        <option :value="null" selected="selected">---</option>
                        <option v-for="location in locations" :value="location.id" :key="location.id">
                            {{ location.name }}
                        </option>
                    </select>
                    <div v-for="error in errors.location" :key="error" v-if="errors?.location">{{ error }}</div>
                    <br />
                    <input name="started_at" placeholder="Date & Time" type="datetime-local" v-model="form.started_at" />
                    <div v-for="error in errors.started_at" :key="error" v-if="errors?.started_at">{{ error }}</div>
                </div>
                <div>
                    <button @click.prevent="closeModal()">Cancel</button>
                    
                </div>
            </form>

            <template #title>
                <div v-show="!editMode">New Quiz</div>
                <div v-show="editMode">Update Quiz</div>
            </template>
            <template #footer>
                <button @click="closeModal()" type="button" class="btn btn-secondary">Cancel</button>
                <button @click.prevent="createQuiz()" v-show="!editMode" type="button" class="btn btn-primary">Create</button>
                <button @click.prevent="updateQuiz()" v-show="editMode" type="button" class="btn btn-primary">Update</button>
            </template>
        </Modal>
    </Bas>
</template>
<style>
    .actions {
        display: flex;
        justify-content: space-between;
    }

    .quiz {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        align-items: center;
        gap: 20px;
    }

    .quiz-actions {
        margin-left: auto;
    }
</style>