<script setup lang="ts">
import { ref, onMounted, watch, computed } from 'vue';
import { Modal } from 'bootstrap';

const props = defineProps<{
  modelValue: boolean,
  custom_classes?: String[]
}>();

const emit = defineEmits<{
  (e: 'modalClosed' ): void
}>();

const modalRef = ref<HTMLElement | null>( null );
let modal: Modal;

onMounted( () => {
  if (modalRef.value) {
    modal = new Modal(modalRef.value)
    modalRef.value.addEventListener('hidden.bs.modal', event => {
      // closed modal via backdrop
      // modal is already dismissed but model value isn't updated
      if( props.modelValue ) {
        close();
      }
    });
  }
} );

watch( () => props.modelValue, (modelValue) => {
  // show or hide the modal after modelValue was changed outside of this component
  if (modelValue) {
    modal.show();
  } else {
    modal.hide();
  }
} );

function close() {
  emit('modalClosed');
}

const base_classes = ['modal', 'fade'];
const final_classes = computed( () => base_classes.concat( props.custom_classes ) );
</script>
<template>
    <div :class="final_classes" tabindex="-1" aria-hidden="true" ref="modalRef">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <slot name="title"></slot>
          </h5>
          <button type="button" class="btn-close" aria-label="Close" @click="close"></button>
        </div>
        <div class="modal-body">
          <slot />
        </div>
        <div class="modal-footer">
          <slot name="footer" />
        </div>
      </div>
    </div>
  </div>
</template>