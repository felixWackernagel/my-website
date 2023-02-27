<script setup>
    import Header from './header.vue';
    import Sidebar from './sidebar.vue';
</script>
<template>
    <div class="bas">
        <Header />
        <div class="side-by-side">
            <Sidebar />
            <div class="content">
                <slot></slot>
            </div>
        </div>
    </div>
</template>
<style>
    @import './base.css';
</style>