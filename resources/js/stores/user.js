import { defineStore } from 'pinia';

export const useUserStore = defineStore( 'user', {
    state: () => ({ 
        user: null
    }),
    getters: {
      authorized: (state) => state.user != null,
      authorizedUser: (state) => state.user
    },
    actions: {
      login( user ) {
        this.user = user;
      },
      logout() {
        this.user = null;
      }
    },
  } );